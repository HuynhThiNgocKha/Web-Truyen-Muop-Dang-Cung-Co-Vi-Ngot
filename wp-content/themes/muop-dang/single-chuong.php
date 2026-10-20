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
            $next_chap_id  = $all_chapters[$idx + 1]->ID;
        }
        break;
    }
}

$total_chapters     = count($all_chapters);
$chap_order         = ($total_chapters > 0) ? ($current_index + 1) : $chap_num;
$next_chap_order    = $chap_order + 1;
$next_is_checkpoint = muop_is_chapter_checkpoint($next_chap_order, $total_chapters);

$current_uid        = get_current_user_id();
$story_post_author  = $story ? (int) $story->post_author : 0;
$is_admin_user      = muop_is_admin();
$is_own_story       = ($current_uid > 0 && ($current_uid === $story_post_author || ($story_id && muop_can_user_manage_story_frontend($story_id, $current_uid))));

$story_post_status = $story ? $story->post_status : 'publish';
$is_story_hidden   = $story_id ? (bool) get_post_meta($story_id, '_truyen_is_hidden', true) : false;

// Nếu truyện chưa công khai (future, draft, trash hoặc bị ẩn) mà người xem KHÔNG PHẢI là Admin và KHÔNG PHẢI chính dịch giả truyện:
if (!$is_admin_user && !$is_own_story && ($story_post_status === 'future' || $story_post_status === 'draft' || $story_post_status === 'trash' || $is_story_hidden)) {
    status_header(404);
    nocache_headers();
    ?>
    <div class="container" style="padding: 60px 16px; text-align: center;">
        <div style="max-width: 500px; margin: 0 auto; background: var(--bg-card); padding: 40px 24px; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); border: 1px solid var(--border-color);">
            <div style="font-size: 64px; color: var(--text-muted); margin-bottom: 16px;"><i class="fa-solid fa-file-circle-question"></i></div>
            <h1 style="font-size: 22px; font-weight: 700; margin-bottom: 12px; color: var(--text-main);">Không tìm thấy chương</h1>
            <p style="color: var(--text-muted); font-size: 14px; line-height: 1.6; margin-bottom: 24px;">Truyện này hiện không tồn tại, đã bị ẩn hoặc chưa đến thời gian phát hành công khai.</p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-house"></i> Về Trang Chủ
            </a>
        </div>
    </div>
    <?php
    get_footer();
    exit;
}

// Quy tắc hiển thị link Shopee Gatekeeper:
// - Nếu là Admin HOẶC Dịch giả đọc truyện của chính mình: MIỄN NHIỄM HOÀN TOÀN (không khóa chương, không popup Shopee)
// - Truyện có 3 hoặc 4 chương: Xuất hiện ở chương 1 và chương 3
// - Truyện >= 5 chương: Xuất hiện ở chương 2, 5, 8, 11... (cứ cách 3 chương 1 lần)
if ($is_admin_user || $is_own_story) {
    $is_checkpoint      = false;
    $next_is_checkpoint = false;
} else {
    $is_checkpoint = muop_is_chapter_checkpoint($chap_order, $total_chapters);
}
$shopee_url     = get_option('muop_shopee_url', 'https://s.shopee.vn/4qG9lQO2rp');
?>

