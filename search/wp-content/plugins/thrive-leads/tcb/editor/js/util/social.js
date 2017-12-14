var TVE_Content_Builder = TVE_Content_Builder || {};

(function ($) {

    /**
     * the tve_social_editor jquery plugin wrapped over the .edit_mode element
     */
    var $lightbox;

    /**
     * empty (void function)
     * @private
     */
    var _noop = function () {
    };

    /**
     *
     * @param loadedCallback
     * @param subset array|string optional subset of networks to check for
     */
    function scriptLoader(loadedCallback, subset) {

        $.ajaxSetup({
            cache: true
        });

        if (typeof subset === 'undefined') {
            subset = ['fb', 'google', 'twitter', 'linkedin', 'pinterest', 'xing'];
        } else if (typeof subset === 'string') {
            subset = [subset];
        }

        var subsetLoaded = {
            fb: function () {
                return window.FB
            },
            google: function () {
                return window.gapi
            },
            twitter: function () {
                return window.twttr
            },
            linkedin: function () {
                return window.IN && window.IN.parse
            },
            pinterest: function () {
                return window.parsePins
            },
            xing: function () {
                return true
            }
        };

        function checkLoaded() {
            var loaded = true;
            $.each(subset, function (i, handle) {
                if (!subsetLoaded[handle]()) {
                    setTimeout(checkLoaded, 100);
                    loaded = false;
                    return false;
                }
            });

            loaded && loadedCallback.call();
        }

        var loaders = {
            fb: function () {
                if (!window.FB) {
                    $.getScript('//connect.facebook.com/en_US/sdk.js', function () {
                        FB.init({
                            appId: '', //tve_path_params.social_fb_app_id,
                            xfbml: false,
                            version: 'v2.3'
                        });
                    });
                }
            },
            google: function () {
                if (!window.gapi) {
                    window.___gcfg = {
                        parsetags: 'explicit'
                    };
                    $.getScript('//apis.google.com/js/platform.js');
                }
            },
            twitter: function () {
                if (!window.twttr) {
                    $.getScript('https://platform.twitter.com/widgets.js');
                }
            },
            linkedin: function () {
                if (!window.IN) {
                    $.getScript('https://platform.linkedin.com/in.js');
                }
            },
            pinterest: function () {
                (function () {
                    window.PinIt = window.PinIt || {loaded: false};
                    if (window.PinIt.loaded) {
                        return;
                    }
                    window.PinIt.loaded = true;

                    var s = document.createElement("script");
                    s.type = "text/javascript";
                    s.async = true;
                    s.src = "https://assets.pinterest.com/js/pinit.js";
                    s["data-pin-build"] = "parsePins";

                    var x = document.getElementsByTagName("script")[0];

                    x.parentNode.insertBefore(s, x);
                })();
            },
            xing: function () {
                $.getScript('https://www.xing-share.com/plugins/share.js');
            }
        };

        $.each(subset, function (i, fn) {
            loaders[fn].call();
        });

        checkLoaded();
    }

    function domParse($element) {
        FB.XFBML.parse($element.find('.tve_s_fb_share')[0]);
        FB.XFBML.parse($element.find('.tve_s_fb_like')[0]);
        gapi.plus.go($element.find('.tve_s_g_share')[0]);
        gapi.plusone.go($element.find('.tve_s_g_plus')[0]);
        setTimeout(function () { /* twitter widgets being rendered thrice */
            twttr.widgets.load($element.find('.tve_s_t_share')[0]);
            twttr.widgets.load($element.find('.tve_s_t_follow')[0]);
        });

        IN.parse($element.find('.tve_s_in_share')[0]);
        try {
            window.parsePins($element.find(".tve_s_pin_share")[0]);
        } catch (error) {
            console.log(error);
        }
    }

    /**
     * facebook will always be needed on the editor page
     */
    scriptLoader(_noop, 'fb');

    TVE_Content_Builder.social = {
        /**
         * triggered both for default style and custom style
         * @param $menu
         */
        onMenuShow: function ($menu, type) {
            var config = TVE_Content_Builder.$social.getConfig(),
                $element = TVE_Content_Builder.$social.$el;

            if (config.global && config.global.counts) {
                $menu.find('#tve-share-show-counts').prop('checked', true);
                if (config.global.min_shares) {
                    $menu.find('#tve-share-min-shares').val(config.global.min_shares);
                }
            }

            if (type == 'default') {
                tve_set_selected_dropdown($menu.find('li#tve_social_' + (config.btn_type || 'none')));
            }

            if (type == 'custom') {
                var _temp_value = tve_handle_integer_or_float($element.find('.tve_social_items').css('font-size'));
                $menu.find('.tve_font_size').val(_temp_value);
                tve_set_selected_dropdown($menu.find('li#tve_social_' + TVE_Content_Builder.$social.getSize()));
                tve_set_selected_dropdown($menu.find('li#' + TVE_Content_Builder.$social.getType()));
                tve_set_selected_dropdown($menu.find('li#' + TVE_Content_Builder.$social.getStyle()));
            }
        },
        syncValue: function ($input, $element, event) {
            var _selector = $input.data('update-selector'),
                $mirror = $(_selector);
            if (!_selector || !$mirror.length) {
                return;
            }
            $mirror.val($input.val());
        },
        /**
         * called when a default social sharing element is inserted in the page
         * @param $element
         */
        onDefaultInsert: function ($element) {
            TVE_Editor_Page.overlay();
            scriptLoader(function () {
                setTimeout(function () {
                    domParse($element.find('.tve_social_items'));
                });
                TVE_Editor_Page.overlay(true);
            });
        },

        onDefaultDrop: function ($element) {
            $element.tve_social_default().render();
        },

        /**
         * open the lightbox for the social sharing options
         * triggered both for the default and custom style
         *
         *
         * @param $btn
         * @param $element
         */
        openOptions: function ($btn, $element) {
            $lightbox = $('#tve_lightbox_content');
            $lightbox.parent().find('.tve_lightbox_buttons').hide();
            TVE_Editor_Page.overlay();

            var self = this,
	            post_data = {
		            social_fb_app_id: tve_path_params.social_fb_app_id,
		            ajax_load: 'lb_social',
		            action: 'tve_ajax_load',
		            config: TVE_Content_Builder.$social.getConfig(),
		            element: $element.attr('class')
	            };
	        if ( tve_path_params.custom_post_data ) {
		        $.each( tve_path_params.custom_post_data, function ( k, v ) {
			        post_data[k] = v;
		        } );
	        }

            $.ajax({
                type: 'post',
                xhrFields: {
                    withCredentials: true
                },
                url: tve_path_params.ajax_url,
                data: post_data
            }).done(function (response) {
                load_lightbox_content(response, false);
                show_lightbox();
                TVE_Editor_Page.overlay(true);
                $lightbox.find('.tve_scT li:visible').first().click();
                $lightbox.find('#ts-url').trigger('keyup');
                self.positionTabs();
            });
        },
        add_class: function ($btn, $element) {
            TVE_Content_Builder.$socialElement = $element.find('.tve_social_custom');
            var new_class = $btn.attr('data-cls') ? $btn.attr('data-cls') : $btn.attr('id');
            if ($btn.attr('data-layout')) {
                TVE_Content_Builder.$socialElement.removeClass('tve_social_ib tve_social_itb tve_social_cb');
            } else { // button style
                TVE_Content_Builder.$socialElement.removeClass('tve_style_1 tve_style_2 tve_style_3 tve_style_4 tve_style_5');
            }
            TVE_Content_Builder.$socialElement.addClass(new_class);
            if ($btn.attr('data-size')) {
                var _temp_value = tve_handle_integer_or_float($element.find('.tve_social_items').css('font-size'));
                $btn.parents().find('.tve_font_size').val(_temp_value);
            }
            tve_set_selected_dropdown($btn);
        },
        /**
         * save the options chosen in the lightbox for the social options button
         * triggered both for default and custom style
         */
        save: function () {
            var config = this.readLightboxConfig(),
                errors = TVE_Content_Builder.$social.getValidationErrors(config);

            this.showErrors(errors);

            if (errors.length) {
                return;
            }

            TVE_Content_Builder.$social.render(config);
            TVE_Content_Builder.controls.lb_close();
        },
        showErrors: function (errors) {
            var $li = $lightbox.find('.tve_scT > ul > li').removeClass('tve_tab_err'),
                $inputs = $lightbox.find('input,select,textarea').removeClass('tve_input_err');

            $inputs.each(function () {
                $(this).next('span.tve_span_err').remove();
            });

            if (errors.length) {
                $.each(errors, function (i, error) {
                    if (i === 0) {
                        $li.filter('.tve_tab_' + error.tab).click();
                    }
                    $li.filter('.tve_tab_' + error.tab).addClass('tve_tab_err');
                    var $input = $lightbox.find('.tve_tc_' + error.tab + ' [name="' + error.field + '"]').addClass('tve_input_err').after('<span class="tve_span_err">' + error.message + '</span>');
                    if (i === 0) {
                        $input.first().focus();
                    }
                });
            }
        },
        /**
         * read all the configuration values from the lightbox
         */
        readLightboxConfig: function () {
            var config = TVE_Content_Builder.$social.getConfig();
            config.selected = this.getSelected();

            if (config.order && config.order.length) {

                config.selected.sort(function (a, b) {

                    var indexA = config.order.indexOf(a);
                    var indexB = config.order.indexOf(b);

                    if (indexA < indexB || indexA === -1 || indexB === -1) {
                        return -1;
                    }

                    return indexA == indexB ? 0 : 1;
                });
            }

            /**
             * read in each input element and store the values in the config object
             */
            $lightbox.find('.social-config-text').each(function () {
                var $this = $(this),
                    $root = $this.parents('.tve_social_config_container'),
                    network = $root.attr('data-config');
                if (!config[network]) {
                    config[network] = {};
                }

                if ($this.is('input[type="checkbox"]')) {
                    if ($this.is(':checked')) {
                        config[network][$this.attr('name')] = $this.val();
                    } else {
                        delete config[network][$this.attr('name')];
                    }
                    return;
                }

                config[network][$this.attr('name')] = $this.val();
                if (!config[network][$this.attr('name')] && $this.attr('data-value-if-empty')) {
                    config[network][$this.attr('name')] = $this.attr('data-value-if-empty');
                }
            });

            /**
             * we need to make sure we have a share-able link at all times, including the blog index pages, where the URL of the page would not be the correct one,
             * we need the url of the current post / page
             */
            $.each(config.selected, function (i, network) {
                if (!config[network].href) {
                    config[network].href = '{tcb_post_url}';
                } else {
                    config[network].href = TVE_Content_Builder.link_compliant(config[network].href);
                }
            });

            return config;
        },
        /**
         * get the selected networks
         *
         * @returns {Array}
         */
        getSelected: function () {
            var enabled = [];

            $lightbox.find('.config-enabled:checked').each(function () {
                enabled.push(this.value);
            });
            return enabled;
        },
        /**
         * control both the checkbox "Show share numbers" and the number if minimum shares
         * @param $input
         */
        shareCount: function ($input) {
            var config = TVE_Content_Builder.$social.getConfig();
            if ($input.is('input[type=checkbox]')) {
                /**
                 * if only Twitter is selected, we need to display a warning that the total share counts will not be displayed
                 */
                if ($input.is(':checked') && config.type === 'custom' && $.isArray(config.selected) && config.selected.length === 1 && config.selected[0] === 't_share') {
                    tve_add_notification(tve_path_params.translations.TwitterShareCountDisabled, true, 8000);
                    $input.prop('checked', false);
                    return;
                }
                var $cnt = $('#tve-share-min-shares');
                $cnt.prop('disabled', !$input.is(':checked'))
                config.global = config.global || {};
                config.global.counts = $input.is(':checked') ? 1 : 0;
            } else {
                var _value = parseInt($input.val());
                if (isNaN(_value) || _value < 0) {
                    _value = 0;
                }
                $input.val(_value);
                config.global = config.global || {};
                config.global.min_shares = _value;
            }
            TVE_Content_Builder.$social.setConfig(config);
            TVE_Content_Builder.$social.totalShareCount();
        },
        /**
         * set the default button style (button / button + count for now)
         *
         * @param $btn
         */
        defaultButtonType: function ($btn) {
            var config = TVE_Content_Builder.$social.getConfig();
            config.btn_type = $btn.attr('data-type');
            TVE_Content_Builder.$social.render(config);

            tve_set_selected_dropdown($btn);
        },
        configTabClick: function ($li) {
            var _index = $li.index();
            $li.parents('.tve_scT').find('.tve_scTC').hide().eq(_index).show();
            $li.addClass('tve_tS').siblings().removeClass('tve_scTS');
        },
        enabledChange: function ($checkbox) {
            var $tabs = $lightbox.find('#tve-social-custom-config');
            if (!$tabs.length) {
                return;
            }
            if ($checkbox.is(':checked')) {
                $tabs.find('li.tve_tab_' + $checkbox.val()).show();
                $tabs.show();
            } else {
                $tabs.find('li.tve_tab_' + $checkbox.val()).hide();
                $tabs.find('li.tve_tc_' + $checkbox.val()).hide();
                if (!$tabs.find('.tve_scT li:visible').length) {
                    $tabs.hide();
                }
            }
            if (!$tabs.find('li.tve_tS:visible').length) {
                $tabs.find('.tve_scT li:visible').first().click();
            }

            this.positionTabs();
        },
        positionTabs: function () {
            var $tabs = $lightbox.find('#tve-social-custom-config');
            $tabs.find('.tve_scTC').css('min-height', ($tabs.find('.tve_scT ul').outerHeight(true) + 20) + 'px');
        },
        toggleDisplay: function ($btn) {
            $($btn.attr('data-target')).toggle();
        },
        /**
         * copy the link from the input that's located near the button to all other social networks
         * @param $btn
         */
        copyLink: function ($btn) {
            var _href = $btn.parent().find('input[name=href]').val();
            $btn.parents('#tve-lb-form').first().find('input[name=href]').val(_href);
        },

        setupButtonsOverlay: function ($element) {

            var $overlay = $('<div class="tve_s_overlay"></div>').css({
                width: "100%",
                height: "100%",
                position: 'absolute',
                top: '0px',
                left: '0px',
                cursor: 'move'
            });
            if ($element.is('.tve_s_item')) {
                $element.css({
                    position: 'relative'
                }).append($overlay);
            } else {
                $element.find('.tve_s_item').css({
                    position: 'relative'
                }).append($overlay);
            }
        },

        enableSortable: function ($btn, $element, event) {

            $('.edit_mode').removeClass('edit_mode');
            $element.find(".tve_social_items").addClass('edit_mode');
            $element.find('.tve_social_overlay').css('display', 'none');

            hide_control_panel_menu();
            TVE_Editor_Page.disable();

            var $_menu = $("#tve_social_sort"),
                position = $element.offset(),
                top = position.top,
                self = this;

            $_menu.css({
                left: (position.left) + 'px',
                width: $element.outerWidth()
            });

            var body_top = jQuery('body').offset().top;
            if (isNaN(body_top)) {
                body_top = 0;
            }

            $.when($_menu.show(0)).done(function () {
                $_menu.css('top', (top - body_top + $element.outerHeight() + 10) + 'px');
            });

            this.setupButtonsOverlay($element);

            $element.find(".tve_social_items").sortable({
                tolerance: "pointer",
                update: function (event, ui) {
                    /**
                     * xing needs to be re-rendered, the iframe is generated on-the-fly, it does not have a src
                     */
                    if (TVE_Content_Builder.$social.type == 'default' && ui.item.hasClass('tve_s_xing_share')) {
                        var $item = $(TVE_Content_Builder.$social.getNetworkHtml('xing_share'));
                        ui.item.after($item);
                        ui.item.remove();
                        scriptLoader(_noop, 'xing');
                        self.setupButtonsOverlay($item);
                        $element.find('.tve_social_items').sortable('refresh');
                    }
                }
            });

            $('html,body').tve_css('overflow-x', 'visible', 'important');
        },

        saveSortable: function ($btn, $element, event) {

            var _config = TVE_Content_Builder.$social.getConfig();
            _config.order = [];

            $element.find('.tve_s_item').each(function () {
                var $this = $(this);
                _config.order.push($this.attr('data-s'));
            });

            if (_config.order.length) {
                _config.selected = _config.order;
            }

            TVE_Content_Builder.$social.setConfig(_config);

            this.closeSortable(null, $element, null);
        },

        closeSortable: function ($btn, $element, event) {
            $('html,body').css('overflow-x', '');
            $("#tve_social_sort").hide();
            $element.removeClass('edit_mode');
            $element.find('.tve_s_overlay').remove();
            $element.sortable("destroy");
            $element.parents('.thrv_social').first().find('.tve_social_overlay').css('display', 'block');
            $(TVE_Editor_Page).unbind('editor.onenable');
            TVE_Editor_Page.CPANEL_OPEN = false;
            TVE_Editor_Page.enable();
            TVE_Content_Builder.$social.render();
        },
        openMedia: function ($btn) {
            var $target = $btn.parent().find('img.tve-s-custom-image');
            var $body = $('body').addClass('mce_open');
            thrive_open_media(null, 'load', 'simple_image', $target, function (attachment) {
                $btn.parent().find('input.tve-image-value').val(attachment.original_url);
                $body.removeClass('mce_open');
                if (attachment.url) {
                    $btn.parent().find('.tve-s-remove-img').show();
                }
            });
        },
        removeMedia: function ($btn) {
            $btn.hide();
            $btn.parent().find('img.tve-s-custom-image').attr('src', '').hide();
            $btn.parent().find('input.tve-image-value').val('');
        }
    };

    TVE_Content_Builder.networks = {
        default_html: function (config) {
            var self = this,
                $html = $();
            jQuery.each(config.selected, function (i, item) {
                var $tpl = $(tve_path_params.social_default_html[item]);
                $tpl.wrap('<div class="tve_s_item tve_s_' + item + '" data-s="' + item + '"></div>');
                config[item] = config[item] || {};
                self[item].setConfig($tpl, config);
                $html = $html.add($tpl.parent());
            });

            return $html.wrapAll('<div></div>').parent().html();
        },
        custom_html: function (config) {
            var self = this,
                $html = $();
            $.each(config.selected, function (i, network) {
                var $tpl = $('#tve-elem-social-custom').find('.tve_s_' + network).clone();
                self[network].decorateCustom && self[network].decorateCustom($tpl, config);
                $html = $html.add($tpl);
            });

            return $html;
        },
        fb_share: {
            sdk: 'fb',
            setConfig: function ($elem, config) {
                if (config.btn_type == 'btn_count') {
                    $elem.attr('data-layout', 'button_count');
                }
                $elem.attr('data-href', config.fb_share.href === '{tcb_post_url}' ? window.tve_path_params.post_url : config.fb_share.href);
            },
            /**
             * check if the fb_share element requires the db js sdk loaded
             * it is required in case the user selected the "FEED" share dialog
             *
             * @param config
             */
            customRequiresSDK: function (config) {
                return !(config.fb_share && config.fb_share.type == 'share');
            },
            /**
             * validate the FB application ID
             * @param $link
             */
            validateAppID: function ($link) {
                var fb_app_id = $link.prop('disabled', true).addClass('tve-disabled').siblings('input[name=app_id]').first().val();
                TVE_Content_Builder.social.showErrors([]);

                function app_id_valid() {
                    $lightbox.find('[name=app_id]').val(fb_app_id).prop('readonly', true);
                    $lightbox.find('.tve_fb_id_invalid').hide();
                    $lightbox.find('.tve_fb_id_valid').show();
                }

                function app_id_invalid() {
                    TVE_Content_Builder.social.showErrors([{
                        tab: $link.parents('.tve_social_config_container').first().attr('data-config'),
                        field: 'app_id',
                        message: tve_path_params.translations.ErrorValidatingID
                    }]);
                }

                $.ajax({
                    url: 'https://graph.facebook.com/' + fb_app_id,
                    dataType: 'json',
                    xhrFields: {
                        withCredentials: false
                    },
                    crossDomain: true
                }).done(function (response) {
                    if (response.id && response.id == fb_app_id) {
                        tve_path_params.social_fb_app_id = fb_app_id;
                        app_id_valid();
                        /** save it directly to the server */
                        jQuery.post(tve_path_params.ajax_url, {
                            action: 'tve_ajax_update_option',
                            option_name: 'tve_social_fb_app_id',
                            option_value: fb_app_id,
                            security: tve_path_params.tve_ajax_nonce
                        });
                    } else {
                        app_id_invalid();
                    }
                }).error(function () {
                    app_id_invalid();
                }).always(function () {
                    $link.removeClass('tve-disabled').prop('disabled', false);
                });
            },
            changeAppID: function ($btn) {
                $btn.siblings('input[name=app_id]').prop('readonly', false).focus().select();
                $lightbox.find('.tve_fb_id_invalid').show();
                $lightbox.find('.tve_fb_id_valid').hide();
            },
            /**
             * functions needed in the lightbox setup
             */
            setup: {
                /**
                 * user clicked on feed or share
                 * @param $selection
                 */
                type: function ($selection) {
                    var type = $selection.attr('data-type');
                    $lightbox.find('#fb-feed-options')[type === 'feed' ? 'show' : 'hide']();
                    $selection.addClass('tve_selected').siblings().removeClass('tve_selected');
                    $lightbox.find('#social-config-fb_share-type').val(type);
                }
            },
            /**
             * For the FEED FB dialog, we need to have a FB App ID setup
             *
             * @param config
             * @returns {*[]}
             */
            validate_custom: function (config) {
                if (config.type == 'feed' && !tve_path_params.social_fb_app_id) {
                    return [{
                        tab: 'fb_share',
                        message: tve_path_params.translations.PleaseSetCorrectAppID,
                        field: 'app_id'
                    }];
                }
            }
        },
        fb_like: {
            sdk: 'fb',
            setConfig: function ($elem, config) {
                if (config.btn_type == 'btn_count') {
                    $elem.attr('data-layout', 'button_count');
                }
                $elem.attr('data-href', config.fb_like.href === '{tcb_post_url}' ? window.tve_path_params.post_url : config.fb_like.href);
            }
        },
        g_share: {
            sdk: 'google',
            setConfig: function ($elem, config) {
                if (config.btn_type == 'btn_count') {
                    $elem.attr('data-annotation', 'bubble');
                }
                $elem.attr('data-href', config.g_share.href === '{tcb_post_url}' ? window.tve_path_params.post_url : config.g_share.href);
            }
        },
        g_plus: {
            sdk: 'google',
            setConfig: function ($elem, config) {
                if (config.btn_type == 'btn_count') {
                    $elem.attr('data-annotation', 'bubble');
                    $elem.parent().addClass('tve_s_g_plus_count');
                }
                $elem.attr('data-href', config.g_plus.href === '{tcb_post_url}' ? window.tve_path_params.post_url : config.g_plus.href);
            }
        },
        t_share: {
            sdk: 'twitter',
            setConfig: function ($elem, config) {
                if (config.btn_type == 'btn_count') {
                    $elem.removeAttr('data-count');
                }
                $elem.attr('data-url', config.t_share.href === '{tcb_post_url}' ? window.tve_path_params.post_url : config.t_share.href);
            },
            setup: {
                /**
                 * count the remaining characters
                 */
                tweetLength: function () {
                    var remaining = 140,
                        via = $lightbox.find('#ts-via').val(),
                        tweet = $lightbox.find('#ts-tweet').val();

                    var url = $lightbox.find('#ts-url').val() || location.href;

                    /**
                     * t.co shortened links
                     * @type {number}
                     */
                    remaining -= url.match(/^https/) ? 23 : 22;
                    remaining -= tweet.length + 1; // space between text and link
                    remaining -= via ? 6 + via.replace('@', '').length : 0;

                    $('#tve-s-t-counter').css('color', remaining <= 0 ? 'red' : '').find('.c-cnt').html(remaining);

                    return remaining;
                }
            },
            validate: function (config) {
                if (this.setup.tweetLength() < 0) {
                    return [{
                        tab: 't_share',
                        message: tve_path_params.translations.TweetContainsTooManyCharacters,
                        field: 'tweet'
                    }];
                }
            }
        },
        t_follow: {
            sdk: 'twitter',
            setConfig: function ($elem, config) {
                if (config.btn_type == 'btn_count') {
                    $elem.removeAttr('data-show-count');
                }
                if (config.t_follow.username) {
                    $elem.attr('href', 'https://twitter.com/' + config.t_follow.username);
                }
                if (config.t_follow.hide_username) {
                    $elem.attr('data-show-screen-name', 'false');
                }
            },
            /**
             * twitter follow requires a username
             * @param config
             */
            validate_default: function (config) {
                if (!config || !$.trim(config.username)) {
                    return [{
                        tab: 't_follow',
                        message: tve_path_params.translations.UsernameRequired,
                        field: 'username'
                    }];
                }
                config.username = '@' + config.username.replace(/@/g, '');
            }
        },
        in_share: {
            sdk: 'linkedin',
            setConfig: function ($elem, config) {
                if (config.btn_type == 'btn_count') {
                    $elem.attr('data-counter', "right");
                }
                $elem.attr('data-url', config.in_share.href === '{tcb_post_url}' ? window.tve_path_params.post_url : config.in_share.href);
            }
        },
        pin_share: {
            sdk: 'pinterest',
            setConfig: function ($elem, config) {
                if (config.btn_type == 'btn_count') {
                    $elem.attr("data-pin-config", "beside");
                    $elem.attr("data-pin-zero", "true");
                    $elem.parent().addClass('tve_s_pin_share_count');
                }
                var _url = encodeURIComponent(config.pin_share.href === '{tcb_post_url}' ? window.tve_path_params.post_url : config.pin_share.href),
                    _media = encodeURIComponent(config.pin_share.media ? config.pin_share.media : ''),
                    _description = encodeURIComponent(config.pin_share.description ? config.pin_share.description : '');
                $elem.attr('href', '//www.pinterest.com/pin/create/button/?url=' + _url + '&media=' + _media + '&description=' + _description);
            },
            decorateCustom: function ($elem, config) {
                config.pin_share.label = 'Pin';
            }
        },
        xing_share: {
            sdk: 'xing',
            setConfig: function ($elem, config) {
                if (config.btn_type == 'btn_count') {
                    $elem.attr("data-counter", "right");
                }
                $elem.attr('data-url', config.xing_share.href === '{tcb_post_url}' ? window.tve_path_params.post_url : config.xing_share.href);
            }
        }
    };

    $.fn.tve_social = function () {
        var _type = this.hasClass('thrv_social_default') ? 'default' : 'custom',
            plugin = this['tve_social_' + _type]();

        $.extend(plugin, {
            getValidationErrors: function (config) {
                var errors = [];
                $.each(config.selected, function (k, network) {
                    var n = TVE_Content_Builder.networks[network];
                    if (n && (n['validate_' + _type] || n['validate'])) {
                        var validator = n['validate'] ? n['validate'] : n['validate_' + _type]
                        var err = validator.call(n, config[network]);
                        if ($.isArray(err)) {
                            errors = errors.concat(err);
                        }
                    }
                });
                return errors;
            }
        });

        plugin.type = _type;

        return plugin;
    };

    /**
     * implemented as a shortcode with JSON configuration saved in a hidden div
     *
     * @returns object
     */
    $.fn.tve_social_default = function () {
        var $element = this,
            configObj = TVE_Editor_Page.thriveShrtcodeConfig($element.find('.thrive-shortcode-config'), 'social_default');

        return {
            $el: $element,
            getConfig: function () {
                if (!this.config) {
                    /* lazy load */
                    this.config = configObj.get();
                }
                return this.config;
            },
            setConfig: function (config) {
                this.config = config;
                configObj.save(config);
            },
            render: function (config) {

                if (typeof config == 'undefined') {
                    config = this.getConfig();
                } else {
                    this.setConfig(config);
                }

                var html = TVE_Content_Builder.networks.default_html(config),
                    rendered = $element.find('.tve_social_items');

                rendered.html(html);
                if (config.type == 'default') {
                    TVE_Content_Builder.social.onDefaultInsert(rendered.parent());
                }
            },
            getNetworkHtml: function (network) {
                var _tempConfig = {};
                $.extend(true, _tempConfig, this.config);
                _tempConfig.selected = [network];

                return TVE_Content_Builder.networks.default_html(_tempConfig);
            },
            getRequiredSDKs: function () {
                var _list = [];
                $.each(this.getConfig().selected, function (i, network) {
                    _list.push(TVE_Content_Builder.networks[network].sdk);
                });
                return _list;
            }
        }
    };

    /**
     *
     * @type {{}}
     */
    $.fn.tve_social_custom = function () {
        var $element = this,
            $customElement = $element.find('.tve_social_custom');

        return {
            $el: $element,
            getConfig: function () {
                if (!this.config) {
                    this.readConfig();
                }

                return this.config;
            },
            setConfig: function (config) {
                this.config = config;
                $.each(config.selected, function (i, network) {
                    if (config[network]) {
                        var $network = $element.find('.tve_s_' + network);
                        $.each(config[network], function (k, v) {
                            if (v) {
                                $network.attr('data-' + k, v);
                            } else {
                                $network.removeAttr('data-' + k);
                            }
                        });
                    }
                });
                config.global = config.global || {};
                $.each(config.global, function (k, v) {
                    if (v) {
                        $element.attr('data-' + k, v);
                    } else {
                        $element.removeAttr('data-' + k);
                    }
                });
            },
            readConfig: function () {
                this.config = {
                    type: 'custom'
                };
                this.config.selected = [];
                this.config.order = [];
                var self = this;
                $element.find('.tve_s_item').each(function () {
                    var $this = $(this),
                        network = $this.attr('data-s');
                    self.config.selected.push(network);
                    self.config.order.push(network);
                    self.config[network] = $.extend({}, $this.data());
                });
                this.config.global = $.extend({}, $element.data());
            },
            render: function (config) {

                if (typeof config === 'undefined') {
                    config = this.getConfig();
                }

                $element.find('.tve_social_items').empty().append(TVE_Content_Builder.networks.custom_html(config));

                /**
                 * setup global stuff, e.g. labels, counters etc
                 */
                $.each(config.selected, function (index, network) {
                    if (typeof config[network].label !== 'undefined') {
                        $element.find('.tve_s_' + network + ' .tve_s_text').html(config[network] && config[network].label ? config[network].label : '');
                    }
                });

                this.setConfig(config);
                TCB_Front.getShareCounts($element, {
                    post_id: tve_path_params.post_id
                });
            },
            getRequiredSDKs: function () {
                var _list = [],
                    config = this.getConfig();

                $.each(config.selected, function (i, network) {
                    var n = TVE_Content_Builder.networks[network];
                    if (n.customRequiresSDK && n.customRequiresSDK(config)) {
                        _list.push(n.sdk);
                    }
                });
                return _list;
            },
            /**
             * display or hide the total share count for the element
             */
            totalShareCount: function () {
                if (!this.config.global || !this.config.global.counts) {
                    $element.find('.tve_s_share_count').remove();
                    return;
                }
                var $counts = $element.find('.tve_s_share_count');
                if (!$counts.length) {
                    $counts = $('#tve-elem-social-custom .tve_s_share_count').clone().prependTo($element);
                }
                TCB_Front.getShareCounts($element, {
                    post_id: tve_path_params.post_id
                });

            },
            getSize: function () {
                if ($customElement.hasClass('tve_social_small')) {
                    return 'small';
                } else if ($customElement.hasClass('tve_social_medium')) {
                    return 'medium';
                } else if ($customElement.hasClass('tve_social_big')) {
                    return 'big';
                }
            },
            getType: function () {
                if ($customElement.hasClass('tve_social_ib')) {
                    return 'tve_social_ib';
                } else if ($customElement.hasClass('tve_social_cb')) {
                    return 'tve_social_cb';
                } else if ($customElement.hasClass('tve_social_itb')) {
                    return 'tve_social_itb';
                }
            },
            getStyle: function () {
                for (var i = 1; i <= 5; i++) {
                    if ($customElement.hasClass('tve_style_' + i)) {
                        return 'tve_style_' + i;
                    }
                }
            }
        };
    };

    $(TVE_Content_Builder).on('menu_show_social_default', function (event, element, $menu) {
        TVE_Content_Builder.$social = $(element).tve_social();
        TVE_Content_Builder.social.onMenuShow($menu, 'default');
    });

    $(TVE_Content_Builder).on('menu_show_social_custom', function (event, element, $menu) {
        TVE_Content_Builder.$social = $(element).tve_social();
        TVE_Content_Builder.social.onMenuShow($menu, 'custom');
    });


})(jQuery);