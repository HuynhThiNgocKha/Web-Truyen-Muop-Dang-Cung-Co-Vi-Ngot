<?php
namespace Muop\Models;

if (!defined('ABSPATH')) exit;

/**
 * Affiliate Model
 * Manages Shopee and TikTok affiliate link URLs, click tracking, and logging
 */
class Affiliate {
    const OPT_SHOPEE_URL   = 'muop_affiliate_shopee_url';
    const OPT_TIKTOK_URL   = 'muop_affiliate_tiktok_url';
    const OPT_SHOPEE_COUNT = 'muop_shopee_clicks_count';
    const OPT_TIKTOK_COUNT = 'muop_tiktok_clicks_count';

    /**
     * Get configured affiliate URLs
     */
    public static function getLinks() {
        return array(
            'shopee' => get_option(self::OPT_SHOPEE_URL, 'https://shopee.vn'),
            'tiktok' => get_option(self::OPT_TIKTOK_URL, 'https://tiktok.com')
        );
    }

    /**
     * Update configured affiliate URLs
     */
    public static function updateLinks($shopee, $tiktok) {
        update_option(self::OPT_SHOPEE_URL, esc_url_raw($shopee));
        update_option(self::OPT_TIKTOK_URL, esc_url_raw($tiktok));
        return self::getLinks();
    }

    /**
     * Track an affiliate click
     */
    public static function trackClick($platform, $chapter_id = 0, $ip = null) {
        global $wpdb;

        $platform = in_array(strtolower($platform), array('shopee', 'tiktok')) ? strtolower($platform) : 'shopee';
        $ip_addr = $ip ?: (isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '127.0.0.1');

        // Increment counter
        $opt_key = ($platform === 'shopee') ? self::OPT_SHOPEE_COUNT : self::OPT_TIKTOK_COUNT;
        $count = (int) get_option($opt_key, 0) + 1;
        update_option($opt_key, $count);

        // Record in affiliate table if exists
        $table = $wpdb->prefix . 'affiliate_clicks';
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") === $table) {
            $wpdb->insert($table, array(
                'platform'   => $platform,
                'chapter_id' => (int) $chapter_id,
                'ip_address' => $ip_addr,
                'created_at' => current_time('mysql')
            ));
        }

        // Return redirect URL
        $links = self::getLinks();
        return array(
            'platform'     => $platform,
            'redirect_url' => $links[$platform],
            'total_clicks' => $count
        );
    }

    /**
     * Get click counters
     */
    public static function getStats() {
        return array(
            'shopee_clicks' => (int) get_option(self::OPT_SHOPEE_COUNT, 0),
            'tiktok_clicks' => (int) get_option(self::OPT_TIKTOK_COUNT, 0),
            'total_clicks'  => (int) get_option(self::OPT_SHOPEE_COUNT, 0) + (int) get_option(self::OPT_TIKTOK_COUNT, 0)
        );
    }

    /**
     * Get recent click log entries
     */
    public static function getRecentLogs($limit = 50) {
        global $wpdb;
        $table = $wpdb->prefix . 'affiliate_clicks';
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") !== $table) {
            return array();
        }

        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table ORDER BY id DESC LIMIT %d",
            $limit
        ));
    }
}
