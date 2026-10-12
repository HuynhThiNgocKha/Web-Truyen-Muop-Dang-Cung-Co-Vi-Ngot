<?php
/**
 * Taxonomy Template: Team Dịch
 * Trang thông tin chi tiết Team Dịch: Nút yêu thích, Tổng lượt đọc, Ngày tham gia, Truyện được yêu thích nhất, Tất cả truyện
 */
if (!defined('ABSPATH')) exit;

get_header();

$queried_obj = get_queried_object();
if ($queried_obj instanceof WP_Term && $queried_obj->taxonomy === 'team_dich') {
    $team_term = $queried_obj;
    $team_name = $team_term->name;
    $team_slug = $team_term->slug;
    $team_id   = $team_term->term_id;
} else {
    $team_slug = get_query_var('team_dich') ?: (isset($_GET['team_dich']) ? sanitize_text_field($_GET['team_dich']) : '');
    $team_term = get_term_by('slug', $team_slug, 'team_dich');
    if ($team_term) {
        $team_name = $team_term->name;
        $team_id   = $team_term->term_id;
    } else {
        $team_name = !empty($team_slug) ? ucwords(str_replace('-', ' ', $team_slug)) : 'Team Dịch';
        $team_id   = 0;
    }
}

// 1. Lấy tất cả truyện của Team để tính tổng view, truyện hot nhất, ngày tham gia
$all_team_stories_args = array(
    'post_type'      => 'truyen',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
);

if ($team_term) {
    $all_team_stories_args['tax_query'] = array(
        array(
            'taxonomy' => 'team_dich',
            'field'    => 'term_id',
            'terms'    => $team_id,
        )
    );
} else {
    $all_team_stories_args['meta_query'] = array(
        array(
            'key'     => '_truyen_team',
            'value'   => $team_name,
            'compare' => '='
        )
    );
}

$all_team_stories = get_posts($all_team_stories_args);
$total_stories_count = count($all_team_stories);

global $wpdb;
$table_bookmarks = $wpdb->prefix . 'reading_bookmarks';

$total_team_views = 0;
$top_story        = null;
$top_story_score  = -1;
$earliest_time    = null;
$first_author_id  = 0;

foreach ($all_team_stories as $st) {
    $v = (int) get_post_meta($st->ID, '_truyen_views', true);
    $total_team_views += $v;

    // Đếm số lượt lưu vào Tủ truyện
    $bks = 0;
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_bookmarks'") === $table_bookmarks) {
        $bks = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_bookmarks WHERE story_id = %d", $st->ID));
    }

    // Điểm yêu thích: Số lượt lưu * 10 + Tổng view
    $score = ($bks * 10) + $v;
    if ($score > $top_story_score) {
        $top_story_score = $score;
        $top_story = $st;
    }

    $p_time = strtotime($st->post_date);
    if ($earliest_time === null || $p_time < $earliest_time) {
        $earliest_time = $p_time;
    }

    if (!$first_author_id) {
        $first_author_id = (int) $st->post_author;
    }
}

// Xác định ngày tham gia
if ($first_author_id) {
    $author_obj = get_userdata($first_author_id);
    $joined_date = $author_obj ? date('d/m/Y', strtotime($author_obj->user_registered)) : ($earliest_time ? date('d/m/Y', $earliest_time) : date('d/m/Y'));
} else {
    $joined_date = $earliest_time ? date('d/m/Y', $earliest_time) : date('d/m/Y');
}

// Lượt yêu thích của Team
$team_fav_count = $team_id > 0 ? (int) get_term_meta($team_id, '_team_favorite_count', true) : 0;
if ($team_fav_count < 0) $team_fav_count = 0;

// Trạng thái đã yêu thích của người dùng hiện tại
$is_favorited = false;
if (is_user_logged_in() && $team_id > 0) {
    $user_fav_teams = get_user_meta(get_current_user_id(), '_muop_favorite_teams', true);
    if (is_array($user_fav_teams) && in_array($team_id, $user_fav_teams)) {
        $is_favorited = true;
    }
}

// 2. Query phân trang cho danh sách tất cả truyện của team
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$query_args = array(
    'post_type'      => 'truyen',
    'posts_per_page' => 16,
    'post_status'    => 'publish',
    'paged'          => $paged
);

