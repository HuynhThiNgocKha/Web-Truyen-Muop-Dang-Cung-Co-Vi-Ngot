<?php
namespace Muop\Models;

if (!defined('ABSPATH')) exit;

/**
 * User Model
 * Handles readers, translators, admin roles, bookmarks, and revenue
 */
class User {
    /**
     * Find user by ID
     */
    public static function find($id) {
        $u = get_userdata($id);
        if (!$u) return null;

        return self::format($u);
    }

    /**
     * Get all translators
     */
    public static function getTranslators() {
        $users = get_users(array('role' => 'dich_gia'));
        return array_map(array(__CLASS__, 'format'), $users);
    }

    /**
     * Calculate monthly salary for a translator
     */
    public static function getSalary($user_id, $rate = 8, $month = null) {
        $month_str = $month ?: date('Y-m');
        $stories = get_posts(array(
            'post_type'      => 'truyen',
            'posts_per_page' => -1,
            'author'         => (int) $user_id
        ));

        $total_views = 0;
        foreach ($stories as $s) {
            $mv = (int) get_post_meta($s->ID, '_truyen_views_' . $month_str, true);
            $total_views += ($mv ?: (int) get_post_meta($s->ID, '_truyen_views', true));
        }

        return array(
            'total_stories' => count($stories),
            'total_views'   => $total_views,
            'rate'          => $rate,
            'amount'        => $total_views * $rate
        );
    }

    /**
     * Toggle lock/active status for user
     */
    public static function toggleStatus($user_id) {
        $current = get_user_meta($user_id, 'muop_user_status', true);
        $new_status = ($current === 'locked') ? 'active' : 'locked';
        update_user_meta($user_id, 'muop_user_status', $new_status);
        return $new_status;
    }

    /**
     * Toggle bookmark for story
     */
    public static function toggleBookmark($user_id, $story_id) {
        $bookmarks = (array) get_user_meta($user_id, 'muop_bookmarks', true) ?: array();
        $key = array_search($story_id, $bookmarks);

        if ($key !== false) {
            unset($bookmarks[$key]);
            $action = 'removed';
        } else {
            $bookmarks[] = (int) $story_id;
            $action = 'added';
        }

        update_user_meta($user_id, 'muop_bookmarks', array_values($bookmarks));
        return array(
            'action'    => $action,
            'bookmarks' => $bookmarks,
            'count'     => count($bookmarks)
        );
    }

    /**
     * Get user custom avatar
     */
    public static function getAvatar($user_id) {
        return get_user_meta($user_id, 'muop_user_avatar', true) ?: '';
    }

    /**
     * Update user profile information
     */
    public static function updateProfile($user_id, $data) {
        $update_args = array('ID' => (int) $user_id);

        if (!empty($data['display_name'])) {
            $update_args['display_name'] = sanitize_text_field($data['display_name']);
        }

        if (!empty($data['user_email'])) {
            $email = sanitize_email($data['user_email']);
            if (is_email($email)) {
                $exists = email_exists($email);
                if ($exists && $exists != $user_id) {
                    return new \WP_Error('email_exists', 'Địa chỉ email này đã được sử dụng bởi tài khoản khác!');
                }
                $update_args['user_email'] = $email;
            }
        }

        $res = wp_update_user($update_args);
        if (is_wp_error($res)) {
            return $res;
        }

        if (isset($data['bio'])) {
            update_user_meta($user_id, 'description', sanitize_textarea_field($data['bio']));
        }

        if (isset($data['avatar'])) {
            update_user_meta($user_id, 'muop_user_avatar', esc_url_raw($data['avatar']));
        }

        return self::find($user_id);
    }

    /**
     * Change user password
     */
    public static function changePassword($user_id, $old_password, $new_password) {
        $user = get_userdata($user_id);
        if (!$user) {
            return new \WP_Error('user_not_found', 'Không tìm thấy thông tin tài khoản!');
        }

        if (!wp_check_password($old_password, $user->user_pass, $user_id)) {
            return new \WP_Error('wrong_password', 'Mật khẩu hiện tại không chính xác!');
        }

        if (strlen($new_password) < 6) {
            return new \WP_Error('password_too_short', 'Mật khẩu mới phải có tối thiểu 6 ký tự!');
        }

        wp_set_password($new_password, $user_id);

        // Keep session active after password change
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id, true);

        return true;
    }

    /**
     * Format user entity
     */
    public static function format($user) {
        $roles = (array) $user->roles;
        $is_locked = (get_user_meta($user->ID, 'muop_user_status', true) === 'locked');
        $avatar = self::getAvatar($user->ID);
        $bio = get_user_meta($user->ID, 'description', true);

        return array(
            'id'           => $user->ID,
            'name'         => $user->display_name,
            'display_name' => $user->display_name,
            'user_login'   => $user->user_login,
            'user_email'   => $user->user_email,
            'roles'        => $roles,
            'is_admin'     => in_array('administrator', $roles),
            'is_translator'=> in_array('dich_gia', $roles),
            'is_reader'    => in_array('doc_gia', $roles),
            'is_locked'    => $is_locked,
            'avatar'       => $avatar,
            'avatar_char'  => mb_strtoupper(mb_substr($user->display_name, 0, 1, 'UTF-8')),
            'bio'          => $bio ?: '',
            'registered'   => date('d/m/Y', strtotime($user->user_registered))
        );
    }
}
