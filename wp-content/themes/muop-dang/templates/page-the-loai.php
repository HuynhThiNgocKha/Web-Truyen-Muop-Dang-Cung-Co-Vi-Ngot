<?php
/**
 * Template Name: Trang Thể Loại
 * Taxonomy / Page Thể Loại Truyện
 */
if (!defined('ABSPATH')) exit;

get_header();

$current_genre_slug = get_query_var('the_loai') ?: (isset($_GET['the_loai']) ? sanitize_text_field($_GET['the_loai']) : '');
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$all_genres = get_terms(array(
    'taxonomy'   => 'the_loai',
    'hide_empty' => false,
));

$query_args = array(
    'post_type'      => 'truyen',
    'posts_per_page' => 20,
    'post_status'    => 'publish',
    'paged'          => $paged
);

if (!empty($current_genre_slug)) {
    $query_args['tax_query'] = array(
        array(
            'taxonomy' => 'the_loai',
            'field'    => 'slug',
            'terms'    => $current_genre_slug
        )
    );
    $current_term = get_term_by('slug', $current_genre_slug, 'the_loai');
    $genre_title = $current_term ? $current_term->name : 'Thể loại';
} else {
    $genre_title = 'Tất Cả Thể Loại';
}

$query = new WP_Query($query_args);
?>

<div class="container">
    <div class="section-block">
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>">Thể loại</a>
            <?php if (!empty($current_genre_slug)) : ?>
                <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
                <span style="color: var(--primary-green); font-weight: 600;"><?php echo esc_html($genre_title); ?></span>
            <?php endif; ?>
        </nav>

        <!-- Danh sách tất cả thể loại (Pill Filter) -->
        <div style="background: var(--bg-card); padding: 18px 20px; border-radius: var(--radius-md); border: 1px solid var(--border-light); margin-bottom: 24px;">
            <div style="font-size: 13.5px; font-weight: 700; color: var(--text-muted); margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-filter" style="color: var(--primary-green);"></i> Chọn Thể Loại Truyện:
            </div>
            <div class="genre-tag-list">
                <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="genre-pill <?php echo empty($current_genre_slug) ? 'active' : ''; ?>" style="<?php echo empty($current_genre_slug) ? 'background: var(--primary-green); color: #fff;' : ''; ?>">
                    Tất Cả
                </a>
                <?php if (!empty($all_genres) && !is_wp_error($all_genres)) : ?>
                    <?php foreach ($all_genres as $g) : 
                        $is_active = ($g->slug === $current_genre_slug);
                    ?>
                        <a href="<?php echo esc_url(get_term_link($g)); ?>" class="genre-pill" style="<?php echo $is_active ? 'background: var(--primary-green); color: #fff;' : ''; ?>">
                            <?php echo esc_html($g->name); ?> (<?php echo $g->count; ?>)
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="section-header">
            <h1 class="section-title">
                <i class="fa-solid fa-tags"></i> Thể Loại: <?php echo esc_html($genre_title); ?>
            </h1>
            <span style="font-size: 13.5px; color: var(--text-muted);">
                Hiển thị <?php echo $query->found_posts; ?> truyện phù hợp
            </span>
        </div>

        <div class="story-grid">
            <?php
            if ($query->have_posts()) :
                while ($query->have_posts()) : $query->the_post();
                    get_template_part('template-parts/story-card');
                endwhile;
            else :
                echo '<p style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 40px;">Chưa có truyện nào thuộc thể loại này.</p>';
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
