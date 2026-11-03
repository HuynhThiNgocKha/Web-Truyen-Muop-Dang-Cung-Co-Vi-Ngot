<?php
/**
 * Template Name: Trang Truyện Audio
 * Hiển thị tất cả các bộ truyện có giọng đọc audio (24 truyện / trang)
 * Những truyện không có audio sẽ không xuất hiện ở đây
 */
if (!defined('ABSPATH')) exit;

get_header();

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$audio_story_ids = function_exists('muop_get_story_ids_with_audio') ? muop_get_story_ids_with_audio() : array(0);

$has_audio_stories = (!empty($audio_story_ids) && $audio_story_ids !== array(0));

if ($has_audio_stories) {
    $query = new WP_Query(array(
        'post_type'      => 'truyen',
        'posts_per_page' => 24,
        'post_status'    => 'publish',
        'post__in'       => $audio_story_ids,
        'orderby'        => 'modified',
        'order'          => 'DESC',
        'paged'          => $paged
    ));
} else {
    $query = null;
}
?>

<div class="container">
    <div class="section-block">
        <!-- Breadcrumb -->
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Truyện Audio</span>
        </nav>

        <div class="section-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <div>
                <h1 class="section-title" style="margin-bottom: 4px;">
                    <i class="fa-solid fa-headphones" style="color: #16a34a;"></i> Truyện Audio
                </h1>
                <p style="font-size: 13px; color: var(--text-muted); margin: 0;">
                    Tổng hợp tất cả bộ truyện có giọng đọc audio truyền cảm, sống động
                </p>
            </div>
            <?php if ($has_audio_stories && $query && $query->found_posts > 0) : ?>
                <span class="badge" style="background: #dcfce7; color: #166534; font-size: 12.5px; font-weight: 700; padding: 6px 14px; border-radius: 20px; border: 1px solid #bbf7d0;">
                    <i class="fa-solid fa-volume-high"></i> <?php echo intval($query->found_posts); ?> Bộ Truyện Audio
                </span>
            <?php endif; ?>
        </div>

        <div class="story-grid">
            <?php
            if ($has_audio_stories && $query && $query->have_posts()) :
                while ($query->have_posts()) : $query->the_post();
                    get_template_part('template-parts/story-card');
                endwhile;
            else :
                echo '<div style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 60px 20px; background: var(--bg-card); border-radius: var(--radius-md); border: 1px dashed var(--border-color);">';
                echo '<i class="fa-solid fa-headphones-simple" style="font-size: 48px; color: #cbd5e1; margin-bottom: 14px; display: block;"></i>';
                echo '<h3 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 6px;">Chưa có truyện audio nào</h3>';
                echo '<p style="font-size: 13.5px; max-width: 420px; margin: 0 auto; color: var(--text-muted);">Hiện tại chưa có bộ truyện nào phát hành audio. Khi dịch giả tải lên audio hoặc chuyển giọng nói, truyện sẽ tự động xuất hiện tại đây!</p>';
                echo '</div>';
            endif;
            ?>
        </div>

        <!-- Phân trang 24 truyện / trang -->
        <?php if ($has_audio_stories && $query && $query->max_num_pages > 1) : ?>
            <div class="pagination-wrapper">
                <?php
                echo paginate_links(array(
                    'total'        => $query->max_num_pages,
                    'current'      => $paged,
                    'prev_text'    => '<i class="fa-solid fa-chevron-left"></i> Trước',
                    'next_text'    => 'Sau <i class="fa-solid fa-chevron-right"></i>',
                    'type'         => 'plain'
                ));
                ?>
            </div>
        <?php endif; 
        if ($query) wp_reset_postdata();
        ?>
    </div>
</div>

<?php
get_footer();
