<?php
/**
 * Template Name: Trang Truyện Mới Cập Nhật
 * Hiển thị tất cả truyện được đăng mới nhất (24 truyện / trang)
 */
if (!defined('ABSPATH')) exit;

get_header();

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$query = new WP_Query(array(
    'post_type'      => 'truyen',
    'posts_per_page' => 24,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
    'paged'          => $paged
));
?>

<div class="container">
    <div class="section-block">
        <!-- Breadcrumb -->
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Truyện Mới Cập Nhật</span>
        </nav>

        <div class="section-header">
            <h1 class="section-title">
                <i class="fa-solid fa-clock-rotate-left"></i> Truyện Mới Cập Nhật
            </h1>
        </div>

        <div class="story-grid">
            <?php
            if ($query->have_posts()) :
                while ($query->have_posts()) : $query->the_post();
                    get_template_part('template-parts/story-card', null, array('show_latest_chapter' => true));
                endwhile;
            else :
                echo '<p style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 40px;">Chưa có truyện nào.</p>';
            endif;
            ?>
        </div>

        <!-- Phân trang 20 truyện / trang (Chỉ hiển thị khi có từ 2 trang trở lên) -->
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
