<?php
/**
 * Template Part: Story Card (Truyện)
 * Thiết kế chuẩn 2 cột dạng Card ảnh MonkeyD (Hình 2)
 */
if (!defined('ABSPATH')) exit;

$story_id  = get_the_ID();
$views     = muop_get_story_views($story_id);
$status    = get_post_meta($story_id, '_truyen_status', true);
$nominate  = get_post_meta($story_id, '_truyen_nominated', true);
$author    = get_post_meta($story_id, '_truyen_author_name', true);
$team      = get_post_meta($story_id, '_truyen_team', true);
$bookmarks = intval(get_post_meta($story_id, '_truyen_bookmarks_count', true));

// Count published chapters
$chapters   = muop_get_story_chapters($story_id);
$chap_count = count($chapters);

// Số chương mới nhất nếu có
$latest_chapter_num = 0;
if (!empty($chapters)) {
    $last_chap = end($chapters);
    $latest_chapter_num = intval(get_post_meta($last_chap->ID, '_chuong_number', true));
    if (!$latest_chapter_num) {
        $latest_chapter_num = $chap_count;
    }
}

// Kiểm tra xem có phải mục "Truyện Mới Cập Nhật" hay không
$is_new_updated = (!empty($args) && !empty($args['show_latest_chapter'])) || is_page('truyen-moi');

// Thời gian cập nhật gần nhất
$story_update_data = muop_get_story_last_updated($story_id);
$time_diff         = $story_update_data['time_ago'] ?: 'Mới';

$thumb_url = get_the_post_thumbnail_url($story_id, 'muop-cover');
if (!$thumb_url) {
    $thumb_url = MUOP_THEME_URI . '/assets/images/default-cover.svg';
}
?>

<div class="story-card">
    <div class="story-cover-wrap">
        <a href="<?php the_permalink(); ?>">
            <img class="story-cover-img" src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
        </a>

        <!-- Badges: Full (Đỏ) nếu đã hoàn thành, Mới (Hồng) nếu mới đăng trong vòng 24h -->
        <?php 
        $is_full   = ($status === 'hoan_thanh');
        $story_age = current_time('timestamp') - (int) get_the_time('U', $story_id);
        $is_new    = ($story_age >= -60 && $story_age <= 86400);
        ?>
        <?php if ($is_full || $is_new) : ?>
            <div class="story-badge-top">
                <?php if ($is_full) : ?>
                    <span class="badge badge-full">Full</span>
                <?php endif; ?>
                <?php if ($is_new) : ?>
                    <span class="badge badge-new">Mới</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Dải thông tin lượt xem & bookmark đè đáy ảnh bìa (chuẩn Hình 2) -->
        <div class="story-cover-meta-bar">
            <span class="meta-views" title="Lượt xem">
                <i class="fa-regular fa-eye"></i> <?php echo number_format($views); ?>
            </span>
            <span class="meta-bookmarks" title="Đánh dấu">
                <i class="fa-regular fa-bookmark"></i> <?php echo number_format($bookmarks); ?>
            </span>
        </div>
    </div>

    <div class="story-card-body">
        <a href="<?php the_permalink(); ?>">
            <h3 class="story-title" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></h3>
        </a>

        <!-- Hàng hiển thị: Chương & Thời gian cập nhật (chuẩn Hình 2) -->
        <div class="story-card-subrow">
            <span class="story-sub-chap">
                <?php if ($is_new_updated) : ?>
                    Chương <?php echo $latest_chapter_num ?: ($chap_count ?: 1); ?>
                <?php else : ?>
                    <?php echo $chap_count; ?> Chương
                <?php endif; ?>
            </span>
            <span class="story-sub-time" title="<?php echo esc_attr($story_update_data['formatted']); ?>">
                <?php echo esc_html($time_diff); ?>
            </span>
        </div>
    </div>
</div>
