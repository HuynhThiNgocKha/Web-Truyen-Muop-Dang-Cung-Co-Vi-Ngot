<?php
/**
 * Single Story Template (Trang giới thiệu / mô tả truyện)
 */
if (!defined('ABSPATH')) exit;

get_header();

$story_id = get_the_ID();
$views    = muop_get_story_views($story_id);
$status   = get_post_meta($story_id, '_truyen_status', true);
$author   = get_post_meta($story_id, '_truyen_author_name', true);
$team     = get_post_meta($story_id, '_truyen_team', true);
if (empty($team)) {
    $team_terms = get_the_terms($story_id, 'team_dich');
    $team = (!empty($team_terms) && !is_wp_error($team_terms)) ? $team_terms[0]->name : get_post_meta($story_id, '_truyen_dich_gia', true);
}
if (empty($team)) {
    $team = 'Mướp Đắng Cũng Có Vị Ngọt';
}
$updated_data = muop_get_story_last_updated($story_id);
$updated      = $updated_data['display'];

// Taxonomies
$terms_the_loai = get_the_terms($story_id, 'the_loai');

// Chapters list
$chapters = muop_get_story_chapters($story_id, 'ASC');
$chap_count = count($chapters);
$first_chap_url = !empty($chapters) ? get_permalink($chapters[0]->ID) : '#';
$last_chap_url  = !empty($chapters) ? get_permalink(end($chapters)->ID) : '#';

// Bookmarked check, Role check, Follow check & Favorite check
$is_bookmarked = false;
$is_following  = false;
$is_favorited  = false;
$can_manage_story = false;
$can_change_status = false;

$story_post_author = (int) get_post_field('post_author', $story_id);
$current_uid = get_current_user_id();
$is_own_story = ($current_uid > 0 && $current_uid === $story_post_author);
$can_show_follow = !$is_own_story; // Không hiển thị nếu là chính dịch giả của truyện
$can_show_favorite = !$is_own_story;
$is_admin_user = muop_is_admin();

if (is_user_logged_in()) {
    global $wpdb;
    $table_bm = $wpdb->prefix . 'reading_bookmarks';
    $is_bookmarked = (bool) $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_bm WHERE user_id = %d AND story_id = %d", $current_uid, $story_id));
    $is_following = muop_is_user_following_story($current_uid, $story_id);
    $is_favorited = muop_is_user_favorited_story($current_uid, $story_id);

    // Phân quyền:
    // 1. Chỉ chính DỊCH GIẢ của truyện đó mới có quyền Thêm / Sửa / Xóa trong trang truyện của mình.
    // 2. Dịch giả khác KHÔNG có quyền.
    // 3. Admin trên trang của dịch giả khác chỉ có quyền XEM, KHÔNG được tác động hay chỉnh sửa.
    // 4. Admin chỉ có quyền tác động khi chính Admin là tác giả truyện này VÀ có thêm vai trò là dịch giả.
    $can_manage_story = muop_can_user_manage_story_frontend($story_id, $current_uid);
    $can_change_status = $can_manage_story;
}
$is_exempt = ($is_admin_user || $is_own_story || !empty($can_manage_story));

$thumb_url = get_the_post_thumbnail_url($story_id, 'muop-cover');
if (!$thumb_url) {
    $thumb_url = MUOP_THEME_URI . '/assets/images/default-cover.svg';
}

$from = isset($_GET['from']) ? sanitize_text_field($_GET['from']) : '';
if (empty($from) && isset($_SERVER['HTTP_REFERER'])) {
    if (strpos($_SERVER['HTTP_REFERER'], 'quan-ly-admin') !== false) {
        $from = 'admin';
    } elseif (strpos($_SERVER['HTTP_REFERER'], 'thong-tin-dich-gia') !== false) {
        $from = 'translator';
    }
}
?>

