<?php
namespace Muop\Models;

if (!defined('ABSPATH')) exit;

/**
 * Chapter Model (Chương)
 * Encapsulates chapter querying, creation, ordering, and reading access
 */
class Chapter {
    const POST_TYPE = 'chuong';

    /**
     * Find chapter by ID
     */
    public static function find($id) {
        $post = get_post($id);
        if (!$post || $post->post_type !== self::POST_TYPE) {
            return null;
        }

        return self::format($post);
    }

    /**
     * Get all chapters belonging to a specific story
     */
    public static function getByStory($story_id, $order = 'ASC') {
        $posts = get_posts(array(
            'post_type'      => self::POST_TYPE,
            'meta_key'       => '_chuong_truyen_id',
            'meta_value'     => (int) $story_id,
            'posts_per_page' => -1,
            'orderby'        => 'meta_value_num',
            'meta_key'       => '_chuong_order',
            'order'          => $order
        ));

        // Fallback order by post date if meta_value_num is empty
        if (empty($posts)) {
            $posts = get_posts(array(
                'post_type'      => self::POST_TYPE,
                'meta_key'       => '_chuong_truyen_id',
                'meta_value'     => (int) $story_id,
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => $order
            ));
        }

        return array_map(array(__CLASS__, 'format'), $posts);
    }

    /**
     * Create new chapter
     */
    public static function create($data) {
        $story_id = (int) $data['story_id'];
        $title    = sanitize_text_field($data['title']);
        $content  = wp_kses_post($data['content']);
        $order    = isset($data['order']) ? (int) $data['order'] : 1;
        $author   = isset($data['author_id']) ? (int) $data['author_id'] : get_current_user_id();

        $chap_id = wp_insert_post(array(
            'post_title'   => $title,
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_type'    => self::POST_TYPE,
            'post_author'  => $author
        ));

        if (is_wp_error($chap_id)) {
            return $chap_id;
        }

        update_post_meta($chap_id, '_chuong_truyen_id', $story_id);
        update_post_meta($chap_id, '_chuong_order', $order);
        if (isset($data['is_locked'])) {
            update_post_meta($chap_id, '_chuong_locked', $data['is_locked'] ? '1' : '0');
        }

        return $chap_id;
    }

    /**
     * Check if a chapter is locked and requires Shopee/TikTok affiliate click
     */
    public static function isLocked($chapter_id, $user_id = 0) {
        $locked = get_post_meta($chapter_id, '_chuong_locked', true);
        if (!$locked || $locked === '0') {
            return false;
        }

        // Check if unlocked via cookie or session
        $unlocked_cookie = isset($_COOKIE['muop_unlocked_chapters']) ? explode(',', $_COOKIE['muop_unlocked_chapters']) : array();
        return !in_array((string) $chapter_id, $unlocked_cookie);
    }

    /**
     * Format WP_Post chapter
     */
    public static function format($post) {
        $id = $post->ID;
        $story_id = (int) get_post_meta($id, '_chuong_truyen_id', true);
        $order = (int) get_post_meta($id, '_chuong_order', true);
        $is_locked = (bool) get_post_meta($id, '_chuong_locked', true);

        return array(
            'id'         => $id,
            'title'      => $post->post_title,
            'content'    => $post->post_content,
            'permalink'  => get_permalink($id),
            'story_id'   => $story_id,
            'order'      => $order,
            'is_locked'  => $is_locked,
            'date'       => get_the_date('d/m/Y', $id),
            'word_count' => str_word_count(strip_tags($post->post_content))
        );
    }
}