<div class="container">
    <div class="chapter-reading-wrapper" 
         data-story-id="<?php echo esc_attr($story_id); ?>" 
         data-chapter-id="<?php echo esc_attr($chapter_id); ?>" 
         data-chapter-num="<?php echo esc_attr($chap_order); ?>"
         data-total-chapters="<?php echo esc_attr($total_chapters); ?>"
         data-is-checkpoint="<?php echo $is_checkpoint ? '1' : '0'; ?>"
         data-is-own-story="<?php echo $is_own_story ? '1' : '0'; ?>">

        <!-- CENTER 1: ĐƯỜNG DẪN (BREADCRUMB DẠNG NGANG TRÊN WEB) -->
        <nav class="breadcrumbs reading-chapter-breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <a href="<?php echo esc_url($story_url); ?>" class="breadcrumb-story-title-link"><?php echo esc_html($story_title); ?></a>
            <i class="fa-solid fa-angle-right breadcrumb-chapter-sep" style="font-size: 11px;"></i>
            <span class="breadcrumb-chapter-current" style="color: var(--primary-green); font-weight: 600;"><?php echo esc_html(muop_get_clean_chapter_title(get_the_title())); ?></span>
        </nav>

        <?php if ($story_post_status === 'future') : 
            $ch_sched_time = get_post_meta($story_id, '_truyen_scheduled_time', true) ?: ($story ? $story->post_date : '');
            $ch_sched_fmt  = $ch_sched_time ? date_i18n('H:i - d/m/Y', strtotime($ch_sched_time)) : '';
        ?>
            <div style="background: linear-gradient(135deg, #f5f3ff, #faf5ff); border: 1px solid #c4b5fd; border-radius: var(--radius-sm); padding: 10px 16px; margin-bottom: 16px; font-size: 13px; color: #5b21b6; display: flex; align-items: center; gap: 10px; box-shadow: 0 1px 4px rgba(124, 58, 237, 0.05);">
                <i class="fa-solid fa-clock-rotate-left" style="font-size: 16px; color: #7c3aed;"></i>
                <span><strong>Chế độ xem trước (Dịch giả / Quản trị viên):</strong> Bộ truyện này đang được lên lịch hẹn giờ công khai vào lúc <strong><?php echo esc_html($ch_sched_fmt); ?></strong>. Độc giả bình thường chưa thể xem chương này.</span>
            </div>
        <?php elseif ($story_post_status === 'draft' || $is_story_hidden) : ?>
            <div style="background: linear-gradient(135deg, #fffbeb, #fef3c7); border: 1px solid #fde68a; border-radius: var(--radius-sm); padding: 10px 16px; margin-bottom: 16px; font-size: 13px; color: #92400e; display: flex; align-items: center; gap: 10px; box-shadow: 0 1px 4px rgba(217, 119, 6, 0.05);">
                <i class="fa-solid fa-eye-slash" style="font-size: 16px; color: #d97706;"></i>
                <span><strong>Chế độ xem trước (Dịch giả / Quản trị viên):</strong> Bộ truyện này đang ở trạng thái Ẩn (Bản nháp). Độc giả bình thường chưa thể xem chương này.</span>
            </div>
        <?php endif; ?>

        <!-- CENTER 2: TOOLBAR ĐIỀU HƯỚNG CHƯƠNG TRƯỚC, 3 GẠCH DANH SÁCH, CHƯƠNG SAU & ĐIỀU CHỈNH CỠ CHỮ -->
        <div class="reading-toolbar-top">
            <div class="chapter-nav-btns">
                <?php if ($prev_chap_url) : ?>
                    <a href="<?php echo esc_url($prev_chap_url); ?>" class="btn btn-secondary btn-chap-prev">
                        <i class="fa-solid fa-arrow-left"></i> Chương Trước
                    </a>
                <?php else : ?>
                    <button class="btn btn-secondary btn-chap-prev" disabled style="opacity: 0.5;">
                        <i class="fa-solid fa-arrow-left"></i> Chương Trước
                    </button>
                <?php endif; ?>

                <!-- Nút 3 gạch mở danh sách chương -->
                <button type="button" class="btn btn-secondary btn-chapter-drawer-toggle" id="btnOpenChapterDrawer" title="Danh sách chương">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <?php if ($next_chap_url) : ?>
                    <a href="<?php echo esc_url($next_chap_url); ?>" class="btn btn-primary btn-next-chapter btn-chap-next"
                       data-story-id="<?php echo esc_attr($story_id); ?>"
                       data-chapter-id="<?php echo esc_attr(!empty($next_chap_id) ? $next_chap_id : 0); ?>"
                       data-chapter-num="<?php echo esc_attr($next_chap_order); ?>"
                       data-total-chapters="<?php echo esc_attr($total_chapters); ?>"
                       data-is-checkpoint="<?php echo $next_is_checkpoint ? '1' : '0'; ?>"
                       data-is-own-story="<?php echo $is_own_story ? '1' : '0'; ?>">
                        Chương Sau <i class="fa-solid fa-arrow-right"></i>
                    </a>
                <?php else : ?>
                    <button class="btn btn-primary btn-chap-next" disabled style="opacity: 0.5;">
                        Chương Sau <i class="fa-solid fa-arrow-right"></i>
                    </button>
                <?php endif; ?>
            </div>

            <!-- Điều chỉnh cỡ chữ & mở popup cài đặt đọc truyện theo Ảnh 3 & Nút Báo lỗi theo Ảnh 2 -->
            <div class="reading-font-bar">
                <button type="button" class="font-size-btn" id="btnFontDec" title="Giảm cỡ chữ">A-</button>
                <button type="button" class="font-size-btn" id="btnFontInc" title="Tăng cỡ chữ">A+</button>
                <button type="button" class="font-size-btn btn-reading-settings-trigger" id="btnOpenReadingSettingsTop" title="Tùy chỉnh font chữ & màu sắc">
                    <i class="fa-solid fa-palette"></i>
                </button>
                <button type="button" class="font-size-btn btn-report-chapter-trigger" id="btnOpenReportChapterModal" data-logged-in="<?php echo is_user_logged_in() ? '1' : '0'; ?>" title="Báo lỗi chương này">
                    <i class="fa-solid fa-flag"></i> Báo lỗi
                </button>
            </div>
        </div>

        <!-- CENTER 3: NỘI DUNG TRUYỆN CỦA CHƯƠNG (CHỐNG COPY) -->
        <article class="chapter-content-box <?php echo $is_checkpoint ? 'chapter-locked-init' : ''; ?>">
            <header class="chapter-header-compact">
                <h1 class="chapter-heading-title"><?php echo esc_html(muop_get_clean_chapter_title(get_the_title())); ?></h1>
                <div class="chapter-meta-date" title="<?php echo esc_attr(get_the_date('d/m/Y H:i')); ?>">
                    <i class="fa-regular fa-clock"></i> <?php 
                        $current_chap_ts = get_post_timestamp(get_the_ID(), 'date') ?: get_post_time('U', true, get_the_ID());
                        $current_chap_time = muop_time_ago($current_chap_ts) ?: get_the_date('d/m/Y');
                        echo esc_html($current_chap_time); 
                    ?>
                </div>
            </header>

            <div class="chapter-body-text">
                <?php the_content(); ?>
            </div>
        </article>

        <!-- TOOLBAR ĐIỀU HƯỚNG DƯỚI -->
        <div class="reading-toolbar-bottom">
            <?php if ($prev_chap_url) : ?>
                <a href="<?php echo esc_url($prev_chap_url); ?>" class="btn btn-secondary btn-chap-prev">
                    <i class="fa-solid fa-arrow-left"></i> Chương Trước
                </a>
            <?php else : ?>
                <button class="btn btn-secondary btn-chap-prev" disabled style="opacity: 0.5;">
                    <i class="fa-solid fa-arrow-left"></i> Chương Trước
                </button>
            <?php endif; ?>

            <button type="button" class="btn btn-secondary btn-chapter-drawer-toggle" id="btnOpenChapterDrawerBottom" title="Danh sách chương">
                <i class="fa-solid fa-bars"></i>
            </button>

            <?php if ($next_chap_url) : ?>
                <a href="<?php echo esc_url($next_chap_url); ?>" class="btn btn-primary btn-next-chapter btn-chap-next" 
                   data-story-id="<?php echo esc_attr($story_id); ?>" 
                   data-chapter-id="<?php echo esc_attr(!empty($next_chap_id) ? $next_chap_id : 0); ?>"
                   data-chapter-num="<?php echo esc_attr($next_chap_order); ?>"
                   data-total-chapters="<?php echo esc_attr($total_chapters); ?>"
                   data-is-checkpoint="<?php echo $next_is_checkpoint ? '1' : '0'; ?>"
                   data-is-own-story="<?php echo $is_own_story ? '1' : '0'; ?>">
                    Chương Sau <i class="fa-solid fa-arrow-right"></i>
                </a>
            <?php else : ?>
                <button class="btn btn-primary btn-chap-next" disabled style="opacity: 0.5;">
                    Chương Sau <i class="fa-solid fa-arrow-right"></i>
                </button>
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
                        <div class="comment-input-wrap">
                            <textarea name="comment" class="comment-textarea" placeholder="Nội dung chương này thế nào, chia sẻ cùng Mướp nha..." required></textarea>
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
                    echo '<p style="color: var(--text-muted); font-size: 13px;">Chưa có bình luận nào cho chương này.</p>';
                endif;
                ?>
            </ul>
        </div>
    </div>
