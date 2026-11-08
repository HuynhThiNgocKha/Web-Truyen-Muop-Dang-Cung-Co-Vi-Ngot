<?php
/**
 * Template Name: Trang Tủ Truyện
 * Trang quản lý Tủ Truyện Cá Nhân: Truyện đã lưu, Truyện yêu thích, Truyện đang theo dõi,
 * Team dịch đã yêu thích, Team dịch đang theo dõi, và Truyện đã đọc (Lịch sử đọc)
 */
if (!defined('ABSPATH')) exit;

if (!is_user_logged_in()) {
    wp_redirect(home_url('/dang-nhap/?redirect_to=' . urlencode(home_url('/tu-truyen/'))));
    exit;
}

get_header();

global $wpdb;
$user_id = get_current_user_id();

// 1. Xác định Tab hiện tại
$valid_tabs = array('saved', 'favorites', 'following', 'teams_favorite', 'teams_following', 'history');
$current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'saved';
if (!in_array($current_tab, $valid_tabs)) {
    $current_tab = 'saved';
}

// 2. Lấy dữ liệu & Đếm số lượng cho từng Tab
// Tab 1: Truyện đã lưu (Bookmarks)
$table_bookmarks = $wpdb->prefix . 'reading_bookmarks';
$saved_story_ids = array();
if ($wpdb->get_var("SHOW TABLES LIKE '$table_bookmarks'") === $table_bookmarks) {
    $saved_story_ids = $wpdb->get_col($wpdb->prepare(
        "SELECT story_id FROM $table_bookmarks WHERE user_id = %d ORDER BY created_at DESC",
        $user_id
    )) ?: array();
    $saved_story_ids = array_map('intval', array_unique($saved_story_ids));
}
$count_saved = count($saved_story_ids);

// Tab 2: Truyện yêu thích (Favorites)
$fav_story_ids = get_user_meta($user_id, '_muop_favorite_stories', true) ?: array();
$fav_story_ids = is_array($fav_story_ids) ? array_map('intval', array_unique($fav_story_ids)) : array();
$count_favorites = count($fav_story_ids);

// Tab 3: Truyện đang theo dõi (Following Stories)
$following_story_ids = get_user_meta($user_id, '_muop_followed_stories', true) ?: array();
$following_story_ids = is_array($following_story_ids) ? array_map('intval', array_unique($following_story_ids)) : array();
$count_following = count($following_story_ids);

// Tab 4: Team dịch đã yêu thích (Favorite Teams)
$fav_team_ids = get_user_meta($user_id, '_muop_favorite_teams', true) ?: array();
$fav_team_ids = is_array($fav_team_ids) ? array_map('intval', array_unique($fav_team_ids)) : array();
$count_teams_fav = count($fav_team_ids);

// Tab 5: Team dịch đang theo dõi (Following Teams)
$following_team_ids = get_user_meta($user_id, '_muop_followed_teams', true) ?: array();
$following_team_ids = is_array($following_team_ids) ? array_map('intval', array_unique($following_team_ids)) : array();
$count_teams_following = count($following_team_ids);

// Tab 6: Truyện đã đọc (Reading History)
$table_history = $wpdb->prefix . 'reading_history';
$history_rows = array();
if ($wpdb->get_var("SHOW TABLES LIKE '$table_history'") === $table_history) {
    $history_rows = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_history WHERE user_id = %d ORDER BY updated_at DESC LIMIT 50",
        $user_id
    )) ?: array();
}
$count_history = count($history_rows);

