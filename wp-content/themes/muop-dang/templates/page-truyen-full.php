<?php
/**
 * Template Name: Trang Truyện Full
 * Hiển thị tất cả truyện đã full hoàn thành (20 truyện / trang)
 */
if (!defined('ABSPATH')) exit;

get_header();

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$query = new WP_Query(array(
    'post_type'      => 'truyen',
    'posts_per_page' => 20,
    'post_status'    => 'publish',
    'meta_query'     => array(
        array(
            'key'     => '_truyen_status',
            'value'   => 'hoan_thanh',
            'compare' => '='
        )
    ),
    'orderby'        => 'modified',
    'order'          => 'DESC',
    'paged'          => $paged
));
?>

<div class="container">
    <div class="section-block">
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Truyện Đã Hoàn Thành (Full)</span>
        </nav>

        <div class="section-header">
            <h1 class="section-title">
                <i class="fa-solid fa-circle-check"></i> Truyện Đã Hoàn Thành (Full)
            </h1>
            <span style="font-size: 13.5px; color: var(--text-muted);">
                Đọc trọn bộ không lo chờ chương mới
            </span>
        </div>

        <div class="story-grid">
            <?php
            if ($query->have_posts()) :
                while ($query->have_posts()) : $query->the_post();
                    get_template_part('template-parts/story-card');
                endwhile;
            else :
                // Fallback to latest stories
                $fallback = new WP_Query(array('post_type' => 'truyen', 'posts_per_page' => 20, 'post_status' => 'publish'));
                while ($fallback->have_posts()) : $fallback->the_post();
                    get_template_part('template-parts/story-card');
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>

        <!-- Phân trang 20 truyện / trang -->
        <div style="display: flex; justify-content: center; gap: 8px; margin-top: 36px;">
            <?php
            echo paginate_links(array(
                'total'        => $query->max_num_pages,
                'current'      => $paged,
                'prev_text'    => '<i class="fa-solid fa-chevron-left"></i> Trước',
                'next_text'    => 'Sau <i class="fa-solid fa-chevron-right"></i>',
                'type'         => 'plain'
            ));
            wp_reset_postdata();
            ?>
        </div>
    </div>
</div>

<?php
get_footer();
