<?php
/**
 * Main Index / Homepage Template
 * Trang Chủ: Truyện Đề Cử (12), Truyện Mới (12), Truyện Hot (12), Truyện Full (12)
 */
if (!defined('ABSPATH')) exit;

get_header();
?>

<div class="container">
    <!-- Welcome / Hero Banner -->
    <div class="welcome-banner">
        <div class="banner-content">
            <h2>Chào Mừng Đến Với Mướp Đắng Cũng Có Vị Ngọt <i class="fa-solid fa-seedling" style="color: var(--avocado-green);"></i></h2>
            <p>Nơi quy tụ những bộ truyện Zhihu dịch chuẩn, chọn lọc tinh tế, ngọt sủng, ngược tâm và drama cuốn hút mỗi ngày.</p>
        </div>
        <div class="banner-actions">
            <a href="<?php echo esc_url(home_url('/truyen-hot/')); ?>" class="btn btn-primary">
                <i class="fa-solid fa-fire"></i> Khám Phá Truyện Hot
            </a>
            <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="btn btn-secondary">
                <i class="fa-solid fa-list-ul"></i> Tất Cả Thể Loại
            </a>
        </div>
    </div>

    <!-- 1. MỤC TRUYỆN ĐỀ CỬ (12 TRUYỆN) -->
    <section class="section-block">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fa-solid fa-star"></i> Truyện Đề Cử
            </h2>
            <span class="badge badge-nominate">BTV Khuyên Đọc</span>
        </div>

        <div class="story-grid">
            <?php
            $query_nominate = new WP_Query(array(
                'post_type'      => 'truyen',
                'posts_per_page' => 12,
                'post_status'    => 'publish',
                'meta_query'     => array(
                    'relation' => 'OR',
                    array(
                        'key'     => '_truyen_nominated',
                        'value'   => array('day', 'week', 'month'),
                        'compare' => 'IN'
                    ),
                    array(
                        'key'     => '_truyen_views',
                        'value'   => 0,
                        'compare' => '>='
                    )
                ),
                'orderby'        => 'meta_value_num',
                'meta_key'       => '_truyen_views',
                'order'          => 'DESC'
            ));

            if ($query_nominate->have_posts()) :
                while ($query_nominate->have_posts()) : $query_nominate->the_post();
                    get_template_part('template-parts/story-card');
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p style="grid-column: 1/-1; color: var(--text-muted); text-align: center; padding: 20px;">Chưa có truyện đề cử nào.</p>';
            endif;
            ?>
        </div>
    </section>

    <!-- 2. MỤC TRUYỆN MỚI CẬP NHẬT (12 TRUYỆN) -->
    <section class="section-block">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fa-solid fa-clock-rotate-left"></i> Truyện Mới Cập Nhật
            </h2>
            <a href="<?php echo esc_url(home_url('/truyen-moi/')); ?>" class="section-view-all">
                Xem thêm <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="story-grid">
            <?php
            $query_new = new WP_Query(array(
                'post_type'      => 'truyen',
                'posts_per_page' => 12,
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC'
            ));

            if ($query_new->have_posts()) :
                while ($query_new->have_posts()) : $query_new->the_post();
                    get_template_part('template-parts/story-card');
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p style="grid-column: 1/-1; color: var(--text-muted); text-align: center; padding: 20px;">Chưa có truyện mới cập nhật.</p>';
            endif;
            ?>
        </div>
    </section>

    <!-- 3. MỤC TRUYỆN HOT (12 TRUYỆN VIEW CAO NHẤT) -->
    <section class="section-block">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fa-solid fa-fire-flame-curved"></i> Truyện Hot Nhiều Lượt Xem
            </h2>
            <a href="<?php echo esc_url(home_url('/truyen-hot/')); ?>" class="section-view-all">
                Xem thêm <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="story-grid">
            <?php
            $query_hot = new WP_Query(array(
                'post_type'      => 'truyen',
                'posts_per_page' => 12,
                'post_status'    => 'publish',
                'meta_key'       => '_truyen_views',
                'orderby'        => 'meta_value_num',
                'order'          => 'DESC'
            ));

            if ($query_hot->have_posts()) :
                while ($query_hot->have_posts()) : $query_hot->the_post();
                    get_template_part('template-parts/story-card');
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p style="grid-column: 1/-1; color: var(--text-muted); text-align: center; padding: 20px;">Chưa có truyện hot.</p>';
            endif;
            ?>
        </div>
    </section>

    <!-- 4. MỤC TRUYỆN FULL (12 TRUYỆN HOÀN THÀNH) -->
    <section class="section-block">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fa-solid fa-circle-check"></i> Truyện Đã Hoàn Thành (Full)
            </h2>
            <a href="<?php echo esc_url(home_url('/truyen-full/')); ?>" class="section-view-all">
                Xem thêm <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="story-grid">
            <?php
            $query_full = new WP_Query(array(
                'post_type'      => 'truyen',
                'posts_per_page' => 12,
                'post_status'    => 'publish',
                'meta_query'     => array(
                    array(
                        'key'     => '_truyen_status',
                        'value'   => 'hoan_thanh',
                        'compare' => '='
                    )
                ),
                'orderby'        => 'modified',
                'order'          => 'DESC'
            ));

            if ($query_full->have_posts()) :
                while ($query_full->have_posts()) : $query_full->the_post();
                    get_template_part('template-parts/story-card');
                endwhile;
                wp_reset_postdata();
            else :
                // If not many marked full, fallback to latest
                $query_fallback = new WP_Query(array('post_type' => 'truyen', 'posts_per_page' => 12, 'post_status' => 'publish'));
                while ($query_fallback->have_posts()) : $query_fallback->the_post();
                    get_template_part('template-parts/story-card');
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </section>
</div>

<?php
get_footer();