</div>

<!-- ==========================================================================
     POPUP DANH SÁCH CHƯƠNG (DRAWER / MODAL)
     Mở ra khi nhấn vào nút 3 gạch (fa-bars) ở thanh điều hướng đọc truyện
     ========================================================================== -->
<div id="chapterDrawerOverlay" class="chapter-drawer-overlay" style="display: none;"></div>
<div id="chapterDrawerModal" class="chapter-drawer-modal" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="chapterDrawerTitle">
    <div class="chapter-drawer-header">
        <div class="chapter-drawer-title-wrap">
            <h3 id="chapterDrawerTitle" class="chapter-drawer-title">
                <i class="fa-solid fa-bars-staggered"></i> Danh Sách Chương
            </h3>
            <span class="chapter-drawer-count">(<?php echo count($all_chapters); ?> chương)</span>
        </div>
        <button type="button" class="chapter-drawer-close" id="btnCloseChapterDrawer" aria-label="Đóng">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <div class="chapter-drawer-story-info">
        <span class="story-name-badge"><i class="fa-solid fa-book"></i> <?php echo esc_html($story_title); ?></span>
    </div>

    <!-- Ô tìm kiếm chương nhanh -->
    <div class="chapter-drawer-search-wrap">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <input type="text" id="chapterDrawerSearchInput" class="chapter-drawer-search-input" placeholder="Tìm kiếm chương..." autocomplete="off">
        <button type="button" id="chapterDrawerSearchClear" class="search-clear-btn" style="display: none;" title="Xóa tìm kiếm">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Danh sách các chương -->
    <div class="chapter-drawer-body">
        <ul class="chapter-drawer-list" id="chapterDrawerList">
            <?php foreach ($all_chapters as $idx => $c) : 
                $is_current = ($c->ID == $chapter_id);
                $chap_clean_title = muop_get_clean_chapter_title($c->post_title);
                $drawer_chap_order = $idx + 1;
                $drawer_is_checkpoint = ($is_admin_user || $is_own_story) ? false : muop_is_chapter_checkpoint($drawer_chap_order, $total_chapters);
            ?>
                <li class="chapter-drawer-item <?php echo $is_current ? 'is-current-chapter' : ''; ?>" data-title="<?php echo esc_attr(mb_strtolower($chap_clean_title)); ?>">
                    <a href="<?php echo esc_url(get_permalink($c->ID)); ?>" 
                       class="chapter-drawer-link"
                       data-story-id="<?php echo esc_attr($story_id); ?>"
                       data-chapter-id="<?php echo esc_attr($c->ID); ?>"
                       data-chapter-num="<?php echo esc_attr($drawer_chap_order); ?>"
                       data-total-chapters="<?php echo esc_attr($total_chapters); ?>"
                       data-is-checkpoint="<?php echo $drawer_is_checkpoint ? '1' : '0'; ?>"
                       data-is-own-story="<?php echo $is_own_story ? '1' : '0'; ?>">
                        <span class="chapter-drawer-name">
                            <?php if ($is_current) : ?>
                                <i class="fa-solid fa-book-open current-icon"></i>
                            <?php else : ?>
                                <i class="fa-regular fa-file-lines item-icon"></i>
                            <?php endif; ?>
                            <?php echo esc_html($chap_clean_title); ?>
                        </span>
                        <?php if ($is_current) : ?>
                            <span class="chapter-current-badge">Đang đọc</span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <div id="chapterDrawerNoResults" class="chapter-drawer-no-results" style="display: none;">
            <i class="fa-regular fa-face-frown"></i> Không tìm thấy chương phù hợp.
        </div>
    </div>

    <div class="chapter-drawer-footer">
        <a href="<?php echo esc_url($story_url); ?>" class="btn-drawer-story-link">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> Xem thông tin truyện
        </a>
    </div>
