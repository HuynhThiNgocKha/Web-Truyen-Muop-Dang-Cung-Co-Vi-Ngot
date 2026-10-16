<?php
/**
 * Seed sample data for Mướp Đắng Cũng Có Vị Ngọt
 */
define('WP_USE_THEMES', false);
require_once __DIR__ . '/wp-load.php';

echo "Seeding data...\n";

// 1. Roles Check
muop_setup_roles();
muop_setup_tables();
muop_create_required_pages();

// 2. Create Users
$users_to_create = array(
    array(
        'user_login'   => 'dichgia_muop',
        'user_pass'    => 'dichgia123',
        'user_email'   => 'muop@muopdang.local',
        'display_name' => 'Tiểu Mướp Dịch Thuật',
        'role'         => 'dich_gia'
    ),
    array(
        'user_login'   => 'dichgia_bocute',
        'user_pass'    => 'dichgia123',
        'user_email'   => 'bo@muopdang.local',
        'display_name' => 'Team Bơ Xanh Xinh Đẹp',
        'role'         => 'dich_gia'
    ),
    array(
        'user_login'   => 'docgia_linh',
        'user_pass'    => 'docgia123',
        'user_email'   => 'linh@muopdang.local',
        'display_name' => 'Thùy Linh Mọt Sách',
        'role'         => 'doc_gia'
    ),
    array(
        'user_login'   => 'docgia_minh',
        'user_pass'    => 'docgia123',
        'user_email'   => 'minh@muopdang.local',
        'display_name' => 'Minh Hoàng Zhihu',
        'role'         => 'doc_gia'
    )
);

$user_ids = array();
foreach ($users_to_create as $u) {
    $existing = get_user_by('login', $u['user_login']);
    if (!$existing) {
        $uid = wp_create_user($u['user_login'], $u['user_pass'], $u['user_email']);
        $new_u = new WP_User($uid);
        $new_u->set_role($u['role']);
        wp_update_user(array('ID' => $uid, 'display_name' => $u['display_name']));
        $user_ids[$u['user_login']] = $uid;
        echo "Created user: {$u['user_login']} ({$u['role']})\n";
    } else {
        $user_ids[$u['user_login']] = $existing->ID;
    }
}

$admin_id   = get_user_by('login', 'admin')->ID;
$muop_id    = $user_ids['dichgia_muop'] ?? $admin_id;
$bo_id      = $user_ids['dichgia_bocute'] ?? $admin_id;
$linh_id    = $user_ids['docgia_linh'] ?? $admin_id;

// 3. Create Categories (Thể loại)
$genres_list = array(
    'Ngôn tình', 'Ngọt', 'Ngược', 'Đam mỹ', 'Cổ trang', 
    'Hiện đại', 'Tương lai', 'Xuyên không', 'Xuyên sách', 
    'Zhihu', 'Trọng sinh', 'Hài hước'
);

$genre_terms = array();
foreach ($genres_list as $gname) {
    $t = term_exists($gname, 'the_loai');
    if (!$t) {
        $t = wp_insert_term($gname, 'the_loai');
    }
    $genre_terms[$gname] = is_array($t) ? $t['term_id'] : $t;
}

