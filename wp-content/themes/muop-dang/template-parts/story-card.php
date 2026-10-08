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

// Thời gian cập nhật gần nhất
$time_diff = human_time_diff(get_post_modified_time('U', false, $story_id), current_time('timestamp')) . ' trước';

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

        <!-- Badges: Full (Đỏ) nếu đã hoàn thành, Mới (Hồng) nếu mới đăng -->
        <?php 
        $is_full = ($status === 'hoan_thanh');
        $is_new  = (current_time('timestamp') - get_the_time('U', $story_id)) <= (14 * 86400);
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
                Chương <?php echo $chap_count ?: 1; ?>
            </span>
            <span class="story-sub-time">
                <?php echo esc_html($time_diff); ?>
            </span>
        </div>
    </div>
</div>