if ($team_term) {
    $query_args['tax_query'] = array(
        array(
            'taxonomy' => 'team_dich',
            'field'    => 'term_id',
            'terms'    => $team_id,
        )
    );
} else {
    $query_args['meta_query'] = array(
        array(
            'key'     => '_truyen_team',
            'value'   => $team_name,
            'compare' => '='
        )
    );
}

$team_query = new WP_Query($query_args);
$avatar_initial = !empty($team_name) ? mb_strtoupper(mb_substr($team_name, 0, 1, 'UTF-8'), 'UTF-8') : 'T';

// Lấy Avatar và Bio của Team
$team_avatar_url = $team_id > 0 ? get_term_meta($team_id, '_team_avatar', true) : '';
$team_bio = '';
if ($team_term) {
    $team_bio = !empty($team_term->description) ? $team_term->description : get_term_meta($team_id, '_team_bio', true);
}
if (empty($team_bio)) {
    $team_bio = 'Chào mừng bạn đến với trang tác phẩm của ' . $team_name . '. Chúc các bạn độc giả có những giây phút đọc truyện ngọt ngào và thư giãn nhất!';
}

// Quyền quản lý và chỉnh sửa Team Dịch
$can_edit_team = false;
if (is_user_logged_in() && $team_id > 0) {
    if (current_user_can('administrator')) {
        $can_edit_team = true;
    } elseif (current_user_can('dich_gia')) {
        $current_uid = get_current_user_id();
        foreach ($all_team_stories as $st) {
            if ((int)$st->post_author === $current_uid) {
                $can_edit_team = true;
                break;
            }
        }
    }
}
?>