// 4. Sample Stories
$stories_seed = array(
    array(
        'title'       => 'Mướp Đắng Cũng Có Vị Ngọt: Sau Ly Hôn Tôi Trở Thành Ánh Trăng Sáng Của Tổng Tài',
        'author'      => 'Tửu Tiểu Thi',
        'team'        => 'Tiểu Mướp Dịch Thuật',
        'user_author' => $muop_id,
        'status'      => 'dang_ra',
        'views'       => 15240,
        'nominate'    => 'day',
        'genres'      => array('Ngôn tình', 'Ngọt', 'Hiện đại', 'Zhihu'),
        'desc'        => "Ba năm làm vợ trên danh nghĩa của Lục Cảnh Hoài, tôi ngoan ngoãn nghe lời, không đòi hỏi một danh phận thực sự. Ngày cô gái thanh mai trúc mã của anh trở về, tôi chủ động đưa đơn ly hôn kèm một chữ ký dứt khoát.\n\nNhưng ngay ngày hôm sau, tại buổi tiệc thượng lưu, vị tổng tài cao ngạo nổi tiếng khắp kinh thành lại nắm chặt tay tôi nơi góc tối hành lang, đôi mắt đỏ hoe: 'Em rời đi, ngay cả trái tim tôi em cũng mang theo rồi sao?'",
        'chapters'    => array(
            array(
                'title'   => 'Quyết định ký đơn ly hôn',
                'content' => "<p>Trời mưa rả rích suốt ba ngày ở Kinh Đô.</p><p>Hứa Nhược Ngưng đặt tách trà lài ấm nóng lên bàn trà, ngón tay nhẹ nhàng đẩy tờ giấy thỏa thuận ly hôn đã ký sẵn tên mình sang phía người đàn ông ngồi đối diện.</p><p>'Lục Cảnh Hoài, chúng ta dừng lại ở đây thôi. Ba năm qua, cảm ơn anh đã chăm sóc.'</p><p>Người đàn ông mặc âu phục may đo màu xám tro hơi khựng lại. Anh ngẩng đầu lên từ tập tài liệu tài chính, ánh mắt thâm trầm như đáy hồ không một gợn sóng: 'Em chắc chắn? Em biết rõ rời khỏi Lục gia, em sẽ không có được bất cứ thứ gì ngoài số tiền bồi thường.'</p><p>'Tôi chắc chắn.' Nhược Ngưng mỉm cười nhẹ nhõm, nụ cười mà ba năm qua anh chưa từng thấy trên gương mặt cô.</p>"
            ),
            array(
                'title'   => 'Màn gặp lại bất ngờ tại yến tiệc',
                'content' => "<p>Hai tháng sau khi rời khỏi Lục gia.</p><p>Khách sạn Đế Đô lộng lẫy ánh đèn pha lê. Hôm nay là buổi tiệc thời trang lớn nhất năm của tập đoàn Vạn Tinh. Nhược Ngưng xuất hiện trong chiếc đầm dạ hội satin màu xanh bơ ôm trọn đường cong mềm mại, mái tóc uốn sóng buông lơi quyến rũ.</p><p>Khi cô bước lên bục nhận giải Nhà thiết kế của năm, toàn bộ quan khách dưới khán đài đều sững sờ. Đặc biệt là Lục Cảnh Hoài ở hàng ghế VIP.</p><p>Ly rượu vang trên tay anh suýt chút nữa rơi xuống thảm. Cô gái nhỏ luôn e thẹn ở nhà nấu canh cho anh mỗi tối, hóa ra lại là nhà thiết kế ẩn danh huyền thoại mà giới thời trang quốc tế săn đón bấy lâu!</p>"
            ),
            array(
                'title'   => 'Góc tối hành lang và lời thầm thì',
                'content' => "<p>'Em tránh mặt tôi?' Giọng nói trầm khàn quen thuộc vang lên sau lưng khi Nhược Ngưng vừa bước ra hành lang đón gió.</p><p>Cô chưa kịp quay lại thì một cánh tay rắn rỏi đã áp sát thành tường, chặn đứng lối đi của cô. Mùi hương gỗ tuyết tùng quen thuộc bao trùm lấy không gian.</p><p>'Lục tổng, ở đây có rất nhiều phóng viên, xin tự trọng.' Cô bình tĩnh ngước nhìn anh.</p><p>Lục Cảnh Hoài cúi thấp đầu, hơi thở phả qua vành tai mẫn cảm của cô: 'Tự trọng? Nhược Ngưng, hai tháng qua tôi chưa từng ngủ trọn một đêm nào. Em nói xem, em đã bỏ bùa gì vào căn nhà của tôi?'</p>"
            ),
            array(
                'title'   => 'Trái tim bắt đầu tan chảy',
                'content' => "<p>Nhược Ngưng đẩy nhẹ ngực áo của anh ra: 'Lục tổng đùa sao? Bạch nguyệt quang của anh đã về nước rồi, anh nên dành sự quan tâm này cho cô ấy mới phải.'</p><p>Lục Cảnh Hoài bật cười cay đắng: 'Bạch nguyệt quang nào chứ? Người tôi luôn tìm kiếm từ 10 năm trước ở trại hè năm đó... chính là người đang đứng trước mặt tôi bây giờ.'</p><p>Đôi mắt Nhược Ngưng khẽ chớp động. Ký ức về mùa hè năm ấy đột ngột ùa về...</p>"
            ),
            array(
                'title'   => 'Lời thề ngọt ngào dưới mưa',
                'content' => "<p>Mưa lại bắt đầu rơi ngoài ban công.</p><p>Lục Cảnh Hoài nắm lấy bàn tay mảnh khảnh của Nhược Ngưng, áp lên lồng ngực đang đập liên hồi của mình: 'Nếu trước đây tôi khiến em cảm thấy như một trái mướp đắng chát, thì từ hôm nay, tôi sẽ chứng minh cho em thấy... mướp đắng của em có vị ngọt lịm như thế nào.'</p><p>Cô nhìn vào ánh mắt chân thành chưa từng có của anh, bờ môi khẽ cong lên một nụ cười ấm áp.</p>"
            )
        )
    ),
    array(
        'title'       => 'Trọng Sinh Về Năm 18 Tuổi: Tôi Không Làm Kẻ Ngốc Nữa',
        'author'      => 'Chanh Chua Chua',
        'team'        => 'Team Bơ Xanh Xinh Đẹp',
        'user_author' => $bo_id,
        'status'      => 'hoan_thanh',
        'views'       => 28900,
        'nominate'    => 'week',
        'genres'      => array('Zhihu', 'Trọng sinh', 'Ngược', 'Ngôn tình'),
        'desc'        => "Kiếp trước, tôi vì cứu vị hôn phu và cô em gái nuôi mà bị hủy hoại dung nhan, cuối cùng bị bọn họ đẩy xuống biển lạnh giá vào đêm đông. Mở mắt ra lần nữa, tôi đã quay trở lại năm 18 tuổi, đúng ngày bọn họ bày mưu lừa tôi vào căn phòng cháy...",
        'chapters'    => array(
            array(
                'title'   => 'Tỉnh lại trong cơn ác mộng',
                'content' => "<p>Cảm giác nước biển lạnh buốt tràn vào phổi tan biến trong tích tắc.</p><p>Tôi bừng tỉnh, đập vào mắt là trần nhà màu xanh nhạt quen thuộc của căn phòng ngủ năm 18 tuổi. Trên bàn học, cuốn lịch bàn ghi rõ ngày 15 tháng 5 năm 2018.</p><p>'Mình... thật sự đã trọng sinh rồi sao?' Tôi sờ lên gương mặt mịn màng không tì vết của mình, nước mắt tuôn rơi nhưng miệng lại mỉm cười sắc lạnh.</p>"
            ),
            array(
                'title'   => 'Kế hoạch phản đòn hoàn hảo',
                'content' => "<p>Buổi chiều hôm ấy, điện thoại tôi rung lên thông báo tin nhắn từ Cố Gia Thành: 'Nhiên Nhiên, em tới kho thể dục tầng 3 giúp anh lấy hồ sơ nhé, anh đang bận họp.'</p><p>Tin nhắn y hệt như kiếp trước. Lần đó, tôi chạy tới và bị nhốt trong đám cháy, còn anh ta và em gái nuôi Lâm Vi Vi lại cùng nhau ăn kem ở quán cà phê đối diện.</p><p>Tôi khẽ nhếch môi, bấm chuyển tiếp tin nhắn ấy cho chính Lâm Vi Vi kèm lời nhắn: 'Chị bận rồi, em tới giúp anh Gia Thành nhé!'</p>"
            ),
            array(
                'title'   => 'Kẻ gieo gió gặt bão',
                'content' => "<p>Khi tiếng còi xe cứu hỏa rú vang khắp sân trường, tôi đứng từ ban công tầng 5 nhìn xuống.</p><p>Lâm Vi Vi ho sặc sụa được lính cứu hỏa đưa ra ngoài, chiếc váy hiệu đắt tiền lấm lem bùn đất tro tàn. Cố Gia Thành hớt hải chạy tới, nhìn thấy tôi đứng lành lặn xinh đẹp bên cạnh thì sắc mặt tái nhợt như xác ướp.</p><p>'Chào Cố thiếu, trò chơi bây giờ mới chỉ bắt đầu thôi!'</p>"
            )
        )
    ),
    array(
        'title'       => 'Cưới Trước Yêu Sau: Giáo Sư Nghiêm Túc Ban Ngày Ban Đêm Lại Rất Quấn Người',
        'author'      => 'Vãn Thu',
        'team'        => 'Tiểu Mướp Dịch Thuật',
        'user_author' => $muop_id,
        'status'      => 'dang_ra',
        'views'       => 9800,
        'nominate'    => 'month',
        'genres'      => array('Ngôn tình', 'Ngọt', 'Hiện đại'),
        'desc'        => "Vì làm vừa lòng người lớn hai bên, tôi kết hôn chớp nhoáng với vị giáo sư vật lý trẻ tuổi nhất viện hàn lâm. Ban ngày trên giảng đường anh lạnh lùng nghiêm nghị bao nhiêu, thì đêm về lại ôm chặt lấy tôi dỗ dành bấy nhiêu...",
        'chapters'    => array(
            array(
                'title'   => 'Cuộc xem mắt định mệnh',
                'content' => "<p>Giáo sư Cố Thần ngồi đối diện tôi trong quán cà phê sách, áo sơ mi trắng phẳng phiu cài cúc tận cổ.</p><p>'Mục đích xem mắt của tôi là kết hôn để ổn định gia đình. Nếu cô Chu không phản đối, tuần sau chúng ta có thể đăng ký.'</p><p>Tôi ngơ ngác nhìn anh: 'Nhanh vậy sao giáo sư Cố?'</p><p>'Hiệu quả là tiêu chuẩn số một trong mọi thí nghiệm vật lý của tôi.' Anh trả lời tỉnh queo.</p>"
            ),
            array(
                'title'   => 'Đêm tân hôn bất ngờ',
                'content' => "<p>Tôi ôm gối định ra sô-pha ngủ để giữ khoảng cách, nhưng chưa kịp bước chân thì cánh cửa phòng ngủ đã bị khóa trái.</p><p>Giáo sư Cố tháo mắt kính gọng vàng, ánh mắt không còn vẻ nghiêm cẩn thường ngày: 'Chu phu nhân, theo luật hôn nhân và quy tắc xác suất, chúng ta nên ngủ chung giường.'</p>"
            )
        )
    ),
    array(
        'title'       => 'Xuyên Sách Thành Nữ Phụ Pháo Hôi: Tôi Quyết Định Nằm Yên Nuôi Cá',
        'author'      => 'Mèo Béo Thích Ngủ',
        'team'        => 'Team Bơ Xanh Xinh Đẹp',
        'user_author' => $bo_id,
        'status'      => 'hoan_thanh',
        'views'       => 34200,
        'nominate'    => 'day',
        'genres'      => array('Xuyên sách', 'Hài hước', 'Cổ trang', 'Zhihu'),
        'desc'        => "Xuyên vào cuốn tiểu thuyết cẩu huyết, tôi thành vị hôn thê độc ác chuyên cản trở nam nữ chính. Thay vì đấu đá để bị chém đầu, tôi quyết định lui về thôn quê đào ao nuôi cá, trồng dưa hấu. Nào ngờ nam chính lại từ bỏ kinh thành để về làm nông dân cùng tôi...",
        'chapters'    => array(
            array(
                'title'   => 'Tỉnh dậy ở hậu viện',
                'content' => "<p>Vừa mở mắt ra, một nha hoàn đã khóc lóc: 'Tiểu thư, Vương gia sắp hủy hôn rồi, người đừng nhảy giếng nữa!'</p><p>Tôi ngồi dậy, phủi bụi trên váy: 'Hủy hôn thật à? Thế của hồi môn có trả lại đủ không?'</p><p>Nha hoàn ngẩn ngơ gật đầu: 'Dạ trả đủ...'</p><p>'Tốt lắm! Thu dọn hành lý, chúng ta về quê mua đất xây trang trại!'</p>"
            ),
            array(
                'title'   => 'Vương gia tới làm tá điền',
                'content' => "<p>Nửa năm sau, trang trại nuôi cá của tôi nức tiếng cả vùng.</p><p>Một hôm, một thiếu niên tuấn tú tuấn mã xuất hiện trước cổng tre, quỳ một gối trước đầm sen: 'Nàng cho ta làm công nhân chăm cá được không? Không cần lương, chỉ cần một chén cơm và một nụ cười của nàng mỗi ngày.'</p>"
            )
        )
    ),
    array(
        'title'       => 'Sau Khi Tôi Chết, Toàn Thể Nhà Họ Thẩm Hối Hận Không Kịp',
        'author'      => 'Nam Phong Tri Ý',
        'team'        => 'Tiểu Mướp Dịch Thuật',
        'user_author' => $muop_id,
        'status'      => 'hoan_thanh',
        'views'       => 45100,
        'nominate'    => 'week',
        'genres'      => array('Zhihu', 'Ngược', 'Hiện đại'),
        'desc'        => "Tôi là đứa con gái ruột bị thất lạc 15 năm, nhưng khi trở về chỉ nhận lại sự ghẻ lạnh từ bố mẹ và ba người anh trai. Họ cưng chiều con gái nuôi, ép tôi hiến tủy rồi đuổi tôi ra khỏi nhà giữa đêm đông. Ngày tôi qua đời vì bạo bệnh, cuốn nhật ký của tôi được công khai...",
        'chapters'    => array(
            array(
                'title'   => 'Lá thư cuối cùng',
                'content' => "<p>'Bố mẹ, các anh... nếu mọi người đọc được những dòng này, có lẽ tro cốt của con đã được rải ngoài biển khơi rồi.'</p><p>Tại tang lễ hoang tàn không một vòng hoa, người anh cả run rẩy cầm cuốn sổ tay dính máu mở từng trang. Những bí mật về người em gái ruột luôn cắn răng chịu đựng vì yêu thương gia đình dần hé lộ...</p>"
            ),
            array(
                'title'   => 'Nước mắt muộn màng',
                'content' => "<p>Bà Thẩm ngã quỵ trước di ảnh của con gái ruột, gào khóc thảm thiết: 'Mẹ sai rồi... Uyển Uyển ơi, con về với mẹ đi!'</p><p>Nhưng đáp lại chỉ là tiếng gió rít qua ô cửa sổ lạnh buốt...</p>"
            )
        )
    ),
    array(
        'title'       => 'Vị Ngọt Sau Mưa: Đại Ca Giang Hồ Và Cô Giáo Dạy Vẽ',
        'author'      => 'Mộc Miên',
        'team'        => 'Team Bơ Xanh Xinh Đẹp',
        'user_author' => $bo_id,
        'status'      => 'dang_ra',
        'views'       => 7400,
        'nominate'    => 'none',
        'genres'      => array('Ngôn tình', 'Ngọt', 'Hiện đại'),
        'desc'        => "Anh là đại ca lạnh lùng khét tiếng khu chợ cũ, người người sợ hãi. Nhưng mỗi chiều thứ Bảy, anh đều ngoan ngoãn ngồi trước giá vẽ của cô giáo nhỏ, ngượng ngùng để cô sửa từng nét cọ màu xanh mướp...",
        'chapters'    => array(
            array(
                'title'   => 'Lớp học vẽ chiều thứ Bảy',
                'content' => "<p>Lạc Phong ngồi co ro trên chiếc ghế gỗ nhỏ xíu, hai cánh tay xăm trổ vụng về cầm chiếc cọ vẽ màu nước.</p><p>Thẩm Dao mỉm cười tiến lại gần, bàn tay mát rượi chạm vào mu bàn tay anh: 'Anh Phong thả lỏng cổ tay ra một chút, vẽ lá mướp đắng thì nét cọ phải mềm mại như thế này nè.'</p><p>Mặt người đàn ông cao mét tám lăm bỗng đỏ bừng như gấc chín.</p>"
            ),
            array(
                'title'   => 'Chiếc ô màu xanh bơ che chở',
                'content' => "<p>Cơn mưa rào mùa hạ bất ngờ đổ xuống con phố nhỏ.</p><p>Lạc Phong bung chiếc ô màu xanh bơ to bản che kín cho Thẩm Dao, mặc cho nửa vai mình ướt đẫm nước mưa: 'Để anh đưa em về, trời tối rồi ngoài đường không an toàn.'</p>"
            )
        )
    ),
    array(
        'title'       => 'Thừa Tướng Đại Nhân, Phu Nhân Lại Bỏ Trốn Rồi!',
        'author'      => 'Thanh Phong',
        'team'        => 'Tiểu Mướp Dịch Thuật',
        'user_author' => $muop_id,
        'status'      => 'hoan_thanh',
        'views'       => 19800,
        'nominate'    => 'month',
        'genres'      => array('Cổ trang', 'Hài hước', 'Ngọt', 'Ngôn tình'),
        'desc'        => "Thừa tướng trẻ tuổi quyền khuynh triều dã, mưu lược vô song, nhưng ngày nào cũng phải đau đầu sai cấm vệ quân đi bắt vị thê tử nghịch ngợm thích leo tường trốn đi ngao du sơn thủy.",
        'chapters'    => array(
            array(
                'title'   => 'Bức tường hậu viện cao ba trượng',
                'content' => "<p>'Phu nhân, người mau xuống đi, đại nhân sắp bãi triều rồi!' Tiểu hoàn nha kêu la thất thanh dưới gốc cây đa.</p><p>Vân Lạc đang vắt vẻo trên bờ tường, tay cầm bọc tay nải: 'Không xuống! Ta muốn đi ngắm sông Tiền Đường, ở trong phủ ngột ngạt chết mất!'</p><p>'Nàng muốn đi ngắm sông cùng ai?' Một giọng nói uy nghiêm vang lên từ dưới bóng râm.</p>"
            ),
            array(
                'title'   => 'Bắt gọn tại trận',
                'content' => "<p>Vân Lạc trượt chân rơi xuống, nhưng không hề ngã xuống đất mà rơi trọn vào vòng tay vững chãi của Thừa tướng đại nhân.</p><p>'Lần này nàng trốn phạt chép gia quy mười lần, hay phạt cùng vi phu đi du ngoạn Giang Nam?' Anh nhướng mày cười.</p>"
            )
        )
    ),
    array(
        'title'       => 'Ảnh Đế Nhà Bên Thích Giả Nghèo',
        'author'      => 'Kẹo Sữa',
        'team'        => 'Team Bơ Xanh Xinh Đẹp',
        'user_author' => $bo_id,
        'status'      => 'dang_ra',
        'views'       => 11200,
        'nominate'    => 'none',
        'genres'      => array('Hiện đại', 'Ngọt', 'Hài hước', 'Zhihu'),
        'desc'        => "Tôi cứ ngỡ anh chàng hàng xóm đẹp trai ngày ngày sang nhà tôi xin ăn ké là sinh viên nghèo thất nghiệp. Nào ngờ tối hôm đó xem lễ trao giải Kim Kê, tôi thấy anh mặc âu phục tiền tỷ lên nhận cúp Ảnh đế!",
        'chapters'    => array(
            array(
                'title'   => 'Bát mì trứng cà chua',
                'content' => "<p>'Em hàng xóm ơi, anh hết tiền mua đồ ăn rồi, cho anh xin bát mì được không?' Chàng trai gõ cửa nhà tôi với nụ cười tỏa nắng.</p><p>Tôi mềm lòng nấu cho anh bát mì trứng thơm phức. Anh ăn ngon lành như thể đó là sơn hào hải vị.</p>"
            ),
            array(
                'title'   => 'Bí mật bại lộ trên màn hình lớn',
                'content' => "<p>Tôi vừa ăn bỏng ngô vừa xem trực tiếp lễ trao giải điện ảnh danh giá.</p><p>'Và giải thưởng Nam diễn viên chính xuất sắc nhất thuộc về... Giang Trì!'</p><p>Khi gương mặt người đàn ông xuất hiện trên màn hình, tôi sặc cả ngụm trà sữa: 'Ơ kìa... đó chẳng phải là cái tên sang xin mì tối qua sao?!'</p>"
            )
        )
    ),
    array(
        'title'       => 'Xuyên Thành Mẹ Kế Của Nhân Vật Phản Diện Nhỏ',
        'author'      => 'Dạ Nguyệt',
        'team'        => 'Tiểu Mướp Dịch Thuật',
        'user_author' => $muop_id,
        'status'      => 'hoan_thanh',
        'views'       => 22400,
        'nominate'    => 'day',
        'genres'      => array('Xuyên không', 'Ngọt', 'Hài hước'),
        'desc'        => "Xuyên vào tiểu thuyết làm mẹ kế của trùm phản diện lúc nó mới 5 tuổi. Thay vì ngược đãi nó để sau này bị trả thù, tôi ngày ngày nấu đồ ăn ngon dỗ dành nó béo tròn, đến mức bố nó cũng đòi ăn theo!",
        'chapters'    => array(
            array(
                'title'   => 'Cậu nhóc mặt lạnh 5 tuổi',
                'content' => "<p>Cậu nhóc trừng mắt nhìn tôi đầy cảnh giác: 'Tôi không ăn đồ của cô đâu!'</p><p>Tôi đặt đĩa bánh bao kim sa nóng hổi, thơm nức mùi sữa xuống bàn: 'Không ăn thì cô ăn hết nhé, ngon lắm đấy.'</p><p>Bụng cậu nhóc kêu 'rột rột' một tiếng rõ to.</p>"
            ),
            array(
                'title'   => 'Thu phục nhóc con và ông bố',
                'content' => "<p>Chưa đầy một tháng, cậu nhóc đã bám chặt lấy chân tôi: 'Mẹ ơi, hôm nay con muốn ăn bánh flan bí đỏ mẹ làm!'</p><p>Người đàn ông tổng tài quyền lực đứng tựa cửa nhìn hai mẹ con, ánh mắt tràn ngập cưng chiều: 'Thế phần của bố đâu hả hai mẹ con?'</p>"
            )
        )
    ),
    array(
        'title'       => 'Đoạn Tuyệt: Tôi Không Còn Là Nữ Phụ Trong Câu Chuyện Của Anh',
        'author'      => 'Thanh Thi',
        'team'        => 'Team Bơ Xanh Xinh Đẹp',
        'user_author' => $bo_id,
        'status'      => 'hoan_thanh',
        'views'       => 16700,
        'nominate'    => 'week',
        'genres'      => array('Zhihu', 'Ngược', 'Hiện đại'),
        'desc'        => "Bảy năm thanh xuân đổi lấy một câu 'Cô ấy cần anh hơn'. Ngày anh hủy hôn để chạy đến bên người cũ, tôi đốt sạch ảnh chụp chung và chuyển đến thành phố khác sống cuộc đời rực rỡ của chính mình.",
        'chapters'    => array(
            array(
                'title'   => 'Hủy bỏ hôn lễ',
                'content' => "<p>'Xin lỗi An An, Vy Vy đang sốt cao trong bệnh viện, hôn lễ dời lại tuần sau nhé.'</p><p>Tôi nhìn vào màn hình điện thoại đang sáng, không khóc, cũng không làm loạn. Tôi tháo chiếc nhẫn đính hôn để lên bàn lễ tân khách sạn rồi xách vali bước ra sân bay.</p>"
            ),
            array(
                'title'   => 'Sự biến mất hoàn toàn',
                'content' => "<p>Khi anh ta quay lại căn hộ chung, tất cả đồ đạc của tôi đã biến mất không còn một dấu vết. Căn phòng trống rỗng và lạnh tanh.</p><p>Lúc này anh ta mới phát hiện, tài khoản mạng xã hội của tôi đã xóa sạch mọi ký ức về anh.</p>"
            )
        )
    ),
    array(
        'title'       => 'Ngọt Ngào Trong Tầm Mắt: Đội Trưởng Đội Cứu Hỏa Rất Chiều Vợ',
        'author'      => 'Bắc Hải',
        'team'        => 'Tiểu Mướp Dịch Thuật',
        'user_author' => $muop_id,
        'status'      => 'dang_ra',
        'views'       => 13600,
        'nominate'    => 'none',
        'genres'      => array('Ngôn tình', 'Ngọt', 'Hiện đại'),
        'desc'        => "Cô phóng viên chiến trường gan góc gặp gỡ chàng đội trưởng cứu hỏa quả cảm. Hai con người từng chứng kiến bao sinh ly tử biệt tìm thấy bến đỗ bình yên và ngọt ngào nhất bên nhau.",
        'chapters'    => array(
            array(
                'title'   => 'Cuộc phỏng vấn giữa khói lửa',
                'content' => "<p>Giữa tiếng còi hụ và khói mù mịt, một cánh tay vững chãi kéo tôi vào khu vực an toàn.</p><p>'Phóng viên nhỏ, đừng mải quay phim mà quên cả mạng sống của mình chứ!' Đôi mắt sáng ngời sau chiếc mũ bảo hộ nhìn thẳng vào tôi.</p>"
            ),
            array(
                'title'   => 'Bông hoa hồng cài áo',
                'content' => "<p>Sau chiến dịch cứu nạn thành công, anh xuất hiện trước đài truyền hình với bộ đồng phục thẳng tắp, trên tay cầm bó hoa hướng dương rực rỡ: 'Em đồng ý làm người nhà của lính cứu hỏa không?'</p>"
            )
        )
    ),
    array(
        'title'       => 'Thiếu Tướng Quân Hôm Nay Cũng Muốn Từ Hôn',
        'author'      => 'Tuyết Sơn',
        'team'        => 'Team Bơ Xanh Xinh Đẹp',
        'user_author' => $bo_id,
        'status'      => 'hoan_thanh',
        'views'       => 21500,
        'nominate'    => 'month',
        'genres'      => array('Cổ trang', 'Ngọt', 'Hài hước'),
        'desc'        => "Thiếu tướng quân biên ải uy danh hiển hách quyết tâm về kinh từ hôn với thiên kim tiểu thư yểu điệu. Nào ngờ nàng tiểu thư ấy lại tinh thông võ nghệ, bắn cung bách phát bách trúng khiến chàng mê mẩn.",
        'chapters'    => array(
            array(
                'title'   => 'Hẹn gặp ở trường bắn',
                'content' => "<p>'Nghe nói tướng quân muốn từ hôn vì chê ta trói gà không chặt?' Thiếu nữ mặc hồng y giương cung bắn liên tiếp ba phát hồng tâm.</p><p>Thiếu tướng quân trợn tròn mắt, lập tức giấu lá đơn từ hôn ra sau lưng: 'Ai nói bậy thế? Ta về kinh là để xin hoàng thượng định ngày đại hôn!'</p>"
            )
        )
    )
);

