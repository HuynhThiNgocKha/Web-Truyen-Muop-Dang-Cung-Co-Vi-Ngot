<?php
namespace Muop\Controllers;

use Muop\Core\Request;
use Muop\Core\Response;
use Muop\Models\Story;
use Muop\Models\User;
use Muop\Models\Affiliate;

if (!defined('ABSPATH')) exit;

/**
 * Admin Controller
 * Manages admin system KPIs, permissions, user locking, and view mode switching
 */
class AdminController {
    /**
     * Get system KPI statistics
     */
    public static function stats() {
        if (!current_user_can('administrator')) {
            return Response::error('Chỉ Quản trị viên mới có thể xem thống kê!', 403);
        }

        $user_counts = count_users();
        $aff_stats   = Affiliate::getStats();
        $total_stories = wp_count_posts('truyen')->publish + wp_count_posts('truyen')->pending;

        // Total views for current month
        global $wpdb;
        $month_key = '_truyen_views_' . date('Y-m');
        $month_views = (int) $wpdb->get_var($wpdb->prepare(
            "SELECT SUM(meta_value) FROM $wpdb->postmeta WHERE meta_key = %s",
            $month_key
        ));

        return Response::success(array(
            'month_views'       => $month_views,
            'total_stories'     => $total_stories,
            'total_readers'     => isset($user_counts['avail_roles']['doc_gia']) ? $user_counts['avail_roles']['doc_gia'] : 0,
            'total_translators' => isset($user_counts['avail_roles']['dich_gia']) ? $user_counts['avail_roles']['dich_gia'] : 0,
            'shopee_clicks'     => $aff_stats['shopee_clicks'],
            'tiktok_clicks'     => $aff_stats['tiktok_clicks']
        ));
    }

    /**
     * Switch admin preview view mode (Admin, Translator, Reader)
     */
    public static function switchMode() {
        if (!current_user_can('administrator')) {
            return Response::error('Chỉ Admin mới có thể đổi chế độ xem!', 403);
        }

        $mode = Request::get('mode', 'admin');
        if (!in_array($mode, array('admin', 'dich_gia', 'doc_gia'))) {
            $mode = 'admin';
        }

        setcookie('muop_admin_preview_mode', $mode, time() + 86400 * 30, COOKIEPATH, COOKIE_DOMAIN);
        return Response::success(array('mode' => $mode), 'Đã chuyển sang chế độ xem: ' . $mode);
    }

    /**
     * Lock or unlock user account
     */
    public static function toggleUser() {
        if (!current_user_can('administrator')) {
            return Response::error('Bạn không có quyền quản lý người dùng!', 403);
        }

        $user_id = Request::getInt('user_id');
        if (!$user_id || $user_id === get_current_user_id()) {
            return Response::error('Thao tác không hợp lệ trên tài khoản này!', 400);
        }

        $new_status = User::toggleStatus($user_id);
        $msg = ($new_status === 'locked') ? 'Đã khóa tài khoản thành công!' : 'Đã mở khóa tài khoản thành công!';

        return Response::success(array('user_id' => $user_id, 'status' => $new_status), $msg);
    }
}