<div class="container">
    <div class="section-block">
        <!-- BREADCRUMBS -->
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <a href="<?php echo esc_url(home_url('/team-dich/')); ?>">Team Dịch</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;" id="breadcrumbTeamName"><?php echo esc_html($team_name); ?></span>
        </nav>

        <!-- HERO HEADER: THÔNG TIN TEAM DỊCH -->
        <div class="team-hero-card">
            <div class="team-hero-top">
                <div class="team-avatar-wrap">
                    <div class="team-avatar-circle" id="teamAvatarDisplay">
                        <?php if (!empty($team_avatar_url)) : ?>
                            <img src="<?php echo esc_url($team_avatar_url); ?>" alt="<?php echo esc_attr($team_name); ?>" class="team-avatar-img" id="teamAvatarImg" />
                            <span id="teamAvatarChar" style="display: none;"><?php echo esc_html($avatar_initial); ?></span>
                        <?php else : ?>
                            <span id="teamAvatarChar"><?php echo esc_html($avatar_initial); ?></span>
                            <img src="" alt="<?php echo esc_attr($team_name); ?>" class="team-avatar-img" id="teamAvatarImg" style="display: none;" />
                        <?php endif; ?>

                        <?php if ($can_edit_team) : ?>
                            <button type="button" class="btn-change-team-avatar-badge" id="btnChangeTeamAvatarBadge" title="Thay đổi ảnh đại diện Team">
                                <i class="fa-solid fa-camera"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="team-hero-info">
                    <div class="team-hero-title-row">
                        <h1 class="team-hero-name" id="teamHeroNameDisplay"><?php echo esc_html($team_name); ?></h1>
                        <?php if ($can_edit_team) : ?>
                            <button type="button" class="btn-edit-team-inline" id="btnEditTeamNameInline" title="Chỉnh sửa tên team dịch">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        <?php endif; ?>
                        <span class="badge badge-green">
                            <i class="fa-solid fa-feather-pointed"></i> Team Dịch Chính Thức
                        </span>
                    </div>

                    <div class="team-hero-bio-row">
                        <p class="team-hero-bio" id="teamHeroBioDisplay"><?php echo esc_html($team_bio); ?></p>
                        <?php if ($can_edit_team) : ?>
                            <button type="button" class="btn-edit-team-inline" id="btnEditTeamBioInline" title="Chỉnh sửa lời giới thiệu team">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- NÚT CHỈNH SỬA & YÊU THÍCH / THEO DÕI TEAM -->
                <div class="team-hero-action">
                    <?php if ($can_edit_team) : ?>
                        <button type="button" class="btn btn-secondary btn-edit-team-all" id="btnOpenEditTeamModal" title="Chỉnh sửa thông tin Team Dịch">
                            <i class="fa-solid fa-gear"></i>
                            <span>Chỉnh Sửa Team</span>
                        </button>
                    <?php endif; ?>

                    <button type="button" class="btn <?php echo $is_favorited ? 'btn-primary' : 'btn-secondary'; ?> btn-favorite-team" id="btnToggleFavoriteTeam" data-team-id="<?php echo esc_attr($team_id); ?>" data-team-name="<?php echo esc_attr($team_name); ?>">
                        <i class="fa-<?php echo $is_favorited ? 'solid' : 'regular'; ?> fa-heart" id="favTeamHeartIcon"></i>
                        <span id="favTeamBtnText"><?php echo $is_favorited ? 'Đã Yêu Thích Team' : 'Yêu Thích Team'; ?></span>
                    </button>
                </div>
            </div>

            <!-- 4 Ô THỐNG KÊ (KPI STATS) -->
            <div class="team-stats-grid">
                <div class="team-stat-item">
                    <div class="team-stat-icon">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    <div class="team-stat-text">
                        <span class="team-stat-num"><?php echo number_format($total_team_views); ?></span>
                        <span class="team-stat-label">Tổng Lượt Đọc</span>
                    </div>
                </div>

                <div class="team-stat-item">
                    <div class="team-stat-icon">
                        <i class="fa-solid fa-book-bookmark"></i>
                    </div>
                    <div class="team-stat-text">
                        <span class="team-stat-num"><?php echo number_format($total_stories_count); ?></span>
                        <span class="team-stat-label">Truyện Đã Đăng</span>
                    </div>
                </div>

                <div class="team-stat-item">
                    <div class="team-stat-icon">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div class="team-stat-text">
                        <span class="team-stat-num"><?php echo esc_html($joined_date); ?></span>
                        <span class="team-stat-label">Ngày Tham Gia</span>
                    </div>
                </div>

                <div class="team-stat-item">
                    <div class="team-stat-icon" style="color: #e91e63;">
                        <i class="fa-solid fa-heart"></i>
                    </div>
                    <div class="team-stat-text">
                        <span class="team-stat-num" id="teamFavCountDisplay"><?php echo number_format($team_fav_count); ?></span>
                        <span class="team-stat-label">Lượt Yêu Thích</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- SPOTLIGHT: TRUYỆN ĐƯỢC YÊU THÍCH NHẤT -->
        <?php if ($top_story) : 
            $top_id        = $top_story->ID;
            $top_thumb_url = get_the_post_thumbnail_url($top_id, 'muop-cover');
            if (!$top_thumb_url) {
                $top_thumb_url = MUOP_THEME_URI . '/assets/images/default-cover.svg';
            }
            $top_views     = (int) get_post_meta($top_id, '_truyen_views', true);
            $top_author    = get_post_meta($top_id, '_truyen_author_name', true) ?: 'Đang cập nhật';
            $top_status    = get_post_meta($top_id, '_truyen_status', true);
            $top_chaps     = muop_get_story_chapters($top_id, 'ASC');
            $top_chap_cnt  = count($top_chaps);
            $top_first_url = !empty($top_chaps) ? get_permalink($top_chaps[0]->ID) : get_permalink($top_id);
            $top_excerpt   = wp_strip_all_tags($top_story->post_content);
            if (mb_strlen($top_excerpt) > 170) {
                $top_excerpt = mb_substr($top_excerpt, 0, 170) . '...';
            }
        ?>
            <div class="team-spotlight-box">
                <div class="team-spotlight-badge">
                    <i class="fa-solid fa-crown" style="color: #f59e0b;"></i> Truyện Được Yêu Thích Nhất Của Team
                </div>
                <div class="team-spotlight-content">
                    <a href="<?php echo esc_url(get_permalink($top_id)); ?>" class="team-spotlight-thumb">
                        <img src="<?php echo esc_url($top_thumb_url); ?>" alt="<?php echo esc_attr(get_the_title($top_id)); ?>" />
                    </a>
                    <div class="team-spotlight-details">
                        <h2 class="team-spotlight-title">
                            <a href="<?php echo esc_url(get_permalink($top_id)); ?>">
                                <?php echo esc_html(get_the_title($top_id)); ?>
                            </a>
                        </h2>
                        <div class="team-spotlight-meta">
                            <span><i class="fa-solid fa-pen-nib"></i> <?php echo esc_html($top_author); ?></span>
                            <span><i class="fa-solid fa-book-open"></i> <?php echo $top_chap_cnt; ?> chương</span>
                            <span><i class="fa-solid fa-eye"></i> <?php echo number_format($top_views); ?> lượt đọc</span>
                            <span class="badge <?php echo ($top_status === 'hoan_thanh') ? 'badge-full' : 'badge-green'; ?>">
                                <?php echo ($top_status === 'hoan_thanh') ? 'Hoàn thành' : 'Đang ra'; ?>
                            </span>
                        </div>
                        <p class="team-spotlight-desc">
                            <?php echo esc_html($top_excerpt); ?>
                        </p>
                        <div class="team-spotlight-actions">
                            <a href="<?php echo esc_url($top_first_url); ?>" class="btn btn-primary" style="padding: 7px 18px; font-size: 13.5px;">
                                <i class="fa-solid fa-book-reader"></i> Đọc Ngay
                            </a>
                            <a href="<?php echo esc_url(get_permalink($top_id)); ?>" class="btn btn-secondary" style="padding: 7px 16px; font-size: 13.5px;">
                                <i class="fa-solid fa-circle-info"></i> Chi Tiết Truyện
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- DANH SÁCH TẤT CẢ TRUYỆN CỦA TEAM -->
        <div class="section-header" style="margin-top: 32px;">
            <h2 class="section-title">
                <i class="fa-solid fa-book-bookmark"></i> Tất Cả Truyện Của Team (<?php echo $total_stories_count; ?>)
            </h2>
            <span style="font-size: 13.5px; color: var(--text-muted);">
                Trang <?php echo $paged; ?> / <?php echo max(1, $team_query->max_num_pages); ?>
            </span>
        </div>

        <div class="story-grid">
            <?php
            if ($team_query->have_posts()) :
                while ($team_query->have_posts()) : $team_query->the_post();
                    get_template_part('template-parts/story-card');
                endwhile;
            else :
                echo '<p style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 40px;">Team hiện chưa đăng truyện nào.</p>';
            endif;
            ?>
        </div>

        <!-- PHÂN TRANG -->
        <?php if ($team_query->max_num_pages > 1) : ?>
            <div style="display: flex; justify-content: center; gap: 8px; margin-top: 36px;">
                <?php
                echo paginate_links(array(
                    'total'        => $team_query->max_num_pages,
                    'current'      => $paged,
                    'prev_text'    => '<i class="fa-solid fa-chevron-left"></i> Trước',
                    'next_text'    => 'Sau <i class="fa-solid fa-chevron-right"></i>',
                    'type'         => 'plain'
                ));
                wp_reset_postdata();
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($can_edit_team) : ?>
<!-- 1. MODAL RIÊNG: THAY ĐỔI ẢNH ĐẠI DIỆN TEAM -->
<div id="modalEditSingleTeamAvatar" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 480px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; display: flex; flex-direction: column; max-height: 90vh;">
        <div class="modal-header-top-box">
            <div class="modal-title-stacked">
                <span class="modal-action-label">
                    <i class="fa-solid fa-camera"></i> Chỉnh Sửa
                </span>
                <h3 class="modal-story-title-sub">Thay Đổi Ảnh Đại Diện Team</h3>
            </div>
            <button type="button" class="btn-close-team-modal" style="background: none; border: none; font-size: 20px; color: var(--text-muted); cursor: pointer; padding: 4px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div style="padding: 20px; overflow-y: auto; flex: 1;">
            <div id="editSingleAvatarAlert" class="alert-box" style="display: none; margin-bottom: 14px;"></div>

            <form id="formEditSingleTeamAvatar" enctype="multipart/form-data">
                <input type="hidden" name="action" value="muop_update_team_info" />
                <input type="hidden" name="team_id" value="<?php echo esc_attr($team_id); ?>" />
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />
                <input type="file" name="team_avatar_file" id="inputSingleAvatarFile" accept="image/png, image/jpeg, image/webp" style="display: none;" />

                <div style="display: flex; gap: 16px; align-items: center; margin-bottom: 16px;">
                    <div class="avatar-preview-circle" style="width: 76px; height: 76px; border-radius: 50%; border: 3px solid #ffffff; overflow: hidden; background: linear-gradient(135deg, var(--primary-green), var(--avocado-green)); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 30px; font-weight: 800; flex-shrink: 0; box-shadow: 0 4px 12px rgba(63, 117, 35, 0.3);">
                        <?php if (!empty($team_avatar_url)) : ?>
                            <img id="singleAvatarPreviewImg" src="<?php echo esc_url($team_avatar_url); ?>" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: block;" />
                            <span id="singleAvatarPreviewChar" style="display: none;"><?php echo esc_html($avatar_initial); ?></span>
                        <?php else : ?>
                            <span id="singleAvatarPreviewChar"><?php echo esc_html($avatar_initial); ?></span>
                            <img id="singleAvatarPreviewImg" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;" />
                        <?php endif; ?>
                    </div>

                    <div style="flex: 1; min-width: 0;">
                        <button type="button" id="btnTriggerSingleAvatarUpload" class="btn btn-secondary" style="padding: 8px 14px; font-size: 13px; margin-bottom: 6px;">
                            <i class="fa-solid fa-upload"></i> Tải ảnh từ thiết bị
                        </button>
                        <div id="singleAvatarFileName" style="font-size: 12px; color: var(--text-muted); word-break: break-all;">Chưa chọn tệp (Hỗ trợ JPG, PNG, WEBP)</div>
                    </div>
                </div>

                <div style="margin-bottom: 18px;">
                    <label for="inputSingleAvatarUrl" class="form-label" style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 4px;">Hoặc dán đường dẫn (URL) ảnh avatar online:</label>
                    <input type="url" id="inputSingleAvatarUrl" name="team_avatar_url" class="form-input" style="height: 38px; font-size: 13px;" placeholder="https://example.com/avatar-team.jpg" value="<?php echo esc_attr($team_avatar_url); ?>" />
                </div>

                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; padding-top: 14px; border-top: 1px solid var(--border-light);">
                    <button type="button" class="btn btn-secondary btn-close-team-modal">Hủy</button>
                    <button type="submit" id="btnSubmitSingleAvatar" class="btn btn-primary" style="padding: 8px 22px;">
                        <i class="fa-solid fa-check"></i> Cập Nhật Ảnh
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. MODAL RIÊNG: ĐỔI TÊN TEAM DỊCH -->
<div id="modalEditSingleTeamName" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 480px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; display: flex; flex-direction: column;">
        <div class="modal-header-top-box">
            <div class="modal-title-stacked">
                <span class="modal-action-label">
                    <i class="fa-solid fa-pen-to-square"></i> Chỉnh Sửa
                </span>
                <h3 class="modal-story-title-sub">Đổi Tên Team Dịch</h3>
            </div>
            <button type="button" class="btn-close-team-modal" style="background: none; border: none; font-size: 20px; color: var(--text-muted); cursor: pointer; padding: 4px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div style="padding: 20px;">
            <div id="editSingleNameAlert" class="alert-box" style="display: none; margin-bottom: 14px;"></div>

            <form id="formEditSingleTeamName">
                <input type="hidden" name="action" value="muop_update_team_info" />
                <input type="hidden" name="team_id" value="<?php echo esc_attr($team_id); ?>" />
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />

                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label" for="inputSingleTeamName" style="font-weight: 700; margin-bottom: 6px; display: block;">Tên Team Dịch Mới (*)</label>
                    <input type="text" id="inputSingleTeamName" name="team_name" class="form-input" style="height: 42px; font-size: 14px;" required value="<?php echo esc_attr($team_name); ?>" placeholder="Nhập tên team dịch mới..." />
                    <p style="font-size: 12px; color: var(--text-muted); margin: 6px 0 0 0;">
                        Lưu ý: Hệ thống sẽ tự động cập nhật tên team ở tất cả các tác phẩm của team.
                    </p>
                </div>

                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--border-light);">
                    <button type="button" class="btn btn-secondary btn-close-team-modal">Hủy</button>
                    <button type="submit" id="btnSubmitSingleName" class="btn btn-primary" style="padding: 8px 22px;">
                        <i class="fa-solid fa-check"></i> Cập Nhật Tên Team
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 3. MODAL RIÊNG: ĐỔI LỜI GIỚI THIỆU (BIO) -->
<div id="modalEditSingleTeamBio" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 500px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; display: flex; flex-direction: column;">
        <div class="modal-header-top-box">
            <div class="modal-title-stacked">
                <span class="modal-action-label">
                    <i class="fa-solid fa-align-left"></i> Chỉnh Sửa
                </span>
                <h3 class="modal-story-title-sub">Đổi Lời Giới Thiệu Team</h3>
            </div>
            <button type="button" class="btn-close-team-modal" style="background: none; border: none; font-size: 20px; color: var(--text-muted); cursor: pointer; padding: 4px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div style="padding: 20px;">
            <div id="editSingleBioAlert" class="alert-box" style="display: none; margin-bottom: 14px;"></div>

            <form id="formEditSingleTeamBio">
                <input type="hidden" name="action" value="muop_update_team_info" />
                <input type="hidden" name="team_id" value="<?php echo esc_attr($team_id); ?>" />
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />

                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label" for="inputSingleTeamBio" style="font-weight: 700; margin-bottom: 6px; display: block;">Lời Giới Thiệu / Bio Team</label>
                    <textarea id="inputSingleTeamBio" name="team_bio" class="form-input" rows="4" style="font-size: 13.5px; line-height: 1.5; resize: vertical;" placeholder="Nhập lời chào mừng hoặc giới thiệu về team dịch..."><?php echo esc_textarea($team_bio); ?></textarea>
                </div>

                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--border-light);">
                    <button type="button" class="btn btn-secondary btn-close-team-modal">Hủy</button>
                    <button type="submit" id="btnSubmitSingleBio" class="btn btn-primary" style="padding: 8px 22px;">
                        <i class="fa-solid fa-check"></i> Cập Nhật Lời Giới Thiệu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 4. MODAL CHUNG: QUẢN LÝ TẤT CẢ THÔNG TIN TEAM DỊCH -->