// Insert Stories & Chapters
foreach ($stories_seed as $s) {
    $existing = get_page_by_title($s['title'], OBJECT, 'truyen');
    if ($existing) continue;

    $post_id = wp_insert_post(array(
        'post_title'   => $s['title'],
        'post_content' => $s['desc'],
        'post_status'  => 'publish',
        'post_type'    => 'truyen',
        'post_author'  => $s['user_author']
    ));

    if (!is_wp_error($post_id)) {
        update_post_meta($post_id, '_truyen_status', $s['status']);
        update_post_meta($post_id, '_truyen_views', $s['views']);
        update_post_meta($post_id, '_truyen_views_' . $current_month_str, round($s['views'] * 0.7));
        update_post_meta($post_id, '_truyen_views_' . $last_month_str, round($s['views'] * 0.3));
        update_post_meta($post_id, '_truyen_nominated', $s['nominate']);
        update_post_meta($post_id, '_truyen_author_name', $s['author']);
        update_post_meta($post_id, '_truyen_team', $s['team']);

        // Taxonomies
        if (!empty($s['genres'])) {
            $cat_ids = array();
            foreach ($s['genres'] as $g) {
                if (isset($genre_terms[$g])) $cat_ids[] = (int) $genre_terms[$g];
            }
            wp_set_object_terms($post_id, $cat_ids, 'the_loai');
        }

        // Chapters
        if (!empty($s['chapters'])) {
            foreach ($s['chapters'] as $c_idx => $c) {
                $chap_num = $c_idx + 1;
                $chap_title = "Chương {$chap_num}: {$c['title']}";
                $cid = wp_insert_post(array(
                    'post_title'   => $chap_title,
                    'post_content' => $c['content'],
                    'post_status'  => 'publish',
                    'post_type'    => 'chuong',
                    'post_author'  => $s['user_author']
                ));
                if (!is_wp_error($cid)) {
                    update_post_meta($cid, '_chuong_truyen_id', $post_id);
                    update_post_meta($cid, '_chuong_number', $chap_num);
                    update_post_meta($cid, '_chuong_views', round($s['views'] / count($s['chapters'])));
                }
            }
        }

        // Sample Comments
        wp_insert_comment(array(
            'comment_post_ID'      => $post_id,
            'comment_author'       => 'Thùy Linh Mọt Sách',
            'comment_author_email' => 'linh@muopdang.local',
            'comment_content'      => 'Truyện hay quá, dịch mượt mà xúc động dã man! Hóng team ra chương mới từng ngày ❤️',
            'comment_approved'     => 1,
            'user_id'              => $linh_id
        ));

        echo "Created story: {$s['title']} ({$s['views']} views)\n";
    }
}

