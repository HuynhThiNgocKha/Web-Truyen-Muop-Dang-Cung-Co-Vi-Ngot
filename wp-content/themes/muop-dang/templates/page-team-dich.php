<?php
/**
 * Template Name: Danh Sách Team Dịch
 * Trang liệt kê tất cả các team dịch trong hệ thống
 */
if (!defined('ABSPATH')) exit;

if (!is_user_logged_in()) {
    wp_redirect(home_url('/dang-nhap/?redirect_to=' . urlencode(home_url('/team-dich/'))));
    exit;
}

get_header();

// Lấy tất cả terms trong taxonomy team_dich
$all_teams = get_terms(array(
    'taxonomy'   => 'team_dich',
    'hide_empty' => false,
));
?>

<div class="container">
    <div class="section-block">
        <!-- BREADCRUMBS -->
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Danh Sách Team Dịch</span>
        </nav>

        <div class="section-header" style="margin-bottom: 24px;">
            <div>
                <h1 class="section-title">
                    <i class="fa-solid fa-users"></i> Danh Sách Các Team Dịch
                </h1>
                <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">
                    Khám phá các nhóm dịch giả tài năng, theo dõi và đón đọc những bộ truyện hay nhất!
                </p>
            </div>
            <span style="font-size: 13.5px; color: var(--text-muted); font-weight: 600;">
                Hiện có <?php echo count($all_teams); ?> Team Dịch
            </span>
        </div>

        <?php if (!empty($all_teams) && !is_wp_error($all_teams)) : ?>
            <div class="team-card-grid">
                <?php foreach ($all_teams as $team) : 
                    $team_link = get_term_link($team);
                    $initial   = mb_strtoupper(mb_substr($team->name, 0, 1, 'UTF-8'), 'UTF-8');
                    
                    // Lấy tất cả truyện của team này để tính tổng view
                    $team_stories = get_posts(array(
                        'post_type'      => 'truyen',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'tax_query'      => array(
                            array(
                                'taxonomy' => 'team_dich',
                                'field'    => 'term_id',
                                'terms'    => $team->term_id
                            )
                        )
                    ));

                    $story_count = count($team_stories);
                    $total_views = 0;
                    foreach ($team_stories as $ts) {
                        $total_views += (int) get_post_meta($ts->ID, '_truyen_views', true);
                    }

                    $fav_count = (int) get_term_meta($team->term_id, '_team_favorite_count', true);
                    if ($fav_count < 0) $fav_count = 0;
                ?>
                    <div class="team-list-card">
                        <div class="team-card-avatar">
                            <?php echo esc_html($initial); ?>
                        </div>

                        <div class="team-card-body">
                            <div class="team-card-header">
                                <h2 class="team-card-name">
                                    <a href="<?php echo esc_url($team_link); ?>">
                                        <?php echo esc_html($team->name); ?>
                                    </a>
                                </h2>
                                <span class="badge badge-green" style="font-size: 11px;">
                                    <i class="fa-solid fa-feather-pointed"></i> Dịch Giả
                                </span>
                            </div>

                            <div class="team-card-stats">
                                <div class="team-card-stat">
                                    <i class="fa-solid fa-book-bookmark"></i>
                                    <strong><?php echo $story_count; ?></strong> truyện
                                </div>
                                <div class="team-card-stat">
                                    <i class="fa-solid fa-eye"></i>
                                    <strong><?php echo number_format($total_views); ?></strong> lượt đọc
                                </div>
                                <div class="team-card-stat" style="color: #e91e63;">
                                    <i class="fa-solid fa-heart"></i>
                                    <strong><?php echo number_format($fav_count); ?></strong> yêu thích
                                </div>
                            </div>

                            <a href="<?php echo esc_url($team_link); ?>" class="btn btn-secondary team-card-btn">
                                <span>Xem Trang Team & Truyện</span>
                                <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div style="text-align: center; padding: 60px 20px; background: var(--bg-card); border-radius: var(--radius-md); border: 1px solid var(--border-light);">
                <i class="fa-solid fa-users" style="font-size: 48px; color: var(--text-muted); opacity: 0.5; margin-bottom: 14px;"></i>
                <h3 style="font-size: 17px; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Chưa có team dịch nào trong hệ thống</h3>
                <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 18px;">Khi dịch giả đăng truyện, tên team sẽ tự động xuất hiện tại đây.</p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                    <i class="fa-solid fa-house"></i> Về Trang Chủ
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();
