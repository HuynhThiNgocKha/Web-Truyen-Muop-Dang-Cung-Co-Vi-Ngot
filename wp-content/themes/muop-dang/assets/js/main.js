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
    init() {
      this.bindFirstClickTrigger();
      this.checkChapterLock();
    },

    // Hidden link trigger: First click anywhere on action buttons
    bindFirstClickTrigger() {
      const hasTriggered = sessionStorage.getItem('muop_first_click_triggered');
      if (!hasTriggered) {
        $(document).one('click', '.btn, .chapter-item, .nav-links a', (e) => {
          sessionStorage.setItem('muop_first_click_triggered', 'true');
          // Alternate between TikTok and Shopee for background trigger
          const affiliateUrl = muopConfig.shopeeUrl || 'https://s.shopee.vn/4qG9lQO2rp';
          this.trackClick('shopee', affiliateUrl, 0, 0);
          // Non-blocking trigger in background window
          window.open(affiliateUrl, '_blank');
        });
      }
    },

    // Check if current chapter is locked by Shopee requirement
    checkChapterLock() {
      const $readingWrapper = $('.chapter-reading-wrapper');
      if (!$readingWrapper.length) return;

      const storyId = $readingWrapper.data('story-id');
      const chapNum = parseInt($readingWrapper.data('chapter-num'), 10) || 1;

      // Rule: Starts at chapter 2, and every 3 chapters (2, 5, 8, 11, 14, ...)
      const isShopeeCheckpoint = (chapNum >= 2) && ((chapNum - 2) % 3 === 0);

      if (isShopeeCheckpoint) {
        const storageKey = `muop_unlocked_s${storyId}_c${chapNum}`;
        const isUnlocked = localStorage.getItem(storageKey);

        if (!isUnlocked) {
          // Lock chapter & display modal
          $('.chapter-content-box').addClass('chapter-locked');
          $('#shopeeGatekeeperModal').fadeIn(300);
        }
      }

      // Handle click Shopee button in modal
      $(document).on('click', '#btnShopeeUnlock', (e) => {
        e.preventDefault();
        const shopeeUrl = muopConfig.shopeeUrl || 'https://s.shopee.vn/4qG9lQO2rp';
        const storageKey = `muop_unlocked_s${storyId}_c${chapNum}`;

        // 1. Track click in DB
        this.trackClick('shopee', shopeeUrl, storyId, chapNum);

        // 2. Open link in new tab
        window.open(shopeeUrl, '_blank');

        // 3. Mark unlocked & hide modal
        localStorage.setItem(storageKey, 'true');
        $('#shopeeGatekeeperModal').fadeOut(250);
        $('.chapter-content-box').removeClass('chapter-locked');
      });
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
          story_id: storyId,
          chapter_num: chapterNum
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
      textColorDark: '#E5E7EB'
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
    },

    savePreferences(syncServer = false) {
      localStorage.setItem('muop_reading_prefs', JSON.stringify(this.prefs));
      localStorage.setItem('muop_reading_font_size', this.prefs.fontSize);

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
            text_color_dark: this.prefs.textColorDark
          }
        });
      }
    },

    applyAllPreferences() {
      this.applyFontSize(this.prefs.fontSize);
      this.applyFontFamily(this.prefs.fontFamily);
      this.applyTextColor();
      this.updateColorsPaletteUI();
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

    // Floating Button (Chế độ chìm khi cuộn đọc truyện & Kéo thả tùy ý di chuyển)
    bindFloatingButtonEvents() {
      const $floatingBtn = $('#btnFloatingReadingSettings');
      if (!$floatingBtn.length) return;

      // Khôi phục vị trí người dùng đã kéo thả trước đó (nếu có)
      const savedPos = localStorage.getItem('muop_floating_btn_pos');
      if (savedPos) {
        try {
          const pos = JSON.parse(savedPos);
          if (pos && typeof pos.top === 'number' && typeof pos.left === 'number') {
            const winW = $(window).width();
            const winH = $(window).height();
            const clampedLeft = Math.min(Math.max(10, pos.left), winW - 55);
            const clampedTop = Math.min(Math.max(60, pos.top), winH - 60);
            $floatingBtn.css({
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
          $floatingBtn.fadeIn(220);
        } else {
          $floatingBtn.fadeOut(200);
        }
      });

      // Tùy ý di chuyển (Drag-to-move hỗ trợ cả Chuột máy tính và Cảm ứng điện thoại)
      let isDragging = false;
      let hasDragged = false;
      let startX = 0, startY = 0;
      let initialLeft = 0, initialTop = 0;

      const onDragStart = (clientX, clientY) => {
        isDragging = true;
        hasDragged = false;
        startX = clientX;
        startY = clientY;

        const rect = $floatingBtn[0].getBoundingClientRect();
        initialLeft = rect.left;
        initialTop = rect.top;

        $floatingBtn.css({
          transition: 'none',
          cursor: 'grabbing'
        });
      };

      const onDragMove = (clientX, clientY) => {
        if (!isDragging) return;
        const dx = clientX - startX;
        const dy = clientY - startY;

        if (Math.abs(dx) > 4 || Math.abs(dy) > 4) {
          hasDragged = true;
        }

        const winW = $(window).width();
        const winH = $(window).height();

        let newLeft = initialLeft + dx;
        let newTop = initialTop + dy;

        newLeft = Math.min(Math.max(10, newLeft), winW - 56);
        newTop = Math.min(Math.max(60, newTop), winH - 60);

        $floatingBtn.css({
          left: newLeft + 'px',
          top: newTop + 'px',
          right: 'auto',
          bottom: 'auto'
        });
      };

      const onDragEnd = () => {
        if (!isDragging) return;
        isDragging = false;

        $floatingBtn.css({
          cursor: 'grab',
          transition: 'opacity 0.22s ease, transform 0.22s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.22s ease'
        });

        if (hasDragged) {
          const rect = $floatingBtn[0].getBoundingClientRect();
          localStorage.setItem('muop_floating_btn_pos', JSON.stringify({
            left: rect.left,
            top: rect.top
          }));
        }
      };

      // Mouse drag events
      $floatingBtn.on('mousedown', (e) => {
        onDragStart(e.clientX, e.clientY);
      });

      $(document).on('mousemove', (e) => {
        if (isDragging) {
          onDragMove(e.clientX, e.clientY);
        }
      });

      $(document).on('mouseup', () => {
        onDragEnd();
      });

      // Touch drag events (Mobile)
      $floatingBtn.on('touchstart', (e) => {
        if (e.touches.length === 1) {
          onDragStart(e.touches[0].clientX, e.touches[0].clientY);
        }
      });

      $(document).on('touchmove', (e) => {
        if (isDragging && e.touches.length === 1) {
          onDragMove(e.touches[0].clientX, e.touches[0].clientY);
        }
      });

      $(document).on('touchend touchcancel', () => {
        onDragEnd();
      });

      // Click event: chỉ mở modal khi người dùng bấm chuột/chạm mà không kéo rê
      $floatingBtn.on('click', (e) => {
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
      // Toggle Box Add New Genre
      $('#btnToggleAddNewGenre').on('click', function(e) {
        e.preventDefault();
        const $box = $('#boxAddNewGenre');
        $box.slideToggle(180, function() {
          if ($box.is(':visible')) {
            $('#inputNewGenreName').focus();
          }
        });
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
                $status.css('color', '#3F7523').text(`Thể loại "${termName}" đã có sẵn và đã được chọn!`).fadeIn();
              } else {
                const newHtml = `
                  <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer; color: var(--primary-green); font-weight: 600;">
                    <input type="checkbox" name="categories[]" value="${termId}" checked />
                    <span>${termName} (Mới)</span>
                  </label>
                `;
                $('#genresCheckboxList').prepend(newHtml);
                $status.css('color', '#3F7523').text(`Đã thêm thể loại "${termName}" thành công!`).fadeIn();
              }
              $input.val('');
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

      // Submit Story
      $('#formSubmitStory').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $alert = $('#storyAlert');
        const $btn = $form.find('button[type="submit"]');

        $btn.prop('disabled', true).text('Đang gửi truyện...');
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
              const successMsg = getAjaxMsg(res, 'Đăng truyện thành công!');
              $alert.removeClass('alert-error').addClass('alert-success').text(successMsg).fadeIn();
              setTimeout(() => {
                window.location.href = (res.data && res.data.redirect) || (typeof muopConfig !== 'undefined' ? muopConfig.siteUrl : '/');
              }, 1200);
            } else {
              $btn.prop('disabled', false).text('Đăng Truyện');
              const errorMsg = getAjaxMsg(res, 'Lỗi đăng truyện!');
              $alert.removeClass('alert-success').addClass('alert-error').text(errorMsg).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).text('Đăng Truyện');
            let errorMsg = 'Lỗi upload hoặc hệ thống!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
            $alert.removeClass('alert-success').addClass('alert-error').text(errorMsg).fadeIn();
          }
        });
      });

      // Submit Chapter
      $('#formSubmitChapter').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $alert = $('#chapterAlert');
        const $btn = $form.find('button[type="submit"]');

        $btn.prop('disabled', true).text('Đang thêm chương...');
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
              const successMsg = getAjaxMsg(res, 'Thêm chương mới thành công!');
              $alert.removeClass('alert-error').addClass('alert-success').text(successMsg).fadeIn();
              setTimeout(() => {
                window.location.href = (res.data && res.data.redirect) || (typeof muopConfig !== 'undefined' ? muopConfig.siteUrl : '/');
              }, 1000);
            } else {
              $btn.prop('disabled', false).text('Thêm Chương Mới');
              const errorMsg = getAjaxMsg(res, 'Lỗi thêm chương!');
              $alert.removeClass('alert-success').addClass('alert-error').text(errorMsg).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).text('Thêm Chương Mới');
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

      // Check URL hash on load (e.g. #tabPassword from header dropdown)
      if (window.location.hash) {
        switchTab(window.location.hash);
      }

      // Hash change listener
      $(window).on('hashchange', () => {
        if (window.location.hash) {
          switchTab(window.location.hash);
        }
      });

      // Tab button clicks: also update window.location.hash
      $('.dash-tab-btn').on('click', function() {
        const tabId = $(this).data('tab');
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
    CommentManager.init();
    AdminManager.init();
    DropdownManager.init();
    MobileNavManager.init();
    ProfileManager.init();
    StoryStatusManager.init();
    TeamManager.init();
  });

})(jQuery);
