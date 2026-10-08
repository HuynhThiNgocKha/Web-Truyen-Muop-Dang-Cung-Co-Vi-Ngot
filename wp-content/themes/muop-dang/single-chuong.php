<?php
/**
 * Single Chapter Template (Trang Đọc Truyện)
 * Bao gồm: Breadcrumb, Điều hướng chương, Nội dung chống copy, Shopee Gatekeeper Modal, Bình luận
 */
if (!defined('ABSPATH')) exit;

get_header();

$chapter_id  = get_the_ID();
$story_id    = get_post_meta($chapter_id, '_chuong_truyen_id', true);
$chap_num    = (int) get_post_meta($chapter_id, '_chuong_number', true) ?: 1;

$story       = get_post($story_id);
$story_title = $story ? $story->post_title : 'Truyện';
$story_url   = $story ? get_permalink($story_id) : home_url('/');

// Get all chapters for navigation
$all_chapters = $story_id ? muop_get_story_chapters($story_id, 'ASC') : array();

$prev_chap_url = '';
$next_chap_url = '';
$current_index = 0;

foreach ($all_chapters as $idx => $c) {
    if ($c->ID == $chapter_id) {
        $current_index = $idx;
        if (isset($all_chapters[$idx - 1])) {
            $prev_chap_url = get_permalink($all_chapters[$idx - 1]->ID);
        }
        if (isset($all_chapters[$idx + 1])) {
            $next_chap_url = get_permalink($all_chapters[$idx + 1]->ID);
        }
        break;
    }
}

// Shopee Checkpoint rule: starts at Chapter 2, every 3 chapters: (chap_num >= 2) && ((chap_num - 2) % 3 == 0)
$is_checkpoint = ($chap_num >= 2) && (($chap_num - 2) % 3 === 0);
$shopee_url    = get_option('muop_shopee_url', 'https://s.shopee.vn/4qG9lQO2rp');
?>

