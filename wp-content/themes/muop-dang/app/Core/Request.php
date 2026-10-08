<?php
namespace Muop\Core;

if (!defined('ABSPATH')) exit;

/**
 * HTTP Request Helper for MVC Architecture
 */
class Request {
    protected static $jsonBody = null;

    /**
     * Get parameter from GET or POST
     */
    public static function get($key, $default = null) {
        if (isset($_POST[$key])) {
            return is_string($_POST[$key]) ? sanitize_text_field(wp_unslash($_POST[$key])) : $_POST[$key];
        }
        if (isset($_GET[$key])) {
            return is_string($_GET[$key]) ? sanitize_text_field(wp_unslash($_GET[$key])) : $_GET[$key];
        }

        $json = self::json();
        if ($json && isset($json[$key])) {
            return $json[$key];
        }

        return $default;
    }

    /**
     * Get integer parameter
     */
    public static function getInt($key, $default = 0) {
        return (int) self::get($key, $default);
    }

    /**
     * Get raw HTML/Rich Text content
     */
    public static function getContent($key, $default = '') {
        if (isset($_POST[$key])) {
            return wp_kses_post(wp_unslash($_POST[$key]));
        }
        return $default;
    }

    /**
     * Parse JSON body if present
     */
    public static function json() {
        if (self::$jsonBody === null) {
            $raw = file_get_contents('php://input');
            self::$jsonBody = json_decode($raw, true) ?: array();
        }
        return self::$jsonBody;
    }

    /**
     * Get current logged in user ID
     */
    public static function userId() {
        return get_current_user_id();
    }

    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        return is_user_logged_in();
    }

    /**
     * Verify Nonce for security
     */
    public static function verifyNonce($nonce, $action = 'muop_nonce') {
        return wp_verify_nonce($nonce, $action);
    }
}
