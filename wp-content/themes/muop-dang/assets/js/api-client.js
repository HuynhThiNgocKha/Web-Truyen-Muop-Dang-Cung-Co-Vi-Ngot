/**
 * MuopApi - Client-Side API Architecture
 * Connects frontend client to backend server via REST API and AJAX
 */
(function(window, $) {
    'use strict';

    const config = window.muopConfig || {
        ajaxUrl: '/wp-admin/admin-ajax.php',
        restUrl: '/wp-json/muop/v1/',
        nonce: ''
    };

    const MuopApi = {
        /**
         * Generic HTTP Request helper
         */
        request: function(endpoint, method, data) {
            method = method || 'GET';
            data = data || {};

            // Prefer REST API if available
            const url = config.restUrl ? (config.restUrl + endpoint.replace(/^\//, '')) : config.ajaxUrl;

            return $.ajax({
                url: url,
                type: method,
                data: (method === 'GET') ? data : JSON.stringify(data),
                contentType: (method === 'GET') ? 'application/x-www-form-urlencoded; charset=UTF-8' : 'application/json; charset=UTF-8',
                dataType: 'json',
                beforeSend: function(xhr) {
                    if (config.nonce) {
                        xhr.setRequestHeader('X-WP-Nonce', config.nonce);
                    }
                }
            });
        },

        /**
         * AJAX Fallback Request helper
         */
        ajax: function(action, data) {
            data = data || {};
            data.action = action;
            data.nonce = config.nonce;

            return $.ajax({
                url: config.ajaxUrl,
                type: 'POST',
                data: data,
                dataType: 'json'
            });
        },

        /**
         * Story Endpoints
         */
        stories: {
            list: function(params) {
                return MuopApi.request('/stories', 'GET', params);
            },
            create: function(storyData) {
                return MuopApi.ajax('muop_save_story', storyData);
            },
            approve: function(storyId) {
                return MuopApi.ajax('muop_admin_action', {
                    sub_action: 'approve_story',
                    story_id: storyId
                });
            },
            nominate: function(storyId, nominateType) {
                return MuopApi.ajax('muop_admin_action', {
                    sub_action: 'nominate_story',
                    story_id: storyId,
                    nominate_type: nominateType
                });
            },
            delete: function(storyId) {
                return MuopApi.ajax('muop_admin_action', {
                    sub_action: 'delete_story',
                    story_id: storyId
                });
            }
        },

        /**
         * Chapter Endpoints
         */
        chapters: {
            create: function(chapterData) {
                return MuopApi.ajax('muop_save_chapter', chapterData);
            }
        },

        /**
         * Admin Endpoints
         */
        admin: {
            getStats: function() {
                return MuopApi.request('/admin/stats', 'GET');
            },
            switchMode: function(mode) {
                return MuopApi.ajax('muop_switch_admin_mode', { mode: mode });
            },
            toggleUser: function(userId) {
                return MuopApi.ajax('muop_admin_action', {
                    sub_action: 'toggle_user_status',
                    user_id: userId
                });
            }
        },

        /**
         * Affiliate Endpoints
         */
        affiliate: {
            track: function(platform, chapterId) {
                return MuopApi.ajax('muop_track_affiliate_click', {
                    platform: platform,
                    chapter_id: chapterId || 0
                });
            },
            saveSettings: function(settings) {
                return MuopApi.ajax('muop_update_affiliate_links', settings);
            }
        }
    };

    // Expose globally
    window.MuopApi = MuopApi;

})(window, jQuery);
