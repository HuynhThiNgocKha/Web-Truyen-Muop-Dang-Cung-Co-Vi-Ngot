<?php
namespace Muop\Controllers;

use Muop\Core\Request;
use Muop\Core\Response;
use Muop\Models\Story;
use Muop\Models\Chapter;

if (!defined('ABSPATH')) exit;

/**
 * Story Controller
 * Orchestrates story business logic and HTTP/AJAX/REST responses
 */
class StoryController {
    /**
     * Get list of stories
     */
    public static function index() {
        $paged = Request::getInt('page', 1);
        $limit = Request::getInt('per_page', 12);
        $genre = Request::get('genre');
        $status = Request::get('status');

        $args = array(
            'posts_per_page' => $limit,
            'paged'          => $paged
        );

        if ($genre) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'the_loai',
                    'field'    => 'slug',
                    'terms'    => $genre
                )
            );
        }

        if ($status === 'full') {
            $args['meta_query'] = array(
                array('key' => '_truyen_status', 'value' => 'full')
            );
        }

        $result = Story::all($args);
        return Response::success($result);
    }

    /**
     * Approve a pending story (Admin only)
     */
    public static function approve() {
        if (!current_user_can('administrator')) {
            return Response::error('Bạn không có quyền duyệt truyện!', 403);
        }

        $id = Request::getInt('story_id');
        if (!$id) {
            return Response::error('ID truyện không hợp lệ!', 400);
        }

        Story::updateStatus($id, 'publish');
        return Response::success(array('id' => $id), 'Đã duyệt truyện thành công!');
    }

    /**
     * Nominate a story (Admin only)
     */
    public static function nominate() {
        if (!current_user_can('administrator')) {
            return Response::error('Bạn không có quyền đề cử!', 403);
        }

        $id = Request::getInt('story_id');
        $period = Request::get('nominate_type', 'none');

        if (!$id) {
            return Response::error('ID truyện không hợp lệ!', 400);
        }

        Story::setNomination($id, $period);
        return Response::success(array('id' => $id, 'period' => $period), 'Đã cập nhật đề cử thành công!');
    }

    /**
     * Delete a story (Admin or Story Author)
     */
    public static function delete() {
        $id = Request::getInt('story_id');
        if (!$id) {
            return Response::error('ID truyện không hợp lệ!', 400);
        }

        $story = get_post($id);
        if (!$story) {
            return Response::error('Không tìm thấy truyện!', 404);
        }

        if (!current_user_can('administrator') && get_current_user_id() !== (int) $story->post_author) {
            return Response::error('Bạn không có quyền xóa truyện này!', 403);
        }

        Story::delete($id);
        return Response::success(array('id' => $id), 'Đã xóa truyện thành công!');
    }

    /**
     * Create / Publish a story
     */
    public static function store() {
        if (!is_user_logged_in()) {
            return Response::error('Vui lòng đăng nhập để đăng truyện!', 401);
        }

        $title = Request::get('story_title');
        $content = Request::getContent('story_desc');

        if (empty($title)) {
            return Response::error('Vui lòng nhập tên truyện!', 400);
        }

        $data = array(
            'title'       => $title,
            'content'     => $content,
            'author_id'   => get_current_user_id(),
            'genres'      => Request::get('story_genres'),
            'full_status' => (Request::get('story_status') === 'full'),
            'shopee_url'  => Request::get('story_shopee'),
            'tiktok_url'  => Request::get('story_tiktok')
        );

        $story_id = Story::create($data);
        if (is_wp_error($story_id)) {
            return Response::error($story_id->get_error_message(), 500);
        }

        $msg = current_user_can('administrator') 
            ? 'Đã đăng truyện thành công!' 
            : 'Truyện đã được gửi và đang chờ Admin duyệt!';

        return Response::success(array('story_id' => $story_id), $msg);
    }
}
