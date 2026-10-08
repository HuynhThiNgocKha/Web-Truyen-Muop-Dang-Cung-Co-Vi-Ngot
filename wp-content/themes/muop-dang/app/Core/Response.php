<?php
namespace Muop\Core;

if (!defined('ABSPATH')) exit;

/**
 * Standardized HTTP / JSON Response Handler
 */
class Response {
    /**
     * Send standard JSON response
     */
    public static function json($data = array(), $statusCode = 200) {
        status_header($statusCode);
        wp_send_json($data);
    }

    /**
     * Send success JSON response
     */
    public static function success($data = array(), $message = 'Success') {
        wp_send_json_success(array_merge(
            array('message' => $message),
            is_array($data) ? $data : array('result' => $data)
        ));
    }

    /**
     * Send error JSON response
     */
    public static function error($message = 'Error', $code = 400, $data = array()) {
        status_header($code);
        wp_send_json_error(array_merge(
            array('message' => $message, 'code' => $code),
            is_array($data) ? $data : array('details' => $data)
        ));
    }
}
