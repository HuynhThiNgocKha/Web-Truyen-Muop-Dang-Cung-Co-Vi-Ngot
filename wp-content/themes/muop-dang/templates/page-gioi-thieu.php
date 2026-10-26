<?php
/**
 * Template Name: Trang Giới Thiệu
 */
if (!defined('ABSPATH')) exit;

get_header();
?>

<div class="container">
    <div class="story-detail-wrapper" style="max-width: 900px; margin: 0 auto;">
        <nav class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house"></i> Trang chủ</a>
            <i class="fa-solid fa-angle-right" style="font-size: 11px;"></i>
            <span style="color: var(--primary-green); font-weight: 600;">Giới Thiệu</span>
        </nav>

        <div style="text-align: center; margin-bottom: 32px;">
            <div class="about-hero-logo">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-icon.png?v=' . (defined('MUOP_THEME_VERSION') ? MUOP_THEME_VERSION : '2.5.0')); ?>" 
                     alt="Logo Mướp Đắng Cũng Có Vị Ngọt" 
                     class="about-logo-icon" />
            </div>
            <div class="about-hero-title">
                <h1 style="margin: 0; line-height: 1;">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-text.png?v=' . (defined('MUOP_THEME_VERSION') ? MUOP_THEME_VERSION : '2.5.0')); ?>" 
                         alt="Mướp Đắng Cũng Có Vị Ngọt" 
                         class="about-logo-text" />
                    <span class="sr-only">Mướp Đắng Cũng Có Vị Ngọt</span>
                </h1>
            </div>
            <?php 
            $about_slogan = get_option('muop_page_about_slogan', 'Góc nhỏ của Mướp, nơi những câu chuyện được cất giữ bằng tất cả sự yêu thích.');
            $custom_about = get_option('muop_page_about', '');
            ?>
            <p style="font-size: 16px; color: var(--text-muted); font-style: italic;">
                "<?php echo esc_html($about_slogan); ?>"
            </p>
        </div>

        <div class="story-summary-box" style="font-size: 15px; line-height: 1.8;">
            <?php if (!empty(trim($custom_about))) : ?>
                <?php echo wp_kses_post(wpautop($custom_about)); ?>
            <?php else : ?>
                <p style="margin-bottom: 16px; font-size: 15.5px;">
                    Xin chào mọi người, chào mừng mọi người đã ghé thăm <strong>Mướp Đắng Cũng Có Vị Ngọt</strong> — ngôi nhà nhỏ dành cho những tâm hồn yêu truyện! ♡
                </p>

                <p style="margin-bottom: 16px;">
                    Mướp là một người rất thích đọc truyện, đặc biệt là những câu chuyện Zhihu, tổng tài, tình yêu, ngược tâm, ngược thân, rồi cả những bộ truyện có chút <em>hê hê</em> khiến người ta phải đỏ mặt, ôm gối cười một mình nữa. Đôi khi là một câu chuyện ngọt ngào khiến lòng vui vẻ cả ngày, đôi khi lại là một bộ truyện ngược đến đau lòng, đọc xong chỉ biết thở dài và tự nhủ: “Thôi, đọc thêm một chương nữa rồi ngủ…” Nhưng rồi chẳng biết bằng cách nào, một chương lại thành mười chương. 🥹💗
                </p>

                <p style="margin-bottom: 24px;">
                    Vì yêu thích những câu chuyện ấy, Mướp luôn mong muốn có một góc nhỏ của riêng mình, nơi có thể lưu giữ những bộ truyện mà Mướp yêu thích, những câu chuyện đã từng khiến Mướp rung động, bật cười, tức giận hay rơi nước mắt. Và thế là <strong>Mướp Đắng Cũng Có Vị Ngọt</strong> ra đời!
                </p>

                <h3 style="color: var(--primary-green); font-size: 18px; margin: 24px 0 12px; display: flex; align-items: center; gap: 8px;">
                    🌷 Một chút về ngôi nhà nhỏ này
                </h3>

                <p style="margin-bottom: 16px;">
                    Trang web được chính tay Mướp lên ý tưởng và trực tiếp viết code, từ những dòng lệnh đầu tiên cho đến khi từng trang truyện dần được hoàn thiện. Mướp không phải tạo ra nơi này để làm điều gì quá lớn lao, chỉ đơn giản là muốn có một không gian riêng để đọc truyện, lưu giữ những câu chuyện mình yêu thích và chia sẻ những bản dịch của mình với mọi người.
                </p>

                <p style="margin-bottom: 16px;">
                    Những bộ truyện được Mướp dịch và đăng tải tại đây đều xuất phát từ niềm yêu thích cá nhân. Mướp hy vọng rằng khi ghé thăm nơi này, mọi người sẽ tìm được một câu chuyện hợp gu, gặp được một nhân vật khiến mình rung động, hoặc đơn giản là có thêm một chút niềm vui sau những giờ học tập và làm việc mệt mỏi.
                </p>

                <p style="margin-bottom: 24px;">
                    Mướp Đắng có thể hơi đắng một chút, nhưng Mướp tin rằng trong mỗi câu chuyện đều có một vị ngọt riêng. Có thể là vị ngọt của tình yêu, sự dịu dàng của những lần gặp gỡ, niềm hạnh phúc sau bao sóng gió, hoặc đôi khi là cảm giác thỏa mãn khi nhân vật phản diện cuối cùng cũng phải trả giá. Hihi! 🍬
                </p>

                <h3 style="color: var(--primary-green); font-size: 18px; margin: 24px 0 12px; display: flex; align-items: center; gap: 8px;">
                    💌 Một vài lời nhắn gửi đến các bạn độc giả
                </h3>

                <p style="margin-bottom: 14px;">
                    Mướp rất vui khi mọi người cùng đọc truyện, chia sẻ cảm nhận và trò chuyện với nhau. Tuy nhiên, để ngôi nhà nhỏ này luôn là một nơi dễ chịu, Mướp mong mọi người hãy cùng nhau giữ gìn một chút văn minh và sự tử tế nhé!
                </p>

                <div style="background: var(--pastel-green, #f0fdf4); border: 1px solid var(--border-color, #dcfce7); border-radius: var(--radius-md, 8px); padding: 16px 20px; margin-bottom: 20px;">
                    <ul style="list-style: none; padding-left: 0; margin: 0; display: flex; flex-direction: column; gap: 10px;">
                        <li style="display: flex; align-items: flex-start; gap: 8px;">
                            <span style="font-size: 16px; flex-shrink: 0;">🌼</span>
                            <div><strong>Bình luận văn minh:</strong> Hãy sử dụng ngôn từ lịch sự, tránh những lời lẽ khiếm nhã, xúc phạm hoặc công kích người khác.</div>
                        </li>
                        <li style="display: flex; align-items: flex-start; gap: 8px;">
                            <span style="font-size: 16px; flex-shrink: 0;">🌼</span>
                            <div><strong>Tôn trọng sự khác biệt:</strong> Mỗi người có một gu đọc truyện và cách cảm nhận riêng. Nếu không đồng tình, chúng mình có thể trao đổi nhẹ nhàng thay vì tranh cãi gay gắt.</div>
                        </li>
                        <li style="display: flex; align-items: flex-start; gap: 8px;">
                            <span style="font-size: 16px; flex-shrink: 0;">🌼</span>
                            <div><strong>Không gây tranh cãi không phù hợp:</strong> Hãy để phần bình luận là nơi chia sẻ niềm vui đọc truyện, không phải nơi để công kích, gây hấn hay tạo ra những cuộc tranh cãi không đáng có.</div>
                        </li>
                        <li style="display: flex; align-items: flex-start; gap: 8px;">
                            <span style="font-size: 16px; flex-shrink: 0;">🌼</span>
                            <div><strong>Cùng nhau giữ gìn góc nhỏ:</strong> Một lời khen, một chiếc bình luận dễ thương hay một chút cảm nhận chân thành cũng đủ khiến người viết và những độc giả khác vui vẻ hơn rất nhiều rồi đó!</div>
                        </li>
                    </ul>
                </div>

                <p style="margin-bottom: 24px;">
                    Mướp hy vọng mọi người sẽ cùng nhau tạo nên một cộng đồng đọc truyện thân thiện, thoải mái và đáng yêu. Chúng mình có thể cùng khóc vì một nam chính tồi tệ, cùng tức giận vì một nữ phụ đáng ghét, cùng hú hét vì một màn phát đường ngọt lịm, nhưng đừng vì thế mà làm tổn thương nhau nhé! 🫶🏻
                </p>

                <h3 style="color: var(--primary-green); font-size: 18px; margin: 24px 0 12px; display: flex; align-items: center; gap: 8px;">
                    📮 Liên hệ với Mướp
                </h3>

                <p style="margin-bottom: 12px;">
                    Nếu mọi người có thắc mắc về trang web, muốn góp ý, báo lỗi hoặc đơn giản là muốn trò chuyện với Mướp, mọi người có thể tìm Mướp qua Facebook:
                </p>

                <div style="margin-bottom: 20px;">
                    <p style="margin-bottom: 0;">
                        <strong>Facebook:</strong> 
                        <a href="https://www.facebook.com/profile.php?id=61594940649925&sk=directory_links&locale=vi_VN" target="_blank" rel="noopener noreferrer" style="color: var(--primary-green); font-weight: 600; text-decoration: underline; margin-left: 4px;">
                            <i class="fa-brands fa-facebook"></i> Mướp Đắng Cũng Có Vị Ngọt
                        </a>
                    </p>
                </div>

                <p style="margin-bottom: 16px;">
                    Mướp luôn trân trọng những góp ý chân thành và sự ủng hộ của mọi người. Vì đây là một trang web do Mướp tự tay xây dựng và chăm chút, nên mỗi sự động viên của các bạn đều là một nguồn động lực rất lớn để Mướp tiếp tục hoàn thiện ngôi nhà nhỏ này.
                </p>

                <p style="margin-bottom: 16px;">
                    Cuối cùng, cảm ơn mọi người đã ghé thăm <strong>Mướp Đắng Cũng Có Vị Ngọt</strong>. Cảm ơn vì đã dành một chút thời gian cho những câu chuyện mà Mướp yêu thích, và hy vọng rằng từ đây, chúng ta sẽ có thêm thật nhiều kỷ niệm đẹp cùng những trang truyện.
                </p>

                <p style="margin-bottom: 24px;">
                    Chúc mọi người đọc truyện thật vui, gặp được thật nhiều bộ truyện hợp gu và luôn tìm thấy một chút ngọt ngào giữa những ngày bình thường. ♡
                </p>

                <div style="margin: 24px 0 20px; font-style: italic;">
                    <p style="margin-bottom: 4px;">Thân thương,</p>
                    <p style="font-weight: 700; color: var(--primary-green); font-size: 16px; margin: 0;">— Mướp 🍈💚</p>
                </div>

                <div style="background: rgba(22, 163, 74, 0.08); border-left: 4px solid var(--primary-green); padding: 14px 18px; border-radius: 0 var(--radius-md, 8px) var(--radius-md, 8px) 0; font-style: italic; color: var(--text-main); margin-top: 24px; font-size: 15px;">
                    “Mỗi câu chuyện là một thế giới nhỏ, còn Mướp chỉ muốn gom góp những thế giới mình yêu thương vào một góc bình yên.”
                </div>
            <?php endif; ?>
        </div>

        <div style="text-align: center; margin-top: 24px;">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary" style="padding: 10px 24px;">
                <i class="fa-solid fa-book-open"></i> Bắt Đầu Đọc Truyện Ngay
            </a>
        </div>
    </div>
</div>

<?php
get_footer();