// 3. Metadata tiêu đề cho từng Tab
$tab_meta = array(
    'saved' => array(
        'title'    => 'Truyện Đã Lưu',
        'subtitle' => 'Nơi lưu trữ những bộ truyện bạn đã thêm vào Tủ truyện để đọc dần.',
        'icon'     => 'fa-solid fa-bookmark',
        'color'    => 'var(--primary-green)'
    ),
    'favorites' => array(
        'title'    => 'Truyện Yêu Thích',
        'subtitle' => 'Những câu chuyện tâm đắc nhất mà bạn đã thả tim yêu thích.',
        'icon'     => 'fa-solid fa-heart',
        'color'    => '#f43f5e'
    ),
    'following' => array(
        'title'    => 'Truyện Đang Theo Dõi',
        'subtitle' => 'Bạn sẽ nhận được thông báo tự động ngay khi dịch giả phát hành chương mới!',
        'icon'     => 'fa-solid fa-bell',
        'color'    => '#8b5cf6'
    ),
    'teams_favorite' => array(
        'title'    => 'Team Dịch Đã Yêu Thích',
        'subtitle' => 'Danh sách các nhóm dịch bạn yêu thích và luôn đồng hành.',
        'icon'     => 'fa-solid fa-users-viewfinder',
        'color'    => '#ec4899'
    ),
    'teams_following' => array(
        'title'    => 'Team Dịch Đang Theo Dõi',
        'subtitle' => 'Theo dõi nhóm dịch để đón nhận những bộ truyện mới phát hành sớm nhất.',
        'icon'     => 'fa-solid fa-user-check',
        'color'    => '#10b981'
    ),
    'history' => array(
        'title'    => 'Truyện Đã Đọc',
        'subtitle' => 'Lưu vết các chương bạn đã đọc để dễ dàng đọc tiếp mọi lúc, mọi nơi.',
        'icon'     => 'fa-solid fa-clock-rotate-left',
        'color'    => '#0284c7'
    ),
);

$curr_meta = $tab_meta[$current_tab];
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>

