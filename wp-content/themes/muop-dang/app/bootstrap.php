<?php
/**
 * MVC & Client-Server Bootstrap
 * Autoloader, Controller Routing, and REST API Initialization
 */

if (!defined('ABSPATH')) exit;

// 1. PSR-4 Autoloader for Muop namespace
spl_autoload_register(function ($class) {
    $prefix = 'Muop\\';
    $base_dir = __DIR__ . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// 2. Initialize REST Server (Client - Server REST API Architecture)
add_action('rest_api_init', array(\Muop\Api\RestServer::class, 'registerRoutes'));

// 3. Enqueue Client-side API Client SDK on frontend
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script(
        'muop-api-client',
        get_template_directory_uri() . '/assets/js/api-client.js',
        array('jquery'),
        defined('MUOP_THEME_VERSION') ? MUOP_THEME_VERSION : '2.0.0',
        true
    );

    wp_localize_script('muop-api-client', 'muopConfig', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'restUrl' => esc_url_raw(rest_url('muop/v1/')),
        'nonce'   => wp_create_nonce('wp_rest')
    ));
}, 5);

// 4. Template Loader for organized templates/ directory
add_filter('template_include', function ($template) {
    if (is_page()) {
        global $post;
        if ($post) {
            // Priority 1: Check templates/page-{slug}.php
            $slug_file = get_template_directory() . '/templates/page-' . $post->post_name . '.php';
            if (file_exists($slug_file)) {
                return $slug_file;
            }

            // Priority 2: Check custom assigned template in templates/
            $custom_template = get_post_meta($post->ID, '_wp_page_template', true);
            if ($custom_template && $custom_template !== 'default') {
                $custom_file = get_template_directory() . '/templates/' . basename($custom_template);
                if (file_exists($custom_file)) {
                    return $custom_file;
                }
            }
        }
    }
    return $template;
});

