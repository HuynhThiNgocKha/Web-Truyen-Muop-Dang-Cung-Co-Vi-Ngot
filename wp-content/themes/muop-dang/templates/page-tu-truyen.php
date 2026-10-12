<?php
/**
 * Template Name: Trang Tủ Truyện
 * Trang riêng hiển thị tất cả các bộ truyện độc giả đã lưu vào Tủ truyện
 */
if (!defined('ABSPATH')) exit;

if (!is_user_logged_in()) {
    wp_redirect(home_url('/dang-nhap/?redirect_to=' . urlencode(home_url('/tu-truyen/'))));
    exit;
}

get_header();

global $wpdb;
$table_bookmarks = $wpdb->prefix . 'reading_bookmarks';
$is_logged_in    = is_user_logged_in();
$user_id         = get_current_user_id();

$bookmarked_story_ids = array();
if ($is_logged_in) {
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_bookmarks'") === $table_bookmarks) {
        $bookmarked_story_ids = $wpdb->get_col($wpdb->prepare(
            "SELECT story_id FROM $table_bookmarks WHERE user_id = %d ORDER BY created_at DESC",
            $user_id
        ));
    }
}

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$has_stories = !empty($bookmarked_story_ids);

if ($has_stories) {
    $query_tu_truyen = new WP_Query(array(
        'post_type'      => 'truyen',
        'post__in'       => $bookmarked_story_ids,
        'orderby'        => 'post__in',
        'posts_per_page' => 20,
        'post_status'    => 'publish',
        'paged'          => $paged
    ));
}
?>

<div class="container">
    <div class="section-block">
        <!-- BREADCRUMBS -->
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Tủ Truyện Cá Nhân</span>
        </nav>

        <div class="section-header" style="margin-bottom: 24px;">
            <div>
                <h1 class="section-title">
                    <i class="fa-solid fa-bookmark"></i> Tủ Truyện Của Tôi
                </h1>
                <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">
                    Nơi lưu trữ những bộ truyện bạn đang theo dõi. Đọc tiếp mọi lúc, mọi nơi!
                </p>
            </div>
            <?php if ($has_stories) : ?>
                <span style="font-size: 13.5px; color: var(--text-muted); font-weight: 600;">
                    Đã lưu <?php echo count($bookmarked_story_ids); ?> bộ truyện
                </span>
            <?php endif; ?>
        </div>

        <?php if (!$is_logged_in) : ?>
            <!-- KHÁCH CHƯA ĐĂNG NHẬP -->
            <div class="tu-truyen-auth-prompt" style="background: var(--bg-card); border: 1.5px dashed var(--border-color); border-radius: var(--radius-lg); padding: 40px 24px; text-align: center; margin-bottom: 30px;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--pastel-green); color: var(--primary-green); display: flex; align-items: center; justify-content: center; font-size: 26px; margin: 0 auto 16px;">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">
                    Đăng nhập để xem Tủ Truyện của bạn
                </h3>
                <p style="font-size: 14px; color: var(--text-muted); max-width: 500px; margin: 0 auto 20px; line-height: 1.55;">
                    Đăng nhập tài khoản giúp bạn đồng bộ danh sách truyện đã lưu trên mọi thiết bị (máy tính, điện thoại, máy tính bảng) mà không lo bị mất.
                </p>
                <div style="display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;">
                    <a href="<?php echo esc_url(home_url('/dang-nhap/?redirect_to=' . urlencode(home_url('/tu-truyen/')))); ?>" class="btn btn-primary" style="padding: 9px 24px;">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Đăng Nhập Ngay
                    </a>
                    <a href="<?php echo esc_url(home_url('/dang-ky/')); ?>" class="btn btn-secondary" style="padding: 9px 24px;">
                        <i class="fa-solid fa-user-plus"></i> Tạo Tài Khoản Mới
                    </a>
                </div>
            </div>
        <?php elseif ($has_stories && $query_tu_truyen->have_posts()) : ?>
            <!-- DANH SÁCH TRUYỆN TRONG TỦ -->
            <div class="story-grid tu-truyen-grid" id="tuTruyenStoryGrid">
                <?php while ($query_tu_truyen->have_posts()) : $query_tu_truyen->the_post(); 
                    $sid = get_the_ID();
                ?>
                    <div class="tu-truyen-item-wrap" id="tuTruyenItem-<?php echo $sid; ?>" style="position: relative;">
                        <?php get_template_part('template-parts/story-card'); ?>
                        
                        <button type="button" class="btn-remove-from-cupboard" data-story-id="<?php echo $sid; ?>" title="Xóa khỏi tủ truyện">
                            <i class="fa-solid fa-xmark"></i> Bỏ Lưu
                        </button>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- PHÂN TRANG NẾU CÓ TRÊN 20 TRUYỆN -->
            <?php if ($query_tu_truyen->max_num_pages > 1) : ?>
                <div style="display: flex; justify-content: center; gap: 8px; margin-top: 36px;">
                    <?php
                    echo paginate_links(array(
                        'total'        => $query_tu_truyen->max_num_pages,
                        'current'      => $paged,
                        'prev_text'    => '<i class="fa-solid fa-chevron-left"></i> Trước',
                        'next_text'    => 'Sau <i class="fa-solid fa-chevron-right"></i>',
                        'type'         => 'plain'
                    ));
                    wp_reset_postdata();
                    ?>
                </div>
            <?php endif; ?>

        <?php else : ?>
            <!-- TỦ TRUYỆN TRỐNG -->
            <div class="tu-truyen-empty-box" style="text-align: center; padding: 60px 20px; background: var(--bg-card); border-radius: var(--radius-lg); border: 1px solid var(--border-light);">
                <div style="width: 72px; height: 72px; border-radius: 50%; background: var(--pastel-green); color: var(--primary-green); display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 16px;">
                    <i class="fa-regular fa-bookmark"></i>
                </div>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">
                    Tủ truyện của bạn đang trống!
                </h3>
                <p style="font-size: 14px; color: var(--text-muted); max-width: 480px; margin: 0 auto 24px; line-height: 1.55;">
                    Bạn chưa lưu bộ truyện nào vào tủ. Khi duyệt đọc truyện, hãy bấm nút <strong>"Lưu Vào Tủ Truyện"</strong> để dễ dàng theo dõi các chương mới nhé!
                </p>
                <div style="display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;">
                    <a href="<?php echo esc_url(home_url('/truyen-hot/')); ?>" class="btn btn-primary">
                        <i class="fa-solid fa-fire"></i> Khám Phá Truyện Hot
                    </a>
                    <a href="<?php echo esc_url(home_url('/truyen-moi/')); ?>" class="btn btn-secondary">
                        <i class="fa-solid fa-clock"></i> Truyện Mới Cập Nhật
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();
