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

  // Bắt buộc gửi cookie xác thực phiên quản trị và header an toàn qua tất cả request AJAX của jQuery
  $.ajaxSetup({
    xhrFields: {
      withCredentials: true
    },
    beforeSend: function(xhr, settings) {
      if (typeof muopConfig !== 'undefined') {
        if (muopConfig.userId && parseInt(muopConfig.userId, 10) > 0) {
          xhr.setRequestHeader('X-Muop-User', muopConfig.userId);
        }
        if (muopConfig.sessionToken) {
          xhr.setRequestHeader('X-Muop-Token', muopConfig.sessionToken);
        }
        if (muopConfig.nonce) {
          xhr.setRequestHeader('X-WP-Nonce', muopConfig.nonce);
        }
      }
    }
  });

  // Chuẩn hóa AJAX URL luôn là relative path trên cùng origin để tránh mất cookie do domain/port mismatch
  function getAjaxUrl() {
    if (typeof muopConfig !== 'undefined' && muopConfig.ajaxUrl) {
      let u = muopConfig.ajaxUrl;
      try {
        if (u.indexOf('://') !== -1) {
          const parsed = new URL(u);
          return parsed.pathname;
        }
      } catch (e) {}
      return u;
    }
    return '/core/wp-admin/admin-ajax.php';
  }

  function getSafeAjaxUrl() {
    return getAjaxUrl();
  }

  // Safe helper to extract message from AJAX response or error (prevents undefined reading 'message')
  function getAjaxMsg(res, defaultMsg) {
    if (!res) return defaultMsg || 'Có lỗi xảy ra!';
    if (typeof res === 'string') {
      let clean = res.trim();
      // Remove any leaked PHP HTML notices/warnings
      if (clean.indexOf('PHP Request Startup') !== -1 || clean.indexOf('<b>Notice</b>') !== -1 || clean.indexOf('<b>Warning</b>') !== -1) {
        clean = clean.replace(/<br\s*\/?>\s*<b>(?:Notice|Warning|Deprecated)<\/b>:.*?<br\s*\/?>/gi, '').trim();
      }
      if (clean === '-1') return 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang (F5) và thử lại!';
      if (clean === '0' || clean.endsWith(' 0') || clean === '0') return 'Phiên đăng nhập đã hết hạn hoặc chưa đăng nhập. Vui lòng tải lại trang (F5)!';
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

  // Universal AJAX error handler for all network/HTTP failures
  function handleAjaxError(xhr, status, error, defaultMsg) {
    try {
      if (xhr && xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.require_login) {
        setTimeout(function() {
          const siteUrl = (typeof muopConfig !== 'undefined' && muopConfig.siteUrl) ? muopConfig.siteUrl : '';
          window.location.href = siteUrl + '/dang-nhap/?redirect_to=' + encodeURIComponent(window.location.href);
        }, 1500);
        return 'Phiên làm việc đã hết hạn. Đang chuyển hướng bạn đến trang đăng nhập...';
      }
      if (xhr && xhr.responseJSON) {
        return getAjaxMsg(xhr.responseJSON, defaultMsg);
      }
      if (xhr && xhr.responseText) {
        const raw = xhr.responseText.trim();
        if (raw === '-1' || raw === '0') {
          return 'Phiên làm việc hoặc bảo mật đã hết hạn. Vui lòng tải lại trang (F5)!';
        }
        const s = raw.indexOf('{');
        const e = raw.lastIndexOf('}');
        if (s !== -1 && e !== -1) {
          const parsed = JSON.parse(raw.substring(s, e + 1));
          return getAjaxMsg(parsed, defaultMsg);
        }
      }
    } catch(e) {}

    if (xhr && xhr.status === 403) {
      return 'Yêu cầu bị từ chối (403 Forbidden). Vui lòng tải lại trang hoặc kiểm tra quyền tài khoản!';
    }
    if (xhr && xhr.status === 404) {
      return 'Không tìm thấy máy chủ xử lý (404 Not Found). Vui lòng thử lại sau!';
    }
    if (xhr && (xhr.status === 504 || status === 'timeout')) {
      return 'Máy chủ phản hồi quá thời gian quy định (Timeout). Vui lòng thử lại!';
    }
    if (xhr && xhr.status === 500) {
      return 'Máy chủ gặp sự cố xử lý (500 Internal Error). Vui lòng thử lại sau!';
    }
    if (error && typeof error === 'string' && error.trim() && error.toLowerCase() !== 'error') {
      return 'Lỗi kết nối máy chủ: ' + error.trim();
    }
    return defaultMsg || 'Không thể kết nối với máy chủ. Vui lòng kiểm tra lại kết nối mạng hoặc thử lại sau!';
  }

  window.getAjaxUrl = getAjaxUrl;
  window.getSafeAjaxUrl = getSafeAjaxUrl;
  window.getAjaxMsg = getAjaxMsg;
  window.handleAjaxError = handleAjaxError;

  // Helper tính số từ & số ký tự dùng chung toàn website
  function countTextWordsAndChars(text) {
    if (!text) return { words: 0, chars: 0 };
    const clean = String(text).trim();
    const words = clean ? clean.split(/\s+/).filter(w => w.length > 0).length : 0;
    const chars = String(text).length;
    return { words, chars };
  }

  function updateRealtimeWordCounter($textarea, $wordBadge, $charBadge) {
    if (!$textarea || !$textarea.length) return;
    try {
      const val = $textarea.val() || '';
      const stats = countTextWordsAndChars(val);
      if ($wordBadge && $wordBadge.length) {
        $wordBadge.text(stats.words.toLocaleString('vi-VN'));
      }
      if ($charBadge && $charBadge.length) {
        $charBadge.text(stats.chars.toLocaleString('vi-VN'));
      }
    } catch (e) {
      console.warn('Word counter error:', e);
    }
  }

  window.muopCountWords = countTextWordsAndChars;
  window.muopUpdateWordCounter = updateRealtimeWordCounter;

  /* ==========================================================================
     GLOBAL POPUP MODAL DIALOG MANAGER (Thay thế hoàn toàn alert / confirm)
     ========================================================================== */
  const PopupManager = {
    $overlay: null,
    currentResolve: null,
    currentOptions: null,

    ensureElements() {
      if (this.$overlay && this.$overlay.length && $('#muopGlobalPopupModal').length) return;
      let $el = $('#muopGlobalPopupModal');
      if (!$el.length) {
        const modalHtml = `
          <div id="muopGlobalPopupModal" class="muop-popup-overlay" role="dialog" aria-modal="true">
            <div class="muop-popup-card">
              <button type="button" class="muop-popup-close-btn" id="muopPopupBtnClose" title="Đóng">&times;</button>
              <div class="muop-popup-icon-container">
                <div class="muop-popup-icon-circle muop-icon-success" id="muopPopupIconCircle">
                  <i class="fa-solid fa-circle-check" id="muopPopupIcon"></i>
                </div>
              </div>
              <h3 class="muop-popup-title" id="muopPopupTitle">Thông Báo</h3>
              <div class="muop-popup-message" id="muopPopupMessage"></div>
              <div class="muop-popup-footer">
                <button type="button" class="muop-popup-btn muop-popup-btn-cancel" id="muopPopupBtnCancel" style="display: none;">Hủy bỏ</button>
                <button type="button" class="muop-popup-btn muop-popup-btn-confirm" id="muopPopupBtnConfirm">Đã hiểu</button>
              </div>
            </div>
          </div>
        `;
        if (document.body) {
          $('body').append(modalHtml);
        } else {
          $(document).ready(() => $('body').append(modalHtml));
        }
        $el = $('#muopGlobalPopupModal');
      }
      this.$overlay = $el;

      // Event handlers
      $(document).off('click.muopPopupConfirm', '#muopPopupBtnConfirm').on('click.muopPopupConfirm', '#muopPopupBtnConfirm', () => {
        this.close(true);
      });

      $(document).off('click.muopPopupCancel', '#muopPopupBtnCancel, #muopPopupBtnClose').on('click.muopPopupCancel', '#muopPopupBtnCancel, #muopPopupBtnClose', () => {
        this.close(false);
      });

      $(document).off('click.muopPopupOverlay', '#muopGlobalPopupModal').on('click.muopPopupOverlay', '#muopGlobalPopupModal', (e) => {
        if (e.target && e.target.id === 'muopGlobalPopupModal') {
          this.close(false);
        }
      });

      $(document).off('keydown.muopPopupKey').on('keydown.muopPopupKey', (e) => {
        if (!this.$overlay || !this.$overlay.hasClass('is-active')) return;
        if (e.key === 'Escape') {
          this.close(false);
        } else if (e.key === 'Enter') {
          // If not focusing on cancel button, confirm
          if (document.activeElement && $(document.activeElement).is('#muopPopupBtnCancel')) {
            this.close(false);
          } else {
            this.close(true);
          }
        }
      });
    },

    detectType(msg) {
      if (!msg || typeof msg !== 'string') return 'info';
      const m = msg.toLowerCase();
      if (m.indexOf('thành công') !== -1 || m.indexOf('đã ẩn') !== -1 || m.indexOf('đã xóa') !== -1 || m.indexOf('đã lưu') !== -1 || m.indexOf('đã cập nhật') !== -1 || m.indexOf('đã duyệt') !== -1 || m.indexOf('đã gửi') !== -1 || m.indexOf('hoàn tất') !== -1) {
        return 'success';
      }
      if (m.indexOf('lỗi') !== -1 || m.indexOf('thất bại') !== -1 || m.indexOf('không thể') !== -1 || m.indexOf('không tồn tại') !== -1 || m.indexOf('không có quyền') !== -1 || m.indexOf('hết hạn') !== -1 || m.indexOf('từ chối') !== -1) {
        return 'error';
      }
      if (m.indexOf('chắc chắn') !== -1 || m.indexOf('xác nhận') !== -1 || m.indexOf('cảnh báo') !== -1 || m.indexOf('có muốn') !== -1) {
        return 'warning';
      }
      return 'info';
    },

    open(options) {
      this.ensureElements();
      this.currentOptions = options;

      if (options.maxWidth) {
        this.$overlay.find('.muop-popup-card').css('max-width', options.maxWidth);
      } else {
        this.$overlay.find('.muop-popup-card').css('max-width', '');
      }

      const type = options.type || this.detectType(options.message);
      const isConfirm = !!options.isConfirm;

      // Icons & Colors
      const $circle = $('#muopPopupIconCircle');
      const $icon = $('#muopPopupIcon');
      $circle.removeClass('muop-icon-success muop-icon-error muop-icon-warning muop-icon-info');

      let iconClass = 'fa-circle-info';
      let defaultTitle = 'Thông Báo';

      if (options.isDanger) {
        $circle.addClass('muop-icon-error');
        iconClass = options.icon || 'fa-trash-can';
        defaultTitle = 'Xác Nhận Xóa';
      } else if (type === 'success') {
        $circle.addClass('muop-icon-success');
        iconClass = options.icon || 'fa-circle-check';
        defaultTitle = 'Thành Công';
      } else if (type === 'error') {
        $circle.addClass('muop-icon-error');
        iconClass = options.icon || 'fa-circle-xmark';
        defaultTitle = 'Thông Báo Lỗi';
      } else if (type === 'warning' || isConfirm) {
        $circle.addClass('muop-icon-warning');
        iconClass = options.icon || (isConfirm ? 'fa-circle-question' : 'fa-triangle-exclamation');
        defaultTitle = isConfirm ? 'Xác Nhận Thao Tác' : 'Cảnh Báo';
      } else {
        $circle.addClass('muop-icon-info');
        iconClass = options.icon || 'fa-circle-info';
        defaultTitle = 'Thông Báo';
      }

      $icon.attr('class', 'fa-solid ' + iconClass);
      $('#muopPopupTitle').text(options.title || defaultTitle);
      $('#muopPopupMessage').html(options.message || '');

      const $btnConfirm = $('#muopPopupBtnConfirm');
      const $btnCancel = $('#muopPopupBtnCancel');

      if (options.hideButtons) {
        $btnConfirm.hide();
        $btnCancel.hide();
      } else {
        $btnConfirm.show().text(options.confirmText || (isConfirm ? 'Xác Nhận' : 'Đã Hiểu'));
        if (options.isDanger) {
          $btnConfirm.addClass('btn-danger-confirm');
        } else {
          $btnConfirm.removeClass('btn-danger-confirm');
        }

        if (isConfirm) {
          $btnCancel.show().text(options.cancelText || 'Hủy Bỏ');
        } else {
          $btnCancel.hide();
        }
      }

      $('#muopPopupBtnClose').toggle(!options.hideCloseBtn);

      this.$overlay.addClass('is-active');
      setTimeout(() => {
        $btnConfirm.focus();
      }, 50);

      return new Promise((resolve) => {
        this.currentResolve = resolve;
      });
    },

    close(result) {
      if (!this.$overlay || !this.$overlay.hasClass('is-active')) return;
      this.$overlay.removeClass('is-active');

      const opts = this.currentOptions || {};
      const resolve = this.currentResolve;
      this.currentResolve = null;
      this.currentOptions = null;

      if (result) {
        if (typeof opts.onConfirm === 'function') {
          try { opts.onConfirm(); } catch (err) { console.error(err); }
        }
      } else {
        if (typeof opts.onCancel === 'function') {
          try { opts.onCancel(); } catch (err) { console.error(err); }
        }
      }

      if (typeof resolve === 'function') {
        resolve(result);
      }
    }
  };

  window.muopAlert = function(message, typeOrCallback, callback) {
    let type = null;
    let onOk = null;
    if (typeof typeOrCallback === 'function') {
      onOk = typeOrCallback;
    } else if (typeof typeOrCallback === 'string') {
      type = typeOrCallback;
      if (typeof callback === 'function') onOk = callback;
    } else if (typeof typeOrCallback === 'object' && typeOrCallback !== null) {
      return PopupManager.open(Object.assign({ message: message }, typeOrCallback));
    }
    return PopupManager.open({
      message: message,
      type: type,
      isConfirm: false,
      onConfirm: onOk
    });
  };

  window.muopConfirm = function(message, onConfirm, onCancel, options) {
    const opts = Object.assign({
      message: message,
      isConfirm: true,
      onConfirm: onConfirm,
      onCancel: onCancel,
      type: 'warning'
    }, options || {});
    return PopupManager.open(opts);
  };

  // Override window.alert để bất kỳ mã nguồn nào gọi alert() cũng hiển thị popup đồng bộ với theme
  window.alert = function(msg, callback) {
    return window.muopAlert(msg, callback);
  };

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

    // Kiểm tra xem chương này có phải là checkpoint có link Shopee hay không
    // - Nếu truyện có 3 hoặc 4 chương (hoặc <= 4): Xuất hiện ở chương 1 và chương 3
    // - Nếu truyện có từ 5 chương trở lên: Xuất hiện ở chương 2, 5, 8, 11... (cứ cách 3 chương 1 lần)
    isCheckpoint(chapNum, totalChapters) {
      if (typeof muopConfig !== 'undefined' && muopConfig.isAdmin) return false;
      chapNum = parseInt(chapNum, 10) || 1;
      totalChapters = parseInt(totalChapters, 10) || 1;

      if (totalChapters <= 4) {
        return (chapNum === 1 || chapNum === 3);
      }
      return (chapNum >= 2 && (chapNum % 3 === 2));
    },

    // Kiểm tra xem chương đã được mở khóa trước đó chưa (trong localStorage hoặc sessionStorage)
    // Đã nhấn 1 lần rồi thì không cần nhấn lần 2
    isUnlocked(storyId, chapterId, chapNum) {
      if (typeof muopConfig !== 'undefined' && muopConfig.isAdmin) return true;
      try {
        if (chapterId) {
          if (localStorage.getItem(`muop_unlocked_chap_${chapterId}`) || sessionStorage.getItem(`muop_unlocked_chap_${chapterId}`)) {
            return true;
          }
        }
        if (storyId && chapNum) {
          if (localStorage.getItem(`muop_unlocked_s${storyId}_c${chapNum}`) || sessionStorage.getItem(`muop_unlocked_s${storyId}_c${chapNum}`)) {
            return true;
          }
        }
      } catch (e) {}
      return false;
    },

    // Đánh dấu đã mở khóa vĩnh viễn (nhấn 1 lần, không hỏi lại lần 2)
    markUnlocked(storyId, chapterId, chapNum) {
      try {
        if (chapterId) {
          localStorage.setItem(`muop_unlocked_chap_${chapterId}`, 'true');
          sessionStorage.setItem(`muop_unlocked_chap_${chapterId}`, 'true');
        }
        if (storyId && chapNum) {
          localStorage.setItem(`muop_unlocked_s${storyId}_c${chapNum}`, 'true');
          sessionStorage.setItem(`muop_unlocked_s${storyId}_c${chapNum}`, 'true');
        }
      } catch (e) {}
    },

    init() {
      // 1. ADMIN: Tuyệt đối không bao giờ hiển thị popup Shopee hay khóa chương
      if (typeof muopConfig !== 'undefined' && muopConfig.isAdmin) {
        $('.chapter-content-box').removeClass('chapter-locked chapter-locked-init');
        $('#shopeeGatekeeperModal').remove();
        return;
      }

      this.bindChapterListInterceptors();
      this.bindUnlockButton();
      this.checkChapterLock();
    },

    // Bắt sự kiện khi click vào link chương trên trang truyện hoặc thanh điều hướng
    bindChapterListInterceptors() {
      $(document).on('click', '.chapter-item, .btn-read-latest, .chapter-drawer-link, .btn-next-chapter', function(e) {
        // 1. Admin hoàn toàn không bao giờ bị chặn Shopee
        if (typeof muopConfig !== 'undefined' && muopConfig.isAdmin) return;

        const $el = $(this);
        const href = $el.attr('href');
        if (!href || href === '#' || href.startsWith('javascript:')) return;

        const storyId = parseInt($el.data('story-id'), 10) || parseInt($('.chapter-reading-wrapper').data('story-id'), 10) || 0;
        const chapterId = parseInt($el.data('chapter-id'), 10) || 0;
        const chapNum = parseInt($el.data('chapter-num'), 10) || 1;
        const totalChapters = parseInt($el.data('total-chapters'), 10) || 0;
        const checkpointAttr = $el.data('is-checkpoint');

        // 2. Dịch giả xem/đọc truyện của CHÍNH MÌNH: không bao giờ bị khóa hoặc chặn Shopee
        const isOwnStory = ($el.data('is-own-story') == 1 || $el.data('is-own-story') === '1' || $el.data('is-own-story') === true) ||
                           ($el.closest('[data-is-own-story="1"]').length > 0) ||
                           ($('.chapter-reading-wrapper[data-is-own-story="1"]').length > 0) ||
                           ($('.story-detail-wrapper[data-is-own-story="1"], .single-story-wrapper[data-is-own-story="1"], .story-detail-container[data-is-own-story="1"]').length > 0) ||
                           (typeof muopConfig !== 'undefined' && Array.isArray(muopConfig.userStoryIds) && storyId && muopConfig.userStoryIds.indexOf(storyId) !== -1);

        if (isOwnStory) {
          return;
        }

        // Xác định xem chương này có phải là checkpoint có link Shopee không:
        // 1. Ưu tiên thuộc tính data-is-checkpoint từ server
        // 2. Nếu không có, tự tính toán theo quy tắc
        let isChk = false;
        if (typeof checkpointAttr !== 'undefined' && checkpointAttr !== '') {
          isChk = (checkpointAttr === 1 || checkpointAttr === '1');
        } else {
          isChk = AffiliateManager.isCheckpoint(chapNum, totalChapters);
        }

        // NẾU KHÔNG PHẢI CHƯƠNG CHECKPOINT -> ĐỌC MIỄN PHÍ TRỰC TIẾP, KHÔNG CHẶN
        if (!isChk) {
          return;
        }

        // NẾU LÀ CHƯƠNG CHECKPOINT: Kiểm tra xem đã nhấn mở khóa chưa (chỉ cần nhấn 1 lần)
        if (AffiliateManager.isUnlocked(storyId, chapterId, chapNum)) {
          // Đã nhấn trước đó -> Cho phép vào đọc ngay lập tức, không hỏi lại
          return;
        }

        // CHƯA MỞ KHÓA -> CHẶN LẠI VÀ HIỆN POPUP SHOPEE
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

      // 3. Nút đóng modal Shopee
      $(document).on('click', '#btnCloseShopeeModal', function(e) {
        e.preventDefault();
        $('#shopeeGatekeeperModal').fadeOut(200);
        AffiliateManager.pendingUnlock = null;
      });

      // 4. Click ra nền mờ đóng modal
      $(document).on('click', '#shopeeGatekeeperModal', function(e) {
        if (e.target === this) {
          $(this).fadeOut(200);
          AffiliateManager.pendingUnlock = null;
        }
      });
    },

    // Xử lý khi độc giả bấm vào nút mở link Shopee để mở khóa (chỉ cần nhấn 1 lần)
    bindUnlockButton() {
      $(document).on('click', '#btnShopeeUnlock', function(e) {
        // KHÔNG gọi e.preventDefault() để trình duyệt mở link Shopee trong tab mới theo thuộc tính target="_blank" tự nhiên, không bao giờ bị popup blocker chặn
        const $btn = $(this);
        const shopeeUrl = $btn.attr('href') || (typeof muopConfig !== 'undefined' && muopConfig.shopeeUrl) || 'https://s.shopee.vn/5q8lDzjzRv';

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

        // 1. Ghi nhận lượt click Shopee qua AJAX
        AffiliateManager.trackClick('shopee', shopeeUrl, storyId, chapNum);

        // 2. Đánh dấu đã mở khóa vĩnh viễn (localStorage & sessionStorage) -> Chỉ cần nhấn 1 lần!
        AffiliateManager.markUnlocked(storyId, chapterId, chapNum);

        // 3. Mở khóa nội dung trên trang đọc ngay lập tức (nếu đang ở trang đọc)
        $('.chapter-content-box').removeClass('chapter-locked chapter-locked-init');

        // 4. Nếu có targetUrl (độc giả vừa bấm từ danh sách chương): chuyển trang sau khi đã kích hoạt mở tab Shopee
        if (pending && pending.targetUrl) {
          $('#shopeeGatekeeperModal').fadeOut(200);
          const target = pending.targetUrl;
          AffiliateManager.pendingUnlock = null;
          setTimeout(() => {
            window.location.href = target;
          }, 350);
          return;
        }

        // 5. Nếu đang ở chính trang đọc chương này (single-chuong.php):
        $('#shopeeGatekeeperModal').fadeOut(250);
      });
    },

    // Kiểm tra trạng thái khóa khi vừa tải trang đọc chương (single-chuong.php)
    checkChapterLock() {
      // 1. Admin không bao giờ bị khóa
      if (typeof muopConfig !== 'undefined' && muopConfig.isAdmin) {
        $('.chapter-content-box').removeClass('chapter-locked chapter-locked-init');
        $('#shopeeGatekeeperModal').hide();
        return;
      }

      const $readingWrapper = $('.chapter-reading-wrapper');
      if (!$readingWrapper.length) return;

      const storyId = parseInt($readingWrapper.data('story-id'), 10) || 0;
      const chapterId = parseInt($readingWrapper.data('chapter-id'), 10) || 0;
      const chapNum = parseInt($readingWrapper.data('chapter-num'), 10) || 1;
      const totalChapters = parseInt($readingWrapper.data('total-chapters'), 10) || 0;
      const checkpointAttr = $readingWrapper.data('is-checkpoint');

      // 2. Dịch giả xem/đọc truyện của CHÍNH MÌNH: không bao giờ bị khóa
      const isOwnStory = ($readingWrapper.data('is-own-story') == 1 || $readingWrapper.data('is-own-story') === '1' || $readingWrapper.data('is-own-story') === true) ||
                         (typeof muopConfig !== 'undefined' && Array.isArray(muopConfig.userStoryIds) && storyId && muopConfig.userStoryIds.indexOf(storyId) !== -1);

      if (isOwnStory) {
        $('.chapter-content-box').removeClass('chapter-locked chapter-locked-init');
        $('#shopeeGatekeeperModal').hide();
        return;
      }

      // Xác định chương này có phải checkpoint cần link Shopee không
      let isChk = false;
      if (typeof checkpointAttr !== 'undefined' && checkpointAttr !== '') {
        isChk = (checkpointAttr === 1 || checkpointAttr === '1');
      } else {
        isChk = this.isCheckpoint(chapNum, totalChapters);
      }

      // Nếu KHÔNG phải checkpoint -> Mở nội dung đọc trực tiếp
      if (!isChk) {
        $('.chapter-content-box').removeClass('chapter-locked chapter-locked-init');
        $('#shopeeGatekeeperModal').hide();
        return;
      }

      // Nếu LÀ checkpoint -> Kiểm tra xem đã mở khóa chưa (chỉ cần nhấn 1 lần)
      if (this.isUnlocked(storyId, chapterId, chapNum)) {
        // Đã nhấn mở khóa rồi -> Không hỏi lần 2, mở đọc ngay
        $('.chapter-content-box').removeClass('chapter-locked chapter-locked-init');
        $('#shopeeGatekeeperModal').hide();
      } else {
        // Chưa nhấn -> Khóa và hiển thị Popup Shopee
        $('.chapter-content-box').addClass('chapter-locked');
        $('#shopeeGatekeeperModal').css('display', 'flex').hide().fadeIn(300);
      }
    },

    // Track click via AJAX
    trackClick(type, url, storyId, chapterNum) {
      if (typeof muopConfig === 'undefined' || !muopConfig.ajaxUrl) return;
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

  // 3.1 TIKTOK SHOP HIDDEN BACKGROUND JUMP MANAGER
  const TikTokJumpManager = {
    config: {
      storageKeyFirst: 'muop_tt_first_clicked',
      storageKeyLastTs: 'muop_tt_last_trigger_ts',

      // Thời gian giãn cách tối thiểu giữa các lần nhảy link TikTok: 7 phút (7 * 60 * 1000 = 420.000 ms)
      cooldownMs: 7 * 60 * 1000
    },

    init() {
      // 1. ADMIN: Tuyệt đối không bao giờ xuất hiện quảng cáo hay nhảy link TikTok Shop ở bất kỳ đâu
      if (typeof muopConfig !== 'undefined' && muopConfig.isAdmin) {
        return;
      }

      // 2. Không bao giờ kích hoạt trên trang Quản lý Admin
      if (window.location.pathname.indexOf('/quan-ly-admin') !== -1) {
        return;
      }

      // 3. Không kích hoạt trên trang Đăng Truyện (/dang-truyen)
      if (window.location.pathname.indexOf('/dang-truyen') !== -1) {
        return;
      }

      // 4. Không kích hoạt trên trang Thêm Chương (/them-chuong)
      if (window.location.pathname.indexOf('/them-chuong') !== -1) {
        return;
      }

      // 5. Không kích hoạt trên Kênh Dịch Giả / Thông tin dịch giả (/thong-tin-dich-gia)
      if (window.location.pathname.indexOf('/thong-tin-dich-gia') !== -1) {
        return;
      }

      this.bindTriggers();
    },

    getTikTokUrl() {
      return (typeof muopConfig !== 'undefined' && muopConfig.tiktokUrl)
        ? muopConfig.tiktokUrl
        : 'https://shop.tiktok.com/vn/pdp/1732477773040355077?_t=ZS-9AM5BidmKfQ';
    },

    getCooldownMs() {
      if (typeof muopConfig !== 'undefined' && muopConfig.tiktokCooldownMinutes) {
        const mins = parseInt(muopConfig.tiktokCooldownMinutes, 10);
        if (mins > 0) return mins * 60 * 1000;
      }
      return this.config.cooldownMs; // Mặc định 7 phút
    },

    shouldTrigger(isChapterNav) {
      const now = Date.now();
      const lastTs = parseInt(localStorage.getItem(this.config.storageKeyLastTs), 10) || 0;

      // 1. Lần đầu tiên người dùng tương tác -> Kích hoạt nhảy link lần 1
      if (!lastTs) {
        return true;
      }

      // 2. Kiểm tra khoảng thời gian đã trôi qua kể từ lần nhảy trước đó
      const cooldownMs = this.getCooldownMs();
      const elapsed = now - lastTs;

      // Hạn chế nghiêm ngặt: Phải đúng sau 7 phút (420.000 ms) mới cho nhảy lần tiếp theo
      if (elapsed >= cooldownMs) {
        return true;
      }

      // Nếu chưa đủ 7 phút: Tuyệt đối KHÔNG nhảy link dù click bao nhiêu lần
      return false;
    },

    recordTrigger() {
      const now = Date.now();
      localStorage.setItem(this.config.storageKeyFirst, '1');
      localStorage.setItem(this.config.storageKeyLastTs, now.toString());
    },

    openLinkSafely(url) {
      if (!url) return;
      let opened = false;
      try {
        const win = window.open(url, '_blank');
        if (win && !win.closed && typeof win.closed !== 'undefined') {
          opened = true;
        }
      } catch (err) {}

      // Dự phòng cho trình duyệt di động (iOS Safari, Android Chrome) khi window.open bị popup blocker chặn
      if (!opened) {
        try {
          const a = document.createElement('a');
          a.href = url;
          a.target = '_blank';
          a.rel = 'noopener noreferrer';
          document.body.appendChild(a);
          a.click();
          setTimeout(() => {
            try { document.body.removeChild(a); } catch (e) {}
          }, 300);
        } catch (e) {}
      }
    },

    triggerJump(storyId, chapterNum) {
      const tiktokUrl = this.getTikTokUrl();
      if (!tiktokUrl) return;

      this.recordTrigger();

      // Mở liên kết TikTok Shop trong tab mới
      this.openLinkSafely(tiktokUrl);

      // Ghi nhận lượt click affiliate TikTok vào hệ thống thống kê
      if (typeof AffiliateManager !== 'undefined' && typeof AffiliateManager.trackClick === 'function') {
        AffiliateManager.trackClick('tiktok', tiktokUrl, storyId || 0, chapterNum || 0);
      }
    },

    bindTriggers() {
      const self = this;
      const btnSelector = 'button, .btn, a.btn, input[type="button"], input[type="submit"], [role="button"], .btn-chap-prev, .btn-chap-next, .btn-next-chapter, .chapter-item, .chapter-drawer-link';

      $(document).on('click', btnSelector, function(e) {
        // Ngăn chặn sự kiện bị xử lý nhiều lần trên cùng 1 lượt click
        if (e._ttJumpHandled) return;
        e._ttJumpHandled = true;

        // 1. ADMIN: Tuyệt đối không bao giờ kích hoạt nhảy link TikTok Shop ở bất kỳ đâu
        if (typeof muopConfig !== 'undefined' && muopConfig.isAdmin) {
          return;
        }

        // 2. Không kích hoạt trên các trang công việc của Dịch Giả / Admin
        const currentPath = window.location.pathname;
        if (currentPath.indexOf('/dang-truyen') !== -1 ||
            currentPath.indexOf('/them-chuong') !== -1 ||
            currentPath.indexOf('/thong-tin-dich-gia') !== -1 ||
            currentPath.indexOf('/quan-ly-admin') !== -1) {
          return;
        }

        const $el = $(this);

        // Bỏ qua nếu button đang bị vô hiệu hóa
        if ($el.prop('disabled') || $el.hasClass('disabled') || $el.attr('aria-disabled') === 'true') {
          return;
        }

        // Bỏ qua nếu thao tác thuộc Modal mở khóa Shopee
        if ($el.closest('#shopeeGatekeeperModal').length || $el.is('#btnShopeeUnlock, #btnCloseShopeeModal')) {
          return;
        }

        // Bỏ qua nếu thao tác trong thanh tác vụ admin
        if ($el.closest('.admin-sidebar, .admin-main-wrap, .adm-modal, #wpadminbar').length) {
          return;
        }

        // 3. DUYỆT NỘI DUNG: Không bao giờ nhảy link khi bấm nút Duyệt Nội Dung
        if ($el.hasClass('btn-scan-sensitive') || $el.closest('.btn-scan-sensitive').length > 0 || $el.is('[data-target*="Description"], [data-target*="Content"]')) {
          return;
        }

        // 4. ĐĂNG TRUYỆN, THÊM CHƯƠNG, CHỈNH SỬA TRUYỆN: Không bao giờ nhảy link khi đang thao tác trong form hoặc modal
        if ($el.closest('#formSubmitStory, #formAddChapter, #formEditStoryMaster, #formEditChapterModal, #formQuickEditStory, #adminQuickEditForm').length > 0) {
          return;
        }

        if ($el.closest('#modalEditStoryInfo, #modalEditChapterContent, #modalQuickEditStory, #modalDeleteChapterConfirm, #adminQuickEditModal, #modalSensitiveWordReview, .custom-modal-backdrop, .modal-backdrop, .translator-dashboard-wrap, .author-manage-box, .chapter-actions-dropdown, .custom-cover-upload-area, #boxAddNewGenre').length > 0) {
          return;
        }

        if ($el.is('.btn-trigger-edit-chapter, .btn-trigger-delete-chapter, .btn-chapter-dropdown-trigger, .btn-edit-story, .btn-quick-edit, #btnSaveDraftNewStory, #btnSubmitPublishStory, #btnUploadCover, #btnRemoveCover, #btnToggleAddNewGenre, #btnConfirmAddGenre, #btnCancelAddGenre, #btnSaveDraftChapterAction, #btnUpdateChapterAction, #btnConfirmDeleteChapter, #btnCancelDeleteChapter')) {
          return;
        }

        // Bỏ qua nếu là link dẫn trực tiếp tới shopee hoặc tiktok
        const href = $el.attr('href') || '';
        if (href.indexOf('shopee.vn') !== -1 || href.indexOf('tiktok.com') !== -1) {
          return;
        }

        // 5. TRUYỆN CỦA CHÍNH DỊCH GIẢ: Dịch giả đọc, xem hoặc thao tác trên truyện của mình KHÔNG BAO GIỜ bị nhảy link
        let storyId = parseInt($el.data('story-id'), 10) || 0;
        let chapterNum = parseInt($el.data('chapter-num'), 10) || 0;
        if (!storyId) {
          const $rw = $('.chapter-reading-wrapper');
          if ($rw.length) {
            storyId = parseInt($rw.data('story-id'), 10) || 0;
            chapterNum = parseInt($rw.data('chapter-num'), 10) || 0;
          }
        }
        if (!storyId) {
          const $sw = $('.story-detail-wrapper, .story-detail-container, .single-story-wrapper');
          if ($sw.length) {
            storyId = parseInt($sw.data('story-id'), 10) || 0;
          }
        }

        const isOwnStory = ($el.data('is-own-story') == 1 || $el.data('is-own-story') === '1' || $el.data('is-own-story') === true) ||
                           ($el.closest('[data-is-own-story="1"]').length > 0) ||
                           ($('.chapter-reading-wrapper[data-is-own-story="1"]').length > 0) ||
                           ($('.story-detail-wrapper[data-is-own-story="1"], .single-story-wrapper[data-is-own-story="1"], .story-detail-container[data-is-own-story="1"]').length > 0) ||
                           (typeof muopConfig !== 'undefined' && Array.isArray(muopConfig.userStoryIds) && storyId && muopConfig.userStoryIds.indexOf(storyId) !== -1);

        if (isOwnStory) {
          return;
        }

        // Xác định xem có phải nút chuyển chương (Chương trước / Chương sau / Danh sách chương) hay không
        const isChapterNav = $el.is('.btn-chap-prev, .btn-chap-next, .btn-next-chapter, .chapter-item, .chapter-drawer-link') ||
                             $el.closest('.chapter-nav-btns, .reading-toolbar-bottom').length > 0;

        // Nếu là nút Chương Sau mà chương đó đang bị Shopee khóa (chưa mở khóa) -> ưu tiên Shopee mở khóa trước
        if ($el.hasClass('btn-next-chapter') && typeof AffiliateManager !== 'undefined') {
          const sId = parseInt($el.data('story-id'), 10) || 0;
          const cId = parseInt($el.data('chapter-id'), 10) || 0;
          const cNum = parseInt($el.data('chapter-num'), 10) || 1;
          const totalChaps = parseInt($el.data('total-chapters'), 10) || 0;
          const isChkAttr = $el.data('is-checkpoint');
          let isChk = (typeof isChkAttr !== 'undefined' && isChkAttr !== '') ? (isChkAttr == 1) : AffiliateManager.isCheckpoint(cNum, totalChaps);
          if (isChk && !AffiliateManager.isUnlocked(sId, cId, cNum)) {
            return;
          }
        }

        // Kiểm tra điều kiện mở TikTok
        if (self.shouldTrigger(isChapterNav)) {
          // Nếu là thẻ <a> có link chuyển trang (như Chương trước / Chương sau):
          // Cần mở tab TikTok trước rồi hoãn chuyển trang 250ms để trình duyệt di động không hủy tab mới
          if ($el.is('a') && href && !href.startsWith('#') && !href.startsWith('javascript:')) {
            e.preventDefault();
            self.triggerJump(storyId, chapterNum);
            setTimeout(() => {
              window.location.href = href;
            }, 250);
            return;
          }

          // Đối với các button thông thường (không chuyển URL trực tiếp):
          self.triggerJump(storyId, chapterNum);
        }
      });
    }
  };

  // 4. ANTI-COPY & ANTI-CRAWLER PROTECTION (BẢO VỆ BẢN QUYỀN TRUYỆN)
  const AntiCopyManager = {
    init() {
      const $box = $('.chapter-content-box');
      const isReadingPage = $box.length > 0 || $('.chapter-reading-wrapper').length > 0;
      if (!isReadingPage) return;

      // 1. Phát hiện trình duyệt tự động Headless (Puppeteer, Selenium, Playwright, HeadlessChrome)
      if (
        navigator.webdriver ||
        window.__nightmare ||
        window._phantom ||
        window.callPhantom ||
        window.__selenium_evaluate ||
        window.__webdriver_evaluate ||
        window.__driver_evaluate
      ) {
        $('.chapter-body-text').html(
          '<div style="padding:40px 20px;text-align:center;color:#ef4444;font-size:16px;line-height:1.6;font-weight:600;">' +
          '<i class="fa-solid fa-triangle-exclamation" style="font-size:32px;margin-bottom:12px;display:block;"></i>' +
          'Phát hiện công cụ tự động hóa (Automation / Headless Scraper).<br>' +
          'Để bảo vệ tác quyền, vui lòng truy cập và đọc truyện bằng trình duyệt thông thường!' +
          '</div>'
        );
        return;
      }

      // 2. Thuật toán cắt ngắn văn bản: Chỉ cho phép copy 1 vài chữ (3-4 từ) trong mỗi đoạn
      const truncateParagraphs = (rawText) => {
        if (!rawText || typeof rawText !== 'string') return '';
        const lines = rawText.split(/\r?\n/);
        const processed = lines.map(line => {
          const trimmed = line.trim();
          if (!trimmed) return '';
          
          // Tách các từ trong đoạn
          const words = trimmed.split(/\s+/).filter(Boolean);
          if (words.length === 0) return '';
          
          // Giữ lại 3 từ đầu tiên của mỗi đoạn, phần còn lại bị cắt bỏ
          const limit = Math.min(3, words.length);
          const firstWords = words.slice(0, limit).join(' ');
          
          return firstWords + '... [Nội dung đã được bảo hộ bản quyền bởi Mướp Đắng - Không sao chép toàn bộ]';
        });
        
        return processed.filter(Boolean).join('\n\n');
      };

      // 3. Can thiệp sự kiện Copy ở Capture Phase (Chạy trước mọi Extension "Allow Copy" hay script thứ ba)
      document.addEventListener('copy', function(e) {
        try {
          const selection = window.getSelection();
          let selectedText = selection ? selection.toString() : '';

          // Nếu không có vùng chọn nhưng có sự kiện copy kích hoạt trên trang đọc
          if (!selectedText) {
            const bodyEl = document.querySelector('.chapter-body-text');
            if (bodyEl) {
              selectedText = bodyEl.innerText || bodyEl.textContent || '';
            }
          }

          if (selectedText) {
            const truncated = truncateParagraphs(selectedText);
            if (e.clipboardData) {
              e.clipboardData.setData('text/plain', truncated);
              e.clipboardData.setData('text/html', '<p>' + truncated.split(/\n\n+/).join('</p><p>') + '</p>');
            }
          }
        } catch (err) {
          // Bỏ qua lỗi ngầm
        }

        e.preventDefault();
        e.stopImmediatePropagation();
        return false;
      }, true); // useCapture: true

      // 4. Hook API navigator.clipboard.writeText đề phòng tool/extension ghi trực tiếp clipboard
      if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
        const originalWriteText = navigator.clipboard.writeText.bind(navigator.clipboard);
        navigator.clipboard.writeText = function(text) {
          try {
            const truncated = truncateParagraphs(text);
            return originalWriteText(truncated);
          } catch (e) {
            return originalWriteText(text);
          }
        };
      }

      // 5. Chặn chuột phải (Context Menu)
      $box.on('contextmenu', function(e) {
        e.preventDefault();
        e.stopPropagation();
        return false;
      });

      // 6. Chặn chọn khối (selectstart), kéo thả (dragstart)
      $box.on('selectstart dragstart', function(e) {
        e.preventDefault();
        return false;
      });

      // 7. Chặn tổ hợp phím tắt: Ctrl+C, Ctrl+A, Ctrl+X, Ctrl+U, Ctrl+S, Ctrl+P, F12, DevTools
      $(document).on('keydown', function(e) {
        const isCtrlOrCmd = e.ctrlKey || e.metaKey;
        const key = (e.key || '').toLowerCase();

        // Chặn phím tắt copy, select all, cut, view-source, save, print
        if (isCtrlOrCmd && (key === 'c' || key === 'a' || key === 'x' || key === 'u' || key === 's' || key === 'p')) {
          e.preventDefault();
          e.stopPropagation();
          return false;
        }

        // Chặn F12 và Ctrl+Shift+I / J / C (Mở DevTools)
        if (e.key === 'F12' || (isCtrlOrCmd && e.shiftKey && (key === 'i' || key === 'j' || key === 'c'))) {
          e.preventDefault();
          e.stopPropagation();
          return false;
        }
      });
    },

    showNotice(msg) {
      // Ẩn thông báo popup để không gây gián đoạn trải nghiệm đọc của độc giả
      return;
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
      this.bindAutoHideHeaderOnScroll();

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
          $('.site-header').removeClass('header-hidden-reading');
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

    // Auto-hide Header khi kéo/cuộn đọc truyện để tối ưu hóa không gian đọc
    bindAutoHideHeaderOnScroll() {
      const $readingWrap = $('.chapter-reading-wrapper');
      const isReadingPage = $readingWrap.length > 0 || $('body').hasClass('single-chuong');
      if (!isReadingPage) return;

      const $header = $('.site-header');
      if (!$header.length) return;

      let lastScrollTop = Math.max(0, window.pageYOffset || document.documentElement.scrollTop || 0);
      let ticking = false;
      const scrollThreshold = 8; // Ngưỡng cuộn (px) để tránh rung giật
      const minScrollToHide = 50; // Chỉ ẩn khi đã cuộn qua 50px từ đầu trang

      const handleScroll = () => {
        const currentScrollTop = Math.max(0, window.pageYOffset || document.documentElement.scrollTop || 0);

        // 1. Khi ở gần đầu trang (<= 25px): Luôn luôn hiện Header
        if (currentScrollTop <= 25) {
          $header.removeClass('header-hidden-reading');
          lastScrollTop = currentScrollTop;
          ticking = false;
          return;
        }

        // 2. Không ẩn nếu đang mở Menu Drawer, Popup tùy chỉnh hoặc đang gõ tìm kiếm
        if (
          $('#mobileNavDrawer').hasClass('open') ||
          $('#readingSettingsQuickModal').is(':visible') ||
          $('#chapterDrawerModal').hasClass('open') ||
          $('#shopeeGatekeeperModal').is(':visible') ||
          $('.site-header input:focus').length > 0
        ) {
          ticking = false;
          return;
        }

        const diff = currentScrollTop - lastScrollTop;

        // 3. Kéo lên màn hình (trang cuộn xuống tiếp tục đọc truyện): Ẩn Header
        if (diff > scrollThreshold && currentScrollTop > minScrollToHide) {
          if (!$header.hasClass('header-hidden-reading')) {
            $header.addClass('header-hidden-reading');
          }
          lastScrollTop = currentScrollTop;
        } 
        // 4. Kéo xuống màn hình (trang cuộn ngược lên): Hiện lại Header mượt mà
        else if (diff < -scrollThreshold) {
          if ($header.hasClass('header-hidden-reading')) {
            $header.removeClass('header-hidden-reading');
          }
          lastScrollTop = currentScrollTop;
        }

        ticking = false;
      };

      window.addEventListener('scroll', () => {
        if (!ticking) {
          window.requestAnimationFrame(handleScroll);
          ticking = true;
        }
      }, { passive: true });
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

      // 5.b Toggle Follow Story (Theo dõi / Hủy theo dõi truyện)
      $(document).on('click', '#btnToggleFollowStory', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const storyId = $btn.data('story-id');

        if (!muopConfig.isLoggedIn) {
          if (typeof muopAlert === 'function') {
            muopAlert('Vui lòng đăng nhập để theo dõi truyện và nhận thông báo ngay khi có chương mới!', 'warning', () => {
              window.location.href = muopConfig.siteUrl + '/dang-nhap/?redirect_to=' + encodeURIComponent(window.location.href);
            });
          } else {
            window.location.href = muopConfig.siteUrl + '/dang-nhap/';
          }
          return;
        }

        const originalHtml = $btn.html();
        $btn.prop('disabled', true);

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_toggle_follow_story',
            nonce: muopConfig.nonce,
            story_id: storyId
          },
          success: (res) => {
            $btn.prop('disabled', false);
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              const isFollowing = res.data && res.data.following;
              if (isFollowing) {
                $btn.html('<i class="fa-solid fa-bell"></i> <span class="follow-btn-label">Đang Theo Dõi</span>');
                $btn.addClass('btn-primary').removeClass('btn-secondary');
                $btn.attr('title', 'Đang theo dõi truyện này (Bấm để hủy)');
              } else {
                $btn.html('<i class="fa-regular fa-bell"></i> <span class="follow-btn-label">Theo Dõi Truyện</span>');
                $btn.removeClass('btn-primary').addClass('btn-secondary');
                $btn.attr('title', 'Theo dõi truyện để nhận thông báo ngay khi có chương mới');
              }
              if (typeof muopAlert === 'function') {
                muopAlert(getAjaxMsg(res, isFollowing ? 'Đã theo dõi truyện thành công!' : 'Đã hủy theo dõi truyện.'), isFollowing ? 'success' : 'info');
              }
            } else {
              $btn.html(originalHtml);
              if (typeof muopAlert === 'function') {
                muopAlert(getAjaxMsg(res, 'Thao tác không thành công!'), 'error');
              }
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html(originalHtml);
            let msg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) msg = getAjaxMsg(xhr.responseJSON, msg);
            if (typeof muopAlert === 'function') {
              muopAlert(msg, 'error');
            }
          }
        });
      });

      // 5.c Toggle Favorite Story (Yêu thích / Bỏ yêu thích truyện)
      $(document).on('click', '#btnToggleFavoriteStory', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const storyId = $btn.data('story-id');

        if (!muopConfig.isLoggedIn) {
          if (typeof muopAlert === 'function') {
            muopAlert('Vui lòng đăng nhập để yêu thích truyện!', 'warning', () => {
              window.location.href = muopConfig.siteUrl + '/dang-nhap/?redirect_to=' + encodeURIComponent(window.location.href);
            });
          } else {
            window.location.href = muopConfig.siteUrl + '/dang-nhap/';
          }
          return;
        }

        const originalHtml = $btn.html();
        $btn.prop('disabled', true);

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_toggle_favorite_story',
            nonce: muopConfig.nonce,
            story_id: storyId
          },
          success: (res) => {
            $btn.prop('disabled', false);
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              const isFavorited = res.data && res.data.favorited;
              if (isFavorited) {
                $btn.html('<i class="fa-solid fa-heart"></i> <span class="favorite-btn-label">Đã Yêu Thích</span>');
                $btn.addClass('btn-primary').removeClass('btn-secondary');
                $btn.attr('title', 'Bỏ yêu thích truyện này');
              } else {
                $btn.html('<i class="fa-regular fa-heart"></i> <span class="favorite-btn-label">Yêu Thích Truyện</span>');
                $btn.removeClass('btn-primary').addClass('btn-secondary');
                $btn.attr('title', 'Yêu thích bộ truyện này');
              }
              if (typeof muopAlert === 'function') {
                muopAlert(getAjaxMsg(res, isFavorited ? 'Đã thêm truyện vào danh sách yêu thích!' : 'Đã bỏ yêu thích bộ truyện.'), isFavorited ? 'success' : 'info');
              }
            } else {
              $btn.html(originalHtml);
              if (typeof muopAlert === 'function') {
                muopAlert(getAjaxMsg(res, 'Thao tác không thành công!'), 'error');
              }
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html(originalHtml);
            let msg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) msg = getAjaxMsg(xhr.responseJSON, msg);
            if (typeof muopAlert === 'function') muopAlert(msg, 'error');
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
        const ajaxUrl = getAjaxUrl();

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
        const ajaxUrl = getAjaxUrl();

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
        const ajaxUrl = getAjaxUrl();

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
        const ajaxUrl = getAjaxUrl();

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
              const start = res.indexOf('{');
              const end = res.lastIndexOf('}');
              if (start !== -1 && end !== -1 && end > start) {
                try {
                  res = JSON.parse(res.substring(start, end + 1));
                } catch (e) {}
              }
            }
            if (res && res.success) {
              const successMsg = getAjaxMsg(res, isDraft ? 'Đã lưu bản nháp thành công!' : 'Đăng truyện thành công!');
              $alert.hide();

              // Reset form inputs ngay lập tức
              $form[0].reset();
              $('#coverFileName').text('');
              $('#coverUploadInfo').hide();
              $('#btnUploadCover').show();
              $('#storyCover').val('');
              $form.find('input[type="checkbox"]').prop('checked', false);
              $btn.prop('disabled', false).text(isDraft ? 'Lưu Nháp' : 'Đăng Truyện');

              // Hiển thị Popup Modal thông báo thành công
              if (typeof muopAlert === 'function') {
                muopAlert(
                  '<strong>' + successMsg + '</strong><br><span style="font-size: 13.5px; color: var(--text-muted); display: block; margin-top: 6px;">Trang sẽ được làm mới để bạn tiếp tục đăng bộ truyện tiếp theo.</span>',
                  'success',
                  function() {
                    window.location.href = window.location.pathname;
                  }
                );
              }

              // Tự động làm mới trang sau 2.5s nếu người dùng chưa bấm nút xác nhận
              setTimeout(() => {
                window.location.href = window.location.pathname;
              }, 2500);
            } else {
              $btn.prop('disabled', false).text(isDraft ? 'Lưu Nháp' : 'Đăng Truyện');
              const errorMsg = getAjaxMsg(res, 'Lỗi đăng truyện!');
              if (typeof muopAlert === 'function') {
                muopAlert(errorMsg, 'error');
              }
              $alert.removeClass('alert-success').addClass('alert-error').text(errorMsg).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).text(isDraft ? 'Lưu Nháp' : 'Đăng Truyện');
            let errorMsg = 'Lỗi upload hoặc hệ thống!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
            if (typeof muopAlert === 'function') {
              muopAlert(errorMsg, 'error');
            }
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

      // Tự động cập nhật số chương tiếp theo & Tên truyện trên breadcrumbs khi người dùng chọn truyện khác trong dropdown
      $('#selectStory').on('change', function() {
        const sId = $(this).val();
        const $selectedOption = $(this).find('option:selected');
        const storyTitle = $selectedOption.text().trim();
        const storyUrl = $selectedOption.data('url') || '#';

        // Cập nhật ngay Tên Truyện trên Breadcrumb đường dẫn
        if (sId && storyTitle && storyTitle.indexOf('--') === -1) {
          $('#breadcrumbStoryText').text(storyTitle);
          $('#breadcrumbStoryLink').attr('href', storyUrl);
          $('#breadcrumbStoryItem').css('display', 'inline-flex');
        } else {
          $('#breadcrumbStoryItem').hide();
        }

        if (!sId) return;
        const ajaxUrl = getAjaxUrl();
        const nonce = (typeof muopConfig !== 'undefined' && muopConfig.nonce) ? muopConfig.nonce : '';
        $.ajax({
          url: ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_get_next_chapter_number',
            story_id: sId,
            nonce: nonce
          },
          success: (res) => {
            if (typeof res === 'string') {
              const clean = res.replace(/<br\s*\/?>\s*<b>(?:Notice|Warning|Deprecated)<\/b>:.*?<br\s*\/?>/gi, '').trim();
              try { res = JSON.parse(clean); } catch (e) {}
            }
            if (res && res.success && res.data && res.data.next_chapter_number) {
              $('#chapNumber').val(res.data.next_chapter_number);
            }
          }
        });
      });

      // -------------------------------------------------------------------------
      // BỘ ĐẾM SỐ TỪ & KÝ TỰ THEO THỜI GIAN THỰC (REAL-TIME WORD COUNT)
      // -------------------------------------------------------------------------

      // Đếm số từ khi nhập/dán nội dung tại trang Thêm Chương
      $('#chapContent').on('input keyup paste change', function() {
        updateRealtimeWordCounter($(this), $('#chapContentWordCount'), $('#chapContentCharCount'));
      });

      // Tự động đếm ngay khi load trang
      if ($('#chapContent').length) {
        updateRealtimeWordCounter($('#chapContent'), $('#chapContentWordCount'), $('#chapContentCharCount'));
      }

      // Đếm số từ khi nhập/dán nội dung tại Popup Sửa Chương
      $('#editChapterContent').on('input keyup paste change', function() {
        updateRealtimeWordCounter($(this), $('#editChapterContentWordCount'), $('#editChapterContentCharCount'));
      });

      $('#formSubmitChapter').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $alert = $('#chapterAlert');
        const isDraft = $('#isChapterDraftInput').val() === '1';
        const $btn = isDraft ? $('#btnSaveDraftNewChapter') : $('#btnSubmitPublishChapter');

        // BẮT BUỘC DUYỆT NỘI DUNG KHI BẤM "THÊM CHƯƠNG NÀY" (NỘI DUNG OKE MỚI ĐƯỢC ĐĂNG)
        if (!isDraft && typeof SensitiveWordModerator !== 'undefined') {
          const $chapContent = $('#chapContent');
          const $chapTitle = $('#chapTitle');
          const fullText = ($chapTitle.val() || '') + '\n' + ($chapContent.val() || '');

          const scanResult = SensitiveWordModerator.quickScan(fullText);
          if (scanResult.foundMap.size > 0) {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-cloud-arrow-up"></i> Thêm Chương Này');

            // Kích hoạt ngay bộ duyệt nội dung và hiển thị bảng đánh dấu trực tiếp
            SensitiveWordModerator.scanAndOpen($chapContent);

            $alert.removeClass('alert-success').addClass('alert-error').html(
              '<i class="fa-solid fa-triangle-exclamation"></i> <strong>Chưa thể đăng chương!</strong> Phát hiện <strong>' + 
              scanResult.foundMap.size + ' từ nhạy cảm (' + scanResult.totalMatches + ' vị trí vi phạm)</strong> trong bài. Vui lòng xem bảng đánh dấu bên dưới và sửa trước khi đăng!'
            ).fadeIn();

            if ($chapContent.length) {
              $('html, body').animate({ scrollTop: $chapContent.offset().top - 140 }, 300);
            }
            return false;
          }
        }

        $btn.prop('disabled', true).text(isDraft ? 'Đang lưu bản nháp...' : 'Đang thêm chương...');
        $alert.hide();

        const nonce = $form.find('input[name="nonce"]').val() || (typeof muopConfig !== 'undefined' ? muopConfig.nonce : '');
        const ajaxUrl = getAjaxUrl();

        const formData = new FormData(this);
        if (!formData.has('action')) formData.append('action', 'muop_submit_chapter');
        if (!formData.has('nonce') || !formData.get('nonce')) formData.append('nonce', nonce);
        if (!formData.has('is_draft')) formData.append('is_draft', isDraft ? '1' : '0');

        $.ajax({
          url: ajaxUrl,
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: (res) => {
            if (typeof res === 'string') {
              const start = res.indexOf('{');
              const end = res.lastIndexOf('}');
              if (start !== -1 && end !== -1 && end > start) {
                try {
                  res = JSON.parse(res.substring(start, end + 1));
                } catch (e) {}
              }
            }
            if (res && res.success) {
              const successMsg = getAjaxMsg(res, isDraft ? 'Đã lưu bản nháp chương thành công!' : 'Thêm chương mới thành công!');
              const curStoryId = $('#selectStory').val();
              const nextNum = (res.data && res.data.next_chapter_number) ? res.data.next_chapter_number : (parseInt($('#chapNumber').val(), 10) + 1);

              // Reset các trường nội dung chương
              $('#chapTitle').val('');
              $('#chapContent').val('');
              updateRealtimeWordCounter($('#chapContent'), $('#chapContentWordCount'), $('#chapContentCharCount'));
              $('#chapNumber').val(nextNum);
              $btn.prop('disabled', false).text(isDraft ? 'Lưu Nháp' : 'Thêm Chương Này');
              $alert.hide();

              const doReload = () => {
                const searchParams = new URLSearchParams(window.location.search);
                if (curStoryId) searchParams.set('story_id', curStoryId);
                searchParams.set('added', '1');
                window.location.search = searchParams.toString();
              };

              // Hiển thị Popup Modal thông báo thành công
              if (typeof muopAlert === 'function') {
                muopAlert(
                  '<strong>' + successMsg + '</strong><br><span style="font-size: 13.5px; color: var(--text-muted); display: block; margin-top: 6px;">Hệ thống đã giữ lại bộ truyện và nạp số chương tiếp theo để bạn tiếp tục đăng.</span>',
                  'success',
                  doReload
                );
              }

              // Tự động làm mới sau 2.5s nếu chưa bấm xác nhận
              setTimeout(doReload, 2500);
            } else {
              $btn.prop('disabled', false).text(isDraft ? 'Lưu Nháp' : 'Thêm Chương Này');
              const errorMsg = getAjaxMsg(res, 'Lỗi thêm chương!');
              if (typeof muopAlert === 'function') {
                muopAlert(errorMsg, 'error');
              }
              $alert.removeClass('alert-success').addClass('alert-error').text(errorMsg).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).text(isDraft ? 'Lưu Nháp' : 'Thêm Chương Này');
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
            if (typeof muopAlert === 'function') {
              muopAlert(errorMsg, 'error');
            }
            $alert.removeClass('alert-success').addClass('alert-error').text(errorMsg).fadeIn();
          }
        });
      });
    }
  };

  // 8. COMMENTS
  const CommentManager = {
    init() {
      // Toggle Sticker Picker
      $(document).on('click', '.btn-sticker-toggle', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const $btn = $(this);
        const $form = $btn.closest('form');
        const $picker = $form.find('.muop-sticker-picker');
        
        $btn.toggleClass('active');
        $picker.slideToggle(200);
      });

      // Close button inside picker
      $(document).on('click', '.sticker-close-btn', function(e) {
        e.preventDefault();
        const $picker = $(this).closest('.muop-sticker-picker');
        $picker.slideUp(200);
        $picker.closest('form').find('.btn-sticker-toggle').removeClass('active');
      });

      // Switch Sticker Tab
      $(document).on('click', '.sticker-tab-btn', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const set = $btn.data('set');
        const $picker = $btn.closest('.muop-sticker-picker');

        $picker.find('.sticker-tab-btn').removeClass('active');
        $btn.addClass('active');

        $picker.find('.sticker-tab-pane').removeClass('active');
        $picker.find('#pane-' + set).addClass('active');
      });

      // Click sticker item to insert into textarea
      $(document).on('click', '.sticker-item', function(e) {
        e.preventDefault();
        const stickerCode = $(this).data('code');
        const $form = $(this).closest('form');
        const $textarea = $form.find('textarea[name="comment"]');

        if ($textarea.length) {
          const val = $textarea.val();
          const el = $textarea[0];
          if (el && typeof el.selectionStart === 'number') {
            const start = el.selectionStart;
            const end = el.selectionEnd;
            const spaceBefore = (start > 0 && val.charAt(start - 1) !== ' ' && val.charAt(start - 1) !== '\n') ? ' ' : '';
            const spaceAfter = (end < val.length && val.charAt(end) !== ' ' && val.charAt(end) !== '\n') ? ' ' : ' ';
            const insertText = spaceBefore + stickerCode + spaceAfter;
            $textarea.val(val.substring(0, start) + insertText + val.substring(end));
            el.selectionStart = el.selectionEnd = start + insertText.length;
          } else {
            $textarea.val((val ? val + ' ' : '') + stickerCode + ' ');
          }
          $textarea.focus();
          CommentManager.updatePreview($form);
        }
      });

      // Remove sticker chip from preview
      $(document).on('click', '.preview-sticker-chip .chip-remove', function(e) {
        e.preventDefault();
        const codeToRemove = $(this).data('code');
        const $form = $(this).closest('form');
        const $textarea = $form.find('textarea[name="comment"]');
        let val = $textarea.val();
        val = val.replace(codeToRemove, '').replace(/\s{2,}/g, ' ').trim();
        $textarea.val(val);
        CommentManager.updatePreview($form);
      });

      // Live update preview on textarea typing
      $(document).on('input propertychange', '#formPostComment textarea[name="comment"]', function() {
        const $form = $(this).closest('form');
        CommentManager.updatePreview($form);
      });

      // Close sticker picker when clicking outside
      $(document).on('click', function(e) {
        if (!$(e.target).closest('.muop-sticker-picker, .btn-sticker-toggle').length) {
          $('.muop-sticker-picker').slideUp(200);
          $('.btn-sticker-toggle').removeClass('active');
        }
      });

      // Submit comment
      $('#formPostComment').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $btn = $form.find('button[type="submit"]');
        const $textarea = $form.find('textarea[name="comment"]');

        if (!muopConfig.isLoggedIn) {
          window.location.href = muopConfig.siteUrl + '/dang-nhap/';
          return;
        }

        const rawContent = $textarea.val().trim();
        if (!rawContent) {
          alert('Vui lòng nhập nội dung bình luận hoặc chọn sticker!');
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
              const commentId = d.comment_id || 0;
              const html = `
                <li class="comment-item" id="comment-${commentId}">
                  <div class="comment-avatar">${author.charAt(0).toUpperCase()}</div>
                  <div class="comment-body">
                    <div class="comment-header-row" style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                      <div>
                        <span class="comment-author-name">${author}</span>
                        <span class="comment-time">${d.time || 'Vừa xong'}</span>
                      </div>
                      ${commentId ? `
                        <button type="button" class="btn-delete-comment" data-comment-id="${commentId}" title="Xóa bình luận">
                          <i class="fa-solid fa-trash-can"></i> Xóa
                        </button>
                      ` : ''}
                    </div>
                    <div class="comment-text">${d.content || ''}</div>
                  </div>
                </li>
              `;
              const $list = $('.comment-list-ul');
              $list.find('p, #noCommentsText').remove();
              $list.prepend(html);
              $textarea.val('');
              $form.find('.muop-sticker-picker').slideUp(200);
              $form.find('.btn-sticker-toggle').removeClass('active');
              CommentManager.updatePreview($form);
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

      // Delete comment (Admin, Story Author / Translator, or Comment Author)
      $(document).on('click', '.btn-delete-comment', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const commentId = $btn.data('comment-id');
        if (!commentId) return;

        muopConfirm(
          'Bạn có chắc chắn muốn xóa bình luận này không?<br><span style="font-size: 13px; color: #dc2626; display: block; margin-top: 6px;"><i class="fa-solid fa-triangle-exclamation"></i> Thao tác này không thể hoàn tác!</span>',
          () => {
            $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');

            $.ajax({
              url: muopConfig.ajaxUrl,
              type: 'POST',
              data: {
                action: 'muop_delete_comment',
                nonce: muopConfig.nonce,
                comment_id: commentId
              },
              success: (res) => {
                if (typeof res === 'string') {
                  try { res = JSON.parse(res); } catch (e) {}
                }
                if (res && res.success) {
                  const $item = $btn.closest('.comment-item');
                  $item.fadeOut(250, function() {
                    const $list = $(this).closest('.comment-list-ul');
                    $(this).remove();
                    if ($list.length && $list.find('.comment-item').length === 0) {
                      $list.html('<p style="color: var(--text-muted); font-size: 13px; padding: 10px 0;">Chưa có bình luận nào.</p>');
                    }
                  });
                } else {
                  $btn.prop('disabled', false).html('<i class="fa-solid fa-trash-can"></i> Xóa');
                  alert(getAjaxMsg(res, 'Không thể xóa bình luận!'));
                }
              },
              error: (xhr) => {
                $btn.prop('disabled', false).html('<i class="fa-solid fa-trash-can"></i> Xóa');
                let errorMsg = 'Lỗi kết nối máy chủ!';
                if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
                else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
                alert(errorMsg);
              }
            });
          },
          null,
          {
            title: 'Xóa Bình Luận',
            confirmText: 'Xóa Vĩnh Viễn',
            cancelText: 'Hủy Bỏ',
            isDanger: true
          }
        );
      });
    },

    updatePreview($form) {
      const $textarea = $form.find('textarea[name="comment"]');
      const val = $textarea.val() || '';
      const regex = /\[sticker:(bo-[1-3]\/[0-9]+\.png)\]/gi;
      const matches = [...val.matchAll(regex)];
      const $preview = $form.find('.comment-stickers-preview');
      const baseUrl = muopConfig.stickerBaseUrl || '/wp-content/themes/muop-dang/assets/images/stickers/';

      if (matches.length > 0) {
        let html = '<div class="preview-sticker-heading"><i class="fa-regular fa-face-smile"></i> Sticker đã đính kèm:</div><div class="preview-sticker-list">';
        matches.forEach(m => {
          const path = m[1];
          const fullCode = m[0];
          html += `<div class="preview-sticker-chip" title="Nhấn để gỡ">
            <img src="${baseUrl + path}" alt="Sticker" />
            <button type="button" class="chip-remove" data-code="${fullCode}">&times;</button>
          </div>`;
        });
        html += '</div>';
        $preview.html(html).slideDown(150);
      } else {
        $preview.slideUp(150, function() { $(this).empty(); });
      }
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

      // Quick Admin Actions (Approve, Reject, Delete, Nominate, Hide, Unhide)
      $(document).on('click', '.btn-admin-action', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const subAction = $btn.data('action');
        const storyId = $btn.data('story-id');
        const userId = $btn.data('user-id');
        const nominateType = $btn.data('nominate-type');

        const doRequest = () => {
          const originalHtml = $btn.html();
          $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');
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
              $btn.prop('disabled', false).html(originalHtml);
              if (typeof res === 'string') {
                try { res = JSON.parse(res); } catch (e) {}
              }
              if (res && res.success) {
                muopAlert(getAjaxMsg(res, 'Thao tác thành công!'), 'success', () => {
                  window.location.reload();
                });
              } else {
                muopAlert(getAjaxMsg(res, 'Lỗi thao tác!'), 'error');
              }
            },
            error: (xhr) => {
              $btn.prop('disabled', false).html(originalHtml);
              let errorMsg = 'Lỗi kết nối máy chủ!';
              if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
              else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
              muopAlert(errorMsg, 'error');
            }
          });
        };

        if (subAction === 'delete_story') {
          muopConfirm('Bạn có chắc chắn muốn xóa truyện này? Thao tác này không thể hoàn tác.', doRequest, null, {
            isDanger: true,
            confirmText: 'Có',
            cancelText: 'Không',
            title: 'Xác Nhận Xóa Truyện'
          });
        } else if (subAction === 'hide_story') {
          muopConfirm('Bạn có chắc chắn muốn ẩn truyện này không?', doRequest, null, {
            type: 'warning',
            confirmText: 'Có',
            cancelText: 'Không',
            title: 'Xác Nhận Ẩn Truyện'
          });
        } else if (subAction === 'unhide_story') {
          const btnText = $btn.text().trim();
          const confirmMsg = btnText.indexOf('Duyệt') !== -1 ? 'Bạn có chắc chắn muốn duyệt và hiện truyện này không?' : 'Bạn có chắc chắn muốn hiện truyện này không?';
          const confirmTitle = btnText.indexOf('Duyệt') !== -1 ? 'Xác Nhận Duyệt Truyện' : 'Xác Nhận Hiện Truyện';
          muopConfirm(confirmMsg, doRequest, null, {
            type: 'warning',
            confirmText: 'Có',
            cancelText: 'Không',
            title: confirmTitle
          });
        } else {
          doRequest();
        }
      });

      // Delete Story (Dịch giả & Admin)
      $(document).on('click', '.btn-delete-story', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const storyId = $btn.data('story-id');

        muopConfirm('Bạn có chắc chắn muốn xóa bộ truyện này không? Toàn bộ chương của truyện cũng sẽ bị xóa vĩnh viễn!', () => {
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
                muopAlert(getAjaxMsg(res, 'Đã xóa bộ truyện thành công!'), 'success', () => {
                  $btn.closest('tr').fadeOut(300, function() {
                    $(this).remove();
                  });
                });
              } else {
                muopAlert(getAjaxMsg(res, 'Lỗi xóa truyện!'), 'error');
                $btn.prop('disabled', false);
              }
            },
            error: (xhr) => {
              let errorMsg = 'Lỗi kết nối máy chủ!';
              if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
              else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
              muopAlert(errorMsg, 'error');
              $btn.prop('disabled', false);
            }
          });
        }, null, { isDanger: true, confirmText: 'Xóa vĩnh viễn' });
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
                  <div class="adm-genre-card" data-id="${data.term_id}" data-name="${safeName.toLowerCase()}" style="background: var(--bg-card); padding: 14px 16px; border-radius: var(--radius-md); border: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; gap: 10px; box-shadow: var(--shadow-sm); position: relative; animation: admModalFadeIn 0.3s ease;">
                      <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                          <span class="adm-genre-title" style="font-weight: 700; color: var(--text-main); font-size: 14.5px; word-break: break-word;">${safeName}</span>
                          <span class="badge badge-green" style="font-size: 11px; white-space: nowrap;">0 truyện</span>
                      </div>
                      <div style="display: flex; align-items: center; justify-content: flex-end; padding-top: 8px; border-top: 1px dashed var(--border-light);">
                          <div class="story-actions-dropdown genre-actions-dropdown">
                              <button type="button" class="btn-story-dropdown-trigger btn-genre-dropdown-trigger" title="Tác vụ thể loại" data-id="${data.term_id}" data-name="${safeName}" data-count="0">
                                  <i class="fa-solid fa-ellipsis-vertical"></i>
                              </button>
                              <div class="story-dropdown-menu">
                                  <button type="button" class="story-dropdown-item adm-btn-edit-genre" data-id="${data.term_id}" data-name="${safeName}">
                                      <i class="fa-solid fa-pen-to-square" style="color: #0284c7;"></i> Sửa
                                  </button>
                                  <button type="button" class="story-dropdown-item adm-btn-delete-genre" data-id="${data.term_id}" data-name="${safeName}" style="color: #ef4444;">
                                      <i class="fa-solid fa-trash-can"></i> Xóa
                                  </button>
                                  <button type="button" class="story-dropdown-item adm-btn-detail-genre" data-id="${data.term_id}" data-name="${safeName}" data-count="0">
                                      <i class="fa-solid fa-circle-info" style="color: var(--primary-green);"></i> Chi tiết
                                  </button>
                              </div>
                          </div>
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
              $card.find('.adm-btn-detail-genre').attr('data-name', data.name).data('name', data.name);
              $card.find('.btn-genre-dropdown-trigger').attr('data-name', data.name).data('name', data.name);
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

        muopConfirm(`Bạn có chắc chắn muốn xóa thể loại "${termName}" khỏi hệ thống không? Các truyện thuộc thể loại này sẽ không còn thể loại này nữa.`, () => {
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
                muopAlert(getAjaxMsg(res, 'Đã xóa thể loại thành công!'), 'success');
              } else {
                muopAlert(getAjaxMsg(res, 'Lỗi khi xóa thể loại!'), 'error');
                $btn.prop('disabled', false).html('<i class="fa-solid fa-trash-can"></i> Xóa');
              }
            },
            error: (xhr) => {
              let errorMsg = 'Lỗi kết nối máy chủ!';
              if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
              muopAlert(errorMsg, 'error');
              $btn.prop('disabled', false).html('<i class="fa-solid fa-trash-can"></i> Xóa');
            }
          });
        }, null, { isDanger: true, confirmText: 'Xóa thể loại' });
      });

      // 7. Chi Tiết Thể Loại - Xem danh sách truyện thuộc thể loại (Popup - Ảnh 1)
      let cachedGenreStories = [];
      const filterGenreDetailStories = () => {
        const query = $.trim($('#admGenreDetailSearch').val()).toLowerCase();
        let matchCount = 0;
        $('#admGenreDetailTableBody tr.adm-genre-story-row').each(function() {
          const $row = $(this);
          const title = $row.data('title') || '';
          const author = $row.data('author') || '';
          if (!query || title.indexOf(query) !== -1 || author.indexOf(query) !== -1) {
            $row.show();
            matchCount++;
          } else {
            $row.hide();
          }
        });
        $('#admGenreDetailShownCount').text(matchCount);
        if (matchCount === 0 && cachedGenreStories.length > 0) {
          if (!$('#admGenreDetailNoMatch').length) {
            $('#admGenreDetailTableBody').append('<tr id="admGenreDetailNoMatch"><td colspan="8" style="text-align: center; padding: 30px; color: var(--text-muted);"><i class="fa-solid fa-magnifying-glass" style="margin-right: 6px;"></i>Không tìm thấy truyện nào khớp với từ khóa tìm kiếm.</td></tr>');
          } else {
            $('#admGenreDetailNoMatch').show();
          }
        } else {
          $('#admGenreDetailNoMatch').remove();
        }
      };

      $('#admGenreDetailSearch').on('input keyup', filterGenreDetailStories);

      $(document).on('click', '.adm-btn-detail-genre', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const termId = $btn.data('id');
        const termName = $btn.attr('data-name') || $btn.data('name') || 'Thể loại';
        const termCount = $btn.data('count') || 0;

        $('#admGenreDetailName').text(termName);
        $('#admGenreDetailBadge').text(termCount + ' truyện');
        $('#admGenreDetailSearch').val('');
        $('#admGenreDetailShownCount').text(termCount);
        $('#admGenreDetailTableBody').empty();
        $('#admGenreDetailLoading').show();
        $('#admGenreDetailEmpty').hide();
        $('#admGenreDetailTableWrapper').hide();
        $('#admModalGenreDetail').css('display', 'flex');

        cachedGenreStories = [];

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_get_genre_stories',
            term_id: termId,
            nonce: muopConfig.nonce
          },
          success: (res) => {
            $('#admGenreDetailLoading').hide();
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success && res.data) {
              const data = res.data;
              const stories = data.stories || [];
              cachedGenreStories = stories;
              $('#admGenreDetailName').text(data.genre_name || termName);
              $('#admGenreDetailBadge').text((data.genre_count || stories.length) + ' truyện');
              $('#admGenreDetailShownCount').text(stories.length);

              if (stories.length === 0) {
                $('#admGenreDetailEmpty').show();
                $('#admGenreDetailTableWrapper').hide();
                return;
              }

              let rowsHtml = '';
              stories.forEach((st, idx) => {
                const safeTitle = $('<div>').text(st.title).html();
                const safeAuthor = $('<div>').text(st.author).html();
                const statusBadge = st.is_full 
                  ? '<span class="badge badge-full" style="font-size: 11px;">Hoàn thành</span>' 
                  : '<span class="badge badge-green" style="font-size: 11px;">Đang ra</span>';

                rowsHtml += `
                  <tr class="adm-genre-story-row" data-title="${safeTitle.toLowerCase()}" data-author="${safeAuthor.toLowerCase()}">
                    <td style="text-align: center; font-weight: 600; color: var(--text-muted);">${idx + 1}</td>
                    <td style="text-align: center;">
                      <img src="${st.thumb}" alt="${safeTitle}" style="width: 34px; height: 46px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color); display: inline-block; vertical-align: middle;" loading="lazy" />
                    </td>
                    <td>
                      <a href="${st.permalink}" target="_blank" style="font-weight: 600; color: var(--text-main); text-decoration: none; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="${safeTitle}">
                        ${safeTitle}
                      </a>
                    </td>
                    <td style="color: var(--text-muted); font-size: 12.5px;">
                      <i class="fa-regular fa-user" style="margin-right: 4px; font-size: 11px;"></i>${safeAuthor}
                    </td>
                    <td style="text-align: center; font-weight: 600;">
                      <span class="badge" style="font-size: 11px;">${st.chapters} ch</span>
                    </td>
                    <td style="text-align: center; color: var(--text-muted); font-size: 12px;">
                      <i class="fa-solid fa-eye" style="margin-right: 3px; font-size: 10.5px;"></i>${st.views}
                    </td>
                    <td style="text-align: center;">
                      ${statusBadge}
                    </td>
                    <td style="text-align: center;">
                      <div style="display: inline-flex; align-items: center; gap: 4px;">
                        <a href="${st.permalink}" target="_blank" class="btn btn-outline btn-xs" style="padding: 3px 7px; font-size: 11.5px; border-radius: 4px;" title="Xem truyện ngoài web">
                          <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                        <a href="${st.edit_link}" target="_blank" class="btn btn-primary btn-xs" style="padding: 3px 7px; font-size: 11.5px; border-radius: 4px;" title="Sửa truyện">
                          <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                `;
              });

              $('#admGenreDetailTableBody').html(rowsHtml);
              $('#admGenreDetailEmpty').hide();
              $('#admGenreDetailTableWrapper').show();
            } else {
              $('#admGenreDetailLoading').hide();
              muopAlert(getAjaxMsg(res, 'Không thể tải danh sách truyện thuộc thể loại!'), 'error');
            }
          },
          error: (xhr) => {
            $('#admGenreDetailLoading').hide();
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            muopAlert(errorMsg, 'error');
          }
        });
      });

      // Đóng modal chi tiết thể loại
      $('#btnCloseGenreDetailModalX, .btn-close-genre-detail').on('click', function(e) {
        e.preventDefault();
        $('#admModalGenreDetail').hide();
      });

      $('#admModalGenreDetail').on('click', function(e) {
        if (e.target === this) {
          $(this).hide();
        }
      });

      // =========================================================================
      // === QUẢN LÝ TỪ NHẠY CẢM (ADMIN SENSITIVE WORDS MANAGEMENT) ===
      // =========================================================================

      // 1. Tìm kiếm từ nhạy cảm realtime
      $('#admSearchSensitiveWord').on('input', function() {
        const q = $(this).val().toLowerCase().trim();
        let matched = 0;
        $('#admSensitiveWordsGrid .adm-word-card').each(function() {
          const word = ($(this).data('word') || $(this).find('.adm-word-title').text()).toLowerCase();
          if (!q || word.indexOf(q) !== -1) {
            $(this).show();
            matched++;
          } else {
            $(this).hide();
          }
        });
        if (matched === 0) {
          $('#admNoWordFound').show();
        } else {
          $('#admNoWordFound').hide();
        }
      });

      // 2. Toggle Box Thêm Từ Mới
      $('#admBtnToggleAddWord').on('click', function(e) {
        e.preventDefault();
        const $box = $('#admBoxAddWord');
        $box.slideToggle(180, function() {
          if ($box.is(':visible')) {
            $('#admInputNewWord').focus();
          }
        });
      });

      $('#admBtnCancelAddWord').on('click', function(e) {
        e.preventDefault();
        $('#admBoxAddWord').slideUp(180);
        $('#admInputNewWord').val('');
        $('#admAddWordStatus').hide();
      });

      // 3. Admin Thêm Từ Nhạy Cảm Mới
      const handleAdminAddWord = () => {
        const $input = $('#admInputNewWord');
        const word = $.trim($input.val());
        const $btn = $('#admBtnConfirmAddWord');
        const $status = $('#admAddWordStatus');

        if (!word) {
          $status.css('color', '#ef4444').text('Vui lòng nhập từ nhạy cảm!').fadeIn();
          $input.focus();
          return;
        }

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...');
        $status.hide();

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_add_sensitive_word',
            nonce: muopConfig.nonce,
            word: word
          },
          success: (res) => {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Lưu Từ Mới');
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              const safeWord = $('<div>').text(res.data.word).html();
              const cardHtml = `
                <div class="adm-genre-card adm-word-card" data-word="${safeWord.toLowerCase()}" style="background: var(--bg-card); padding: 14px 16px; border-radius: var(--radius-md); border: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; gap: 10px; box-shadow: var(--shadow-sm); position: relative; animation: admModalFadeIn 0.3s ease;">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                        <span class="adm-genre-title adm-word-title" style="font-weight: 700; color: var(--text-main); font-size: 14.5px; word-break: break-word;">${safeWord}</span>
                        <span class="badge badge-hot" style="font-size: 11px; white-space: nowrap; background: #fee2e2; color: #dc2626; border: 1px solid #f87171;">Từ cấm</span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: flex-end; padding-top: 8px; border-top: 1px dashed var(--border-light);">
                        <div class="story-actions-dropdown genre-actions-dropdown">
                            <button type="button" class="btn-story-dropdown-trigger btn-genre-dropdown-trigger" title="Tác vụ từ nhạy cảm" data-word="${safeWord}">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="story-dropdown-menu">
                                <button type="button" class="story-dropdown-item adm-btn-edit-word" data-word="${safeWord}">
                                    <i class="fa-solid fa-pen-to-square" style="color: #0284c7;"></i> Sửa
                                </button>
                                <button type="button" class="story-dropdown-item adm-btn-delete-word" data-word="${safeWord}" style="color: #ef4444;">
                                    <i class="fa-solid fa-trash-can"></i> Xóa
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
              `;
              $('#admSensitiveWordsGrid').prepend(cardHtml);
              $('#admNoWordFound').hide();
              if (res.data.total_count) {
                $('#admSensitiveWordsTotalBadge').text(res.data.total_count + ' từ');
              }
              // Cập nhật ngay vào dictionary của bộ kiểm duyệt client
              if (typeof SensitiveWordModerator !== 'undefined' && Array.isArray(SensitiveWordModerator.dictionary)) {
                if (SensitiveWordModerator.dictionary.indexOf(word) === -1) {
                  SensitiveWordModerator.dictionary.unshift(word);
                  SensitiveWordModerator.dictionary.sort((a, b) => b.length - a.length);
                }
              }
              $status.css('color', '#3F7523').text('Đã thêm từ nhạy cảm mới thành công!').fadeIn();
              $input.val('');
              setTimeout(() => {
                $('#admBoxAddWord').slideUp(180);
                $status.hide();
              }, 1000);
            } else {
              $status.css('color', '#ef4444').text(getAjaxMsg(res, 'Lỗi thêm từ nhạy cảm!')).fadeIn();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Lưu Từ Mới');
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            $status.css('color', '#ef4444').text(errorMsg).fadeIn();
          }
        });
      };

      $('#admBtnConfirmAddWord').on('click', function(e) {
        e.preventDefault();
        handleAdminAddWord();
      });

      $('#admInputNewWord').on('keydown', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          handleAdminAddWord();
        }
      });

      // 4. Mở Modal Chỉnh Sửa Từ Nhạy Cảm
      $(document).on('click', '.adm-btn-edit-word', function(e) {
        e.preventDefault();
        const word = $(this).data('word') || $(this).closest('.adm-word-card').find('.adm-word-title').text().trim();
        $('#admEditOldWord').val(word);
        $('#admEditWordInput').val(word);
        $('#admEditWordStatus').hide();
        $('#admModalEditWord').css('display', 'flex');
        setTimeout(() => $('#admEditWordInput').focus().select(), 50);
      });

      $('#admBtnCloseEditWordModal, #admBtnCancelEditWordModal').on('click', function(e) {
        e.preventDefault();
        $('#admModalEditWord').hide();
      });

      $('#admModalEditWord').on('click', function(e) {
        if (e.target === this) {
          $(this).hide();
        }
      });

      // 5. Lưu Chỉnh Sửa Từ Nhạy Cảm
      const handleAdminSaveEditWord = () => {
        const oldWord = $('#admEditOldWord').val();
        const newWord = $.trim($('#admEditWordInput').val());
        const $btn = $('#admBtnSaveEditWord');
        const $status = $('#admEditWordStatus');

        if (!newWord) {
          $status.css('color', '#ef4444').text('Vui lòng nhập từ nhạy cảm mới!').fadeIn();
          $('#admEditWordInput').focus();
          return;
        }

        if (newWord.toLowerCase() === oldWord.toLowerCase()) {
          $('#admModalEditWord').hide();
          return;
        }

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...');
        $status.hide();

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_update_sensitive_word',
            nonce: muopConfig.nonce,
            old_word: oldWord,
            new_word: newWord
          },
          success: (res) => {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Lưu Thay Đổi');
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              const $card = $(`#admSensitiveWordsGrid .adm-word-card[data-word="${oldWord.toLowerCase()}"]`);
              if ($card.length) {
                $card.attr('data-word', newWord.toLowerCase());
                $card.find('.adm-word-title').text(newWord);
                $card.find('.btn-story-dropdown-trigger').data('word', newWord).attr('data-word', newWord);
                $card.find('.adm-btn-edit-word').data('word', newWord).attr('data-word', newWord);
                $card.find('.adm-btn-delete-word').data('word', newWord).attr('data-word', newWord);
              }
              // Cập nhật vào dictionary
              if (typeof SensitiveWordModerator !== 'undefined' && Array.isArray(SensitiveWordModerator.dictionary)) {
                const idx = SensitiveWordModerator.dictionary.indexOf(oldWord);
                if (idx !== -1) {
                  SensitiveWordModerator.dictionary[idx] = newWord;
                  SensitiveWordModerator.dictionary.sort((a, b) => b.length - a.length);
                }
              }
              $status.css('color', '#3F7523').text('Đã cập nhật từ nhạy cảm thành công!').fadeIn();
              setTimeout(() => {
                $('#admModalEditWord').hide();
                $status.hide();
              }, 800);
            } else {
              $status.css('color', '#ef4444').text(getAjaxMsg(res, 'Lỗi cập nhật từ nhạy cảm!')).fadeIn();
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

      $('#admBtnSaveEditWord').on('click', function(e) {
        e.preventDefault();
        handleAdminSaveEditWord();
      });

      $('#admEditWordInput').on('keydown', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          handleAdminSaveEditWord();
        }
      });

      // 6. Xóa Từ Nhạy Cảm
      $(document).on('click', '.adm-btn-delete-word', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const word = $btn.data('word') || $btn.closest('.adm-word-card').find('.adm-word-title').text().trim();
        const $card = $btn.closest('.adm-word-card');

        muopConfirm(`Bạn có chắc chắn muốn xóa từ nhạy cảm "<strong>${word}</strong>" khỏi hệ thống kiểm duyệt không?`, () => {
          $btn.prop('disabled', true);
          $.ajax({
            url: muopConfig.ajaxUrl,
            type: 'POST',
            data: {
              action: 'muop_delete_sensitive_word',
              nonce: muopConfig.nonce,
              word: word
            },
            success: (res) => {
              if (typeof res === 'string') {
                try { res = JSON.parse(res); } catch (e) {}
              }
              if (res && res.success) {
                $card.fadeOut(250, function() {
                  $(this).remove();
                  if ($('#admSensitiveWordsGrid .adm-word-card').length === 0) {
                    $('#admNoWordFound').show();
                  }
                });
                if (res.data && res.data.total_count !== undefined) {
                  $('#admSensitiveWordsTotalBadge').text(res.data.total_count + ' từ');
                }
                // Xóa khỏi dictionary
                if (typeof SensitiveWordModerator !== 'undefined' && Array.isArray(SensitiveWordModerator.dictionary)) {
                  const idx = SensitiveWordModerator.dictionary.indexOf(word);
                  if (idx !== -1) {
                    SensitiveWordModerator.dictionary.splice(idx, 1);
                  }
                }
                muopAlert(`Đã xóa từ nhạy cảm "${word}" thành công!`, 'success');
              } else {
                $btn.prop('disabled', false);
                muopAlert(getAjaxMsg(res, 'Lỗi xóa từ nhạy cảm!'), 'error');
              }
            },
            error: (xhr) => {
              $btn.prop('disabled', false);
              let errorMsg = 'Lỗi kết nối máy chủ!';
              if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
              muopAlert(errorMsg, 'error');
            }
          });
        }, null, { isDanger: true, confirmText: 'Xóa Từ', title: 'Xác Nhận Xóa Từ Nhạy Cảm' });
      });

      // =========================================================================
      // ADMIN DATABASE MANAGER - MASTER-DETAIL HIERARCHICAL DRILLDOWN (IMAGE 2)
      // =========================================================================

      // 1. Stories Live Search & Multi-Filters (Hot, Newest, Full, Ongoing, Nominated, Hidden, Visible)
      const filterAdminStories = (forcedFilter) => {
        const query = $.trim($('#admStorySearchInput').val()).toLowerCase();
        const pillFilter = $('#admStoryFilterPills .btn-story-filter-pill.active').data('filter');
        const selectFilter = $('#admStoryQuickFilter').val();
        const quickFilter = forcedFilter || pillFilter || selectFilter || 'all';

        const $tbody = $('#tableAdminStories tbody');
        const $rows = $tbody.find('tr.adm-story-row').get();

        // Sort rows if needed (hot, newest, or default original index)
        $rows.sort(function(a, b) {
          if (quickFilter === 'hot') {
            const vA = parseInt($(a).data('views'), 10) || 0;
            const vB = parseInt($(b).data('views'), 10) || 0;
            return vB - vA; // Descending
          } else if (quickFilter === 'newest') {
            const dA = parseInt($(a).data('date'), 10) || parseInt($(a).data('story-id'), 10) || 0;
            const dB = parseInt($(b).data('date'), 10) || parseInt($(b).data('story-id'), 10) || 0;
            return dB - dA; // Descending
          } else {
            const idxA = parseInt($(a).data('index'), 10) || 0;
            const idxB = parseInt($(b).data('index'), 10) || 0;
            return idxA - idxB; // Initial order
          }
        });

        // Re-append sorted rows
        $.each($rows, function(idx, row) {
          $tbody.append(row);
        });

        let visibleCount = 0;
        $('#tableAdminStories .adm-story-row').each(function() {
          const $row = $(this);
          const title = String($row.data('title') || '');
          const team = String($row.data('team') || '');
          const author = String($row.data('author') || '');
          const status = String($row.data('status') || '');
          const progress = String($row.data('progress') || '');
          const nominate = String($row.data('nominate') || 'none');

          const matchQuery = !query || title.indexOf(query) !== -1 || team.indexOf(query) !== -1 || author.indexOf(query) !== -1;

          let matchFilter = true;
          if (quickFilter === 'full') {
            matchFilter = (progress === 'hoan_thanh');
          } else if (quickFilter === 'ongoing') {
            matchFilter = (progress !== 'hoan_thanh');
          } else if (quickFilter === 'nominated') {
            matchFilter = (nominate !== 'none' && nominate !== '');
          } else if (quickFilter === 'not_nominated') {
            matchFilter = (nominate === 'none' || !nominate);
          } else if (quickFilter === 'visible') {
            matchFilter = (status === 'publish');
          } else if (quickFilter === 'future') {
            matchFilter = (status === 'future');
          } else if (quickFilter === 'hidden') {
            matchFilter = (status !== 'publish' && status !== 'future');
          }

          if (matchQuery && matchFilter) {
            $row.show();
            visibleCount++;
          } else {
            $row.hide();
          }
        });

        // Dynamic STT numbering for visible rows
        let currentStt = 1;
        $('#tableAdminStories .adm-story-row:visible').each(function() {
          $(this).find('td:first').text(currentStt++);
        });

        // Update counter
        $('#admStoryVisibleCount').text(visibleCount);

        // Empty state
        if (visibleCount === 0) {
          if (!$('#noAdminStoriesRow').length) {
            $tbody.append('<tr id="noAdminStoriesRow"><td colspan="11" style="text-align: center; padding: 40px; color: var(--text-muted); font-size: 13.5px;"><i class="fa-solid fa-filter-circle-xmark" style="font-size: 32px; color: var(--primary-green); margin-bottom: 10px; display: block;"></i> Không tìm thấy bộ truyện nào phù hợp với bộ lọc hiện tại.</td></tr>');
          }
          $('#noAdminStoriesRow').show();
        } else {
          $('#noAdminStoriesRow').remove();
        }
      };

      $('#admStorySearchInput').on('input keyup', function() {
        filterAdminStories();
      });

      $('#admStoryQuickFilter').on('change', function() {
        const val = $(this).val() || 'all';
        $('#admStoryFilterPills .btn-story-filter-pill').removeClass('active');
        $('#admStoryFilterPills .btn-story-filter-pill[data-filter="' + val + '"]').addClass('active');
        filterAdminStories(val);
      });

      $(document).on('click', '#admStoryFilterPills .btn-story-filter-pill', function(e) {
        e.preventDefault();
        const val = $(this).data('filter') || 'all';
        $('#admStoryFilterPills .btn-story-filter-pill').removeClass('active');
        $(this).addClass('active');
        $('#admStoryQuickFilter').val(val);
        filterAdminStories(val);
      });

      // 2. Open Story Detail Hierarchical View
      const openStoryDetail = (storyId) => {
        if (!storyId) return;

        muopToast('Đang tải thông tin chi tiết CSDL bộ truyện...', 'info');

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_admin_action',
            sub_action: 'get_story_details',
            story_id: storyId,
            nonce: muopConfig.nonce
          },
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (!res || !res.success || !res.data) {
              muopAlert(getAjaxMsg(res, 'Không thể tải dữ liệu chi tiết truyện!'), 'error');
              return;
            }

            const data = res.data;

            // Populate Story Metadata
            if (data.thumbnail) {
              $('#storyDetailCoverImg').attr('src', data.thumbnail).show();
            } else {
              $('#storyDetailCoverImg').attr('src', muopConfig.themeUri + '/assets/images/placeholder.jpg').show();
            }

            $('#storyDetailTitle').text(data.title);
            $('#storyDetailAuthor').text(data.author_name || 'Chưa cập nhật');
            if (data.team_url) {
              $('#storyDetailTeam').html('<a href="' + data.team_url + '" target="_blank" class="db-team-link"><i class="fa-solid fa-users" style="font-size:11px;margin-right:4px;color:#8b5cf6;"></i><span>' + (data.team_name || 'Mướp Đắng') + '</span> <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px;opacity:0.6;margin-left:3px;"></i></a>');
            } else {
              $('#storyDetailTeam').text(data.team_name || 'Mướp Đắng');
            }
            $('#storyDetailPostedBy').text(data.posted_by || 'Không rõ');
            $('#storyDetailGenres').text(data.genres || 'Chưa phân loại');
            $('#storyDetailViewsAll').text(data.views_all.toLocaleString('vi-VN'));
            $('#storyDetailViewsMonth').text(data.views_month.toLocaleString('vi-VN'));
            $('#storyDetailDesc').html(data.description || 'Chưa có mô tả.');

            // Badges
            let badgesHtml = '';
            if (data.post_status === 'publish') {
              badgesHtml += '<span class="badge badge-approved"><i class="fa-solid fa-check"></i> Hiển thị công khai</span>';
            } else if (data.post_status === 'pending') {
              badgesHtml += '<span class="badge badge-hot"><i class="fa-solid fa-clock"></i> Chờ duyệt</span>';
            } else {
              badgesHtml += '<span class="badge" style="background:#fee2e2;color:#dc2626;"><i class="fa-solid fa-eye-slash"></i> Đang ẩn (Nháp)</span>';
            }

            badgesHtml += (data.progress === 'hoan_thanh') 
              ? '<span class="badge badge-full"><i class="fa-solid fa-check"></i> Full</span>' 
              : '<span class="badge badge-green"><i class="fa-solid fa-arrows-rotate"></i> Đang ra</span>';

            if (data.nominated && data.nominated !== 'none') {
              const nomName = data.nominated === 'day' ? 'Ngày' : (data.nominated === 'week' ? 'Tuần' : 'Tháng');
              badgesHtml += `<span class="badge badge-hot"><i class="fa-solid fa-star"></i> Đề cử ${nomName}</span>`;
            }

            $('#storyDetailBadges').html(badgesHtml);

            // Story Action Buttons
            $('#btnDetailEditStoryDesc').attr('data-story-id', data.id);
            $('#btnDetailAddChapterLink').attr('href', data.add_chapter_url);
            $('#btnDetailViewPublicLink').attr('href', data.permalink);
            $('#btnSubtableAddChapter').attr('href', data.add_chapter_url);

            const $btnHide = $('#btnDetailToggleHideStory');
            $btnHide.attr('data-story-id', data.id);
            if (data.post_status === 'publish') {
              $btnHide.attr('data-action', 'hide_story').html('<i class="fa-solid fa-eye-slash"></i> Ẩn Truyện');
            } else {
              $btnHide.attr('data-action', 'unhide_story').html('<i class="fa-solid fa-eye"></i> Hiện Truyện');
            }

            // Populate Chapters Sub-Table
            $('#storyDetailChaptersCount').text(data.total_chapters || 0);
            const $tbody = $('#storyDetailChaptersTbody');
            $tbody.empty();

            if (!data.chapters || data.chapters.length === 0) {
              $tbody.html('<tr><td colspan="7" style="text-align: center; padding: 24px; color: var(--text-muted);"><i class="fa-solid fa-circle-info"></i> Truyện này hiện chưa có chương nào. Nhấn "+ Thêm Chương Mới" để đăng chương đầu tiên.</td></tr>');
            } else {
              data.chapters.forEach((ch) => {
                const statusBadge = ch.status === 'publish' 
                  ? '<span class="badge badge-approved"><i class="fa-solid fa-check"></i> Xuất bản</span>' 
                  : '<span class="badge badge-hot"><i class="fa-solid fa-clock"></i> Bản nháp</span>';

                const rowHtml = `
                  <tr class="adm-chapter-subrow" data-chap-num="${ch.number}" data-chap-title="${(ch.title || '').toLowerCase()}">
                    <td style="text-align: center;"><strong>Chương ${ch.number}</strong></td>
                    <td><span style="font-weight: 600; color: var(--text-main);">${ch.title}</span></td>
                    <td><span class="badge badge-green" style="font-size: 11.5px;">${ch.words.toLocaleString('vi-VN')} từ</span></td>
                    <td>${statusBadge}</td>
                    <td style="font-size: 12px; color: var(--text-muted);">${ch.date}</td>
                    <td><i class="fa-solid fa-eye"></i> ${ch.views.toLocaleString('vi-VN')}</td>
                    <td style="text-align: center;">
                      <div class="db-row-actions">
                        <button type="button" class="btn btn-outline btn-xs btn-subtable-edit-chap" data-story-id="${data.id}" data-chapter-id="${ch.id}" title="Chỉnh sửa nội dung chương">
                          <i class="fa-solid fa-file-pen"></i> Sửa
                        </button>
                        <a href="${ch.permalink}" target="_blank" class="btn btn-outline btn-xs" title="Đọc thử chương trên website">
                          <i class="fa-solid fa-arrow-up-right-from-square"></i> Xem
                        </a>
                        <button type="button" class="btn btn-outline btn-xs btn-subtable-delete-chap" data-chapter-id="${ch.id}" data-chapter-num="${ch.number}" data-chapter-title="${ch.title}" data-story-id="${data.id}" title="Xóa chương này" style="color: #ef4444; border-color: #fca5a5;">
                          <i class="fa-solid fa-trash-can"></i> Xóa
                        </button>
                      </div>
                    </td>
                  </tr>
                `;
                $tbody.append(rowHtml);
              });
            }

            // Switch to Detail View
            $('#admStoryMasterView').hide();
            $('#admStoryDetailView').fadeIn(200);

            // Scroll up smoothly
            window.scrollTo({ top: 0, behavior: 'smooth' });
          },
          error: (xhr) => {
            muopAlert('Không thể kết nối đến máy chủ để tải chi tiết truyện!', 'error');
          }
        });
      };

      // Click to open Story Detail
      $(document).on('click', '.btn-open-story-details', function(e) {
        e.preventDefault();
        const storyId = $(this).data('story-id') || $(this).closest('.adm-story-row').data('story-id');
        openStoryDetail(storyId);
      });

      // Back to Story Master
      $('#btnBackToStoryMaster').on('click', function() {
        $('#admStoryDetailView').hide();
        $('#admStoryMasterView').fadeIn(200);
      });

      // In-subtable chapter search
      $('#admChapterSearchInput').on('input keyup', function() {
        const q = $.trim($(this).val()).toLowerCase();
        $('#storyDetailChaptersTbody .adm-chapter-subrow').each(function() {
          const $r = $(this);
          const num = String($r.data('chap-num') || '');
          const title = String($r.data('chap-title') || '');
          if (!q || num.indexOf(q) !== -1 || title.indexOf(q) !== -1) {
            $r.show();
          } else {
            $r.hide();
          }
        });
      });

      // Subtable Edit Chapter button click
      $(document).on('click', '.btn-subtable-edit-chap', function(e) {
        e.preventDefault();
        const storyId = $(this).data('story-id');
        const chapterId = $(this).data('chapter-id');
        
        // Open edit chapter content modal
        $('#modalEditChapterContent').fadeIn(200);
        $('#modalChapterStoryTitleText').text($('#storyDetailTitle').text());

        // Load chapter data directly
        $('#editChapterAlert').hide();
        $.ajax({
          url: getAjaxUrl(),
          type: 'POST',
          data: {
            action: 'muop_get_chapter_data',
            nonce: (typeof muopConfig !== 'undefined' && muopConfig.nonce) ? muopConfig.nonce : '',
            chapter_id: chapterId,
            story_id: storyId,
            is_admin_portal: 1
          },
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success && res.data) {
              const data = res.data;
              $('#editChapterId').val(data.id || chapterId);
              $('#editChapterNumber').val(data.chapter_number || data.number || 1);
              $('#editChapterTitle').val(data.title || '');
              $('#editChapterContent').val(data.content || '');
              $('#editChapterIsDraftInput').val(data.status === 'draft' ? '1' : '0');

              try {
                updateRealtimeWordCounter($('#editChapterContent'), $('#editChapterContentWordCount'), $('#editChapterContentCharCount'));
              } catch (err) {}

              $('#wrapperSelectEditChapter').hide();
              $('#formEditChapterFields').fadeIn(200);
            } else {
              muopAlert(getAjaxMsg(res, 'Không thể tải nội dung chương!'), 'error');
            }
          },
          error: (xhr) => {
            let msg = 'Lỗi kết nối khi tải nội dung chương!';
            if (xhr && xhr.responseJSON) msg = getAjaxMsg(xhr.responseJSON, msg);
            muopAlert(msg, 'error');
          }
        });
      });

      // Subtable Delete Single Chapter click
      $(document).on('click', '.btn-subtable-delete-chap', function(e) {
        e.preventDefault();
        const chapterId = $(this).data('chapter-id');
        const chapterNum = $(this).data('chapter-num');
        const chapterTitle = $(this).data('chapter-title') || '';
        const storyId = $(this).data('story-id');

        muopConfirm(`Bạn có chắc chắn muốn xóa Chương ${chapterNum}: "${chapterTitle}" không? Hành động này sẽ xóa vĩnh viễn nội dung chương!`, () => {
          $.ajax({
            url: muopConfig.ajaxUrl,
            type: 'POST',
            data: {
              action: 'muop_delete_chapter',
              nonce: muopConfig.nonce,
              chapter_id: chapterId,
              is_admin_portal: 1
            },
            success: (res) => {
              if (typeof res === 'string') {
                try { res = JSON.parse(res); } catch (e) {}
              }
              if (res && res.success) {
                muopAlert('Đã xóa chương thành công!', 'success');
                openStoryDetail(storyId);
              } else {
                muopAlert(getAjaxMsg(res, 'Lỗi khi xóa chương!'), 'error');
              }
            }
          });
        }, null, { isDanger: true, confirmText: 'Xóa Chương' });
      });

      // 3. Users Live Search & Role Filter
      const filterAdminUsers = () => {
        const query = $.trim($('#admUserSearchInput').val()).toLowerCase();
        const roleFilter = $('#admUserRoleFilter').val();

        $('#tableAdminUsers .adm-user-row').each(function() {
          const $row = $(this);
          const name = $row.data('name') || '';
          const uname = $row.data('username') || '';
          const email = $row.data('email') || '';
          const role = $row.data('role') || '';

          const matchQuery = !query || name.indexOf(query) !== -1 || uname.indexOf(query) !== -1 || email.indexOf(query) !== -1;
          const matchRole = !roleFilter || role === roleFilter;

          if (matchQuery && matchRole) {
            $row.show();
          } else {
            $row.hide();
          }
        });
      };

      $('#admUserSearchInput').on('input keyup', filterAdminUsers);
      $('#admUserRoleFilter').on('change', filterAdminUsers);

      // 4. Open User Detail Hierarchical View
      const openUserDetail = (userId) => {
        if (!userId) return;

        muopToast('Đang tải thông tin chi tiết người dùng...', 'info');

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_admin_action',
            sub_action: 'get_user_details',
            user_id: userId,
            nonce: muopConfig.nonce
          },
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (!res || !res.success || !res.data) {
              muopAlert(getAjaxMsg(res, 'Không thể tải thông tin người dùng!'), 'error');
              return;
            }

            const data = res.data;

            // Avatar
            if (data.avatar_url) {
              $('#userDetailAvatarBox').html(`<img src="${data.avatar_url}" alt="Avatar" />`);
            } else {
              $('#userDetailAvatarBox').html(`<span>${data.avatar_char}</span>`);
            }

            $('#userDetailDisplayName').text(data.display_name);
            $('#userDetailLogin').text('@' + data.user_login);
            $('#userDetailEmail').text(data.user_email);
            $('#userDetailId').text('#' + data.id);
            $('#userDetailRegistered').text(data.registered);
            $('#userDetailTotalStories').text(data.total_stories || 0);
            $('#userDetailViewsMonth').text((data.total_views_month || 0).toLocaleString('vi-VN'));

            // Badges
            let userBadgesHtml = '';
            if (data.role === 'administrator') {
              userBadgesHtml += '<span class="badge badge-gold"><i class="fa-solid fa-shield-halved"></i> Quản Trị Viên</span>';
            } else if (data.role === 'dich_gia') {
              userBadgesHtml += '<span class="badge badge-green"><i class="fa-solid fa-feather-pointed"></i> Dịch Giả</span>';
            } else {
              userBadgesHtml += '<span class="badge"><i class="fa-solid fa-book-reader"></i> Độc Giả</span>';
            }

            userBadgesHtml += data.is_locked 
              ? '<span class="badge badge-hot"><i class="fa-solid fa-lock"></i> Đã khóa tài khoản</span>' 
              : '<span class="badge badge-full"><i class="fa-solid fa-unlock"></i> Đang hoạt động</span>';

            $('#userDetailBadges').html(userBadgesHtml);

            // Bank details
            if (data.bank_number) {
              $('#userDetailBankText').html(`<strong>${data.bank_name}</strong> - <span style="font-family: monospace; font-size: 13px;">${data.bank_number}</span> (Chủ TK: <strong>${data.bank_holder}</strong>)`);
            } else {
              $('#userDetailBankText').text('Chưa cập nhật tài khoản ngân hàng.');
            }

            // Role selector
            $('#selectDetailUserRole').val(data.role).off('change').on('change', function() {
              const newRole = $(this).val();
              $(this).trigger('change_role', [data.id, newRole]);
            });

            // Toggle lock button
            const $btnLock = $('#btnDetailToggleLockUser');
            $btnLock.attr('data-user-id', data.id);
            if (data.is_locked) {
              $btnLock.removeClass('btn-danger').addClass('btn-primary').html('<i class="fa-solid fa-unlock"></i> Mở Khóa Tài Khoản');
            } else {
              $btnLock.removeClass('btn-primary').addClass('btn-danger').html('<i class="fa-solid fa-lock"></i> Khóa Tài Khoản');
            }

            // Authored Stories Subtable
            $('#userDetailStoriesCount').text(data.total_stories || 0);
            const $tbody = $('#userDetailStoriesTbody');
            $tbody.empty();

            if (!data.stories || data.stories.length === 0) {
              $tbody.html('<tr><td colspan="7" style="text-align: center; padding: 24px; color: var(--text-muted);"><i class="fa-solid fa-circle-info"></i> Người dùng này chưa đăng tải bộ truyện nào trên hệ thống.</td></tr>');
            } else {
              data.stories.forEach((st) => {
                const stBadge = st.status === 'publish'
                  ? '<span class="badge badge-approved"><i class="fa-solid fa-check"></i> Hiển thị</span>'
                  : '<span class="badge badge-hot"><i class="fa-solid fa-clock"></i> Chờ duyệt</span>';

                const rowHtml = `
                  <tr>
                    <td>
                      <a href="javascript:void(0)" class="btn-open-story-details" data-story-id="${st.id}" style="font-weight: 700; color: var(--primary-green);">
                        ${st.title}
                      </a>
                    </td>
                    <td>${stBadge}</td>
                    <td><strong>${st.chapters}</strong> chương</td>
                    <td><i class="fa-solid fa-eye"></i> ${st.views_month.toLocaleString('vi-VN')}</td>
                    <td><i class="fa-solid fa-eye"></i> ${st.views_total.toLocaleString('vi-VN')}</td>
                    <td style="color: #2E7D32; font-weight: 700;">${st.salary.toLocaleString('vi-VN')} VNĐ</td>
                    <td style="text-align: center;">
                      <button type="button" class="btn btn-outline btn-xs btn-open-story-details" data-story-id="${st.id}">
                        <i class="fa-solid fa-folder-tree"></i> Xem Chi Tiết
                      </button>
                    </td>
                  </tr>
                `;
                $tbody.append(rowHtml);
              });
            }

            // Switch view
            $('#admUserMasterView').hide();
            $('#admUserDetailView').fadeIn(200);

            window.scrollTo({ top: 0, behavior: 'smooth' });
          },
          error: (xhr) => {
            muopAlert('Không thể kết nối đến máy chủ để tải chi tiết người dùng!', 'error');
          }
        });
      };

      // Click to open User Detail
      $(document).on('click', '.btn-open-user-details', function(e) {
        e.preventDefault();
        const userId = $(this).data('user-id') || $(this).closest('.adm-user-row').data('user-id');
        openUserDetail(userId);
      });

      // Back to User Master
      $('#btnBackToUserMaster').on('click', function() {
        $('#admUserDetailView').hide();
        $('#admUserMasterView').fadeIn(200);
      });

      // Lock user from detail view
      $('#btnDetailToggleLockUser').on('click', function(e) {
        e.preventDefault();
        const userId = $(this).attr('data-user-id');
        if (!userId) return;

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_admin_action',
            sub_action: 'toggle_user_status',
            user_id: userId,
            nonce: muopConfig.nonce
          },
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              muopAlert(getAjaxMsg(res, 'Đã cập nhật trạng thái tài khoản!'), 'success');
              openUserDetail(userId);
            } else {
              muopAlert(getAjaxMsg(res, 'Lỗi thao tác!'), 'error');
            }
          }
        });
      });

      // 5. Genre Quick Search in Toolbar
      $('#admGenreSearchInput').on('input keyup', function() {
        const q = $.trim($(this).val()).toLowerCase();
        let visibleCount = 0;
        $('#admGenresGrid .adm-genre-card').each(function() {
          const name = $(this).data('name') || '';
          if (!q || name.indexOf(q) !== -1) {
            $(this).show();
            visibleCount++;
          } else {
            $(this).hide();
          }
        });

        if (visibleCount === 0) {
          $('#admNoGenreFound').show();
        } else {
          $('#admNoGenreFound').hide();
        }
      });
    }
  };

  // 10. DROPDOWN MENUS (USER ACCOUNT & THỂ LOẠI CLICK TOGGLES)
  const DropdownManager = {
    init() {
      // Toggle User Account Dropdown on click / tap
      $(document).on('click', '#userProfileToggle, .user-profile-badge, #userMenuWrapper .user-avatar-mini', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $wrapper = $('#userMenuWrapper');
        const $panel = $('#userDropdownPanel');
        const isOpen = $wrapper.hasClass('active') || $panel.hasClass('show');

        // Close other dropdowns if open
        $('#notifDropdownPanel').removeClass('show');
        $('#notifToggleBtn').attr('aria-expanded', 'false');
        $('.genre-dropdown-menu').removeClass('show');
        $('.has-genre-dropdown').removeClass('open');

        if (isOpen) {
          $wrapper.removeClass('active');
          $panel.removeClass('show');
          $('#userProfileToggle').attr('aria-expanded', 'false');
        } else {
          $wrapper.addClass('active');
          $panel.addClass('show');
          $('#userProfileToggle').attr('aria-expanded', 'true');
        }
      });

      // Prevent closing when clicking inside user panel, allowing direct navigation
      $(document).on('click', '#userDropdownPanel, .user-dropdown-panel', function(e) {
        e.stopPropagation();
      });

      // Hover grace timer cho Thể loại dropdown (đứng yên không bị tắt khi di chuột cuộn kéo)
      let genreDropdownHoverTimer = null;
      $(document).on('mouseenter', '.has-genre-dropdown', function() {
        if (genreDropdownHoverTimer) {
          clearTimeout(genreDropdownHoverTimer);
          genreDropdownHoverTimer = null;
        }
        $(this).addClass('hovered');
      });

      $(document).on('mouseleave', '.has-genre-dropdown', function() {
        const $this = $(this);
        genreDropdownHoverTimer = setTimeout(() => {
          $this.removeClass('hovered');
        }, 260);
      });

      // Kéo cuộn lên xuống mượt mà bằng chuột cho Menu Thể Loại
      function initVerticalDragScroll(selector) {
        $(selector).each(function() {
          const el = this;
          if (el._hasDragScroll) return;
          el._hasDragScroll = true;

          let isDown = false;
          let startY = 0;
          let scrollTop = 0;
          let isDragging = false;

          el.addEventListener('mousedown', function(e) {
            if (e.button !== 0) return; // Chỉ bắt chuột trái
            isDown = true;
            isDragging = false;
            startY = e.pageY - el.getBoundingClientRect().top;
            scrollTop = el.scrollTop;
          });

          window.addEventListener('mouseup', function() {
            if (!isDown) return;
            isDown = false;
            setTimeout(() => {
              isDragging = false;
            }, 60);
          });

          el.addEventListener('mousemove', function(e) {
            if (!isDown) return;
            const currentY = e.pageY - el.getBoundingClientRect().top;
            const walk = (currentY - startY) * 1.25;
            if (Math.abs(walk) > 4) {
              isDragging = true;
              el.scrollTop = scrollTop - walk;
            }
          });

          el.addEventListener('click', function(e) {
            if (isDragging) {
              e.preventDefault();
              e.stopPropagation();
            }
          }, true);
        });
      }

      // Khởi tạo kéo cuộn dọc
      initVerticalDragScroll('.genre-dropdown-menu, .genre-selector-body');
      $(document).on('click', '#navGenreToggle, #genreSelectorHeader, #btnGenreSelectorArrow', function() {
        setTimeout(() => {
          initVerticalDragScroll('.genre-dropdown-menu, .genre-selector-body');
        }, 60);
      });

      // Toggle Thể loại dropdown on click
      $(document).on('click', '#navGenreToggle, .nav-genre-btn', function(e) {
        const $li = $(this).closest('.has-genre-dropdown');
        const $menu = $li.find('.genre-dropdown-menu');
        const isOpen = $li.hasClass('open') || $menu.hasClass('show');

        e.preventDefault();
        e.stopPropagation();

        // Close user dropdown & other dropdowns if open
        $('#userMenuWrapper').removeClass('active');
        $('#userDropdownPanel').removeClass('show');
        $('#userProfileToggle').attr('aria-expanded', 'false');
        $('#notifDropdownPanel').removeClass('show');
        $('#notifToggleBtn').attr('aria-expanded', 'false');
        $('.has-tu-truyen-dropdown').removeClass('open');
        $('.tu-truyen-dropdown-menu').removeClass('show');

        if (isOpen) {
          $li.removeClass('open');
          $menu.removeClass('show');
        } else {
          $li.addClass('open');
          $menu.addClass('show');
        }
      });

      $(document).on('click', '.genre-dropdown-menu', function(e) {
        e.stopPropagation();
      });

      // Toggle Tủ truyện dropdown on click
      $(document).on('click', '#navTuTruyenToggle, .nav-tu-truyen-btn', function(e) {
        const $li = $(this).closest('.has-tu-truyen-dropdown');
        const $menu = $li.find('.tu-truyen-dropdown-menu');
        const isOpen = $li.hasClass('open') || $menu.hasClass('show');

        e.preventDefault();
        e.stopPropagation();

        // Close other dropdowns if open
        $('#userMenuWrapper').removeClass('active');
        $('#userDropdownPanel').removeClass('show');
        $('#userProfileToggle').attr('aria-expanded', 'false');
        $('#notifDropdownPanel').removeClass('show');
        $('#notifToggleBtn').attr('aria-expanded', 'false');
        $('.has-genre-dropdown').removeClass('open');
        $('.genre-dropdown-menu').removeClass('show');

        if (isOpen) {
          $li.removeClass('open');
          $menu.removeClass('show');
        } else {
          $li.addClass('open');
          $menu.addClass('show');
        }
      });

      $(document).on('click', '.tu-truyen-dropdown-menu', function(e) {
        e.stopPropagation();
      });

      // Close all dropdowns when clicking anywhere outside
      $(document).on('click', function(e) {
        if (!$(e.target).closest('#userMenuWrapper').length) {
          $('#userMenuWrapper').removeClass('active');
          $('#userDropdownPanel').removeClass('show');
          $('#userProfileToggle').attr('aria-expanded', 'false');
        }
        if (!$(e.target).closest('.has-genre-dropdown').length) {
          $('.has-genre-dropdown').removeClass('open');
          $('.genre-dropdown-menu').removeClass('show');
        }
        if (!$(e.target).closest('.has-tu-truyen-dropdown').length) {
          $('.has-tu-truyen-dropdown').removeClass('open');
          $('.tu-truyen-dropdown-menu').removeClass('show');
        }
        if (!$(e.target).closest('#notifWrapper').length) {
          $('#notifDropdownPanel').removeClass('show');
          $('#notifToggleBtn').attr('aria-expanded', 'false');
        }
      });

      // Escape key to close open dropdowns
      $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
          $('#userMenuWrapper').removeClass('active');
          $('#userDropdownPanel').removeClass('show');
          $('#userProfileToggle').attr('aria-expanded', 'false');
          $('.has-genre-dropdown').removeClass('open');
          $('.genre-dropdown-menu').removeClass('show');
          $('.has-tu-truyen-dropdown').removeClass('open');
          $('.tu-truyen-dropdown-menu').removeClass('show');
          $('#notifDropdownPanel').removeClass('show');
          $('#notifToggleBtn').attr('aria-expanded', 'false');
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

      // Toggle Mobile Tủ Truyện Accordion Submenu
      $(document).on('click', '#drawerTuTruyenToggle', function(e) {
        e.preventDefault();
        $('#drawerTuTruyenItem').toggleClass('open');
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

  // 11.5 DASHBOARD & ADMIN TAB MANAGER (UNIVERSAL)
  const TabManager = {
    init() {
      // Redirect legacy hashes to dedicated standalone pages on profile
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

          // Sync Admin Database Portal Topbar & Toolbar
          const tabLabelMap = {
            'admTabStories': 'Quản lý truyện',
            'admTabAnalytics': 'Thống kê & Phân tích lượt đọc',
            'admTabTeams': 'Quản lý team dịch',
            'admTabSplitter': 'Tách truyện tự động',
            'admTabVivuCrawler': 'Công cụ lấy crawl tự động',
            'admTabUsers': 'Quản lý user',
            'admTabAffiliate': 'Link Shopee & TikTok',
            'admTabSalaries': 'Tiền lương dịch giả',
            'admTabGenres': 'Thể loại truyện',
            'admTabComments': 'Bình luận & Đánh giá',
            'admTabReports': 'Báo lỗi chương',
            'admTabBroadcast': 'Thông báo hệ thống'
          };

          if (tabLabelMap[cleanId]) {
            $('#admActiveTabLabel').text(tabLabelMap[cleanId]);
            
            // Sync toolbar
            $('.admin-toolbar-group').hide();
            if (cleanId === 'admTabStories') {
              $('#toolbarGroupStories').show();
            } else if (cleanId === 'admTabUsers') {
              $('#toolbarGroupUsers').show();
            } else if (cleanId === 'admTabGenres') {
              $('#toolbarGroupGenres').show();
            } else if (cleanId === 'admTabTeams') {
              $('#toolbarGroupTeams').show();
            } else if (cleanId === 'admTabSplitter') {
              $('#toolbarGroupSplitter').show();
            } else if (cleanId === 'admTabVivuCrawler') {
              $('#toolbarGroupVivuCrawler').show();
            } else if (cleanId === 'admTabAnalytics') {
              $('#toolbarGroupAnalytics').show();
            } else {
              $('#toolbarGroupGeneric').show();
            }

            // Close mobile sidebar
            $('#adminDbSidebar').removeClass('mobile-open');
          }
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

      // Delegated click for tab buttons with data-tab
      $(document).on('click', '.dash-tab-btn[data-tab]', function(e) {
        e.preventDefault();
        const tabId = $(this).attr('data-tab');
        if (!tabId) return;
        switchTab(tabId);
        if (history.replaceState) {
          history.replaceState(null, null, '#' + tabId);
        } else {
          window.location.hash = '#' + tabId;
        }
      });
    }
  };

  // 12. USER PROFILE & PASSWORD SETTINGS
  const ProfileManager = {
    init() {
      if (!$('#profileUpdateForm').length && !$('#passwordChangeForm').length) {
        return;
      }

      this.initAvatarPicker();
      this.initProfileForm();
      this.initPasswordForm();
      this.initPasswordToggles();
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

        const ajaxUrl = getAjaxUrl();
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

        muopConfirm(`Bạn có muốn đổi trạng thái bộ truyện này sang "${nextLabel}" không?`, () => {
          const ajaxUrl = getAjaxUrl();
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
                muopAlert(getAjaxMsg(res, 'Đã cập nhật trạng thái truyện thành công!'), 'success');
              } else {
                muopAlert(getAjaxMsg(res, 'Lỗi cập nhật trạng thái!'), 'error');
              }
            },
            error: (xhr) => {
              let errorMsg = 'Lỗi kết nối máy chủ!';
              if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
              else if (xhr && xhr.responseText) errorMsg = getAjaxMsg(xhr.responseText, errorMsg);
              muopAlert(errorMsg, 'error');
            }
          });
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
        const ajaxUrl = getAjaxUrl();

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
              muopAlert(getAjaxMsg(res, 'Vui lòng đăng nhập để lưu team vào danh sách yêu thích!'), 'info');
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false);
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            muopAlert(errorMsg, 'error');
          }
        });
      });

      // Toggle Follow Team (Theo dõi / Hủy theo dõi Team dịch)
      $('#btnToggleFollowTeam').on('click', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const teamId = $btn.data('team-id');
        const teamName = $btn.data('team-name');
        const $icon = $('#followTeamBellIcon');
        const $text = $('#followTeamBtnText');

        if (!teamId && !teamName) return;

        $btn.prop('disabled', true);
        const nonce = (typeof muopConfig !== 'undefined' ? muopConfig.nonce : '');
        const ajaxUrl = getAjaxUrl();

        $.ajax({
          url: ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_toggle_follow_team',
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
              const isFollowing = res.data && res.data.following;
              if (isFollowing) {
                $btn.removeClass('btn-secondary').addClass('btn-primary');
                $icon.removeClass('fa-regular').addClass('fa-solid');
                $text.text('Đang Theo Dõi Team');
                $btn.attr('title', 'Đang theo dõi team dịch này (Bấm để hủy)');
              } else {
                $btn.removeClass('btn-primary').addClass('btn-secondary');
                $icon.removeClass('fa-solid').addClass('fa-regular');
                $text.text('Theo Dõi Team');
                $btn.attr('title', 'Theo dõi team dịch để nhận thông báo truyện mới');
              }
              muopAlert(getAjaxMsg(res, isFollowing ? 'Đã theo dõi team dịch thành công!' : 'Đã hủy theo dõi team dịch.'), isFollowing ? 'success' : 'info');
            } else {
              muopAlert(getAjaxMsg(res, 'Vui lòng đăng nhập để theo dõi team dịch!'), 'info');
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false);
            let errorMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errorMsg = getAjaxMsg(xhr.responseJSON, errorMsg);
            muopAlert(errorMsg, 'error');
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

        muopConfirm('Bạn có chắc chắn muốn bỏ lưu bộ truyện này khỏi Tủ truyện?', () => {
          $btn.prop('disabled', true);
          const nonce = (typeof muopConfig !== 'undefined' ? muopConfig.nonce : '');
          const ajaxUrl = getAjaxUrl();

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
              muopAlert('Lỗi kết nối máy chủ!', 'error');
            }
          });
        });
      });

      // Remove from favorites in page-tu-truyen
      $(document).on('click', '.btn-remove-from-favorites', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const $btn = $(this);
        const storyId = $btn.data('story-id');
        const $item = $(`#tuTruyenFavItem-${storyId}`);

        if (!storyId) return;

        muopConfirm('Bạn có chắc chắn muốn bỏ yêu thích bộ truyện này?', () => {
          $btn.prop('disabled', true);
          const nonce = (typeof muopConfig !== 'undefined' ? muopConfig.nonce : '');
          const ajaxUrl = getAjaxUrl();

          $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
              action: 'muop_toggle_favorite_story',
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
              muopAlert('Lỗi kết nối máy chủ!', 'error');
            }
          });
        });
      });

      // Remove from following in page-tu-truyen
      $(document).on('click', '.btn-remove-from-following', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const $btn = $(this);
        const storyId = $btn.data('story-id');
        const $item = $(`#tuTruyenFollowItem-${storyId}`);

        if (!storyId) return;

        muopConfirm('Bạn có chắc chắn muốn hủy theo dõi bộ truyện này?', () => {
          $btn.prop('disabled', true);
          const nonce = (typeof muopConfig !== 'undefined' ? muopConfig.nonce : '');
          const ajaxUrl = getAjaxUrl();

          $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
              action: 'muop_toggle_follow_story',
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
              muopAlert('Lỗi kết nối máy chủ!', 'error');
            }
          });
        });
      });

      // Remove team favorite in page-tu-truyen
      $(document).on('click', '.btn-remove-team-fav', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const $btn = $(this);
        const teamId = $btn.data('team-id');
        const $item = $(`#tuTeamFavItem-${teamId}`);

        if (!teamId) return;

        muopConfirm('Bạn có chắc chắn muốn bỏ yêu thích team dịch này?', () => {
          $btn.prop('disabled', true);
          const nonce = (typeof muopConfig !== 'undefined' ? muopConfig.nonce : '');
          const ajaxUrl = getAjaxUrl();

          $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
              action: 'muop_toggle_favorite_team',
              nonce: nonce,
              team_id: teamId
            },
            success: (res) => {
              $item.fadeOut(250, function() {
                $(this).remove();
                if ($('#tuTruyenTeamsGrid .tu-team-card').length === 0) {
                  location.reload();
                }
              });
            },
            error: () => {
              $btn.prop('disabled', false);
              muopAlert('Lỗi kết nối máy chủ!', 'error');
            }
          });
        });
      });

      // Remove team following in page-tu-truyen
      $(document).on('click', '.btn-remove-team-follow', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const $btn = $(this);
        const teamId = $btn.data('team-id');
        const $item = $(`#tuTeamFollowItem-${teamId}`);

        if (!teamId) return;

        muopConfirm('Bạn có chắc chắn muốn hủy theo dõi team dịch này?', () => {
          $btn.prop('disabled', true);
          const nonce = (typeof muopConfig !== 'undefined' ? muopConfig.nonce : '');
          const ajaxUrl = getAjaxUrl();

          $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
              action: 'muop_toggle_follow_team',
              nonce: nonce,
              team_id: teamId
            },
            success: (res) => {
              $item.fadeOut(250, function() {
                $(this).remove();
                if ($('#tuTruyenTeamsGrid .tu-team-card').length === 0) {
                  location.reload();
                }
              });
            },
            error: () => {
              $btn.prop('disabled', false);
              muopAlert('Lỗi kết nối máy chủ!', 'error');
            }
          });
        });
      });

      // Remove single reading history item
      $(document).on('click', '.btn-remove-history-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const $btn = $(this);
        const storyId = $btn.data('story-id');
        const $item = $(`#tuHistoryItem-${storyId}`);

        if (!storyId) return;

        muopConfirm('Bạn có chắc chắn muốn xóa bộ truyện này khỏi lịch sử đọc?', () => {
          $btn.prop('disabled', true);
          const nonce = (typeof muopConfig !== 'undefined' ? muopConfig.nonce : '');
          const ajaxUrl = getAjaxUrl();

          $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
              action: 'muop_remove_reading_history',
              nonce: nonce,
              story_id: storyId
            },
            success: (res) => {
              $item.fadeOut(250, function() {
                $(this).remove();
                if ($('#tuTruyenHistoryList .tu-history-item').length === 0) {
                  location.reload();
                }
              });
            },
            error: () => {
              $btn.prop('disabled', false);
              muopAlert('Lỗi kết nối máy chủ!', 'error');
            }
          });
        });
      });

      // Clear all reading history
      $(document).on('click', '#btnClearAllHistory, .btn-clear-all-history', function(e) {
        e.preventDefault();
        muopConfirm('Bạn có chắc chắn muốn xóa TOÀN BỘ lịch sử đọc truyện?', () => {
          const nonce = (typeof muopConfig !== 'undefined' ? muopConfig.nonce : '');
          const ajaxUrl = getAjaxUrl();

          $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
              action: 'muop_clear_all_reading_history',
              nonce: nonce
            },
            success: () => {
              location.reload();
            },
            error: () => {
              muopAlert('Lỗi kết nối máy chủ!', 'error');
            }
          });
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
        const currentBio = $('#teamHeroBioDisplay').attr('data-raw-bio') || $('#teamHeroBioDisplay').text().trim();
        $('#inputSingleTeamBio').val(currentBio);
        $('#modalEditSingleTeamBio').css('display', 'flex');
        setTimeout(() => $('#inputSingleTeamBio').trigger('focus'), 100);
      });

      // D. Mở popup chung: Quản Lý Toàn Bộ Team (Hình 2 - nút Chỉnh Sửa Team)
      $('#btnOpenEditTeamModal').on('click', function(e) {
        e.preventDefault();
        $('#editTeamAlert').hide();
        $('#inputTeamName').val($('#teamHeroNameDisplay').text().trim());
        const currentBio = $('#teamHeroBioDisplay').attr('data-raw-bio') || $('#teamHeroBioDisplay').text().trim();
        $('#inputTeamBio').val(currentBio);
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
        const ajaxUrl = getAjaxUrl();

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
                const bioRaw = (res.data.team_bio || '').trim();
                $('#teamHeroBioDisplay').attr('data-raw-bio', bioRaw);
                const bioLines = bioRaw.split(/\r?\n/).map(l => l.trim()).filter(Boolean);
                if (bioLines.length > 0) {
                  const formattedHtml = bioLines.map(l => $('<p>').text(l).prop('outerHTML')).join('');
                  $('#teamHeroBioDisplay').html(formattedHtml);
                } else {
                  $('#teamHeroBioDisplay').html('');
                }
                $('#inputSingleTeamBio').val(bioRaw);
                $('#inputTeamBio').val(bioRaw);
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
      // 2. Đăng truyện từ bản nháp
      $(document).on('click', '.btn-publish-draft', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const storyId = $btn.data('story-id');

        muopConfirm('Bạn có chắc chắn muốn đăng bộ truyện này lên hệ thống?', () => {
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
                muopAlert(getAjaxMsg(res, 'Đã gửi đăng truyện thành công!'), 'success', () => {
                  window.location.reload();
                });
              } else {
                muopAlert(getAjaxMsg(res, 'Lỗi đăng truyện!'), 'error');
                $btn.prop('disabled', false).html('<i class="fa-solid fa-cloud-arrow-up"></i> Đăng Lên');
              }
            },
            error: (xhr) => {
              let err = 'Lỗi kết nối máy chủ!';
              if (xhr && xhr.responseJSON) err = getAjaxMsg(xhr.responseJSON, err);
              muopAlert(err, 'error');
              $btn.prop('disabled', false).html('<i class="fa-solid fa-cloud-arrow-up"></i> Đăng Lên');
            }
          });
        });
      });

      // 2.1 Dịch Giả & Admin Ẩn hoặc Bỏ Ẩn Truyện
      $(document).on('click', '.btn-translator-toggle-hide', function(e) {
        e.preventDefault();
        $('.story-actions-dropdown').removeClass('is-open');
        const $btn = $(this);
        const storyId = $btn.data('story-id');
        const actionType = $btn.data('action') || 'hide'; // 'hide' or 'unhide'
        const storyTitle = $btn.data('story-title') || 'bộ truyện này';

        if (actionType === 'hide') {
          muopConfirm(`Bạn có chắc chắn muốn ẩn truyện "${storyTitle}" không? Sau khi ẩn, độc giả sẽ không thể xem hoặc tìm kiếm truyện này trên website.`, () => {
            $btn.prop('disabled', true);
            $.ajax({
              url: muopConfig.ajaxUrl,
              type: 'POST',
              data: {
                action: 'muop_toggle_story_hide',
                nonce: muopConfig.nonce,
                story_id: storyId,
                action_type: 'hide'
              },
              success: (res) => {
                if (typeof res === 'string') {
                  try { res = JSON.parse(res); } catch (e) {}
                }
                if (res && res.success) {
                  muopAlert(getAjaxMsg(res, 'Đã ẩn truyện thành công!'), 'success', () => {
                    window.location.reload();
                  });
                } else {
                  muopAlert(getAjaxMsg(res, 'Lỗi ẩn truyện!'), 'error');
                  $btn.prop('disabled', false);
                }
              },
              error: (xhr) => {
                let err = 'Lỗi kết nối máy chủ!';
                if (xhr && xhr.responseJSON) err = getAjaxMsg(xhr.responseJSON, err);
                muopAlert(err, 'error');
                $btn.prop('disabled', false);
              }
            });
          }, null, {
            title: 'Xác Nhận Ẩn Truyện',
            type: 'warning',
            confirmText: 'Ẩn Truyện',
            cancelText: 'Hủy'
          });
        } else {
          muopConfirm(`Bạn có chắc chắn muốn bỏ ẩn và hiển thị công khai lại truyện "${storyTitle}" trên website?`, () => {
            $btn.prop('disabled', true);
            $.ajax({
              url: muopConfig.ajaxUrl,
              type: 'POST',
              data: {
                action: 'muop_toggle_story_hide',
                nonce: muopConfig.nonce,
                story_id: storyId,
                action_type: 'unhide'
              },
              success: (res) => {
                if (typeof res === 'string') {
                  try { res = JSON.parse(res); } catch (e) {}
                }
                if (res && res.success) {
                  muopAlert(getAjaxMsg(res, 'Đã hiển thị công khai truyện thành công!'), 'success', () => {
                    window.location.reload();
                  });
                } else {
                  muopAlert(getAjaxMsg(res, 'Lỗi thao tác!'), 'error');
                  $btn.prop('disabled', false);
                }
              },
              error: (xhr) => {
                let err = 'Lỗi kết nối máy chủ!';
                if (xhr && xhr.responseJSON) err = getAjaxMsg(xhr.responseJSON, err);
                muopAlert(err, 'error');
                $btn.prop('disabled', false);
              }
            });
          }, null, {
            title: 'Xác Nhận Hiện Truyện',
            type: 'warning',
            confirmText: 'Hiện Truyện',
            cancelText: 'Hủy'
          });
        }
      });

      // 3. Mở Modal Chỉnh Sửa Thông Tin Truyện (Popup 1)
      $(document).on('click', '.btn-edit-story, .btn-action-edit-story-desc', function(e) {
        e.preventDefault();
        $('.story-actions-dropdown').removeClass('is-open');
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

              // Hẹn giờ đăng truyện (chuẩn Việt Nam 24h)
              if (data.post_status === 'future' || data.scheduled_time) {
                $('#editToggleScheduleStory').prop('checked', true);
                $('#editScheduleTimeCollapse').show();
                if (data.scheduled_formatted) {
                  $('#editStoryCurrentScheduleBadge').text('Đang hẹn: ' + data.scheduled_formatted).show();
                }
                if (data.scheduled_time) {
                  const sParts = data.scheduled_time.replace('T', ' ').split(' ');
                  if (sParts.length >= 2) {
                    $('#editStorySchedDate').val(sParts[0]);
                    const tP = sParts[1].split(':');
                    if (tP.length >= 1) $('#editStorySchedHour').val(tP[0].padStart(2, '0'));
                    if (tP.length >= 2) {
                      const rM = Math.floor(parseInt(tP[1], 10) / 5) * 5;
                      $('#editStorySchedMinute').val(String(rM).padStart(2, '0'));
                    }
                  }
                  if (typeof muopSyncEditStorySchedule === 'function') {
                    muopSyncEditStorySchedule();
                  }
                }
              } else {
                $('#editToggleScheduleStory').prop('checked', false);
                $('#editScheduleTimeCollapse').hide();
                $('#editStoryCurrentScheduleBadge').hide();
              }
            } else {
              muopAlert(getAjaxMsg(res, 'Không thể tải thông tin truyện!'), 'error');
              $modal.hide();
            }
          },
          error: (xhr) => {
            let err = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) err = getAjaxMsg(xhr.responseJSON, err);
            muopAlert(err, 'error');
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
          url: getAjaxUrl(),
          type: 'POST',
          data: {
            action: 'muop_get_chapter_data',
            nonce: (typeof muopConfig !== 'undefined' && muopConfig.nonce) ? muopConfig.nonce : '',
            chapter_id: chapId
          },
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success && res.data) {
              const data = res.data;
              $('#editChapterNumber').val(data.chapter_number || data.number || 1);
              $('#editChapterTitle').val(data.title || '');
              $('#editChapterContent').val(data.content || '');
              try {
                updateRealtimeWordCounter($('#editChapterContent'), $('#editChapterContentWordCount'), $('#editChapterContentCharCount'));
              } catch (cntErr) {}
              $form.fadeIn(200);
            } else {
              muopAlert(getAjaxMsg(res, 'Không thể tải nội dung chương!'), 'error');
            }
          },
          error: (xhr) => {
            let msg = 'Lỗi kết nối khi tải nội dung chương!';
            if (xhr && xhr.responseJSON) msg = getAjaxMsg(xhr.responseJSON, msg);
            muopAlert(msg, 'error');
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

        // BẮT BUỘC DUYỆT NỘI DUNG KHI CẬP NHẬT CHƯƠNG (NỘI DUNG OKE MỚI ĐƯỢC LƯU)
        if (!isDraft && typeof SensitiveWordModerator !== 'undefined') {
          const $editContent = $('#editChapterContent');
          const $editTitle = $('#editChapterTitle');
          const fullText = ($editTitle.val() || '') + '\n' + ($editContent.val() || '');

          const scanResult = SensitiveWordModerator.quickScan(fullText);
          if (scanResult.foundMap.size > 0) {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Cập Nhật Chương');

            SensitiveWordModerator.scanAndOpen($editContent);

            $alert.removeClass('alert-success').addClass('alert-error').html(
              '<i class="fa-solid fa-triangle-exclamation"></i> <strong>Chưa thể lưu chương!</strong> Phát hiện <strong>' + 
              scanResult.foundMap.size + ' từ nhạy cảm (' + scanResult.totalMatches + ' vị trí vi phạm)</strong>. Vui lòng xem bảng đánh dấu bên dưới và sửa trước khi lưu!'
            ).fadeIn();

            return false;
          }
        }

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
                $('#modalEditChapterContent').fadeOut(250);
              }, 1200);
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
          url: getAjaxUrl(),
          type: 'POST',
          data: {
            action: 'muop_get_chapter_data',
            chapter_id: chapterId,
            story_id: storyId,
            is_admin_portal: (window.location.pathname.indexOf('quan-ly-admin') !== -1 || window.location.search.indexOf('from=admin') !== -1) ? 1 : 0,
            nonce: (typeof muopConfig !== 'undefined' && muopConfig.nonce) ? muopConfig.nonce : ''
          },
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success && res.data) {
              const data = res.data;
              $('#editChapterAlert').hide();
              $('#editChapterId').val(data.id || chapterId);
              $('#editChapterNumber').val(data.chapter_number || data.number || chapterNum || 1);
              $('#editChapterTitle').val(data.title || chapterTitle || '');
              $('#editChapterContent').val(data.content || '');
              try {
                updateRealtimeWordCounter($('#editChapterContent'), $('#editChapterContentWordCount'), $('#editChapterContentCharCount'));
              } catch (cntErr) {
                console.warn('Word counter error:', cntErr);
              }
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
              const msg = (res && res.data && res.data.message) || (res && res.message) || 'Không thể tải nội dung chương!';
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

      // =========================================================================
      // STORY ACTIONS 3-DOTS DROPDOWN & ACTIONS
      // =========================================================================

      function closeStoryDropdowns() {
        $('.story-actions-dropdown, .user-actions-dropdown, .genre-actions-dropdown').each(function() {
          $(this).removeClass('is-open dropup').css('z-index', '');
          $(this).find('.story-dropdown-menu').css({
            top: '',
            left: '',
            right: '',
            bottom: '',
            zIndex: '',
            display: 'none',
            visibility: ''
          });
        });
        $('.adm-genre-card').removeClass('has-open-dropdown').css('z-index', '');
        $('tr').removeClass('has-open-dropdown').css('z-index', '');
      }

      // Mở/Đóng Dropdown 3 chấm của truyện, người dùng & thể loại (Tự động tính toán vị trí fixed hiển thị liền kề nút bấm)
      $(document).on('click', '.btn-story-dropdown-trigger, .btn-user-dropdown-trigger, .btn-genre-dropdown-trigger', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const $btn = $(this);
        const $dropdown = $btn.closest('.story-actions-dropdown, .user-actions-dropdown, .genre-actions-dropdown');
        const wasOpen = $dropdown.hasClass('is-open');

        // Đóng các dropdown khác đang mở
        closeStoryDropdowns();

        // Nếu trước đó đang mở thì bấm vào là đóng
        if (wasOpen) {
          return;
        }

        const $menu = $dropdown.find('.story-dropdown-menu');
        if (!$menu.length) return;

        // Đánh dấu mở và nâng z-index của card/hàng cha lên cao nhất
        $dropdown.addClass('is-open').css('z-index', '100000');
        const $card = $btn.closest('.adm-genre-card');
        if ($card.length) {
          $card.addClass('has-open-dropdown').css('z-index', '99999');
        }
        const $tr = $btn.closest('tr');
        if ($tr.length) {
          $tr.addClass('has-open-dropdown').css('z-index', '99999');
        }

        // Đo kích thước thực tế của menu
        $menu.css({
          display: 'block',
          visibility: 'hidden',
          position: 'fixed',
          top: '0px',
          left: '0px',
          right: 'auto',
          bottom: 'auto',
          zIndex: '100001'
        });

        const menuHeight = $menu.outerHeight() || 260;
        const menuWidth = $menu.outerWidth() || 220;

        const btnRect = this.getBoundingClientRect();
        const windowHeight = window.innerHeight || $(window).height();
        const windowWidth = window.innerWidth || $(window).width();

        const spaceBelow = windowHeight - btnRect.bottom;
        const spaceAbove = btnRect.top;

        let top;
        let isDropup = false;
        // Nếu khoảng trống phía dưới màn hình không đủ cho chiều cao menu và phía trên rộng hơn -> mở ngược lên (Dropup)
        if (spaceBelow < (menuHeight + 12) && spaceAbove > spaceBelow) {
          top = btnRect.top - menuHeight - 6;
          isDropup = true;
        } else {
          top = btnRect.bottom + 6;
        }

        // Đảm bảo menu không vượt ra ngoài mép trên hoặc dưới của viewport
        if (top < 10) top = 10;
        if (top + menuHeight > windowHeight - 10) {
          top = Math.max(10, windowHeight - menuHeight - 10);
        }

        // Căn lề phải của menu khớp với lề phải của nút 3 chấm
        let left = btnRect.right - menuWidth;
        if (left < 10) left = 10;
        if (left + menuWidth > windowWidth - 10) {
          left = Math.max(10, windowWidth - menuWidth - 10);
        }

        if (isDropup) {
          $dropdown.addClass('dropup');
        }

        $menu.css({
          position: 'fixed',
          top: top + 'px',
          left: left + 'px',
          right: 'auto',
          bottom: 'auto',
          zIndex: 100001,
          visibility: 'visible',
          display: 'block'
        });
      });

      // Tự động đóng dropdown khi click ra ngoài hoặc click vào item
      $(document).on('click', function(e) {
        if (!$(e.target).closest('.story-actions-dropdown, .user-actions-dropdown, .genre-actions-dropdown').length) {
          closeStoryDropdowns();
        }
      });

      $(document).on('click', '.story-dropdown-item', function() {
        setTimeout(closeStoryDropdowns, 50);
      });

      // Đóng dropdown khi cuộn trang hoặc resize để tránh lệch vị trí
      $(window).on('scroll resize', function() {
        if ($('.story-actions-dropdown.is-open, .user-actions-dropdown.is-open, .genre-actions-dropdown.is-open').length) {
          closeStoryDropdowns();
        }
      });

      // Đóng dropdown khi cuộn bảng hoặc thanh cuộn nội bộ
      $(document).on('scroll', '.table-responsive, .db-table-container, .adm-content', function() {
        if ($('.story-actions-dropdown.is-open, .user-actions-dropdown.is-open, .genre-actions-dropdown.is-open').length) {
          closeStoryDropdowns();
        }
      });

      // =========================================================================
      // TOPBAR: USER DROPDOWN (TRANG CHỦ WEB & ĐĂNG XUẤT - HÌNH 3)
      // =========================================================================
      $(document).off('click.adminUserDropdown', '#btnAdminAccountDropdownTrigger')
        .on('click.adminUserDropdown', '#btnAdminAccountDropdownTrigger', function(e) {
          e.preventDefault();
          e.stopPropagation();
          $('#adminUserDropdownWrap').toggleClass('is-open');
        });

      $(document).off('click.adminUserDropdownClose')
        .on('click.adminUserDropdownClose', function(e) {
          if (!$(e.target).closest('#adminUserDropdownWrap').length) {
            $('#adminUserDropdownWrap').removeClass('is-open');
          }
        });

      $(document).off('keydown.adminUserDropdownClose')
        .on('keydown.adminUserDropdownClose', function(e) {
          if (e.key === 'Escape' || e.keyCode === 27) {
            $('#adminUserDropdownWrap').removeClass('is-open');
          }
        });

      // =========================================================================
      // STORY ACTIONS: THÊM CHƯƠNG NHANH & CHI TIẾT ĐẦY ĐỦ (HÌNH 1 & HÌNH 4)
      // =========================================================================

      // Mục 1: "Thêm chương" từ Menu 3 chấm của truyện -> Mở Popup Thêm Chương Nhanh
      $(document).on('click', '.btn-action-quick-add-chapter', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.story-actions-dropdown').removeClass('is-open');

        const storyId = $(this).data('story-id');
        const storyTitle = $(this).data('story-title') || 'Thêm Chương Mới';
        if (!storyId) return;

        const $modal = $('#modalQuickAddChapterToStory');
        if (!$modal.length) return;

        // Reset form & trạng thái
        $('#formQuickAddChapter')[0].reset();
        $('#quickAddStoryId').val(storyId);
        $('#quickAddIsDraft').val('0');
        $('#quickAddStoryTitleDisplay').text(storyTitle);
        $('#quickAddChapterAlert').hide().removeClass('alert-success alert-error');
        $('#quickAddWordCount').text('0');
        $('#quickAddCharCount').text('0');
        $('#btnQuickAddPublish').prop('disabled', false).html('<i class="fa-solid fa-cloud-arrow-up"></i> Xuất Bản (Đăng Ngay)');
        $('#btnQuickAddDraft').prop('disabled', false).html('<i class="fa-solid fa-file-pen"></i> Lưu Nháp');

        $modal.css('display', 'flex');

        // Tự động lấy số chương tiếp theo
        $.ajax({
          url: getAjaxUrl(),
          type: 'POST',
          data: {
            action: 'muop_get_next_chapter_number',
            story_id: storyId,
            nonce: (typeof muopConfig !== 'undefined' && muopConfig.nonce) ? muopConfig.nonce : ''
          },
          success: (res) => {
            if (typeof res === 'string') { try { res = JSON.parse(res); } catch (e) {} }
            const nextChap = (res && res.data && (res.data.next_chapter_number || res.data.next_chapter)) || 1;
            $('#quickAddChapterNumber').val(nextChap);
          },
          error: () => {
            $('#quickAddChapterNumber').val(1);
          }
        });
      });

      // Đóng Popup Thêm Chương Nhanh
      $('.btn-close-quick-add-chap').on('click', function(e) {
        e.preventDefault();
        $('#modalQuickAddChapterToStory').hide();
      });

      $('#modalQuickAddChapterToStory').on('click', function(e) {
        if (e.target === this) {
          $(this).hide();
        }
      });

      // Realtime word counter cho popup thêm chương
      $(document).on('input propertychange', '#quickAddChapterContent', function() {
        if (typeof updateRealtimeWordCounter === 'function') {
          updateRealtimeWordCounter($(this), $('#quickAddWordCount'), $('#quickAddCharCount'));
        }
      });

      // Nút Lưu Nháp của Popup Thêm Chương
      $('#btnQuickAddDraft').on('click', function(e) {
        e.preventDefault();
        $('#quickAddIsDraft').val('1');
        $('#formQuickAddChapter').submit();
      });

      // Submit Form Thêm Chương Nhanh
      $('#formQuickAddChapter').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $btnPublish = $('#btnQuickAddPublish');
        const $btnDraft = $('#btnQuickAddDraft');
        const isDraft = $('#quickAddIsDraft').val() === '1';
        const $activeBtn = isDraft ? $btnDraft : $btnPublish;
        const origBtnText = $activeBtn.html();

        $activeBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...');
        if (!isDraft) $btnDraft.prop('disabled', true);
        else $btnPublish.prop('disabled', true);

        $('#quickAddChapterAlert').hide();

        $.ajax({
          url: getAjaxUrl(),
          type: 'POST',
          data: $form.serialize(),
          success: (res) => {
            $activeBtn.prop('disabled', false).html(origBtnText);
            $btnDraft.prop('disabled', false);
            $btnPublish.prop('disabled', false);

            if (typeof res === 'string') { try { res = JSON.parse(res); } catch (e) {} }

            if (res && res.success) {
              const successMsg = getAjaxMsg(res, isDraft ? 'Đã lưu bản nháp chương thành công!' : 'Đã xuất bản chương mới thành công!');
              $('#quickAddChapterAlert').removeClass('alert-error').addClass('alert-success').text(successMsg).fadeIn();

              setTimeout(() => {
                $('#modalQuickAddChapterToStory').hide();
                // Cập nhật số chương trên hàng truyện nếu có
                const storyId = $('#quickAddStoryId').val();
                const $row = $(`#storyRow-${storyId}`);
                if ($row.length) {
                  const $chapCount = $row.find('.col-story-chaps strong');
                  if ($chapCount.length) {
                    $chapCount.text(parseInt($chapCount.text() || '0') + 1);
                  }
                }
                muopAlert(successMsg, 'success');
              }, 600);
            } else {
              const errMsg = getAjaxMsg(res, 'Không thể thêm chương. Vui lòng thử lại!');
              $('#quickAddChapterAlert').removeClass('alert-success').addClass('alert-error').text(errMsg).fadeIn();
            }
          },
          error: (xhr) => {
            $activeBtn.prop('disabled', false).html(origBtnText);
            $btnDraft.prop('disabled', false);
            $btnPublish.prop('disabled', false);
            let errMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errMsg = getAjaxMsg(xhr.responseJSON, errMsg);
            $('#quickAddChapterAlert').removeClass('alert-success').addClass('alert-error').text(errMsg).fadeIn();
          }
        });
      });

      // Mục 7: "Chi tiết" từ Menu 3 chấm của truyện -> Mở Popup Chi Tiết Đầy Đủ (Hình 4)
      $(document).on('click', '.btn-action-story-full-preview', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.story-actions-dropdown').removeClass('is-open');

        const storyId = $(this).data('story-id');
        if (!storyId) return;

        const $modal = $('#modalStoryFullPreviewDetails');
        if (!$modal.length) return;

        $('#prevStoryLoading').show();
        $('#prevStoryBody').hide();
        $modal.css('display', 'flex');

        $.ajax({
          url: getAjaxUrl(),
          type: 'POST',
          data: {
            action: 'muop_admin_action',
            sub_action: 'get_story_details',
            story_id: storyId,
            nonce: (typeof muopConfig !== 'undefined' && muopConfig.nonce) ? muopConfig.nonce : ''
          },
          success: (res) => {
            if (typeof res === 'string') { try { res = JSON.parse(res); } catch (e) {} }

            if (res && res.success && res.data) {
              const d = res.data;
              $('#prevStoryCover').attr('src', d.thumbnail || '/wp-content/themes/muop-dang/assets/images/default-cover.jpg');
              $('#prevStoryTitle').text(d.title || 'Không có tiêu đề');
              $('#prevStoryTeam').text(d.team_name || 'Cá nhân');
              $('#prevStoryGenres').text(d.genres || 'Đang cập nhật');
              
              if (d.post_status === 'publish') {
                $('#prevStoryStatusBadge').attr('class', 'badge badge-full').html('<i class="fa-solid fa-circle-check"></i> Đã duyệt');
              } else {
                $('#prevStoryStatusBadge').attr('class', 'badge badge-hot').html('<i class="fa-solid fa-clock"></i> Bản nháp');
              }

              $('#prevStoryChapsCount').text(d.total_chapters || 0);
              $('#prevStoryViews').text((d.views_all || 0).toLocaleString('vi-VN'));
              $('#prevStoryUpdated').text(d.chapters && d.chapters.length > 0 ? d.chapters[d.chapters.length - 1].date : '--');

              // Nút đọc từ đầu / mới nhất / xem website
              if (d.chapters && d.chapters.length > 0) {
                $('#prevBtnReadFirst').attr('href', d.chapters[0].permalink).show();
                $('#prevBtnReadLatest').attr('href', d.chapters[d.chapters.length - 1].permalink).show();
              } else {
                $('#prevBtnReadFirst').hide();
                $('#prevBtnReadLatest').hide();
              }
              if (d.permalink) {
                $('#prevBtnViewWeb').attr('href', d.permalink).show();
              } else {
                $('#prevBtnViewWeb').hide();
              }

              // Giới thiệu truyện phân đoạn (Hình 4)
              $('#prevStoryDescription').html(d.description || '<p style="color:var(--text-muted);margin:0;">Chưa có phần giới thiệu truyện.</p>');

              // Danh sách chương
              $('#prevStoryChapsTotal').text(d.total_chapters || 0);
              if (d.chapters && d.chapters.length > 0) {
                let html = '';
                d.chapters.forEach(ch => {
                  const draftBadge = ch.status === 'draft' ? '<span class="badge" style="background:#fef3c7;color:#b45309;font-size:10.5px;padding:2px 6px;margin-left:auto;">Nháp</span>' : '';
                  html += `
                    <a href="${ch.permalink}" target="_blank" style="display:flex;align-items:center;gap:8px;padding:9px 12px;background:var(--bg-body);border:1px solid var(--border-color);border-radius:var(--radius-sm);text-decoration:none;color:var(--text-main);font-size:13px;transition:all 0.15s ease;" onmouseover="this.style.borderColor='var(--primary-green)';this.style.color='var(--primary-green)';" onmouseout="this.style.borderColor='var(--border-color)';this.style.color='var(--text-main)';">
                      <span style="font-weight:700;color:var(--primary-green);font-size:12px;white-space:nowrap;">Chương ${ch.number}</span>
                      <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-weight:500;">${ch.title}</span>
                      ${draftBadge}
                    </a>
                  `;
                });
                $('#prevStoryChaptersList').html(html);
              } else {
                $('#prevStoryChaptersList').html('<div style="grid-column:1/-1;text-align:center;padding:20px;color:var(--text-muted);font-style:italic;">Truyện chưa có chương nào được đăng.</div>');
              }

              $('#prevStoryLoading').hide();
              $('#prevStoryBody').fadeIn();
            } else {
              muopAlert(getAjaxMsg(res, 'Không thể tải chi tiết truyện!'), 'error');
              $modal.hide();
            }
          },
          error: () => {
            muopAlert('Lỗi kết nối khi tải chi tiết truyện!', 'error');
            $modal.hide();
          }
        });
      });

      // Đóng Modal Chi Tiết Truyện Đầy Đủ
      $('.btn-close-story-preview').on('click', function(e) {
        e.preventDefault();
        $('#modalStoryFullPreviewDetails').hide();
      });

      $('#modalStoryFullPreviewDetails').on('click', function(e) {
        if (e.target === this) {
          $(this).hide();
        }
      });

      // =========================================================================
      // USER MANAGEMENT 3-DOTS ACTIONS & MODALS (HÌNH 5)
      // =========================================================================

      // 1. Sửa người dùng -> Mở Popup #modalEditUserAdmin
      $(document).on('click', '.btn-action-edit-user', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.user-actions-dropdown').removeClass('is-open');

        const userId = $(this).data('user-id');
        const userLogin = $(this).data('user-login') || '';
        const displayName = $(this).data('display-name') || '';
        const email = $(this).data('email') || '';
        const role = $(this).data('role') || 'doc_gia';

        $('#editUserId').val(userId);
        $('#editUserLogin').val(userLogin);
        $('#editUserDisplayName').val(displayName);
        $('#editUserEmail').val(email);
        $('#editUserRole').val(role);
        $('#editUserPassword').val('');
        $('#editUserAlert').hide();

        $('#modalEditUserAdmin').css('display', 'flex');
      });

      $('.btn-close-edit-user').on('click', function(e) {
        e.preventDefault();
        $('#modalEditUserAdmin').hide();
      });

      $('#modalEditUserAdmin').on('click', function(e) {
        if (e.target === this) {
          $(this).hide();
        }
      });

      // Submit Form Sửa Người Dùng
      $('#formAdminEditUser').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $btn = $('#btnSubmitEditUser');
        const origText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...');
        $('#editUserAlert').hide();

        $.ajax({
          url: getAjaxUrl(),
          type: 'POST',
          data: $form.serialize(),
          success: (res) => {
            $btn.prop('disabled', false).html(origText);
            if (typeof res === 'string') { try { res = JSON.parse(res); } catch (e) {} }

            if (res && res.success) {
              $('#modalEditUserAdmin').hide();
              const uid = $('#editUserId').val();
              const newName = $('#editUserDisplayName').val();
              const newEmail = $('#editUserEmail').val();
              const newRole = $('#editUserRole').val();

              // Cập nhật lại giao diện hàng của user trong bảng
              const $row = $(`#adminUserRow_${uid}`);
              if ($row.length) {
                $row.find('.col-user-displayname').text(newName);
                $row.find('.col-user-email').text(newEmail);
                $row.find('.col-user-role-select').val(newRole);

                const newPassInput = $('#editUserPassword').val().trim();
                if (newPassInput) {
                  const $passCell = $('#userPassCell_' + userId);
                  if ($passCell.length) {
                    const safePass = $('<div>').text(newPassInput).html();
                    $passCell.html(`
                      <div class="user-pass-inline-wrap">
                        <input type="password" readonly value="${safePass}" class="input-user-pass-inline" />
                        <button type="button" class="btn-toggle-inline-pass" title="Hiện / Ẩn mật khẩu">
                          <i class="fa-regular fa-eye"></i>
                        </button>
                        <button type="button" class="btn-copy-inline-pass" data-pass="${safePass}" title="Sao chép mật khẩu">
                          <i class="fa-regular fa-copy"></i>
                        </button>
                      </div>
                    `);
                  }
                }

                const $btnEdit = $row.find('.btn-action-edit-user');
                $btnEdit.data('display-name', newName);
                $btnEdit.data('email', newEmail);
                $btnEdit.data('role', newRole);
              }

              muopAlert(getAjaxMsg(res, 'Cập nhật thông tin người dùng thành công!'), 'success');
            } else {
              const errMsg = getAjaxMsg(res, 'Không thể cập nhật người dùng!');
              $('#editUserAlert').text(errMsg).show();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html(origText);
            let errMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errMsg = getAjaxMsg(xhr.responseJSON, errMsg);
            $('#editUserAlert').text(errMsg).show();
          }
        });
      });

      // Password generator helper
      function generateSecurePassword() {
        const letters = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
        const numbers = '23456789';
        let pass = 'Muop@';
        for (let i = 0; i < 4; i++) {
          pass += numbers.charAt(Math.floor(Math.random() * numbers.length));
        }
        for (let i = 0; i < 2; i++) {
          pass += letters.charAt(Math.floor(Math.random() * letters.length));
        }
        return pass;
      }

      // =========================================================================
      // 1.1 SỬA & XEM MẬT KHẨU NGƯỜI DÙNG (#modalUserPasswordAdmin)
      // =========================================================================
      $(document).on('click', '.btn-action-manage-user-pass', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.user-actions-dropdown').removeClass('is-open');

        const userId = $(this).data('user-id') || $(this).attr('data-user-id');
        const userLogin = $(this).data('user-login') || $(this).attr('data-user-login') || '';
        const displayName = $(this).data('display-name') || $(this).attr('data-display-name') || 'Người dùng';
        const email = $(this).data('email') || $(this).attr('data-email') || '';

        if (!userId) return;

        $('#changePassUserId').val(userId);
        $('#passUserDisplayName').text(displayName);
        $('#passUserLogin').text(userLogin || '--');
        $('#passUserEmail').text(email || '--');
        $('#passUserAvatarChar').text((displayName || 'U').charAt(0).toUpperCase());

        $('#changePassAlert').hide();
        $('#inputNewUserPass').val('').attr('type', 'text');
        $('#iconToggleNewPass').removeClass('fa-eye').addClass('fa-eye-slash');

        $('#boxExistingPasswordInfo').hide();
        $('#passModalLoading').show();
        $('#modalUserPasswordAdmin').css('display', 'flex');

        $.ajax({
          url: getAjaxUrl(),
          type: 'POST',
          data: {
            action: 'muop_admin_action',
            sub_action: 'get_user_password_info',
            user_id: userId,
            nonce: (typeof muopConfig !== 'undefined' && muopConfig.nonce) ? muopConfig.nonce : $('input[name="nonce"]').val()
          },
          success: (res) => {
            $('#passModalLoading').hide();
            $('#boxExistingPasswordInfo').show();
            if (typeof res === 'string') { try { res = JSON.parse(res); } catch (e) {} }

            if (res && res.success && res.data) {
              const d = res.data;
              if (d.has_temp && d.temp_password) {
                $('#inputExistingPassDisplay').val(d.temp_password).attr('type', 'password');
                $('#iconToggleExistingPass').removeClass('fa-eye-slash').addClass('fa-eye');
                $('#passLastResetTime').text(d.last_reset ? 'Cập nhật: ' + d.last_reset : '');
                $('#passExistingAvailable').show();
                $('#passExistingNotSet').hide();
              } else {
                $('#inputExistingPassDisplay').val('');
                $('#passExistingAvailable').hide();
                $('#passExistingNotSet').show();
                $('#passLastResetTime').text('');
              }
            } else {
              $('#passExistingAvailable').hide();
              $('#passExistingNotSet').show();
            }
          },
          error: () => {
            $('#passModalLoading').hide();
            $('#boxExistingPasswordInfo').show();
            $('#passExistingAvailable').hide();
            $('#passExistingNotSet').show();
          }
        });
      });

      $('.btn-close-user-pass').on('click', function(e) {
        e.preventDefault();
        $('#modalUserPasswordAdmin').hide();
      });

      $('#modalUserPasswordAdmin').on('click', function(e) {
        if (e.target === this) $(this).hide();
      });

      // Toggle Xem/Ẩn Mật khẩu Cũ đã cấp
      $('#btnToggleExistingPass').on('click', function(e) {
        e.preventDefault();
        const $inp = $('#inputExistingPassDisplay');
        const $icon = $('#iconToggleExistingPass');
        if ($inp.attr('type') === 'password') {
          $inp.attr('type', 'text');
          $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
          $inp.attr('type', 'password');
          $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
      });

      // Copy Mật khẩu Cũ đã cấp
      $('#btnCopyExistingPass').on('click', function(e) {
        e.preventDefault();
        const val = $('#inputExistingPassDisplay').val();
        if (val) {
          navigator.clipboard.writeText(val).then(() => {
            muopAlert('Đã sao chép mật khẩu: ' + val, 'success');
          }).catch(() => {
            muopAlert('Mật khẩu: ' + val, 'info');
          });
        }
      });

      // Tạo ngẫu nhiên Mật khẩu Mới
      $('#btnGenerateRandomPass').on('click', function(e) {
        e.preventDefault();
        const randPass = generateSecurePassword();
        $('#inputNewUserPass').val(randPass).attr('type', 'text');
        $('#iconToggleNewPass').removeClass('fa-eye').addClass('fa-eye-slash');
        muopAlert('Đã sinh mật khẩu ngẫu nhiên: ' + randPass, 'success');
      });

      // Toggle Xem/Ẩn Mật khẩu Mới
      $('#btnToggleNewPass').on('click', function(e) {
        e.preventDefault();
        const $inp = $('#inputNewUserPass');
        const $icon = $('#iconToggleNewPass');
        if ($inp.attr('type') === 'password') {
          $inp.attr('type', 'text');
          $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
          $inp.attr('type', 'password');
          $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
      });

      // Copy Mật khẩu Mới
      $('#btnCopyNewPass').on('click', function(e) {
        e.preventDefault();
        const val = $('#inputNewUserPass').val();
        if (val) {
          navigator.clipboard.writeText(val).then(() => {
            muopAlert('Đã sao chép mật khẩu: ' + val, 'success');
          }).catch(() => {
            muopAlert('Mật khẩu: ' + val, 'info');
          });
        } else {
          muopAlert('Chưa có mật khẩu để sao chép!', 'warning');
        }
      });

      // Submit Form Đổi Mật Khẩu
      $('#formAdminChangeUserPass').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $btn = $('#btnSubmitChangeUserPass');
        const origText = $btn.html();
        const newPass = $('#inputNewUserPass').val().trim();

        if (newPass.length < 6) {
          $('#changePassAlert').removeClass('alert-success').addClass('alert-error').text('Mật khẩu phải có ít nhất 6 ký tự!').show();
          return;
        }

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...');
        $('#changePassAlert').hide();

        $.ajax({
          url: getAjaxUrl(),
          type: 'POST',
          data: $form.serialize(),
          success: (res) => {
            $btn.prop('disabled', false).html(origText);
            if (typeof res === 'string') { try { res = JSON.parse(res); } catch (e) {} }

            if (res && res.success) {
              muopAlert(getAjaxMsg(res, 'Đã cập nhật mật khẩu mới thành công!'), 'success');
              $('#inputExistingPassDisplay').val(newPass).attr('type', 'text');
              $('#iconToggleExistingPass').removeClass('fa-eye').addClass('fa-eye-slash');
              $('#passLastResetTime').text('Vừa cập nhật');
              $('#passExistingAvailable').show();
              $('#passExistingNotSet').hide();
              $('#changePassAlert').removeClass('alert-error').addClass('alert-success').text('Đổi mật khẩu thành công! Mật khẩu mới là: ' + newPass).show();

              const targetUserId = $('#changePassUserId').val();
              const $cell = $('#userPassCell_' + targetUserId);
              if ($cell.length) {
                const safePass = $('<div>').text(newPass).html();
                $cell.html(`
                  <div class="user-pass-inline-wrap">
                    <input type="password" readonly value="${safePass}" class="input-user-pass-inline" />
                    <button type="button" class="btn-toggle-inline-pass" title="Hiện / Ẩn mật khẩu">
                      <i class="fa-regular fa-eye"></i>
                    </button>
                    <button type="button" class="btn-copy-inline-pass" data-pass="${safePass}" title="Sao chép mật khẩu">
                      <i class="fa-regular fa-copy"></i>
                    </button>
                  </div>
                `);
              }
            } else {
              const errMsg = getAjaxMsg(res, 'Lỗi không thể đổi mật khẩu!');
              $('#changePassAlert').removeClass('alert-success').addClass('alert-error').text(errMsg).show();
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html(origText);
            let errMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errMsg = getAjaxMsg(xhr.responseJSON, errMsg);
            $('#changePassAlert').removeClass('alert-success').addClass('alert-error').text(errMsg).show();
          }
        });
      });

      // Toggle Xem/Ẩn mật khẩu trực tiếp trên bảng User Admin
      $(document).on('click', '.btn-toggle-inline-pass', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const $wrap = $(this).closest('.user-pass-inline-wrap');
        const $inp = $wrap.find('.input-user-pass-inline');
        const $icon = $(this).find('i');
        if ($inp.attr('type') === 'password') {
          $inp.attr('type', 'text');
          $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
          $inp.attr('type', 'password');
          $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
      });

      // Copy mật khẩu trực tiếp trên bảng User Admin
      $(document).on('click', '.btn-copy-inline-pass', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const val = $(this).data('pass') || $(this).closest('.user-pass-inline-wrap').find('.input-user-pass-inline').val();
        if (val) {
          navigator.clipboard.writeText(val).then(() => {
            if (typeof muopAlert === 'function') {
              muopAlert('Đã sao chép mật khẩu: ' + val, 'success');
            }
          }).catch(() => {
            if (typeof muopAlert === 'function') {
              muopAlert('Mật khẩu: ' + val, 'info');
            }
          });
        }
      });

      // Hỗ trợ Xem/Tạo ngẫu nhiên mật khẩu trong Popup Sửa thông thường (#modalEditUserAdmin)
      $('#btnToggleViewEditUserPass').on('click', function(e) {
        e.preventDefault();
        const $inp = $('#editUserPassword');
        const $icon = $('#iconToggleEditPass');
        if ($inp.attr('type') === 'password') {
          $inp.attr('type', 'text');
          $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
          $inp.attr('type', 'password');
          $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
      });

      $('#btnGenerateEditUserPass').on('click', function(e) {
        e.preventDefault();
        const randPass = generateSecurePassword();
        $('#editUserPassword').val(randPass).attr('type', 'text');
        $('#iconToggleEditPass').removeClass('fa-eye').addClass('fa-eye-slash');
        muopAlert('Đã sinh mật khẩu: ' + randPass, 'success');
      });

      $('#btnCopyEditUserPass').on('click', function(e) {
        e.preventDefault();
        const val = $('#editUserPassword').val();
        if (val) {
          navigator.clipboard.writeText(val).then(() => {
            muopAlert('Đã sao chép mật khẩu: ' + val, 'success');
          }).catch(() => {
            muopAlert('Mật khẩu: ' + val, 'info');
          });
        } else {
          muopAlert('Chưa có mật khẩu để sao chép!', 'warning');
        }
      });

      // 2. Xóa người dùng -> Mở Popup Xác Nhận #modalConfirmDeleteUser
      $(document).on('click', '.btn-action-confirm-delete-user', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.user-actions-dropdown').removeClass('is-open');

        const userId = $(this).data('user-id');
        const userName = $(this).data('name') || 'Người dùng';

        $('#deleteUserTargetName').text(userName);
        $('#modalConfirmDeleteUser').data('user-id', userId).css('display', 'flex');
      });

      $('.btn-cancel-delete-user').on('click', function(e) {
        e.preventDefault();
        $('#modalConfirmDeleteUser').hide();
      });

      $('#modalConfirmDeleteUser').on('click', function(e) {
        if (e.target === this) {
          $(this).hide();
        }
      });

      // Thực hiện Xóa người dùng khi ấn Xác Nhận trong Modal
      $('#btnConfirmDeleteUserAction').on('click', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const $modal = $('#modalConfirmDeleteUser');
        const userId = $modal.data('user-id');
        if (!userId) return;

        const origText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang xóa...');

        $.ajax({
          url: getAjaxUrl(),
          type: 'POST',
          data: {
            action: 'muop_admin_action',
            sub_action: 'delete_user',
            user_id: userId,
            nonce: (typeof muopConfig !== 'undefined' && muopConfig.nonce) ? muopConfig.nonce : ''
          },
          success: (res) => {
            $btn.prop('disabled', false).html(origText);
            if (typeof res === 'string') { try { res = JSON.parse(res); } catch (e) {} }

            if (res && res.success) {
              $modal.hide();
              $(`#adminUserRow_${userId}`).fadeOut(300, function() {
                $(this).remove();
              });
              muopAlert(getAjaxMsg(res, 'Đã xóa người dùng thành công!'), 'success');
            } else {
              muopAlert(getAjaxMsg(res, 'Không thể xóa người dùng!'), 'error');
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html(origText);
            let errMsg = 'Lỗi kết nối máy chủ khi xóa người dùng!';
            if (xhr && xhr.responseJSON) errMsg = getAjaxMsg(xhr.responseJSON, errMsg);
            muopAlert(errMsg, 'error');
          }
        });
      });

      // 3. Khóa / Mở Khóa Tài Khoản -> Mở Popup Xác Nhận #modalConfirmToggleLockUser
      $(document).on('click', '.btn-action-confirm-toggle-lock', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.user-actions-dropdown').removeClass('is-open');

        const userId = $(this).data('user-id');
        const userName = $(this).data('name') || 'Người dùng';
        const isLocked = $(this).data('locked') == '1';

        const $modal = $('#modalConfirmToggleLockUser');
        $modal.data('user-id', userId).data('locked', isLocked ? 1 : 0);
        $('#lockTargetUserName').text(userName);

        if (isLocked) {
          // Giao diện mở khóa
          $('#iconLockModal').attr('class', 'fa-solid fa-unlock');
          $('#lockIconBox').css({ background: '#dcfce7', color: '#16a34a' });
          $('#titleLockModal').css('color', '#15803d').text('Xác Nhận Mở Khóa Tài Khoản?');
          $('#descLockModal').html(`Bạn có chắc chắn muốn mở khóa cho tài khoản <strong style="color: #16a34a;">${userName}</strong> không? Người dùng sẽ có thể đăng nhập bình thường.`);
          $('#btnConfirmToggleLockAction').css({ background: 'var(--primary-green)', borderColor: 'var(--primary-green)' }).html('<i class="fa-solid fa-unlock"></i> Mở Khóa Tài Khoản');
        } else {
          // Giao diện khóa
          $('#iconLockModal').attr('class', 'fa-solid fa-lock');
          $('#lockIconBox').css({ background: '#ffedd5', color: '#ea580c' });
          $('#titleLockModal').css('color', '#9a3412').text('Xác Nhận Khóa Tài Khoản?');
          $('#descLockModal').html(`Bạn có chắc chắn muốn khóa tài khoản <strong style="color: #ea580c;">${userName}</strong> không? Người dùng sẽ không thể đăng nhập sau khi bị khóa.`);
          $('#btnConfirmToggleLockAction').css({ background: '#ea580c', borderColor: '#ea580c' }).html('<i class="fa-solid fa-lock"></i> Khóa Tài Khoản');
        }

        $modal.css('display', 'flex');
      });

      $('.btn-cancel-toggle-lock').on('click', function(e) {
        e.preventDefault();
        $('#modalConfirmToggleLockUser').hide();
      });

      $('#modalConfirmToggleLockUser').on('click', function(e) {
        if (e.target === this) {
          $(this).hide();
        }
      });

      // Thực hiện Khóa / Mở khóa khi ấn Xác Nhận trong Modal
      $('#btnConfirmToggleLockAction').on('click', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const $modal = $('#modalConfirmToggleLockUser');
        const userId = $modal.data('user-id');
        const wasLocked = $modal.data('locked') == 1;
        if (!userId) return;

        const origText = $btn.html();
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang xử lý...');

        $.ajax({
          url: getAjaxUrl(),
          type: 'POST',
          data: {
            action: 'muop_admin_action',
            sub_action: 'toggle_user_status',
            user_id: userId,
            nonce: (typeof muopConfig !== 'undefined' && muopConfig.nonce) ? muopConfig.nonce : ''
          },
          success: (res) => {
            $btn.prop('disabled', false).html(origText);
            if (typeof res === 'string') { try { res = JSON.parse(res); } catch (e) {} }

            if (res && res.success) {
              $modal.hide();
              const newLockedState = !wasLocked;
              const $row = $(`#adminUserRow_${userId}`);
              if ($row.length) {
                const $statusCell = $row.find('.col-user-status-cell');
                const $lockBtn = $row.find('.btn-action-confirm-toggle-lock');

                if (newLockedState) {
                  $statusCell.html('<span class="user-status-badge status-badge-locked"><i class="fa-solid fa-lock"></i> Đã khóa</span>');
                  $lockBtn.data('locked', '1');
                  $lockBtn.find('i').attr('class', 'fa-solid fa-unlock');
                  $lockBtn.find('.user-lock-toggle-label').text('Mở khóa tài khoản');
                } else {
                  $statusCell.html('<span class="user-status-badge status-badge-active"><i class="fa-solid fa-circle-check"></i> Hoạt động</span>');
                  $lockBtn.data('locked', '0');
                  $lockBtn.find('i').attr('class', 'fa-solid fa-lock');
                  $lockBtn.find('.user-lock-toggle-label').text('Khóa tài khoản');
                }
              }
              muopAlert(getAjaxMsg(res, newLockedState ? 'Đã khóa tài khoản thành công!' : 'Đã mở khóa tài khoản thành công!'), 'success');
            } else {
              muopAlert(getAjaxMsg(res, 'Thao tác không thành công!'), 'error');
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html(origText);
            let errMsg = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) errMsg = getAjaxMsg(xhr.responseJSON, errMsg);
            muopAlert(errMsg, 'error');
          }
        });
      });

      // 4. Chi tiết người dùng -> Mở Popup Hồ Sơ Chi Tiết #modalUserFullDetailPopup
      $(document).on('click', '.btn-action-user-full-details', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.user-actions-dropdown').removeClass('is-open');

        const userId = $(this).data('user-id');
        if (!userId) return;

        const $modal = $('#modalUserFullDetailPopup');
        if (!$modal.length) return;

        $('#popupUserLoading').show();
        $('#popupUserContentBox').hide();
        $modal.css('display', 'flex');

        $.ajax({
          url: getAjaxUrl(),
          type: 'POST',
          data: {
            action: 'muop_admin_action',
            sub_action: 'get_user_details',
            user_id: userId,
            nonce: (typeof muopConfig !== 'undefined' && muopConfig.nonce) ? muopConfig.nonce : ''
          },
          success: (res) => {
            if (typeof res === 'string') { try { res = JSON.parse(res); } catch (e) {} }

            if (res && res.success && res.data) {
              const u = res.data;
              $('#popupUserDisplayName').text(u.display_name || '--');
              $('#popupUserLogin').text(u.user_login || '--');
              $('#popupUserIdText').text('#' + (u.id || userId));
              $('#popupUserEmail').text(u.user_email || '--');

              // Avatar
              if (u.avatar_url) {
                $('#popupUserAvatarWrap').html(`<img src="${u.avatar_url}" style="width:100%;height:100%;object-fit:cover;" alt="Avatar" />`);
              } else {
                const initial = u.avatar_char || (u.display_name || 'U').charAt(0).toUpperCase();
                $('#popupUserAvatarWrap').html(`<span>${initial}</span>`);
              }

              // Badges
              const roleLabels = { 'administrator': 'Quản trị viên', 'dich_gia': 'Dịch giả', 'doc_gia': 'Độc giả' };
              $('#popupUserRoleBadge').text(roleLabels[u.role] || u.role);

              if (u.is_locked) {
                $('#popupUserStatusBadge').attr('class', 'badge badge-hot').html('<i class="fa-solid fa-lock"></i> Đã khóa');
              } else {
                $('#popupUserStatusBadge').attr('class', 'badge badge-full').html('<i class="fa-solid fa-unlock"></i> Hoạt động');
              }

              // Thống kê 3 hộp
              $('#popupUserStoriesCount').text(u.total_stories || 0);
              $('#popupUserViewsCount').text((u.total_views_all || 0).toLocaleString('vi-VN'));
              $('#popupUserSalaryCount').text((u.total_salary || 0).toLocaleString('vi-VN') + ' VNĐ');

              // Ngân hàng
              $('#popupUserBankName').text(u.bank_name || 'Chưa cập nhật');
              $('#popupUserBankHolder').text(u.bank_holder || 'Chưa cập nhật');
              $('#popupUserBankNumber').text(u.bank_number || 'Chưa cập nhật');

              // Danh sách truyện
              $('#popupUserStoriesTotal').text(u.total_stories || 0);
              if (u.stories && u.stories.length > 0) {
                let stHtml = '<table class="data-table" style="width:100%;font-size:13px;"><thead><tr><th>Tên Truyện</th><th style="width:75px;text-align:center;">Số Chương</th><th style="width:90px;text-align:center;">Lượt Xem</th><th style="width:85px;text-align:center;">Trạng Thái</th></tr></thead><tbody>';
                u.stories.forEach(s => {
                  stHtml += `
                    <tr>
                      <td><a href="${s.permalink || '#'}" target="_blank" style="font-weight:600;color:var(--text-main);">${s.title}</a></td>
                      <td style="text-align:center;"><strong>${s.chapters || 0}</strong></td>
                      <td style="text-align:center;color:#0284c7;"><strong>${(s.views_total || 0).toLocaleString('vi-VN')}</strong></td>
                      <td style="text-align:center;"><span class="badge ${s.status === 'publish' ? 'badge-full' : 'badge-hot'}" style="font-size:11px;">${s.status === 'publish' ? 'Đã duyệt' : 'Bản nháp'}</span></td>
                    </tr>
                  `;
                });
                stHtml += '</tbody></table>';
                $('#popupUserStoriesList').html(stHtml);
              } else {
                $('#popupUserStoriesList').html('<div style="text-align:center;padding:16px;color:var(--text-muted);font-style:italic;">Người dùng chưa đăng bộ truyện nào.</div>');
              }

              $('#popupUserLoading').hide();
              $('#popupUserContentBox').fadeIn();
            } else {
              muopAlert(getAjaxMsg(res, 'Không thể tải hồ sơ người dùng!'), 'error');
              $modal.hide();
            }
          },
          error: () => {
            muopAlert('Lỗi kết nối khi tải hồ sơ người dùng!', 'error');
            $modal.hide();
          }
        });
      });

      // Đóng Popup Hồ Sơ Người Dùng
      $('.btn-close-user-detail-popup').on('click', function(e) {
        e.preventDefault();
        $('#modalUserFullDetailPopup').hide();
      });

      $('#modalUserFullDetailPopup').on('click', function(e) {
        if (e.target === this) {
          $(this).hide();
        }
      });

      // Mục 3: "Chỉnh sửa chương" từ Menu 3 chấm của truyện
      $(document).on('click', '.btn-action-edit-story-chaps', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.story-actions-dropdown').removeClass('is-open');

        const storyId = $(this).data('story-id');
        const storyTitle = $(this).data('story-title') || 'Chỉnh Sửa Chương';
        if (!storyId) return;

        const $modal = $('#modalEditChapterContent');
        if (!$modal.length) return;

        // Reset trạng thái modal
        $('#editChapterAlert').hide();
        $('#editNoChaptersMsg').hide();
        $('#formEditChapterFields').hide();
        $('#modalChapterStoryTitleText').text(storyTitle);
        $('#wrapperSelectEditChapter').show();
        $('#selectEditChapter').html('<option value="">-- Đang nạp danh sách chương... --</option>');

        $modal.css('display', 'flex');

        // Gọi AJAX lấy danh sách chương của bộ truyện
        $.ajax({
          url: getAjaxUrl(),
          type: 'POST',
          data: {
            action: 'muop_get_story_data',
            story_id: storyId,
            nonce: (typeof muopConfig !== 'undefined' && muopConfig.nonce) ? muopConfig.nonce : ''
          },
          success: (res) => {
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success && res.data) {
              const data = res.data;
              if (data.title) {
                $('#modalChapterStoryTitleText').text(data.title);
              }
              if (data.chapters && data.chapters.length > 0) {
                let optsHtml = '<option value="">-- Chọn chương cần chỉnh sửa --</option>';
                data.chapters.forEach(ch => {
                  const statusLabel = ch.status === 'draft' ? ' (Bản nháp)' : '';
                  optsHtml += `<option value="${ch.id}">Chương ${ch.number}: ${ch.title}${statusLabel}</option>`;
                });
                $('#selectEditChapter').html(optsHtml);
                // Mặc định chọn ngay chương đầu tiên và tải nội dung luôn
                $('#selectEditChapter').val(data.chapters[0].id).trigger('change');
              } else {
                $('#wrapperSelectEditChapter').hide();
                $('#formEditChapterFields').hide();
                $('#editNoChaptersMsg').fadeIn();
                const fromParam = (window.location.pathname.indexOf('quan-ly-admin') !== -1 || window.location.search.indexOf('from=admin') !== -1) ? 'admin&from_admin=1' : 'translator';
                const addUrl = '/them-chuong/?story_id=' + storyId + '&from=' + fromParam;
                $('#btnGoAddChapterFromModal').attr('href', addUrl);
              }
            } else {
              muopAlert(getAjaxMsg(res, 'Không thể tải danh sách chương!'), 'error');
              $modal.hide();
            }
          },
          error: (xhr) => {
            let msg = 'Lỗi kết nối khi tải danh sách chương!';
            if (xhr && xhr.responseJSON) msg = getAjaxMsg(xhr.responseJSON, msg);
            muopAlert(msg, 'error');
            $modal.hide();
          }
        });
      });

      // Mục 6: "Xóa chương truyện" từ Menu 3 chấm của truyện -> Mở Popup Quản Lý Xóa Chương
      $(document).on('click', '.btn-action-open-delete-chaps', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.story-actions-dropdown').removeClass('is-open');

        const storyId = $(this).data('story-id');
        const storyTitle = $(this).data('story-title') || 'Xóa Chương Truyện';
        if (!storyId) return;

        const $modal = $('#modalManageDeleteChapters');
        if (!$modal.length) return;

        $('#modalDeleteChaptersStoryTitle').text(storyTitle);
        $('#deleteChaptersLoading').show();
        $('#deleteChaptersEmpty').hide();
        $('#deleteChaptersList').empty();

        $modal.css('display', 'flex');

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_get_story_data',
            story_id: storyId,
            nonce: muopConfig.nonce
          },
          success: (res) => {
            $('#deleteChaptersLoading').hide();
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success && res.data) {
              const data = res.data;
              if (data.title) {
                $('#modalDeleteChaptersStoryTitle').text(data.title);
              }
              if (data.chapters && data.chapters.length > 0) {
                let html = '';
                data.chapters.forEach(ch => {
                  const draftBadge = ch.status === 'draft' ? '<span class="badge" style="background:#fef3c7;color:#b45309;font-size:11px;padding:2px 6px;font-weight:700;">Bản nháp</span>' : '';
                  html += `
                    <div class="delete-chap-row-item" id="delChapRow-${ch.id}">
                      <div class="delete-chap-info">
                        <span class="delete-chap-num-badge">Chương ${ch.number}</span>
                        <span class="delete-chap-title-text" title="${ch.title}">${ch.title}</span>
                        ${draftBadge}
                      </div>
                      <button type="button" class="btn btn-danger btn-sm btn-action-delete-single-chap" data-chapter-id="${ch.id}" data-chapter-num="${ch.number}" data-chapter-title="${ch.title}" data-story-id="${storyId}" style="padding: 4px 12px; font-size: 12px; border-radius: 4px; flex-shrink: 0;">
                        <i class="fa-solid fa-trash-can"></i> Xóa
                      </button>
                    </div>
                  `;
                });
                $('#deleteChaptersList').html(html);
              } else {
                $('#deleteChaptersEmpty').show();
              }
            } else {
              $('#deleteChaptersEmpty').show();
            }
          },
          error: (xhr) => {
            $('#deleteChaptersLoading').hide();
            let msg = 'Lỗi kết nối khi tải danh sách chương!';
            if (xhr && xhr.responseJSON) msg = getAjaxMsg(xhr.responseJSON, msg);
            muopAlert(msg, 'error');
            $modal.hide();
          }
        });
      });

      // Đóng Modal Quản Lý Xóa Chương
      $('#btnCloseManageDeleteChaptersModal, .btn-close-modal-delete-chapters').on('click', function(e) {
        e.preventDefault();
        $('#modalManageDeleteChapters').hide();
      });

      $('#modalManageDeleteChapters').on('click', function(e) {
        if (e.target === this) {
          $(this).hide();
        }
      });

      // Xác nhận và thực hiện xóa từng chương trong popup
      $(document).on('click', '.btn-action-delete-single-chap', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const chapterId = $btn.data('chapter-id');
        const chapterNum = $btn.data('chapter-num');
        const chapterTitle = $btn.data('chapter-title') || '';
        const storyId = $btn.data('story-id');

        if (!chapterId) return;

        const labelText = chapterTitle ? `Chương ${chapterNum}: "${chapterTitle}"` : `Chương ${chapterNum}`;

        muopConfirm(
          `Bạn có chắc chắn muốn xóa vĩnh viễn ${labelText} không? Hành động này không thể hoàn tác!`,
          () => {
            const originalHtml = $btn.html();
            $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');

            $.ajax({
              url: muopConfig.ajaxUrl,
              type: 'POST',
              data: {
                action: 'muop_delete_chapter',
                chapter_id: chapterId,
                nonce: muopConfig.nonce,
                is_admin_portal: 1
              },
              success: (res) => {
                if (typeof res === 'string') {
                  try { res = JSON.parse(res); } catch (e) {}
                }
                if (res && res.success) {
                  $(`#delChapRow-${chapterId}`).slideUp(200, function() {
                    $(this).remove();
                    if ($('#deleteChaptersList .delete-chap-row-item').length === 0) {
                      $('#deleteChaptersEmpty').fadeIn();
                    }
                  });
                  muopAlert(getAjaxMsg(res, `Đã xóa ${labelText} thành công!`), 'success');
                } else {
                  $btn.prop('disabled', false).html(originalHtml);
                  muopAlert(getAjaxMsg(res, 'Không thể xóa chương!'), 'error');
                }
              },
              error: (xhr) => {
                $btn.prop('disabled', false).html(originalHtml);
                let msg = 'Lỗi kết nối khi xóa chương!';
                if (xhr && xhr.responseJSON) msg = getAjaxMsg(xhr.responseJSON, msg);
                muopAlert(msg, 'error');
              }
            });
          },
          null,
          {
            isDanger: true,
            confirmText: 'Có',
            cancelText: 'Không',
            title: 'Xác Nhận Xóa Chương'
          }
        );
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
        const fileInput = document.getElementById('inputDirectCoverFile');
        if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
          formData.delete('cover_file');
        }

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
            let parsed = null;
            if (xhr && xhr.responseText) {
              const jsonMatch = xhr.responseText.match(/\{[\s\S]*\}/);
              if (jsonMatch) {
                try { parsed = JSON.parse(jsonMatch[0]); } catch (e) {}
              }
            }
            if (parsed && parsed.success) {
              const newUrl = parsed.data && parsed.data.cover_url;
              if (newUrl) {
                $('#mainStoryCoverImg').attr('src', newUrl);
                $('#editStoryCoverPreview').attr('src', newUrl).show();
              }
              if (parsed.data && parsed.data.updated && parsed.data.updated.display) {
                $('#storyUpdatedMeta').text(parsed.data.updated.display).attr('title', parsed.data.updated.formatted);
              }
              $alert.removeClass('alert-error').addClass('alert-success').text(getAjaxMsg(parsed, 'Thay đổi ảnh bìa thành công!')).fadeIn();
              setTimeout(() => {
                $('#modalChangeCoverDirect').hide();
              }, 1000);
              return;
            }
            let msg = 'Lỗi kết nối máy chủ!';
            if (parsed) msg = getAjaxMsg(parsed, msg);
            else if (xhr && xhr.responseJSON) msg = getAjaxMsg(xhr.responseJSON, msg);
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
      // Toggle dropdown thông báo khi click chuông
      $(document).on('click', '#notifToggleBtn', (e) => {
        e.preventDefault();
        e.stopPropagation();

        const $panel = $('#notifDropdownPanel');
        const isOpen = $panel.hasClass('show');

        // Đóng dropdown user và thể loại nếu đang mở
        $('#userDropdownPanel').removeClass('show');
        $('#userMenuWrapper').removeClass('active');
        $('.genre-dropdown-menu').removeClass('show');
        $('.has-genre-dropdown').removeClass('open');

        if (isOpen) {
          NotificationManager.closeDropdown();
        } else {
          NotificationManager.openDropdown();
        }
      });

      // Ngăn chặn sự kiện click bên trong panel thông báo làm đóng panel
      $(document).on('click', '#notifDropdownPanel', (e) => {
        e.stopPropagation();
      });

      // Đóng dropdown thông báo khi click ra ngoài
      $(document).on('click', (e) => {
        if (!$(e.target).closest('#notifWrapper').length) {
          NotificationManager.closeDropdown();
        }
      });

      // Đóng dropdown khi bấm Escape
      $(document).on('keydown', (e) => {
        if (e.key === 'Escape') {
          NotificationManager.closeDropdown();
        }
      });

      // Mark all read
      $(document).on('click', '#notifMarkAllReadBtn', (e) => {
        e.preventDefault();
        e.stopPropagation();
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
          NotificationManager.closeDropdown();
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
            NotificationManager.closeDropdown();
            NotificationManager.openBillModal(billUrl);
          }
          return;
        }

        // 3. Các thông báo thông thường khác
        if (link && !$(e.target).closest('.notif-bill-preview-btn').length) {
          NotificationManager.closeDropdown();
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

    openDropdown() {
      // Đóng dropdown user và thể loại nếu đang mở
      $('#userDropdownPanel').removeClass('show');
      $('#userMenuWrapper').removeClass('active');
      $('.genre-dropdown-menu').removeClass('show');
      $('.has-genre-dropdown').removeClass('open');

      const $panel = $('#notifDropdownPanel');
      $panel.addClass('show');
      $('#notifToggleBtn').attr('aria-expanded', 'true');
      this.fetchNotifications();
    },

    closeDropdown() {
      $('#notifDropdownPanel').removeClass('show');
      $('#notifToggleBtn').attr('aria-expanded', 'false');
    },

    openModal() {
      this.openDropdown();
    },

    closeModal() {
      this.closeDropdown();
    },

    openBillModal(billUrl) {
      $('#billModalImg').attr('src', billUrl);
      $('#btnDownloadBill').attr('href', billUrl);
      $('#billPreviewModal').css('display', 'flex');
    },

    fetchNotifications() {
      const $container = $('#notifListContainer');
      $container.html('<div class="notif-loading"><i class="fa-solid fa-spinner fa-spin" style="font-size:24px;margin-bottom:8px;color:var(--primary-green);"></i><div>Đang tải thông báo...</div></div>');

      let endpoint = (window.muopConfig && window.muopConfig.ajaxUrl) ? window.muopConfig.ajaxUrl : '/core/wp-admin/admin-ajax.php';
      if (window.muopConfig && window.muopConfig.ajaxUrl) {
        endpoint = window.muopConfig.ajaxUrl;
        try {
          const parsed = new URL(endpoint, window.location.origin);
          if (parsed.host !== window.location.host) {
            endpoint = parsed.pathname;
          }
        } catch (e) {
          endpoint = window.muopConfig.ajaxUrl;
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
              $('#admModalPaySalary').hide();
              muopAlert(res.data.message || 'Thanh toán và gửi bill thành công!', 'success', () => {
                window.location.reload();
              });
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
              $('#admModalReplyReport').hide();
              muopAlert(res.data.message || 'Phản hồi báo lỗi thành công!', 'success', () => {
                window.location.reload();
              });
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

  /* ==========================================================================
     20. SENSITIVE WORD REVIEW & AUTO-REPLACEMENT ("DUYỆT NỘI DUNG")
     ========================================================================== */
  const SensitiveWordModerator = {
    $currentTarget: null,
    currentDelim: '.',
    dictionary: [
      // 1. Cái chết, bạo lực, tự sát, máu me
      'không muốn sống', 'muốn chết', 'tìm chết', 'chết đi', 'đi chết đi', 'chết quách',
      'chết tiệt mày', 'chết tiệt', 'chết dẫm', 'cái chết', 'tử vong', 'tử thi', 'tự tử', 'tự sát',
      'treo cổ', 'nhảy lầu', 'cắt cổ', 'chặt đầu', 'đâm chết', 'bắn chết', 'giết người', 'giết chết', 'giết',
      'sát hại', 'mưu sát', 'thảm sát', 'hành hạ', 'tra tấn', 'xác chết', 'thi thể', 'vũng máu',
      'chảy máu', 'đẫm máu', 'máu me', 'máu chó', 'máu', 'huyết', 'chém chết', 'đâm chém', 'bắn giết',
      'đánh chết', 'bóp cổ', 'chết',
      // 2. Chửi thề, tục tĩu, lăng mạ, tiếng lóng Zhihu
      'đệt mợ', 'đệt mẹ', 'đệt cụ', 'đệt', 'đệch mợ', 'đệch mẹ', 'đệch',
      'đù má', 'đù mẹ', 'đù', 'đậu má', 'đậu mợ',
      'đụ má mày', 'đụ má', 'đụ mẹ mày', 'đụ mẹ', 'đụ bà', 'đụ con', 'đụ cha', 'đụ',
      'địt mẹ mày', 'địt con mẹ', 'địt mẹ', 'địt cụ mày', 'địt cụ', 'địt bà', 'địt cha', 'địt',
      'con mẹ nó', 'mẹ nó', 'cha nó', 'bà nó', 'bà nội nó', 'con đĩ mẹ nó',
      'đào mỏ', 'tiện nhân', 'đồ tiện nhân', 'tiện nữ', 'hồ ly tinh', 'trà xanh',
      'đĩ mẹ mày', 'đĩ điếm', 'đĩ chó', 'đĩ mẹ', 'đĩ', 'điếm', 'con điếm', 'con đĩ', 'phò', 'con phò', 'cave', 'gái bao', 'gái điếm', 'bitch',
      'lồn mẹ mày', 'lồn', 'con cặc', 'thằng cặc', 'cặc', 'buồi', 'dái', 'hòn dái', 'lỗ đít', 'lỗ nhị', 'hậu môn',
      'lũ chó', 'chó đẻ', 'chó má', 'đồ chó', 'khốn nạn', 'khốn khiếp', 'đồ khốn', 'vô liêm sỉ', 'mẹ kiếp',
      'vãi lồn', 'vãi cặc', 'vãi đái', 'vãi chưởng', 'vcl', 'vkl', 'vl', 'đm', 'dcm', 'clgt', 'cl', 'cc',
      'đéo', 'dell', 'đách', 'đếch', 'mẹ mày', 'bố mày', 'tiên sư', 'tổ sư', 'bố láo', 'mất dạy',
      'súc sinh', 'súc vật', 'cầm thú', 'bại hoại',
      // 3. Bộ phận sinh dục, quan hệ, 18+, đồi trụy
      'bộ phận sinh dục', 'dương vật', 'âm đạo', 'âm hộ', 'âm vật', 'hột lẹt', 'quy đầu', 'bao quy đầu',
      'tinh hoàn', 'tinh trùng', 'tinh dịch', 'thủ dâm', 'tự sướng', 'dâm đãng', 'dâm ô', 'dâm dục', 'dâm loạn',
      'làm tình', 'giao cấu', 'quan hệ tình dục', 'tình dục', 'quan hệ xác thịt', 'lên giường', 'ân ái',
      'khỏa thân', 'khoả thân', 'ngực trần', 'cởi trần', 'lột đồ', 'nude', 'sex',
      'bú cu', 'bú lồn', 'liếm lồn', 'bú mớm', 'nện', 'chịch', 'xoạc', 'bắn tinh',
      'hãm hiếp', 'cưỡng hiếp', 'hiếp dâm', 'cưỡng bức', 'bóp ngực', 'bóp vú', 'sờ soạng',
      'sờ mó', 'dục vọng', 'phê sướng', 'lên đỉnh', 'khiêu dâm', 'cu bự', 'chim to', 'móc lốp',
      'xếp hình', 'móc cua',
      // 4. Ma túy, chất kích thích độc hại
      'ma túy đá', 'ma túy', 'thuốc phiện', 'cần sa', 'heroin', 'thuốc lắc', 'đập đá', 'ngáo đá',
      'hút cỏ', 'ke kẹo'
    ],

    escapeRegex(str) {
      return (str || '').replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    },

    escapeHtml(str) {
      return $('<div>').text(str || '').html();
    },

    obfuscate(word, delim) {
      if (!word) return '';
      return word.split(/\s+/).map(part => Array.from(part).join(delim)).join(' ');
    },

    quickScan(rawText) {
      if (!rawText) return { foundMap: new Map(), totalMatches: 0 };
      const text = rawText.normalize('NFC');
      const sortedWords = [...this.dictionary]
        .map(w => (w || '').normalize('NFC').trim())
        .filter(w => w.length > 0)
        .sort((a, b) => b.length - a.length);

      if (!sortedWords.length) return { foundMap: new Map(), totalMatches: 0 };

      const pattern = sortedWords.map(w => this.escapeRegex(w)).join('|');
      let regex;
      try {
        regex = new RegExp('(?<![\\p{L}\\p{N}])(' + pattern + ')(?![\\p{L}\\p{N}])', 'gui');
      } catch (regErr) {
        regex = new RegExp('(^|[^a-zA-Z0-9à-ỹÀ-Ỹ])(' + pattern + ')(?=[^a-zA-Z0-9à-ỹÀ-Ỹ]|$)', 'gi');
      }

      const foundMap = new Map();
      let match;
      let totalMatches = 0;

      while ((match = regex.exec(text)) !== null) {
        const raw = match[1] || match[0];
        const lower = raw.toLowerCase().trim();
        if (lower) {
          if (!foundMap.has(lower)) {
            foundMap.set(lower, { sample: raw.trim(), count: 0 });
          }
          foundMap.get(lower).count++;
          totalMatches++;
        }
        if (match.index === regex.lastIndex) regex.lastIndex++;
      }

      return { foundMap, totalMatches };
    },

    scanAndOpen($target) {
      if (!$target || !$target.length) {
        // Fallback: tìm ô nội dung chương hoặc văn án trên trang
        const $fallback = $('#chapContent, #editChapterContent, #storyDescription, textarea[name="content"], textarea[name="description"]').filter(':visible').first();
        if ($fallback.length) {
          $target = $fallback;
        } else {
          window.muopAlert('Không tìm thấy ô nhập nội dung để duyệt!', 'error');
          return;
        }
      }

      this.$currentTarget = $target;
      const rawText = $target.val() || '';

      if (!rawText.trim()) {
        window.muopAlert('Vui lòng nhập hoặc dán nội dung vào ô trước khi nhấn Duyệt Nội Dung!', 'warning');
        return;
      }

      // 1. HIỆN POPUP ĐANG DUYỆT NỘI DUNG (HIỂN THỊ RÕ RÀNG TRONG SUỐT QUÁ TRÌNH DUYỆT)
      PopupManager.open({
        title: 'Đang Duyệt Nội Dung...',
        message: '<div style="text-align: center; padding: 14px 0;">' +
          '<i class="fa-solid fa-spinner fa-spin" style="font-size: 38px; color: var(--primary-green); margin-bottom: 14px; display: block;"></i>' +
          '<span style="font-size: 15px; font-weight: 600; color: var(--text-main); display: block; margin-bottom: 6px;">Hệ thống đang rà soát từng từ ngữ trong nội dung...</span>' +
          '<span style="font-size: 13px; color: var(--text-muted); display: block;">Quá trình đối soát quy tắc từ ngữ nhạy cảm, bạo lực, 18+ đang diễn ra, vui lòng đợi trong giây lát...</span>' +
          '</div>',
        type: 'info',
        hideButtons: true,
        hideCloseBtn: true
      });

      // 2. Thực hiện quét sau 1000ms để người dùng thấy rõ popup đang duyệt
      setTimeout(() => {
        try {
          // Chuẩn hóa Unicode NFC để bắt chính xác 100% tiếng Việt
          const text = rawText.normalize('NFC');
          const sortedWords = [...this.dictionary]
            .map(w => (w || '').normalize('NFC').trim())
            .filter(w => w.length > 0)
            .sort((a, b) => b.length - a.length);

          const pattern = sortedWords.map(w => this.escapeRegex(w)).join('|');
          
          let regex;
          try {
            regex = new RegExp('(?<![\\p{L}\\p{N}])(' + pattern + ')(?![\\p{L}\\p{N}])', 'gui');
          } catch (regErr) {
            regex = new RegExp('(^|[^a-zA-Z0-9à-ỹÀ-Ỹ])(' + pattern + ')(?=[^a-zA-Z0-9à-ỹÀ-Ỹ]|$)', 'gi');
          }

          const foundMap = new Map();
          let match;
          let totalMatches = 0;

          while ((match = regex.exec(text)) !== null) {
            const raw = match[1] || match[0];
            const lower = raw.toLowerCase().trim();
            if (lower) {
              if (!foundMap.has(lower)) {
                foundMap.set(lower, {
                  sample: raw.trim(),
                  count: 0
                });
              }
              foundMap.get(lower).count++;
              totalMatches++;
            }
            if (match.index === regex.lastIndex) {
              regex.lastIndex++;
            }
          }

          // Đóng popup đang duyệt sau khi duyệt xong
          PopupManager.close();

          // A. NẾU NỘI DUNG AN TOÀN (KHÔNG CÓ TỪ NHẠY CẢM)
          if (foundMap.size === 0) {
            setTimeout(() => {
              PopupManager.open({
                title: 'Nội Dung An Toàn',
                type: 'success',
                icon: 'fa-circle-check',
                message: '<strong>Đã duyệt xong!</strong><br><span style="font-size: 13.5px; color: var(--text-muted); display: block; margin-top: 8px;"><i class="fa-solid fa-shield-halved" style="color: var(--primary-green);"></i> Nội dung an toàn, không phát hiện từ ngữ nhạy cảm, thô tục hay vi phạm nào trong văn bản!</span>',
                confirmText: 'Tuyệt Vời'
              });
            }, 120);
            return;
          }

          // B. NẾU PHÁT HIỆN TỪ NHẠY CẢM:
          this.lastFoundMap = foundMap;
          this.lastTotalMatches = totalMatches;

          // B1. Đánh dấu trực tiếp lên bảng nội dung và tạo bộ công cụ rà soát
          this.renderDetectedSuite(this.$currentTarget, foundMap, totalMatches);

          // B2. Xây dựng danh sách các từ vi phạm dạng thẻ tag nổi bật màu đỏ
          let wordsBadgesHtml = '';
          foundMap.forEach((info) => {
            wordsBadgesHtml += `
              <span class="badge" style="display: inline-flex; align-items: center; gap: 6px; background: #fee2e2; border: 1.5px solid #ef4444; color: #991b1b; font-weight: 700; font-size: 13.5px; padding: 5px 12px; border-radius: 20px; box-shadow: 0 1px 3px rgba(220, 38, 38, 0.15);">
                <i class="fa-solid fa-triangle-exclamation" style="color: #dc2626; font-size: 12px;"></i>
                <span>${this.escapeHtml(info.sample)}</span>
                <span style="background: #dc2626; color: #fff; border-radius: 10px; padding: 1px 7px; font-size: 11px;">${info.count} lần</span>
              </span>
            `;
          });

          // B3. Hiện popup thông báo phát hiện từ nhạy cảm với danh sách từ rõ ràng
          setTimeout(() => {
            PopupManager.open({
              title: 'Phát Hiện Từ Nhạy Cảm!',
              type: 'warning',
              icon: 'fa-triangle-exclamation',
              maxWidth: '560px',
              message: `
                <div style="font-size: 14.5px; margin-bottom: 12px; line-height: 1.5;">
                  <strong>Đã duyệt xong!</strong> Hệ thống phát hiện <strong style="color: #dc2626; font-size: 16px;">${foundMap.size} từ nhạy cảm</strong> (<strong style="color: #dc2626;">${totalMatches} vị trí vi phạm</strong>) trong bài viết.
                </div>
                <div style="background: #fff5f5; border: 1.5px dashed #f87171; border-radius: 10px; padding: 12px 14px; margin: 12px 0 14px; text-align: left;">
                  <div style="font-size: 12.5px; font-weight: 700; color: #991b1b; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-list-check" style="color: #dc2626;"></i> Các từ nhạy cảm được đánh dấu trong bảng nội dung:
                  </div>
                  <div style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: flex-start; max-height: 140px; overflow-y: auto; padding: 2px;">
                    ${wordsBadgesHtml}
                  </div>
                </div>
                <div style="font-size: 13px; color: var(--text-muted); line-height: 1.5;">
                  Các từ trên đã được <strong>tô đỏ trực tiếp trong bảng nội dung</strong> bên dưới. Bạn có thể xem ngay, bấm vào từng từ để nhảy đến vị trí hoặc mở bảng thay thế tự động.
                </div>
              `,
              confirmText: 'Xem Bảng Đánh Dấu',
              cancelText: 'Mở Bảng Thay Thế',
              onConfirm: () => {
                // Đảm bảo bảng nội dung đánh dấu hiển thị và cuộn tới
                if (this.$currentTarget && this.$currentTarget.length) {
                  const $banner = $('.sensitive-detected-banner');
                  if ($banner.length) {
                    $('html, body').animate({ scrollTop: $banner.offset().top - 80 }, 300);
                  }
                }
              },
              onCancel: () => {
                SensitiveWordModerator.openReviewModal(foundMap, totalMatches);
              }
            });
          }, 120);

        } catch (err) {
          console.error('Lỗi khi duyệt nội dung:', err);
          PopupManager.close();
          setTimeout(() => {
            window.muopAlert('Có lỗi xảy ra trong quá trình duyệt: ' + (err.message || 'Lỗi không xác định'), 'error');
          }, 120);
        }
      }, 1000);
    },

    renderHighlightedPreview(rawText, foundMap) {
      if (!rawText) return '<div style="color: var(--text-muted); text-align: center; padding: 20px;">Nội dung trống.</div>';
      
      const words = Array.from(foundMap.keys()).sort((a, b) => b.length - a.length);
      if (!words.length) return this.escapeHtml(rawText);

      const pattern = words.map(w => this.escapeRegex(w)).join('|');
      let regex;
      try {
        regex = new RegExp('(?<![\\p{L}\\p{N}])(' + pattern + ')(?![\\p{L}\\p{N}])', 'gui');
      } catch (e) {
        regex = new RegExp('(^|[^a-zA-Z0-9à-ỹÀ-Ỹ])(' + pattern + ')(?=[^a-zA-Z0-9à-ỹÀ-Ỹ]|$)', 'gi');
      }

      let count = 0;
      let lastIndex = 0;
      let html = '';
      let match;
      const text = rawText.normalize('NFC');

      while ((match = regex.exec(text)) !== null) {
        const raw = match[1] || match[0];
        const matchStart = match.index + (match[0].indexOf(raw));
        const matchEnd = matchStart + raw.length;

        html += this.escapeHtml(text.substring(lastIndex, matchStart));

        count++;
        const lower = raw.toLowerCase().trim();
        html += `<mark class="sensitive-mark-highlight" data-word="${this.escapeHtml(lower)}" id="sensitiveMark_${count}" title="Vị trí vi phạm #${count}: ${this.escapeHtml(raw)} (Bấm để nhảy tới vị trí hoặc sửa)">${this.escapeHtml(raw)} <span style="font-size: 10px; opacity: 0.85; padding-left: 2px;">#${count}</span></mark>`;

        lastIndex = matchEnd;
        if (match.index === regex.lastIndex) {
          regex.lastIndex++;
        }
      }
      html += this.escapeHtml(text.substring(lastIndex));
      return html;
    },

    renderDetectedSuite($target, foundMap, totalMatches) {
      if (!$target || !$target.length) return;
      
      // Xóa các thanh và bảng đánh dấu cũ nếu có
      $('.sensitive-detected-banner, .sensitive-inline-content-box').remove();

      let chipsHtml = '';
      foundMap.forEach((info, lowerWord) => {
        chipsHtml += `
          <button type="button" class="btn-jump-sensitive-word" data-word="${this.escapeHtml(lowerWord)}" title="Nhấn để cuộn đến và đánh dấu từ này trong bảng nội dung">
            <span>📍 ${this.escapeHtml(info.sample)}</span>
            <span style="background: #dc2626; color: #fff; border-radius: 10px; padding: 1px 6px; font-size: 10.5px;">${info.count} lần</span>
          </button>
        `;
      });

      const bannerHtml = `
        <div class="sensitive-detected-banner">
          <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
            <div style="font-size: 13.5px; font-weight: 700; color: #991b1b; display: flex; align-items: center; gap: 6px;">
              <i class="fa-solid fa-triangle-exclamation" style="color: #dc2626; font-size: 16px;"></i>
              <span>Phát hiện <strong style="color: #dc2626; font-size: 14.5px;">${foundMap.size} từ nhạy cảm</strong> (${totalMatches} vị trí vi phạm) trong bảng nội dung:</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
              <button type="button" class="btn-quick-auto-replace" title="Tự động thay thế toàn bộ từ nhạy cảm bằng dấu chấm (.) ngay lập tức">
                <i class="fa-solid fa-wand-magic-sparkles"></i> Sửa Hết Bằng Dấu (.)
              </button>
              <button type="button" class="btn btn-sm btn-reopen-sensitive-modal" style="background: #dc2626; color: #fff; border: none; font-size: 12px; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-weight: 700; display: inline-flex; align-items: center; gap: 5px;">
                <i class="fa-solid fa-sliders"></i> Tùy Chọn Chi Tiết
              </button>
              <button type="button" class="btn-close-sensitive-banner" style="background: none; border: none; color: #991b1b; font-size: 20px; cursor: pointer; padding: 0 4px; line-height: 1;" title="Đóng thanh thông báo này">&times;</button>
            </div>
          </div>

          <!-- Thanh chuyển đổi chế độ xem giữa Bảng Đánh Dấu & Ô Soạn Thảo -->
          <div class="sensitive-view-switcher">
            <div class="sensitive-view-tabs">
              <button type="button" class="sensitive-view-tab-btn active" data-view="highlight" title="Xem bảng nội dung với toàn bộ từ vi phạm được tô đỏ nổi bật">
                <i class="fa-solid fa-highlighter" style="color: #dc2626;"></i>
                <span>1. Bảng Đánh Dấu Trong Bài</span>
                <span style="background: #dc2626; color: #fff; border-radius: 10px; padding: 1px 7px; font-size: 11px;">${totalMatches} vi phạm</span>
              </button>
              <button type="button" class="sensitive-view-tab-btn" data-view="edit" title="Chuyển về ô soạn thảo thông thường để tự gõ hoặc sửa văn bản">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>2. Ô Soạn Thảo (Tự sửa bằng tay)</span>
              </button>
            </div>
            <div style="font-size: 12px; color: var(--text-muted);">
              <i class="fa-solid fa-circle-info" style="color: var(--primary-green);"></i> Nhấn vào từ bên dưới để cuộn đến vị trí đánh dấu trong bảng:
            </div>
          </div>

          <!-- Dải chip danh sách các từ vi phạm -->
          <div style="display: flex; flex-wrap: wrap; gap: 6px; align-items: center;">
            ${chipsHtml}
          </div>
        </div>
      `;

      // 1. Chèn banner lên trước ô textarea
      $target.before(bannerHtml);

      // 2. Tạo khung Bảng Nội Dung Đã Đánh Dấu (Inline Highlighted Box) nằm ngay vị trí textarea
      const rawText = $target.val() || '';
      const previewHtml = this.renderHighlightedPreview(rawText, foundMap);
      const highlightBoxHtml = `
        <div class="sensitive-inline-content-box" id="sensitiveInlineContentBox" title="Bảng nội dung đã đánh dấu từ nhạy cảm. Bấm vào từ bất kỳ để sửa hoặc thay thế.">
          ${previewHtml}
        </div>
      `;
      $target.before(highlightBoxHtml);

      // 3. Mặc định: Hiện Bảng Đánh Dấu và ẩn tạm ô textarea để người dùng thấy ngay từ vi phạm được tô đỏ
      $target.hide();
    },

    jumpToWordInTextarea($target, word) {
      if (!$target || !$target.length) return;
      const textarea = $target[0];
      const text = textarea.value || '';
      const lowerText = text.toLowerCase();
      const searchWord = (word || '').toLowerCase();
      if (!searchWord) return;

      if (!this.cycleIndices) this.cycleIndices = {};
      const lastPos = this.cycleIndices[searchWord] !== undefined ? this.cycleIndices[searchWord] : -1;
      let nextIdx = lowerText.indexOf(searchWord, lastPos + 1);
      if (nextIdx === -1) {
        nextIdx = lowerText.indexOf(searchWord, 0);
      }
      if (nextIdx === -1) return;
      this.cycleIndices[searchWord] = nextIdx;

      // Đảm bảo ô soạn thảo hiển thị
      $target.show();
      $('#sensitiveInlineContentBox').hide();
      $('.sensitive-view-tab-btn[data-view="edit"]').addClass('active');
      $('.sensitive-view-tab-btn[data-view="highlight"]').removeClass('active');

      textarea.focus();
      textarea.setSelectionRange(nextIdx, nextIdx + searchWord.length);

      // Đo chính xác tọa độ pixel theo dòng của từ (Pixel Mirror Technique)
      try {
        const computed = window.getComputedStyle(textarea);
        const mirror = document.createElement('div');
        const stylesToCopy = [
          'boxSizing', 'width', 'fontFamily', 'fontSize', 'fontWeight',
          'lineHeight', 'letterSpacing', 'wordSpacing', 'paddingTop',
          'paddingBottom', 'paddingLeft', 'paddingRight', 'borderTopWidth',
          'borderLeftWidth', 'borderRightWidth', 'whiteSpace', 'wordBreak',
          'overflowWrap'
        ];
        stylesToCopy.forEach(prop => { mirror.style[prop] = computed[prop]; });
        mirror.style.position = 'absolute';
        mirror.style.top = '-99999px';
        mirror.style.left = '-99999px';
        mirror.style.visibility = 'hidden';
        mirror.style.height = 'auto';
        mirror.style.width = textarea.clientWidth + 'px';
        mirror.style.whiteSpace = 'pre-wrap';
        mirror.style.wordBreak = 'break-word';

        mirror.appendChild(document.createTextNode(text.substring(0, nextIdx)));
        const span = document.createElement('span');
        span.textContent = text.substring(nextIdx, nextIdx + searchWord.length);
        mirror.appendChild(span);

        document.body.appendChild(mirror);
        const targetTop = span.offsetTop;
        document.body.removeChild(mirror);

        const scrollTarget = Math.max(0, targetTop - (textarea.clientHeight / 2));
        textarea.scrollTop = scrollTarget;
      } catch (e) {
        const linesBefore = text.substring(0, nextIdx).split('\n').length;
        textarea.scrollTop = Math.max(0, (linesBefore - 3) * 24);
      }
    },

    scrollToWordInHighlightBox($box, word) {
      if (!$box || !$box.length) return;
      const lowerWord = (word || '').toLowerCase();
      const $marks = $box.find(`.sensitive-mark-highlight[data-word="${this.escapeHtml(lowerWord)}"]`);
      if (!$marks.length) return;

      if (!this.cycleBoxIndices) this.cycleBoxIndices = {};
      const lastIdx = this.cycleBoxIndices[lowerWord] !== undefined ? this.cycleBoxIndices[lowerWord] : -1;
      let nextIdx = (lastIdx + 1) % $marks.length;
      this.cycleBoxIndices[lowerWord] = nextIdx;

      const targetMark = $marks.get(nextIdx);
      if (targetMark) {
        $marks.removeClass('active-target');
        $(targetMark).addClass('active-target');

        const boxElem = $box[0];
        const markTop = targetMark.offsetTop;
        const boxTop = boxElem.offsetTop;
        const targetScroll = Math.max(0, (markTop - boxTop) - (boxElem.clientHeight / 2) + 20);

        $box.stop().animate({ scrollTop: targetScroll }, 250);

        setTimeout(() => {
          $(targetMark).removeClass('active-target');
        }, 2500);
      }
    },

    scrollToWordInModal(word) {
      const $container = $('#sensitiveTextHighlightContainer');
      if (!$container.length) return;
      const lowerWord = (word || '').toLowerCase();
      const $marks = $container.find(`.sensitive-mark-highlight[data-word="${this.escapeHtml(lowerWord)}"]`);
      if (!$marks.length) return;

      if (!this.cycleModalIndices) this.cycleModalIndices = {};
      const lastIdx = this.cycleModalIndices[lowerWord] !== undefined ? this.cycleModalIndices[lowerWord] : -1;
      let nextIdx = (lastIdx + 1) % $marks.length;
      this.cycleModalIndices[lowerWord] = nextIdx;

      const targetMark = $marks.get(nextIdx);
      if (targetMark) {
        $marks.removeClass('active-target');
        $(targetMark).addClass('active-target');

        const cElem = $container[0];
        const markTop = targetMark.offsetTop;
        const cTop = cElem.offsetTop;
        const targetScroll = Math.max(0, (markTop - cTop) - (cElem.clientHeight / 2) + 20);

        $container.stop().animate({ scrollTop: targetScroll }, 250);

        setTimeout(() => {
          $(targetMark).removeClass('active-target');
        }, 2500);
      }
    },

    autoReplaceAll($target, foundMap, delim) {
      if (!$target || !$target.length || !foundMap || foundMap.size === 0) return;
      delim = delim || this.currentDelim || '.';
      let text = ($target.val() || '').normalize('NFC');
      let count = 0;

      const words = Array.from(foundMap.keys()).sort((a, b) => b.length - a.length);
      words.forEach(word => {
        let regex;
        try {
          regex = new RegExp('(?<![\\p{L}\\p{N}])(' + this.escapeRegex(word) + ')(?![\\p{L}\\p{N}])', 'gui');
        } catch (e) {
          regex = new RegExp('(^|[^a-zA-Z0-9à-ỹÀ-Ỹ])(' + this.escapeRegex(word) + ')(?=[^a-zA-Z0-9à-ỹÀ-Ỹ]|$)', 'gi');
        }
        text = text.replace(regex, (match) => {
          count++;
          return SensitiveWordModerator.obfuscate(match, delim);
        });
      });

      $target.val(text);
      $target.show();
      $target.trigger('input').trigger('change');

      // Đồng bộ bộ đếm số từ & ký tự
      if (typeof window.muopUpdateWordCounter === 'function') {
        if ($target.is('#chapContent')) {
          window.muopUpdateWordCounter($('#chapContent'), $('#chapContentWordCount'), $('#chapContentCharCount'));
        } else if ($target.is('#editChapterContent')) {
          window.muopUpdateWordCounter($('#editChapterContent'), $('#editChapterContentWordCount'), $('#editChapterContentCharCount'));
        }
      }

      // Xóa bỏ thanh cảnh báo và khung highlight
      $('.sensitive-detected-banner, .sensitive-inline-content-box').remove();

      PopupManager.open({
        title: 'Đã Tự Động Thay Thế!',
        type: 'success',
        icon: 'fa-circle-check',
        message: '<strong>Thành công!</strong> Đã tự động thay thế an toàn <strong>' + count + ' vị trí</strong> từ nhạy cảm trong bảng nội dung bằng ký tự phân cách (' + delim + ').',
        confirmText: 'Tuyệt Vời'
      });
    },

    openReviewModal(foundMap, totalMatches) {
      $('#sensitiveNoneFound').hide();
      $('#sensitiveTableWrapper').hide();
      $('#sensitiveDelimiterBar').show();
      $('#sensitivePreviewWrapper').show();
      $('#tabBtnSensitivePreview').addClass('active');
      $('#tabBtnSensitiveTable').removeClass('active');

      $('#btnApplySensitiveReplacements').show();
      $('#sensitiveCountBadge').text(foundMap.size + ' từ (' + totalMatches + ' vị trí)');
      $('#detectedWordsCountText').text(foundMap.size + ' từ (' + totalMatches + ' vị trí)');
      $('#sensitiveTabTableCount').text(foundMap.size + ' từ');
      $('#sensitiveTabPreviewCount').text(totalMatches + ' vị trí');
      $('#sensitiveScanResultNotice').show();

      // Đổ danh sách chip nhảy nhanh trong modal
      let modalChipsHtml = '';
      foundMap.forEach((info, lowerWord) => {
        modalChipsHtml += `
          <button type="button" class="btn-jump-sensitive-word modal-jump-chip" data-word="${this.escapeHtml(lowerWord)}" title="Nhấn để cuộn đến từ này trong bài">
            <span>📍 ${this.escapeHtml(info.sample)}</span>
            <span style="background: #dc2626; color: #fff; border-radius: 10px; padding: 1px 6px; font-size: 10.5px;">${info.count} lần</span>
          </button>
        `;
      });
      $('#modalSensitiveWordChips').html(modalChipsHtml);

      // Đổ bảng thay thế chi tiết ở Tab 2
      let rowsHtml = '';
      foundMap.forEach((info, lowerWord) => {
        const defaultReplacement = this.obfuscate(info.sample, this.currentDelim);
        rowsHtml += `
          <tr data-word="${this.escapeHtml(lowerWord)}" data-sample="${this.escapeHtml(info.sample)}" style="border-bottom: 1px solid var(--border-light);">
            <td style="padding: 10px 12px; text-align: center;">
              <input type="checkbox" class="sensitive-word-check" data-word="${this.escapeHtml(lowerWord)}" checked style="width: 16px; height: 16px; cursor: pointer; accent-color: var(--primary-green);" />
            </td>
            <td style="padding: 10px 12px;">
              <span class="sensitive-highlight-word">${this.escapeHtml(info.sample)}</span>
            </td>
            <td style="padding: 10px 12px; text-align: center;">
              <span class="badge" style="background: rgba(239, 68, 68, 0.12); color: #dc2626; font-weight: 700; padding: 2px 8px; border-radius: 10px; font-size: 12px;">${info.count}</span>
            </td>
            <td style="padding: 10px 12px;">
              <input type="text" class="form-input sensitive-replace-input" data-word="${this.escapeHtml(lowerWord)}" value="${this.escapeHtml(defaultReplacement)}" style="width: 100%; font-size: 13.5px; font-weight: 600; padding: 6px 10px; border-radius: 6px; box-sizing: border-box;" />
            </td>
            <td style="padding: 10px 12px; text-align: center;">
              <button type="button" class="btn btn-sm btn-outline btn-view-word-in-preview" data-word="${this.escapeHtml(lowerWord)}" style="font-size: 11.5px; padding: 4px 10px; border-radius: 6px; white-space: nowrap; border-color: #f87171; color: #dc2626; font-weight: 600; cursor: pointer;">
                <i class="fa-solid fa-eye"></i> Xem vị trí
              </button>
            </td>
          </tr>
        `;
      });

      $('#sensitiveWordsListBody').html(rowsHtml);
      $('#checkAllSensitiveWords').prop('checked', true);

      // Render nội dung văn bản kèm thẻ đánh dấu highlight
      const previewHtml = this.renderHighlightedPreview(this.$currentTarget ? this.$currentTarget.val() : '', foundMap);
      $('#sensitiveTextHighlightContainer').html(previewHtml);

      $('#modalSensitiveWordReview').css({ 'display': 'flex', 'z-index': 999999999 }).hide().fadeIn(200);
    },

    init() {
      // Tải từ điển nhạy cảm từ Database nếu có
      if (typeof muopConfig !== 'undefined' && Array.isArray(muopConfig.sensitiveWords) && muopConfig.sensitiveWords.length > 0) {
        this.dictionary = [...muopConfig.sensitiveWords];
      }
      // Đảm bảo từ điển được sắp xếp theo độ dài giảm dần
      this.dictionary.sort((a, b) => b.length - a.length);

      // 1. Nhấn nút "Duyệt Nội Dung"
      $(document).on('click', '.btn-scan-sensitive', function(e) {
        e.preventDefault();
        const targetSelector = $(this).data('target');
        let $target = null;
        if (targetSelector) {
          $target = $(targetSelector);
        }
        if (!$target || !$target.length) {
          $target = $(this).closest('.form-group').find('textarea');
        }
        SensitiveWordModerator.scanAndOpen($target);
      });

      // 2. Chuyển đổi ký tự phân cách (.) (*) (/) (-)
      $(document).on('click', '.delimiter-pill', function(e) {
        e.preventDefault();
        $('.delimiter-pill').removeClass('active');
        $(this).addClass('active');
        const delim = $(this).data('delim') || '.';
        SensitiveWordModerator.currentDelim = delim;

        $('#sensitiveWordsListBody tr').each(function() {
          const $row = $(this);
          const sample = $row.data('sample');
          const $input = $row.find('.sensitive-replace-input');
          const currentVal = $input.val().trim();
          const lettersVal = currentVal.replace(/[^a-zA-ZÀ-ỹ0-9]/g, '').toLowerCase();
          const lettersSample = String(sample).replace(/[^a-zA-ZÀ-ỹ0-9]/g, '').toLowerCase();
          if (lettersVal === lettersSample || $input.attr('data-is-custom') !== '1') {
            $input.val(SensitiveWordModerator.obfuscate(sample, delim));
          }
        });
      });

      // 3. Đánh dấu người dùng tự chỉnh sửa ô thay thế
      $(document).on('input', '.sensitive-replace-input', function() {
        $(this).attr('data-is-custom', '1');
      });

      // 4. Chọn / Bỏ chọn tất cả checkbox
      $(document).on('change', '#checkAllSensitiveWords', function() {
        const isChecked = $(this).is(':checked');
        $('.sensitive-word-check').prop('checked', isChecked);
      });

      $(document).on('change', '.sensitive-word-check', function() {
        const total = $('.sensitive-word-check').length;
        const checked = $('.sensitive-word-check:checked').length;
        $('#checkAllSensitiveWords').prop('checked', total > 0 && total === checked);
      });

      // 5. Đóng modal
      $(document).on('click', '#btnCloseSensitiveReviewModal, #btnDismissSensitiveModal', (e) => {
        e.preventDefault();
        $('#modalSensitiveWordReview').fadeOut(180);
      });

      $(document).on('click', '#modalSensitiveWordReview', function(e) {
        if (e.target === this) {
          $(this).fadeOut(180);
        }
      });

      // Tab switcher in Sensitive Word Review Modal
      $(document).on('click', '.sensitive-tab-btn', function(e) {
        e.preventDefault();
        const tab = $(this).data('tab');
        $('.sensitive-tab-btn').removeClass('active');
        $(this).addClass('active');

        if (tab === 'preview') {
          $('#sensitiveTableWrapper').hide();
          $('#sensitivePreviewWrapper').fadeIn(150);
        } else {
          $('#sensitivePreviewWrapper').hide();
          $('#sensitiveTableWrapper').fadeIn(150);
        }
      });

      // Chuyển đổi qua lại giữa [Bảng Đánh Dấu] và [Ô Soạn Thảo] trên trang chính
      $(document).on('click', '.sensitive-view-tab-btn', function(e) {
        e.preventDefault();
        const view = $(this).data('view');
        $('.sensitive-view-tab-btn').removeClass('active');
        $(this).addClass('active');

        const $target = SensitiveWordModerator.$currentTarget;
        const $box = $('#sensitiveInlineContentBox');

        if (view === 'highlight') {
          // Cập nhật lại nội dung đánh dấu từ ô textarea mới nhất
          if ($target && $target.length && SensitiveWordModerator.lastFoundMap) {
            const rawText = $target.val() || '';
            const previewHtml = SensitiveWordModerator.renderHighlightedPreview(rawText, SensitiveWordModerator.lastFoundMap);
            $box.html(previewHtml);
          }
          $target.hide();
          $box.fadeIn(150);
        } else {
          // Chuyển sang ô soạn thảo để người dùng tự sửa
          $box.hide();
          $target.fadeIn(150).focus();
        }
      });

      // Nút sửa nhanh toàn bộ bằng dấu chấm (.) ngay trên banner
      $(document).on('click', '.btn-quick-auto-replace', function(e) {
        e.preventDefault();
        SensitiveWordModerator.autoReplaceAll(
          SensitiveWordModerator.$currentTarget,
          SensitiveWordModerator.lastFoundMap,
          '.'
        );
      });

      // Click chip từ khóa trên banner trang chính
      $(document).on('click', '.btn-jump-sensitive-word:not(.modal-jump-chip)', function(e) {
        e.preventDefault();
        const word = $(this).data('word');
        const isHighlightView = $('.sensitive-view-tab-btn[data-view="highlight"]').hasClass('active');
        const $box = $('#sensitiveInlineContentBox');

        if (isHighlightView && $box.is(':visible')) {
          SensitiveWordModerator.scrollToWordInHighlightBox($box, word);
        } else {
          SensitiveWordModerator.jumpToWordInTextarea(SensitiveWordModerator.$currentTarget, word);
        }
      });

      // Click chip từ khóa trong modal review
      $(document).on('click', '.modal-jump-chip', function(e) {
        e.preventDefault();
        const word = $(this).data('word');
        SensitiveWordModerator.scrollToWordInModal(word);
      });

      // Click vào từ đánh dấu trong bảng nội dung inline
      $(document).on('click', '#sensitiveInlineContentBox .sensitive-mark-highlight', function(e) {
        e.preventDefault();
        const word = $(this).data('word');
        // Chuyển sang ô soạn thảo và chọn ngay từ đó
        SensitiveWordModerator.jumpToWordInTextarea(SensitiveWordModerator.$currentTarget, word);
      });

      // Click "Xem vị trí" in table row (Tab 2 Modal)
      $(document).on('click', '.btn-view-word-in-preview', function(e) {
        e.preventDefault();
        const word = $(this).data('word');
        $('#tabBtnSensitivePreview').trigger('click');
        setTimeout(() => {
          SensitiveWordModerator.scrollToWordInModal(word);
        }, 120);
      });

      // Đóng banner và hiển thị lại ô soạn thảo
      $(document).on('click', '.btn-close-sensitive-banner', function(e) {
        e.preventDefault();
        $('.sensitive-detected-banner, #sensitiveInlineContentBox').remove();
        if (SensitiveWordModerator.$currentTarget) {
          SensitiveWordModerator.$currentTarget.show();
        }
      });

      // Mở lại modal rà soát từ banner
      $(document).on('click', '.btn-reopen-sensitive-modal', function(e) {
        e.preventDefault();
        if (SensitiveWordModerator.lastFoundMap) {
          SensitiveWordModerator.openReviewModal(SensitiveWordModerator.lastFoundMap, SensitiveWordModerator.lastTotalMatches || 0);
        }
      });

      // 6. Nhấn nút OK - Thay Thế / Cập Nhật Toàn Bộ
      $(document).on('click', '#btnApplySensitiveReplacements', () => {
        if (!this.$currentTarget || !this.$currentTarget.length) {
          $('#modalSensitiveWordReview').fadeOut(180);
          return;
        }

        const $checkedRows = $('#sensitiveWordsListBody tr').filter(function() {
          return $(this).find('.sensitive-word-check').is(':checked');
        });

        if ($checkedRows.length === 0) {
          window.muopAlert('Vui lòng chọn ít nhất một từ để thay thế!', 'warning');
          return;
        }

        const replacements = [];
        $checkedRows.each(function() {
          const $row = $(this);
          const word = $row.data('word');
          const sample = $row.data('sample');
          const $input = $row.find('.sensitive-replace-input');
          const val = $input.val().trim();
          if (val) {
            const lettersVal = val.replace(/[^a-zA-ZÀ-ỹ0-9]/g, '').toLowerCase();
            const lettersSample = String(sample).replace(/[^a-zA-ZÀ-ỹ0-9]/g, '').toLowerCase();
            const isCustom = (lettersVal !== lettersSample) && ($input.attr('data-is-custom') === '1');
            replacements.push({
              word: word,
              sample: sample,
              val: val,
              isCustom: isCustom
            });
          }
        });

        // Sắp xếp từ dài nhất trước để thay thế an toàn
        replacements.sort((a, b) => b.word.length - a.word.length);

        let currentText = (this.$currentTarget.val() || '').normalize('NFC');
        let totalReplaced = 0;

        replacements.forEach(item => {
          let regex;
          try {
            regex = new RegExp('(?<![\\p{L}\\p{N}])(' + this.escapeRegex(item.word) + ')(?![\\p{L}\\p{N}])', 'gui');
          } catch (e) {
            regex = new RegExp('(^|[^a-zA-Z0-9à-ỹÀ-Ỹ])(' + this.escapeRegex(item.word) + ')(?=[^a-zA-Z0-9à-ỹÀ-Ỹ]|$)', 'gi');
          }
          currentText = currentText.replace(regex, (match) => {
            totalReplaced++;
            if (item.isCustom) {
              return item.val;
            } else {
              const nonAlpha = item.val.match(/[^a-zA-ZÀ-ỹ0-9\s]/);
              const delim = nonAlpha ? nonAlpha[0] : SensitiveWordModerator.currentDelim;
              return SensitiveWordModerator.obfuscate(match, delim);
            }
          });
        });

        // Cập nhật lại vào textarea và hiển thị lại ô soạn thảo
        this.$currentTarget.val(currentText).show();
        this.$currentTarget.trigger('input').trigger('change');

        // Xóa thanh banner và bảng đánh dấu vì các từ nhạy cảm đã được thay thế an toàn
        $('.sensitive-detected-banner, .sensitive-inline-content-box').remove();

        // Đồng bộ số từ & số ký tự
        if (typeof window.muopUpdateWordCounter === 'function') {
          if (this.$currentTarget.is('#chapContent')) {
            window.muopUpdateWordCounter($('#chapContent'), $('#chapContentWordCount'), $('#chapContentCharCount'));
          } else if (this.$currentTarget.is('#editChapterContent')) {
            window.muopUpdateWordCounter($('#editChapterContent'), $('#editChapterContentWordCount'), $('#editChapterContentCharCount'));
          }
        }

        // Đóng modal rà soát
        $('#modalSensitiveWordReview').fadeOut(180);

        // HIỆN POPUP THÔNG BÁO ĐÃ CHỈNH SỬA XONG
        setTimeout(() => {
          PopupManager.open({
            title: 'Chỉnh Sửa Hoàn Tất',
            type: 'success',
            icon: 'fa-circle-check',
            message: '<strong>Đã duyệt & chỉnh sửa xong!</strong><br><span style="font-size: 13.5px; color: var(--text-muted); display: block; margin-top: 8px;"><i class="fa-solid fa-check-double" style="color: var(--primary-green);"></i> Đã thay thế thành công <strong>' + totalReplaced + ' vị trí</strong> từ nhạy cảm trong nội dung!</span>',
            confirmText: 'Đã Hiểu'
          });
        }, 200);
      });
    }
  };

  // =========================================================================
  // IMAGE OPTIMIZER MANAGER (Tối ưu hóa & nén ảnh thông minh phía client + admin)
  // =========================================================================
  const ImageOptimizerManager = {
    init: function() {
      // 1. Cung cấp hàm toàn cục cho website
      window.muopCompressImage = this.compressImage;

      // 2. Tự động lắng nghe sự kiện chọn ảnh trên mọi input file ảnh
      $(document).on('change', 'input[type="file"]', async function(e) {
        const input = this;
        const accept = (input.getAttribute('accept') || '').toLowerCase();
        const inputName = (input.name || '').toLowerCase();
        const inputId = (input.id || '').toLowerCase();

        const isImageInput = accept.indexOf('image') !== -1 || 
                             ['cover', 'avatar', 'bill', 'photo', 'thumb', 'image'].some(key => inputName.indexOf(key) !== -1 || inputId.indexOf(key) !== -1);

        if (!isImageInput) return;
        if (!input.files || !input.files.length) return;

        const file = input.files[0];
        if (!file.type || !file.type.startsWith('image/') || file.type === 'image/gif') return;

        // Nếu ảnh lớn hơn 300KB, tiến hành tối ưu nhanh qua Canvas
        if (file.size > 300 * 1024) {
          try {
            const originalKb = Math.round(file.size / 1024);
            const compressedFile = await ImageOptimizerManager.compressImage(file, {
              maxWidth: 1600,
              maxHeight: 2400,
              quality: 0.82
            });

            if (compressedFile && compressedFile.size < file.size) {
              const newKb = Math.round(compressedFile.size / 1024);
              const percent = Math.round((1 - compressedFile.size / file.size) * 100);

              if (window.DataTransfer) {
                const dt = new DataTransfer();
                dt.items.add(compressedFile);
                input.files = dt.files;
              }

              console.log(`[MuopImageOptimizer] Đã tối ưu ảnh "${file.name}": ${originalKb}KB -> ${newKb}KB (Giảm ${percent}%)`);

              // Hiển thị huy hiệu đã nén cạnh tên file
              const $container = $(input).closest('.form-group, .cover-upload-box, .profile-avatar-wrap, form, td');
              const $info = $container.find('#coverFileName, .upload-size-badge, .file-name-preview, .custom-file-label');
              if ($info.length && !$container.find('.muop-opt-badge').length) {
                $info.append(`<span class="muop-opt-badge" style="font-size: 11px; background: #dcfce7; color: #166534; padding: 2px 6px; border-radius: 4px; margin-left: 6px; font-weight: 600;"><i class="fa-solid fa-bolt"></i> Đã nén -${percent}% (${newKb}KB)</span>`);
              }
            }
          } catch (err) {
            console.warn('[MuopImageOptimizer] Lỗi nén ảnh phía client:', err);
          }
        }
      });

      // 3. Xử lý nút Quét & Nén Thư Viện Ảnh Cũ trong Admin Portal
      $(document).on('click', '#btnScanOptimizeLibrary', function() {
        const $btn = $(this);
        const $box = $('#scanOptimizeProgressBox');
        const $msg = $('#scanProgressMsg');
        
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang nén ảnh...');
        $box.slideDown(200);
        $msg.html('<i class="fa-solid fa-spinner fa-spin"></i> Đang quét và tối ưu các tệp ảnh trong thư viện uploads...');

        const adminNonce = $('input[name="admin_nonce"]').val() || (typeof muopConfig !== 'undefined' ? muopConfig.nonce : '');

        $.ajax({
          url: (typeof muopConfig !== 'undefined' ? muopConfig.ajaxUrl : '/core/wp-admin/admin-ajax.php'),
          type: 'POST',
          data: {
            action: 'muop_optimize_existing_library',
            nonce: adminNonce
          },
          success: function(res) {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-bolt"></i> Quét & Nén Thư Viện Ảnh Cũ');
            if (res && res.success) {
              if (res.data.total_files) $('#optStatFiles').text(res.data.total_files + ' ảnh');
              if (res.data.mb_saved) $('#optStatBytes').text(res.data.mb_saved);
              $msg.html('<i class="fa-solid fa-circle-check" style="color: #16a34a;"></i> ' + res.data.message + ' (Tiết kiệm +' + res.data.batch_saved_kb + ' KB)');
              setTimeout(() => { $box.slideUp(300); }, 6000);
            } else {
              $msg.html('<i class="fa-solid fa-circle-exclamation" style="color: #ef4444;"></i> ' + (res.data ? res.data.message : 'Có lỗi xảy ra khi nén thư viện.'));
            }
          },
          error: function() {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-bolt"></i> Quét & Nén Thư Viện Ảnh Cũ');
            $msg.html('<i class="fa-solid fa-circle-exclamation" style="color: #ef4444;"></i> Lỗi kết nối tới máy chủ khi nén ảnh.');
          }
        });
      });
    },

    /**
     * Nén tệp hình ảnh sử dụng Canvas API chất lượng cao
     */
    compressImage: function(file, options) {
      options = options || {};
      const maxWidth = options.maxWidth || 1600;
      const maxHeight = options.maxHeight || 2400;
      const quality = options.quality || 0.82;

      return new Promise((resolve) => {
        if (!file || !file.type || !file.type.startsWith('image/') || file.type === 'image/gif') {
          return resolve(file);
        }

        const reader = new FileReader();
        reader.onload = function(e) {
          const img = new Image();
          img.onload = function() {
            let width = img.naturalWidth || img.width;
            let height = img.naturalHeight || img.height;

            if (width <= maxWidth && height <= maxHeight && file.size < 350 * 1024) {
              return resolve(file);
            }

            if (width > maxWidth || height > maxHeight) {
              const ratio = Math.min(maxWidth / width, maxHeight / height);
              width = Math.max(1, Math.round(width * ratio));
              height = Math.max(1, Math.round(height * ratio));
            }

            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');

            ctx.imageSmoothingEnabled = true;
            ctx.imageSmoothingQuality = 'high';

            ctx.drawImage(img, 0, 0, width, height);

            const mime = (file.type === 'image/png') ? 'image/png' : 'image/jpeg';
            canvas.toBlob((blob) => {
              if (!blob || blob.size >= file.size) {
                return resolve(file);
              }
              const compressed = new File([blob], file.name, {
                type: mime,
                lastModified: Date.now()
              });
              resolve(compressed);
            }, mime, quality);
          };
          img.onerror = function() {
            resolve(file);
          };
          img.src = e.target.result;
        };
        reader.onerror = function() {
          resolve(file);
        };
        reader.readAsDataURL(file);
      });
    }
  };

  // 31. SPOTLIGHT CAROUSEL MANAGER (TOP TRUYỆN NỔI BẬT CHUYỂN ĐỘNG QUA LẠI)
  const SpotlightCarouselManager = {
    init() {
      const $box = $('#teamSpotlightBox');
      if (!$box.length) return;

      const $track = $('#teamSpotlightTrack');
      const $slides = $track.find('.spotlight-slide');
      const total = $slides.length;
      if (total <= 1) return;

      let current = 0;
      let timer = null;
      let isPaused = false;
      let direction = 1; // 1: tiến, -1: lùi (chuyển động qua lại)

      const goToSlide = (index) => {
        if (index < 0) index = total - 1;
        if (index >= total) index = 0;
        current = index;
        $track.css('transform', 'translateX(-' + (current * 100) + '%)');
        $('#teamSpotlightDots .spotlight-dot').removeClass('active');
        $('#teamSpotlightDots .spotlight-dot[data-slide="' + current + '"]').addClass('active');
      };

      const startAutoPlay = () => {
        stopAutoPlay();
        timer = setInterval(() => {
          if (isPaused) return;
          // Tự động chuyển động qua lại
          if (current >= total - 1) {
            direction = -1;
          } else if (current <= 0) {
            direction = 1;
          }
          goToSlide(current + direction);
        }, 3500);
      };

      const stopAutoPlay = () => {
        if (timer) {
          clearInterval(timer);
          timer = null;
        }
      };

      $('#btnSpotlightPrev').on('click', function(e) {
        e.preventDefault();
        goToSlide(current - 1);
        startAutoPlay();
      });

      $('#btnSpotlightNext').on('click', function(e) {
        e.preventDefault();
        goToSlide(current + 1);
        startAutoPlay();
      });

      $(document).on('click', '#teamSpotlightDots .spotlight-dot', function(e) {
        e.preventDefault();
        const idx = parseInt($(this).data('slide'), 10);
        goToSlide(idx);
        startAutoPlay();
      });

      // Tạm dừng khi rê chuột hoặc chạm tay
      $box.on('mouseenter touchstart', function() {
        isPaused = true;
      }).on('mouseleave touchend', function() {
        isPaused = false;
      });

      // Vuốt chạm cảm ứng trên di động
      let startX = 0;
      $box.on('touchstart', function(e) {
        if (e.originalEvent && e.originalEvent.touches && e.originalEvent.touches.length) {
          startX = e.originalEvent.touches[0].clientX;
        }
      });
      $box.on('touchend', function(e) {
        if (e.originalEvent && e.originalEvent.changedTouches && e.originalEvent.changedTouches.length) {
          const endX = e.originalEvent.changedTouches[0].clientX;
          const diff = startX - endX;
          if (Math.abs(diff) > 40) {
            if (diff > 0) goToSlide(current + 1);
            else goToSlide(current - 1);
            startAutoPlay();
          }
        }
      });

      startAutoPlay();
    }
  };

  // 32. SCHEDULE STORY MANAGER (HẸN GIỜ ĐĂNG TRUYỆN, POPUP ĐẾM NGƯỢC & BỘ LỌC)
  const ScheduleStoryManager = {
    activeCountdownInterval: null,

    calcPreset(type, hours) {
      const d = new Date();
      if (hours) {
        d.setHours(d.getHours() + hours);
      } else if (type === 'tonight') {
        if (d.getHours() >= 20) d.setDate(d.getDate() + 1);
        d.setHours(20, 0, 0, 0);
      } else if (type === 'tomorrow_morning') {
        d.setDate(d.getDate() + 1);
        d.setHours(8, 0, 0, 0);
      }
      const pad = n => String(n).padStart(2, '0');
      return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
    },

    init() {
      const self = this;

      // Đảm bảo trạng thái hiển thị đúng nếu checkbox đã được tích sẵn
      if ($('#toggleScheduleStory').is(':checked')) {
        $('#scheduleTimeCollapse').show();
        $('#btnSubmitStoryText').text('Lên Lịch Đăng');
        $('#btnSubmitStoryIcon').removeClass('fa-cloud-arrow-up').addClass('fa-clock');
      }
      if ($('#editToggleScheduleStory').is(':checked')) {
        $('#editScheduleTimeCollapse').show();
      }

      // 1. Giao diện trang Đăng Truyện: Bật/Tắt hẹn giờ
      $(document).on('change', '#toggleScheduleStory', function() {
        if ($(this).is(':checked')) {
          $('#scheduleTimeCollapse').slideDown(200);
          $('#btnSubmitStoryText').text('Lên Lịch Đăng');
          $('#btnSubmitStoryIcon').removeClass('fa-cloud-arrow-up').addClass('fa-clock');
          if (!$('#storyScheduledTime').val()) {
            $('#storyScheduledTime').val(self.calcPreset(null, 3));
          }
        } else {
          $('#scheduleTimeCollapse').slideUp(200);
          $('#btnSubmitStoryText').text('Đăng Truyện');
          $('#btnSubmitStoryIcon').removeClass('fa-clock').addClass('fa-cloud-arrow-up');
        }
      });

      // Nút chọn nhanh thời gian trong form Đăng truyện
      $(document).on('click', '.btn-quick-sched', function(e) {
        e.preventDefault();
        const hours = $(this).data('hours');
        const preset = $(this).data('preset');
        if (typeof muopSetQuickSchedule === 'function') {
          muopSetQuickSchedule(hours, preset);
        } else {
          $('#storyScheduledTime').val(self.calcPreset(preset, hours));
        }
        if (!$('#toggleScheduleStory').is(':checked')) {
          $('#toggleScheduleStory').prop('checked', true).trigger('change');
        }
      });

      // 2. Giao diện Modal Sửa truyện: Bật/Tắt hẹn giờ
      $(document).on('change', '#editToggleScheduleStory', function() {
        if ($(this).is(':checked')) {
          $('#editScheduleTimeCollapse').slideDown(200);
          if (typeof muopSetQuickEditSchedule === 'function') {
            muopSetQuickEditSchedule(3, null);
          } else if (!$('#editStoryScheduledTime').val()) {
            $('#editStoryScheduledTime').val(self.calcPreset(null, 3));
          }
        } else {
          $('#editScheduleTimeCollapse').slideUp(200);
        }
      });

      $(document).on('click', '.btn-quick-edit-sched', function(e) {
        e.preventDefault();
        const hours = $(this).data('hours');
        const preset = $(this).data('preset');
        if (typeof muopSetQuickEditSchedule === 'function') {
          muopSetQuickEditSchedule(hours, preset);
        } else {
          $('#editStoryScheduledTime').val(self.calcPreset(preset, hours));
        }
        if (!$('#editToggleScheduleStory').is(':checked')) {
          $('#editToggleScheduleStory').prop('checked', true).trigger('change');
        }
      });

      $(document).on('click', '.btn-popup-quick-sched', function(e) {
        e.preventDefault();
        const hours = $(this).data('hours');
        const preset = $(this).data('preset');
        if (typeof window.muopSetPopupQuickSchedule === 'function') {
          window.muopSetPopupQuickSchedule(hours, preset);
        } else {
          $('#inputQuickChangeScheduleTime').val(self.calcPreset(preset, hours));
        }
      });

      // 3. Mở Popup Chi Tiết Thời Gian Đã Lên Lịch khi click vào trạng thái Đã hẹn giờ
      $(document).on('click', '.btn-show-scheduled-popup', function(e) {
        e.preventDefault();
        $('.story-actions-dropdown').removeClass('is-open');
        const storyId   = $(this).data('story-id');
        const storyTitle= $(this).data('story-title') || 'Bộ truyện';
        const schedTime = $(this).data('scheduled-time') || '';
        const schedFmt  = $(this).data('scheduled-formatted') || schedTime;

        if (!storyId) return;

        if (typeof window.muopOpenScheduleModal === 'function') {
          window.muopOpenScheduleModal(storyId, storyTitle, schedTime, schedFmt);
          return;
        }

        const $modal = $('#modalScheduleStoryDetail');
        if (!$modal.length) return;

        $modal.data('story-id', storyId);
        $modal.data('scheduled-time', schedTime);
        $('#scheduleModalStoryTitle').text(storyTitle);
        $('#scheduleModalDatetimeText').text(schedFmt);
        $('#scheduleDetailAlert').hide();
        $('#scheduleChangeFormWrap').hide();

        // Bắt đầu đếm ngược thời gian thực
        self.startCountdown(schedTime);
        $modal.css('display', 'flex');
      });

      // Đóng Popup
      $('.btn-close-schedule-modal').on('click', function(e) {
        e.preventDefault();
        self.stopCountdown();
        $('#modalScheduleStoryDetail').hide();
      });

      $('#modalScheduleStoryDetail').on('click', function(e) {
        if (e.target === this) {
          self.stopCountdown();
          $(this).hide();
        }
      });

      // 4. Bấm "Đổi Giờ Hẹn" trong Popup
      $('#btnTriggerToggleChangeSchedule').on('click', function(e) {
        e.preventDefault();
        $('#scheduleChangeFormWrap').slideToggle(200);
      });

      $('#btnCancelToggleChangeSchedule').on('click', function(e) {
        e.preventDefault();
        $('#scheduleChangeFormWrap').slideUp(200);
      });

      // 5. Xác nhận lưu giờ hẹn mới từ Popup
      $('#btnSubmitChangeScheduleTime').on('click', function(e) {
        e.preventDefault();
        const $modal = $('#modalScheduleStoryDetail');
        const storyId = $modal.data('story-id');
        const newTime = $('#inputQuickChangeScheduleTime').val();

        if (!newTime) {
          muopAlert('Vui lòng chọn thời gian hẹn giờ mới!', 'warning');
          return;
        }

        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...');

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: {
            action: 'muop_schedule_story',
            nonce: muopConfig.nonce,
            story_id: storyId,
            scheduled_time: newTime
          },
          success: (res) => {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Lưu Giờ Hẹn Mới');
            if (typeof res === 'string') {
              try { res = JSON.parse(res); } catch (e) {}
            }
            if (res && res.success) {
              const data = res.data || {};
              $('#scheduleModalDatetimeText').text(data.scheduled_formatted || newTime);
              $('#scheduleChangeFormWrap').slideUp(200);
              $modal.data('scheduled-time', data.scheduled_time || newTime);
              self.startCountdown(data.scheduled_time || newTime);
              muopAlert('Đã cập nhật giờ hẹn thành công!', 'success', () => {
                window.location.reload();
              });
            } else {
              muopAlert(getAjaxMsg(res, 'Không thể cập nhật giờ hẹn!'), 'error');
            }
          },
          error: (xhr) => {
            $btn.prop('disabled', false).html('<i class="fa-solid fa-check"></i> Lưu Giờ Hẹn Mới');
            let err = 'Lỗi kết nối máy chủ!';
            if (xhr && xhr.responseJSON) err = getAjaxMsg(xhr.responseJSON, err);
            muopAlert(err, 'error');
          }
        });
      });

      // 6. Đăng ngay bây giờ từ Popup
      $('#btnPopupPublishScheduledNow').on('click', function(e) {
        e.preventDefault();
        const $modal = $('#modalScheduleStoryDetail');
        const storyId = $modal.data('story-id');
        if (!storyId) return;

        muopConfirm('Bạn có chắc chắn muốn phát hành bộ truyện này ngay lập tức thay vì chờ đến giờ hẹn?', () => {
          const $btn = $('#btnPopupPublishScheduledNow');
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
                muopAlert(getAjaxMsg(res, 'Đã phát hành truyện thành công!'), 'success', () => {
                  window.location.reload();
                });
              } else {
                $btn.prop('disabled', false).html('<i class="fa-solid fa-bolt"></i> Đăng Ngay');
                muopAlert(getAjaxMsg(res, 'Lỗi đăng truyện!'), 'error');
              }
            },
            error: (xhr) => {
              $btn.prop('disabled', false).html('<i class="fa-solid fa-bolt"></i> Đăng Ngay');
              let err = 'Lỗi kết nối!';
              if (xhr && xhr.responseJSON) err = getAjaxMsg(xhr.responseJSON, err);
              muopAlert(err, 'error');
            }
          });
        });
      });

      // 7. Hủy hẹn giờ (về Bản Nháp)
      $('#btnPopupCancelScheduleToDraft, .btn-cancel-schedule-action').on('click', function(e) {
        e.preventDefault();
        const storyId = $(this).data('story-id') || $('#modalScheduleStoryDetail').data('story-id');
        if (!storyId) return;

        muopConfirm('Bạn có chắc chắn muốn hủy lịch hẹn và chuyển bộ truyện này về Bản Nháp?', () => {
          $.ajax({
            url: muopConfig.ajaxUrl,
            type: 'POST',
            data: {
              action: 'muop_cancel_schedule',
              nonce: muopConfig.nonce,
              story_id: storyId
            },
            success: (res) => {
              if (typeof res === 'string') {
                try { res = JSON.parse(res); } catch (e) {}
              }
              if (res && res.success) {
                muopAlert(getAjaxMsg(res, 'Đã chuyển về bản nháp!'), 'success', () => {
                  window.location.reload();
                });
              } else {
                muopAlert(getAjaxMsg(res, 'Không thể hủy lịch hẹn!'), 'error');
              }
            },
            error: (xhr) => {
              let err = 'Lỗi kết nối!';
              if (xhr && xhr.responseJSON) err = getAjaxMsg(xhr.responseJSON, err);
              muopAlert(err, 'error');
            }
          });
        });
      });

      // 8. Tác vụ Hẹn giờ đăng cho bản nháp từ menu 3 chấm
      $(document).on('click', '.btn-action-schedule-draft', function(e) {
        e.preventDefault();
        $('.story-actions-dropdown').removeClass('is-open');
        const storyId = $(this).data('story-id');
        const storyTitle = $(this).data('story-title') || 'Bộ truyện';

        if (typeof window.muopOpenScheduleModal === 'function') {
          window.muopOpenScheduleModal(storyId, storyTitle, '', '');
          $('#scheduleChangeFormWrap').show();
          return;
        }

        const $modal = $('#modalScheduleStoryDetail');
        $modal.data('story-id', storyId);
        $modal.data('scheduled-time', '');
        $('#scheduleModalStoryTitle').text(storyTitle);
        $('#scheduleModalDatetimeText').text('Chưa lên lịch');
        $('#scheduleModalCountdownText').html('<i class="fa-solid fa-clock"></i> Hãy chọn thời gian bạn muốn đăng');
        if (typeof window.muopSetPopupQuickSchedule === 'function') {
          window.muopSetPopupQuickSchedule(3, null);
        }
        $('#scheduleChangeFormWrap').show();
        $modal.css('display', 'flex');
      });
    },

    startCountdown(schedTimeStr) {
      this.stopCountdown();
      if (!schedTimeStr) {
        $('#scheduleModalCountdownText').html('<i class="fa-solid fa-circle-info"></i> Chưa có thời gian cụ thể');
        return;
      }

      const update = () => {
        const targetDate = new Date(schedTimeStr.replace(/-/g, '/')).getTime();
        const now = new Date().getTime();
        const diff = targetDate - now;

        if (diff <= 0) {
          $('#scheduleModalCountdownText').html('<i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Đã đến giờ phát hành! Hệ thống đang kích hoạt xuất bản.');
          return;
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const secs = Math.floor((diff % (1000 * 60)) / 1000);

        let parts = [];
        if (days > 0) parts.push(days + ' ngày');
        if (hours > 0 || days > 0) parts.push(hours + ' giờ');
        parts.push(mins + ' phút');
        parts.push(secs + ' giây');

        $('#scheduleModalCountdownText').html('<i class="fa-solid fa-hourglass-half fa-spin"></i> Còn ' + parts.join(' '));
      };

      update();
      this.activeCountdownInterval = setInterval(update, 1000);
    },

    stopCountdown() {
      if (this.activeCountdownInterval) {
        clearInterval(this.activeCountdownInterval);
        this.activeCountdownInterval = null;
      }
    }
  };

  // 33. NOMINATED CAROUSEL MANAGER (TRUYỆN ĐỀ CỬ: KHUNG XANH MỜ PHÍA SAU, CĂN GIỮA & VÒNG LẶP VÔ TẬN NHƯ VÒNG TRÒN)
  const NominatedCarouselManager = {
    init() {
      const track = document.getElementById('nominatedStoryTrack');
      if (!track) return;

      const container = track.closest('.section-nominated-carousel') || track;
      const btnPrev = document.getElementById('btnNominatedPrev');
      const btnNext = document.getElementById('btnNominatedNext');
      const dotsContainer = document.getElementById('nominatedStoryDots');

      // Danh sách các thẻ truyện gốc
      const originalCards = Array.from(track.querySelectorAll('.story-card:not(.is-clone)'));
      const originalCount = originalCards.length;
      if (!originalCount) return;

      // Đánh số chỉ số thực (realIndex) cho từng truyện gốc (0..originalCount - 1)
      originalCards.forEach((card, idx) => {
        card.dataset.realIndex = idx;
      });

      // Tạo vòng lặp vô tận liền mạch như vòng tròn (Circular Infinite Loop):
      // Nhân bản 1 bộ phía trước (Set 0) và 1 bộ phía sau (Set 2) ôm trọn bộ gốc (Set 1)
      let allCards = [];
      if (originalCount >= 2) {
        const set0Clones = originalCards.map((card, idx) => {
          const clone = card.cloneNode(true);
          clone.dataset.realIndex = idx;
          clone.classList.add('is-clone');
          return clone;
        });

        const set2Clones = originalCards.map((card, idx) => {
          const clone = card.cloneNode(true);
          clone.dataset.realIndex = idx;
          clone.classList.add('is-clone');
          return clone;
        });

        // Chèn Set 0 vào trước Set 1
        set0Clones.forEach(clone => track.insertBefore(clone, originalCards[0]));
        // Nối Set 2 vào sau Set 1
        set2Clones.forEach(clone => track.appendChild(clone));

        allCards = Array.from(track.querySelectorAll('.story-card'));
      } else {
        allCards = originalCards;
      }

      let isDown = false;
      let startX = 0;
      let scrollLeft = 0;
      let isDragging = false;
      let autoTimer = null;
      let isUserInteracting = false;
      let isHoveringCard = false;
      let scrollThrottle = null;
      let isScrolling = false;
      let scrollResetTimeout = null;

      let cachedStep = 0;
      let cachedFirstCenter = 0;
      let lastActiveGlobalIdx = -1;

      // Tính toán vị trí và bước nhảy của card (tính 1 lần và khi resize để tối ưu cực độ hiệu năng)
      const refreshCardMetrics = () => {
        if (!allCards.length) return;
        const c0 = allCards[0];
        const c1 = allCards[1];
        if (c0 && c1) {
          cachedStep = c1.offsetLeft - c0.offsetLeft;
          cachedFirstCenter = c0.offsetLeft + (c0.offsetWidth / 2);
        } else if (c0) {
          cachedStep = c0.offsetWidth;
          cachedFirstCenter = c0.offsetLeft + (c0.offsetWidth / 2);
        }
      };

      // Khoảng cách cuộn trọn vẹn 1 vòng 360 độ (chiều rộng đúng 1 set truyện)
      const getSetDistance = () => {
        if (originalCount < 2 || allCards.length < 3 * originalCount) return 0;
        return allCards[2 * originalCount].offsetLeft - allCards[originalCount].offsetLeft;
      };

      // Cập nhật padding hai bên của track để truyện đầu tiên và truyện cuối cùng đều nằm chính giữa
      const updateTrackPadding = () => {
        if (!track || !allCards.length) return;
        const firstCard = allCards[0];
        const lastCard = allCards[allCards.length - 1];
        if (!firstCard) return;

        const halfTrack = track.clientWidth / 2;
        const halfFirst = firstCard.offsetWidth / 2;
        const halfLast = (lastCard ? lastCard.offsetWidth : firstCard.offsetWidth) / 2;

        const padLeft = Math.max(12, Math.round(halfTrack - halfFirst));
        const padRight = Math.max(12, Math.round(halfTrack - halfLast));

        track.style.paddingLeft = padLeft + 'px';
        track.style.paddingRight = padRight + 'px';
      };

      // Xác định card trong toàn bộ danh sách đang nằm gần tâm giữa màn hình nhất (O(1) toán học, 60fps mượt mà)
      const getActiveGlobalIndex = () => {
        if (!allCards.length) return 0;
        const trackCenter = track.scrollLeft + (track.clientWidth / 2);

        if (cachedStep > 0) {
          const estimatedIdx = Math.round((trackCenter - cachedFirstCenter) / cachedStep);
          return Math.max(0, Math.min(allCards.length - 1, estimatedIdx));
        }

        // Tự phục hồi tính toán nếu chưa có cache
        const c0 = allCards[0];
        if (c0) {
          const step = allCards[1] ? (allCards[1].offsetLeft - c0.offsetLeft) : c0.offsetWidth;
          if (step > 0) {
            cachedStep = step;
            cachedFirstCenter = c0.offsetLeft + (c0.offsetWidth / 2);
            const estimatedIdx = Math.round((trackCenter - cachedFirstCenter) / cachedStep);
            return Math.max(0, Math.min(allCards.length - 1, estimatedIdx));
          }
        }
        return 0;
      };

      // Chuẩn hóa vị trí vòng lặp về lại Set 1 (giữa) mượt mà không hề giật
      const normalizeLoopPosition = () => {
        if (originalCount < 2) return;
        const setDist = getSetDistance();
        if (setDist <= 0) return;

        const currentIdx = getActiveGlobalIndex();

        // Nếu đã trôi sang Set 2 (phía sau): dịch ngược lại về Set 1
        if (currentIdx >= 2 * originalCount) {
          const normalizedIdx = currentIdx - originalCount;
          const targetCard = allCards[normalizedIdx];
          if (targetCard) {
            const targetLeft = targetCard.offsetLeft + (targetCard.offsetWidth / 2) - (track.clientWidth / 2);
            track.classList.add('is-animating');
            track.scrollLeft = targetLeft;
            track.classList.remove('is-animating');
            lastActiveGlobalIdx = -1;
            updateActiveCard();
          }
        }
        // Nếu đã trôi ngược về Set 0 (phía trước): dịch tiến tới về Set 1
        else if (currentIdx < originalCount) {
          const normalizedIdx = currentIdx + originalCount;
          const targetCard = allCards[normalizedIdx];
          if (targetCard) {
            const targetLeft = targetCard.offsetLeft + (targetCard.offsetWidth / 2) - (track.clientWidth / 2);
            track.classList.add('is-animating');
            track.scrollLeft = targetLeft;
            track.classList.remove('is-animating');
            lastActiveGlobalIdx = -1;
            updateActiveCard();
          }
        }
      };

      // Cuộn để đưa truyện chỉ định nằm ngay chính giữa màn hình
      const scrollToCard = (idx, behavior = 'smooth') => {
        if (!allCards.length) return;
        if (idx < 0) idx = 0;
        if (idx >= allCards.length) idx = allCards.length - 1;

        const targetCard = allCards[idx];
        if (!targetCard) return;

        const targetScrollLeft = targetCard.offsetLeft + (targetCard.offsetWidth / 2) - (track.clientWidth / 2);

        if (behavior === 'smooth') {
          isScrolling = true;
          track.classList.add('is-animating');
          track.scrollTo({ left: targetScrollLeft, behavior: 'smooth' });

          if (scrollResetTimeout) clearTimeout(scrollResetTimeout);
          scrollResetTimeout = setTimeout(() => {
            track.classList.remove('is-animating');
            isScrolling = false;
            normalizeLoopPosition();
          }, 460);
        } else {
          track.classList.add('is-animating');
          track.scrollLeft = targetScrollLeft;
          track.classList.remove('is-animating');
          normalizeLoopPosition();
        }
      };

      // Tạo các dấu chấm màu xanh tương ứng với các truyện gốc
      const renderDots = () => {
        if (!dotsContainer) return;
        dotsContainer.innerHTML = '';
        originalCards.forEach((card, realIdx) => {
          const dot = document.createElement('button');
          dot.type = 'button';
          dot.className = 'story-carousel-dot' + (realIdx === 0 ? ' is-active' : '');
          dot.setAttribute('aria-label', 'Xem truyện thứ ' + (realIdx + 1));
          dot.dataset.realIndex = realIdx;

          dot.addEventListener('click', (e) => {
            e.preventDefault();
            const currGlobalIdx = getActiveGlobalIndex();
            const currRealIdx = parseInt(allCards[currGlobalIdx].dataset.realIndex, 10) || 0;

            // Tính bước nhảy ngắn nhất theo vòng tròn
            let diff = realIdx - currRealIdx;
            if (diff > originalCount / 2) diff -= originalCount;
            if (diff < -originalCount / 2) diff += originalCount;

            const targetGlobalIdx = currGlobalIdx + diff;
            scrollToCard(targetGlobalIdx, 'smooth');
            pauseThenResume(4500);
          });

          dotsContainer.appendChild(dot);
        });
      };

      // Cập nhật card & dấu chấm đang được kéo đến nằm ở giữa (active: to lên + khung xanh mờ phía sau)
      const updateActiveCard = () => {
        if (isHoveringCard) return;

        const activeGlobalIdx = getActiveGlobalIndex();
        if (activeGlobalIdx === lastActiveGlobalIdx) return;
        lastActiveGlobalIdx = activeGlobalIdx;

        const activeCard = allCards[activeGlobalIdx];
        if (!activeCard) return;

        const activeRealIdx = parseInt(activeCard.dataset.realIndex, 10) || 0;

        for (let i = 0; i < allCards.length; i++) {
          if (i === activeGlobalIdx) {
            allCards[i].classList.add('is-active');
          } else {
            allCards[i].classList.remove('is-active');
          }
        }

        if (dotsContainer) {
          const dots = dotsContainer.querySelectorAll('.story-carousel-dot');
          dots.forEach((dot) => {
            const dotRealIdx = parseInt(dot.dataset.realIndex, 10);
            if (dotRealIdx === activeRealIdx) {
              dot.classList.add('is-active');
            } else {
              dot.classList.remove('is-active');
            }
          });
        }
      };

      // Tự động chuyển động tiến tới theo 1 vòng lặp vô tận như vòng tròn
      const stepAutoScroll = () => {
        if (isUserInteracting || isDown || isDragging || isScrolling) return;

        const currentIdx = getActiveGlobalIndex();
        scrollToCard(currentIdx + 1, 'smooth');
      };

      const startAutoPlay = () => {
        stopAutoPlay();
        autoTimer = setInterval(stepAutoScroll, 3200);
      };

      const stopAutoPlay = () => {
        if (autoTimer) {
          clearInterval(autoTimer);
          autoTimer = null;
        }
      };

      const pauseThenResume = (delay = 4000) => {
        stopAutoPlay();
        setTimeout(() => {
          if (!isUserInteracting && !isDown) {
            startAutoPlay();
          }
        }, delay);
      };

      // Lắng nghe sự kiện scroll trên track để cập nhật card phóng to liên tục thời gian thực
      track.addEventListener('scroll', () => {
        if (scrollThrottle) return;
        scrollThrottle = requestAnimationFrame(() => {
          updateActiveCard();
          scrollThrottle = null;
        });
      }, { passive: true });

      // Hover trên desktop: phóng to ngay truyện được rê chuột vào
      allCards.forEach((card, idx) => {
        card.addEventListener('mouseenter', () => {
          isHoveringCard = true;
          lastActiveGlobalIdx = idx;
          allCards.forEach(c => c.classList.remove('is-active'));
          card.classList.add('is-active');
        });

        card.addEventListener('mouseleave', () => {
          isHoveringCard = false;
          lastActiveGlobalIdx = -1;
          updateActiveCard();
        });
      });

      // Kéo chuột thủ công trên PC
      track.addEventListener('mousedown', (e) => {
        isDown = true;
        isDragging = false;
        startX = e.pageX - track.offsetLeft;
        scrollLeft = track.scrollLeft;
        stopAutoPlay();
      });

      window.addEventListener('mouseup', () => {
        if (!isDown) return;
        isDown = false;
        setTimeout(() => {
          isDragging = false;
          updateActiveCard();
          normalizeLoopPosition();
          if (!isUserInteracting) startAutoPlay();
        }, 120);
      });

      track.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        const x = e.pageX - track.offsetLeft;
        const walk = (x - startX) * 1.5;
        if (Math.abs(walk) > 6) {
          isDragging = true;
          track.scrollLeft = scrollLeft - walk;
        }
      });

      // Ngăn chặn chuyển hướng trang khi người dùng đang kéo rê chuột
      track.addEventListener('click', (e) => {
        if (isDragging) {
          e.preventDefault();
          e.stopPropagation();
        }
      }, true);

      // Tạm dừng khi rê chuột vào khung truyện đề cử
      container.addEventListener('mouseenter', () => {
        isUserInteracting = true;
        stopAutoPlay();
      });

      container.addEventListener('mouseleave', () => {
        isUserInteracting = false;
        if (!isDown) startAutoPlay();
      });

      // Tạm dừng khi chạm vuốt trên màn hình cảm ứng di động
      track.addEventListener('touchstart', () => {
        isUserInteracting = true;
        stopAutoPlay();
      }, { passive: true });

      track.addEventListener('touchend', () => {
        isUserInteracting = false;
        setTimeout(() => {
          updateActiveCard();
          normalizeLoopPosition();
        }, 150);
        pauseThenResume(3500);
      }, { passive: true });

      // Nút Prev (<) & Next (>) theo vòng lặp tròn
      if (btnPrev) {
        btnPrev.addEventListener('click', (e) => {
          e.preventDefault();
          const currentIdx = getActiveGlobalIndex();
          scrollToCard(currentIdx - 1, 'smooth');
          pauseThenResume(4500);
        });
      }

      if (btnNext) {
        btnNext.addEventListener('click', (e) => {
          e.preventDefault();
          const currentIdx = getActiveGlobalIndex();
          scrollToCard(currentIdx + 1, 'smooth');
          pauseThenResume(4500);
        });
      }

      // Tự động tạm dừng khi chuyển sang tab khác để tiết kiệm pin & CPU
      document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
          stopAutoPlay();
        } else if (!isUserInteracting) {
          startAutoPlay();
        }
      });

      // Cập nhật padding và kích thước card để căn giữa và định vị lại khi đổi kích thước màn hình
      refreshCardMetrics();
      updateTrackPadding();
      window.addEventListener('resize', () => {
        refreshCardMetrics();
        updateTrackPadding();
        const activeIdx = getActiveGlobalIndex();
        scrollToCard(activeIdx, 'auto');
      });

      // Khởi tạo các dấu chấm màu xanh
      renderDots();

      // Bắt đầu ngay tại truyện đầu tiên của Set 1 (giữa)
      const initialIndex = (originalCount >= 2) ? originalCount : 0;
      scrollToCard(initialIndex, 'auto');
      updateActiveCard();

      // Bắt đầu tự động chuyển động sau 2.5s khi trang tải xong
      setTimeout(startAutoPlay, 2500);
    }
  };

  // INITIALIZE ALL
  $(document).ready(() => {
    try {
      if (typeof localStorage !== 'undefined') {
        localStorage.removeItem('muop_accent_color');
      }
    } catch(e) {}

    const safeInit = (name, fn) => {
      try {
        if (typeof fn === 'function') fn();
      } catch (err) {
        console.warn('[MuopDang] Warning initializing ' + name + ':', err);
      }
    };

    // 1. Giao diện cơ bản & Điều hướng menu (Bắt buộc chạy đầu tiên, không bao giờ bị lỗi chặn)
    safeInit('ThemeManager', () => ThemeManager.init());
    safeInit('DropdownManager', () => DropdownManager.init());
    safeInit('MobileNavManager', () => MobileNavManager.init());
    safeInit('PasswordToggle', () => PasswordToggle.init());
    safeInit('NotificationManager', () => NotificationManager.init());
    safeInit('TabManager', () => TabManager.init());

    // 2. Các chức năng bổ trợ khác
    safeInit('AffiliateManager', () => AffiliateManager.init());
    safeInit('TikTokJumpManager', () => TikTokJumpManager.init());
    safeInit('AntiCopyManager', () => AntiCopyManager.init());
    safeInit('ReadingManager', () => ReadingManager.init());
    safeInit('AuthForms', () => AuthForms.init());
    safeInit('CreatorForms', () => CreatorForms.init());
    safeInit('StoryChapterEditor', () => StoryChapterEditor.init());
    safeInit('CommentManager', () => CommentManager.init());
    safeInit('AdminManager', () => AdminManager.init());
    safeInit('ProfileManager', () => ProfileManager.init());
    safeInit('StoryStatusManager', () => StoryStatusManager.init());
    safeInit('StorySummaryManager', () => StorySummaryManager.init());
    safeInit('TeamManager', () => TeamManager.init());
    safeInit('ChapterReportManager', () => ChapterReportManager.init());
    safeInit('ReportDialogueManager', () => ReportDialogueManager.init());
    safeInit('TranslatorBankManager', () => TranslatorBankManager.init());
    safeInit('AdminExtraManager', () => AdminExtraManager.init());
    safeInit('SensitiveWordModerator', () => SensitiveWordModerator.init());
    safeInit('ImageOptimizerManager', () => ImageOptimizerManager.init());
    safeInit('SpotlightCarouselManager', () => SpotlightCarouselManager.init());
    safeInit('ScheduleStoryManager', () => ScheduleStoryManager.init());
    safeInit('NominatedCarouselManager', () => NominatedCarouselManager.init());
  });

})(jQuery);
