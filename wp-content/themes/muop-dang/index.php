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
            <p>Một góc nhỏ dành cho những ai đam mê đọc truyện, đến rồi hãy ở lại ghé thăm góc nhỏ này của Mướp nha, chúc các bạn đọc truyện vui vẻ.</p>
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

    <!-- 1. MỤC TRUYỆN ĐỀ CỬ (TỐI ĐA 12 TRUYỆN - KÉO QUA LẠI) -->
    <section class="section-block section-nominated-carousel">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fa-solid fa-star"></i> Truyện Đề Cử
            </h2>
            <div class="carousel-nav-arrows">
                <button type="button" class="carousel-arrow-btn carousel-prev" id="btnNominatedPrev" aria-label="Kéo sang trái">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button type="button" class="carousel-arrow-btn carousel-next" id="btnNominatedNext" aria-label="Kéo sang phải">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <div class="story-carousel-wrap">
            <div class="story-carousel-track story-nominated-track" id="nominatedStoryTrack">
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
                    echo '<p style="color: var(--text-muted); text-align: center; padding: 20px; width: 100%;">Chưa có truyện đề cử nào.</p>';
                endif;
                ?>
            </div>
            <!-- Các dấu chấm màu xanh chỉ báo vị trí truyện đề cử -->
            <div class="story-carousel-dots" id="nominatedStoryDots" aria-label="Chỉ báo vị trí truyện đề cử"></div>
        </div>
    </section>

    <!-- 2. MỤC TRUYỆN MỚI CẬP NHẬT (12 TRUYỆN) -->
    <section class="section-block">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fa-solid fa-clock-rotate-left"></i> Truyện Mới Cập Nhật
            </h2>
            <a href="<?php echo esc_url(home_url('/truyen-moi/')); ?>" class="section-view-all">
                Xem thêm
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
                    get_template_part('template-parts/story-card', null, array('show_latest_chapter' => true));
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
                <i class="fa-solid fa-fire-flame-curved"></i> Truyện Hot
            </h2>
            <a href="<?php echo esc_url(home_url('/truyen-hot/')); ?>" class="section-view-all">
                Xem thêm
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
                <i class="fa-solid fa-circle-check"></i> Truyện Full
            </h2>
            <a href="<?php echo esc_url(home_url('/truyen-full/')); ?>" class="section-view-all">
                Xem thêm
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
