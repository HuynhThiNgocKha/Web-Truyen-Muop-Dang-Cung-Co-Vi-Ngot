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

        <!-- CENTER 1: ĐƯỜNG DẪN (BREADCRUMB DẠNG DỌC THEO ẢNH 2) -->
        <nav class="reading-vertical-breadcrumbs">
            <div class="bread-row">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="bread-link">Trang chủ</a>
            </div>
            <div class="bread-row">
                <i class="fa-solid fa-angle-right bread-caret"></i>
                <a href="<?php echo esc_url($story_url); ?>" class="bread-link bread-story-link"><?php echo esc_html($story_title); ?></a>
            </div>
            <div class="bread-row bread-current">
                <i class="fa-solid fa-angle-right bread-caret"></i>
                <span class="bread-chapter-name"><?php echo esc_html(muop_get_clean_chapter_title(get_the_title())); ?></span>
            </div>
        </nav>

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
                    <a href="<?php echo esc_url($next_chap_url); ?>" class="btn btn-primary btn-next-chapter btn-chap-next">
                        Chương Sau <i class="fa-solid fa-arrow-right"></i>
                    </a>
                <?php else : ?>
                    <button class="btn btn-primary btn-chap-next" disabled style="opacity: 0.5;">
                        Chương Sau <i class="fa-solid fa-arrow-right"></i>
                    </button>
                <?php endif; ?>
            </div>

            <!-- Điều chỉnh cỡ chữ & mở popup cài đặt đọc truyện theo Ảnh 3 -->
            <div class="reading-font-bar">
                <button type="button" class="font-size-btn" id="btnFontDec" title="Giảm cỡ chữ">A-</button>
                <button type="button" class="font-size-btn" id="btnFontInc" title="Tăng cỡ chữ">A+</button>
                <button type="button" class="font-size-btn btn-reading-settings-trigger" id="btnOpenReadingSettingsTop" title="Tùy chỉnh font chữ & màu sắc">
                    <i class="fa-solid fa-palette"></i>
                </button>
            </div>
        </div>

        <!-- CENTER 3: NỘI DUNG TRUYỆN CỦA CHƯƠNG (CHỐNG COPY) -->
        <article class="chapter-content-box">
            <header class="chapter-header-compact">
                <h1 class="chapter-heading-title"><?php echo esc_html(muop_get_clean_chapter_title(get_the_title())); ?></h1>
                <div class="chapter-meta-date">
                    <i class="fa-regular fa-clock"></i> <?php echo get_the_date('d/m/Y'); ?>
                </div>
            </header>

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
                <button class="btn btn-secondary" disabled style="opacity: 0.5;">
                    <i class="fa-solid fa-arrow-left"></i> Chương Trước
                </button>
            <?php endif; ?>

            <button type="button" class="btn btn-secondary btn-chapter-drawer-toggle" id="btnOpenChapterDrawerBottom" title="Danh sách chương">
                <i class="fa-solid fa-bars"></i> Danh Sách Chương
            </button>

            <?php if ($next_chap_url) : ?>
                <a href="<?php echo esc_url($next_chap_url); ?>" class="btn btn-primary btn-next-chapter">
                    Chương Sau <i class="fa-solid fa-arrow-right"></i>
                </a>
            <?php else : ?>
                <button class="btn btn-primary" disabled style="opacity: 0.5;">
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
            ?>
                <li class="chapter-drawer-item <?php echo $is_current ? 'is-current-chapter' : ''; ?>" data-title="<?php echo esc_attr(mb_strtolower($chap_clean_title)); ?>">
                    <a href="<?php echo esc_url(get_permalink($c->ID)); ?>" class="chapter-drawer-link">
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
     NÚT NỔI CHẾ ĐỘ CHÌM (FLOATING BUTTON)
     Tự động xuất hiện khi lướt đọc truyện xuống. Ở chế độ chìm (mờ nhẹ),
     chỉ khi nhấn vào mới mở popup tùy chỉnh cỡ chữ, font chữ và màu sắc.
     ========================================================================== -->
<button type="button" class="floating-reading-btn btn-reading-settings-trigger" id="btnFloatingReadingSettings" title="Tùy chỉnh đọc truyện (Font, Màu, Cỡ chữ)" style="display: none;">
    <i class="fa-solid fa-sliders"></i>
</button>

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

        <!-- 2. KIỂU CHỮ (FONT) -->
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

        <!-- 5. FOOTER: NÚT KHÔI PHỤC & LIÊN KẾT ĐẾN CÀI ĐẶT HỆ THỐNG -->
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

<?php
get_footer();
