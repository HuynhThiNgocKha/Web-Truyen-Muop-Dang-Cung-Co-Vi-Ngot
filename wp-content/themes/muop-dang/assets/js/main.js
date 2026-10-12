/**
 * MƯỚP ĐẮNG CŨNG CÓ VỊ NGỌT - CLIENT CORE SCRIPT
 * - Dark/Light mode
 * - Shopee Gatekeeper Modal (Chương 2, 5, 8...)
 * - Hidden background affiliate triggers
 * - Anti-copy content protection
 * - AJAX Login, Register, Bookmarks, Comments, Story Submission
 */

(function($) {
  'use strict';

  // Safe helper to extract message from AJAX response or error (prevents undefined reading 'message')
  function getAjaxMsg(res, defaultMsg) {
    if (!res) return defaultMsg || 'Có lỗi xảy ra!';
    if (typeof res === 'string') {
      let clean = res.trim();
      // Remove any leaked PHP HTML notices/warnings
      if (clean.indexOf('PHP Request Startup') !== -1 || clean.indexOf('<b>Notice</b>') !== -1 || clean.indexOf('<b>Warning</b>') !== -1) {
        clean = clean.replace(/<br\s*\/?>\s*<b>(?:Notice|Warning|Deprecated)<\/b>:.*?<br\s*\/?>/gi, '').trim();
      }
      if (clean === '-1') return 'Phiên bảo mật (nonce) đã hết hạn. Vui lòng tải lại trang (F5) và thử lại!';
      if (clean === '0' || clean.endsWith(' 0') || clean === '0') return 'Bạn cần đăng nhập để thực hiện chức năng này!';
      try {
        const json = JSON.parse(clean);
        return getAjaxMsg(json, defaultMsg);
      } catch (e) {
        return clean.length < 250 && clean.length > 0 ? clean : (defaultMsg || 'Lỗi xử lý máy chủ!');
      }
    }
    if (res.data) {
      if (typeof res.data === 'string') return res.data;
      if (res.data.message) return res.data.message;
    }
    if (res.message) return res.message;
    return defaultMsg || 'Có lỗi xảy ra!';
  }

  // 1. THEME TOGGLE (DARK / LIGHT MODE)
  const ThemeManager = {
    init() {
      const savedTheme = localStorage.getItem('muop_theme') || 'light';
      this.setTheme(savedTheme);

      $(document).on('click', '.theme-toggle-btn', () => {
        const currentTheme = $('html').attr('data-theme') || 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        this.setTheme(newTheme);
      });
    },

    setTheme(theme) {
      $('html').attr('data-theme', theme);
      localStorage.setItem('muop_theme', theme);
      const $icon = $('.theme-toggle-btn i');
      if (theme === 'dark') {
        $icon.removeClass('fa-moon').addClass('fa-sun');
      } else {
        $icon.removeClass('fa-sun').addClass('fa-moon');
      }
      $(document).trigger('muopThemeChanged', [theme]);
    }
  };

  // 2. PASSWORD TOGGLE
  const PasswordToggle = {
    init() {
      $(document).on('click', '.toggle-password-visibility', function() {
        const targetId = $(this).data('target');
        const $input = $('#' + targetId);
        const $icon = $(this).find('i');
        
        if ($input.attr('type') === 'password') {
          $input.attr('type', 'text');
          $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
          $input.attr('type', 'password');
          $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
      });
    }
  };

  // 3. SHOPEE GATEKEEPER & AFFILIATE CLICK TRACKING
  const AffiliateManager = {
    pendingUnlock: null,

    init() {
      this.bindChapterListInterceptors();
      this.bindUnlockButton();
      this.checkChapterLock();
    },

    // Chặn click vào bất kỳ chương nào không phải chương 1 nếu chưa mở khóa link Shopee
    bindChapterListInterceptors() {
      // 1. Bắt sự kiện khi click vào link chương trên trang truyện hoặc thanh điều hướng
      $(document).on('click', '.chapter-item, .btn-read-latest, .chapter-drawer-link, .btn-next-chapter', function(e) {
        const $el = $(this);
        const href = $el.attr('href');
        if (!href || href === '#' || href.startsWith('javascript:')) return;

        // Xác định xem có phải Chương 1 không
        const isChap1Attr = $el.data('is-chap1');
        const isChap1 = (isChap1Attr === 1 || isChap1Attr === '1');
        const storyId = parseInt($el.data('story-id'), 10) || 0;
        const chapterId = parseInt($el.data('chapter-id'), 10) || 0;
        const chapNum = parseInt($el.data('chapter-num'), 10) || 0;

        // Kiểm tra chữ trong tiêu đề nếu chưa có meta
        const text = $el.text().toLowerCase().trim();
        const isFirstChapter = isChap1 || (chapNum === 1) || 
          (chapNum === 0 && text.includes('chương 1') && !text.match(/chương 1\d/));

        // NẾU LÀ CHƯƠNG 1 -> ĐỌC MIỄN PHÍ TRỰC TIẾP, KHÔNG CHẶN
        if (isFirstChapter) {
          return;
        }

        // BẤT KỲ CHƯƠNG NÀO KHÔNG PHẢI CHƯƠNG 1 (CHƯƠNG 2, 3, 4, 5...):
        // Kiểm tra xem chương này đã được mở khóa trong phiên chưa
        const isUnlocked = (chapterId && sessionStorage.getItem(`muop_unlocked_chap_${chapterId}`)) ||
                           (storyId && chapNum && sessionStorage.getItem(`muop_unlocked_s${storyId}_c${chapNum}`));

        if (isUnlocked) {
          // Đã mở khóa trước đó trong phiên này -> Cho phép mở đọc
          return;
        }

        // CHƯA MỞ KHÓA -> CHẶN LẠI VÀ HIỆN POPUP SHOPEE BẮT BUỘC
        e.preventDefault();
        e.stopPropagation();

        AffiliateManager.pendingUnlock = {
          targetUrl: href,
          storyId: storyId,
          chapterId: chapterId,
          chapNum: chapNum
        };

        $('#shopeeGatekeeperModal').css('display', 'flex').hide().fadeIn(250);
      });

      // 2. Nếu đang ở trang đọc chương mà bị khóa, click vào nội dung mờ sẽ mở lại modal Shopee
      $(document).on('click', '.chapter-locked, .chapter-locked-init', function(e) {
        e.preventDefault();
        $('#shopeeGatekeeperModal').css('display', 'flex').hide().fadeIn(250);
      });

      // 3. Nút đóng modal Shopee (Nếu độc giả không bấm link Shopee thì không được vào đọc)
      $(document).on('click', '#btnCloseShopeeModal', function(e) {
        e.preventDefault();
        $('#shopeeGatekeeperModal').fadeOut(200);
        AffiliateManager.pendingUnlock = null;
      });

      // 4. Click ra nền mờ đóng modal (vẫn không đọc được nếu không click link)
      $(document).on('click', '#shopeeGatekeeperModal', function(e) {
        if (e.target === this) {
          $(this).fadeOut(200);
          AffiliateManager.pendingUnlock = null;
        }
      });
    },

    // Xử lý khi độc giả bấm vào nút mở link Shopee để mở khóa
    bindUnlockButton() {
      $(document).on('click', '#btnShopeeUnlock', (e) => {
        e.preventDefault();
        const shopeeUrl = $('#btnShopeeUnlock').attr('href') || muopConfig.shopeeUrl || 'https://s.shopee.vn/4qG9lQO2rp';

        // 1. Mở link Shopee trong tab mới
        window.open(shopeeUrl, '_blank');

        const pending = AffiliateManager.pendingUnlock;
        let storyId = 0;
        let chapterId = 0;
        let chapNum = 1;

        if (pending) {
          storyId = pending.storyId;
          chapterId = pending.chapterId;
          chapNum = pending.chapNum;
        } else {
          const $rw = $('.chapter-reading-wrapper');
          if ($rw.length) {
            storyId = parseInt($rw.data('story-id'), 10) || 0;
            chapterId = parseInt($rw.data('chapter-id'), 10) || 0;
            chapNum = parseInt($rw.data('chapter-num'), 10) || 1;
          }
        }

        // 2. Track click qua AJAX
        this.trackClick('shopee', shopeeUrl, storyId, chapNum);

        // 3. Đánh dấu đã mở khóa trong sessionStorage
        if (chapterId) {
          sessionStorage.setItem(`muop_unlocked_chap_${chapterId}`, 'true');
        }
        if (storyId && chapNum) {
          sessionStorage.setItem(`muop_unlocked_s${storyId}_c${chapNum}`, 'true');
        }

        // 4. Nếu có targetUrl (độc giả vừa bấm từ danh sách chương):
        // Đóng modal và chuyển hướng ngay tới chương đó để đọc!
        if (pending && pending.targetUrl) {
          $('#shopeeGatekeeperModal').fadeOut(200);
          const target = pending.targetUrl;
          AffiliateManager.pendingUnlock = null;
          window.location.href = target;
          return;
        }

        // 5. Nếu đang ở chính trang đọc chương này (single-chuong.php):
        // Mở khóa nội dung chương ngay lập tức
        $('#shopeeGatekeeperModal').fadeOut(250);
        $('.chapter-content-box').removeClass('chapter-locked chapter-locked-init');
      });
    },

    // Kiểm tra trạng thái khóa khi vừa tải trang đọc chương (single-chuong.php)
    checkChapterLock() {
      const $readingWrapper = $('.chapter-reading-wrapper');
      if (!$readingWrapper.length) return;

      const storyId = parseInt($readingWrapper.data('story-id'), 10) || 0;
      const chapterId = parseInt($readingWrapper.data('chapter-id'), 10) || 0;
      const chapNum = parseInt($readingWrapper.data('chapter-num'), 10) || 1;
      const checkpointAttr = $readingWrapper.data('is-checkpoint');

      // Nếu là Chương 1 -> Đọc trực tiếp, đảm bảo không bị khóa
      if (chapNum === 1 && (checkpointAttr === 0 || checkpointAttr === '0')) {
        $('.chapter-content-box').removeClass('chapter-locked chapter-locked-init');
        $('#shopeeGatekeeperModal').hide();
        return;
      }

      // Bất kỳ chương nào không phải Chương 1:
      // Kiểm tra xem đã mở khóa trong phiên chưa
      const isUnlocked = (chapterId && sessionStorage.getItem(`muop_unlocked_chap_${chapterId}`)) ||
                         (storyId && chapNum && sessionStorage.getItem(`muop_unlocked_s${storyId}_c${chapNum}`));

      if (!isUnlocked) {
        // Chưa mở khóa -> Khóa nội dung và hiển thị Popup Shopee
        $('.chapter-content-box').addClass('chapter-locked');
        $('#shopeeGatekeeperModal').css('display', 'flex').hide().fadeIn(300);
      } else {
        // Đã mở khóa -> Mở toàn bộ nội dung đọc
        $('.chapter-content-box').removeClass('chapter-locked chapter-locked-init');
        $('#shopeeGatekeeperModal').hide();
      }
    },

    // Track click via AJAX
    trackClick(type, url, storyId, chapterNum) {
      $.ajax({
        url: muopConfig.ajaxUrl,
        type: 'POST',
        data: {
          action: 'muop_track_click',
          link_type: type,
          url: url,
          story_id: storyId || 0,
          chapter_num: chapterNum || 0
        }
      });
    }
  };

  // 4. ANTI-COPY PROTECTION
  const AntiCopyManager = {
    init() {
      const $box = $('.chapter-content-box');
      if (!$box.length) return;

      // Prevent Context Menu (Right click)
      $box.on('contextmenu', function(e) {
        e.preventDefault();
        AntiCopyManager.showNotice('Nội dung thuộc bản quyền website Mướp Đắng Cũng Có Vị Ngọt. Vui lòng không sao chép!');
        return false;
      });

      // Prevent Copy / Cut
      $box.on('copy cut selectstart', function(e) {
        e.preventDefault();
        AntiCopyManager.showNotice('Chức năng sao chép nội dung đã bị vô hiệu hóa để bảo vệ tác quyền!');
        return false;
      });

      // Prevent Key combinations (Ctrl+C, Ctrl+U, Ctrl+S, F12)
      $(document).on('keydown', function(e) {
        if (
          (e.ctrlKey && (e.key === 'c' || e.key === 'C' || e.key === 'u' || e.key === 'U' || e.key === 's' || e.key === 'S' || e.key === 'p' || e.key === 'P')) ||
          e.key === 'F12'
        ) {
          if ($('.chapter-reading-wrapper').length) {
            e.preventDefault();
            AntiCopyManager.showNotice('Thao tác phím tắt này bị chặn trên trang đọc truyện!');
            return false;
          }
        }
      });
    },

    showNotice(msg) {
      let $toast = $('#antiCopyToast');
      if (!$toast.length) {
        $toast = $('<div id="antiCopyToast" style="position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:#1C2B18;color:#fff;padding:12px 24px;border-radius:30px;box-shadow:0 6px 20px rgba(0,0,0,0.3);z-index:99999;font-size:13.5px;font-weight:600;display:none;border:1px solid #689F38;text-align:center;"></div>');
        $('body').append($toast);
      }
      $toast.text(msg).stop(true, true).fadeIn(200).delay(2500).fadeOut(300);
    }
  };

  // 5. READING CONTROLS (FONT SIZE, FONT FAMILY, TEXT COLOR, FLOATING SETTINGS, HISTORY, BOOKMARK)
  const ReadingManager = {
    // 5 Màu Đề Xuất Phổ Biến Cho Nền Sáng
    suggestedColorsLight: [
      { color: '#262626', name: 'Đen than (Chuẩn)' },
      { color: '#3E2723', name: 'Nâu cà phê' },
      { color: '#1A365D', name: 'Xanh chàm' },
      { color: '#1B4332', name: 'Xanh rêu' },
      { color: '#4A5568', name: 'Xám dịu' }
    ],
    // 5 Màu Đề Xuất Phổ Biến Cho Nền Tối
    suggestedColorsDark: [
      { color: '#E5E7EB', name: 'Trắng bạc (Chuẩn)' },
      { color: '#FDE68A', name: 'Vàng ấm' },
      { color: '#A7F3D0', name: 'Xanh ngọc' },
      { color: '#D1D5DB', name: 'Xám khói' },
      { color: '#FBCFE8', name: 'Hồng phấn' }
    ],

    prefs: {
      fontSize: ($(window).width() <= 768) ? 19 : 21,
      fontFamily: 'sans-serif',
      textColorLight: '#262626',
      textColorDark: '#E5E7EB',
      paragraphSpacing: ($(window).width() <= 768) ? 13 : 14
    },

    init() {
      this.loadPreferences();
      this.applyAllPreferences();
      this.bindReaderToolbarEvents();
      this.bindQuickModalEvents();
      this.bindFloatingButtonEvents();
      this.bindProfileSettingsEvents();
      this.bindChapterDrawerEvents();
      this.recordHistoryAndView();
      this.bindBookmarkToggle();

      // Khi đổi Theme Sáng / Tối, tự động cập nhật màu chữ và bảng màu tương ứng
      $(document).on('muopThemeChanged', (e, theme) => {
        this.updateColorsPaletteUI();
        this.applyTextColor();
      });
    },

    loadPreferences() {
      // Ưu tiên load từ user_meta qua muopConfig nếu đã đăng nhập
      let serverPrefs = muopConfig.readingPrefs;
      if (typeof serverPrefs === 'string') {
        try { serverPrefs = JSON.parse(serverPrefs); } catch (e) {}
      }

      let localPrefs = null;
      try {
        localPrefs = JSON.parse(localStorage.getItem('muop_reading_prefs'));
      } catch (e) {}

      // Legacy font size fallback
      const legacyFontSize = parseInt(localStorage.getItem('muop_reading_font_size'), 10);

      const source = serverPrefs || localPrefs || {};
      if (source.fontSize) this.prefs.fontSize = parseInt(source.fontSize, 10);
      else if (source.font_size) this.prefs.fontSize = parseInt(source.font_size, 10);
      else if (legacyFontSize) this.prefs.fontSize = Math.max(legacyFontSize, ($(window).width() <= 768) ? 19 : 21);

      if (source.fontFamily) this.prefs.fontFamily = source.fontFamily;
      else if (source.font_family) this.prefs.fontFamily = source.font_family;

      if (source.textColorLight) this.prefs.textColorLight = source.textColorLight;
      else if (source.text_color_light) this.prefs.textColorLight = source.text_color_light;

      if (source.textColorDark) this.prefs.textColorDark = source.textColorDark;
      else if (source.text_color_dark) this.prefs.textColorDark = source.text_color_dark;

      if (source.paragraphSpacing) this.prefs.paragraphSpacing = parseInt(source.paragraphSpacing, 10);
      else if (source.paragraph_spacing) this.prefs.paragraphSpacing = parseInt(source.paragraph_spacing, 10);
    },

    savePreferences(syncServer = false) {
      localStorage.setItem('muop_reading_prefs', JSON.stringify(this.prefs));
      localStorage.setItem('muop_reading_font_size', this.prefs.fontSize);
      localStorage.setItem('muop_reading_paragraph_spacing', this.prefs.paragraphSpacing);

      if (syncServer && muopConfig.isLoggedIn) {
        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_save_reading_preferences',
            nonce: muopConfig.nonce,
            font_size: this.prefs.fontSize,
            font_family: this.prefs.fontFamily,
            text_color_light: this.prefs.textColorLight,
            text_color_dark: this.prefs.textColorDark,
            paragraph_spacing: this.prefs.paragraphSpacing
          }
        });
      }
    },

    applyAllPreferences() {
      this.applyFontSize(this.prefs.fontSize);
      this.applyFontFamily(this.prefs.fontFamily);
      this.applyTextColor();
      this.applyParagraphSpacing(this.prefs.paragraphSpacing);
      this.updateColorsPaletteUI();
    },

    applyParagraphSpacing(spacing) {
      spacing = parseInt(spacing, 10) || (($(window).width() <= 768) ? 13 : 14);
      this.prefs.paragraphSpacing = spacing;

      document.documentElement.style.setProperty('--reading-paragraph-spacing', spacing + 'px');
      $('.chapter-body-text, #previewBodyText').css('--reading-paragraph-spacing', spacing + 'px');
      $('.chapter-body-text p, #previewBodyText p').css('margin-bottom', spacing + 'px');
      $('.chapter-body-text br, #previewBodyText br').css('margin-bottom', Math.round(spacing * 0.65) + 'px');

      $('#settingSpacingBadge, #profileSpacingDisplay').text(spacing + 'px');

      $('.setting-spacing-chips .spacing-chip-btn, #profileSpacingChips .spacing-chip-btn').removeClass('active');
      $(`.setting-spacing-chips .spacing-chip-btn[data-spacing="${spacing}"], #profileSpacingChips .spacing-chip-btn[data-spacing="${spacing}"]`).addClass('active');

      this.savePreferences(false);
    },

    applyFontSize(size) {
      this.prefs.fontSize = size;
      $('.chapter-body-text, #previewBodyText').css('font-size', size + 'px');
      $('#settingSizeBadge, #profileSizeDisplay').text(size + 'px');
      this.savePreferences(false);
    },

    applyFontFamily(fontKey) {
      this.prefs.fontFamily = fontKey;
      const $targets = $('.chapter-body-text, #previewBodyText');
      $targets.removeClass('reader-font-sans-serif reader-font-merriweather reader-font-be-vietnam reader-font-georgia reader-font-times');
      $targets.addClass('reader-font-' + fontKey);

      // Active chips UI
      $('.setting-font-chips .font-chip-btn').removeClass('active');
      $(`.setting-font-chips .font-chip-btn[data-font="${fontKey}"]`).addClass('active');

      this.savePreferences(false);
    },

    applyTextColor() {
      const isDark = ($('html').attr('data-theme') === 'dark');
      const activeColor = isDark ? this.prefs.textColorDark : this.prefs.textColorLight;

      $('.chapter-body-text, #previewBodyText').css('color', activeColor);

      // Update color inputs and hex badges
      $('#inputCustomColorReader, #inputProfileCustomColor').val(activeColor);
      $('#customColorHexReader, #profileCustomColorHex').text(activeColor.toUpperCase());

      // Update active swatch
      $('.color-swatch-item').removeClass('active');
      $(`.color-swatch-item[data-color="${activeColor.toUpperCase()}"]`).addClass('active');

      this.savePreferences(false);
    },

    updateColorsPaletteUI() {
      const isDark = ($('html').attr('data-theme') === 'dark');
      const list = isDark ? this.suggestedColorsDark : this.suggestedColorsLight;
      const activeColor = (isDark ? this.prefs.textColorDark : this.prefs.textColorLight).toUpperCase();

      const renderSwatches = (containerId) => {
        const $cont = $(containerId);
        if (!$cont.length) return;
        let html = '';
        list.forEach(item => {
          const uCol = item.color.toUpperCase();
          const isActive = (uCol === activeColor) ? 'active' : '';
          html += `<button type="button" class="color-swatch-item ${isActive}" data-color="${uCol}" style="background-color: ${uCol};" title="${item.name} (${uCol})" aria-label="${item.name}"></button>`;
        });
        $cont.html(html);
      };

      renderSwatches('#settingColorsPalette');
      renderSwatches('#profileColorsPalette');

      const modeHint = isDark ? '5 màu đề xuất cho Nền Tối 🌙:' : '5 màu đề xuất cho Nền Sáng ☀️:';
      $('#colorModeHint, #profileColorModeHint').text(modeHint);
    },

    // Toolbar A-, A+ & Settings buttons
    bindReaderToolbarEvents() {
      $('#btnFontInc').on('click', () => {
        if (this.prefs.fontSize < 32) {
          this.applyFontSize(this.prefs.fontSize + 2);
        }
      });

      $('#btnFontDec').on('click', () => {
        if (this.prefs.fontSize > 15) {
          this.applyFontSize(this.prefs.fontSize - 2);
        }
      });

      // Quick size in modal
      $('#btnQuickFontInc').on('click', () => {
        if (this.prefs.fontSize < 32) {
          this.applyFontSize(this.prefs.fontSize + 1);
        }
      });

      $('#btnQuickFontDec').on('click', () => {
        if (this.prefs.fontSize > 15) {
          this.applyFontSize(this.prefs.fontSize - 1);
        }
      });

      // Open settings triggers (từ nút trên thanh font-bar)
      $('#btnOpenReadingSettingsTop').on('click', (e) => {
        e.preventDefault();
        this.openQuickModal();
      });
    },

    // Floating Toolbar (Nút nổi khi cuộn đọc truyện & Kéo thả di chuyển không trôi chữ)
    bindFloatingButtonEvents() {
      const $floatingToolbar = $('#floatingReadingToolbar');
      if (!$floatingToolbar.length) return;

      // Khôi phục vị trí người dùng đã kéo thả trước đó (nếu có)
      const savedPos = localStorage.getItem('muop_floating_toolbar_pos') || localStorage.getItem('muop_floating_btn_pos');
      if (savedPos) {
        try {
          const pos = JSON.parse(savedPos);
          if (pos && typeof pos.top === 'number' && typeof pos.left === 'number') {
            const winW = $(window).width();
            const winH = $(window).height();
            const clampedLeft = Math.min(Math.max(10, pos.left), winW - 60);
            const clampedTop = Math.min(Math.max(60, pos.top), winH - 120);
            $floatingToolbar.css({
              top: clampedTop + 'px',
              left: clampedLeft + 'px',
              right: 'auto',
              bottom: 'auto'
            });
          }
        } catch (e) {}
      }

      // Hiện / ẩn khi cuộn trang
      $(window).on('scroll', () => {
        const scrollY = $(window).scrollTop();
        if (scrollY > 140) {
          $floatingToolbar.fadeIn(220);
        } else {
          $floatingToolbar.fadeOut(200);
        }
      });

      // Tùy ý di chuyển (Drag-to-move hỗ trợ Chuột & Cảm ứng điện thoại - Không cuộn trang, chữ đứng yên)
      let isDragging = false;
      let hasDragged = false;
      let startX = 0, startY = 0;
      let initialLeft = 0, initialTop = 0;

      const onDragStart = (clientX, clientY) => {
        isDragging = true;
        hasDragged = false;
        startX = clientX;
        startY = clientY;

        const rect = $floatingToolbar[0].getBoundingClientRect();
        initialLeft = rect.left;
        initialTop = rect.top;

        $floatingToolbar.css({
          transition: 'none'
        });
        $floatingToolbar.find('.floating-reading-btn').css('cursor', 'grabbing');
      };

      const onDragMove = (clientX, clientY) => {
        if (!isDragging) return;
        const dx = clientX - startX;
        const dy = clientY - startY;

        if (Math.abs(dx) > 5 || Math.abs(dy) > 5) {
          hasDragged = true;
        }

        const winW = $(window).width();
        const winH = $(window).height();
        const tbW = $floatingToolbar.outerWidth() || 46;
        const tbH = $floatingToolbar.outerHeight() || 100;

        let newLeft = initialLeft + dx;
        let newTop = initialTop + dy;

        newLeft = Math.min(Math.max(8, newLeft), winW - tbW - 8);
        newTop = Math.min(Math.max(50, newTop), winH - tbH - 20);

        $floatingToolbar.css({
          left: newLeft + 'px',
          top: newTop + 'px',
          right: 'auto',
          bottom: 'auto'
        });
      };

      const onDragEnd = () => {
        if (!isDragging) return;
        isDragging = false;

        $floatingToolbar.find('.floating-reading-btn').css('cursor', 'grab');
        $floatingToolbar.css({
          transition: 'opacity 0.22s ease, transform 0.22s ease'
        });

        if (hasDragged) {
          const rect = $floatingToolbar[0].getBoundingClientRect();
          localStorage.setItem('muop_floating_toolbar_pos', JSON.stringify({
            left: rect.left,
            top: rect.top
          }));
        }
      };

      const toolbarEl = $floatingToolbar[0];

      // Touch events (Mobile) với non-passive listener để e.preventDefault() hoạt động triệt để
      toolbarEl.addEventListener('touchstart', (e) => {
        if (e.touches.length === 1) {
          onDragStart(e.touches[0].clientX, e.touches[0].clientY);
        }
      }, { passive: true });

      window.addEventListener('touchmove', (e) => {
        if (isDragging && e.touches.length === 1) {
          // Ngăn chặn trình duyệt cuộn trang khi độc giả đang kéo nút - chữ đọc truyện đứng yên 100%
          if (e.cancelable) {
            e.preventDefault();
          }
          onDragMove(e.touches[0].clientX, e.touches[0].clientY);
        }
      }, { passive: false });

      window.addEventListener('touchend', () => {
        if (isDragging) {
          onDragEnd();
        }
      }, { passive: true });

      window.addEventListener('touchcancel', () => {
        if (isDragging) {
          onDragEnd();
        }
      }, { passive: true });

      // Mouse events (Desktop)
      toolbarEl.addEventListener('mousedown', (e) => {
        if (e.button !== 0) return;
        onDragStart(e.clientX, e.clientY);
      });

      window.addEventListener('mousemove', (e) => {
        if (isDragging) {
          e.preventDefault();
          onDragMove(e.clientX, e.clientY);
        }
      });

      window.addEventListener('mouseup', () => {
        if (isDragging) {
          onDragEnd();
        }
      });

      // Click event cho nút Lên Đầu Trang
      $('#btnFloatingScrollTop').on('click', (e) => {
        e.preventDefault();
        if (!hasDragged) {
          window.scrollTo({ top: 0, behavior: 'smooth' });
        }
      });

      // Click event cho nút Tùy Chỉnh Đọc Truyện (Popup Modal)
      $('#btnFloatingReadingSettings').on('click', (e) => {
        e.preventDefault();
        if (!hasDragged) {
          this.openQuickModal();
        }
      });
    },

    // Quick Modal Handlers
    openQuickModal() {
      $('#readingSettingsOverlay').fadeIn(180);
      $('#readingSettingsQuickModal').fadeIn(180);
      $('#btnFloatingReadingSettings').addClass('is-active');
      this.updateColorsPaletteUI();
    },

    closeQuickModal() {
      $('#readingSettingsQuickModal').fadeOut(150);
      $('#readingSettingsOverlay').fadeOut(150);
      $('#btnFloatingReadingSettings').removeClass('is-active');
    },

    bindQuickModalEvents() {
      $('#btnCloseReadingSettings, #readingSettingsOverlay').on('click', () => {
        this.closeQuickModal();
      });

      $(document).on('keydown', (e) => {
        if (e.key === 'Escape' && $('#readingSettingsQuickModal').is(':visible')) {
          this.closeQuickModal();
        }
      });

      // Paragraph spacing chips selection
      $(document).on('click', '#readingSpacingChips .spacing-chip-btn', (e) => {
        const sp = $(e.currentTarget).data('spacing');
        this.applyParagraphSpacing(sp);
      });

      $('#btnQuickSpacingDec').on('click', () => {
        if (this.prefs.paragraphSpacing > 6) {
          this.applyParagraphSpacing(this.prefs.paragraphSpacing - 2);
        }
      });

      $('#btnQuickSpacingInc').on('click', () => {
        if (this.prefs.paragraphSpacing < 36) {
          this.applyParagraphSpacing(this.prefs.paragraphSpacing + 2);
        }
      });

      // Font chips selection
      $(document).on('click', '#readingFontChips .font-chip-btn', (e) => {
        const font = $(e.currentTarget).data('font');
        this.applyFontFamily(font);
      });

      // Color swatches selection
      $(document).on('click', '#settingColorsPalette .color-swatch-item', (e) => {
        const col = $(e.currentTarget).data('color');
        const isDark = ($('html').attr('data-theme') === 'dark');
        if (isDark) this.prefs.textColorDark = col;
        else this.prefs.textColorLight = col;
        this.applyTextColor();
      });

      // Custom color picker
      $('#inputCustomColorReader').on('input change', (e) => {
        const col = $(e.currentTarget).val();
        const isDark = ($('html').attr('data-theme') === 'dark');
        if (isDark) this.prefs.textColorDark = col;
        else this.prefs.textColorLight = col;
        this.applyTextColor();
      });

      // Theme choices in modal
      $('.setting-theme-choice-row .theme-choice-btn').on('click', function() {
        const theme = $(this).data('theme');
        ThemeManager.setTheme(theme);
        $('.setting-theme-choice-row .theme-choice-btn').removeClass('active');
        $(this).addClass('active');
      });

      // Nút Áp Dụng (Lưu cài đặt và đóng popup)
      $('#btnApplyReadingSettings').on('click', (e) => {
        e.preventDefault();
        const $btn = $(e.currentTarget);
        this.savePreferences(true);

        const originalHtml = $btn.html();
        $btn.html('<i class="fa-solid fa-circle-check"></i> Đã Áp Dụng!').prop('disabled', true);

        setTimeout(() => {
          $btn.html(originalHtml).prop('disabled', false);
          this.closeQuickModal();
        }, 320);
      });

      // Reset Defaults
      $('#btnResetReadingPrefs').on('click', () => {
        this.prefs.fontSize = ($(window).width() <= 768) ? 19 : 21;
        this.prefs.fontFamily = 'sans-serif';
        this.prefs.textColorLight = '#262626';
        this.prefs.textColorDark = '#E5E7EB';
        this.prefs.paragraphSpacing = ($(window).width() <= 768) ? 13 : 14;
        this.applyAllPreferences();
        this.savePreferences(true);
      });
    },

    // Profile Page Tab 5 Handlers
    bindProfileSettingsEvents() {
      const $tab = $('#tabReadingSettings');
      if (!$tab.length) return;

      // Font size buttons
      $('#btnProfileFontInc').on('click', () => {
        if (this.prefs.fontSize < 32) this.applyFontSize(this.prefs.fontSize + 1);
      });
      $('#btnProfileFontDec').on('click', () => {
        if (this.prefs.fontSize > 15) this.applyFontSize(this.prefs.fontSize - 1);
      });

      // Spacing buttons in profile
      $('#btnProfileSpacingInc').on('click', () => {
        if (this.prefs.paragraphSpacing < 36) this.applyParagraphSpacing(this.prefs.paragraphSpacing + 2);
      });
      $('#btnProfileSpacingDec').on('click', () => {
        if (this.prefs.paragraphSpacing > 6) this.applyParagraphSpacing(this.prefs.paragraphSpacing - 2);
      });

      // Spacing chips in profile
      $('#profileSpacingChips .spacing-chip-btn').on('click', (e) => {
        const sp = $(e.currentTarget).data('spacing');
        this.applyParagraphSpacing(sp);
      });

      // Font chips
      $('#profileFontChips .font-chip-btn').on('click', (e) => {
        const font = $(e.currentTarget).data('font');
        this.applyFontFamily(font);
      });

      // Color swatches
      $(document).on('click', '#profileColorsPalette .color-swatch-item', (e) => {
        const col = $(e.currentTarget).data('color');
        const isDark = ($('#previewContentBox').hasClass('preview-theme-dark'));
        if (isDark) this.prefs.textColorDark = col;
        else this.prefs.textColorLight = col;
        this.applyTextColor();
      });

      // Custom color
      $('#inputProfileCustomColor').on('input change', (e) => {
        const col = $(e.currentTarget).val();
        const isDark = ($('#previewContentBox').hasClass('preview-theme-dark'));
        if (isDark) this.prefs.textColorDark = col;
        else this.prefs.textColorLight = col;
        this.applyTextColor();
      });

      // Preview theme toggle
      $('.btn-preview-theme').on('click', function() {
        $('.btn-preview-theme').removeClass('active');
        $(this).addClass('active');
        const previewTheme = $(this).data('preview-theme');
        $('#previewContentBox').removeClass('preview-theme-light preview-theme-dark')
          .addClass('preview-theme-' + previewTheme);
        ReadingManager.applyTextColor();
      });

      // Save button on profile
      $('#btnSaveProfileReadingSettings').on('click', () => {
        this.savePreferences(true);
        $('#profileReadingSettingsAlert').html(`
          <div style="background: #E8F5E9; color: #2E7D32; padding: 12px 16px; border-radius: 8px; font-size: 13.5px; display: flex; align-items: center; gap: 8px; border: 1px solid rgba(46,125,50,0.25);">
            <i class="fa-solid fa-circle-check"></i> Đã lưu cài đặt đọc truyện thành công! Các tùy chỉnh sẽ tự động áp dụng cho mọi truyện bạn đọc.
          </div>
        `).fadeIn();
        setTimeout(() => { $('#profileReadingSettingsAlert').fadeOut(); }, 4000);
      });

      // Reset button on profile
      $('#btnResetProfileReadingSettings').on('click', () => {
        this.prefs.fontSize = ($(window).width() <= 768) ? 19 : 21;
        this.prefs.fontFamily = 'sans-serif';
        this.prefs.textColorLight = '#262626';
        this.prefs.textColorDark = '#E5E7EB';
        this.prefs.paragraphSpacing = ($(window).width() <= 768) ? 13 : 14;
        this.applyAllPreferences();
        this.savePreferences(true);
        $('#profileReadingSettingsAlert').html(`
          <div style="background: #E8F5E9; color: #2E7D32; padding: 12px 16px; border-radius: 8px; font-size: 13.5px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-circle-check"></i> Đã khôi phục cài đặt đọc truyện về mặc định!
          </div>
        `).fadeIn();
      });
    },

    // Drawer danh sách chương
    bindChapterDrawerEvents() {
      const $drawerModal = $('#chapterDrawerModal');
      const $drawerOverlay = $('#chapterDrawerOverlay');
      const $drawerSearch = $('#chapterDrawerSearchInput');
      const $drawerClear = $('#chapterDrawerSearchClear');
      const $drawerItems = $('#chapterDrawerList .chapter-drawer-item');
      const $drawerNoResults = $('#chapterDrawerNoResults');

      function openDrawer() {
        $drawerOverlay.fadeIn(180);
        $drawerModal.fadeIn(180);
        $('body').css('overflow', 'hidden');

        setTimeout(() => {
          const $current = $('#chapterDrawerList .is-current-chapter');
          if ($current.length) {
            const container = $('.chapter-drawer-body')[0];
            if (container) {
              const itemTop = $current.position().top;
              const containerHalf = container.clientHeight / 2;
              container.scrollTop = container.scrollTop + itemTop - containerHalf + ($current.outerHeight() / 2);
            }
          }
        }, 80);
      }

      function closeDrawer() {
        $drawerModal.fadeOut(150);
        $drawerOverlay.fadeOut(150);
        $('body').css('overflow', '');
      }

      $(document).on('click', '.btn-chapter-drawer-toggle', (e) => {
        e.preventDefault();
        openDrawer();
      });

      $('#btnCloseChapterDrawer, #chapterDrawerOverlay').on('click', () => {
        closeDrawer();
      });

      $(document).on('keydown', (e) => {
        if (e.key === 'Escape' && $drawerModal.is(':visible')) {
          closeDrawer();
        }
      });

      // Live search
      $drawerSearch.on('input', function() {
        const q = $(this).val().toLowerCase().trim();
        if (q.length > 0) $drawerClear.show();
        else $drawerClear.hide();

        let visibleCount = 0;
        $drawerItems.each(function() {
          const title = $(this).data('title') || '';
          if (!q || title.indexOf(q) !== -1) {
            $(this).show();
            visibleCount++;
          } else {
            $(this).hide();
          }
        });

        if (visibleCount === 0) $drawerNoResults.show();
        else $drawerNoResults.hide();
      });

      $drawerClear.on('click', () => {
        $drawerSearch.val('').trigger('input').focus();
      });

      // Legacy fallback
      $('#readingChapterSelect').on('change', function() {
        const url = $(this).val();
        if (url) window.location.href = url;
      });
    },

    recordHistoryAndView() {
      const $readingWrapper = $('.chapter-reading-wrapper');
      if ($readingWrapper.length) {
        const storyId = $readingWrapper.data('story-id');
        const chapterId = $readingWrapper.data('chapter-id');
        const chapterNum = $readingWrapper.data('chapter-num');

        // Record View
        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_record_view',
            story_id: storyId,
            chapter_id: chapterId
          }
        });

        // Record History
        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_save_history',
            story_id: storyId,
            chapter_id: chapterId,
            chapter_num: chapterNum
          }
        });
      }
    },

    bindBookmarkToggle() {
      $(document).on('click', '#btnToggleBookmark', function(e) {
        e.preventDefault();
        const storyId = $(this).data('story-id');
        const $btn = $(this);

        if (!muopConfig.isLoggedIn) {
          window.location.href = muopConfig.siteUrl + '/dang-nhap/';
          return;
        }

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_toggle_bookmark',
            nonce: muopConfig.nonce,
            story_id: storyId
          },
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              if (res.data && res.data.bookmarked) {
                $btn.html('<i class="fa-solid fa-bookmark"></i> Đã Lưu Tủ Truyện');
                $btn.addClass('btn-primary').removeClass('btn-secondary');
              } else {
                $btn.html('<i class="fa-regular fa-bookmark"></i> Lưu Vào Tủ Truyện');
                $btn.removeClass('btn-primary').addClass('btn-secondary');
              }
            } else {
              alert(getAjaxMsg(res, 'Lỗi lưu truyện!'));
            }
          },
          error: (xhr) => {
            alert('Lỗi kết nối máy chủ!');
          }
        });
      });
    }
  };

  // 6. AUTHENTICATION & FORM SUBMISSION
  const AuthForms = {
    init() {
      // Login Form
      $('#formMuopLogin').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $alert = $('#loginAlert');
        const $btn = $form.find('button[type="submit"]');

        $btn.prop('disabled', true).text('Đang đăng nhập...');
        $alert.hide();

        const nonce = $form.find('input[name="nonce"]').val() || (typeof muopConfig !== 'undefined' ? muopConfig.nonce : '');
        const ajaxUrl = (typeof muopConfig !== 'undefined' && muopConfig.ajaxUrl) ? muopConfig.ajaxUrl : '/core/wp-admin/admin-ajax.php';

        $.ajax({
          url: ajaxUrl,
          type: 'POST',
          data: $form.serialize() + '&action=muop_login&nonce=' + encodeURIComponent(nonce),
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              $alert.removeClass('alert-error').addClass('alert-success').text(getAjaxMsg(res, 'Đăng nhập thành công!')).fadeIn();
              setTimeout(() => {
                window.location.href = (res.data && res.data.redirect) || (typeof muopConfig !== 'undefined' ? muopConfig.siteUrl : '/');
              }, 600);
            } else {
              $btn.prop('disabled', false).text('Đăng Nhập');
              $alert.removeClass('alert-success').addClass('alert-error').text(getAjaxMsg(res, 'Đăng nhập thất bại!')).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).text('Đăng Nhập');
            let errorMsg = 'Có lỗi xảy ra, vui lòng thử lại!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
            $alert.removeClass('alert-success').addClass('alert-error').text(errorMsg).fadeIn();
          }
        });
      });

      // Register Form
      $('#formMuopRegister').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $alert = $('#registerAlert');
        const $btn = $form.find('button[type="submit"]');

        $btn.prop('disabled', true).text('Đang đăng ký...');
        $alert.hide();

        const nonce = $form.find('input[name="nonce"]').val() || (typeof muopConfig !== 'undefined' ? muopConfig.nonce : '');
        const ajaxUrl = (typeof muopConfig !== 'undefined' && muopConfig.ajaxUrl) ? muopConfig.ajaxUrl : '/core/wp-admin/admin-ajax.php';

        $.ajax({
          url: ajaxUrl,
          type: 'POST',
          data: $form.serialize() + '&action=muop_register&nonce=' + encodeURIComponent(nonce),
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              $alert.removeClass('alert-error').addClass('alert-success').text(getAjaxMsg(res, 'Đăng ký thành công!')).fadeIn();
              setTimeout(() => {
                window.location.href = (res.data && res.data.redirect) || (typeof muopConfig !== 'undefined' ? muopConfig.siteUrl : '/');
              }, 800);
            } else {
              $btn.prop('disabled', false).text('Đăng Ký Độc Giả');
              $alert.removeClass('alert-success').addClass('alert-error').text(getAjaxMsg(res, 'Đăng ký thất bại!')).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).text('Đăng Ký Độc Giả');
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
            $alert.removeClass('alert-success').addClass('alert-error').text(errorMsg).fadeIn();
          }
        });
      });
    }
  };

  // 7. STORY & CHAPTER SUBMISSION (TRANSLATORS / ADMIN)
  const CreatorForms = {
    init() {
      // 1. Tải ảnh bìa từ thiết bị
      $('#btnUploadCover').on('click', function(e) {
        e.preventDefault();
        $('#storyCover').trigger('click');
      });

      $('#storyCover').on('change', function() {
        const file = this.files && this.files[0];
        if (file) {
          $('#coverFileName').text(file.name);
          $('#coverUploadInfo').css('display', 'inline-flex');
          $('#btnUploadCover').html('<i class="fa-solid fa-arrow-rotate-right"></i> Đổi ảnh khác');
        } else {
          $('#coverFileName').text('');
          $('#coverUploadInfo').hide();
          $('#btnUploadCover').html('<i class="fa-solid fa-arrow-up-from-bracket"></i> Tải ảnh từ thiết bị');
        }
      });

      $('#btnRemoveCover').on('click', function(e) {
        e.preventDefault();
        $('#storyCover').val('');
        $('#coverFileName').text('');
        $('#coverUploadInfo').hide();
        $('#btnUploadCover').html('<i class="fa-solid fa-arrow-up-from-bracket"></i> Tải ảnh từ thiết bị');
      });

      // 2. Tìm kiếm thể loại trong trang đăng truyện
      $('#inputSearchGenre').on('input', function() {
        const query = $(this).val().toLowerCase().trim();
        let matchedCount = 0;
        $('#genresCheckboxList .genre-checkbox-item').each(function() {
          const name = ($(this).data('name') || $(this).text()).toLowerCase();
          if (!query || name.indexOf(query) !== -1) {
            $(this).show();
            matchedCount++;
          } else {
            $(this).hide();
          }
        });
        if (matchedCount === 0) {
          $('#noGenreFoundMsg').show();
        } else {
          $('#noGenreFoundMsg').hide();
        }
      });

      // 3. Toggle Box Add New Genre
      $('#btnToggleAddNewGenre').on('click', function(e) {
        e.preventDefault();
        const $box = $('#boxAddNewGenre');
        $box.slideToggle(180, function() {
          if ($box.is(':visible')) {
            $('#inputNewGenreName').focus();
          }
        });
      });

      $('#btnCancelAddGenre').on('click', function(e) {
        e.preventDefault();
        $('#boxAddNewGenre').slideUp(180);
        $('#inputNewGenreName').val('');
        $('#addGenreStatus').hide();
      });

      // Confirm Add New Genre (Lưu vào taxonomy the_loai ngay lập tức)
      const handleCreateGenre = () => {
        const $input = $('#inputNewGenreName');
        const genreName = $.trim($input.val());
        const $status = $('#addGenreStatus');
        const $btn = $('#btnConfirmAddGenre');

        if (!genreName) {
          $status.css('color', '#ef4444').text('Vui lòng nhập tên thể loại!').fadeIn();
          $input.focus();
          return;
        }

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...');
        $status.hide();

        const nonce = (typeof muopConfig !== 'undefined' ? muopConfig.nonce : '');
        const ajaxUrl = (typeof muopConfig !== 'undefined' && muopConfig.ajaxUrl) ? muopConfig.ajaxUrl : '/core/wp-admin/admin-ajax.php';

        $.ajax({
          url: ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_create_genre',
            nonce: nonce,
            name: genreName
          },
          success: (res) => {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Lưu Thể Loại');
            if (typeof res === 'string') {
              const clean = res.replace(/<br\s*\/?>\s*<b>(?:Notice|Warning|Deprecated)<\/b>:.*?<br\s*\/?>/gi, '').trim();
              try { res = JSON.parse(clean); } catch (e) {}
            }
            if (res && res.success) {
              const data = res.data;
              const termId = data.term_id;
              const termName = data.name;

              // Kiểm tra xem đã có checkbox trong list chưa
              let $existing = $(`#genresCheckboxList input[value="${termId}"]`);
              if ($existing.length) {
                $existing.prop('checked', true);
                $existing.closest('.genre-checkbox-item').show();
                $status.css('color', '#3F7523').text(`Thể loại "${termName}" đã có sẵn và đã được chọn!`).fadeIn();
              } else {
                const escapedName = $('<div>').text(termName).html();
                const newHtml = `
                  <label class="genre-checkbox-item" data-name="${escapedName.toLowerCase()}" style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer; color: var(--primary-green); font-weight: 600;">
                    <input type="checkbox" name="categories[]" value="${termId}" checked />
                    <span class="genre-checkbox-name">${escapedName} (Mới)</span>
                  </label>
                `;
                $('#genresCheckboxList').prepend(newHtml);
                $('#noGenreFoundMsg').hide();
                $status.css('color', '#3F7523').text(`Đã thêm thể loại "${termName}" thành công!`).fadeIn();
              }
              $input.val('');
              setTimeout(() => {
                $('#boxAddNewGenre').slideUp(180);
                $status.hide();
              }, 1200);
            } else {
              $status.css('color', '#ef4444').text(getAjaxMsg(res, 'Lỗi thêm thể loại!')).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Lưu Thể Loại');
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            $status.css('color', '#ef4444').text(errorMsg).fadeIn();
          }
        });
      };

      $('#btnConfirmAddGenre').on('click', function(e) {
        e.preventDefault();
        handleCreateGenre();
      });

      $('#inputNewGenreName').on('keydown', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          handleCreateGenre();
        }
      });

      // Submit Story & Save Draft
      $('#btnSaveDraftNewStory').on('click', function(e) {
        e.preventDefault();
        $('#isStoryDraftInput').val('1');
        $('#formSubmitStory').trigger('submit');
      });

      $('#btnSubmitPublishStory').on('click', function() {
        $('#isStoryDraftInput').val('0');
      });

      $('#formSubmitStory').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $alert = $('#storyAlert');
        const isDraft = $('#isStoryDraftInput').val() === '1';
        const $btn = isDraft ? $('#btnSaveDraftNewStory') : $('#btnSubmitPublishStory');

        $btn.prop('disabled', true).text(isDraft ? 'Đang lưu bản nháp...' : 'Đang gửi truyện...');
        $alert.hide();

        const nonce = $form.find('input[name="nonce"]').val() || (typeof muopConfig !== 'undefined' ? muopConfig.nonce : '');
        const ajaxUrl = (typeof muopConfig !== 'undefined' && muopConfig.ajaxUrl) ? muopConfig.ajaxUrl : '/core/wp-admin/admin-ajax.php';

        const formData = new FormData(this);
        if (!formData.has('action')) formData.append('action', 'muop_submit_story');
        if (!formData.has('nonce') || !formData.get('nonce')) formData.append('nonce', nonce);

        $.ajax({
          url: ajaxUrl,
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: (res) => {
            if (typeof res === 'string') {
              const clean = res.replace(/<br\s*\/?>\s*<b>(?:Notice|Warning|Deprecated)<\/b>:.*?<br\s*\/?>/gi, '').trim();
              try { res = JSON.parse(clean); } catch (e) {}
            }
            if (res && res.success) {
              const successMsg = getAjaxMsg(res, isDraft ? 'Đã lưu bản nháp thành công!' : 'Đăng truyện thành công!');
              $alert.removeClass('alert-error').addClass('alert-success').text(successMsg).fadeIn();
              setTimeout(() => {
                window.location.href = (res.data && res.data.redirect) || (typeof muopConfig !== 'undefined' ? muopConfig.siteUrl + '/thong-tin-dich-gia/' : '/thong-tin-dich-gia/');
              }, 1200);
            } else {
              $btn.prop('disabled', false).text(isDraft ? 'Lưu Nháp' : 'Đăng Truyện');
              const errorMsg = getAjaxMsg(res, 'Lỗi đăng truyện!');
              $alert.removeClass('alert-success').addClass('alert-error').text(errorMsg).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).text(isDraft ? 'Lưu Nháp' : 'Đăng Truyện');
            let errorMsg = 'Lỗi upload hoặc hệ thống!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
            $alert.removeClass('alert-success').addClass('alert-error').text(errorMsg).fadeIn();
          }
        });
      });

      // Submit Chapter & Save Draft
      $('#btnSaveDraftNewChapter').on('click', function(e) {
        e.preventDefault();
        $('#isChapterDraftInput').val('1');
        $('#formSubmitChapter').trigger('submit');
      });

      $('#btnSubmitPublishChapter').on('click', function() {
        $('#isChapterDraftInput').val('0');
      });

      $('#formSubmitChapter').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $alert = $('#chapterAlert');
        const isDraft = $('#isChapterDraftInput').val() === '1';
        const $btn = isDraft ? $('#btnSaveDraftNewChapter') : $('#btnSubmitPublishChapter');

        $btn.prop('disabled', true).text(isDraft ? 'Đang lưu bản nháp...' : 'Đang thêm chương...');
        $alert.hide();

        const nonce = $form.find('input[name="nonce"]').val() || (typeof muopConfig !== 'undefined' ? muopConfig.nonce : '');
        const ajaxUrl = (typeof muopConfig !== 'undefined' && muopConfig.ajaxUrl) ? muopConfig.ajaxUrl : '/core/wp-admin/admin-ajax.php';

        let postData = $form.serialize();
        if (postData.indexOf('action=') === -1) postData += '&action=muop_submit_chapter';
        if (postData.indexOf('nonce=') === -1 && nonce) postData += '&nonce=' + encodeURIComponent(nonce);

        $.ajax({
          url: ajaxUrl,
          type: 'POST',
          data: postData,
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              const successMsg = getAjaxMsg(res, isDraft ? 'Đã lưu bản nháp chương thành công!' : 'Thêm chương mới thành công!');
              $alert.removeClass('alert-error').addClass('alert-success').text(successMsg).fadeIn();
              setTimeout(() => {
                window.location.href = (res.data && res.data.redirect) || (typeof muopConfig !== 'undefined' ? muopConfig.siteUrl : '/');
              }, 1000);
            } else {
              $btn.prop('disabled', false).text(isDraft ? 'Lưu Nháp' : 'Thêm Chương Này');
              const errorMsg = getAjaxMsg(res, 'Lỗi thêm chương!');
              $alert.removeClass('alert-success').addClass('alert-error').text(errorMsg).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).text(isDraft ? 'Lưu Nháp' : 'Thêm Chương Này');
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
            $alert.removeClass('alert-success').addClass('alert-error').text(errorMsg).fadeIn();
          }
        });
      });
    }
  };

  // 8. COMMENTS
  const CommentManager = {
    init() {
      $('#formPostComment').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $btn = $form.find('button[type="submit"]');
        const $textarea = $form.find('textarea[name="comment"]');

        if (!muopConfig.isLoggedIn) {
          window.location.href = muopConfig.siteUrl + '/dang-nhap/';
          return;
        }

        $btn.prop('disabled', true);

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: $form.serialize() + '&action=muop_post_comment&nonce=' + muopConfig.nonce,
          success: (res) => {
            $btn.prop('disabled', false);
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              const d = res.data || {};
              const author = d.author || 'Bạn';
              const html = `
                <li class="comment-item">
                  <div class="comment-avatar">${author.charAt(0).toUpperCase()}</div>
                  <div class="comment-body">
                    <span class="comment-author-name">${author}</span>
                    <span class="comment-time">${d.time || 'Vừa xong'}</span>
                    <div class="comment-text">${d.content || ''}</div>
                  </div>
                </li>
              `;
              $('.comment-list-ul').prepend(html);
              $textarea.val('');
            } else {
              alert(getAjaxMsg(res, 'Lỗi gửi bình luận!'));
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false);
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
            alert(errorMsg);
          }
        });
      });
    }
  };

  // 9. ADMIN ACTIONS & MODE SWITCHER
  const AdminManager = {
    init() {
      // Mode Switcher
      $('#adminModeSelect').on('change', function() {
        const mode = $(this).val();
        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_switch_preview_mode',
            nonce: muopConfig.nonce,
            mode: mode
          },
          success: () => {
            window.location.reload();
          }
        });
      });

      // Quick Admin Actions (Approve, Reject, Delete, Nominate)
      $(document).on('click', '.btn-admin-action', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const subAction = $btn.data('action');
        const storyId = $btn.data('story-id');
        const userId = $btn.data('user-id');
        const nominateType = $btn.data('nominate-type');

        if (subAction === 'delete_story' && !confirm('Bạn có chắc chắn muốn xóa truyện này?')) {
          return;
        }

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_admin_action',
            nonce: muopConfig.nonce,
            sub_action: subAction,
            story_id: storyId,
            user_id: userId,
            nominate_type: nominateType
          },
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              alert(getAjaxMsg(res, 'Thao tác thành công!'));
              window.location.reload();
            } else {
              alert(getAjaxMsg(res, 'Lỗi thao tác!'));
            }
          },
          error: (xhr) => {
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
            alert(errorMsg);
          }
        });
      });

      // Delete Story (Dịch giả & Admin)
      $(document).on('click', '.btn-delete-story', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const storyId = $btn.data('story-id');

        if (!confirm('Bạn có chắc chắn muốn xóa bộ truyện này không? Toàn bộ chương của truyện cũng sẽ bị xóa vĩnh viễn!')) {
          return;
        }

        $btn.prop('disabled', true);

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_delete_story',
            nonce: muopConfig.nonce,
            story_id: storyId
          },
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              alert(getAjaxMsg(res, 'Đã xóa bộ truyện thành công!'));
              $btn.closest('tr').fadeOut(300, function() {
                $(this).remove();
              });
            } else {
              alert(getAjaxMsg(res, 'Lỗi xóa truyện!'));
              $btn.prop('disabled', false);
            }
          },
          error: (xhr) => {
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
            alert(errorMsg);
            $btn.prop('disabled', false);
          }
        });
      });

      // Update Affiliate Links Form
      $('#formUpdateAffiliateLinks').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $alert = $('#affiliateAlert');

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: $form.serialize() + '&action=muop_admin_action&sub_action=update_links&nonce=' + muopConfig.nonce,
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              $alert.removeClass('alert-error').addClass('alert-success').text(getAjaxMsg(res, 'Cập nhật liên kết thành công!')).fadeIn().delay(2000).fadeOut();
            } else {
              $alert.removeClass('alert-success').addClass('alert-error').text(getAjaxMsg(res, 'Lỗi cập nhật liên kết!')).fadeIn();
            }
          },
          error: (xhr) => {
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
            $alert.removeClass('alert-success').addClass('alert-error').text(errorMsg).fadeIn();
          }
        });
      });

      // === QUẢN LÝ THỂ LOẠI (ADMIN GENRE MANAGEMENT) ===
      // 1. Tìm kiếm thể loại realtime
      $('#admSearchGenre').on('input', function() {
        const q = $(this).val().toLowerCase().trim();
        let matched = 0;
        $('#admGenresGrid .adm-genre-card').each(function() {
          const name = ($(this).data('name') || $(this).find('.adm-genre-title').text()).toLowerCase();
          if (!q || name.indexOf(q) !== -1) {
            $(this).show();
            matched++;
          } else {
            $(this).hide();
          }
        });
        if (matched === 0) {
          $('#admNoGenreFound').show();
        } else {
          $('#admNoGenreFound').hide();
        }
      });

      // 2. Toggle Box Thêm Thể Loại
      $('#admBtnToggleAddGenre').on('click', function(e) {
        e.preventDefault();
        const $box = $('#admBoxAddGenre');
        $box.slideToggle(180, function() {
          if ($box.is(':visible')) {
            $('#admInputNewGenre').focus();
          }
        });
      });

      $('#admBtnCancelAddGenre').on('click', function(e) {
        e.preventDefault();
        $('#admBoxAddGenre').slideUp(180);
        $('#admInputNewGenre').val('');
        $('#admAddGenreStatus').hide();
      });

      // 3. Admin Thêm Thể Loại Mới
      const handleAdminAddGenre = () => {
        const $input = $('#admInputNewGenre');
        const name = $.trim($input.val());
        const $btn = $('#admBtnConfirmAddGenre');
        const $status = $('#admAddGenreStatus');

        if (!name) {
          $status.css('color', '#ef4444').text('Vui lòng nhập tên thể loại!').fadeIn();
          $input.focus();
          return;
        }

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...');
        $status.hide();

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_create_genre',
            nonce: muopConfig.nonce,
            name: name
          },
          success: (res) => {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Lưu Thể Loại');
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              const data = res.data;
              const $existing = $(`#admGenresGrid .adm-genre-card[data-id="${data.term_id}"]`);
              if ($existing.length) {
                $status.css('color', '#3F7523').text('Thể loại này đã có sẵn trong danh sách!').fadeIn();
                $existing.show();
              } else {
                const safeName = $('<div>').text(data.name).html();
                const cardHtml = `
                  <div class="adm-genre-card" data-id="${data.term_id}" data-name="${safeName.toLowerCase()}" style="background: var(--bg-card); padding: 14px; border-radius: var(--radius-md); border: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; gap: 10px; box-shadow: var(--shadow-sm); animation: admModalFadeIn 0.3s ease;">
                      <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                          <span class="adm-genre-title" style="font-weight: 700; color: var(--text-main); font-size: 14px; word-break: break-word;">${safeName}</span>
                          <span class="badge badge-green" style="font-size: 11px; white-space: nowrap;">0 truyện</span>
                      </div>
                      <div style="display: flex; align-items: center; justify-content: flex-end; gap: 6px; padding-top: 8px; border-top: 1px dashed var(--border-light);">
                          <button type="button" class="btn btn-outline btn-xs adm-btn-edit-genre" data-id="${data.term_id}" data-name="${safeName}" title="Sửa tên thể loại" style="padding: 4px 10px; font-size: 12px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                              <i class="fa-solid fa-pen-to-square"></i> Sửa
                          </button>
                          <button type="button" class="btn btn-outline btn-xs adm-btn-delete-genre" data-id="${data.term_id}" data-name="${safeName}" title="Xóa thể loại này" style="padding: 4px 10px; font-size: 12px; border-radius: 4px; color: #ef4444; border-color: #fca5a5; display: inline-flex; align-items: center; gap: 4px;">
                              <i class="fa-solid fa-trash-can"></i> Xóa
                          </button>
                      </div>
                  </div>
                `;
                $('#admGenresGrid').prepend(cardHtml);
                $('#admNoGenreFound').hide();
                const curCount = parseInt($('#admGenreTotalCount').text(), 10) || 0;
                $('#admGenreTotalCount').text(curCount + 1);
                $status.css('color', '#3F7523').text('Đã thêm thể loại mới thành công!').fadeIn();
                $input.val('');
                setTimeout(() => {
                  $('#admBoxAddGenre').slideUp(180);
                  $status.hide();
                }, 1000);
              }
            } else {
              $status.css('color', '#ef4444').text(getAjaxMsg(res, 'Lỗi thêm thể loại!')).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Lưu Thể Loại');
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            $status.css('color', '#ef4444').text(errorMsg).fadeIn();
          }
        });
      };

      $('#admBtnConfirmAddGenre').on('click', function(e) {
        e.preventDefault();
        handleAdminAddGenre();
      });

      $('#admInputNewGenre').on('keydown', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          handleAdminAddGenre();
        }
      });

      // 4. Mở Modal Chỉnh Sửa Thể Loại
      $(document).on('click', '.adm-btn-edit-genre', function(e) {
        e.preventDefault();
        const termId = $(this).data('id');
        const termName = $(this).attr('data-name') || $(this).data('name') || '';
        $('#admEditGenreId').val(termId);
        $('#admEditGenreName').val(termName);
        $('#admEditGenreStatus').hide();
        $('#admModalEditGenre').css('display', 'flex');
        setTimeout(() => $('#admEditGenreName').focus().select(), 50);
      });

      $('#admBtnCloseEditModal, #admBtnCancelEditModal').on('click', function(e) {
        e.preventDefault();
        $('#admModalEditGenre').hide();
      });

      $('#admModalEditGenre').on('click', function(e) {
        if (e.target === this) {
          $(this).hide();
        }
      });

      // 5. Lưu Chỉnh Sửa Thể Loại
      const handleAdminSaveEditGenre = () => {
        const termId = $('#admEditGenreId').val();
        const newName = $.trim($('#admEditGenreName').val());
        const $status = $('#admEditGenreStatus');
        const $btn = $('#admBtnSaveEditGenre');

        if (!newName) {
          $status.css('color', '#ef4444').text('Vui lòng nhập tên thể loại!').fadeIn();
          return;
        }

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...');
        $status.hide();

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_update_genre',
            nonce: muopConfig.nonce,
            term_id: termId,
            name: newName
          },
          success: (res) => {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Lưu Thay Đổi');
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              const data = res.data;
              const $card = $(`#admGenresGrid .adm-genre-card[data-id="${termId}"]`);
              $card.attr('data-name', data.name.toLowerCase());
              $card.data('name', data.name.toLowerCase());
              $card.find('.adm-genre-title').text(data.name);
              $card.find('.adm-btn-edit-genre').attr('data-name', data.name).data('name', data.name);
              $card.find('.adm-btn-delete-genre').attr('data-name', data.name).data('name', data.name);
              $status.css('color', '#3F7523').text('Đã cập nhật thể loại thành công!').fadeIn();
              setTimeout(() => {
                $('#admModalEditGenre').hide();
              }, 800);
            } else {
              $status.css('color', '#ef4444').text(getAjaxMsg(res, 'Lỗi cập nhật thể loại!')).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Lưu Thay Đổi');
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            $status.css('color', '#ef4444').text(errorMsg).fadeIn();
          }
        });
      };

      $('#admBtnSaveEditGenre').on('click', function(e) {
        e.preventDefault();
        handleAdminSaveEditGenre();
      });

      $('#admEditGenreName').on('keydown', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          handleAdminSaveEditGenre();
        }
      });

      // 6. Xóa Thể Loại
      $(document).on('click', '.adm-btn-delete-genre', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const termId = $btn.data('id');
        const termName = $btn.attr('data-name') || $btn.data('name') || 'này';

        if (!confirm(`Bạn có chắc chắn muốn xóa thể loại "${termName}" khỏi hệ thống không? Các truyện thuộc thể loại này sẽ không còn thể loại này nữa.`)) {
          return;
        }

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_delete_genre',
            nonce: muopConfig.nonce,
            term_id: termId
          },
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              const $card = $btn.closest('.adm-genre-card');
              $card.fadeOut(250, function() {
                $(this).remove();
                const curCount = parseInt($('#admGenreTotalCount').text(), 10) || 1;
                $('#admGenreTotalCount').text(Math.max(0, curCount - 1));
              });
            } else {
              alert(getAjaxMsg(res, 'Lỗi khi xóa thể loại!'));
              $btn.prop('disabled', false).html('<i class="fa-solid fa-trash-can"></i> Xóa');
            }
          },
          error: (xhr) => {
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            alert(errorMsg);
            $btn.prop('disabled', false).html('<i class="fa-solid fa-trash-can"></i> Xóa');
          }
        });
      });

      // Dashboard Tabs
      $('.dash-tab-btn').on('click', function() {
        const target = $(this).data('tab');
        $('.dash-tab-btn').removeClass('active');
        $(this).addClass('active');
        $('.dash-tab-pane').removeClass('active');
        $('#' + target).addClass('active');
      });
    }
  };

  // 10. DROPDOWN MENUS (USER ACCOUNT & THỂ LOẠI CLICK TOGGLES)
  const DropdownManager = {
    init() {
      // Toggle User Account Dropdown on click
      $(document).on('click', '#userProfileToggle, .user-profile-badge', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const $wrapper = $(this).closest('.user-menu-wrapper');
        const $panel = $wrapper.find('.user-dropdown-panel');
        
        // Close notification dropdown if open
        $('#notifDropdownPanel').removeClass('show');
        $('#notifToggleBtn').attr('aria-expanded', 'false');

        $wrapper.toggleClass('active');
        $panel.toggleClass('show');

        // Close genre dropdown if open
        $('.genre-dropdown-menu').removeClass('show');
        $('.has-genre-dropdown').removeClass('open');
      });

      // Prevent closing when clicking inside user panel, allowing direct navigation
      $(document).on('click', '.user-dropdown-panel', function(e) {
        e.stopPropagation();
      });

      // Toggle Thể loại dropdown on click
      $(document).on('click', '#navGenreToggle, .nav-genre-btn', function(e) {
        const $li = $(this).closest('.has-genre-dropdown');
        const $menu = $li.find('.genre-dropdown-menu');

        e.preventDefault();
        e.stopPropagation();
        $li.toggleClass('open');
        $menu.toggleClass('show');

        // Close user dropdown if open
        $('.user-menu-wrapper').removeClass('active');
        $('.user-dropdown-panel').removeClass('show');
      });

      $(document).on('click', '.genre-dropdown-menu', function(e) {
        e.stopPropagation();
      });

      // Close all dropdowns when clicking anywhere outside
      $(document).on('click', function(e) {
        if (!$(e.target).closest('.user-menu-wrapper').length) {
          $('.user-menu-wrapper').removeClass('active');
          $('.user-dropdown-panel').removeClass('show');
        }
        if (!$(e.target).closest('.has-genre-dropdown').length) {
          $('.has-genre-dropdown').removeClass('open');
          $('.genre-dropdown-menu').removeClass('show');
        }
      });

      // Escape key to close open dropdowns
      $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
          $('.user-menu-wrapper').removeClass('active');
          $('.user-dropdown-panel').removeClass('show');
          $('.has-genre-dropdown').removeClass('open');
          $('.genre-dropdown-menu').removeClass('show');
        }
      });
    }
  };

  // 11. MOBILE DRAWER & SEARCH TOGGLE
  const MobileNavManager = {
    init() {
      // Open Mobile Menu Drawer
      $(document).on('click', '#mobileMenuToggle', function(e) {
        e.preventDefault();
        $('#mobileNavDrawer').addClass('open');
        $('#mobileDrawerOverlay').addClass('active');
        $('body').css('overflow', 'hidden');
      });

      // Close Mobile Menu Drawer
      $(document).on('click', '#drawerCloseBtn, #mobileDrawerOverlay', function(e) {
        e.preventDefault();
        MobileNavManager.closeDrawer();
      });

      // Toggle Mobile Genre Accordion Submenu
      $(document).on('click', '#drawerGenreToggle', function(e) {
        e.preventDefault();
        $('#drawerGenreItem').toggleClass('open');
      });

      // Mobile Search Dropdown
      $(document).on('click', '#mobileSearchToggle', function(e) {
        e.preventDefault();
        const $dropdown = $('#mobileSearchBar');
        $(this).toggleClass('active');
        $dropdown.toggleClass('show');
        if ($dropdown.hasClass('show')) {
          $dropdown.find('input[type="search"]').focus();
        }
      });

      // Mobile Admin Mode Select Sync
      $(document).on('change', '#mobileDrawerAdminModeSelect', function() {
        const selectedMode = $(this).val();
        document.cookie = 'muop_preview_mode=' + encodeURIComponent(selectedMode) + '; path=/; max-age=' + (86400 * 30);
        window.location.reload();
      });

      // Close drawer on Escape key
      $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#mobileNavDrawer').hasClass('open')) {
          MobileNavManager.closeDrawer();
        }
      });
    },

    closeDrawer() {
      $('#mobileNavDrawer').removeClass('open');
      $('#mobileDrawerOverlay').removeClass('active');
      $('body').css('overflow', '');
    }
  };

  // 12. USER PROFILE & PASSWORD SETTINGS
  const ProfileManager = {
    init() {
      if (!$('#profileUpdateForm').length && !$('#passwordChangeForm').length) {
        return;
      }

      this.initTabs();
      this.initAvatarPicker();
      this.initProfileForm();
      this.initPasswordForm();
      this.initPasswordToggles();
    },

    // Handle hash and tab switching
    initTabs() {
      // Redirect legacy hashes to dedicated standalone pages
      if (window.location.pathname.indexOf('/ho-so') !== -1) {
        if (window.location.hash === '#tabPassword') {
          window.location.replace('/doi-mat-khau/');
          return;
        }
        if (window.location.hash === '#tabReadingSettings') {
          window.location.replace('/cai-dat-doc-truyen/');
          return;
        }
        if (window.location.hash === '#tabBookmarks') {
          window.location.replace('/tu-truyen/');
          return;
        }
      }

      const switchTab = (tabId) => {
        if (!tabId) return;
        const cleanId = tabId.replace(/^#/, '');
        const $btn = $(`.dash-tab-btn[data-tab="${cleanId}"]`);
        const $pane = $(`#${cleanId}`);
        if ($btn.length && $pane.length) {
          $('.dash-tab-btn').removeClass('active');
          $('.dash-tab-pane').removeClass('active');
          $btn.addClass('active');
          $pane.addClass('active');
        }
      };

      // Check URL hash on load
      if (window.location.hash) {
        switchTab(window.location.hash);
      }

      // Hash change listener
      $(window).on('hashchange', () => {
        if (window.location.hash) {
          switchTab(window.location.hash);
        }
      });

      // Tab button clicks: only for elements with data-tab
      $('.dash-tab-btn[data-tab]').on('click', function() {
        const tabId = $(this).data('tab');
        if (!tabId) return;
        switchTab(tabId);
        if (history.replaceState) {
          history.replaceState(null, null, '#' + tabId);
        } else {
          window.location.hash = '#' + tabId;
        }
      });
    },

    // Avatar selection: file upload preview, url input, and preset buttons
    initAvatarPicker() {
      const $fileInput = $('#avatarFileInput');
      const $btnChoose = $('#btnChooseAvatarFile');
      const $fileNameDisplay = $('#avatarFileNameDisplay');
      const $urlInput = $('#avatarUrlInput');
      const $previewImg = $('#avatarPreviewImg');
      const $previewChar = $('#avatarPreviewChar');
      const $presetBtns = $('.preset-avatar-btn');

      // Click trigger
      $btnChoose.on('click', (e) => {
        e.preventDefault();
        $fileInput.click();
      });

      // File input change
      $fileInput.on('change', function() {
        if (this.files && this.files[0]) {
          const file = this.files[0];
          $fileNameDisplay.text(file.name);
          $presetBtns.removeClass('active');

          const reader = new FileReader();
          reader.onload = (e) => {
            $previewImg.attr('src', e.target.result).show();
            $previewChar.hide();
          };
          reader.readAsDataURL(file);
        }
      });

      // URL input change/input
      $urlInput.on('input change', function() {
        const url = $(this).val().trim();
        if (url) {
          $fileInput.val('');
          $fileNameDisplay.text('Chưa chọn tệp (Hỗ trợ JPG, PNG, WEBP)');
          $previewImg.attr('src', url).show();
          $previewChar.hide();

          // Sync preset buttons active state
          $presetBtns.each(function() {
            if ($(this).data('url') === url) {
              $(this).addClass('active');
            } else {
              $(this).removeClass('active');
            }
          });
        }
      });

      // Preset avatar buttons
      $presetBtns.on('click', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        $presetBtns.removeClass('active');
        $(this).addClass('active');

        $urlInput.val(url);
        $fileInput.val('');
        $fileNameDisplay.text('Chưa chọn tệp (Hỗ trợ JPG, PNG, WEBP)');

        $previewImg.attr('src', url).show();
        $previewChar.hide();
      });
    },

    // Password eye toggle for .btn-toggle-pwd
    initPasswordToggles() {
      $(document).on('click', '.btn-toggle-pwd', function(e) {
        e.preventDefault();
        const targetId = $(this).data('target');
        const $input = $('#' + targetId);
        const $icon = $(this).find('i');

        if ($input.attr('type') === 'password') {
          $input.attr('type', 'text');
          $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
          $input.attr('type', 'password');
          $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
      });
    },

    // Submit profile update form
    initProfileForm() {
      const $form = $('#profileUpdateForm');
      const $alertContainer = $('#profileAlertContainer');
      const $btnSubmit = $('#btnSubmitProfile');
      const $spinner = $('#profileSavingSpinner');

      $form.on('submit', function(e) {
        e.preventDefault();

        $alertContainer.empty();
        $btnSubmit.prop('disabled', true);
        $spinner.show();

        const formData = new FormData(this);

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: (res) => {
            $btnSubmit.prop('disabled', false);
            $spinner.hide();

            if (typeof res === 'string') {
              const clean = res.replace(/<br\s*\/?>\s*<b>(?:Notice|Warning|Deprecated)<\/b>:.*?<br\s*\/?>/gi, '').trim();
              try { res = JSON.parse(clean); } catch (e) {}
            }

            if (res && res.success) {
              $alertContainer.html(`
                <div class="alert-box alert-success" style="display:block; margin-bottom: 20px;">
                  <i class="fa-solid fa-circle-check"></i> ${getAjaxMsg(res, 'Cập nhật thông tin thành công!')}
                </div>
              `);

              // Update profile header elements
              if (res.data) {
                if (res.data.display_name) {
                  $('#profileHeaderDisplayName').text(res.data.display_name);
                  $('.user-name-text').text(res.data.display_name);
                  $('.user-dropdown-name').text(res.data.display_name);
                }
                if (res.data.user_email) {
                  $('#profileHeaderEmail').text(res.data.user_email);
                }
                if (typeof res.data.bio !== 'undefined') {
                  let $bio = $('#profileHeaderBio');
                  if (res.data.bio) {
                    if (!$bio.length) {
                      $('.profile-header-info').append(`<p class="profile-bio-text" id="profileHeaderBio">"${res.data.bio}"</p>`);
                    } else {
                      $bio.text(`"${res.data.bio}"`).show();
                    }
                  } else if ($bio.length) {
                    $bio.hide();
                  }
                }
                if (res.data.avatar_url) {
                  $('#profileHeaderAvatarDisplay').html(`<img src="${res.data.avatar_url}" alt="${res.data.display_name || ''}" class="profile-avatar-img" />`);
                  // Update topbar mini avatar
                  $('.user-avatar-mini').html(`<img src="${res.data.avatar_url}" alt="${res.data.display_name || ''}" class="user-avatar-mini-img" />`);
                }
              }

              // Scroll smoothly to top of card
              if ($('.profile-header-card').length) {
                $('html, body').animate({
                  scrollTop: $('.profile-header-card').offset().top - 80
                }, 300);
              }
            } else {
              $alertContainer.html(`
                <div class="alert-box alert-error" style="display:block; margin-bottom: 20px;">
                  <i class="fa-solid fa-circle-exclamation"></i> ${getAjaxMsg(res, 'Lỗi cập nhật thông tin!')}
                </div>
              `);
            }
          },
          error: (xhr) => {
            $btnSubmit.prop('disabled', false);
            $spinner.hide();
            let errorMsg = 'Lỗi kết nối máy chủ hoặc tệp ảnh quá lớn. Vui lòng thử lại!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
            $alertContainer.html(`
              <div class="alert-box alert-error" style="display:block; margin-bottom: 20px;">
                <i class="fa-solid fa-circle-exclamation"></i> ${errorMsg}
              </div>
            `);
          }
        });
      });
    },

    // Submit change password form
    initPasswordForm() {
      const $form = $('#passwordChangeForm');
      const $alertContainer = $('#passwordAlertContainer');
      const $btnSubmit = $('#btnSubmitPassword');
      const $spinner = $('#passwordSavingSpinner');

      $form.on('submit', function(e) {
        e.preventDefault();

        const oldPass = $('#oldPasswordInput').val().trim();
        const newPass = $('#newPasswordInput').val().trim();
        const confirmPass = $('#confirmPasswordInput').val().trim();

        $alertContainer.empty();

        if (newPass.length < 6) {
          $alertContainer.html(`
            <div class="alert-box alert-error" style="display:block; margin-bottom: 20px;">
              <i class="fa-solid fa-circle-exclamation"></i> Mật khẩu mới phải dài tối thiểu 6 ký tự!
            </div>
          `);
          return;
        }

        if (newPass !== confirmPass) {
          $alertContainer.html(`
            <div class="alert-box alert-error" style="display:block; margin-bottom: 20px;">
              <i class="fa-solid fa-circle-exclamation"></i> Mật khẩu mới và xác nhận mật khẩu không khớp!
            </div>
          `);
          return;
        }

        $btnSubmit.prop('disabled', true);
        $spinner.show();

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: $form.serialize(),
          success: (res) => {
            $btnSubmit.prop('disabled', false);
            $spinner.hide();

            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }

            if (res && res.success) {
              $alertContainer.html(`
                <div class="alert-box alert-success" style="display:block; margin-bottom: 20px;">
                  <i class="fa-solid fa-circle-check"></i> ${getAjaxMsg(res, 'Đổi mật khẩu thành công!')}
                </div>
              `);
              $form[0].reset();
            } else {
              $alertContainer.html(`
                <div class="alert-box alert-error" style="display:block; margin-bottom: 20px;">
                  <i class="fa-solid fa-circle-exclamation"></i> ${getAjaxMsg(res, 'Lỗi đổi mật khẩu!')}
                </div>
              `);
            }
          },
          error: (xhr) => {
            $btnSubmit.prop('disabled', false);
            $spinner.hide();
            let errorMsg = 'Lỗi kết nối máy chủ! Vui lòng thử lại sau.';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
            $alertContainer.html(`
              <div class="alert-box alert-error" style="display:block; margin-bottom: 20px;">
                <i class="fa-solid fa-circle-exclamation"></i> ${errorMsg}
              </div>
            `);
          }
        });
      });
    }
  };

  // 12. STORY STATUS MANAGER (Dịch giả & Admin đổi trạng thái truyện)
  const StoryStatusManager = {
    init() {
      // Toggle dropdown on single-truyen page
      $(document).on('click', '#storyStatusBadge.badge-editable', function(e) {
        e.stopPropagation();
        const $wrap = $(this).closest('.status-interactive-wrap');
        const $menu = $wrap.find('#statusDropdownMenu');
        $wrap.toggleClass('open');
        $menu.fadeToggle(150);
      });

      // Close dropdown when clicking outside
      $(document).on('click', (e) => {
        if (!$(e.target).closest('.status-interactive-wrap').length) {
          $('.status-interactive-wrap').removeClass('open');
          $('#statusDropdownMenu').fadeOut(100);
        }
      });

      // Select new status from single-truyen dropdown
      $(document).on('click', '.status-dropdown-item', function(e) {
        e.preventDefault();
        const $item = $(this);
        const storyId = $item.data('story-id');
        const newStatus = $item.data('status');
        const $wrap = $item.closest('.status-interactive-wrap');
        const $badge = $wrap.find('#storyStatusBadge');
        const $icon = $wrap.find('#storyStatusIcon');
        const $text = $wrap.find('#storyStatusText');
        const $menu = $wrap.find('#statusDropdownMenu');

        if ($item.hasClass('active')) {
          $menu.fadeOut(100);
          $wrap.removeClass('open');
          return;
        }

        $menu.fadeOut(100);
        $wrap.removeClass('open');

        const ajaxUrl = (typeof muopConfig !== 'undefined' && muopConfig.ajaxUrl) ? muopConfig.ajaxUrl : '/core/wp-admin/admin-ajax.php';
        const nonce = (typeof muopConfig !== 'undefined') ? muopConfig.nonce : '';

        $.ajax({
          url: ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_update_story_status',
            nonce: nonce,
            story_id: storyId,
            status: newStatus
          },
          success: (res) => {
            if (typeof res === 'string') {
              const clean = res.replace(/<br\s*\/?>\s*<b>(?:Notice|Warning|Deprecated)<\/b>:.*?<br\s*\/?>/gi, '').trim();
              try { res = JSON.parse(clean); } catch (e) {}
            }
            if (res && res.success) {
              if (res.data && res.data.is_full) {
                $badge.removeClass('badge-green').addClass('badge-full');
                $icon.removeClass('fa-arrows-rotate').addClass('fa-check');
                $text.text('Hoàn thành');
              } else {
                $badge.removeClass('badge-full').addClass('badge-green');
                $icon.removeClass('fa-check').addClass('fa-arrows-rotate');
                $text.text('Đang ra');
              }
              $wrap.find('.status-dropdown-item').removeClass('active');
              $item.addClass('active');

              if (res.data && res.data.updated && res.data.updated.display) {
                $('#storyUpdatedMeta').text(res.data.updated.display).attr('title', res.data.updated.formatted);
              }

              alert(getAjaxMsg(res, 'Đã cập nhật trạng thái truyện thành công!'));
            } else {
              alert(getAjaxMsg(res, 'Lỗi cập nhật trạng thái!'));
            }
          },
          error: (xhr) => {
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
            alert(errorMsg);
          }
        });
      });

      // Quick toggle in dashboard tables (Dịch giả & Admin)
      $(document).on('click', '.btn-table-change-status', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const storyId = $btn.data('story-id');
        const currentStatus = $btn.data('current-status') || 'dang_ra';
        const nextStatus = (currentStatus === 'hoan_thanh') ? 'dang_ra' : 'hoan_thanh';
        const nextLabel = (nextStatus === 'hoan_thanh') ? 'Hoàn thành' : 'Đang ra';

        if (!confirm(`Bạn có muốn đổi trạng thái bộ truyện này sang "${nextLabel}" không?`)) {
          return;
        }

        const ajaxUrl = (typeof muopConfig !== 'undefined' && muopConfig.ajaxUrl) ? muopConfig.ajaxUrl : '/core/wp-admin/admin-ajax.php';
        const nonce = (typeof muopConfig !== 'undefined') ? muopConfig.nonce : '';

        $.ajax({
          url: ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_update_story_status',
            nonce: nonce,
            story_id: storyId,
            status: nextStatus
          },
          success: (res) => {
            if (typeof res === 'string') {
              const clean = res.replace(/<br\s*\/?>\s*<b>(?:Notice|Warning|Deprecated)<\/b>:.*?<br\s*\/?>/gi, '').trim();
              try { res = JSON.parse(clean); } catch (e) {}
            }
            if (res && res.success) {
              $btn.data('current-status', nextStatus);
              if (res.data && res.data.is_full) {
                $btn.removeClass('badge-green').addClass('badge-full');
                $btn.find('i.fa-solid:first').removeClass('fa-arrows-rotate').addClass('fa-check');
                $btn.find('.table-status-text').text('Full');
              } else {
                $btn.removeClass('badge-full').addClass('badge-green');
                $btn.find('i.fa-solid:first').removeClass('fa-check').addClass('fa-arrows-rotate');
                $btn.find('.table-status-text').text('Đang ra');
              }
              alert(getAjaxMsg(res, 'Đã cập nhật trạng thái truyện thành công!'));
            } else {
              alert(getAjaxMsg(res, 'Lỗi cập nhật trạng thái!'));
            }
          },
          error: (xhr) => {
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
            alert(errorMsg);
          }
        });
      });
    }
  };

  // 15. TEAM DỊCH MANAGER (YÊU THÍCH / THEO DÕI TEAM)
  const TeamManager = {
    init() {
      $('#btnToggleFavoriteTeam').on('click', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const teamId = $btn.data('team-id');
        const teamName = $btn.data('team-name');
        const $icon = $('#favTeamHeartIcon');
        const $text = $('#favTeamBtnText');
        const $count = $('#teamFavCountDisplay');

        if (!teamId && !teamName) return;

        $btn.prop('disabled', true);
        const nonce = (typeof muopConfig !== 'undefined' ? muopConfig.nonce : '');
        const ajaxUrl = (typeof muopConfig !== 'undefined' && muopConfig.ajaxUrl) ? muopConfig.ajaxUrl : '/core/wp-admin/admin-ajax.php';

        $.ajax({
          url: ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_toggle_favorite_team',
            nonce: nonce,
            team_id: teamId,
            team_name: teamName
          },
          success: (res) => {
            $btn.prop('disabled', false);
            if (typeof res === 'string') {
              const clean = res.replace(/<br\s*\/?>\s*<b>(?:Notice|Warning|Deprecated)<\/b>:.*?<br\s*\/?>/gi, '').trim();
              try { res = JSON.parse(clean); } catch (e) {}
            }
            if (res && res.success) {
              const isFav = res.data.favorited;
              const count = res.data.count;

              if (isFav) {
                $btn.removeClass('btn-secondary').addClass('btn-primary');
                $icon.removeClass('fa-regular').addClass('fa-solid');
                $text.text('Đã Yêu Thích Team');
              } else {
                $btn.removeClass('btn-primary').addClass('btn-secondary');
                $icon.removeClass('fa-solid').addClass('fa-regular');
                $text.text('Yêu Thích Team');
              }
              if ($count.length) {
                $count.text(count.toLocaleString('vi-VN'));
              }
            } else {
              alert(getAjaxMsg(res, 'Vui lòng đăng nhập để lưu team vào danh sách yêu thích!'));
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false);
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            alert(errorMsg);
          }
        });
      });

      // Remove from cupboard in page-tu-truyen
      $(document).on('click', '.btn-remove-from-cupboard', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const $btn = $(this);
        const storyId = $btn.data('story-id');
        const $item = $(`#tuTruyenItem-${storyId}`);

        if (!storyId) return;

        if (!confirm('Bạn có chắc chắn muốn bỏ lưu bộ truyện này khỏi Tủ truyện?')) {
          return;
        }

        $btn.prop('disabled', true);
        const nonce = (typeof muopConfig !== 'undefined' ? muopConfig.nonce : '');
        const ajaxUrl = (typeof muopConfig !== 'undefined' && muopConfig.ajaxUrl) ? muopConfig.ajaxUrl : '/core/wp-admin/admin-ajax.php';

        $.ajax({
          url: ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_toggle_bookmark',
            nonce: nonce,
            story_id: storyId
          },
          success: (res) => {
            $item.fadeOut(250, function() {
              $(this).remove();
              if ($('#tuTruyenStoryGrid .tu-truyen-item-wrap').length === 0) {
                location.reload();
              }
            });
          },
          error: () => {
            $btn.prop('disabled', false);
            alert('Lỗi kết nối máy chủ!');
          }
        });
      });

      // 15.1 Quản lý Popup Riêng & Popup Chung của Team Dịch
      // A. Mở popup riêng: Thay Avatar (Hình 1 - icon camera)
      $('#btnChangeTeamAvatarBadge').on('click', function(e) {
        e.preventDefault();
        $('#editSingleAvatarAlert').hide();
        $('#modalEditSingleTeamAvatar').css('display', 'flex');
      });

      // B. Mở popup riêng: Đổi Tên Team (Hình 1 - icon cây bút cạnh tên)
      $('#btnEditTeamNameInline').on('click', function(e) {
        e.preventDefault();
        $('#editSingleNameAlert').hide();
        $('#inputSingleTeamName').val($('#teamHeroNameDisplay').text().trim());
        $('#modalEditSingleTeamName').css('display', 'flex');
        setTimeout(() => $('#inputSingleTeamName').trigger('focus'), 100);
      });

      // C. Mở popup riêng: Đổi Bio (Hình 1 - icon cây bút cạnh bio)
      $('#btnEditTeamBioInline').on('click', function(e) {
        e.preventDefault();
        $('#editSingleBioAlert').hide();
        $('#inputSingleTeamBio').val($('#teamHeroBioDisplay').text().trim());
        $('#modalEditSingleTeamBio').css('display', 'flex');
        setTimeout(() => $('#inputSingleTeamBio').trigger('focus'), 100);
      });

      // D. Mở popup chung: Quản Lý Toàn Bộ Team (Hình 2 - nút Chỉnh Sửa Team)
      $('#btnOpenEditTeamModal').on('click', function(e) {
        e.preventDefault();
        $('#editTeamAlert').hide();
        $('#inputTeamName').val($('#teamHeroNameDisplay').text().trim());
        $('#inputTeamBio').val($('#teamHeroBioDisplay').text().trim());
        $('#modalEditTeamInfo').css('display', 'flex');
        setTimeout(() => $('#inputTeamName').trigger('focus'), 100);
      });

      // Đóng tất cả modal team
      $('.btn-close-team-modal').on('click', function(e) {
        e.preventDefault();
        $('#modalEditSingleTeamAvatar, #modalEditSingleTeamName, #modalEditSingleTeamBio, #modalEditTeamInfo').hide();
      });

      $('#modalEditSingleTeamAvatar, #modalEditSingleTeamName, #modalEditSingleTeamBio, #modalEditTeamInfo').on('click', function(e) {
        if (e.target === this) {
          $(this).hide();
        }
      });

      // Preview avatar modal riêng
      $('#btnTriggerSingleAvatarUpload').on('click', function(e) {
        e.preventDefault();
        $('#inputSingleAvatarFile').trigger('click');
      });

      $('#inputSingleAvatarFile').on('change', function() {
        const file = this.files && this.files[0];
        if (file) {
          $('#singleAvatarFileName').html('<strong>Đã chọn:</strong> ' + file.name);
          const reader = new FileReader();
          reader.onload = function(e) {
            $('#singleAvatarPreviewImg').attr('src', e.target.result).show();
            $('#singleAvatarPreviewChar').hide();
          };
          reader.readAsDataURL(file);
        }
      });

      $('#inputSingleAvatarUrl').on('input paste', function() {
        const url = $(this).val().trim();
        if (url) {
          $('#singleAvatarPreviewImg').attr('src', url).show();
          $('#singleAvatarPreviewChar').hide();
        }
      });

      // Preview avatar modal chung
      $('#btnTriggerTeamAvatarUpload').on('click', function(e) {
        e.preventDefault();
        $('#inputTeamAvatarFile').trigger('click');
      });

      $('#inputTeamAvatarFile').on('change', function() {
        const file = this.files && this.files[0];
        if (file) {
          $('#teamAvatarFileName').html('<strong>Đã chọn:</strong> ' + file.name);
          const reader = new FileReader();
          reader.onload = function(e) {
            $('#teamModalAvatarPreviewImg').attr('src', e.target.result).show();
            $('#teamModalAvatarPreviewChar').hide();
          };
          reader.readAsDataURL(file);
        }
      });

      $('#inputTeamAvatarUrl').on('input paste', function() {
        const url = $(this).val().trim();
        if (url) {
          $('#teamModalAvatarPreviewImg').attr('src', url).show();
          $('#teamModalAvatarPreviewChar').hide();
        }
      });

      // Hàm submit AJAX dùng chung cho cả 4 form
      function handleTeamFormSubmit($form, $btn, $alert, $modal, originalBtnText) {
        $alert.hide();
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...');

        const formData = new FormData($form[0]);
        const ajaxUrl = (typeof muopConfig !== 'undefined' && muopConfig.ajaxUrl) ? muopConfig.ajaxUrl : '/core/wp-admin/admin-ajax.php';

        $.ajax({
          url: ajaxUrl,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: (res) => {
            $btn.prop('disabled', false).html(originalBtnText);
            if (typeof res === 'string') {
              const clean = res.replace(/<br\s*\/?>\s*<b>(?:Notice|Warning|Deprecated)<\/b>:.*?<br\s*\/?>/gi, '').trim();
              try { res = JSON.parse(clean); } catch (err) {}
            }

            if (res && res.success) {
              $alert.removeClass('alert-error').addClass('alert-success').text(res.data.message || 'Cập nhật thành công!').fadeIn();

              // Cập nhật tên team
              if (res.data.team_name) {
                $('#teamHeroNameDisplay').text(res.data.team_name);
                $('#breadcrumbTeamName').text(res.data.team_name);
                $('#inputSingleTeamName').val(res.data.team_name);
                $('#inputTeamName').val(res.data.team_name);
                $('#btnToggleFavoriteTeam').data('team-name', res.data.team_name);
              }

              // Cập nhật bio
              if (res.data.team_bio !== undefined) {
                $('#teamHeroBioDisplay').text(res.data.team_bio);
                $('#inputSingleTeamBio').val(res.data.team_bio);
                $('#inputTeamBio').val(res.data.team_bio);
              }

              // Cập nhật avatar
              if (res.data.avatar_url) {
                $('#teamAvatarImg').attr('src', res.data.avatar_url).show();
                $('#teamAvatarChar').hide();
                $('#singleAvatarPreviewImg').attr('src', res.data.avatar_url).show();
                $('#singleAvatarPreviewChar').hide();
                $('#teamModalAvatarPreviewImg').attr('src', res.data.avatar_url).show();
                $('#teamModalAvatarPreviewChar').hide();
              }

              // Nếu có redirect url (do đổi slug)
              if (res.data.redirect_url && window.location.href !== res.data.redirect_url) {
                setTimeout(() => {
                  window.location.href = res.data.redirect_url;
                }, 800);
              } else {
                setTimeout(() => {
                  $modal.fadeOut(200);
                  $alert.hide();
                }, 900);
              }
            } else {
              $alert.removeClass('alert-success').addClass('alert-error').text(getAjaxMsg(res, 'Không thể cập nhật thông tin!')).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html(originalBtnText);
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            $alert.removeClass('alert-success').addClass('alert-error').text(errorMsg).fadeIn();
          }
        });
      }

      // 1. Submit form Avatar riêng
      $('#formEditSingleTeamAvatar').on('submit', function(e) {
        e.preventDefault();
        handleTeamFormSubmit($(this), $('#btnSubmitSingleAvatar'), $('#editSingleAvatarAlert'), $('#modalEditSingleTeamAvatar'), '<i class="fa-solid fa-check"></i> Cập Nhật Ảnh');
      });

      // 2. Submit form Tên riêng
      $('#formEditSingleTeamName').on('submit', function(e) {
        e.preventDefault();
        const val = $('#inputSingleTeamName').val().trim();
        if (!val) {
          $('#editSingleNameAlert').removeClass('alert-success').addClass('alert-error').text('Vui lòng nhập tên team dịch!').fadeIn();
          return;
        }
        handleTeamFormSubmit($(this), $('#btnSubmitSingleName'), $('#editSingleNameAlert'), $('#modalEditSingleTeamName'), '<i class="fa-solid fa-check"></i> Cập Nhật Tên Team');
      });

      // 3. Submit form Bio riêng
      $('#formEditSingleTeamBio').on('submit', function(e) {
        e.preventDefault();
        handleTeamFormSubmit($(this), $('#btnSubmitSingleBio'), $('#editSingleBioAlert'), $('#modalEditSingleTeamBio'), '<i class="fa-solid fa-check"></i> Cập Nhật Lời Giới Thiệu');
      });

      // 4. Submit form Chung (cả 3 mục)
      $('#formEditTeamInfo').on('submit', function(e) {
        e.preventDefault();
        const val = $('#inputTeamName').val().trim();
        if (!val) {
          $('#editTeamAlert').removeClass('alert-success').addClass('alert-error').text('Vui lòng nhập tên team dịch!').fadeIn();
          return;
        }
        handleTeamFormSubmit($(this), $('#btnSubmitTeamInfo'), $('#editTeamAlert'), $('#modalEditTeamInfo'), '<i class="fa-solid fa-check"></i> Lưu Thay Đổi');
      });
    }
  };

  // 16. STORY SUMMARY TOGGLE (XEM THÊM / THU GỌN GIỚI THIỆU TRUYỆN)
  const StorySummaryManager = {
    init() {
      const $box = $('#storySummaryBox');
      const $btn = $('#btnToggleSummary');
      const $wrap = $('#storySummaryToggleWrap');
      if (!$box.length || !$btn.length) return;

      // Nếu nội dung thực tế ngắn (<= 150px) thì không cần thu gọn
      if ($box[0].scrollHeight <= 150) {
        $box.removeClass('is-collapsed');
        $wrap.hide();
        return;
      }

      $btn.on('click', function(e) {
        e.preventDefault();
        const isCollapsed = $box.hasClass('is-collapsed');
        if (isCollapsed) {
          $box.removeClass('is-collapsed');
          $btn.html('<span>Thu gọn</span> <i class="fa-solid fa-chevron-up"></i>');
        } else {
          $box.addClass('is-collapsed');
          $btn.html('<span>Xem thêm</span> <i class="fa-solid fa-chevron-down"></i>');
          const boxTop = $box.offset().top - 80;
          if ($(window).scrollTop() > boxTop) {
            $('html, body').animate({ scrollTop: boxTop }, 200);
          }
        }
      });
    }
  };

  // 17. STORY & CHAPTER MASTER EDITOR (POPUP CHỈNH SỬA THÔNG TIN TRUYỆN & CHƯƠNG)
  const StoryChapterEditor = {
    init() {
      // 1. Lọc bảng truyện theo trạng thái (Tất Cả, Đang Phát Hành, Chờ Duyệt, Mục Lưu Nháp)
      $('.btn-tab-filter').on('click', function(e) {
        e.preventDefault();
        const filter = $(this).data('filter');
        $('.btn-tab-filter').removeClass('active');
        $(this).addClass('active');

        let visibleCount = 0;
        $('.story-table-row').each(function() {
          const status = $(this).data('status');
          if (filter === 'all' || status === filter) {
            $(this).show();
            visibleCount++;
          } else {
            $(this).hide();
          }
        });

        if (visibleCount === 0) {
          $('#noStoriesFilterMsg').show();
        } else {
          $('#noStoriesFilterMsg').hide();
        }
      });

      // 2. Nút "Đăng Lên" từ danh sách bản nháp
      $(document).on('click', '.btn-publish-draft', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const storyId = $btn.data('story-id');

        if (!confirm('Bạn có chắc chắn muốn đăng bộ truyện này lên hệ thống?')) {
          return;
        }

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang đăng...');

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_publish_draft',
            nonce: muopConfig.nonce,
            story_id: storyId
          },
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              alert(getAjaxMsg(res, 'Đã gửi đăng truyện thành công!'));
              window.location.reload();
            } else {
              alert(getAjaxMsg(res, 'Lỗi đăng truyện!'));
              $btn.prop('disabled', false).html('<i class="fa-solid fa-cloud-arrow-up"></i> Đăng Lên');
            }
          },
          error: (xhr) => {
            let err = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) err = getAjaxMsg(xhr.responseJSON, err);
            alert(err);
            $btn.prop('disabled', false).html('<i class="fa-solid fa-cloud-arrow-up"></i> Đăng Lên');
          }
        });
      });

      // 3. Mở Modal Chỉnh Sửa Thông Tin Truyện (Popup 1)
      $(document).on('click', '.btn-edit-story', function(e) {
        e.preventDefault();
        const storyId = $(this).data('story-id');
        if (!storyId) return;

        const $modal = $('#modalEditStoryInfo').length ? $('#modalEditStoryInfo') : $('#modalEditStoryMaster');
        if (!$modal.length) return;

        // Reset modal state
        $('#editStoryAlert').hide();
        $('#editStoryCoverInfo').hide();
        $('#editStoryCoverFile').val('');
        if ($('#formEditStoryMaster').length) {
          $('#formEditStoryMaster')[0].reset();
        }
        $('#editStoryId').val(storyId);
        $('#modalStoryTitleText').text('Đang tải dữ liệu...');

        $modal.css('display', 'flex');

        // Fetch data
        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_get_story_data',
            nonce: muopConfig.nonce,
            story_id: storyId
          },
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              const data = res.data;
              $('#modalStoryTitleText').text(data.title);
              $('#editStoryTitle').val(data.title);
              $('#editStoryStatus').val(data.status || 'dang_ra');
              $('#editStoryAuthor').val(data.author_name || '');
              $('#editStoryTeam').val(data.team_name || '');
              $('#editStoryDescription').val(data.description || '');

              // Thumbnail preview
              if (data.thumbnail) {
                $('#editStoryCoverPreview').attr('src', data.thumbnail).show();
              } else {
                $('#editStoryCoverPreview').hide();
              }

              // Genres
              $('#editGenresCheckboxList input[type="checkbox"]').prop('checked', false);
              if (data.categories && data.categories.length) {
                data.categories.forEach(catId => {
                  $(`#editGenresCheckboxList input[value="${catId}"]`).prop('checked', true);
                });
              }
            } else {
              alert(getAjaxMsg(res, 'Không thể tải thông tin truyện!'));
              $modal.hide();
            }
          },
          error: (xhr) => {
            let err = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) err = getAjaxMsg(xhr.responseJSON, err);
            alert(err);
            $modal.hide();
          }
        });
      });

      // Đóng Modal Thông Tin Truyện
      $('#btnCloseStoryInfoModal, #btnCloseStoryMasterModal, .btn-close-modal-story-info, .btn-close-modal-action').on('click', function(e) {
        e.preventDefault();
        $('#modalEditStoryInfo, #modalEditStoryMaster').hide();
      });

      $('#modalEditStoryInfo, #modalEditStoryMaster').on('click', function(e) {
        if (e.target === this) {
          $(this).hide();
        }
      });

      // 6. Upload ảnh bìa trong Modal
      $('#btnEditUploadCover').on('click', function(e) {
        e.preventDefault();
        $('#editStoryCoverFile').trigger('click');
      });

      $('#editStoryCoverFile').on('change', function() {
        const file = this.files && this.files[0];
        if (file) {
          $('#editStoryCoverFileName').text(file.name);
          $('#editStoryCoverInfo').css('display', 'inline-flex');
          const reader = new FileReader();
          reader.onload = function(e) {
            $('#editStoryCoverPreview').attr('src', e.target.result).show();
          };
          reader.readAsDataURL(file);
        } else {
          $('#editStoryCoverInfo').hide();
        }
      });

      // 7. Tìm kiếm thể loại trong Modal
      $('#editInputSearchGenre').on('input', function() {
        const q = $(this).val().toLowerCase().trim();
        $('#editGenresCheckboxList .edit-genre-item').each(function() {
          const name = ($(this).data('name') || $(this).text()).toLowerCase();
          if (!q || name.indexOf(q) !== -1) {
            $(this).show();
          } else {
            $(this).hide();
          }
        });
      });

      // 8. Lưu Thay Đổi Truyện & Lưu Nháp Truyện (Tab 1)
      $('#btnSaveDraftStoryAction').on('click', function(e) {
        e.preventDefault();
        $('#editStoryIsDraftInput').val('1');
        $('#formEditStoryMaster').trigger('submit');
      });

      $('#btnUpdateStoryAction').on('click', function() {
        $('#editStoryIsDraftInput').val('0');
      });

      $('#formEditStoryMaster').on('submit', function(e) {
        e.preventDefault();
        const $alert = $('#editStoryAlert');
        const isDraft = $('#editStoryIsDraftInput').val() === '1';
        const $btn = isDraft ? $('#btnSaveDraftStoryAction') : $('#btnUpdateStoryAction');

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...');
        $alert.hide();

        const formData = new FormData(this);

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: (res) => {
            $btn.prop('disabled', false).html(isDraft ? '<i class="fa-solid fa-file-pen"></i> Lưu Vào Bản Nháp' : '<i class="fa-solid fa-check"></i> Cập Nhật Truyện');
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              $alert.removeClass('alert-error').addClass('alert-success').text(getAjaxMsg(res, 'Cập nhật thành công!')).fadeIn();
              setTimeout(() => {
                window.location.reload();
              }, 900);
            } else {
              $alert.removeClass('alert-success').addClass('alert-error').text(getAjaxMsg(res, 'Lỗi cập nhật!')).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html(isDraft ? '<i class="fa-solid fa-file-pen"></i> Lưu Vào Bản Nháp' : '<i class="fa-solid fa-check"></i> Cập Nhật Truyện');
            let msg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) msg = getAjaxMsg(xhr.responseJSON, msg);
            $alert.removeClass('alert-success').addClass('alert-error').text(msg).fadeIn();
          }
        });
      });

      // 9. Chọn chương để chỉnh sửa (Tab 2)
      $('#selectEditChapter').on('change', function() {
        const chapId = $(this).val();
        const $form = $('#formEditChapterFields');
        const $alert = $('#editChapterAlert');
        $alert.hide();

        if (!chapId) {
          $form.hide();
          return;
        }

        $form.hide();
        $('#editChapterId').val(chapId);

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_get_chapter_data',
            nonce: muopConfig.nonce,
            chapter_id: chapId
          },
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              const data = res.data;
              $('#editChapterNumber').val(data.chapter_number);
              $('#editChapterTitle').val(data.title);
              $('#editChapterContent').val(data.content);
              $form.fadeIn(200);
            } else {
              alert(getAjaxMsg(res, 'Không thể tải nội dung chương!'));
            }
          },
          error: (xhr) => {
            let msg = 'Lỗi kết nối khi tải nội dung chương!';
            if (xhr && xhr.responseJSON) msg = getAjaxMsg(xhr.responseJSON, msg);
            alert(msg);
          }
        });
      });

      // 10. Lưu Thay Đổi Chương & Lưu Nháp Chương (Tab 2)
      $('#btnSaveDraftChapterAction').on('click', function(e) {
        e.preventDefault();
        $('#editChapterIsDraftInput').val('1');
        $('#formEditChapterFields').trigger('submit');
      });

      $('#btnUpdateChapterAction').on('click', function() {
        $('#editChapterIsDraftInput').val('0');
      });

      $('#formEditChapterFields').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $alert = $('#editChapterAlert');
        const isDraft = $('#editChapterIsDraftInput').val() === '1';
        const $btn = isDraft ? $('#btnSaveDraftChapterAction') : $('#btnUpdateChapterAction');

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...');
        $alert.hide();

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: $form.serialize(),
          success: (res) => {
            $btn.prop('disabled', false).html(isDraft ? '<i class="fa-solid fa-file-pen"></i> Lưu Nháp Chương' : '<i class="fa-solid fa-check"></i> Cập Nhật Chương');
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              $alert.removeClass('alert-error').addClass('alert-success').text(getAjaxMsg(res, 'Cập nhật chương thành công!')).fadeIn();
              const data = res.data;
              const statusLabel = (data.post_status === 'draft') ? ' (Bản nháp)' : '';
              $(`#selectEditChapter option[value="${data.chapter_id}"]`).text(`Chương ${data.chapter_number}: ${data.chapter_title}${statusLabel}`);

              // Cập nhật ngay tên chương trên danh sách ngoài giao diện
              const $row = $(`#chapterRow-${data.chapter_id}`);
              if ($row.length) {
                $row.find('.chap-title-text').text(data.chapter_title);
                $row.find('.btn-chapter-dropdown-trigger').data('chapter-title', data.chapter_title).data('chapter-num', data.chapter_number);
                $row.find('.btn-trigger-edit-chapter').data('chapter-title', data.chapter_title).data('chapter-num', data.chapter_number);
                $row.find('.btn-trigger-delete-chapter').data('chapter-title', data.chapter_title).data('chapter-num', data.chapter_number);
              }

              setTimeout(() => {
                $alert.fadeOut(300);
              }, 2500);
            } else {
              $alert.removeClass('alert-success').addClass('alert-error').text(getAjaxMsg(res, 'Lỗi cập nhật chương!')).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html(isDraft ? '<i class="fa-solid fa-file-pen"></i> Lưu Nháp Chương' : '<i class="fa-solid fa-check"></i> Cập Nhật Chương');
            let msg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) msg = getAjaxMsg(xhr.responseJSON, msg);
            $alert.removeClass('alert-success').addClass('alert-error').text(msg).fadeIn();
          }
        });
      });

      // 11.a. Menu 3 chấm dọc ở từng chương: Mở/Đóng Dropdown menu
      $(document).on('click', '.btn-chapter-dropdown-trigger', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const $dropdown = $(this).closest('.chapter-actions-dropdown');
        const isOpen = $dropdown.hasClass('is-open');

        // Đóng các dropdown khác đang mở
        $('.chapter-actions-dropdown').not($dropdown).removeClass('is-open').find('.chapter-dropdown-menu').removeClass('is-show');

        if (isOpen) {
          $dropdown.removeClass('is-open');
          $dropdown.find('.chapter-dropdown-menu').removeClass('is-show');
        } else {
          $dropdown.addClass('is-open');
          $dropdown.find('.chapter-dropdown-menu').addClass('is-show');
        }
      });

      // Đóng dropdown khi click ra ngoài
      $(document).on('click', function(e) {
        if (!$(e.target).closest('.chapter-actions-dropdown').length) {
          $('.chapter-actions-dropdown').removeClass('is-open');
          $('.chapter-dropdown-menu').removeClass('is-show');
        }
      });

      // 11.b. Khi nhấn "Chỉnh sửa chương" từ menu 3 chấm: Mở popup và tự động nạp thẳng nội dung chương, không cần chọn lại chương
      $(document).on('click', '.btn-trigger-edit-chapter', function(e) {
        e.preventDefault();
        e.stopPropagation();

        // Đóng dropdown menu
        $('.chapter-actions-dropdown').removeClass('is-open');
        $('.chapter-dropdown-menu').removeClass('is-show');

        const storyId = $(this).data('story-id');
        const chapterId = $(this).data('chapter-id');
        const chapterNum = $(this).data('chapter-num');
        const chapterTitle = $(this).data('chapter-title') || '';

        if (!chapterId) return;

        const $modal = $('#modalEditChapterContent');
        if (!$modal.length) return;

        // Reset cảnh báo và ẩn hẳn phần chọn chương (vì đã biết rõ chương cần sửa)
        $('#editChapterAlert').hide();
        $('#wrapperSelectEditChapter').hide();
        $('#editNoChaptersMsg').hide();
        $('#formEditChapterFields').hide();

        // Đặt tiêu đề modal: Chương [số]: [tiêu đề]
        const displayTitle = chapterTitle ? `Chương ${chapterNum}: ${chapterTitle}` : `Chương ${chapterNum}`;
        $('#modalChapterStoryTitleText').text(displayTitle);

        $modal.css('display', 'flex');

        // Hiển thị trạng thái đang nạp nội dung
        $('#editChapterAlert').html('<i class="fa-solid fa-spinner fa-spin"></i> Đang nạp nội dung chương...').removeClass('alert-error alert-success').addClass('alert-info').show();

        // Nạp trực tiếp dữ liệu của chương này
        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_get_chapter_data',
            chapter_id: chapterId,
            nonce: muopConfig.nonce
          },
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success && res.data) {
              const data = res.data;
              $('#editChapterAlert').hide();
              $('#editChapterId').val(data.id);
              $('#editChapterNumber').val(data.chapter_number);
              $('#editChapterTitle').val(data.title);
              $('#editChapterContent').val(data.content);
              $('#editChapterIsDraftInput').val(data.status === 'draft' ? '1' : '0');

              if (data.status === 'draft') {
                $('#btnSaveDraftChapterAction').html('<i class="fa-solid fa-file-pen"></i> Tiếp Tục Lưu Nháp');
                $('#btnUpdateChapterAction').html('<i class="fa-solid fa-cloud-arrow-up"></i> Xuất Bản (Đăng Ngay)');
              } else {
                $('#btnSaveDraftChapterAction').html('<i class="fa-solid fa-file-pen"></i> Chuyển Về Bản Nháp');
                $('#btnUpdateChapterAction').html('<i class="fa-solid fa-check"></i> Cập Nhật Chương');
              }

              $('#formEditChapterFields').fadeIn(200);
            } else {
              const msg = (res && res.data && res.data.message) || 'Không thể tải nội dung chương!';
              $('#editChapterAlert').html('<i class="fa-solid fa-circle-exclamation"></i> ' + msg).removeClass('alert-info').addClass('alert-error').show();
            }
          },
          error: (xhr) => {
            let msg = 'Lỗi kết nối khi tải nội dung chương!';
            if (xhr && xhr.responseJSON) msg = getAjaxMsg(xhr.responseJSON, msg);
            $('#editChapterAlert').html('<i class="fa-solid fa-circle-exclamation"></i> ' + msg).removeClass('alert-info').addClass('alert-error').show();
          }
        });
      });

      // 11.c. Khi nhấn "Xóa chương" từ menu 3 chấm: Mở popup xác nhận xóa chương
      $(document).on('click', '.btn-trigger-delete-chapter', function(e) {
        e.preventDefault();
        e.stopPropagation();

        // Đóng dropdown menu
        $('.chapter-actions-dropdown').removeClass('is-open');
        $('.chapter-dropdown-menu').removeClass('is-show');

        const storyId = $(this).data('story-id');
        const chapterId = $(this).data('chapter-id');
        const chapterNum = $(this).data('chapter-num');
        const chapterTitle = $(this).data('chapter-title') || '';

        if (!chapterId) return;

        const $modal = $('#modalDeleteChapterConfirm');
        if (!$modal.length) return;

        $modal.data('chapter-id', chapterId).data('story-id', storyId);
        const nameText = chapterTitle ? `Chương ${chapterNum}: "${chapterTitle}"` : `Chương ${chapterNum}`;
        $('#deleteChapterLabelName').text(nameText);
        $('#deleteChapterAlert').hide();
        $('#btnConfirmDeleteChapter').prop('disabled', false).html('<i class="fa-solid fa-trash-can"></i> Xóa Chương');

        $modal.css('display', 'flex');
      });

      // Đóng modal xóa chương
      $(document).on('click', '.btn-cancel-delete-chapter', function(e) {
        e.preventDefault();
        $('#modalDeleteChapterConfirm').hide();
      });

      $('#modalDeleteChapterConfirm').on('click', function(e) {
        if (e.target === this) {
          $(this).hide();
        }
      });

      // Xác nhận thực hiện xóa chương qua AJAX
      $('#btnConfirmDeleteChapter').on('click', function(e) {
        e.preventDefault();
        const $modal = $('#modalDeleteChapterConfirm');
        const chapterId = $modal.data('chapter-id');
        const storyId = $modal.data('story-id');
        const $btn = $(this);

        if (!chapterId) return;

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang xóa...');
        $('#deleteChapterAlert').hide();

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_delete_chapter',
            chapter_id: chapterId,
            nonce: muopConfig.nonce
          },
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              $modal.hide();

              // Xóa hàng chương khỏi giao diện danh sách chương với hiệu ứng trượt mượt mà
              const $row = $(`#chapterRow-${chapterId}`);
              $row.slideUp(250, function() {
                $(this).remove();

                const remaining = (res.data && typeof res.data.remaining_chapters !== 'undefined')
                  ? res.data.remaining_chapters
                  : $('.chapter-item-row').length;

                $('.chapter-list-header .section-title').html(`<i class="fa-solid fa-list-ol"></i> Danh Sách Chương (${remaining})`);

                if (remaining === 0) {
                  $('.chapter-grid').replaceWith(`
                    <div style="text-align: center; padding: 30px; background: var(--pastel-green); border-radius: var(--radius-md); color: var(--text-muted);">
                      <i class="fa-regular fa-folder-open" style="font-size: 32px; color: var(--avocado-green); margin-bottom: 8px;"></i>
                      <p>Truyện đang được cập nhật chương mới. Vui lòng quay lại sau nha!</p>
                    </div>
                  `);
                }
              });
            } else {
              $btn.prop('disabled', false).html('<i class="fa-solid fa-trash-can"></i> Xóa Chương');
              const msg = (res && res.data && res.data.message) || 'Lỗi xóa chương. Vui lòng thử lại!';
              $('#deleteChapterAlert').text(msg).show();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-trash-can"></i> Xóa Chương');
            let msg = 'Lỗi kết nối máy chủ khi xóa chương!';
            if (xhr && xhr.responseJSON) msg = getAjaxMsg(xhr.responseJSON, msg);
            $('#deleteChapterAlert').text(msg).show();
          }
        });
      });

      // Đóng Modal Chương Truyện
      $('#btnCloseChapterContentModal, .btn-close-modal-chapter').on('click', function(e) {
        e.preventDefault();
        $('#modalEditChapterContent').hide();
      });

      $('#modalEditChapterContent').on('click', function(e) {
        if (e.target === this) {
          $(this).hide();
        }
      });

      // 12. Cây bút mờ trên ảnh bìa truyện (Hình 1): Đổi ảnh bìa nhanh
      $('#btnChangeCoverOverlay').on('click', function(e) {
        e.preventDefault();
        const storyId = $(this).data('story-id');
        if (!storyId) return;

        const $modal = $('#modalChangeCoverDirect');
        if (!$modal.length) return;

        $('#changeCoverAlert').hide();
        $('#directCoverSelectedStatus').hide();
        $('#inputDirectCoverFile').val('');
        $('#inputSelectedPresetCoverId').val('');
        $('#inputSelectedPresetCoverUrl').val('');
        $('#quickCoverStoryId').val(storyId);

        // Preview ảnh hiện tại
        const currentSrc = $('#mainStoryCoverImg').attr('src') || '';
        $('#directCoverPreviewImg').attr('src', currentSrc);

        $modal.css('display', 'flex');

        // Nạp danh sách preset covers qua AJAX
        const $presetGrid = $('#directPresetCoversList');
        $presetGrid.html('<div style="grid-column: 1 / -1; text-align: center; font-size: 12px; color: var(--text-muted); padding: 15px;"><i class="fa-solid fa-spinner fa-spin"></i> Đang nạp danh sách ảnh...</div>');

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_get_preset_covers',
            story_id: storyId,
            nonce: muopConfig.nonce
          },
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success && res.data && res.data.covers) {
              $presetGrid.empty();
              res.data.covers.forEach(c => {
                const card = $(`
                  <div class="preset-cover-card" data-id="${c.id}" data-url="${c.url}" title="${c.title}">
                    <img src="${c.thumb}" alt="${c.title}" />
                  </div>
                `);
                $presetGrid.append(card);
              });
            } else {
              $presetGrid.html('<div style="grid-column: 1 / -1; text-align: center; font-size: 12px; color: var(--text-muted); padding: 10px;">Không có ảnh mẫu nào. Hãy tải ảnh từ thiết bị của bạn!</div>');
            }
          },
          error: () => {
            $presetGrid.html('<div style="grid-column: 1 / -1; text-align: center; font-size: 12px; color: var(--text-muted); padding: 10px;">Không thể tải danh sách ảnh mẫu. Hãy tải ảnh từ thiết bị!</div>');
          }
        });
      });

      // Click chọn ảnh preset trong modal đổi ảnh
      $(document).on('click', '.preset-cover-card', function() {
        $('.preset-cover-card').removeClass('is-selected');
        $(this).addClass('is-selected');

        const presetId = $(this).data('id');
        const presetUrl = $(this).data('url');

        $('#inputSelectedPresetCoverId').val(presetId);
        $('#inputSelectedPresetCoverUrl').val(presetUrl);
        $('#inputDirectCoverFile').val('');

        $('#directCoverPreviewImg').attr('src', presetUrl);
        $('#directCoverFileName').text($(this).attr('title') || 'Ảnh mẫu');
        $('#directCoverSelectedStatus').css('display', 'inline-flex');
      });

      // Tải ảnh từ thiết bị trong modal đổi ảnh
      $('#btnTriggerDirectUpload').on('click', function(e) {
        e.preventDefault();
        $('#inputDirectCoverFile').trigger('click');
      });

      $('#inputDirectCoverFile').on('change', function() {
        const file = this.files && this.files[0];
        if (file) {
          $('.preset-cover-card').removeClass('is-selected');
          $('#inputSelectedPresetCoverId').val('');
          $('#inputSelectedPresetCoverUrl').val('');

          $('#directCoverFileName').text(file.name);
          $('#directCoverSelectedStatus').css('display', 'inline-flex');

          const reader = new FileReader();
          reader.onload = function(e) {
            $('#directCoverPreviewImg').attr('src', e.target.result);
          };
          reader.readAsDataURL(file);
        } else {
          $('#directCoverSelectedStatus').hide();
        }
      });

      // Submit Form Đổi Ảnh Bìa Nhanh
      $('#formChangeCoverDirect').on('submit', function(e) {
        e.preventDefault();
        const $btn = $('#btnSubmitDirectCover');
        const $alert = $('#changeCoverAlert');
        $alert.hide();

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang cập nhật...');

        const formData = new FormData(this);

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: (res) => {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Cập Nhật Ảnh Bìa');
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              const newUrl = res.data && res.data.cover_url;
              if (newUrl) {
                $('#mainStoryCoverImg').attr('src', newUrl);
                $('#editStoryCoverPreview').attr('src', newUrl).show();
              }
              if (res.data && res.data.updated && res.data.updated.display) {
                $('#storyUpdatedMeta').text(res.data.updated.display).attr('title', res.data.updated.formatted);
              }
              $alert.removeClass('alert-error').addClass('alert-success').text(getAjaxMsg(res, 'Thay đổi ảnh bìa thành công!')).fadeIn();
              setTimeout(() => {
                $('#modalChangeCoverDirect').hide();
              }, 1000);
            } else {
              $alert.removeClass('alert-success').addClass('alert-error').text(getAjaxMsg(res, 'Lỗi cập nhật ảnh bìa!')).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Cập Nhật Ảnh Bìa');
            let msg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) msg = getAjaxMsg(xhr.responseJSON, msg);
            $alert.removeClass('alert-success').addClass('alert-error').text(msg).fadeIn();
          }
        });
      });

      // 13. Cây bút cạnh tên truyện (Hình 1): Đổi tên truyện nhanh
      $('#btnEditTitleInline').on('click', function(e) {
        e.preventDefault();
        const storyId = $(this).data('story-id');
        if (!storyId) return;

        const currentTitle = $('#storyTitleText').text().trim();
        $('#editTitleAlert').hide();
        $('#quickTitleStoryId').val(storyId);
        $('#inputQuickStoryTitle').val(currentTitle);

        $('#modalEditTitleDirect').css('display', 'flex');
        $('#inputQuickStoryTitle').trigger('focus');
      });

      // Submit Form Đổi Tên Truyện Nhanh
      $('#formEditTitleDirect').on('submit', function(e) {
        e.preventDefault();
        const $btn = $('#btnSubmitQuickTitle');
        const $alert = $('#editTitleAlert');
        const newTitle = $('#inputQuickStoryTitle').val().trim();
        $alert.hide();

        if (!newTitle) {
          $alert.removeClass('alert-success').addClass('alert-error').text('Vui lòng nhập tên truyện!').fadeIn();
          return;
        }

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...');

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: $(this).serialize(),
          success: (res) => {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Cập Nhật Tên Truyện');
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              $('#storyTitleText').text(newTitle);
              $('#breadcrumbStoryTitle').text(newTitle);
              $('#modalStoryTitleText').text(newTitle);
              $('#editStoryTitle').val(newTitle);

              if (res.data && res.data.updated && res.data.updated.display) {
                $('#storyUpdatedMeta').text(res.data.updated.display).attr('title', res.data.updated.formatted);
              }

              $alert.removeClass('alert-error').addClass('alert-success').text(getAjaxMsg(res, 'Đổi tên truyện thành công!')).fadeIn();
              setTimeout(() => {
                $('#modalEditTitleDirect').hide();
              }, 900);
            } else {
              $alert.removeClass('alert-success').addClass('alert-error').text(getAjaxMsg(res, 'Lỗi đổi tên truyện!')).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Cập Nhật Tên Truyện');
            let msg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) msg = getAjaxMsg(xhr.responseJSON, msg);
            $alert.removeClass('alert-success').addClass('alert-error').text(msg).fadeIn();
          }
        });
      });

      // 14. Đóng Quick Modals
      $('.btn-close-quick-modal').on('click', function(e) {
        e.preventDefault();
        $('#modalChangeCoverDirect, #modalEditTitleDirect').hide();
      });

      $('#modalChangeCoverDirect, #modalEditTitleDirect').on('click', function(e) {
        if (e.target === this) {
          $(this).hide();
        }
      });
    }
  };

  // 17. NOTIFICATIONS & BILL PREVIEW
  const NotificationManager = {
    init() {
      // Toggle popup modal thông báo khi click chuông
      $(document).on('click', '#notifToggleBtn', (e) => {
        e.preventDefault();
        e.stopPropagation();

        if ($('#notifModalBackdrop').is(':visible')) {
          NotificationManager.closeModal();
        } else {
          NotificationManager.openModal();
        }
      });

      // Nút đóng modal X
      $(document).on('click', '#btnCloseNotifModal', (e) => {
        e.preventDefault();
        NotificationManager.closeModal();
      });

      // Đóng modal khi click ra nền mờ bên ngoài
      $(document).on('click', '#notifModalBackdrop', function(e) {
        if (e.target === this) {
          NotificationManager.closeModal();
        }
      });

      // Đóng modal khi bấm Escape
      $(document).on('keydown', (e) => {
        if (e.key === 'Escape' && $('#notifModalBackdrop').is(':visible')) {
          NotificationManager.closeModal();
        }
      });

      // Mark all read
      $(document).on('click', '#notifMarkAllReadBtn', (e) => {
        e.preventDefault();
        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_mark_all_notifs_read',
            nonce: muopConfig.nonce
          },
          success: (res) => {
            if (res && res.success) {
              $('#notifBadgeCount').hide().text('0');
              $('#notifUnreadCountPill').text('0 chưa đọc');
              $('.notif-item').removeClass('unread');
            }
          }
        });
      });

      // Click individual notif item
      $(document).on('click', '.notif-item', function(e) {
        const notifId = $(this).data('id');
        const link = $(this).data('link') || '';
        const notifType = $(this).data('type') || '';
        const hasBill = $(this).data('bill');
        const repIdAttr = $(this).data('report-id');
        let repId = repIdAttr ? parseInt(repIdAttr, 10) : 0;
        if (!repId && link) {
          const match = link.match(/#report-(\d+)/);
          if (match) repId = parseInt(match[1], 10);
        }

        // Mark as read ngầm
        if ($(this).hasClass('unread')) {
          $(this).removeClass('unread');
          $.ajax({
            url: muopConfig.ajaxUrl,
            type: 'POST',
            data: {
              action: 'muop_mark_notification_read',
              notif_id: notifId,
              nonce: muopConfig.nonce
            },
            success: (res) => {
              if (res && res.success) {
                const count = res.data.unread_count;
                if (count > 0) {
                  $('#notifBadgeCount').text(count > 99 ? '99+' : count).show();
                  $('#notifUnreadCountPill').text(count + ' chưa đọc');
                } else {
                  $('#notifBadgeCount').hide().text('0');
                  $('#notifUnreadCountPill').text('0 chưa đọc');
                }
              }
            }
          });
        }

        // 1. Nếu là báo lỗi chương hoặc phản hồi báo lỗi -> MỞ MODAL ĐỐI THOẠI KHÔNG RELOAD / GIỮ NGUYÊN TÀI KHOẢN
        if (repId || notifType === 'chapter_report' || notifType === 'report_reply' || (link && link.indexOf('#report-') !== -1)) {
          e.preventDefault();
          NotificationManager.closeModal();
          if (repId) {
            ReportDialogueManager.openModal(repId);
          }
          return;
        }

        // 2. If clicked on "Xem Bill" or notification has bill image
        if ($(e.target).closest('.notif-bill-preview-btn').length || (hasBill && !link)) {
          e.preventDefault();
          const billUrl = hasBill || $(e.target).closest('.notif-bill-preview-btn').data('bill-url');
          if (billUrl) {
            NotificationManager.closeModal();
            NotificationManager.openBillModal(billUrl);
          }
          return;
        }

        // 3. Các thông báo thông thường khác
        if (link && !$(e.target).closest('.notif-bill-preview-btn').length) {
          NotificationManager.closeModal();
          window.location.href = link;
        }
      });

      // Global Bill Modal handlers
      $(document).on('click', '.btn-view-bill', function(e) {
        e.preventDefault();
        const billUrl = $(this).data('bill-url');
        if (billUrl) {
          NotificationManager.openBillModal(billUrl);
        }
      });

      $('#btnCloseBillModal, #btnDismissBillModal').on('click', () => {
        $('#billPreviewModal').hide();
      });

      $('#billPreviewModal').on('click', function(e) {
        if (e.target === this) {
          $(this).hide();
        }
      });
    },

    openModal() {
      // Đóng dropdown user nếu đang mở
      $('#userDropdownPanel').removeClass('show');
      $('#userMenuWrapper').removeClass('active');

      $('#notifModalBackdrop').css('display', 'flex');
      $('#notifToggleBtn').attr('aria-expanded', 'true');
      this.fetchNotifications();
    },

    closeModal() {
      $('#notifModalBackdrop').hide();
      $('#notifToggleBtn').attr('aria-expanded', 'false');
    },

    openBillModal(billUrl) {
      $('#billModalImg').attr('src', billUrl);
      $('#btnDownloadBill').attr('href', billUrl);
      $('#billPreviewModal').css('display', 'flex');
    },

    fetchNotifications() {
      const $container = $('#notifListContainer');
      $container.html('<div class="notif-loading"><i class="fa-solid fa-spinner fa-spin" style="font-size:24px;margin-bottom:8px;color:var(--primary-green);"></i><div>Đang tải thông báo...</div></div>');

      let endpoint = '/wp-admin/admin-ajax.php';
      if (window.muopConfig && window.muopConfig.ajaxUrl) {
        endpoint = window.muopConfig.ajaxUrl;
        // Nếu ajaxUrl khác hostname hiện tại (ví dụ truy cập IP LAN mà siteurl là domain), fallback về relative path
        try {
          const parsed = new URL(endpoint, window.location.origin);
          if (parsed.host !== window.location.host) {
            endpoint = '/wp-admin/admin-ajax.php';
          }
        } catch (e) {
          endpoint = '/wp-admin/admin-ajax.php';
        }
      }

      const nonce = (window.muopConfig && window.muopConfig.nonce) ? window.muopConfig.nonce : '';

      $.ajax({
        url: endpoint,
        type: 'POST',
        timeout: 10000,
        data: {
          action: 'muop_get_notifications',
          nonce: nonce
        },
        success: (res) => {
          if (typeof res === 'string') {
            try { res = JSON.parse(res); } catch (e) {}
          }
          if (res && res.success) {
            const notifs = res.data.notifications || [];
            const unread = res.data.unread_count || 0;

            if (unread > 0) {
              $('#notifBadgeCount').text(unread > 99 ? '99+' : unread).show();
              $('#notifUnreadCountPill').text(unread + ' chưa đọc');
            } else {
              $('#notifBadgeCount').hide();
              $('#notifUnreadCountPill').text('0 chưa đọc');
            }

            if (notifs.length === 0) {
              $container.html('<div class="notif-empty"><i class="fa-regular fa-bell-slash" style="font-size:28px;display:block;margin-bottom:10px;color:var(--text-muted);opacity:0.6;"></i>Bạn chưa có thông báo nào.</div>');
              return;
            }

            let html = '';
            notifs.forEach(n => {
              const unreadClass = n.is_read ? '' : 'unread';
              const billBtn = n.image ? `<button type="button" class="notif-bill-preview-btn" data-bill-url="${n.image}"><i class="fa-solid fa-receipt"></i> Xem Bill Chuyển Khoản</button>` : '';
              html += `
                <div class="notif-item ${unreadClass}" data-id="${n.id}" data-type="${n.type || ''}" data-report-id="${n.report_id || ''}" data-link="${n.link || ''}" data-bill="${n.image || ''}">
                  <div class="notif-icon-box" style="background: ${n.icon_color}18; color: ${n.icon_color};">
                    <i class="${n.icon}"></i>
                  </div>
                  <div class="notif-content">
                    <div class="notif-title">${n.title}</div>
                    <div class="notif-message">${n.message}</div>
                    ${billBtn}
                    <div class="notif-time"><i class="fa-regular fa-clock"></i> ${n.time_ago}</div>
                  </div>
                </div>
              `;
            });
            $container.html(html);
          } else {
            $container.html('<div class="notif-empty">Không thể tải thông báo. Vui lòng thử lại sau!</div>');
          }
        },
        error: () => {
          $container.html('<div class="notif-empty">Lỗi kết nối khi tải thông báo.</div>');
        }
      });
    }
  };

  // 18. CHAPTER REPORT MODAL (HÌNH 2) - YÊU CẦU ĐĂNG NHẬP
  const ChapterReportManager = {
    init() {
      // Mở modal báo lỗi chương
      $(document).on('click', '#btnOpenReportChapterModal', function(e) {
        e.preventDefault();
        const isLogged = $(this).data('logged-in') == 1 || $(this).data('logged-in') === '1' || (window.muopConfig && window.muopConfig.isLoggedIn);
        if (!isLogged) {
          alert('Bạn cần đăng ký hoặc đăng nhập tài khoản để báo lỗi chương truyện nhé!');
          if ($('#modalAuth').length) {
            $('#modalAuth').show();
          }
          return;
        }
        $('#reportChapterModal').css('display', 'flex');
      });

      // Đóng modal
      $('#btnCloseReportChapterModal, #btnCancelReportChapter').on('click', () => {
        $('#reportChapterModal').hide();
      });

      $('#reportChapterModal').on('click', function(e) {
        if (e.target === this) {
          $(this).hide();
        }
      });

      // Submit form báo lỗi
      $('#formSubmitChapterReport').on('submit', function(e) {
        e.preventDefault();
        const $btn = $('#btnSubmitReportChapter');
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang gửi...');

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: $(this).serialize() + '&action=muop_submit_chapter_report&nonce=' + muopConfig.nonce,
          success: (res) => {
            $btn.prop('disabled', false).html(originalHtml);
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              alert(res.data.message || 'Cảm ơn bạn đã báo lỗi! Ban quản trị và dịch giả đã nhận được thông tin.');
              $('#formSubmitChapterReport')[0].reset();
              $('#reportChapterModal').hide();
            } else {
              alert(getAjaxMsg(res, 'Lỗi gửi báo lỗi!'));
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html(originalHtml);
            let msg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) msg = getAjaxMsg(xhr.responseJSON, msg);
            alert(msg);
          }
        });
      });
    }
  };

  // 18B. REPORT DIALOGUE MANAGER (XEM CHI TIẾT & TRAO ĐỔI PHẢN HỒI 2 CHIỀU ĐỘC GIẢ - DỊCH GIẢ - ADMIN)
  const ReportDialogueManager = {
    init() {
      // Đóng modal
      $('#btnCloseReportDialogueModal, #btnCancelReportDialogueModal').on('click', () => {
        $('#reportDialogueModal').hide();
      });

      $('#reportDialogueModal').on('click', function(e) {
        if (e.target === this) {
          $(this).hide();
        }
      });

      // Submit phản hồi trong modal
      $('#formSubmitReportDialogueReply').on('submit', function(e) {
        e.preventDefault();
        const replyText = $.trim($('#repDlgReplyTextarea').val());
        if (!replyText) {
          alert('Vui lòng nhập nội dung phản hồi!');
          return;
        }

        const $btn = $('#btnSubmitReportDialogueReply');
        const originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang gửi...');

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: $(this).serialize() + '&action=muop_submit_report_reply&nonce=' + muopConfig.nonce,
          success: (res) => {
            $btn.prop('disabled', false).html(originalHtml);
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              $('#repDlgReplyTextarea').val('');
              if (res.data.status) {
                ReportDialogueManager.updateStatusBadge(res.data.status);
              }
              if (res.data.entry) {
                // Xóa thông báo empty nếu có
                $('#repDlgMessagesList .rep-empty-thread').remove();
                // Append message mới
                const entryHtml = ReportDialogueManager.renderMessageItem(res.data.entry);
                $('#repDlgMessagesList').append(entryHtml);
                // Cập nhật số lượng
                const currentCount = parseInt($('#repDlgReplyCount').text(), 10) || 0;
                $('#repDlgReplyCount').text(currentCount + 1);
                // Cuộn xuống tin nhắn mới nhất
                const $list = $('#repDlgMessagesList');
                $list.scrollTop($list[0].scrollHeight);
              }
            } else {
              alert(getAjaxMsg(res, 'Lỗi gửi phản hồi!'));
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html(originalHtml);
            let msg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) msg = getAjaxMsg(xhr.responseJSON, msg);
            alert(msg);
          }
        });
      });

      // Mở tự động nếu URL có hash #report-(\d+)
      if (window.location.hash && window.location.hash.indexOf('#report-') === 0) {
        const hashReportId = window.location.hash.replace('#report-', '');
        if (hashReportId && !isNaN(hashReportId)) {
          setTimeout(() => {
            ReportDialogueManager.openModal(parseInt(hashReportId, 10));
          }, 350);
        }
      }
    },

    updateStatusBadge(status) {
      const $badge = $('#repDlgStatusBadge');
      $badge.removeClass('badge-pending badge-processing badge-resolved badge-rejected');
      if (status === 'resolved') {
        $badge.addClass('badge-resolved').text('Đã sửa xong');
      } else if (status === 'processing') {
        $badge.addClass('badge-processing').text('Đang xử lý');
      } else if (status === 'rejected') {
        $badge.addClass('badge-rejected').text('Đã đóng');
      } else {
        $badge.addClass('badge-pending').text('Chờ xử lý');
      }
    },

    renderMessageItem(item) {
      const isMeClass = item.is_me ? 'is-me' : '';
      let roleTagClass = 'role-doc-gia';
      if (item.role === 'admin') roleTagClass = 'role-admin';
      else if (item.role === 'dich_gia') roleTagClass = 'role-dich-gia';

      const avatarHtml = item.avatar 
        ? `<img src="${item.avatar}" alt="${item.sender_name}" class="rep-msg-avatar" />`
        : `<div class="rep-msg-avatar rep-msg-avatar-placeholder"><i class="fa-solid fa-user"></i></div>`;

      return `
        <div class="rep-msg-item ${isMeClass}">
          ${avatarHtml}
          <div class="rep-msg-bubble">
            <div class="rep-msg-meta">
              <span class="rep-msg-author">${item.sender_name}</span>
              <span class="rep-role-tag ${roleTagClass}">${item.role_label}</span>
              <span class="rep-msg-time" title="${item.created_at}">${item.time_ago || item.created_at}</span>
            </div>
            <div class="rep-msg-body">${item.message}</div>
          </div>
        </div>
      `;
    },

    openModal(reportId) {
      if (!reportId) return;
      const $modal = $('#reportDialogueModal');
      $modal.css('display', 'flex');

      $('#repDlgLoading').show();
      $('#repDlgMainContent').hide();
      $('#repDlgHiddenReportId').val(reportId);

      $.ajax({
        url: muopConfig.ajaxUrl,
        type: 'POST',
        data: {
          action: 'muop_get_report_details',
          report_id: reportId,
          nonce: muopConfig.nonce
        },
        success: (res) => {
          if (typeof res === 'string') {
            try { res = JSON.parse(res); } catch (e) {}
          }
          if (res && res.success) {
            const d = res.data;
            $('#repDlgLoading').hide();
            $('#repDlgMainContent').show();

            // Trạng thái
            ReportDialogueManager.updateStatusBadge(d.status);

            // Thông tin truyện & chương
            $('#repDlgStoryLink').text(d.story_title).attr('href', d.story_url);
            $('#repDlgChapterNum').text(`Chương ${d.chapter_num}`);
            $('#repDlgChapterTitle').text(d.chapter_title ? `- ${d.chapter_title}` : '');
            $('#repDlgCreatedAt').html(`<i class="fa-regular fa-clock"></i> ${d.created_at_time} (${d.created_at_full})`);

            // Người gửi ban đầu
            $('#repDlgSenderName').text(d.sender_name);
            if (d.sender_avatar) {
              $('#repDlgSenderAvatar').attr('src', d.sender_avatar).show();
            } else {
              $('#repDlgSenderAvatar').attr('src', 'https://secure.gravatar.com/avatar/?d=mp').show();
            }

            const $roleTag = $('#repDlgSenderRole');
            $roleTag.removeClass('role-admin role-dich-gia role-doc-gia');
            if (d.sender_role === 'admin') $roleTag.addClass('role-admin');
            else if (d.sender_role === 'dich_gia') $roleTag.addClass('role-dich-gia');
            else $roleTag.addClass('role-doc-gia');
            $roleTag.text(d.sender_role_label);

            $('#repDlgIssueType').html(`<i class="fa-solid fa-triangle-exclamation"></i> ${d.issue_type}`);
            $('#repDlgDescription').html(d.description);

            // Render Thread tin nhắn
            const $messagesList = $('#repDlgMessagesList');
            $messagesList.empty();
            const replies = d.replies || [];
            $('#repDlgReplyCount').text(replies.length);

            if (replies.length === 0) {
              $messagesList.html('<div class="rep-empty-thread"><i class="fa-regular fa-comments"></i> Chưa có phản hồi nào. Ban quản trị hoặc dịch giả sẽ sớm phản hồi cho bạn.</div>');
            } else {
              replies.forEach(rep => {
                $messagesList.append(ReportDialogueManager.renderMessageItem(rep));
              });
              // Cuộn xuống cuối
              setTimeout(() => {
                $messagesList.scrollTop($messagesList[0].scrollHeight);
              }, 100);
            }

            // Quyền & Giao diện phản hồi
            if (d.can_change_status) {
              $('#repDlgStatusControl').css('display', 'flex');
              $('#repDlgStatusSelect').val(d.status);
              $('#repDlgBtnSubmitText').text('Gửi Phản Hồi');
              $('#repDlgReplyTextarea').attr('placeholder', 'Nhập nội dung phản hồi gửi lại cho độc giả...');
              $('#repDlgUserNotice').html('<i class="fa-solid fa-shield-halved" style="color:var(--primary-green);"></i> Bạn đang trả lời với tư cách <strong>' + (d.current_user_role === 'admin' ? 'Ban Quản Trị' : 'Dịch Giả') + '</strong>.');
            } else {
              $('#repDlgStatusControl').hide();
              $('#repDlgBtnSubmitText').text('Gửi Phản Hồi Tiếp');
              $('#repDlgReplyTextarea').attr('placeholder', 'Nhập phản hồi tiếp của bạn gửi tới Admin & Dịch giả...');
              $('#repDlgUserNotice').html('<i class="fa-solid fa-user-check" style="color:var(--primary-green);"></i> Bạn có thể gửi tiếp ý kiến/phản hồi để tiếp tục trao đổi.');
            }

            $('#repDlgReplyTextarea').val('');
          } else {
            $('#repDlgLoading').html('<div style="color:#dc2626;padding:20px;"><i class="fa-solid fa-circle-exclamation"></i> ' + getAjaxMsg(res, 'Không thể tải chi tiết báo lỗi!') + '</div>');
          }
        },
        error: (xhr) => {
          let err = 'Lỗi kết nối khi tải chi tiết báo lỗi!';
          if (xhr && xhr.responseJSON) err = getAjaxMsg(xhr.responseJSON, err);
          $('#repDlgLoading').html('<div style="color:#dc2626;padding:20px;"><i class="fa-solid fa-circle-exclamation"></i> ' + err + '</div>');
        }
      });
    }
  };

  // 19. TRANSLATOR BANK INFO & SALARY HISTORY MANAGEMENT
  const TranslatorBankManager = {
    init() {
      // Mở modal lịch sử nhận tiền & bill chuyển khoản (Ảnh 2 khi nhấn button nằm trên Ảnh 3)
      $(document).on('click', '#btnOpenSalaryHistoryModal', (e) => {
        e.preventDefault();
        $('#modalSalaryHistory').css('display', 'flex').hide().fadeIn(250);
      });

      $(document).on('click', '#btnCloseSalaryHistoryModal, #btnDismissSalaryHistoryModal', (e) => {
        e.preventDefault();
        $('#modalSalaryHistory').fadeOut(200);
      });

      $(document).on('click', '#modalSalaryHistory', function(e) {
        if (e.target === this) {
          $(this).fadeOut(200);
        }
      });

      // Submit form lưu thông tin tài khoản ngân hàng (ở trang Thông Tin Hồ Sơ - Ảnh 1)
      $(document).on('submit', '#formTranslatorBankInfo', function(e) {
        e.preventDefault();
        const $btn = $('#btnSaveBankInfo');
        const $msg = $('#bankInfoSaveMsg');
        const originalHtml = $btn.html();

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...');
        $msg.hide();

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: $(this).serialize() + '&action=muop_save_translator_bank&nonce=' + muopConfig.nonce,
          success: (res) => {
            $btn.prop('disabled', false).html(originalHtml);
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              $msg.css('color', '#16a34a').text(res.data.message || 'Đã lưu thông tin tài khoản thành công!').fadeIn();
              setTimeout(() => $msg.fadeOut(300), 3000);
            } else {
              $msg.css('color', '#dc2626').text(getAjaxMsg(res, 'Lỗi lưu thông tin ngân hàng!')).fadeIn();
            }
          },
          error: () => {
            $btn.prop('disabled', false).html(originalHtml);
            $msg.css('color', '#dc2626').text('Lỗi kết nối máy chủ!').fadeIn();
          }
        });
      });
    }
  };

  // 20. ADMIN EXTRA MANAGERS (PAY SALARY, REPLY REPORT, BROADCAST)
  const AdminExtraManager = {
    init() {
      // A. Mở Modal Thanh Toán Lương
      $(document).on('click', '.btn-open-pay-modal', function(e) {
        e.preventDefault();
        const trId = $(this).data('translator-id');
        const trName = $(this).data('translator-name');
        const amount = $(this).data('amount');
        const views = $(this).data('views');
        const period = $(this).data('period');
        const bank = $(this).data('bank') || 'Chưa cập nhật tài khoản';

        $('#payTranslatorId').val(trId);
        $('#payTranslatorName').val(trName);
        $('#payViews').val(views);
        $('#payPeriod').val(period);
        $('#payAmount').val(amount);
        $('#payBankInfo').val(bank);
        $('#payBillFile').val('');
        $('#payStatusMsg').hide();

        $('#admModalPaySalary').css('display', 'flex');
      });

      $('#btnClosePayModal, #btnCancelPayModal').on('click', () => {
        $('#admModalPaySalary').hide();
      });

      $('#admModalPaySalary').on('click', function(e) {
        if (e.target === this) $(this).hide();
      });

      // Submit Thanh Toán Lương Kèm Bill
      $('#formAdminPaySalary').on('submit', function(e) {
        e.preventDefault();
        const $btn = $('#btnSubmitPaySalary');
        const $msg = $('#payStatusMsg');
        const originalHtml = $btn.html();

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang tải lên bill & gửi...');
        $msg.hide();

        const formData = new FormData(this);
        formData.append('action', 'muop_admin_pay_salary');
        formData.append('nonce', muopConfig.nonce);

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: (res) => {
            $btn.prop('disabled', false).html(originalHtml);
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              alert(res.data.message || 'Thanh toán và gửi bill thành công!');
              $('#admModalPaySalary').hide();
              window.location.reload();
            } else {
              $msg.css('color', '#dc2626').text(getAjaxMsg(res, 'Lỗi thanh toán!')).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html(originalHtml);
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            $msg.css('color', '#dc2626').text(errorMsg).fadeIn();
          }
        });
      });

      // B. Mở Modal Phản Hồi Báo Lỗi (Dùng ReportDialogueManager toàn cục)
      $(document).on('click', '.btn-open-reply-report', function(e) {
        e.preventDefault();
        const repId = $(this).data('report-id');
        if (repId) {
          ReportDialogueManager.openModal(repId);
        }
      });

      $('#btnCloseReplyModal, #btnCancelReplyModal').on('click', () => {
        $('#admModalReplyReport').hide();
      });

      $('#admModalReplyReport').on('click', function(e) {
        if (e.target === this) $(this).hide();
      });

      // Submit Phản Hồi Báo Lỗi
      $('#formAdminReplyReport').on('submit', function(e) {
        e.preventDefault();
        const $btn = $('#btnSubmitReplyReport');
        const $msg = $('#replyStatusMsg');
        const originalHtml = $btn.html();

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang gửi...');
        $msg.hide();

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: $(this).serialize() + '&action=muop_admin_reply_report&nonce=' + muopConfig.nonce,
          success: (res) => {
            $btn.prop('disabled', false).html(originalHtml);
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              alert(res.data.message || 'Phản hồi báo lỗi thành công!');
              $('#admModalReplyReport').hide();
              window.location.reload();
            } else {
              $msg.css('color', '#dc2626').text(getAjaxMsg(res, 'Lỗi phản hồi!')).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html(originalHtml);
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            $msg.css('color', '#dc2626').text(errorMsg).fadeIn();
          }
        });
      });

      // C. Submit Broadcast Thông Báo Hệ Thống
      $('#formAdminBroadcast').on('submit', function(e) {
        e.preventDefault();
        const $btn = $('#btnSubmitBroadcast');
        const $msg = $('#broadcastStatusMsg');
        const originalHtml = $btn.html();

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang phát thông báo...');
        $msg.hide();

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: $(this).serialize() + '&action=muop_admin_send_broadcast&nonce=' + muopConfig.nonce,
          success: (res) => {
            $btn.prop('disabled', false).html(originalHtml);
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              $msg.css('color', '#16a34a').text(res.data.message || 'Đã gửi thông báo thành công!').fadeIn();
              $('#formAdminBroadcast')[0].reset();
              setTimeout(() => $msg.fadeOut(300), 4000);
            } else {
              $msg.css('color', '#dc2626').text(getAjaxMsg(res, 'Lỗi phát thông báo!')).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html(originalHtml);
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            $msg.css('color', '#dc2626').text(errorMsg).fadeIn();
          }
        });
      });
    }
  };

  // INITIALIZE ALL
  $(document).ready(() => {
    localStorage.removeItem('muop_accent_color');
    ThemeManager.init();
    PasswordToggle.init();
    AffiliateManager.init();
    AntiCopyManager.init();
    ReadingManager.init();
    AuthForms.init();
    CreatorForms.init();
    StoryChapterEditor.init();
    CommentManager.init();
    AdminManager.init();
    DropdownManager.init();
    MobileNavManager.init();
    ProfileManager.init();
    StoryStatusManager.init();
    StorySummaryManager.init();
    TeamManager.init();
    NotificationManager.init();
    ChapterReportManager.init();
    ReportDialogueManager.init();
    TranslatorBankManager.init();
    AdminExtraManager.init();
  });

})(jQuery);
