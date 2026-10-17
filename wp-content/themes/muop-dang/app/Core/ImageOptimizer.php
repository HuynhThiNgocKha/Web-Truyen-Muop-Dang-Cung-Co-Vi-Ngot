<?php
namespace Muop\Core;

if (!defined('ABSPATH')) exit;

/**
 * ImageOptimizer
 * 
 * Bộ công cụ tự động tối ưu hóa, nén hình ảnh và tạo định dạng WebP hiện đại
 * Kích hoạt tự động trên toàn bộ các kênh tải ảnh của website:
 * - Ảnh bìa truyện mới & chỉnh sửa truyện (Cover Image)
 * - Ảnh đại diện người dùng & nhóm dịch (Avatars)
 * - Ảnh hóa đơn chuyển khoản nhuận bút (Payment Proof Bills)
 * - Thư viện Media tiêu chuẩn WordPress (Media Library)
 * - Tự động xoay ảnh theo EXIF orientation & loại bỏ metadata/GPS thừa
 */
class ImageOptimizer {

    const MAX_WIDTH = 1600;
    const MAX_HEIGHT = 2400;
    const JPEG_QUALITY = 82;
    const WEBP_QUALITY = 82;
    const PNG_COMPRESSION = 8; // Thang đo 0 (không nén) - 9 (nén tối đa)

    /**
     * Khởi tạo các hook can thiệp của WordPress
     */
    public static function init() {
        // 1. Can thiệp trực tiếp ngay khi tệp được tải lên thư mục uploads
        add_filter('wp_handle_upload', array(__CLASS__, 'handleUploadFilter'), 10, 2);
        add_filter('wp_handle_sideload', array(__CLASS__, 'handleUploadFilter'), 10, 2);

        // 2. Tinh chỉnh bộ lọc nén chất lượng ảnh cốt lõi WordPress
        add_filter('jpeg_quality', array(__CLASS__, 'getJpegQuality'));
        add_filter('wp_editor_set_quality', array(__CLASS__, 'getEditorQuality'), 10, 2);

        // 3. Giới hạn ngưỡng kích thước ảnh gốc khổng lồ (thay vì 2560px mặc định)
        add_filter('big_image_size_threshold', array(__CLASS__, 'getBigImageThreshold'));

        // 4. Ưu tiên GD Editor hỗ trợ xử lý WebP nếu Imagick chưa cài đặt
        add_filter('wp_image_editors', array(__CLASS__, 'prioritizeImageEditors'));

        // 5. Tự động tối ưu và tạo bản WebP đồng hành cho mọi ảnh thumbnail (sub-sizes)
        add_filter('wp_generate_attachment_metadata', array(__CLASS__, 'handleAttachmentMetadata'), 10, 2);

        // 6. Các API AJAX hỗ trợ Quản trị viên
        add_action('wp_ajax_muop_get_image_opt_stats', array(__CLASS__, 'ajaxGetStats'));
        add_action('wp_ajax_muop_optimize_existing_library', array(__CLASS__, 'ajaxOptimizeExistingLibrary'));
    }

    public static function getJpegQuality() {
        return self::JPEG_QUALITY;
    }

    public static function getEditorQuality($quality, $mime_type) {
        if ($mime_type === 'image/webp') {
            return self::WEBP_QUALITY;
        }
        return self::JPEG_QUALITY;
    }

    public static function getBigImageThreshold() {
        return self::MAX_WIDTH;
    }

    public static function prioritizeImageEditors($editors) {
        if (extension_loaded('gd')) {
            $key = array_search('WP_Image_Editor_GD', $editors);
            if ($key !== false) {
                unset($editors[$key]);
                array_unshift($editors, 'WP_Image_Editor_GD');
            }
        }
        return $editors;
    }

    /**
     * Hook bộ lọc wp_handle_upload / wp_handle_sideload
     */
    public static function handleUploadFilter($upload, $context = 'upload') {
        if (!empty($upload['error']) || empty($upload['file'])) {
            return $upload;
        }

        $filePath = $upload['file'];
        if (!file_exists($filePath)) {
            return $upload;
        }

        // Tự động tối ưu hóa tệp vừa tải lên
        self::optimizeFile($filePath, array(
            'create_webp_companion' => true,
            'log_stat'              => true
        ));

        return $upload;
    }