<div class="container">
    <div class="story-detail-wrapper" data-story-id="<?php echo esc_attr($story_id); ?>" data-is-own-story="<?php echo ($is_own_story || !empty($can_manage_story)) ? '1' : '0'; ?>">
        <!-- CENTER 1: ĐƯỜNG DẪN (BREADCRUMB) -->
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <?php if ($from === 'admin') : ?>
                <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
                <a href="<?php echo esc_url(home_url('/quan-ly-admin/')); ?>">Quản Trị Hệ Thống</a>
            <?php elseif ($from === 'translator') : ?>
                <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
                <a href="<?php echo esc_url(home_url('/thong-tin-dich-gia/')); ?>">Kênh Dịch Giả</a>
            <?php endif; ?>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;" id="breadcrumbStoryTitle"><?php the_title(); ?></span>
        </nav>

        <!-- CENTER 2: PHẦN LEFT (ẢNH) & PHẦN RIGHT (THÔNG TIN) -->
        <div class="story-detail-top">
            <!-- Left: Hình ảnh truyện -->
            <div class="story-cover-col">
                <div class="story-cover-frame" id="storyCoverFrame">
                    <img id="mainStoryCoverImg" src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title_attribute(); ?>" />
                    <?php if ($can_manage_story) : ?>
                        <button type="button" class="btn-change-cover-overlay" id="btnChangeCoverOverlay" data-story-id="<?php echo $story_id; ?>" title="Thay đổi ảnh bìa truyện (Dịch giả)">
                            <i class="fa-solid fa-camera"></i>
                            <span>Thay đổi ảnh bìa</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right: Thông tin truyện -->
            <div class="story-info-col">
                <div class="story-title-header-wrap">
                    <h1 class="story-info-title"><span id="storyTitleText"><?php the_title(); ?></span><?php if ($can_manage_story) : ?><button type="button" class="btn-edit-title-inline" id="btnEditTitleInline" data-story-id="<?php echo $story_id; ?>" title="Chỉnh sửa tên truyện (Dịch giả)"><i class="fa-solid fa-pen-to-square"></i></button><?php endif; ?></h1>
                </div>

                <div class="story-info-meta-list">
                    <?php if (!empty($author)) : ?>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fa-solid fa-pen-nib"></i> Tác giả:</span>
                            <span class="meta-value"><?php echo esc_html($author); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($team)) : 
                        $team_terms = get_the_terms($story_id, 'team_dich');
                        if (!empty($team_terms) && !is_wp_error($team_terms)) {
                            $team_url = get_term_link($team_terms[0]);
                        } else {
                            $found_term = get_term_by('name', $team, 'team_dich');
                            $team_url = ($found_term && !is_wp_error($found_term)) ? get_term_link($found_term) : home_url('/team-dich/' . sanitize_title($team) . '/');
                        }
                    ?>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fa-solid fa-users"></i> Team dịch:</span>
                            <div class="meta-value">
                                <a href="<?php echo esc_url($team_url); ?>" class="team-badge-btn" title="Xem trang của Team <?php echo esc_attr($team); ?>">
                                    <?php echo esc_html($team); ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="meta-item">
                        <span class="meta-label"><i class="fa-solid fa-tags"></i> Thể loại:</span>
                        <div class="genre-tag-list">
                            <?php if (!empty($terms_the_loai) && !is_wp_error($terms_the_loai)) : ?>
                                <?php foreach ($terms_the_loai as $term) : ?>
                                    <a href="<?php echo esc_url(get_term_link($term)); ?>" class="genre-pill">
                                        <?php echo esc_html($term->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <span class="genre-pill">Zhihu</span>
                                <span class="genre-pill">Hiện đại</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="meta-item meta-item-status">
                        <span class="meta-label"><i class="fa-solid fa-chart-line"></i> Trạng thái:</span>
                        <div class="meta-value status-interactive-wrap">
                            <span class="badge <?php echo ($status === 'hoan_thanh') ? 'badge-full' : 'badge-green'; ?> <?php echo $can_change_status ? 'badge-editable' : ''; ?>" id="storyStatusBadge" <?php if ($can_change_status) : ?>role="button" tabindex="0" title="Nhấn để đổi trạng thái truyện (Dịch giả & Admin)"<?php endif; ?>>
                                <i class="fa-solid <?php echo ($status === 'hoan_thanh') ? 'fa-check' : 'fa-arrows-rotate'; ?>" id="storyStatusIcon"></i>
                                <span id="storyStatusText"><?php echo ($status === 'hoan_thanh') ? 'Hoàn thành' : 'Đang ra'; ?></span>
                                <?php if ($can_change_status) : ?>
                                    <i class="fa-solid fa-chevron-down status-caret"></i>
                                <?php endif; ?>
                            </span>

                            <?php if ($can_change_status) : ?>
                                <div class="status-dropdown-menu" id="statusDropdownMenu" style="display: none;">
                                    <button type="button" class="status-dropdown-item <?php echo ($status !== 'hoan_thanh') ? 'active' : ''; ?>" data-status="dang_ra" data-story-id="<?php echo $story_id; ?>">
                                        <i class="fa-solid fa-arrows-rotate"></i> Đang ra
                                    </button>
                                    <button type="button" class="status-dropdown-item <?php echo ($status === 'hoan_thanh') ? 'active' : ''; ?>" data-status="hoan_thanh" data-story-id="<?php echo $story_id; ?>">
                                        <i class="fa-solid fa-check"></i> Hoàn thành
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="meta-item">
                        <span class="meta-label"><i class="fa-solid fa-book-open"></i> Số chương:</span>
                        <span class="meta-value"><strong><?php echo $chap_count; ?></strong> Chương</span>
                    </div>

                    <div class="meta-item">
                        <span class="meta-label"><i class="fa-solid fa-eye"></i> Số lượt xem:</span>
                        <span class="meta-value"><strong><?php echo number_format($views); ?></strong> lượt</span>
                    </div>

                    <div class="meta-item">
                        <span class="meta-label"><i class="fa-solid fa-clock"></i> Cập nhật:</span>
                        <span class="meta-value" id="storyUpdatedMeta" title="<?php echo esc_attr($updated_data['formatted']); ?>"><?php echo esc_html($updated); ?></span>
                    </div>
                </div>

                <!-- Action Buttons: Đọc từ đầu, Đọc mới nhất, Lưu tủ truyện -->
                <div class="story-actions-bar">
                    <?php if ($chap_count > 0) : 
                        $first_chap_id = $chapters[0]->ID;
                        $last_chap = end($chapters);
                        $last_chap_id = $last_chap ? $last_chap->ID : 0;
                        $last_chap_num = $last_chap ? (get_post_meta($last_chap->ID, '_chuong_number', true) ?: $chap_count) : 1;
                        $is_last_chap1 = ($last_chap_id === $first_chap_id || (int)$last_chap_num === 1);
                    ?>
                        <a href="<?php echo esc_url($first_chap_url); ?>" class="btn btn-primary">
                            <i class="fa-solid fa-book-reader"></i> Đọc Từ Đầu
                        </a>
                        <a href="<?php echo esc_url($last_chap_url); ?>" 
                           class="btn btn-secondary btn-read-latest"
                           data-story-id="<?php echo esc_attr($story_id); ?>"
                           data-chapter-id="<?php echo esc_attr($last_chap_id); ?>"
                           data-chapter-num="<?php echo esc_attr($last_chap_num); ?>"
                           data-total-chapters="<?php echo esc_attr($chap_count); ?>"
                           data-is-checkpoint="<?php echo ($is_exempt ? 0 : (muop_is_chapter_checkpoint($last_chap_num, $chap_count) ? 1 : 0)); ?>"
                           data-is-own-story="<?php echo ($is_own_story || !empty($can_manage_story)) ? '1' : '0'; ?>">
                            <i class="fa-solid fa-forward-step"></i> Đọc Mới Nhất
                        </a>
                    <?php endif; ?>

                    <button type="button" class="btn <?php echo $is_bookmarked ? 'btn-primary' : 'btn-secondary'; ?>" id="btnToggleBookmark" data-story-id="<?php echo $story_id; ?>">
                        <i class="fa-<?php echo $is_bookmarked ? 'solid' : 'regular'; ?> fa-bookmark"></i>
                        <?php echo $is_bookmarked ? 'Đã Lưu Tủ Truyện' : 'Lưu Vào Tủ Truyện'; ?>
                    </button>

                    <button type="button" 
                            class="btn <?php echo $is_favorited ? 'btn-primary' : 'btn-secondary'; ?> btn-favorite-story" 
                            id="btnToggleFavoriteStory" 
                            data-story-id="<?php echo $story_id; ?>" 
                            title="<?php echo $is_favorited ? 'Bỏ yêu thích truyện này' : 'Yêu thích truyện này'; ?>">
                        <i class="fa-<?php echo $is_favorited ? 'solid' : 'regular'; ?> fa-heart"></i>
                        <span class="favorite-btn-label"><?php echo $is_favorited ? 'Đã Yêu Thích' : 'Yêu Thích'; ?></span>
                    </button>

                    <button type="button" 
                            class="btn <?php echo $is_following ? 'btn-primary' : 'btn-secondary'; ?> btn-follow-story" 
                            id="btnToggleFollowStory" 
                            data-story-id="<?php echo $story_id; ?>" 
                            title="<?php echo $is_following ? 'Đang theo dõi truyện này (Bấm để hủy)' : 'Theo dõi truyện để nhận thông báo ngay khi có chương mới'; ?>">
                        <i class="fa-<?php echo $is_following ? 'solid' : 'regular'; ?> fa-bell"></i>
                        <span class="follow-btn-label"><?php echo $is_following ? 'Đang Theo Dõi' : 'Theo Dõi'; ?></span>
                    </button>

                    <?php if ($can_manage_story) : ?>
                        <button type="button" class="btn btn-outline btn-edit-story" data-story-id="<?php echo $story_id; ?>" title="Chỉnh sửa toàn bộ thông tin truyện (Dịch giả)">
                            <i class="fa-solid fa-pen-to-square"></i> Chỉnh Sửa Truyện
                        </button>

                        <?php 
                        $add_chap_from = !empty($from) ? $from : 'story';
                        $add_chap_args = array('story_id' => $story_id, 'from' => $add_chap_from);
                        if ($add_chap_from === 'admin') {
                            $add_chap_args['from_admin'] = 1;
                        }
                        $add_chap_url = add_query_arg($add_chap_args, home_url('/them-chuong/'));
                        ?>
                        <a href="<?php echo esc_url($add_chap_url); ?>" class="btn btn-outline" style="border-style: dashed;">
                            <i class="fa-solid fa-plus-circle"></i> Thêm Chương Mới
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- CENTER 3: ĐOẠN GIỚI THIỆU TRUYỆN -->
        <div class="section-block" style="margin-top: 10px;">
            <div class="section-header">
                <h2 class="section-title" style="font-size: 17px;">
                    <i class="fa-solid fa-quote-left"></i> Giới Thiệu Truyện
                </h2>
            </div>
            <div class="story-summary-wrapper">
                <div class="story-summary-box is-collapsed" id="storySummaryBox">
                    <?php 
                    $content = get_the_content();
                    if (!empty($content)) {
                        echo muop_format_story_summary($content);
                    } else {
                        echo '<p>Câu chuyện tình yêu đầy cảm xúc được dịch và biên tập cẩn thận bởi team Mướp Đắng. Chúc các độc giả có những phút giây thư giãn ngọt ngào nhất!</p>';
                    }
                    ?>
                </div>
                <div class="story-summary-toggle-wrap" id="storySummaryToggleWrap">
                    <button type="button" class="btn-story-summary-toggle" id="btnToggleSummary">
                        <span>Xem thêm</span> <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- CENTER 4: DANH SÁCH CHƯƠNG -->
        <div class="chapter-list-section">
            <div class="chapter-list-header">
                <h2 class="section-title" style="font-size: 17px;">
                    <i class="fa-solid fa-list-ol"></i> Danh Sách Chương (<?php echo $chap_count; ?>)
                </h2>
            </div>

            <?php if (!empty($chapters)) : 
                $chapters_desc = array_reverse($chapters);
                $first_chap_id = $chapters[0]->ID;
            ?>
                <div class="chapter-grid">
                    <?php foreach ($chapters_desc as $chap) : 
                        $chap_num      = get_post_meta($chap->ID, '_chuong_number', true) ?: 1;
                        $chap_views    = muop_get_chapter_views($chap->ID);
                        $chap_ts       = get_post_timestamp($chap->ID, 'date') ?: get_post_time('U', true, $chap->ID);
                        $chap_time_ago = muop_time_ago($chap_ts);
                        if (empty($chap_time_ago)) {
                            $chap_time_ago = get_the_time('d/m/Y', $chap->ID);
                        }
                        $is_chap_1 = ((int)$chap->ID === (int)$first_chap_id || (int)$chap_num === 1);
                        $chap_is_checkpoint = $is_exempt ? false : muop_is_chapter_checkpoint($chap_num, $chap_count);
                    ?>
                        <div class="chapter-item-row" id="chapterRow-<?php echo $chap->ID; ?>">
                            <a href="<?php echo esc_url(get_permalink($chap->ID)); ?>" 
                               class="chapter-item"
                               data-story-id="<?php echo esc_attr($story_id); ?>"
                               data-chapter-id="<?php echo esc_attr($chap->ID); ?>"
                               data-chapter-num="<?php echo esc_attr($chap_num); ?>"
                               data-total-chapters="<?php echo esc_attr($chap_count); ?>"
                               data-is-checkpoint="<?php echo $chap_is_checkpoint ? '1' : '0'; ?>"
                               data-is-own-story="<?php echo ($is_own_story || !empty($can_manage_story)) ? '1' : '0'; ?>"
                               data-is-chap1="<?php echo $is_chap_1 ? '1' : '0'; ?>">
                                <span class="chapter-item-title">
                                    <i class="fa-regular fa-file-lines" style="color: var(--avocado-green); margin-right: 6px;"></i>
                                    <span class="chap-title-text"><?php echo esc_html(muop_get_clean_chapter_title($chap->post_title)); ?></span>
                                </span>
                                <span class="chapter-item-meta">
                                    <?php if ($can_manage_story) : ?>
                                        <span class="chapter-item-views" title="Lượt xem chương (Dịch giả)">
                                            <i class="fa-regular fa-eye"></i> <?php echo number_format($chap_views); ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="chapter-item-date" title="<?php echo esc_attr(get_the_time('d/m/Y H:i', $chap->ID)); ?>"><?php echo esc_html($chap_time_ago); ?></span>
                                </span>
                            </a>
                            <?php if ($can_manage_story) : ?>
                                <div class="chapter-actions-dropdown">
                                    <button type="button" class="btn-chapter-dropdown-trigger" data-story-id="<?php echo $story_id; ?>" data-chapter-id="<?php echo $chap->ID; ?>" data-chapter-num="<?php echo esc_attr($chap_num); ?>" data-chapter-title="<?php echo esc_attr(muop_get_clean_chapter_title($chap->post_title)); ?>" title="Tùy chọn chương (Dịch giả)">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <div class="chapter-dropdown-menu">
                                        <button type="button" class="chapter-dropdown-item btn-trigger-edit-chapter" data-story-id="<?php echo $story_id; ?>" data-chapter-id="<?php echo $chap->ID; ?>" data-chapter-num="<?php echo esc_attr($chap_num); ?>" data-chapter-title="<?php echo esc_attr(muop_get_clean_chapter_title($chap->post_title)); ?>">
                                            <i class="fa-solid fa-pen-to-square"></i> Chỉnh sửa chương
                                        </button>
                                        <button type="button" class="chapter-dropdown-item item-danger btn-trigger-delete-chapter" data-story-id="<?php echo $story_id; ?>" data-chapter-id="<?php echo $chap->ID; ?>" data-chapter-num="<?php echo esc_attr($chap_num); ?>" data-chapter-title="<?php echo esc_attr(muop_get_clean_chapter_title($chap->post_title)); ?>">
                                            <i class="fa-solid fa-trash-can"></i> Xóa chương
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div style="text-align: center; padding: 30px; background: var(--pastel-green); border-radius: var(--radius-md); color: var(--text-muted);">
                    <i class="fa-regular fa-folder-open" style="font-size: 32px; color: var(--avocado-green); margin-bottom: 8px;"></i>
                    <p>Truyện đang được cập nhật chương mới. Vui lòng quay lại sau nha!</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- CENTER 5: BÌNH LUẬN & ĐÁNH GIÁ -->
        <div class="comments-container">
            <h3 class="comments-header">
                <i class="fa-solid fa-comments"></i> Bình Luận & Thảo Luận
            </h3>

            <div class="comment-form-box">
                <?php if (is_user_logged_in()) : ?>
                    <form id="formPostComment">
                        <input type="hidden" name="post_id" value="<?php echo $story_id; ?>" />
                        <div class="comment-input-wrap">
                            <textarea name="comment" class="comment-textarea" placeholder="Chia sẻ cảm nghĩ của bạn về câu truyện này cùng Mướp nha..." required></textarea>
                            <div class="comment-stickers-preview" style="display: none;"></div>
                        </div>
                        <div class="comment-form-toolbar">
                            <button type="button" class="btn-sticker-toggle" title="Thêm Sticker">
                                <i class="fa-regular fa-face-smile"></i> <span>Sticker</span>
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-paper-plane"></i> Gửi Bình Luận
                            </button>
                        </div>
                        <?php muop_render_sticker_picker(); ?>
                    </form>
                <?php else : ?>
                    <div style="background: var(--pastel-green); padding: 14px 18px; border-radius: var(--radius-md); font-size: 13.5px; display: flex; align-items: center; justify-content: space-between;">
                        <span><i class="fa-solid fa-lock" style="color: var(--primary-green);"></i> Vui lòng đăng nhập để tham gia bình luận truyện cùng mọi người!</span>
                        <a href="<?php echo esc_url(home_url('/dang-nhap/')); ?>" class="btn btn-primary" style="padding: 6px 14px; font-size: 12.5px;">Đăng Nhập Ngay</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- List Comments -->
            <ul class="comment-list-ul">
                <?php
                $comments = get_comments(array('post_id' => $story_id, 'status' => 'approve'));
                if (!empty($comments)) :
                    foreach ($comments as $cmt) :
                ?>
                    <li class="comment-item" id="comment-<?php echo $cmt->comment_ID; ?>">
                        <div class="comment-avatar">
                            <?php echo esc_html(strtoupper(substr($cmt->comment_author, 0, 1))); ?>
                        </div>
                        <div class="comment-body">
                            <div class="comment-header-row" style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                <div>
                                    <span class="comment-author-name"><?php echo esc_html($cmt->comment_author); ?></span>
                                    <span class="comment-time"><?php echo esc_html(muop_time_ago(strtotime($cmt->comment_date))); ?></span>
                                </div>
                                <?php if (muop_can_user_delete_comment($cmt)) : ?>
                                    <button type="button" class="btn-delete-comment" data-comment-id="<?php echo (int) $cmt->comment_ID; ?>" title="Xóa bình luận">
                                        <i class="fa-solid fa-trash-can"></i> Xóa
                                    </button>
                                <?php endif; ?>
                            </div>
                            <div class="comment-text"><?php echo muop_render_comment_content($cmt->comment_content); ?></div>
                        </div>
                    </li>
                <?php 
                    endforeach;
                else :
                    echo '<p style="color: var(--text-muted); font-size: 13px;">Chưa có bình luận nào. Hãy là người đầu tiên để lại cảm nghĩ nhé!</p>';
                endif;
                ?>
            </ul>
        </div>
    </div>
</div>

<?php
if ($can_manage_story) {
    get_template_part('templates/modal-edit-story-master');
    get_template_part('templates/modal-quick-edit-story');
}

get_footer();
