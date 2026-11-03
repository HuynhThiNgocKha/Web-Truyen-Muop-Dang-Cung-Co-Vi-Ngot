#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Voice Clone TTS Engine - Bắt chước giọng nói từ file âm thanh mẫu
Mướp Đắng Web Truyện
"""

import os
import sys
import argparse
import json
import re
import tempfile
import subprocess
import numpy as np

# Ensure stdout uses UTF-8
if sys.stdout.encoding != 'utf-8':
    try:
        sys.stdout.reconfigure(encoding='utf-8')
    except Exception:
        pass

try:
    from pydub import AudioSegment
    from gtts import gTTS
except ImportError as e:
    print(json.dumps({"success": False, "error": f"Thiếu thư viện Python cần thiết: {str(e)}"}))
    sys.exit(1)


def clean_html_text(raw_text):
    """Làm sạch HTML tags và ký tự dư thừa khỏi nội dung chương."""
    if not raw_text:
        return ""
    # Chuyển đổi ngắt dòng <p>, <br> thành dấu chấm ngắt câu
    text = re.sub(r'<(p|br|div|h[1-6])[^>]*>', '\n', raw_text, flags=re.IGNORECASE)
    # Loại bỏ toàn bộ HTML tags còn lại
    text = re.sub(r'<[^>]+>', ' ', text)
    # Loại bỏ Markdown đậm, nghiêng, link
    text = re.sub(r'[*_~`#]', '', text)
    # Chuẩn hóa khoảng trắng và dấu cách
    lines = [line.strip() for line in text.split('\n') if line.strip()]
    cleaned = ' '.join(lines)
    cleaned = re.sub(r'\s+', ' ', cleaned).strip()
    return cleaned


def split_text_into_chunks(text, max_len=160):
    """Tách văn bản thành các đoạn ngắn tự nhiên theo dấu câu để tổng hợp mượt mà."""
    if not text:
        return []
    # Tách theo dấu ngắt câu ., !, ?, ;, \n
    sentences = re.split(r'([.!?;\n]+)', text)
    chunks = []
    current_chunk = ""

    for part in sentences:
        if not part:
            continue
        if len(current_chunk) + len(part) < max_len:
            current_chunk += part
        else:
            if current_chunk.strip():
                chunks.append(current_chunk.strip())
            current_chunk = part

    if current_chunk.strip():
        chunks.append(current_chunk.strip())

    # Kiểm tra nếu có chunk nào quá dài (> 200 ký tự mà không có dấu câu), tách theo dấu phẩy
    final_chunks = []
    for c in chunks:
        if len(c) > max_len and ',' in c:
            sub = c.split(',')
            buf = ""
            for s in sub:
                if len(buf) + len(s) < max_len:
                    buf += (s + ",")
                else:
                    if buf.strip():
                        final_chunks.append(buf.strip())
                    buf = s + ","
            if buf.strip():
                final_chunks.append(buf.strip())
        else:
            final_chunks.append(c)

    return [c for c in final_chunks if len(c.strip()) > 0]


def analyze_reference_voice(ref_audio_path):
    """
    Phân tích đặc tính âm học của file giọng nói mẫu tải lên:
    - Cao độ cơ bản F0 (Pitch) qua autocorrelation
    - Phổ năng lượng theo dải tần số (Timbre / Vocal tract formant profile)
    """
    if not os.path.exists(ref_audio_path):
        return {
            "pitch_hz": 195.0,
            "pitch_ratio": 1.0,
            "eq_gains": [0, 0, 0, 0, 0],
            "tempo_factor": 1.0
        }

    try:
        seg = AudioSegment.from_file(ref_audio_path)
        # Lấy tối đa 60 giây đầu tiên để phân tích nhanh
        if len(seg) > 60000:
            seg = seg[:60000]

        seg = seg.set_channels(1).set_frame_rate(22050)
        samples = np.array(seg.get_array_of_samples(), dtype=np.float32)
        sr = 22050

        if len(samples) == 0:
            return {"pitch_hz": 195.0, "pitch_ratio": 1.0, "eq_gains": [0, 0, 0, 0, 0], "tempo_factor": 1.0}

        # Chuẩn hóa biên độ
        max_abs = np.max(np.abs(samples))
        if max_abs > 0:
            samples = samples / max_abs

        # 1. Trích xuất Pitch (F0) trên các khung tiếng nói (Voice Activity Detection)
        frame_len = int(0.04 * sr)  # 40ms
        hop_len = int(0.02 * sr)    # 20ms
        pitches = []

        min_lag = int(sr / 380)  # Max ~380Hz
        max_lag = int(sr / 75)   # Min ~75Hz

        active_frames = []

        for i in range(0, len(samples) - frame_len, hop_len):
            frame = samples[i:i + frame_len]
            energy = np.mean(frame ** 2)
            if energy < 0.008:
                continue

            active_frames.append(frame)

            # Autocorrelation
            corr = np.correlate(frame, frame, mode='full')
            corr = corr[len(corr) // 2:]

            if max_lag < len(corr):
                search_region = corr[min_lag:max_lag]
                if len(search_region) > 0:
                    lag = min_lag + np.argmax(search_region)
                    if corr[0] > 0 and (corr[lag] / corr[0]) > 0.32:
                        freq = sr / lag
                        if 75 <= freq <= 380:
                            pitches.append(freq)

        median_pitch = float(np.median(pitches)) if pitches else 195.0
        # Base pitch của giọng mẫu gTTS tiếng Việt nữ chuẩn khoảng 195Hz
        base_pitch = 195.0
        pitch_ratio = median_pitch / base_pitch

        # Giới hạn pitch ratio trong khoảng an toàn tự nhiên (0.60 đến 1.55)
        pitch_ratio = float(np.clip(pitch_ratio, 0.60, 1.55))

        # 2. Phân tích phổ năng lượng âm sắc (Timbre Profile / Formants)
        eq_gains = [0.0, 0.0, 0.0, 0.0, 0.0]
        if active_frames:
            fft_size = 1024
            specs = []
            for fr in active_frames[:60]:  # Lấy tối đa 60 active frames
                windowed = fr[:fft_size] * np.hanning(min(len(fr), fft_size))
                if len(windowed) < fft_size:
                    windowed = np.pad(windowed, (0, fft_size - len(windowed)))
                mag = np.abs(np.fft.rfft(windowed))
                specs.append(mag)

            avg_spec = np.mean(specs, axis=0)
            freqs = np.fft.rfftfreq(fft_size, d=1.0 / sr)

            def get_band_energy(f_min, f_max):
                mask = (freqs >= f_min) & (freqs <= f_max)
                if np.sum(mask) == 0:
                    return 0.001
                return float(np.mean(avg_spec[mask]))

            b1 = get_band_energy(100, 250)
            b2 = get_band_energy(250, 600)
            b3 = get_band_energy(600, 1800)
            b4 = get_band_energy(1800, 3800)
            b5 = get_band_energy(3800, 8000)

            total_energy = b1 + b2 + b3 + b4 + b5 + 1e-6
            ratios = [b1 / total_energy, b2 / total_energy, b3 / total_energy, b4 / total_energy, b5 / total_energy]
            defaults = [0.25, 0.30, 0.25, 0.14, 0.06]
            for idx in range(5):
                db_diff = 10.0 * np.log10(max(ratios[idx], 1e-4) / max(defaults[idx], 1e-4))
                eq_gains[idx] = float(np.clip(db_diff * 0.85, -7.0, 7.0))

        return {
            "pitch_hz": round(median_pitch, 1),
            "pitch_ratio": round(pitch_ratio, 4),
            "eq_gains": [round(g, 1) for g in eq_gains],
            "tempo_factor": 1.0
        }

    except Exception as e:
        return {
            "pitch_hz": 195.0,
            "pitch_ratio": 1.0,
            "eq_gains": [0, 0, 0, 0, 0],
            "tempo_factor": 1.0,
            "error": str(e)
        }


def synthesize_chapter_audio(text, ref_audio_path, output_mp3_path, speed=1.0):
    """
    Quy trình tổng hợp giọng nói bắt chước giọng mẫu:
    1. Làm sạch và cắt đoạn văn bản.
    2. Phân tích âm học file giọng mẫu (Pitch + Timbre EQ).
    3. Tạo âm thanh tiếng Việt cơ sở từ các đoạn câu.
    4. Điều chế âm học (Voice Morphing & Spectral Timbre Matching) qua FFmpeg.
    5. Xuất file MP3 nén tối ưu.
    """
    cleaned_text = clean_html_text(text)
    if not cleaned_text or len(cleaned_text) < 5:
        raise ValueError("Văn bản chương truyện quá ngắn hoặc không có nội dung chữ hợp lệ!")

    chunks = split_text_into_chunks(cleaned_text, max_len=180)
    if not chunks:
        raise ValueError("Không thể chia đoạn văn bản chương truyện!")

    # Phân tích giọng mẫu đưa lên
    voice_profile = analyze_reference_voice(ref_audio_path)
    pitch_ratio = voice_profile["pitch_ratio"]
    eq_gains = voice_profile["eq_gains"]

    temp_dir = tempfile.mkdtemp(prefix="muop_tts_")
    combined_audio = AudioSegment.empty()
    pause_audio = AudioSegment.silent(duration=220)  # Khoảng lặng 220ms giữa các câu

    try:
        # Tổng hợp từng chunk
        for idx, chunk in enumerate(chunks):
            chunk_file = os.path.join(temp_dir, f"chunk_{idx}.mp3")
            tts = gTTS(text=chunk, lang='vi', slow=False)
            tts.save(chunk_file)
            part_seg = AudioSegment.from_file(chunk_file)
            combined_audio += part_seg
            combined_audio += pause_audio

        raw_wav_path = os.path.join(temp_dir, "raw_synthesized.wav")
        combined_audio.export(raw_wav_path, format="wav")

        # Chuẩn bị bộ lọc FFmpeg để bắt chước giọng mẫu
        target_sample_rate = int(22050 * pitch_ratio)
        inverse_pitch_tempo = float(1.0 / pitch_ratio)
        final_tempo = float(np.clip(inverse_pitch_tempo * speed, 0.5, 2.0))

        filter_parts = [
            f"asetrate={target_sample_rate}",
            "aresample=24000",
            f"atempo={final_tempo:.4f}",
            "highpass=f=65",
            "lowpass=f=11500",
            f"equalizer=f=180:width_type=o:width=1.2:g={eq_gains[0]:.1f}",
            f"equalizer=f=400:width_type=o:width=1.2:g={eq_gains[1]:.1f}",
            f"equalizer=f=1200:width_type=o:width=1.2:g={eq_gains[2]:.1f}",
            f"equalizer=f=2800:width_type=o:width=1.2:g={eq_gains[3]:.1f}",
            f"equalizer=f=5500:width_type=o:width=1.2:g={eq_gains[4]:.1f}",
            "loudnorm=I=-16:TP=-1.5:LRA=11"
        ]
        filter_str = ",".join(filter_parts)

        # Đảm bảo thư mục đích tồn tại
        os.makedirs(os.path.dirname(os.path.abspath(output_mp3_path)), exist_ok=True)

        # Nén tối đa dung lượng MP3 cho giọng nói (Speech-Optimized: Mono 1 kênh, 24kHz, 40kbps LAME)
        ffmpeg_cmd = [
            "ffmpeg", "-y",
            "-i", raw_wav_path,
            "-af", filter_str,
            "-ac", "1",
            "-ar", "24000",
            "-c:a", "libmp3lame",
            "-b:a", "40k",
            output_mp3_path
        ]

        res = subprocess.run(ffmpeg_cmd, capture_output=True, text=True)
        if res.returncode != 0:
            simple_filter = f"asetrate={target_sample_rate},aresample=24000,atempo={final_tempo:.4f},highpass=f=65,lowpass=f=11500"
            ffmpeg_fallback = [
                "ffmpeg", "-y",
                "-i", raw_wav_path,
                "-af", simple_filter,
                "-ac", "1",
                "-ar", "24000",
                "-c:a", "libmp3lame",
                "-b:a", "40k",
                output_mp3_path
            ]
            subprocess.run(ffmpeg_fallback, check=True)

        final_seg = AudioSegment.from_file(output_mp3_path)
        duration_sec = round(len(final_seg) / 1000.0, 1)

        return {
            "success": True,
            "output_path": os.path.abspath(output_mp3_path),
            "duration": duration_sec,
            "text_length": len(cleaned_text),
            "chunks_count": len(chunks),
            "voice_profile": voice_profile
        }

    finally:
        try:
            for f in os.listdir(temp_dir):
                os.remove(os.path.join(temp_dir, f))
            os.rmdir(temp_dir)
        except Exception:
            pass


def main():
    parser = argparse.ArgumentParser(description="Mướp Đắng Voice Clone TTS Engine")
    parser.add_argument("--text-file", required=True, help="Đường dẫn file văn bản nội dung chương (UTF-8)")
    parser.add_argument("--ref-audio", required=True, help="Đường dẫn file âm thanh mẫu giọng đọc cần bắt chước")
    parser.add_argument("--output", required=True, help="Đường dẫn file MP3 đầu ra")
    parser.add_argument("--speed", type=float, default=1.0, help="Tốc độ đọc (mặc định 1.0)")

    args = parser.parse_args()

    if not os.path.exists(args.text_file):
        print(json.dumps({"success": False, "error": f"Không tìm thấy file văn bản: {args.text_file}"}, ensure_ascii=False))
        sys.exit(1)

    with open(args.text_file, 'r', encoding='utf-8', errors='ignore') as f:
        text = f.read()

    try:
        result = synthesize_chapter_audio(
            text=text,
            ref_audio_path=args.ref_audio,
            output_mp3_path=args.output,
            speed=args.speed
        )
        print(json.dumps(result, ensure_ascii=False))
        sys.exit(0)
    except Exception as e:
        print(json.dumps({"success": False, "error": str(e)}, ensure_ascii=False))
        sys.exit(1)


if __name__ == '__main__':
    main()