    /**
     * Tối ưu hóa toàn diện một tệp hình ảnh
     *
     * @param string $filePath Đường dẫn tuyệt đối tới tệp ảnh
     * @param array  $options  Tùy chọn nâng cao
     * @return array|false     Thông tin kết quả tối ưu hoặc false nếu không thành công
     */
    public static function optimizeFile($filePath, $options = array()) {
        if (!file_exists($filePath) || !is_readable($filePath) || !is_writable($filePath)) {
            return false;
        }

        $originalSize = filesize($filePath);
        if ($originalSize < 1024) { // Bỏ qua tệp quá nhỏ < 1KB
            return false;
        }

        $imageInfo = @getimagesize($filePath);
        if (!$imageInfo) {
            return false;
        }

        $mime = $imageInfo['mime'];
        $width = $imageInfo[0];
        $height = $imageInfo[1];

        // Chỉ xử lý các định dạng raster thông dụng
        if (!in_array($mime, array('image/jpeg', 'image/png', 'image/webp'), true)) {
            return false;
        }

        // Tạm thời cấp phát đủ bộ nhớ để xử lý ảnh độ phân giải cao
        $currentMemory = ini_get('memory_limit');
        if (intval($currentMemory) < 256) {
            @ini_set('memory_limit', '256M');
        }

        $tmpOptimized = $filePath . '.opt_tmp';

        try {
            $image = null;
            switch ($mime) {
                case 'image/jpeg':
                    if (function_exists('imagecreatefromjpeg')) {
                        $image = @imagecreatefromjpeg($filePath);
                    }
                    break;
                case 'image/png':
                    if (function_exists('imagecreatefrompng')) {
                        $image = @imagecreatefrompng($filePath);
                    }
                    break;
                case 'image/webp':
                    if (function_exists('imagecreatefromwebp')) {
                        $image = @imagecreatefromwebp($filePath);
                    }
                    break;
            }

            if (!$image) {
                return false;
            }

            // 1. Tự động xoay ảnh theo EXIF Orientation (chụp từ điện thoại iPhone/Android)
            if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
                $exif = @exif_read_data($filePath);
                if (!empty($exif['Orientation'])) {
                    switch ($exif['Orientation']) {
                        case 3:
                            $rotated = imagerotate($image, 180, 0);
                            if ($rotated) { imagedestroy($image); $image = $rotated; }
                            break;
                        case 6:
                            $rotated = imagerotate($image, -90, 0);
                            if ($rotated) { 
                                imagedestroy($image); $image = $rotated;
                                $t = $width; $width = $height; $height = $t;
                            }
                            break;
                        case 8:
                            $rotated = imagerotate($image, 90, 0);
                            if ($rotated) {
                                imagedestroy($image); $image = $rotated;
                                $t = $width; $width = $height; $height = $t;
                            }
                            break;
                    }
                }
            }

            // 2. Giới hạn kích thước tối đa và thu nhỏ theo tỷ lệ chuẩn
            $maxWidth = isset($options['max_width']) ? (int)$options['max_width'] : self::MAX_WIDTH;
            $maxHeight = isset($options['max_height']) ? (int)$options['max_height'] : self::MAX_HEIGHT;

            $workingImage = $image;
            $needsResize = ($width > $maxWidth || $height > $maxHeight);

            if ($needsResize && $width > 0 && $height > 0) {
                $ratio = min($maxWidth / $width, $maxHeight / $height);
                $newWidth = max(1, (int)round($width * $ratio));
                $newHeight = max(1, (int)round($height * $ratio));

                $canvas = imagecreatetruecolor($newWidth, $newHeight);
                if ($mime === 'image/png' || $mime === 'image/webp') {
                    imagealphablending($canvas, false);
                    imagesavealpha($canvas, true);
                    $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
                    imagefilledrectangle($canvas, 0, 0, $newWidth, $newHeight, $transparent);
                }

                imagecopyresampled($canvas, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                $workingImage = $canvas;
            }

            // 3. Nén và xuất tệp ảnh mới (tự động loại bỏ metadata EXIF và GPS thừa)
            $success = false;

            if ($mime === 'image/jpeg') {
                imageinterlace($workingImage, true); // Chế độ Progressive JPEG hiển thị mượt mà
                $success = @imagejpeg($workingImage, $tmpOptimized, self::JPEG_QUALITY);
            } elseif ($mime === 'image/png') {
                imagesavealpha($workingImage, true);
                $success = @imagepng($workingImage, $tmpOptimized, self::PNG_COMPRESSION);
            } elseif ($mime === 'image/webp') {
                $success = @imagewebp($workingImage, $tmpOptimized, self::WEBP_QUALITY);
            }

            // 4. Kiểm tra và ghi đè tệp gốc nếu kích thước tối ưu hơn hoặc đã được resize
            if ($success && file_exists($tmpOptimized)) {
                $newSize = filesize($tmpOptimized);
                if ($newSize < $originalSize || $needsResize) {
                    @unlink($filePath);
                    @rename($tmpOptimized, $filePath);
                    $finalSize = filesize($filePath);
                } else {
                    @unlink($tmpOptimized);
                    $finalSize = $originalSize;
                }
            } else {
                $finalSize = $originalSize;
            }

            // 5. Tự động tạo tệp WebP đồng hành (.webp) để tăng tốc tải trang
            $createWebp = isset($options['create_webp_companion']) ? $options['create_webp_companion'] : true;
            if ($createWebp && function_exists('imagewebp') && ($mime === 'image/jpeg' || $mime === 'image/png')) {
                $webpPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $filePath);
                if ($webpPath && $webpPath !== $filePath) {
                    @imagewebp($workingImage, $webpPath, self::WEBP_QUALITY);
                }
            }

            // 6. Giải phóng bộ nhớ GD
            if ($workingImage !== $image && (is_resource($workingImage) || (is_object($workingImage) && $workingImage instanceof \GdImage))) {
                @imagedestroy($workingImage);
            }
            if (is_resource($image) || (is_object($image) && $image instanceof \GdImage)) {
                @imagedestroy($image);
            }

            // 7. Ghi nhận nhật ký thống kê
            $bytesSaved = max(0, $originalSize - $finalSize);
            if (!empty($options['log_stat'])) {
                self::recordStat(basename($filePath), $originalSize, $finalSize, $bytesSaved);
            }

            return array(
                'original_size' => $originalSize,
                'final_size'    => $finalSize,
                'bytes_saved'   => $bytesSaved,
                'percent_saved' => $originalSize > 0 ? round(($bytesSaved / $originalSize) * 100, 1) : 0
            );

        } catch (\Throwable $e) {
            error_log('Muop ImageOptimizer Error: ' . $e->getMessage());
            if (file_exists($tmpOptimized)) {
                @unlink($tmpOptimized);
            }
            return false;
        }
    }

    /**
     * Tự động tạo WebP cho toàn bộ các kích thước thumbnail WordPress sinh ra
     */
    public static function handleAttachmentMetadata($metadata, $attachment_id) {
        if (empty($metadata) || !is_array($metadata)) {
            return $metadata;
        }

        $uploads = wp_upload_dir();
        $baseDir = $uploads['basedir'];

        if (!empty($metadata['sizes']) && is_array($metadata['sizes']) && !empty($metadata['file'])) {
            $fileSubdir = dirname($metadata['file']);
            $targetDir = ($fileSubdir && $fileSubdir !== '.') ? $baseDir . DIRECTORY_SEPARATOR . $fileSubdir : $baseDir;

            foreach ($metadata['sizes'] as $sizeKey => $sizeData) {
                if (!empty($sizeData['file'])) {
                    $subFilePath = $targetDir . DIRECTORY_SEPARATOR . $sizeData['file'];
                    if (file_exists($subFilePath)) {
                        self::optimizeFile($subFilePath, array(
                            'create_webp_companion' => true,
                            'log_stat'              => false
                        ));
                    }
                }
            }
        }

        return $metadata;
    }

    /**
     * Ghi nhận số liệu dung lượng tiết kiệm vào cơ sở dữ liệu
     */
    public static function recordStat($filename, $originalSize, $finalSize, $bytesSaved) {
        $stats = get_option('muop_image_opt_stats', array(
            'total_files'       => 0,
            'total_bytes_saved' => 0,
            'history'           => array()
        ));

        $stats['total_files'] = intval($stats['total_files']) + 1;
        $stats['total_bytes_saved'] = intval($stats['total_bytes_saved']) + $bytesSaved;

        $historyItem = array(
            'file'          => $filename,
            'original_size' => $originalSize,
            'final_size'    => $finalSize,
            'bytes_saved'   => $bytesSaved,
            'percent_saved' => $originalSize > 0 ? round(($bytesSaved / $originalSize) * 100, 1) : 0,
            'time'          => current_time('d/m/Y H:i:s')
        );

        if (!isset($stats['history']) || !is_array($stats['history'])) {
            $stats['history'] = array();
        }

        array_unshift($stats['history'], $historyItem);
        if (count($stats['history']) > 30) {
            $stats['history'] = array_slice($stats['history'], 0, 30);
        }

        update_option('muop_image_opt_stats', $stats);
    }

    /**
     * AJAX: Trả về thống kê tối ưu hình ảnh
     */
    public static function ajaxGetStats() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Bạn không có quyền xem thông tin này.'));
        }

        $stats = get_option('muop_image_opt_stats', array(
            'total_files'       => 0,
            'total_bytes_saved' => 0,
            'history'           => array()
        ));

        $mbSaved = round($stats['total_bytes_saved'] / (1024 * 1024), 2);

        wp_send_json_success(array(
            'total_files'       => number_format(intval($stats['total_files'])),
            'total_bytes_saved' => number_format($stats['total_bytes_saved']),
            'mb_saved'          => $mbSaved . ' MB',
            'history'           => !empty($stats['history']) ? $stats['history'] : array(),
            'webp_supported'    => function_exists('imagewebp'),
            'gd_supported'      => extension_loaded('gd')
        ));
    }

    /**
     * AJAX: Quét và nén hàng loạt các ảnh cũ trong uploads
     */
    public static function ajaxOptimizeExistingLibrary() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Bạn không có quyền thực hiện chức năng này.'));
        }

        check_ajax_referer('muop_admin_nonce', 'nonce');

        $uploads = wp_upload_dir();
        $baseDir = $uploads['basedir'];

        $files = self::scanImageFiles($baseDir, 15); // Xử lý từng đợt 15 ảnh để tránh timeout
        $optimizedCount = 0;
        $batchBytesSaved = 0;

        foreach ($files as $file) {
            $res = self::optimizeFile($file, array(
                'create_webp_companion' => true,
                'log_stat'              => true
            ));
            if ($res && !empty($res['bytes_saved'])) {
                $optimizedCount++;
                $batchBytesSaved += $res['bytes_saved'];
            }
        }

        $stats = get_option('muop_image_opt_stats', array(
            'total_files'       => 0,
            'total_bytes_saved' => 0,
            'history'           => array()
        ));

        $mbSaved = round($stats['total_bytes_saved'] / (1024 * 1024), 2);

        wp_send_json_success(array(
            'message'           => 'Đã quét và nén thành công ' . $optimizedCount . ' ảnh trong thư viện.',
            'batch_optimized'   => $optimizedCount,
            'batch_saved_kb'    => round($batchBytesSaved / 1024, 1),
            'total_files'       => number_format(intval($stats['total_files'])),
            'mb_saved'          => $mbSaved . ' MB',
            'history'           => !empty($stats['history']) ? $stats['history'] : array()
        ));
    }

    /**
     * Tìm kiếm các tệp ảnh cần tối ưu
     */
    private static function scanImageFiles($dir, $limit = 15) {
        $result = array();
        if (!is_dir($dir)) return $result;

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isFile()) {
                $ext = strtolower($item->getExtension());
                if (in_array($ext, array('jpg', 'jpeg', 'png'), true)) {
                    $path = $item->getPathname();
                    // Nếu chưa có tệp webp đồng hành
                    $webpCompanion = preg_replace('/\.(jpe?g|png)$/i', '.webp', $path);
                    if (!file_exists($webpCompanion) || $item->getSize() > 300 * 1024) {
                        $result[] = $path;
                        if (count($result) >= $limit) {
                            break;
                        }
                    }
                }
            }
        }

        return $result;
    }
}
