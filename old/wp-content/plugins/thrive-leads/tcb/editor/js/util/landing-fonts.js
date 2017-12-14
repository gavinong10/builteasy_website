/**
 * Created by Danut on 7/15/2015.
 */
var TVE_Content_Builder = TVE_Content_Builder || {};

(function ($) {

    tve_path_params.tve_globals.landing_fonts = tve_path_params.tve_globals.landing_fonts || {};

    var landing_fonts = {
        $main_menu: null,
        initElement: function ($btn, $element, event) {
            tve_refresh(false);
            var $wrapper = jQuery('.tve_wrap_all');
            $wrapper.addClass('tve_landing_fonts');
            event.target = $wrapper;
            event.currentTarget = $wrapper;
            tve_editor_init(event, $wrapper);
        },
        addToGlobals: function (selector, rule, value) {
            tve_path_params.tve_globals['landing_fonts'][selector] = tve_path_params.tve_globals['landing_fonts'][selector] || {};
            tve_path_params.tve_globals['landing_fonts'][selector][rule] = value;
        },
        removeFromGlobals: function (selector, rule) {
            if (tve_path_params.tve_globals['landing_fonts'][selector] && tve_path_params.tve_globals['landing_fonts'][selector][rule]) {
                delete tve_path_params.tve_globals['landing_fonts'][selector][rule];
            }
        },
        getSelectorMenu: function (selector) {
            if (!this.$main_menu) {
                return null;
            }
            return this.$main_menu.find('ul.tve_menu[data-selector="' + selector + '"]');
        },
        setFontFamily: function ($button) {
            tve_set_selected_dropdown($button);
            var _selector = $button.data('selector'),
                _font_family = $button.data('font-family'),
                _css_class = $button.data('cls');

            this.addToGlobals(_selector, 'font-family', _font_family);
            tve_path_params.tve_globals.landing_fonts[_selector]['css_class'] = _css_class;
            this.add_css_rule(_selector, 'font-family: ' + _font_family);
        },
        clearFontFamily: function ($button) {
            var _selector = $button.data('selector'),
                _menu = this.getSelectorMenu(_selector);
            this.remove_css_rule(_selector, 'font-family');
            this.removeFromGlobals(_selector, 'font-family');
            delete tve_path_params.tve_globals.landing_fonts[_selector]['css_class'];
            tve_set_selected_dropdown(_menu.find('.tve_font_list li').first(), true);
        },
        setFontSize: function ($input) {
            var _selector = $input.data('selector'),
                _size = tve_handle_integer_or_float($input.val()),
                _unit = this.getSelectorMenu(_selector).find('.tve_landing_fonts_size_unit[data-selector="' + _selector + '"]').text();

            this.add_css_rule(_selector, 'font-size:' + _size + _unit + "");
            this.addToGlobals(_selector, 'font-size', _size + _unit + "");
        },
        setFontSizeUnit: function ($button) {
            var _selector = $button.data('selector'),
                _size = tve_handle_integer_or_float(this.getSelectorMenu(_selector).find('input.tve_landing_fonts_size[data-selector="' + _selector + '"]').val()),
                _unit = $button.text().indexOf("em") !== -1 ? 'em' : 'px';

            tve_set_selected_dropdown($button);

            this.add_css_rule(_selector, 'font-size:' + _size + _unit + "");
            this.addToGlobals(_selector, 'font-size', _size + _unit + "");
        },
        setFontLineHeight: function ($input) {
            var _selector = $input.data('selector'),
                _size = tve_handle_integer_or_float($input.val()),
                _unit = this.getSelectorMenu(_selector).find('.tve_landing_fonts_line_height_unit[data-selector="' + _selector + '"]').text();

            this.add_css_rule(_selector, 'line-height:' + _size + _unit + "");
            this.addToGlobals(_selector, 'line-height', _size + _unit + "");
        },
        setFontLineHeightUnit: function ($button) {
            var _selector = $button.data('selector'),
                _size = tve_handle_integer_or_float(this.getSelectorMenu(_selector).find('input.tve_landing_fonts_line_height[data-selector="' + _selector + '"]').val()),
                _unit = $button.text().indexOf("em") !== -1 ? 'em' : 'px';

            tve_set_selected_dropdown($button);

            this.add_css_rule(_selector, 'line-height:' + _size + _unit + "");
            this.addToGlobals(_selector, 'line-height', _size + _unit + "");
        },
        remove_css_rule: function (selector, rule) {
            tve_remove_css_rule("#tve_editor " + selector, rule);
            if (selector === 'p') {
                tve_remove_css_rule("#tve_editor li", rule);
            }
        },
        add_css_rule: function (selector, rule) {
            tve_add_css_rule("#tve_editor " + selector, rule, 0);
            if (selector === 'p') {
                tve_add_css_rule("#tve_editor li", rule, 0);
            }
        },
        onMenuLoad: function ($element, $menu) {
            this.$main_menu = $menu;

            $.each(tve_path_params.tve_globals.landing_fonts, function (selector, rules) {
                var _submenu = $menu.find('.tve_menu[data-selector="' + selector + '"]');

                if (rules['font-family']) {
                    var $li = _submenu.find('.tve_font_list li[data-font-family="' + rules['font-family'] + '"]');
                    tve_set_selected_dropdown($li);
                }

                if (rules['font-size']) {
                    var _font_size = tve_handle_integer_or_float(rules['font-size']),
                        _unit = rules['font-size'].indexOf("px") !== -1 ? "px" : "em",
                        _unit_li = _submenu.find("li.tve_landing_fonts_size_" + _unit + "[data-selector='" + selector + "']");
                    _submenu.find('input.tve_landing_fonts_size[data-selector="' + selector + '"]').val(_font_size);
                    tve_set_selected_dropdown(_unit_li);
                }

                if (rules['line-height']) {
                    var _line_height = tve_handle_integer_or_float(rules['line-height']),
                        _unit = rules['line-height'].indexOf("px") !== -1 ? "px" : "em",
                        _unit_li = _submenu.find("li.tve_landing_fonts_line_height_" + _unit + "[data-selector='" + selector + "']");
                    _submenu.find('input.tve_landing_fonts_line_height[data-selector="' + selector + '"]').val(_line_height);
                    tve_set_selected_dropdown(_unit_li);
                }
            });
        },
        fontUsed: function (font_class) {
            var _found = false;
            $.each(tve_path_params.tve_globals.landing_fonts, function (selector, rules) {
                if (rules.css_class === font_class) {
                    _found = true;
                    return;
                }
            });

            return _found;
        },
        colorChanged: function (data) {
            if (!data.force_selector) {
                return;
            }
            var _selector = data.force_selector.replace("#tve_editor ", "");
            this.add_css_rule(_selector, "color: " + data.color_value);
            this.addToGlobals(_selector, 'color', data.color_value);
        }
    };

    TVE_Content_Builder.landing_fonts = landing_fonts;

    $(TVE_Content_Builder).on('menu_show_landing_fonts', function (event, $element, $menu) {
        landing_fonts.onMenuLoad($element, $menu);
    });

    $(TVE_Editor_Page).on('editor.beforecolorpickerchange_landing_fonts', function (event, data) {
        landing_fonts.colorChanged(data);
    });

    TVE_Content_Builder.add_filter('get_static_color_landing_fonts', function (color, selector) {
        var _selector = selector.replace("#tve_editor ", "");

        if (tve_path_params.tve_globals.landing_fonts[_selector] && tve_path_params.tve_globals.landing_fonts[_selector].color) {
            color = tve_path_params.tve_globals.landing_fonts[_selector].color;
        } else {
            color = $(selector).css('color');
            if (!color) {
                var element = $('<' + _selector + '></' + _selector + '>').appendTo(TVE_Editor_Page.editor);
                color = element.css('color');
                element.remove();
            }
        }

        return color;
    });

})(jQuery);
