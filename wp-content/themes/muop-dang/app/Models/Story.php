<?php
namespace Muop\Models;

if (!defined('ABSPATH')) exit;

/**
 * Story Model (Truyện)
 * Encapsulates all data operations for stories
 */
class Story {
    const POST_TYPE = 'truyen';

    /**
     * Find single story by ID with structured attributes
     */
    public static function find($id) {
        $post = get_post($id);
        if (!$post || $post->post_type !== self::POST_TYPE) {
            return null;
        }

        return self::format($post);
    }

    /**
     * Get all stories matching query arguments
     */
    public static function all($args = array()) {
        $defaults = array(
            'post_type'      => self::POST_TYPE,
            'posts_per_page' => 12,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC'
        );

        $query = new \WP_Query(wp_parse_args($args, $defaults));
        $items = array();
        if ($query->have_posts()) {
            foreach ($query->posts as $p) {
                $items[] = self::format($p);
            }
        }

        return array(
            'items'       => $items,
            'total'       => $query->found_posts,
            'total_pages' => $query->max_num_pages
        );
    }

    /**
     * Get pending stories waiting for approval
     */
    public static function getPending() {
        $posts = get_posts(array(
            'post_type'      => self::POST_TYPE,
            'post_status'    => 'pending',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'ASC'
        ));

        return array_map(array(__CLASS__, 'format'), $posts);
    }

    /**
     * Get nominated stories (day, week, month)
     */
    public static function getNominated($period = 'day', $limit = 6) {
        $meta_query = array(
            array(
                'key'     => '_truyen_nominated',
                'value'   => $period,
                'compare' => '='
            )
        );

        return self::all(array(
            'posts_per_page' => $limit,
            'meta_query'     => $meta_query
        ));
    }

    /**
     * Create new story
     */
    public static function create($data) {
        $title   = sanitize_text_field($data['title']);
        $content = isset($data['content']) ? wp_kses_post($data['content']) : '';
        $author  = isset($data['author_id']) ? (int) $data['author_id'] : get_current_user_id();
        $status  = isset($data['status']) ? $data['status'] : (current_user_can('administrator') ? 'publish' : 'pending');

        $story_id = wp_insert_post(array(
            'post_title'   => $title,
            'post_content' => $content,
            'post_status'  => $status,
            'post_type'    => self::POST_TYPE,
            'post_author'  => $author
        ));

        if (is_wp_error($story_id)) {
            return $story_id;
        }

        // Genres
        if (!empty($data['genres'])) {
            wp_set_object_terms($story_id, (array) $data['genres'], 'the_loai');
        }

        // Meta fields
        if (isset($data['full_status'])) {
            update_post_meta($story_id, '_truyen_status', $data['full_status'] ? 'full' : 'ongoing');
        }
        if (isset($data['shopee_url'])) {
            update_post_meta($story_id, '_truyen_shopee_url', esc_url_raw($data['shopee_url']));
        }
        if (isset($data['tiktok_url'])) {
            update_post_meta($story_id, '_truyen_tiktok_url', esc_url_raw($data['tiktok_url']));
        }

        return $story_id;
    }

    /**
     * Update story status (approve/publish/draft)
     */
    public static function updateStatus($id, $status) {
        return wp_update_post(array(
            'ID'          => (int) $id,
            'post_status' => sanitize_text_field($status)
        ));
    }

    /**
     * Set nomination period
     */
    public static function setNomination($id, $period) {
        $allowed = array('none', 'day', 'week', 'month');
        $val = in_array($period, $allowed) ? $period : 'none';
        return update_post_meta((int) $id, '_truyen_nominated', $val);
    }

    /**
     * Delete story and related chapters
     */
    public static function delete($id) {
        // Delete all chapters belonging to this story
        $chaps = get_posts(array(
            'post_type'      => 'chuong',
            'meta_key'       => '_chuong_truyen_id',
            'meta_value'     => $id,
            'posts_per_page' => -1
        ));

        foreach ($chaps as $c) {
            wp_delete_post($c->ID, true);
        }

        return wp_delete_post((int) $id, true);
    }

    /**
     * Get story views count
     */
    public static function getViews($id) {
        return (int) get_post_meta($id, '_truyen_views', true);
    }

    /**
     * Increment view count (both total and current month)
     */
    public static function incrementViews($id) {
        $total = self::getViews($id) + 1;
        update_post_meta($id, '_truyen_views', $total);

        $month_key = '_truyen_views_' . date('Y-m');
        $month_views = (int) get_post_meta($id, $month_key, true) + 1;
        update_post_meta($id, $month_key, $month_views);

        return $total;
    }

    /**
     * Format WP_Post into clean entity array
     */
    public static function format($post) {
        $id = $post->ID;
        $views = self::getViews($id);
        $status = get_post_meta($id, '_truyen_status', true) ?: 'ongoing';
        $nominate = get_post_meta($id, '_truyen_nominated', true) ?: 'none';
        $genres = wp_get_object_terms($id, 'the_loai');

        return array(
            'id'           => $id,
            'title'        => $post->post_title,
            'slug'         => $post->post_name,
            'permalink'    => get_permalink($id),
            'content'      => $post->post_content,
            'excerpt'      => wp_trim_words($post->post_content, 30),
            'thumbnail'    => get_the_post_thumbnail_url($id, 'muop-cover') ?: get_template_directory_uri() . '/assets/images/default-cover.svg',
            'status'       => $post->post_status, // publish, pending, etc.
            'story_status' => $status,            // full, ongoing
            'is_full'      => ($status === 'full'),
            'nominate'     => $nominate,
            'views'        => $views,
            'author_id'    => $post->post_author,
            'author_name'  => get_the_author_meta('display_name', $post->post_author),
            'date'         => get_the_date('d/m/Y', $id),
            'time_ago'     => human_time_diff(get_the_time('U', $id), current_time('timestamp')) . ' trước',
            'genres'       => array_map(function($g) {
                return array('id' => $g->term_id, 'name' => $g->name, 'slug' => $g->slug);
            }, $genres)
        );
    }
}
