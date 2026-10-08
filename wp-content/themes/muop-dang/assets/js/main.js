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

  // 5. READING CONTROLS (FONT SIZE, HISTORY, BOOKMARK)
  const ReadingManager = {
    init() {
      let currentSize = parseInt(localStorage.getItem('muop_reading_font_size'), 10) || 18;
      this.applyFontSize(currentSize);

      $('#btnFontInc').on('click', () => {
        if (currentSize < 28) {
          currentSize += 2;
          this.applyFontSize(currentSize);
        }
      });

      $('#btnFontDec').on('click', () => {
        if (currentSize > 14) {
          currentSize -= 2;
          this.applyFontSize(currentSize);
        }
      });

      // Chapter change dropdown
      $('#readingChapterSelect').on('change', function() {
        const url = $(this).val();
        if (url) window.location.href = url;
      });

      // Record reading history & view count
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

      // Bookmark / Tủ truyện Toggle
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
            if (res.success) {
              if (res.data.bookmarked) {
                $btn.html('<i class="fa-solid fa-bookmark"></i> Đã Lưu Tủ Truyện');
                $btn.addClass('btn-primary').removeClass('btn-secondary');
              } else {
                $btn.html('<i class="fa-regular fa-bookmark"></i> Lưu Vào Tủ Truyện');
                $btn.removeClass('btn-primary').addClass('btn-secondary');
              }
            } else {
              alert(res.data.message || 'Lỗi lưu truyện!');
            }
          }
        });
      });
    },

    applyFontSize(size) {
      $('.chapter-body-text').css('font-size', size + 'px');
      localStorage.setItem('muop_reading_font_size', size);
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

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: $form.serialize() + '&action=muop_login&nonce=' + muopConfig.nonce,
          success: (res) => {
            if (res.success) {
              $alert.removeClass('alert-error').addClass('alert-success').text(res.data.message).fadeIn();
              setTimeout(() => {
                window.location.href = res.data.redirect || muopConfig.siteUrl;
              }, 600);
            } else {
              $btn.prop('disabled', false).text('Đăng Nhập');
              $alert.removeClass('alert-success').addClass('alert-error').text(res.data.message).fadeIn();
            }
          },
          error: () => {
            $btn.prop('disabled', false).text('Đăng Nhập');
            $alert.removeClass('alert-success').addClass('alert-error').text('Có lỗi xảy ra, vui lòng thử lại!').fadeIn();
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

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: $form.serialize() + '&action=muop_register&nonce=' + muopConfig.nonce,
          success: (res) => {
            if (res.success) {
              $alert.removeClass('alert-error').addClass('alert-success').text(res.data.message).fadeIn();
              setTimeout(() => {
                window.location.href = res.data.redirect || muopConfig.siteUrl;
              }, 800);
            } else {
              $btn.prop('disabled', false).text('Đăng Ký Độc Giả');
              $alert.removeClass('alert-success').addClass('alert-error').text(res.data.message).fadeIn();
            }
          },
          error: () => {
            $btn.prop('disabled', false).text('Đăng Ký Độc Giả');
            $alert.removeClass('alert-success').addClass('alert-error').text('Lỗi kết nối máy chủ!').fadeIn();
          }
        });
      });
    }
  };

  // 7. STORY & CHAPTER SUBMISSION (TRANSLATORS / ADMIN)
  const CreatorForms = {
    init() {
      // Submit Story
      $('#formSubmitStory').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $alert = $('#storyAlert');
        const $btn = $form.find('button[type="submit"]');

        $btn.prop('disabled', true).text('Đang gửi truyện...');
        $alert.hide();

        const formData = new FormData(this);
        formData.append('action', 'muop_submit_story');
        formData.append('nonce', muopConfig.nonce);

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: (res) => {
            if (res.success) {
              $alert.removeClass('alert-error').addClass('alert-success').text(res.data.message).fadeIn();
              setTimeout(() => {
                window.location.href = res.data.redirect || muopConfig.siteUrl;
              }, 1200);
            } else {
              $btn.prop('disabled', false).text('Đăng Truyện');
              $alert.removeClass('alert-success').addClass('alert-error').text(res.data.message).fadeIn();
            }
          },
          error: () => {
            $btn.prop('disabled', false).text('Đăng Truyện');
            $alert.removeClass('alert-success').addClass('alert-error').text('Lỗi upload hoặc hệ thống!').fadeIn();
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

        $.ajax({
          url: muopConfig.ajaxUrl,
          type: 'POST',
          data: $form.serialize() + '&action=muop_submit_chapter&nonce=' + muopConfig.nonce,
          success: (res) => {
            if (res.success) {
              $alert.removeClass('alert-error').addClass('alert-success').text(res.data.message).fadeIn();
              setTimeout(() => {
                window.location.href = res.data.redirect || muopConfig.siteUrl;
              }, 1000);
            } else {
              $btn.prop('disabled', false).text('Thêm Chương Mới');
              $alert.removeClass('alert-success').addClass('alert-error').text(res.data.message).fadeIn();
            }
          },
          error: () => {
            $btn.prop('disabled', false).text('Thêm Chương Mới');
            $alert.removeClass('alert-success').addClass('alert-error').text('Lỗi kết nối máy chủ!').fadeIn();
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
            if (res.success) {
              const html = `
                <li class="comment-item">
                  <div class="comment-avatar">${res.data.author.charAt(0).toUpperCase()}</div>
                  <div class="comment-body">
                    <span class="comment-author-name">${res.data.author}</span>
                    <span class="comment-time">${res.data.time}</span>
                    <div class="comment-text">${res.data.content}</div>
                  </div>
                </li>
              `;
              $('.comment-list-ul').prepend(html);
              $textarea.val('');
            } else {
              alert(res.data.message || 'Lỗi gửi bình luận!');
            }
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
            if (res.success) {
              alert(res.data.message);
              window.location.reload();
            } else {
              alert(res.data.message || 'Lỗi thao tác!');
            }
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
            if (res.success) {
              alert(res.data.message);
              $btn.closest('tr').fadeOut(300, function() {
                $(this).remove();
              });
            } else {
              alert(res.data.message || 'Lỗi xóa truyện!');
              $btn.prop('disabled', false);
            }
          },
          error: () => {
            alert('Lỗi kết nối máy chủ!');
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
            if (res.success) {
              $alert.removeClass('alert-error').addClass('alert-success').text(res.data.message).fadeIn().delay(2000).fadeOut();
            } else {
              $alert.removeClass('alert-success').addClass('alert-error').text(res.data.message).fadeIn();
            }
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

            if (res.success) {
              $alertContainer.html(`
                <div class="alert-box alert-success" style="display:block; margin-bottom: 20px;">
                  <i class="fa-solid fa-circle-check"></i> ${res.data.message || 'Cập nhật thông tin thành công!'}
                </div>
              `);

              // Update profile header elements
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

              // Scroll smoothly to top of card
              if ($('.profile-header-card').length) {
                $('html, body').animate({
                  scrollTop: $('.profile-header-card').offset().top - 80
                }, 300);
              }
            } else {
              $alertContainer.html(`
                <div class="alert-box alert-error" style="display:block; margin-bottom: 20px;">
                  <i class="fa-solid fa-circle-exclamation"></i> ${res.data.message || 'Lỗi cập nhật thông tin!'}
                </div>
              `);
            }
          },
          error: () => {
            $btnSubmit.prop('disabled', false);
            $spinner.hide();
            $alertContainer.html(`
              <div class="alert-box alert-error" style="display:block; margin-bottom: 20px;">
                <i class="fa-solid fa-circle-exclamation"></i> Lỗi kết nối máy chủ hoặc tệp ảnh quá lớn. Vui lòng thử lại!
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

            if (res.success) {
              $alertContainer.html(`
                <div class="alert-box alert-success" style="display:block; margin-bottom: 20px;">
                  <i class="fa-solid fa-circle-check"></i> ${res.data.message || 'Đổi mật khẩu thành công!'}
                </div>
              `);
              $form[0].reset();
            } else {
              $alertContainer.html(`
                <div class="alert-box alert-error" style="display:block; margin-bottom: 20px;">
                  <i class="fa-solid fa-circle-exclamation"></i> ${res.data.message || 'Lỗi đổi mật khẩu!'}
                </div>
              `);
            }
          },
          error: () => {
            $btnSubmit.prop('disabled', false);
            $spinner.hide();
            $alertContainer.html(`
              <div class="alert-box alert-error" style="display:block; margin-bottom: 20px;">
                <i class="fa-solid fa-circle-exclamation"></i> Lỗi kết nối máy chủ! Vui lòng thử lại sau.
              </div>
            `);
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
  });

})(jQuery);