<div class="container">
    <div class="chapter-reading-wrapper" 
         data-story-id="<?php echo esc_attr($story_id); ?>" 
         data-chapter-id="<?php echo esc_attr($chapter_id); ?>" 
         data-chapter-num="<?php echo esc_attr($chap_num); ?>">

        <!-- CENTER 1: ĐƯỜNG DẪN (BREADCRUMB) -->
        <nav class="breadcrumbs" style="margin-bottom: 16px;">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <a href="<?php echo esc_url($story_url); ?>"><?php echo esc_html($story_title); ?></a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;"><?php the_title(); ?></span>
        </nav>

        <!-- CENTER 2: TOOLBAR ĐIỀU HƯỚNG CHƯƠNG TRƯỚC, DANH SÁCH, CHƯƠNG SAU -->
        <div class="reading-toolbar-top">
            <div class="chapter-nav-btns">
                <?php if ($prev_chap_url) : ?>
                    <a href="<?php echo esc_url($prev_chap_url); ?>" class="btn btn-secondary" style="padding: 6px 14px;">
                        <i class="fa-solid fa-arrow-left"></i> Chương Trước
                    </a>
                <?php else : ?>
                    <button class="btn btn-secondary" disabled style="opacity: 0.5; padding: 6px 14px;">
                        <i class="fa-solid fa-arrow-left"></i> Chương Trước
                    </button>
                <?php endif; ?>

                <!-- Dropdown Chọn chương nhanh -->
                <select id="readingChapterSelect" class="chapter-select-dropdown">
                    <?php foreach ($all_chapters as $c) : ?>
                        <option value="<?php echo esc_url(get_permalink($c->ID)); ?>" <?php selected($c->ID, $chapter_id); ?>>
                            <?php echo esc_html($c->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <?php if ($next_chap_url) : ?>
                    <a href="<?php echo esc_url($next_chap_url); ?>" class="btn btn-primary btn-next-chapter" style="padding: 6px 14px;">
                        Chương Sau <i class="fa-solid fa-arrow-right"></i>
                    </a>
                <?php else : ?>
                    <button class="btn btn-primary" disabled style="opacity: 0.5; padding: 6px 14px;">
                        Chương Sau <i class="fa-solid fa-arrow-right"></i>
                    </button>
                <?php endif; ?>
            </div>

            <!-- Chỉnh cỡ chữ -->
            <div class="font-controls">
                <button type="button" class="font-btn" id="btnFontDec" title="Giảm cỡ chữ">A-</button>
                <button type="button" class="font-btn" id="btnFontInc" title="Tăng cỡ chữ">A+</button>
            </div>
        </div>

        <!-- CENTER 3: NỘI DUNG TRUYỆN CỦA CHƯƠNG (CHỐNG COPY) -->
        <article class="chapter-content-box">
            <h1 class="chapter-heading-title"><?php the_title(); ?></h1>
            <div style="text-align: center; font-size: 13px; color: var(--text-muted); margin-bottom: 24px;">
                <span><i class="fa-regular fa-clock"></i> Đăng: <?php echo get_the_date('d/m/Y'); ?></span>
                <span style="margin: 0 8px;">•</span>
                <span><i class="fa-solid fa-shield-halved" style="color: var(--avocado-green);"></i> Nội dung có bản quyền chống sao chép</span>
            </div>

            <div class="chapter-body-text">
                <?php the_content(); ?>
            </div>
        </article>

        <!-- TOOLBAR ĐIỀU HƯỚNG DƯỚI -->
        <div class="reading-toolbar-bottom">
            <?php if ($prev_chap_url) : ?>
                <a href="<?php echo esc_url($prev_chap_url); ?>" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Chương Trước
                </a>
            <?php else : ?>
                <div></div>
            <?php endif; ?>

            <a href="<?php echo esc_url($story_url); ?>" class="btn btn-secondary">
                <i class="fa-solid fa-list-ul"></i> Mục Lục Truyện
            </a>

            <?php if ($next_chap_url) : ?>
                <a href="<?php echo esc_url($next_chap_url); ?>" class="btn btn-primary btn-next-chapter">
                    Chương Sau <i class="fa-solid fa-arrow-right"></i>
                </a>
            <?php else : ?>
                <div></div>
            <?php endif; ?>
        </div>

        <!-- CENTER 4: BÌNH LUẬN CỦA ĐỘC GIẢ -->
        <div class="comments-container">
            <h3 class="comments-header">
                <i class="fa-solid fa-comments"></i> Thảo Luận Chương Này
            </h3>

            <div class="comment-form-box">
                <?php if (is_user_logged_in()) : ?>
                    <form id="formPostComment">
                        <input type="hidden" name="post_id" value="<?php echo $chapter_id; ?>" />
                        <textarea name="comment" class="comment-textarea" placeholder="Nội dung chương này thế nào, chia sẻ cùng Mướp nha..." required></textarea>
                        <div style="display: flex; justify-content: flex-end; margin-top: 8px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-paper-plane"></i> Gửi Bình Luận
                            </button>
                        </div>
                    </form>
                <?php else : ?>
                    <div style="background: var(--pastel-green); padding: 14px 18px; border-radius: var(--radius-md); font-size: 13.5px; display: flex; align-items: center; justify-content: space-between;">
                        <span><i class="fa-solid fa-lock" style="color: var(--primary-green);"></i> Đăng nhập để bình luận về chương truyện này!</span>
                        <a href="<?php echo esc_url(home_url('/dang-nhap/')); ?>" class="btn btn-primary" style="padding: 6px 14px; font-size: 12.5px;">Đăng Nhập</a>
                    </div>
                <?php endif; ?>
            </div>

            <ul class="comment-list-ul">
                <?php
                $chap_comments = get_comments(array('post_id' => $chapter_id, 'status' => 'approve'));
                if (!empty($chap_comments)) :
                    foreach ($chap_comments as $cmt) :
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
                    echo '<p style="color: var(--text-muted); font-size: 13px;">Chưa có bình luận nào cho chương này.</p>';
                endif;
                ?>
            </ul>
        </div>
    </div>
</div>

<!-- ==========================================================================
     POPUP SHOPEE GATEKEEPER MODAL
     Xuất hiện từ chương 2 và cứ cách 3 chương hiện 1 lần (Chương 2, 5, 8, 11...)
     Bắt buộc độc giả nhấn vào link Shopee mới có thể mở khóa đọc tiếp.
     ========================================================================== -->
<div id="shopeeGatekeeperModal" class="shopee-gatekeeper-overlay" style="display: none;">
    <div class="shopee-modal-card">
        <div class="shopee-modal-icon">
            <i class="fa-solid fa-gift"></i>
        </div>
        <h3 class="shopee-modal-title">Ủng Hộ Mướp Đắng Nhé! 🌱</h3>
        
        <div class="shopee-modal-msg">
            <p style="margin-bottom: 8px;"><strong>1. Để giúp Mướp có thể duy trì web lâu dài mọi người click vào link này nha.</strong></p>
            <p style="font-size: 13px; color: var(--text-muted);">Bạn cần nhấn mở link Shopee dưới đây để hệ thống tự động mở khóa chương truyện này cho bạn đọc tiếp nhé!</p>
        </div>

        <div class="shopee-modal-btn-wrap">
            <a href="<?php echo esc_url($shopee_url); ?>" id="btnShopeeUnlock" class="btn btn-shopee" style="width: 100%; padding: 12px; font-size: 16px;">
                <i class="fa-solid fa-bag-shopping"></i> Nhấn Vào Đây Để Mở Khóa Đọc Tiếp
            </a>
        </div>

        <p class="shopee-modal-note">
            <i class="fa-solid fa-circle-check" style="color: var(--primary-green);"></i> Chỉ cần 1 lượt click ủng hộ, chương truyện sẽ được mở khóa ngay lập tức!
        </p>
    </div>
</div>

<?php
get_footer();
