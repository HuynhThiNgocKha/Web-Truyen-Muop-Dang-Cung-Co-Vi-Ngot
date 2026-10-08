<?php
namespace Muop\Controllers;

use Muop\Core\Request;
use Muop\Core\Response;
use Muop\Models\Affiliate;

if (!defined('ABSPATH')) exit;

/**
 * Affiliate Controller
 * Manages tracking clicks and saving affiliate link settings
 */
class AffiliateController {
    /**
     * Track affiliate click and return destination URL
     */
    public static function track() {
        $platform   = Request::get('platform', 'shopee');
        $chapter_id = Request::getInt('chapter_id', 0);

        $result = Affiliate::trackClick($platform, $chapter_id);

        // Mark chapter as unlocked in cookie
        if ($chapter_id > 0) {
            $unlocked = isset($_COOKIE['muop_unlocked_chapters']) ? explode(',', $_COOKIE['muop_unlocked_chapters']) : array();
            if (!in_array((string) $chapter_id, $unlocked)) {
                $unlocked[] = $chapter_id;
                setcookie('muop_unlocked_chapters', implode(',', $unlocked), time() + 86400 * 30, COOKIEPATH, COOKIE_DOMAIN);
            }
        }

        return Response::success($result, 'Đã ghi nhận click tiếp thị liên kết');
    }

    /**
     * Update affiliate link settings (Admin only)
     */
    public static function saveSettings() {
        if (!current_user_can('administrator')) {
            return Response::error('Bạn không có quyền thay đổi liên kết tiếp thị!', 403);
        }

        $shopee = Request::get('shopee_url');
        $tiktok = Request::get('tiktok_url');

        if (empty($shopee) || empty($tiktok)) {
            return Response::error('Vui lòng điền đầy đủ cả 2 liên kết!', 400);
        }

        $updated = Affiliate::updateLinks($shopee, $tiktok);
        return Response::success($updated, 'Đã lưu cấu hình liên kết Shopee & TikTok thành công!');
    }
}
