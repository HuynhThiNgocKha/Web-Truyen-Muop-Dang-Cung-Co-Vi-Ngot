<?php
/**
 * Template Name: Trang Thể Loại
 * Taxonomy / Page Thể Loại Truyện
 * Thiết kế giao diện chuẩn Hình 1 & Hình 2 (dammyy)
 */
if (!defined('ABSPATH')) exit;

get_header();

$queried_obj = get_queried_object();
if ($queried_obj instanceof WP_Term && $queried_obj->taxonomy === 'the_loai') {
    $current_genre_slug = $queried_obj->slug;
    $genre_title = $queried_obj->name;
} else {
    $current_genre_slug = get_query_var('the_loai') ?: (isset($_GET['the_loai']) ? sanitize_text_field($_GET['the_loai']) : '');
    if (!empty($current_genre_slug)) {
        $current_term = get_term_by('slug', $current_genre_slug, 'the_loai');
        $genre_title = $current_term ? $current_term->name : 'Thể loại';
    } else {
        $genre_title = 'Tất Cả Thể Loại';
    }
}
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Cache danh mục thể loại để tăng tốc độ tải trang
$all_genres = wp_cache_get('muop_all_genres_list');
if ($all_genres === false) {
    $all_genres = get_terms(array(
        'taxonomy'   => 'the_loai',
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ));
    wp_cache_set('muop_all_genres_list', $all_genres, '', 3600);
}

// Truy vấn truyện: luôn sắp xếp truyện mới nhất trước
$query_args = array(
    'post_type'      => 'truyen',
    'posts_per_page' => 24,
    'post_status'    => 'publish',
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC'
);

if (!empty($current_genre_slug)) {
    $query_args['tax_query'] = array(
        array(
            'taxonomy' => 'the_loai',
            'field'    => 'slug',
            'terms'    => $current_genre_slug
        )
    );
}

$query = new WP_Query($query_args);

// Tiêu đề trang: TRUYỆN [TÊN THỂ LOẠI] (In hoa, đậm)
$page_heading = !empty($current_genre_slug) ? ('TRUYỆN ' . mb_strtoupper($genre_title, 'UTF-8')) : 'TẤT CẢ THỂ LOẠI';
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

        <!-- Tiêu đề TRUYỆN [TÊN THỂ LOẠI] (Đã bỏ phần sắp xếp, chiếm trọn 1 hàng) -->
        <div class="genre-page-top-bar">
            <h1 class="genre-page-title">
                <?php echo esc_html($page_heading); ?>
            </h1>
        </div>

        <!-- Khung Menu Thể Loại (Khi nhấp vào thể loại thì sau đó ẩn đi, tới khi nhấn vào thanh tiêu đề mới sổ xuống) -->
        <?php
        $is_specific_genre = !empty($current_genre_slug);
        $card_collapsed_class = $is_specific_genre ? 'is-collapsed' : '';
        ?>
        <div class="genre-selector-card <?php echo esc_attr($card_collapsed_class); ?>" id="genreSelectorCard" data-specific-genre="<?php echo $is_specific_genre ? '1' : '0'; ?>">
            <div class="genre-selector-header" id="genreSelectorHeader" role="button" tabindex="0" aria-label="Đóng mở danh sách thể loại">
                <span class="genre-selector-title">
                    <i class="fa-solid fa-layer-group" style="font-size: 14px; color: var(--primary-green);"></i> Thể Loại
                </span>
                <button type="button" class="genre-selector-arrow-btn" id="btnGenreSelectorArrow" aria-label="Thu gọn hoặc mở rộng danh sách thể loại">
                    <i class="fa-solid fa-chevron-up"></i>
                </button>
            </div>

            <div class="genre-selector-body" id="genreSelectorBody">
                <div class="genre-selector-grid">
                    <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="genre-box-btn <?php echo empty($current_genre_slug) ? 'is-active' : ''; ?>">
                        Tất Cả Thể Loại
                    </a>
                    <?php if (!empty($all_genres) && !is_wp_error($all_genres)) : ?>
                        <?php foreach ($all_genres as $g) : 
                            $is_active = ($g->slug === $current_genre_slug);
                            $item_url = get_term_link($g);
                        ?>
                            <a href="<?php echo esc_url($item_url); ?>" class="genre-box-btn <?php echo $is_active ? 'is-active' : ''; ?>" title="<?php echo esc_attr($g->name); ?>">
                                <?php echo esc_html($g->name); ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Danh sách truyện dạng card (Sắp xếp mới nhất trước) -->
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

<script>
(function() {
    function initGenreSelector() {
        var card = document.getElementById('genreSelectorCard');
        var header = document.getElementById('genreSelectorHeader');
        if (!card || !header) return;

        var isSpecificGenre = card.dataset.specificGenre === '1';

        // Kiểm tra trạng thái đã lưu từ sessionStorage
        var savedState = null;
        try {
            savedState = sessionStorage.getItem('muop_genre_collapsed');
        } catch(e) {}

        if (savedState === '1') {
            card.classList.add('is-collapsed');
        } else if (savedState === '0' && !isSpecificGenre) {
            card.classList.remove('is-collapsed');
        }

        // Khi nhấp vào bất kỳ nút thể loại nào -> Lưu cờ để sau đó trang thể loại tự động ẩn đi
        var genreButtons = card.querySelectorAll('.genre-box-btn');
        genreButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                try {
                    sessionStorage.setItem('muop_genre_collapsed', '1');
                } catch(e) {}
            });
        });

        // Bấm vào thanh tiêu đề Thể Loại để đóng / mở (sổ xuống)
        function toggleGenreCard(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            var isCurrentlyCollapsed = card.classList.contains('is-collapsed');
            if (isCurrentlyCollapsed) {
                card.classList.remove('is-collapsed');
                try {
                    sessionStorage.setItem('muop_genre_collapsed', '0');
                } catch(e) {}
            } else {
                card.classList.add('is-collapsed');
                try {
                    sessionStorage.setItem('muop_genre_collapsed', '1');
                } catch(e) {}
            }
        }

        header.addEventListener('click', toggleGenreCard);
        header.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleGenreCard(e);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGenreSelector);
    } else {
        initGenreSelector();
    }
})();
</script>

<?php
get_footer();
