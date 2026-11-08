/**
 * Muop Audio Compressor - Pure Client-Side Audio Optimization Engine
 * Nén âm thanh trực tiếp trên trình duyệt bằng Web Audio API & lamejs
 * Hoạt động 100% không phụ thuộc máy chủ, không cần FFmpeg trên hosting!
 */
(function(window) {
    'use strict';

    window.muopCompressAudioInBrowser = async function(audioSource, onProgress) {
        if (typeof lamejs === 'undefined' || typeof lamejs.Mp3Encoder === 'undefined') {
            throw new Error('Thư viện lamejs chưa được tải trên trang!');
        }

        const AudioContextClass = window.AudioContext || window.webkitAudioContext;
        if (!AudioContextClass) {
            throw new Error('Trình duyệt của bạn không hỗ trợ Web Audio API để xử lý âm thanh!');
        }

        // 1. Lấy dữ liệu dạng ArrayBuffer
        let arrayBuffer;
        let origSizeBytes = 0;

        if (typeof audioSource === 'string') {
            if (onProgress) onProgress({ phase: 'downloading', percent: 10, text: 'Đang tải file audio về trình duyệt...' });
            const resp = await fetch(audioSource);
            if (!resp.ok) {
                throw new Error('Không thể tải file audio từ máy chủ (Mã lỗi HTTP ' + resp.status + ')');
            }
            arrayBuffer = await resp.arrayBuffer();
            origSizeBytes = arrayBuffer.byteLength;
        } else if (audioSource instanceof Blob || audioSource instanceof File) {
            origSizeBytes = audioSource.size;
            if (onProgress) onProgress({ phase: 'reading', percent: 15, text: 'Đang đọc dữ liệu âm thanh...' });
            arrayBuffer = await audioSource.arrayBuffer();
        } else if (audioSource instanceof ArrayBuffer) {
            origSizeBytes = audioSource.byteLength;
            arrayBuffer = audioSource;
        } else {
            throw new Error('Định dạng nguồn audio không hợp lệ!');
        }

        if (onProgress) onProgress({ phase: 'decoding', percent: 30, text: 'Trình duyệt đang giải mã âm thanh...' });

        // 2. Giải mã bằng AudioContext
        const audioCtx = new AudioContextClass();
        let audioBuffer;
        try {
            audioBuffer = await audioCtx.decodeAudioData(arrayBuffer.slice(0));
        } catch (decodeErr) {
            throw new Error('Không thể giải mã dữ liệu âm thanh: ' + (decodeErr.message || decodeErr));
        } finally {
            if (audioCtx && typeof audioCtx.close === 'function') {
                audioCtx.close().catch(function() {});
            }
        }

        if (onProgress) onProgress({ phase: 'resampling', percent: 50, text: 'Đang chuyển đổi sang Mono 24kHz...' });

        // 3. Chuyển kênh sang Mono & Resample sang 24,000 Hz
        const targetSampleRate = 24000;
        const numChannels = audioBuffer.numberOfChannels;
        const origSampleRate = audioBuffer.sampleRate;
        const origLength = audioBuffer.length;

        // Trộn các kênh thành Mono Float32
        let monoFloat32;
        if (numChannels === 1) {
            monoFloat32 = audioBuffer.getChannelData(0);
        } else {
            monoFloat32 = new Float32Array(origLength);
            const ch0 = audioBuffer.getChannelData(0);
            const ch1 = audioBuffer.getChannelData(1);
            for (let i = 0; i < origLength; i++) {
                monoFloat32[i] = (ch0[i] + ch1[i]) / 2;
            }
        }

        // Resample sang 24kHz bằng Linear Interpolation (tối ưu giọng đọc truyện)
        let resampledFloat32;
        if (origSampleRate === targetSampleRate) {
            resampledFloat32 = monoFloat32;
        } else {
            const ratio = origSampleRate / targetSampleRate;
            const newLength = Math.round(origLength / ratio);
            resampledFloat32 = new Float32Array(newLength);
            for (let i = 0; i < newLength; i++) {
                const origIndex = i * ratio;
                const indexFloor = Math.floor(origIndex);
                const indexCeil = Math.min(indexFloor + 1, origLength - 1);
                const fraction = origIndex - indexFloor;
                resampledFloat32[i] = monoFloat32[indexFloor] * (1 - fraction) + monoFloat32[indexCeil] * fraction;
            }
        }

        // 4. Chuyển đổi Float32 [-1.0, 1.0] sang Int16 [-32768, 32767]
        const numSamples = resampledFloat32.length;
        const samplesInt16 = new Int16Array(numSamples);
        for (let i = 0; i < numSamples; i++) {
            const s = Math.max(-1, Math.min(1, resampledFloat32[i]));
            samplesInt16[i] = s < 0 ? s * 0x8000 : s * 0x7FFF;
        }

        if (onProgress) onProgress({ phase: 'encoding', percent: 65, text: 'Đang nén sang MP3 36kbps...' });

        // 5. Nén sang MP3 bằng lamejs (Mono, 24kHz, 36kbps)
        // 36kbps tại 24kHz cho chất lượng giọng đọc trong trẻo, mượt mà và cực kỳ nhẹ (chỉ ~15MB cho 1 giờ nghe)
        const mp3encoder = new lamejs.Mp3Encoder(1, targetSampleRate, 36);
        const mp3Data = [];
        const sampleBlockSize = 1152;

        for (let i = 0; i < numSamples; i += sampleBlockSize) {
            const chunk = samplesInt16.subarray(i, Math.min(i + sampleBlockSize, numSamples));
            const mp3buf = mp3encoder.encodeBuffer(chunk);
            if (mp3buf.length > 0) {
                mp3Data.push(mp3buf);
            }
            if (onProgress && (i % (sampleBlockSize * 80) === 0)) {
                const progressPct = 65 + Math.round((i / numSamples) * 30);
                onProgress({ phase: 'encoding', percent: progressPct, text: 'Đang nén MP3 (' + progressPct + '%)...' });
            }
        }

        const endBuf = mp3encoder.flush();
        if (endBuf.length > 0) {
            mp3Data.push(endBuf);
        }

        const compressedBlob = new Blob(mp3Data, { type: 'audio/mp3' });
        const newSizeBytes = compressedBlob.size;
        const savedBytes = Math.max(0, origSizeBytes - newSizeBytes);
        const savedPercent = origSizeBytes > 0 ? Math.round((savedBytes / origSizeBytes) * 100) : 0;

        if (onProgress) onProgress({ phase: 'done', percent: 100, text: 'Hoàn tất nén âm thanh!' });

        return {
            blob: compressedBlob,
            origSizeBytes: origSizeBytes,
            newSizeBytes: newSizeBytes,
            savedBytes: savedBytes,
            savedPercent: savedPercent
        };
    };

})(window);
