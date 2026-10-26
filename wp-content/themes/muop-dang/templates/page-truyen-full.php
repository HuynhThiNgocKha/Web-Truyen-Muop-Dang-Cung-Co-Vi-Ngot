<?php
/**
 * Template Name: Trang Truyện Full
 * Hiển thị tất cả truyện đã full hoàn thành (24 truyện / trang)
 */
if (!defined('ABSPATH')) exit;

get_header();

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$query = new WP_Query(array(
    'post_type'      => 'truyen',
    'posts_per_page' => 24,
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
            <span style="color: var(--primary-green); font-weight: 600;">Truyện Full</span>
        </nav>

        <div class="section-header">
            <h1 class="section-title">
                <i class="fa-solid fa-circle-check"></i> Truyện Full
            </h1>
        </div>

        <div class="story-grid">
            <?php
            if ($query->have_posts()) :
                while ($query->have_posts()) : $query->the_post();
                    get_template_part('template-parts/story-card');
                endwhile;
            else :
                // Fallback to latest stories
                $fallback = new WP_Query(array('post_type' => 'truyen', 'posts_per_page' => 24, 'post_status' => 'publish'));
                while ($fallback->have_posts()) : $fallback->the_post();
                    get_template_part('template-parts/story-card');
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>

        <!-- Phân trang 24 truyện / trang (Chỉ hiển thị khi có từ 2 trang trở lên) -->
        <?php if ($query->max_num_pages > 1) : ?>
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
        wp_reset_postdata();
        ?>
    </div>
</div>

<?php
get_footer();
