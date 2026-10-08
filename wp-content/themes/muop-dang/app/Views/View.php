<?php
namespace Muop\Views;

if (!defined('ABSPATH')) exit;

/**
 * View Helper
 * Loads and renders presentation templates with decoupled data
 */
class View {
    /**
     * Render a view file with data array
     */
    public static function render($view_name, $data = array(), $echo = true) {
        $file = get_template_directory() . '/app/Views/' . $view_name . '.php';
        if (!file_exists($file)) {
            $file = get_template_directory() . '/template-parts/' . $view_name . '.php';
        }

        if (!file_exists($file)) {
            return '';
        }

        extract($data);

        if (!$echo) {
            ob_start();
            include $file;
            return ob_get_clean();
        }

        include $file;
    }

    /**
     * Render badge component
     */
    public static function badge($type, $text = '', $icon = '') {
        $class = 'badge ';
        switch ($type) {
            case 'approved':
                $class .= 'badge-approved';
                $icon = $icon ?: 'fa-solid fa-check';
                $text = $text ?: 'Đã duyệt';
                break;
            case 'pending':
                $class .= 'badge-hot';
                $icon = $icon ?: 'fa-solid fa-clock';
                $text = $text ?: 'Chờ duyệt';
                break;
            case 'full':
                $class .= 'badge-full';
                $text = $text ?: 'Full';
                break;
            case 'new':
                $class .= 'badge-new';
                $text = $text ?: 'Mới';
                break;
            default:
                $class .= 'badge-green';
        }

        $icon_html = $icon ? '<i class="' . esc_attr($icon) . '"></i> ' : '';
        return '<span class="' . esc_attr($class) . '">' . $icon_html . esc_html($text) . '</span>';
    }
}