// 5. Seed Bookmarks & Reading History for Reader docgia_linh
$first_story = get_posts(array('post_type' => 'truyen', 'posts_per_page' => 1));
if (!empty($first_story)) {
    $sid = $first_story[0]->ID;
    $chaps = muop_get_story_chapters($sid);
    $cid = !empty($chaps) ? $chaps[0]->ID : 0;

    // Bookmark
    $table_bm = $wpdb->prefix . 'reading_bookmarks';
    $wpdb->replace($table_bm, array(
        'user_id'    => $linh_id,
        'story_id'   => $sid,
        'created_at' => current_time('mysql')
    ));

    // History
    if ($cid) {
        $table_hist = $wpdb->prefix . 'reading_history';
        $wpdb->replace($table_hist, array(
            'user_id'     => $linh_id,
            'story_id'    => $sid,
            'chapter_id'  => $cid,
            'chapter_num' => 1,
            'updated_at'  => current_time('mysql')
        ));
    }
}

// 6. Seed Sample Affiliate Clicks
$table_clicks = $wpdb->prefix . 'affiliate_clicks';
$wpdb->insert($table_clicks, array(
    'link_type'   => 'shopee',
    'url'         => 'https://s.shopee.vn/4qG9lQO2rp',
    'ip'          => '127.0.0.1',
    'user_id'     => $linh_id,
    'story_id'    => $first_story[0]->ID ?? 0,
    'chapter_num' => 2,
    'created_at'  => current_time('mysql')
));
update_option('muop_shopee_clicks_count', 42);
update_option('muop_tiktok_clicks_count', 18);

echo "Data seeding completed successfully!\n";
