<?php
namespace Muop\Controllers;

use Muop\Core\Request;
use Muop\Core\Response;
use Muop\Models\Chapter;
use Muop\Models\Story;

if (!defined('ABSPATH')) exit;

/**
 * Chapter Controller
 * Handles chapter creation and reading management
 */
class ChapterController {
    /**
     * Create / Add chapter
     */
    public static function store() {
        if (!is_user_logged_in()) {
            return Response::error('Vui lòng đăng nhập để thêm chương!', 401);
        }

        $story_id = Request::getInt('story_id');
        $title    = Request::get('chapter_title');
        $content  = Request::getContent('chapter_content');
        $order    = Request::getInt('chapter_order', 1);

        if (!$story_id || empty($title) || empty($content)) {
            return Response::error('Vui lòng điền đầy đủ tiêu đề và nội dung chương!', 400);
        }

        $story = get_post($story_id);
        if (!$story) {
            return Response::error('Không tìm thấy bộ truyện!', 404);
        }

        if (!current_user_can('administrator') && get_current_user_id() !== (int) $story->post_author) {
            return Response::error('Bạn không có quyền thêm chương cho truyện này!', 403);
        }

        $chap_id = Chapter::create(array(
            'story_id'   => $story_id,
            'title'      => $title,
            'content'    => $content,
            'order'      => $order,
            'is_locked'  => (Request::get('is_locked') === '1'),
            'author_id'  => get_current_user_id()
        ));

        if (is_wp_error($chap_id)) {
            return Response::error($chap_id->get_error_message(), 500);
        }

        return Response::success(array('chapter_id' => $chap_id), 'Đã thêm chương thành công!');
    }
}