<div class="container">
    <div class="section-block tu-truyen-main-wrapper">
        <!-- BREADCRUMBS -->
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <a href="<?php echo esc_url(home_url('/tu-truyen/')); ?>">Tủ Truyện Cá Nhân</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;"><?php echo esc_html($curr_meta['title']); ?></span>
        </nav>

        <!-- HEADER SECTION (Đã bỏ dòng giới thiệu và thanh tabs theo yêu cầu) -->
        <div class="section-header tu-truyen-header" style="margin-bottom: 24px;">
            <div>
                <h1 class="section-title">
                    <i class="<?php echo esc_attr($curr_meta['icon']); ?>" style="color: <?php echo esc_attr($curr_meta['color']); ?>; margin-right: 8px;"></i>
                    <?php echo esc_html($curr_meta['title']); ?>
                </h1>
            </div>
            
            <?php if ($current_tab === 'history' && $count_history > 0) : ?>
                <button type="button" class="btn btn-secondary btn-clear-all-history" id="btnClearAllHistory" title="Xóa toàn bộ lịch sử đọc">
                    <i class="fa-solid fa-trash-can"></i> Xóa Tất Cả Lịch Sử
                </button>
            <?php endif; ?>
        </div>

        <!-- ==================== TAB 1: TRUYỆN ĐÃ LƯU ==================== -->
        <?php if ($current_tab === 'saved') : ?>
            <?php if ($count_saved > 0) : 
                $query_saved = new WP_Query(array(
                    'post_type'      => 'truyen',
                    'post__in'       => $saved_story_ids,
                    'orderby'        => 'post__in',
                    'posts_per_page' => 20,
                    'post_status'    => 'publish',
                    'paged'          => $paged
                ));
            ?>
                <?php if ($query_saved->have_posts()) : ?>
                    <div class="story-grid tu-truyen-grid" id="tuTruyenStoryGrid">
                        <?php while ($query_saved->have_posts()) : $query_saved->the_post(); 
                            $sid = get_the_ID();
                        ?>
                            <div class="tu-truyen-item-wrap" id="tuTruyenItem-<?php echo $sid; ?>" style="position: relative;">
                                <?php get_template_part('template-parts/story-card'); ?>
                                <button type="button" class="btn-remove-from-cupboard" data-story-id="<?php echo $sid; ?>" title="Xóa khỏi truyện đã lưu">
                                    <i class="fa-solid fa-xmark"></i> Bỏ Lưu
                                </button>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>

                    <?php if ($query_saved->max_num_pages > 1) : ?>
                        <div class="pagination-wrapper" style="display: flex; justify-content: center; gap: 8px; margin-top: 36px;">
                            <?php
                            echo paginate_links(array(
                                'total'     => $query_saved->max_num_pages,
                                'current'   => $paged,
                                'prev_text' => '<i class="fa-solid fa-chevron-left"></i> Trước',
                                'next_text' => 'Sau <i class="fa-solid fa-chevron-right"></i>',
                                'type'      => 'plain'
                            ));
                            ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else : ?>
                <div class="tu-truyen-empty-box">
                    <div class="empty-icon-circle" style="background: var(--pastel-green); color: var(--primary-green);">
                        <i class="fa-regular fa-bookmark"></i>
                    </div>
                    <h3>Tủ truyện đã lưu đang trống!</h3>
                    <p>Bạn chưa lưu bộ truyện nào vào Tủ truyện. Hãy duyệt đọc và bấm nút <strong>"Lưu Vào Tủ Truyện"</strong> để đọc tiếp bất cứ lúc nào nhé!</p>
                    <div class="empty-actions">
                        <a href="<?php echo esc_url(home_url('/truyen-hot/')); ?>" class="btn btn-primary">
                            <i class="fa-solid fa-fire"></i> Khám Phá Truyện Hot
                        </a>
                        <a href="<?php echo esc_url(home_url('/truyen-moi/')); ?>" class="btn btn-secondary">
                            <i class="fa-solid fa-clock"></i> Truyện Mới Cập Nhật
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        <!-- ==================== TAB 2: TRUYỆN YÊU THÍCH ==================== -->
        <?php elseif ($current_tab === 'favorites') : ?>
            <?php if ($count_favorites > 0) : 
                $query_fav = new WP_Query(array(
                    'post_type'      => 'truyen',
                    'post__in'       => $fav_story_ids,
                    'orderby'        => 'post__in',
                    'posts_per_page' => 20,
                    'post_status'    => 'publish',
                    'paged'          => $paged
                ));
            ?>
                <?php if ($query_fav->have_posts()) : ?>
                    <div class="story-grid tu-truyen-grid" id="tuTruyenStoryGrid">
                        <?php while ($query_fav->have_posts()) : $query_fav->the_post(); 
                            $sid = get_the_ID();
                        ?>
                            <div class="tu-truyen-item-wrap" id="tuTruyenFavItem-<?php echo $sid; ?>" style="position: relative;">
                                <?php get_template_part('template-parts/story-card'); ?>
                                <button type="button" class="btn-remove-from-favorites" data-story-id="<?php echo $sid; ?>" title="Bỏ yêu thích truyện này">
                                    <i class="fa-solid fa-heart-crack"></i> Bỏ Thích
                                </button>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>

                    <?php if ($query_fav->max_num_pages > 1) : ?>
                        <div class="pagination-wrapper" style="display: flex; justify-content: center; gap: 8px; margin-top: 36px;">
                            <?php
                            echo paginate_links(array(
                                'total'     => $query_fav->max_num_pages,
                                'current'   => $paged,
                                'prev_text' => '<i class="fa-solid fa-chevron-left"></i> Trước',
                                'next_text' => 'Sau <i class="fa-solid fa-chevron-right"></i>',
                                'type'      => 'plain'
                            ));
                            ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else : ?>
                <div class="tu-truyen-empty-box">
                    <div class="empty-icon-circle" style="background: #ffe4e6; color: #f43f5e;">
                        <i class="fa-regular fa-heart"></i>
                    </div>
                    <h3>Danh sách truyện yêu thích đang trống!</h3>
                    <p>Bạn chưa thả tim bộ truyện nào. Khi tìm thấy câu chuyện hợp gu, hãy bấm nút <strong>"Yêu Thích Truyện"</strong> để lưu giữ vào đây nhé!</p>
                    <div class="empty-actions">
                        <a href="<?php echo esc_url(home_url('/truyen-hot/')); ?>" class="btn btn-primary">
                            <i class="fa-solid fa-fire"></i> Khám Phá Truyện Hot
                        </a>
                        <a href="<?php echo esc_url(home_url('/the-loai/')); ?>" class="btn btn-secondary">
                            <i class="fa-solid fa-tags"></i> Tìm Theo Thể Loại
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        <!-- ==================== TAB 3: TRUYỆN ĐANG THEO DÕI ==================== -->
        <?php elseif ($current_tab === 'following') : ?>
            <?php if ($count_following > 0) : 
                $query_follow = new WP_Query(array(
                    'post_type'      => 'truyen',
                    'post__in'       => $following_story_ids,
                    'orderby'        => 'post__in',
                    'posts_per_page' => 20,
                    'post_status'    => 'publish',
                    'paged'          => $paged
                ));
            ?>
                <?php if ($query_follow->have_posts()) : ?>
                    <div class="story-grid tu-truyen-grid" id="tuTruyenStoryGrid">
                        <?php while ($query_follow->have_posts()) : $query_follow->the_post(); 
                            $sid = get_the_ID();
                        ?>
                            <div class="tu-truyen-item-wrap" id="tuTruyenFollowItem-<?php echo $sid; ?>" style="position: relative;">
                                <?php get_template_part('template-parts/story-card'); ?>
                                <button type="button" class="btn-remove-from-following" data-story-id="<?php echo $sid; ?>" title="Hủy theo dõi truyện này">
                                    <i class="fa-solid fa-bell-slash"></i> Bỏ Theo Dõi
                                </button>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>

                    <?php if ($query_follow->max_num_pages > 1) : ?>
                        <div class="pagination-wrapper" style="display: flex; justify-content: center; gap: 8px; margin-top: 36px;">
                            <?php
                            echo paginate_links(array(
                                'total'     => $query_follow->max_num_pages,
                                'current'   => $paged,
                                'prev_text' => '<i class="fa-solid fa-chevron-left"></i> Trước',
                                'next_text' => 'Sau <i class="fa-solid fa-chevron-right"></i>',
                                'type'      => 'plain'
                            ));
                            ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else : ?>
                <div class="tu-truyen-empty-box">
                    <div class="empty-icon-circle" style="background: #f3e8ff; color: #8b5cf6;">
                        <i class="fa-regular fa-bell"></i>
                    </div>
                    <h3>Bạn chưa theo dõi bộ truyện nào!</h3>
                    <p>Bấm nút <strong>"Theo Dõi Truyện"</strong> ở trang chi tiết truyện để nhận ngay thông báo chuông mỗi khi tác giả/dịch giả ra chương mới!</p>
                    <div class="empty-actions">
                        <a href="<?php echo esc_url(home_url('/truyen-moi/')); ?>" class="btn btn-primary">
                            <i class="fa-solid fa-clock"></i> Truyện Mới Cập Nhật
                        </a>
                        <a href="<?php echo esc_url(home_url('/truyen-hot/')); ?>" class="btn btn-secondary">
                            <i class="fa-solid fa-fire"></i> Khám Phá Truyện Hot
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        <!-- ==================== TAB 4: TEAM DỊCH ĐÃ YÊU THÍCH ==================== -->
        <?php elseif ($current_tab === 'teams_favorite') : ?>
            <?php if ($count_teams_fav > 0) : ?>
                <div class="team-cards-grid" id="tuTruyenTeamsGrid">
                    <?php foreach ($fav_team_ids as $tid) : 
                        $team_term = get_term($tid, 'team_dich');
                        if (!$team_term || is_wp_error($team_term)) continue;

                        $team_avatar = get_term_meta($tid, '_team_avatar', true);
                        $team_avatar = function_exists('muop_normalize_url') ? muop_normalize_url($team_avatar) : $team_avatar;
                        $team_bio = !empty($team_term->description) ? $team_term->description : get_term_meta($tid, '_team_bio', true);
                        if (empty($team_bio)) {
                            $team_bio = 'Nhóm dịch nhiệt huyết tại Mướp Đắng Cũng Có Vị Ngọt.';
                        }
                        $team_initial = mb_strtoupper(mb_substr($team_term->name, 0, 1, 'UTF-8'), 'UTF-8');
                        $team_link = get_term_link($team_term);

                        // Tính số truyện & lượt đọc của team
                        $team_stories_count = $team_term->count;
                    ?>
                        <div class="tu-team-card" id="tuTeamFavItem-<?php echo $tid; ?>">
                            <div class="tu-team-card-header">
                                <div class="tu-team-avatar">
                                    <?php if (!empty($team_avatar)) : ?>
                                        <img src="<?php echo esc_url($team_avatar); ?>" alt="<?php echo esc_attr($team_term->name); ?>" />
                                    <?php else : ?>
                                        <span><?php echo esc_html($team_initial); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="tu-team-info">
                                    <h3 class="tu-team-name">
                                        <a href="<?php echo esc_url($team_link); ?>"><?php echo esc_html($team_term->name); ?></a>
                                    </h3>
                                    <div class="tu-team-stats">
                                        <span><i class="fa-solid fa-book-open"></i> <?php echo (int)$team_stories_count; ?> bộ truyện</span>
                                    </div>
                                </div>
                            </div>
                            <p class="tu-team-bio"><?php echo esc_html($team_bio); ?></p>
                            <div class="tu-team-card-actions">
                                <a href="<?php echo esc_url($team_link); ?>" class="btn btn-secondary btn-sm" style="flex: 1;">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Xem Trang
                                </a>
                                <button type="button" class="btn btn-outline btn-sm btn-remove-team-fav" data-team-id="<?php echo $tid; ?>" title="Bỏ yêu thích team này">
                                    <i class="fa-solid fa-heart-crack"></i> Bỏ Thích
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="tu-truyen-empty-box">
                    <div class="empty-icon-circle" style="background: #fce7f3; color: #ec4899;">
                        <i class="fa-solid fa-users-viewfinder"></i>
                    </div>
                    <h3>Chưa có team dịch yêu thích!</h3>
                    <p>Bạn chưa thêm team dịch nào vào danh sách yêu thích. Hãy ghé thăm trang <strong>Team Dịch</strong> và bấm nút "Yêu Thích Team" để cổ vũ nhé!</p>
                    <div class="empty-actions">
                        <a href="<?php echo esc_url(home_url('/team-dich/')); ?>" class="btn btn-primary">
                            <i class="fa-solid fa-users"></i> Khám Phá Team Dịch
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        <!-- ==================== TAB 5: TEAM DỊCH ĐANG THEO DÕI ==================== -->
        <?php elseif ($current_tab === 'teams_following') : ?>
            <?php if ($count_teams_following > 0) : ?>
                <div class="team-cards-grid" id="tuTruyenTeamsGrid">
                    <?php foreach ($following_team_ids as $tid) : 
                        $team_term = get_term($tid, 'team_dich');
                        if (!$team_term || is_wp_error($team_term)) continue;

                        $team_avatar = get_term_meta($tid, '_team_avatar', true);
                        $team_avatar = function_exists('muop_normalize_url') ? muop_normalize_url($team_avatar) : $team_avatar;
                        $team_bio = !empty($team_term->description) ? $team_term->description : get_term_meta($tid, '_team_bio', true);
                        if (empty($team_bio)) {
                            $team_bio = 'Nhóm dịch nhiệt huyết tại Mướp Đắng Cũng Có Vị Ngọt.';
                        }
                        $team_initial = mb_strtoupper(mb_substr($team_term->name, 0, 1, 'UTF-8'), 'UTF-8');
                        $team_link = get_term_link($team_term);
                        $team_stories_count = $team_term->count;
                    ?>
                        <div class="tu-team-card" id="tuTeamFollowItem-<?php echo $tid; ?>">
                            <div class="tu-team-card-header">
                                <div class="tu-team-avatar">
                                    <?php if (!empty($team_avatar)) : ?>
                                        <img src="<?php echo esc_url($team_avatar); ?>" alt="<?php echo esc_attr($team_term->name); ?>" />
                                    <?php else : ?>
                                        <span><?php echo esc_html($team_initial); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="tu-team-info">
                                    <h3 class="tu-team-name">
                                        <a href="<?php echo esc_url($team_link); ?>"><?php echo esc_html($team_term->name); ?></a>
                                    </h3>
                                    <div class="tu-team-stats">
                                        <span><i class="fa-solid fa-book-open"></i> <?php echo (int)$team_stories_count; ?> bộ truyện</span>
                                    </div>
                                </div>
                            </div>
                            <p class="tu-team-bio"><?php echo esc_html($team_bio); ?></p>
                            <div class="tu-team-card-actions">
                                <a href="<?php echo esc_url($team_link); ?>" class="btn btn-secondary btn-sm" style="flex: 1;">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Xem Trang
                                </a>
                                <button type="button" class="btn btn-outline btn-sm btn-remove-team-follow" data-team-id="<?php echo $tid; ?>" title="Hủy theo dõi team này">
                                    <i class="fa-solid fa-user-xmark"></i> Bỏ Theo Dõi
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="tu-truyen-empty-box">
                    <div class="empty-icon-circle" style="background: var(--pastel-green); color: var(--primary-green);">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <h3>Bạn chưa theo dõi nhóm dịch nào!</h3>
                    <p>Theo dõi các team dịch bạn mến mộ để nhận ngay thông báo khi team phát hành tác phẩm mới nhé!</p>
                    <div class="empty-actions">
                        <a href="<?php echo esc_url(home_url('/team-dich/')); ?>" class="btn btn-primary">
                            <i class="fa-solid fa-users"></i> Khám Phá Team Dịch
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        <!-- ==================== TAB 6: TRUYỆN ĐÃ ĐỌC (LỊCH SỬ ĐỌC) ==================== -->
        <?php elseif ($current_tab === 'history') : ?>
            <?php if ($count_history > 0) : ?>
                <div class="tu-history-list" id="tuTruyenHistoryList">
                    <?php foreach ($history_rows as $row) : 
                        $story = get_post($row->story_id);
                        if (!$story || $story->post_type !== 'truyen') continue;

                        $chap_post = !empty($row->chapter_id) ? get_post($row->chapter_id) : null;
                        $chap_url = $chap_post ? get_permalink($chap_post) : get_permalink($story);
                        $chap_num = !empty($row->chapter_num) ? $row->chapter_num : 1;
                        $chap_title = $chap_post ? $chap_post->post_title : 'Chương ' . $chap_num;

                        $story_thumb = get_the_post_thumbnail_url($story->ID, 'medium');
                        if (!$story_thumb) {
                            $story_thumb = MUOP_THEME_URI . '/assets/images/default-cover.svg';
                        }
                        $time_ago = human_time_diff(strtotime($row->updated_at), current_time('timestamp')) . ' trước';
                    ?>
                        <div class="tu-history-item" id="tuHistoryItem-<?php echo $story->ID; ?>">
                            <a href="<?php echo esc_url(get_permalink($story)); ?>" class="history-item-thumb">
                                <img src="<?php echo esc_url($story_thumb); ?>" alt="<?php echo esc_attr($story->post_title); ?>" />
                            </a>
                            <div class="history-item-body">
                                <h3 class="history-item-title">
                                    <a href="<?php echo esc_url(get_permalink($story)); ?>"><?php echo esc_html($story->post_title); ?></a>
                                </h3>
                                <div class="history-item-meta">
                                    <a href="<?php echo esc_url($chap_url); ?>" class="history-chapter-badge" title="Đọc tiếp chương này">
                                        <i class="fa-solid fa-book-open"></i> Đang đọc Chương <?php echo esc_html($chap_num); ?>
                                    </a>
                                    <span class="history-time"><i class="fa-regular fa-clock"></i> <?php echo esc_html($time_ago); ?></span>
                                </div>
                            </div>
                            <div class="history-item-actions">
                                <a href="<?php echo esc_url($chap_url); ?>" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-book-reader"></i> Đọc Tiếp
                                </a>
                                <button type="button" class="btn-remove-history-item" data-story-id="<?php echo $story->ID; ?>" title="Xóa bộ truyện này khỏi lịch sử đọc">
                                    <i class="fa-solid fa-xmark"></i> Xóa
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="tu-truyen-empty-box">
                    <div class="empty-icon-circle" style="background: #e0f2fe; color: #0284c7;">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <h3>Lịch sử đọc của bạn đang trống!</h3>
                    <p>Khi bạn bắt đầu đọc bất kỳ chương truyện nào trên Mướp Đắng, hệ thống sẽ tự động lưu lại vị trí chương để bạn tiếp tục đọc bất cứ lúc nào.</p>
                    <div class="empty-actions">
                        <a href="<?php echo esc_url(home_url('/truyen-hot/')); ?>" class="btn btn-primary">
                            <i class="fa-solid fa-fire"></i> Khám Phá Truyện Hot
                        </a>
                        <a href="<?php echo esc_url(home_url('/truyen-moi/')); ?>" class="btn btn-secondary">
                            <i class="fa-solid fa-clock"></i> Truyện Mới Cập Nhật
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</div>

<?php
get_footer();
