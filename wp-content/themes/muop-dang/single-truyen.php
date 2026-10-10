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
$updated  = get_the_modified_date('d/m/Y H:i');

// Taxonomies
$terms_the_loai = get_the_terms($story_id, 'the_loai');

// Chapters list
$chapters = muop_get_story_chapters($story_id, 'ASC');
$chap_count = count($chapters);
$first_chap_url = !empty($chapters) ? get_permalink($chapters[0]->ID) : '#';
$last_chap_url  = !empty($chapters) ? get_permalink(end($chapters)->ID) : '#';

// Bookmarked check
$is_bookmarked = false;
$can_change_status = false;
if (is_user_logged_in()) {
    global $wpdb;
    $current_uid = get_current_user_id();
    $table_bm = $wpdb->prefix . 'reading_bookmarks';
    $is_bookmarked = (bool) $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_bm WHERE user_id = %d AND story_id = %d", $current_uid, $story_id));

    $post_author_id = (int) get_post_field('post_author', $story_id);
    if (current_user_can('administrator') || ($post_author_id === $current_uid)) {
        $can_change_status = true;
    }
}

$thumb_url = get_the_post_thumbnail_url($story_id, 'muop-cover');
if (!$thumb_url) {
    $thumb_url = MUOP_THEME_URI . '/assets/images/default-cover.svg';
}
?>

<div class="container">
    <div class="story-detail-wrapper">
        <!-- CENTER 1: ĐƯỜNG DẪN (BREADCRUMB) -->
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Truyện</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;"><?php the_title(); ?></span>
        </nav>

        <!-- CENTER 2: PHẦN LEFT (ẢNH) & PHẦN RIGHT (THÔNG TIN) -->
        <div class="story-detail-top">
            <!-- Left: Hình ảnh truyện -->
            <div class="story-cover-col">
                <div class="story-cover-frame">
                    <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title_attribute(); ?>" />
                </div>
            </div>

            <!-- Right: Thông tin truyện -->
            <div class="story-info-col">
                <h1 class="story-info-title"><?php the_title(); ?></h1>

                <div class="story-info-meta-list">
                    <?php if (!empty($author)) : ?>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fa-solid fa-pen-nib"></i> Tác giả:</span>
                            <span class="meta-value"><?php echo esc_html($author); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($team)) : ?>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fa-solid fa-users"></i> Team dịch:</span>
                            <span class="meta-value"><?php echo esc_html($team); ?></span>
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
                        <span class="meta-value"><strong><?php echo $chap_count; ?></strong> chương</span>
                    </div>

                    <div class="meta-item">
                        <span class="meta-label"><i class="fa-solid fa-eye"></i> Số lượt xem:</span>
                        <span class="meta-value"><strong><?php echo number_format($views); ?></strong> lượt</span>
                    </div>

                    <div class="meta-item">
                        <span class="meta-label"><i class="fa-solid fa-clock"></i> Cập nhật gần nhất:</span>
                        <span class="meta-value"><?php echo esc_html($updated); ?></span>
                    </div>
                </div>

                <!-- Action Buttons: Đọc từ đầu, Đọc mới nhất, Lưu tủ truyện -->
                <div class="story-actions-bar">
                    <?php if ($chap_count > 0) : ?>
                        <a href="<?php echo esc_url($first_chap_url); ?>" class="btn btn-primary">
                            <i class="fa-solid fa-book-reader"></i> Đọc Từ Đầu
                        </a>
                        <a href="<?php echo esc_url($last_chap_url); ?>" class="btn btn-secondary">
                            <i class="fa-solid fa-forward-step"></i> Đọc Mới Nhất
                        </a>
                    <?php endif; ?>

                    <button type="button" class="btn <?php echo $is_bookmarked ? 'btn-primary' : 'btn-secondary'; ?>" id="btnToggleBookmark" data-story-id="<?php echo $story_id; ?>">
                        <i class="fa-<?php echo $is_bookmarked ? 'solid' : 'regular'; ?> fa-bookmark"></i>
                        <?php echo $is_bookmarked ? 'Đã Lưu Tủ Truyện' : 'Lưu Vào Tủ Truyện'; ?>
                    </button>

                    <?php if (current_user_can('dich_gia') || current_user_can('administrator')) : ?>
                        <a href="<?php echo esc_url(add_query_arg('story_id', $story_id, home_url('/them-chuong/'))); ?>" class="btn btn-outline" style="border-style: dashed;">
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
            <div class="story-summary-box">
                <?php 
                $content = get_the_content();
                if (!empty($content)) {
                    the_content();
                } else {
                    echo '<p>Câu chuyện tình yêu đầy cảm xúc được dịch và biên tập cẩn thận bởi team Mướp Đắng. Chúc các độc giả có những phút giây thư giãn ngọt ngào nhất!</p>';
                }
                ?>
            </div>
        </div>

        <!-- CENTER 4: DANH SÁCH CHƯƠNG -->
        <div class="chapter-list-section">
            <div class="chapter-list-header">
                <h2 class="section-title" style="font-size: 17px;">
                    <i class="fa-solid fa-list-ol"></i> Danh Sách Chương (<?php echo $chap_count; ?>)
                </h2>
                <span style="font-size: 12.5px; color: var(--text-muted);">Sắp xếp: Tăng dần</span>
            </div>

            <?php if (!empty($chapters)) : ?>
                <div class="chapter-grid">
                    <?php foreach ($chapters as $chap) : 
                        $chap_num = get_post_meta($chap->ID, '_chuong_number', true) ?: 1;
                    ?>
                        <a href="<?php echo esc_url(get_permalink($chap->ID)); ?>" class="chapter-item">
                            <span><i class="fa-regular fa-file-lines" style="color: var(--avocado-green); margin-right: 6px;"></i> <?php echo esc_html($chap->post_title); ?></span>
                            <span style="font-size: 11px; color: var(--text-muted);"><?php echo get_the_time('d/m', $chap->ID); ?></span>
                        </a>
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
                        <textarea name="comment" class="comment-textarea" placeholder="Chia sẻ cảm nghĩ của bạn về câu truyện này cùng Mướp nha..." required></textarea>
                        <div style="display: flex; justify-content: flex-end; margin-top: 8px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-paper-plane"></i> Gửi Bình Luận
                            </button>
                        </div>
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
                    <li class="comment-item">
                        <div class="comment-avatar">
                            <?php echo esc_html(strtoupper(substr($cmt->comment_author, 0, 1))); ?>
                        </div>
                        <div class="comment-body">
                            <span class="comment-author-name"><?php echo esc_html($cmt->comment_author); ?></span>
                            <span class="comment-time"><?php echo esc_html(human_time_diff(strtotime($cmt->comment_date), current_time('timestamp'))) . ' trước'; ?></span>
                            <div class="comment-text"><?php echo esc_html($cmt->comment_content); ?></div>
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
get_footer();