</div>

<!-- ==========================================================================
     THANH NÚT NỔI KHI ĐỌC TRUYỆN (FLOATING TOOLBAR)
     Bao gồm:
     1. Nút cuộn lên đầu trang (Scroll to top)
     2. Nút mở tùy chỉnh đọc truyện (Font, Cỡ chữ, Màu nền)
     Có thể kéo thả di chuyển tùy ý mà không làm chạy chữ của độc giả.
     ========================================================================== -->
<div class="floating-reading-toolbar" id="floatingReadingToolbar" style="display: none;">
    <button type="button" class="floating-reading-btn btn-floating-scroll-top" id="btnFloatingScrollTop" title="Lên đầu trang">
        <i class="fa-solid fa-chevron-up"></i>
    </button>
    <button type="button" class="floating-reading-btn btn-reading-settings-trigger" id="btnFloatingReadingSettings" title="Tùy chỉnh đọc truyện (Font, Màu, Cỡ chữ)">
        <i class="fa-solid fa-sliders"></i>
    </button>
</div>

<!-- ==========================================================================
     POPUP / POPOVER TÙY CHỈNH ĐỌC TRUYỆN (FONT, MÀU CHỮ, CỠ CHỮ)
     ========================================================================== -->
<div id="readingSettingsOverlay" class="reading-settings-overlay" style="display: none;"></div>
<div id="readingSettingsQuickModal" class="reading-settings-modal" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="readingSettingsTitle">
    <div class="reading-settings-header">
        <h3 id="readingSettingsTitle" class="reading-settings-title">
            <i class="fa-solid fa-sliders"></i> Tùy Chỉnh Đọc Truyện
        </h3>
        <button type="button" class="reading-settings-close" id="btnCloseReadingSettings" aria-label="Đóng">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <div class="reading-settings-body">
        <!-- 1. CỠ CHỮ -->
        <div class="setting-item-block">
            <label class="setting-item-label"><i class="fa-solid fa-text-height"></i> Cỡ chữ nội dung</label>
            <div class="setting-size-row">
                <button type="button" class="font-size-btn-sm" id="btnQuickFontDec" title="Giảm cỡ chữ">A-</button>
                <span class="setting-size-badge" id="settingSizeBadge">21px</span>
                <button type="button" class="font-size-btn-sm" id="btnQuickFontInc" title="Tăng cỡ chữ">A+</button>
            </div>
        </div>

        <!-- 2. KHOẢNG CÁCH CÁC ĐOẠN (DÃN ĐOẠN) -->
        <div class="setting-item-block">
            <label class="setting-item-label"><i class="fa-solid fa-arrows-up-down"></i> Khoảng cách các đoạn</label>
            <div class="setting-spacing-chips" id="readingSpacingChips">
                <button type="button" class="spacing-chip-btn" data-spacing="8">Gọn (8px)</button>
                <button type="button" class="spacing-chip-btn active" data-spacing="14">Vừa (14px)</button>
                <button type="button" class="spacing-chip-btn" data-spacing="20">Thoáng (20px)</button>
                <button type="button" class="spacing-chip-btn" data-spacing="26">Rộng (26px)</button>
            </div>
            <div class="setting-size-row" style="margin-top: 10px;">
                <button type="button" class="font-size-btn-sm" id="btnQuickSpacingDec" title="Giảm khoảng cách đoạn"><i class="fa-solid fa-minus"></i></button>
                <span class="setting-size-badge" id="settingSpacingBadge">14px</span>
                <button type="button" class="font-size-btn-sm" id="btnQuickSpacingInc" title="Tăng khoảng cách đoạn"><i class="fa-solid fa-plus"></i></button>
            </div>
        </div>

        <!-- 3. KIỂU CHỮ (FONT) -->
        <div class="setting-item-block">
            <label class="setting-item-label"><i class="fa-solid fa-font"></i> Kiểu chữ (Font)</label>
            <div class="setting-font-chips" id="readingFontChips">
                <button type="button" class="font-chip-btn active" data-font="sans-serif">Sans-serif (Mặc định)</button>
                <button type="button" class="font-chip-btn" data-font="merriweather">Merriweather (Sách báo)</button>
                <button type="button" class="font-chip-btn" data-font="be-vietnam">Be Vietnam Pro</button>
                <button type="button" class="font-chip-btn" data-font="georgia">Georgia (Cổ điển)</button>
                <button type="button" class="font-chip-btn" data-font="times">Times New Roman</button>
            </div>
        </div>

        <!-- 3. MÀU CHỮ (5 MÀU ĐỀ XUẤT + MÀU TÙY CHỌN) -->
        <div class="setting-item-block">
            <div class="setting-title-split">
                <label class="setting-item-label"><i class="fa-solid fa-palette"></i> Màu chữ đọc truyện</label>
                <span class="setting-hint-text" id="colorModeHint">5 màu đề xuất phổ biến:</span>
            </div>
            <!-- Grid 5 màu đề xuất -->
            <div class="setting-colors-palette" id="settingColorsPalette"></div>

            <!-- Tùy chọn màu tự do theo ý người dùng -->
            <div class="custom-color-row">
                <label for="inputCustomColorReader" class="custom-color-label">
                    <i class="fa-solid fa-eye-dropper"></i> Tự chọn màu tùy thích:
                </label>
                <div class="custom-color-control">
                    <input type="color" id="inputCustomColorReader" class="custom-color-input" value="#262626" />
                    <span class="custom-color-hex" id="customColorHexReader">#262626</span>
                </div>
            </div>
        </div>

        <!-- 4. GIAO DIỆN SÁNG / TỐI -->
        <div class="setting-item-block">
            <label class="setting-item-label"><i class="fa-solid fa-circle-half-stroke"></i> Nền giao diện</label>
            <div class="setting-theme-choice-row">
                <button type="button" class="theme-choice-btn active" data-theme="light">
                    <i class="fa-regular fa-sun"></i> Nền Sáng
                </button>
                <button type="button" class="theme-choice-btn" data-theme="dark">
                    <i class="fa-solid fa-moon"></i> Nền Tối
                </button>
            </div>
        </div>

        <!-- 5. HÀNG NÚT ÁP DỤNG -->
        <div class="setting-apply-wrap">
            <button type="button" class="btn btn-primary btn-apply-reading-settings" id="btnApplyReadingSettings">
                <i class="fa-solid fa-check"></i> Áp Dụng
            </button>
        </div>

        <!-- 6. FOOTER: NÚT KHÔI PHỤC & LIÊN KẾT ĐẾN CÀI ĐẶT HỆ THỐNG -->
        <div class="setting-modal-footer">
            <button type="button" class="btn-link-reset" id="btnResetReadingPrefs">
                <i class="fa-solid fa-arrow-rotate-left"></i> Khôi phục mặc định
            </button>
            <?php if (is_user_logged_in()) : ?>
                <a href="<?php echo esc_url(home_url('/ho-so/#tabReadingSettings')); ?>" class="link-to-profile-settings" target="_blank">
                    <i class="fa-solid fa-gear"></i> Trang Cài đặt tài khoản
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- MODAL POPUP BÁO LỖI CHƯƠNG THEO ẢNH 2 -->
<div class="modal-backdrop" id="reportChapterModal" style="display: none;" aria-hidden="true">
    <div class="modal-content report-chapter-modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fa-solid fa-triangle-exclamation"></i> Báo Lỗi Chương Truyện
            </h3>
            <button type="button" class="btn-close-modal" id="btnCloseReportChapterModal" aria-label="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="formSubmitChapterReport">
            <input type="hidden" name="story_id" value="<?php echo esc_attr($story_id); ?>" />
            <input type="hidden" name="chapter_id" value="<?php echo esc_attr(get_the_ID()); ?>" />
            <input type="hidden" name="chapter_num" value="<?php echo esc_attr($chap_num); ?>" />
            
            <div class="report-info-banner">
                <div>Truyện: <strong class="rep-story-name"><?php echo esc_html($story ? $story->post_title : ''); ?></strong></div>
                <div>Chương: <strong class="rep-chap-name">Chương <?php echo esc_html($chap_num); ?> - <?php echo esc_html(muop_get_clean_chapter_title(get_the_title())); ?></strong></div>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label class="form-label">
                    Loại lỗi gặp phải:
                </label>
                <select name="issue_type" class="form-input" required>
                    <option value="Lỗi chính tả / ngữ pháp">Lỗi chính tả / Ngữ pháp / Dịch thuật</option>
                    <option value="Thiếu nội dung / Mất chữ / Trắng trang">Thiếu nội dung / Mất chữ / Trắng trang</option>
                    <option value="Trùng lặp nội dung">Trùng lặp nội dung với chương khác</option>
                    <option value="Sai thứ tự chương">Sai thứ tự chương truyện</option>
                    <option value="Lỗi định dạng / Hiển thị dính chữ">Lỗi định dạng hiển thị / Chữ dính liền</option>
                    <option value="Khiếu nại bản quyền / Nội dung phản cảm">Khiếu nại bản quyền / Nội dung không phù hợp</option>
                    <option value="Lỗi khác">Lỗi khác</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label class="form-label">
                    Mô tả chi tiết lỗi: <span class="required-star">*</span>
                </label>
                <textarea name="description" class="form-input" rows="4" placeholder="Vui lòng mô tả cụ thể câu văn bị lỗi, từ ngữ hoặc vấn đề bạn gặp phải để BQT và dịch giả kiểm tra khắc phục..." required></textarea>
            </div>

            <div class="report-modal-actions">
                <button type="button" class="btn-report-cancel-pill" id="btnCancelReportChapter">Hủy</button>
                <button type="submit" class="btn-report-submit-pill" id="btnSubmitReportChapter">
                    <i class="fa-solid fa-paper-plane"></i> Gửi Báo Lỗi
                </button>
            </div>
        </form>
    </div>
</div>

<?php
get_footer();