<div id="modalEditTeamInfo" class="custom-modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 999999; align-items: center; justify-content: center; padding: 16px;">
    <div class="custom-modal-content" style="background: var(--bg-card); width: 100%; max-width: 560px; border-radius: var(--radius-md); box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); overflow: hidden; display: flex; flex-direction: column; max-height: 90vh;">
        
        <!-- Header -->
        <div class="modal-header-top-box">
            <div class="modal-title-stacked">
                <span class="modal-action-label">
                    <i class="fa-solid fa-gear"></i> Quản Lý Team Dịch
                </span>
                <h3 class="modal-story-title-sub">Chỉnh Sửa Thông Tin Team</h3>
            </div>
            <button type="button" class="btn-close-team-modal" style="background: none; border: none; font-size: 20px; color: var(--text-muted); cursor: pointer; padding: 4px;" title="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Body -->
        <div style="padding: 20px; overflow-y: auto; flex: 1;">
            <div id="editTeamAlert" class="alert-box" style="display: none; margin-bottom: 14px;"></div>

            <form id="formEditTeamInfo" enctype="multipart/form-data">
                <input type="hidden" name="action" value="muop_update_team_info" />
                <input type="hidden" name="team_id" value="<?php echo esc_attr($team_id); ?>" />
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('muop_ajax_nonce'); ?>" />
                <input type="file" name="team_avatar_file" id="inputTeamAvatarFile" accept="image/png, image/jpeg, image/webp" style="display: none;" />

                <!-- PHẦN 1: ẢNH ĐẠI DIỆN TEAM -->
                <div class="team-edit-section" style="margin-bottom: 20px; padding-bottom: 18px; border-bottom: 1px solid var(--border-light);">
                    <label class="form-label" style="font-weight: 700; margin-bottom: 10px; display: block;">
                        <i class="fa-solid fa-image"></i> Ảnh Đại Diện Team (Avatar)
                    </label>
                    <div style="display: flex; gap: 16px; align-items: center;">
                        <div class="avatar-preview-circle" style="width: 72px; height: 72px; border-radius: 50%; border: 3px solid #ffffff; overflow: hidden; background: linear-gradient(135deg, var(--primary-green), var(--avocado-green)); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: 800; flex-shrink: 0; box-shadow: 0 4px 12px rgba(63, 117, 35, 0.3);">
                            <?php if (!empty($team_avatar_url)) : ?>
                                <img id="teamModalAvatarPreviewImg" src="<?php echo esc_url($team_avatar_url); ?>" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: block;" />
                                <span id="teamModalAvatarPreviewChar" style="display: none;"><?php echo esc_html($avatar_initial); ?></span>
                            <?php else : ?>
                                <span id="teamModalAvatarPreviewChar"><?php echo esc_html($avatar_initial); ?></span>
                                <img id="teamModalAvatarPreviewImg" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;" />
                            <?php endif; ?>
                        </div>

                        <div style="flex: 1; min-width: 0;">
                            <button type="button" id="btnTriggerTeamAvatarUpload" class="btn btn-secondary" style="padding: 8px 14px; font-size: 13px; margin-bottom: 6px;">
                                <i class="fa-solid fa-upload"></i> Tải ảnh từ thiết bị
                            </button>
                            <div id="teamAvatarFileName" style="font-size: 12px; color: var(--text-muted); word-break: break-all;">Chưa chọn tệp (Hỗ trợ JPG, PNG, WEBP)</div>
                        </div>
                    </div>

                    <div style="margin-top: 12px;">
                        <label for="inputTeamAvatarUrl" class="form-label" style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 4px;">Hoặc dán đường dẫn (URL) ảnh avatar online:</label>
                        <input type="url" id="inputTeamAvatarUrl" name="team_avatar_url" class="form-input" style="height: 38px; font-size: 13px;" placeholder="https://example.com/avatar-team.jpg" value="<?php echo esc_attr($team_avatar_url); ?>" />
                    </div>
                </div>

                <!-- PHẦN 2: TÊN TEAM DỊCH -->
                <div class="team-edit-section" style="margin-bottom: 20px; padding-bottom: 18px; border-bottom: 1px solid var(--border-light);">
                    <label class="form-label" for="inputTeamName" style="font-weight: 700; margin-bottom: 6px; display: block;">
                        <i class="fa-solid fa-users"></i> Tên Team Dịch (*)
                    </label>
                    <input type="text" id="inputTeamName" name="team_name" class="form-input" style="height: 40px; font-size: 14px;" required value="<?php echo esc_attr($team_name); ?>" placeholder="Nhập tên team dịch..." />
                    <p style="font-size: 12px; color: var(--text-muted); margin: 6px 0 0 0;">
                        Lưu ý: Khi đổi tên team dịch, hệ thống sẽ tự động cập nhật tên team ở tất cả các tác phẩm của team.
                    </p>
                </div>

                <!-- PHẦN 3: DÒNG GIỚI THIỆU (BIO) -->
                <div class="team-edit-section" style="margin-bottom: 10px;">
                    <label class="form-label" for="inputTeamBio" style="font-weight: 700; margin-bottom: 6px; display: block;">
                        <i class="fa-solid fa-align-left"></i> Lời Giới Thiệu / Bio Team
                    </label>
                    <textarea id="inputTeamBio" name="team_bio" class="form-input" rows="3" style="font-size: 13.5px; line-height: 1.5; resize: vertical;" placeholder="Nhập lời chào mừng hoặc giới thiệu về team dịch..."><?php echo esc_textarea($team_bio); ?></textarea>
                </div>

                <!-- Footer buttons -->
                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 14px; border-top: 1px solid var(--border-light);">
                    <button type="button" class="btn btn-secondary btn-close-team-modal">Hủy</button>
                    <button type="submit" id="btnSubmitTeamInfo" class="btn btn-primary" style="padding: 8px 22px;">
                        <i class="fa-solid fa-check"></i> Lưu Thay Đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
get_footer();
