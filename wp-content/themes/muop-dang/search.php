<?php
/**
 * Search Results Template
 */
if (!defined('ABSPATH')) exit;

get_header();

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>

<div class="container">
    <div class="section-block">
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Tìm Kiếm</span>
        </nav>

        <div class="section-header">
            <h1 class="section-title">
                <i class="fa-solid fa-magnifying-glass"></i> Kết Quả Tìm Kiếm: "<?php echo get_search_query(); ?>"
            </h1>
            <span style="font-size: 13.5px; color: var(--text-muted);">
                Tìm thấy <?php echo $wp_query->found_posts; ?> truyện
            </span>
        </div>

        <div class="story-grid">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
                    if (get_post_type() === 'truyen') {
                        get_template_part('template-parts/story-card');
                    }
                endwhile;
            else :
                echo '<p style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 40px;">Không tìm thấy truyện nào khớp với từ khóa của bạn.</p>';
            endif;
            ?>
        </div>

        <div style="display: flex; justify-content: center; gap: 8px; margin-top: 36px;">
            <?php
            echo paginate_links(array(
                'total'     => $wp_query->max_num_pages,
                'current'   => $paged,
                'prev_text' => '<i class="fa-solid fa-chevron-left"></i> Trước',
                'next_text' => 'Sau <i class="fa-solid fa-chevron-right"></i>'
            ));
            ?>
        </div>
    </div>
</div>

<?php
get_footer();
