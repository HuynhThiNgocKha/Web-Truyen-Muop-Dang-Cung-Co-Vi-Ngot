<?php
namespace Muop\Api;

use Muop\Controllers\StoryController;
use Muop\Controllers\ChapterController;
use Muop\Controllers\AdminController;
use Muop\Controllers\AffiliateController;

if (!defined('ABSPATH')) exit;

/**
 * REST Server
 * Exposes standardized REST API endpoints for Client-Server communication
 */
class RestServer {
    const NAMESPACE = 'muop/v1';

    /**
     * Register REST API routes
     */
    public static function registerRoutes() {
        // 1. Stories API
        register_rest_route(self::NAMESPACE, '/stories', array(
            array(
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => array(__CLASS__, 'getStories'),
                'permission_callback' => '__return_true'
            ),
            array(
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => array(__CLASS__, 'createStory'),
                'permission_callback' => function() {
                    return is_user_logged_in();
                }
            )
        ));

        register_rest_route(self::NAMESPACE, '/stories/(?P<id>\d+)/approve', array(
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => array(__CLASS__, 'approveStory'),
            'permission_callback' => function() {
                return current_user_can('administrator');
            }
        ));

        register_rest_route(self::NAMESPACE, '/stories/(?P<id>\d+)/nominate', array(
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => array(__CLASS__, 'nominateStory'),
            'permission_callback' => function() {
                return current_user_can('administrator');
            }
        ));

        register_rest_route(self::NAMESPACE, '/stories/(?P<id>\d+)', array(
            'methods'             => \WP_REST_Server::DELETABLE,
            'callback'            => array(__CLASS__, 'deleteStory'),
            'permission_callback' => function() {
                return is_user_logged_in();
            }
        ));

        // 2. Chapters API
        register_rest_route(self::NAMESPACE, '/chapters', array(
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => array(__CLASS__, 'createChapter'),
            'permission_callback' => function() {
                return is_user_logged_in();
            }
        ));

        // 3. Admin System API
        register_rest_route(self::NAMESPACE, '/admin/stats', array(
            'methods'             => \WP_REST_Server::READABLE,
            'callback'            => array(AdminController::class, 'stats'),
            'permission_callback' => function() {
                return current_user_can('administrator');
            }
        ));

        register_rest_route(self::NAMESPACE, '/admin/switch-mode', array(
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => array(AdminController::class, 'switchMode'),
            'permission_callback' => function() {
                return current_user_can('administrator');
            }
        ));

        // 4. Affiliate Tracking API
        register_rest_route(self::NAMESPACE, '/affiliate/track', array(
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => array(AffiliateController::class, 'track'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route(self::NAMESPACE, '/affiliate/settings', array(
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => array(AffiliateController::class, 'saveSettings'),
            'permission_callback' => function() {
                return current_user_can('administrator');
            }
        ));
    }

    public static function getStories(\WP_REST_Request $req) {
        $_GET['page']     = $req->get_param('page') ?: 1;
        $_GET['per_page'] = $req->get_param('per_page') ?: 12;
        $_GET['genre']    = $req->get_param('genre');
        $_GET['status']   = $req->get_param('status');
        return StoryController::index();
    }

    public static function createStory(\WP_REST_Request $req) {
        $_POST['story_title']   = $req->get_param('title');
        $_POST['story_desc']    = $req->get_param('content');
        $_POST['story_genres']  = $req->get_param('genres');
        $_POST['story_status']  = $req->get_param('status');
        $_POST['story_shopee']  = $req->get_param('shopee_url');
        $_POST['story_tiktok']  = $req->get_param('tiktok_url');
        return StoryController::store();
    }

    public static function approveStory(\WP_REST_Request $req) {
        $_POST['story_id'] = $req->get_param('id');
        return StoryController::approve();
    }

    public static function nominateStory(\WP_REST_Request $req) {
        $_POST['story_id']      = $req->get_param('id');
        $_POST['nominate_type'] = $req->get_param('type');
        return StoryController::nominate();
    }

    public static function deleteStory(\WP_REST_Request $req) {
        $_POST['story_id'] = $req->get_param('id');
        return StoryController::delete();
    }

    public static function createChapter(\WP_REST_Request $req) {
        $_POST['story_id']        = $req->get_param('story_id');
        $_POST['chapter_title']   = $req->get_param('title');
        $_POST['chapter_content'] = $req->get_param('content');
        $_POST['chapter_order']   = $req->get_param('order') ?: 1;
        $_POST['is_locked']       = $req->get_param('is_locked') ? '1' : '0';
        return ChapterController::store();
    }
}
