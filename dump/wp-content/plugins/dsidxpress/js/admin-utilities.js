(function ($) {
    dsIdxLinkBuilder = {
        init: function () {
            this.resetFilters();
            if ($('#dsidxpress-assembled-url').val() == 'http://') {
                this.resetUrl();
            } else {
                this.loadFiltersFromUrl();
            }
            this.disableSubmitOnEnterKey();
            // load the filter options
            $('#dsidxpress-filter-menu').append($('<option>', { value: -1 }).text('Choose criteria...'));
            $.each(this.filters, function (key, filter) {
                $('#dsidxpress-filter-menu').append($('<option>', { value: key }).text(filter.name));
            });

            // attach events
            $('#dsidxpress-filter-menu').change(function () {
                dsIdxLinkBuilder.buildEditor(this);
            });

            $('#dsidxpress-link-builder').on('click', '.dsidx-editor-cancel', function () {
                dsIdxLinkBuilder.cancelEditor();
            });

            $('#dsidxpress-link-builder').on('click', '.button-primary', function () {
                dsIdxLinkBuilder.addFilter();
            });

            $('#dsidxpress-filter-list').on('click', 'li', function () {
                dsIdxLinkBuilder.buildEditor(this);
            });

            $('#dsidxpress-filter-list').on('click', 'li a', function (e) {
                dsIdxLinkBuilder.removeFilter(this.parentNode);
                e.stopPropagation();
            });
            $('#dsidxpress-link-builder').on('click', '#idx-lookup-geocode-btn', function () {
                dsIdxLinkBuilder.doRadiusGeocode();
            });
            $('#dsidxpress-link-builder .dsidxpress-checkbox input').click(function () {
                if (this.checked) {
                    $('#dsidxpress-assembled-url-wrap').removeClass('hidden');
                } else {
                    $('#dsidxpress-assembled-url-wrap').addClass('hidden');
                }
            });

            $('#submit-linkbuilderdiv').click(function (e) {
                $('#img-link-builder-waiting').show();
                wpNavMenu.addLinkToMenu($('#dsidxpress-assembled-url').val(), $('#dsidxpress-menu-item-label').val(), null, function () {
                    $('#img-link-builder-waiting').hide();
                    $('#dsidxpress-menu-item-label').val('').blur();
                    $('#dsidxpress-menu-item-url').val(dsIdxLinkBuilder.resetUrl());
                    dsIdxLinkBuilder.resetFilters();
                    dsIdxLinkBuilder.cancelEditor();
                });
            });
        },
        buildEditor: function (el, n) {
            var $e = $(el), n = el.nodeName.toLowerCase();
            if ($e.val() != '-1') {
                var fid = (n == 'li') ? el.id.replace('dsidxpress-filter-', '') : $e.val();
                var filter = this.filters[fid];
                var $w = $('#dsidxpress-editor-wrap');
                var lb = dsIdxLinkBuilder, html = '';

                this.resetMenu(fid);

                if (filter.help) {
                    html += '<p class="howto">' + filter.help + '</p>';
                }
                if(filter.autocomplete){
                    html += '<div class="filter-autocomplete"><input type="text" placeholder="'+filter.autocomplete.label+'" id="dsidx-filter-autocomplete" /></div>';
                }
                $.each(filter.fields, function (i, field) {

                    var label = (field.label) ? lb.escapeHtmlEntities(field.label) : lb.escapeHtmlEntities(filter.name);
                    var hint = lb.escapeHtmlEntities(field.hint);
                    var fid = lb.escapeHtmlEntities(field.id);
                    switch (field.mode) {
                        case 'single':
                            value = (field.id in lb.current_filters) ? ' value=' + lb.escapeHtmlEntities(lb.current_filters[field.id]) : '';
                            html +=
                                '<label class="howto">' +
                                '<input id="' + fid + '" name="' + fid + '" type="text" title="' + hint + '" class="input-with-default-title"' + value + ' />' +
                                '<span>' + label + '</span>' +
                                '</label>';
                            break;
                        
                        case 'radius-search':
                            value = (field.id in lb.current_filters) ? ' value=' + lb.escapeHtmlEntities(lb.current_filters[field.id]) : '';
                            html +=
                                '<label class="radius-search">' +
                                '<input type="button" value="Lookup Address" id="idx-lookup-geocode-btn" class="button button-secondary" />' +
                                '<input id="' + fid + '" name="' + fid + '" type="text" title="' + hint + '" ' + value + ' />' +
                                '</label>';
                            break;

                        case 'multi':
                            value = (field.id in lb.current_filters) ? lb.escapeHtmlEntities(lb.current_filters[field.id].join('\n')) : '';
                            html += '<textarea id="' + fid + '" name="' + fid + '" title="' + hint + '" class="input-with-default-title">' + value + '</textarea>';
                            break;

                        case 'lookup':
                            value = (field.id in lb.current_filters) ? lb.escapeHtmlEntities(lb.current_filters[field.id]) : '';
                            if(field.type == 'lookup-narrow'){
                                html += '<label>';
                                html += '<select id="' + fid + '" name="' + fid + '" class="narrow"><option value="0">Select ' + label + '</option>';
                                $.each(field.options, function (v, t) {
                                    var select = (v == value) ? ' selected="selected"' : '';
                                    html += '<option value="' + v + '"' + select + '>' + t + '</option>';
                                });
                                html += '</select>' +
                                '</label>';
                                break;
                            }
                            
                            html += '<select id="' + fid + '" name="' + fid + '" class="regular-text"><option value="0">Select ' + label + '</option>';
                            $.each(field.options, function (v, t) {
                                var select = (v == value) ? ' selected="selected"' : '';
                                html += '<option value="' + v + '"' + select + '>' + t + '</option>';
                            });
                            html += '</select>';
                            break;

                        case 'lookupPropTypes':
                            var curSelections = [];
                            value = (field.id in lb.current_filters) ? lb.escapeHtmlEntities(lb.current_filters[field.id].join('\n')) : '';
                            html += '<div id="' + fid + '" name="' + fid + '" class="regular-text">';
                            var curValues = document.getElementById('linkBuilderPropertyTypes').value;
                            var arrayValues = curValues.split(",");
                            if (lb.current_filters[field.id]) { var txt = new String(lb.current_filters[field.id]); curSelections = txt.split(","); }
                            var ii;
                            for (i = 0; i < arrayValues.length; i++) {
                                var indyValues = arrayValues[i].split(": ");
                                var select = '';
                                if (curSelections.length > 0) {
                                    for (ii = 0; ii < curSelections.length; ii++) {
                                        if (curSelections[ii] == indyValues[0]) {
                                            select = ' checked="checked"';
                                            break;
                                        }
                                    }
                                }
                                html += '<input type="checkbox" name="groupPropType" value="' + indyValues[0] + '"' + select + '>' + indyValues[1] + '<div style="height:6px; clear:both;"></div>';
                            }
                            html += '</div>';
                            break;

                        case 'lookupListStatuses':
							value = (field.id in lb.current_filters) ? lb.current_filters[field.id] : '';
                            html += '<div id="' + fid + '" name="' + fid + '" class="regular-text">';
	                        var selectActive = (value == 1 || value == 3 || value == 5 || value == 7 || value == 9 || value == 11 || value == 13 || value == 15) ? ' checked' : '';
	                        var selectConditional = (value == 2 || value == 3 || value == 6 || value == 7 || value == 10 || value == 11 || value == 14 || value == 15) ? ' checked' : '';
	                        var selectPending = (value == 4 || value == 5 || value == 6 || value == 7 || value == 12 || value == 13 || value == 14 || value == 15) ? ' checked' : '';
	                        var selectSold = (value == 8 || value == 9 || value == 10 || value == 11 || value == 12 || value == 13 || value == 14 || value == 15) ? ' checked' : '';
                            html += '<input type="checkbox" name="groupListStatus" value="1"' + selectActive + '>Active<div style="height:6px; clear:both;"></div>';
                            html += '<input type="checkbox" name="groupListStatus" value="2"' + selectConditional + '>Conditional<div style="height:6px; clear:both;"></div>';
                            if(('HasSoldData' in mlsCapabilities) && mlsCapabilities['HasSoldData'] !== ''){
                                html += '<input type="checkbox" name="groupListStatus" value="4"' + selectPending + '>Pending<div style="height:6px; clear:both;"></div>';
                            }
                            if(('HasSoldData' in mlsCapabilities) && mlsCapabilities['HasSoldData'] !== ''){
                                html += '<input type="checkbox" name="groupListStatus" value="8"' + selectSold + '>Sold<div style="height:6px; clear:both;"></div>';
                            }
                            html += '</div>';
                            break;
                    }
                });

                $w.find('.dsidxpress-editor-header h4 b').html(filter.name);
                $w.find('.dsidxpress-editor-main').html(html);
                $w.removeClass('hidden');

                this.setupFieldsWithDefaultTitle();
                if(filter.autocomplete){
                    this.buildAutoComplete(filter);
                }
            }
        },
        buildAutoComplete: function(filter){
            if($('#dsidx-filter-autocomplete').data('ui-autocomplete') != undefined){
                $('#dsidx-filter-autocomplete').autocomplete('destroy');
            }
            var dataUrl = filter.autocomplete.url;
            var dataField = filter.autocomplete.dataField;
            var appendTo = filter.autocomplete.appendTo;
            $.ajax({
                url: dataUrl,
                type: 'post',
                data: filter.autocomplete.args,
                success: function(response){
                    $('#dsidx-filter-autocomplete').autocomplete({source:response, select: function(event, ui){dsIdxLinkBuilder.handleAutocompleteSelect(event, ui, appendTo); $(this).val(''); return false;}});
                },
                dataType:'json'
            });
        },
        handleAutocompleteSelect: function(event, ui, appendTo){
            var selectedVal = ui.item.value;
            var newVals = [];
            if($('#'+appendTo).hasClass('input-with-default-title')){
                $('#'+appendTo).val(selectedVal).removeClass('input-with-default-title');
                return;
            }

            var curVals = $('#'+appendTo).val().split("\n");
            for(var i in curVals){
                var clean = curVals[i].trim();
                if(clean == selectedVal){
                    return;
                }
                newVals.push(clean);
            }
            newVals.push(selectedVal);
            $('#'+appendTo).val(newVals.join("\n"));

        },
        cancelEditor: function () {
            $('#dsidxpress-editor-wrap').addClass('hidden');
            this.resetMenu();
        },
        disableSubmitOnEnterKey: function (){
            $(document).on('keypress', '#dsidxpress-link-builder input', function(e){
                if ( e.which == 13 ) return false;
            });
        },
        addFilter: function () {
            var $e = $('#dsidxpress-filter-menu'), lb = dsIdxLinkBuilder, active_filter = false, err = false, err_msg;
            var filter = this.filters[$e.val()];
            $.each(filter.fields, function (x, field) {
                var $f = $('#' + field.id);
                if (field.type == 'lookupPropTypes') {//this type has different way of aquiring the value from radio inputs
                    if (lb.validate(field, $f.val())) {
                        var curChildren = $f.children('input');
                        lb.current_filters[field.id] = [];
                        var index = 0;
                        $f.children().each(function () {
                            var opt = $(this);
                            if (opt.is(':checked')) {
                                lb.current_filters[field.id][index] = opt.val();
                                active_filter = true;
                                index++;
                            }
                        });
                    } else {
                        err_msg = 'You have entered invalid information, please check your entries and try again.';
                        err = true;
                    }
                }
                else if (field.type == 'lookupListStatuses') {//this type has different way of aquiring the value from radio inputs
	                if (lb.validate(field, $f.val())) {
                        var curChildren = $f.children('input');
                        lb.current_filters[field.id] = -1;
                        var index = 0;
                        $f.children().each(function () {
                            var opt = $(this);
                            if (opt.is(':checked')) {
                                index = +index + +opt.val();
                            }
                        });
                        if (index!=0) {
							lb.current_filters[field.id] = index;
	                        active_filter = true;
                        }
                    } else {
                        err_msg = 'You have entered invalid information, please check your entries and try again.';
                        err = true;
                    }
                }
                else if ($f.val() != '' && $f.val() != field.hint) {
                    switch (field.mode) {
                        case 'single':
                        case 'lookup':
                        case 'lookup-narrow':
                            if (lb.validate(field, $f.val())) {
                                if ($f.val() != '') {
                                    lb.current_filters[field.id] = $f.val();
                                    active_filter = true;
                                }
                            } else {
                                err_msg = 'You have entered invalid information, please check your entries and try again.';
                                err = true;
                            }
                            break;
                        case 'multi':
                            var vals = $f.val().split('\n');
                            lb.current_filters[field.id] = [];
                            var index = 0;
                            $.each(vals, function (i, val) {
                                if (lb.validate(field.type, val)) {
                                    if (val != '') {
                                        lb.current_filters[field.id][index] = val;
                                        active_filter = true;
                                        index++;
                                    }
                                } else {
                                    err_msg = 'You have entered invalid information, please check your entries and try again.';
                                    err = true;
                                }
                            });
                            break;
                    }
                } else {
                    delete lb.current_filters[field.id];
                }
            });

            if (active_filter)
                this.addFilterToList($e.val());

            if (err) {
                if ($('.dsidxpress-editor-main .dsidxpress-editor-error').length == 0) {
                    var msg = '<p class="dsidxpress-editor-error" style="font-weight: bold; color: #c00;">' + err_msg + '</p>';
                    $('.dsidxpress-editor-main').prepend(msg);
                }
            } else {
                this.updateUrl();
                this.cancelEditor();
            }

        },
        addFilterToList: function (id) {
            var $w = $('#dsidxpress-filters-wrap'), $f = $('#dsidxpress-filter-list'), fid = 'dsidxpress-filter-' + id;

            if (!$f.children('#' + fid).length)
                $f.append('<li id="' + fid + '">' + this.filters[id].name + ' <a href="javascript:void(0)"></a></li>');

            if ($w.hasClass('hidden'))
                $w.removeClass('hidden');
        },
        removeFilter: function (el) {
            var id = el.id.replace('dsidxpress-filter-', '');

            var filter = this.filters[id];
            $.each(filter.fields, function (i, field) {
                delete dsIdxLinkBuilder.current_filters[field.id];
            });

            $('#' + el.id).remove();

            if ($.isEmptyObject(this.current_filters)) {
                $('#dsidxpress-filters-wrap').addClass('hidden');
            }

            if ($('#dsidxpress-filter-menu').val() == id)
                this.cancelEditor();

            this.updateUrl();
        },
        resetFilters: function () {
            this.current_filters = {};

            $('#dsidxpress-filter-list').empty();
            if (!$('#dsidxpress-filters-wrap').hasClass('hidden'))
                $('#dsidxpress-filters-wrap').addClass('hidden');
        },
        loadFiltersFromUrl: function () {
            var url = decodeURIComponent($('#dsidxpress-assembled-url').val()), lb = dsIdxLinkBuilder;
            $.each(this.filters, function (key, filter) {
                $.each(filter.fields, function (index, field) {
                    switch (field.mode) {
                        case 'single':
                        case 'lookupListStatuses':
                        case 'lookup':
                            var val = (field.id.indexOf('__DSIDXINDEX__') != -1) ? lb.getQueryValue(url, field.id.replace('__DSIDXINDEX__', '<0>')) : lb.getQueryValue(url, field.id);
                            if (val != '') {
                                lb.current_filters[field.id] = val;
                                lb.addFilterToList(key);
                            }
                            break;

                        case 'lookupPropTypes':
                        case 'multi':
                            for (var i = 0; ; i++) {
                                var val = (field.id.indexOf('__DSIDXINDEX__') != -1) ? lb.getQueryValue(url, field.id.replace('__DSIDXINDEX__', '<' + i + '>')) : lb.getQueryValue(url, field.id + '<' + i + '>');
                                if (val == '') {
                                    break;
                                } else {
                                    if (i == 0) {
                                        lb.current_filters[field.id] = [];
                                    }
                                    lb.current_filters[field.id][i] = val;
                                    lb.addFilterToList(key);
                                }
                            }
                            break;
                    }
                });
            });
        },
        updateUrl: function () {
            var qs = [];
            $.each(this.current_filters, function (key, val) {
                if ($.isArray(val)) {
                    $.each(val, function (i, v) {
                        var k = key, pos = key.indexOf('__DSIDXINDEX__');
                        if (pos > -1) {
                            k = [k.slice(0, pos), '<' + i + '>', k.slice(pos)].join('');
                            k = k.replace('__DSIDXINDEX__', '');
                        } else {
                            k = k + '<' + i + '>';
                        }
                        qs.push(encodeURIComponent(k) + '=' + encodeURIComponent(v));
                    });
                } else {
                    var pos = key.indexOf('__DSIDXINDEX__');
                    key = (pos > -1) ? [key.slice(0, pos), '<0>', key.slice(pos)].join('') : key;
                    key = key.replace('__DSIDXINDEX__', '');
                    qs.push(encodeURIComponent(key) + '=' + encodeURIComponent(val));
                }
            });
            this.resetUrl();
            $('#dsidxpress-assembled-url').val($('#dsidxpress-assembled-url').val() + qs.join('&'));
        },
        resetUrl: function () {
            $('#dsidxpress-assembled-url').val(zpress_home_url + '/idx/?');
        },
        resetMenu: function (val) {
            val = (val) ? val : '-1';
            $('#dsidxpress-filter-menu').val(val);
        },
        validate: function (field, val) {
            var result = true;
            switch (field.type) {
                case 'int':
                    result = /^[0-9]+$/.test(val);
                    break;

                case 'decimal':
                    result = !isNaN(parseFloat(val)) && isFinite(val);
                    break;

                case 'lookup', 'lookup-narrow':
                    result = val in field.options;
                    break;
            }

            return result;
        },
        setupFieldsWithDefaultTitle: function () {
            var name = 'input-with-default-title';
            $('#dsidxpress-editor-wrap .' + name).each(function () {
                var $t = $(this), title = $t.attr('title'), val = $t.val();
                $t.data(name, title);

                if ('' == val) $t.val(title);
                else if (title == val) return;
                else $t.removeClass(name);
            }).focus(function () {
                var $t = $(this);
                if ($t.val() == $t.data(name))
                    $t.val('').removeClass(name);
            }).blur(function () {
                var $t = $(this);
                if ('' == $t.val())
                    $t.addClass(name).val($t.data(name));
            });
        },
        doRadiusGeocode: function(){
            var lookupAddress = $('#idx-Address-Lookup').val();
            if(lookupAddress.trim() == ''){
                return;
            }
            if($('#idx-q-RadiusDistanceInMiles').val() == 0){
                $('#idx-q-RadiusDistanceInMiles').find('option[value="2"]').prop('selected', true); 
            }
            $('#idx-Address-Lookup').val('Looking up address...');
            $('#idx-q-RadiusLatitude').val('');
            $('#idx-q-RadiusLongitude').val('');


            var geocoder = new google.maps.Geocoder();

            if (geocoder) {
                geocoder.geocode({ 'address': lookupAddress }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        var lat = results[0].geometry.location.lat();
                        var lng = results[0].geometry.location.lng();
                        $('#idx-q-RadiusLatitude, #idx-q-RadiusLongitude').removeClass('input-with-default-title');
                        $('#idx-q-RadiusLatitude').val(lat);
                        $('#idx-q-RadiusLongitude').val(lng);
                        $('#idx-Address-Lookup').val('');
                    }
                    else {
                        $('#idx-q-RadiusLatitude').val('');
                        $('#idx-q-RadiusLongitude').val('');
                        $('#idx-Address-Lookup').val(unescape(lookupAddress));
                        alert('Could not get cordinates for provided address');
                    }
                });
            }
            else{
                alert('Could not load google maps API');
            }
        },
        filters: {
            mls_numbers: {
                name: 'MLS Numbers',
                fields: [
                    {
                        id: 'idx-q-MlsNumbers',
                        type: 'textarea',
                        mode: 'multi',
                        hint: 'One per line'
                    }
                ]
            },
            property_types: {
                name: 'Property Types',
                fields: [
                    {
                        id: 'idx-q-PropertyTypes',
                        type: 'lookupPropTypes',
                        mode: 'lookupPropTypes',
                        hint: 'One per line'
                    }
                ]
            },
            property_features: {
                name: 'Property Features',
                fields: [
                    {
                        id: 'idx-q-PropertyFeatures',
                        type: 'string',
                        mode: 'multi',
                        hint: 'One per line'
                    }
                ]
            },
            days_on_market: {
                name: 'Days On Market',
                fields: [
                    {
                        label: 'Minimum Days On Market',
                        id: 'idx-q-DaysOnMarketMin',
                        type: 'int',
                        mode: 'single',
                        hint: '0'
                    },
                    {
                        label: 'Maximum Days On Market',
                        id: 'idx-q-DaysOnMarketMax',
                        type: 'int',
                        mode: 'single',
                        hint: '∞'
                    }
                ]
            },
            price: {
                name: 'Price',
                fields: [
                    {
                        label: 'Minimum Price',
                        id: 'idx-q-PriceMin',
                        type: 'int',
                        mode: 'single',
                        hint: '0'
                    },
                    {
                        label: 'Maximum Price',
                        id: 'idx-q-PriceMax',
                        type: 'int',
                        mode: 'single',
                        hint: '∞'
                    }
                ]
            },
            beds: {
                name: 'Beds',
                fields: [
                    {
                        label: 'Minimum Beds',
                        id: 'idx-q-BedsMin',
                        type: 'int',
                        mode: 'single',
                        hint: '0'
                    },
                    {
                        label: 'Maximum Beds',
                        id: 'idx-q-BedsMax',
                        type: 'int',
                        mode: 'single',
                        hint: '∞'
                    }
                ]
            },
            baths: {
                name: 'Baths',
                fields: [
                    {
                        label: 'Minimum Baths',
                        id: 'idx-q-BathsMin',
                        type: 'int',
                        mode: 'single',
                        hint: '0'
                    },
                    {
                        label: 'Maximum Baths',
                        id: 'idx-q-BathsMax',
                        type: 'int',
                        mode: 'single',
                        hint: '∞'
                    }
                ]
            },
            square_feet: {
                name: 'Square Feet',
                help: 'Be aware that some MLSs either do not provide lot size in their data or give us an unreliable way to calculate it.',
                fields: [
                    {
                        label: 'Minimum Square Feet',
                        id: 'idx-q-ImprovedSqFtMin',
                        type: 'int',
                        mode: 'single',
                        hint: '0'
                    },
                    {
                        label: 'Maximum Square Feet',
                        id: 'idx-q-ImprovedSqFtMax',
                        type: 'int',
                        mode: 'single',
                        hint: '∞'
                    }
                ]
            },
            lot_size: {
                name: 'Lot Size',
                help: 'Lot size in square feet.<br /><br />Be aware that some MLSs either do not provide lot size in their data or give us an unreliable way to calculate it.',
                fields: [
                    {
                        label: 'Minimum Lot Size',
                        id: 'idx-q-LotSqFtMin',
                        type: 'int',
                        mode: 'single',
                        hint: '0'
                    },
                    {
                        label: 'Maximum Lot Size',
                        id: 'idx-q-LotSqFtMax',
                        type: 'int',
                        mode: 'single',
                        hint: '∞'
                    }
                ]
            },
            year_built: {
                name: 'Year Built',
                fields: [
                    {
                        label: 'Minimum Year Built',
                        id: 'idx-q-YearBuiltMin',
                        type: 'int',
                        mode: 'single',
                        hint: '0'
                    },
                    {
                        label: 'Maximum Year Built',
                        id: 'idx-q-YearBuiltMax',
                        type: 'int',
                        mode: 'single',
                        hint: '∞'
                    }
                ]
            },
            price_drop: {
                name: 'Price Drop',
                help: 'The price drop percentage over a specified number of days.',
                fields: [
                    {
                        label: 'Price Drop Days',
                        id: 'idx-q-PriceDropDays',
                        type: 'int',
                        mode: 'single',
                        hint: '#'
                    },
                    {
                        label: 'Price Drop Percentage',
                        id: 'idx-q-PriceDropPercent',
                        type: 'int',
                        mode: 'single',
                        hint: '%'
                    }
                ]
            },
            walk_score: {
                name: 'Walk Score',
                help: 'Be aware that properties without addresses or properties that have un-geocodable addresses will not have Walk Scores.',
                fields: [
                    {
                        label: 'Minimum Walk Score',
                        id: 'idx-q-WalkScoreMin',
                        type: 'int',
                        mode: 'single',
                        hint: '#'
                    }
                ]
            },
            distress_types: {
                name: 'Distress Types',
                fields: [
                    {
                        id: 'idx-q-DistressTypes',
                        type: 'lookup',
                        mode: 'lookup',
                        hint: '-1',
                        options: {
                            '1': 'only pre-foreclosures',
                            '2': 'only foreclosed listings / REOs',
                            '3': 'only pre-foreclosures or REOs',
                            '0': 'neither pre-foreclosures nor REOs'
                        }
                    }
                ]
            },
            listing_statuses: {
                name: 'Listing Statuses',
                fields: [
                    {
                        id: 'idx-q-ListingStatuses',
                        type: 'lookupListStatuses',
                        mode: 'lookupListStatuses',
                        hint: 'One per line'
                    }
                ]
            },
            schools: {
                name: 'Schools/Type',
                fields: [
                    {
                        id: 'idx-q-Schools__DSIDXINDEX__-Name',
                        suffix: '-Name',
                        type: 'string',
                        mode: 'multi',
                        hint: 'One per line'
                    },
                    {
                        id: 'idx-q-Schools__DSIDXINDEX__-Type',
                        type: 'lookup',
                        mode: 'lookup',
                        hint: '-1',
                        options: {
                            'Elementary School': 'Elementary School',
                            'Grade School': 'Grade School',
                            'High School': 'High School',
                            'Jr. High School': 'Jr. High School',
                            'Middle School': 'Middle School',
                            'School District': 'School District'
                        }
                    }
                ]
            },
            listing_agent_id: {
                name: 'Listing Agent IDs',
                fields: [
                    {
                        id: 'idx-q-ListingAgentID',
                        type: 'string',
                        mode: 'multi',
                        hint: 'One per line'
                    }
                ]
            },
            listing_office_id: {
                name: 'Listing Office IDs',
                fields: [
                    {
                        id: 'idx-q-ListingOfficeID',
                        type: 'string',
                        mode: 'multi',
                        hint: 'One per line'
                    }
                ]
            },
            address_masks: {
                name: 'Address Masks',
                help: 'List of partial addresses to match.  A <code>%</code> character indicates a wildcard.',
                fields: [
                    {
                        id: 'idx-q-AddressMasks',
                        type: 'string',
                        mode: 'multi',
                        hint: 'One per line'
                    }
                ]
            },
            cities: {
                name: 'Cities',
                fields: [
                    {
                        id: 'idx-q-Cities',
                        type: 'string',
                        mode: 'multi',
                        hint: 'One per line'
                    }
                ],
                autocomplete: {
                    label: 'Search Cities',
                    url: dsIdxPluginUri + 'client-assist.php?action=LoadAreasByType',
                    args: {'type':'City', 'dataField':'Name'},
                    dataField: 'Name',
                    appendTo: 'idx-q-Cities',
                }
            },
            states: {
                name: 'States',
                fields: [
                    {
                        id: 'idx-q-States',
                        type: 'string',
                        mode: 'multi',
                        hint: 'One per line'
                    }
                ]
            },
            zip_codes: {
                name: 'Zip Codes',
                fields: [
                    {
                        id: 'idx-q-ZipCodes',
                        type: 'string',
                        mode: 'multi',
                        hint: 'One per line'
                    }
                ],
                autocomplete: {
                    label: 'Search Zip Codes',
                    url: dsIdxPluginUri + 'client-assist.php?action=LoadAreasByType',
                    args: {'type':'Zip', 'dataField':'Name'},
                    dataField: 'Name',
                    appendTo: 'idx-q-ZipCodes',
                }
            },
            communities: {
                name: 'Communities',
                fields: [
                    {
                        id: 'idx-q-Communities',
                        type: 'string',
                        mode: 'multi',
                        hint: 'One per line'
                    }
                ],
                autocomplete: {
                    label: 'Search Communities',
                    url: dsIdxPluginUri + 'client-assist.php?action=LoadAreasByType',
                    args: {'type':'Community', 'dataField':'Name'},
                    dataField: 'Name',
                    appendTo: 'idx-q-Communities',
                }
            },
            tracts: {
                name: 'Tracts',
                fields: [
                    {
                        id: 'idx-q-TractIdentifiers',
                        type: 'string',
                        mode: 'multi',
                        hint: 'One per line'
                    }
                ],
                autocomplete: {
                    label: 'Search Tracts',
                    url: dsIdxPluginUri + 'client-assist.php?action=LoadAreasByType',
                    args: {'type':'Tract', 'dataField':'Name'},
                    dataField: 'Name',
                    appendTo: 'idx-q-TractIdentifiers',
                }
            },
            areas: {
                name: 'Areas',
                help: 'List of tracts or communities.',
                fields: [
                    {
                        id: 'idx-q-Areas',
                        type: 'string',
                        mode: 'multi',
                        hint: 'One per line'
                    }
                ],
                autocomplete: {
                    label: 'Search Areas',
                    url: dsIdxPluginUri + 'client-assist.php?action=LoadAreasByType',
                    args: {'type':'Areas', 'dataField':'Name'},
                    dataField: 'Name',
                    appendTo: 'idx-q-Areas',
                }
            },
            counties: {
                name: 'Counties',
                fields: [
                    {
                        id: 'idx-q-Counties',
                        type: 'string',
                        mode: 'multi',
                        hint: 'One per line'
                    }
                ],
                autocomplete: {
                    label: 'Search Counties',
                    url: dsIdxPluginUri + 'client-assist.php?action=LoadAreasByType',
                    args: {'type':'County', 'dataField':'Name'},
                    dataField: 'Name',
                    appendTo: 'idx-q-Counties',
                }
            },
            radius: {
                name: 'Radius',
                help: 'Narrow results down to those within the selected radius of a specific location based on latitude / longitude.',
                fields: [
                    {
                        label: 'Find latitude/longitude by Address',
                        id: 'idx-Address-Lookup',
                        type: 'string',
                        mode: 'radius-search',
                        hint: ''
                    },
                    {
                        label: 'Latitude',
                        id: 'idx-q-RadiusLatitude',
                        type: 'decimal',
                        mode: 'single',
                        hint: ''
                    },
                    {
                        label: 'Longitude',
                        id: 'idx-q-RadiusLongitude',
                        type: 'decimal',
                        mode: 'single',
                        hint: ''
                    },
                    {
                        label: 'Radius',
                        id: 'idx-q-RadiusDistanceInMiles',
                        type: 'lookup-narrow',
                        mode: 'lookup',
                        hint: '',
                        options: {
                            '.5': '0.5 Miles',
                            '1': '1 Mile',
                            '2': '2 Miles',
                            '5': '5 Miles',
                            '10': '10 Miles',
                            '20': '20 Miles'
                        }
                    }
                ]
            },
            latitude: {
                name: 'Latitude',
                help: 'Latitude / longitude will correlate to a rectangular map area.<br /><code>ex: 117.714214</code>',
                fields: [
                    {
                        label: 'Minimum Latitude',
                        id: 'idx-q-LatitudeMin',
                        type: 'decimal',
                        mode: 'single',
                        hint: ''
                    },
                    {
                        label: 'Maximum Latitude',
                        id: 'idx-q-LatitudeMax',
                        type: 'decimal',
                        mode: 'single',
                        hint: ''
                    }
                ]
            },
            longitude: {
                name: 'Longitude',
                help: 'Latitude / longitude will correlate to a rectangular map area.<br /><code>ex: 33.567573</code>',
                fields: [
                    {
                        label: 'Minimum Longitude',
                        id: 'idx-q-LongitudeMin',
                        type: 'decimal',
                        mode: 'single',
                        hint: ''
                    },
                    {
                        label: 'Maximum Longitude',
                        id: 'idx-q-LongitudeMax',
                        type: 'decimal',
                        mode: 'single',
                        hint: ''
                    }
                ]
            },
            open_houses: {
                name: 'Open Houses (PRO users only)',
                help: 'Open House information available to dsIDXpress PRO users only.',
                fields: [
                    {
                        id: 'idx-q-OpenHouseRelativeTime',
                        label: 'Open House Time',
                        type: 'lookup',
                        mode: 'lookup',
                        hint: '-1',
                        options: {
                            'NowThroughEndOfToday': 'Now through the end of today',
                            'NowThroughEndOfWeekend': 'Now through the end of the weekend',
                            'ThisWeekend': 'This weekend',
                            'ThisWeek' : 'This week',

                        }
                    }
                ]
            },
            sort_columns: {
                name: 'Sorting',
                fields: [
                    {
                        id: 'idx-d-SortOrders__DSIDXINDEX__-Column',
                        label: 'Sort Column',
                        type: 'lookup',
                        mode: 'lookup',
                        hint: '0',
                        options: {
                            'Price': 'Price',
                            'DateAdded': 'Date Added',
                            'OverallPriceDropPercent': 'Overall Price Drop Percentage',
                            'WalkScore': 'Walk Score',
                            'ImprovedSqFt': 'Improved Square Footage',
                            'LotSqFt': 'Lot Square Footage'
                        }

                    },
                    {
                        id: 'idx-d-SortOrders__DSIDXINDEX__-Direction',
                        label: 'Sort Direction',
                        type: 'lookup',
                        mode: 'lookup',
                        hint: '0',
                        options: {
                            'ASC': 'Ascending',
                            'DESC': 'Descending'
                        }
                    }
                ]
            }
        },
        escapeHtmlEntities: function (text) {
            return text.replace(/[\u00A0-\u2666<>\&]/g, function (c) {
                return '&' + dsIdxLinkBuilder.entityTable[c.charCodeAt(0)] + ';' || '#' + c.charCodeAt(0) + ';';
            });
        },
        entityTable: { 34: 'quot', 38: 'amp', 39: 'apos', 60: 'lt', 62: 'gt', 160: 'nbsp', 161: 'iexcl', 162: 'cent', 163: 'pound', 164: 'curren', 165: 'yen', 166: 'brvbar', 167: 'sect', 168: 'uml', 169: 'copy', 170: 'ordf', 171: 'laquo', 172: 'not', 173: 'shy', 174: 'reg', 175: 'macr', 176: 'deg', 177: 'plusmn', 178: 'sup2', 179: 'sup3', 180: 'acute', 181: 'micro', 182: 'para', 183: 'middot', 184: 'cedil', 185: 'sup1', 186: 'ordm', 187: 'raquo', 188: 'frac14', 189: 'frac12', 190: 'frac34', 191: 'iquest', 192: 'Agrave', 193: 'Aacute', 194: 'Acirc', 195: 'Atilde', 196: 'Auml', 197: 'Aring', 198: 'AElig', 199: 'Ccedil', 200: 'Egrave', 201: 'Eacute', 202: 'Ecirc', 203: 'Euml', 204: 'Igrave', 205: 'Iacute', 206: 'Icirc', 207: 'Iuml', 208: 'ETH', 209: 'Ntilde', 210: 'Ograve', 211: 'Oacute', 212: 'Ocirc', 213: 'Otilde', 214: 'Ouml', 215: 'times', 216: 'Oslash', 217: 'Ugrave', 218: 'Uacute', 219: 'Ucirc', 220: 'Uuml', 221: 'Yacute', 222: 'THORN', 223: 'szlig', 224: 'agrave', 225: 'aacute', 226: 'acirc', 227: 'atilde', 228: 'auml', 229: 'aring', 230: 'aelig', 231: 'ccedil', 232: 'egrave', 233: 'eacute', 234: 'ecirc', 235: 'euml', 236: 'igrave', 237: 'iacute', 238: 'icirc', 239: 'iuml', 240: 'eth', 241: 'ntilde', 242: 'ograve', 243: 'oacute', 244: 'ocirc', 245: 'otilde', 246: 'ouml', 247: 'divide', 248: 'oslash', 249: 'ugrave', 250: 'uacute', 251: 'ucirc', 252: 'uuml', 253: 'yacute', 254: 'thorn', 255: 'yuml', 402: 'fnof', 913: 'Alpha', 914: 'Beta', 915: 'Gamma', 916: 'Delta', 917: 'Epsilon', 918: 'Zeta', 919: 'Eta', 920: 'Theta', 921: 'Iota', 922: 'Kappa', 923: 'Lambda', 924: 'Mu', 925: 'Nu', 926: 'Xi', 927: 'Omicron', 928: 'Pi', 929: 'Rho', 931: 'Sigma', 932: 'Tau', 933: 'Upsilon', 934: 'Phi', 935: 'Chi', 936: 'Psi', 937: 'Omega', 945: 'alpha', 946: 'beta', 947: 'gamma', 948: 'delta', 949: 'epsilon', 950: 'zeta', 951: 'eta', 952: 'theta', 953: 'iota', 954: 'kappa', 955: 'lambda', 956: 'mu', 957: 'nu', 958: 'xi', 959: 'omicron', 960: 'pi', 961: 'rho', 962: 'sigmaf', 963: 'sigma', 964: 'tau', 965: 'upsilon', 966: 'phi', 967: 'chi', 968: 'psi', 969: 'omega', 977: 'thetasym', 978: 'upsih', 982: 'piv', 8226: 'bull', 8230: 'hellip', 8242: 'prime', 8243: 'Prime', 8254: 'oline', 8260: 'frasl', 8472: 'weierp', 8465: 'image', 8476: 'real', 8482: 'trade', 8501: 'alefsym', 8592: 'larr', 8593: 'uarr', 8594: 'rarr', 8595: 'darr', 8596: 'harr', 8629: 'crarr', 8656: 'lArr', 8657: 'uArr', 8658: 'rArr', 8659: 'dArr', 8660: 'hArr', 8704: 'forall', 8706: 'part', 8707: 'exist', 8709: 'empty', 8711: 'nabla', 8712: 'isin', 8713: 'notin', 8715: 'ni', 8719: 'prod', 8721: 'sum', 8722: 'minus', 8727: 'lowast', 8730: 'radic', 8733: 'prop', 8734: 'infin', 8736: 'ang', 8743: 'and', 8744: 'or', 8745: 'cap', 8746: 'cup', 8747: 'int', 8756: 'there4', 8764: 'sim', 8773: 'cong', 8776: 'asymp', 8800: 'ne', 8801: 'equiv', 8804: 'le', 8805: 'ge', 8834: 'sub', 8835: 'sup', 8836: 'nsub', 8838: 'sube', 8839: 'supe', 8853: 'oplus', 8855: 'otimes', 8869: 'perp', 8901: 'sdot', 8968: 'lceil', 8969: 'rceil', 8970: 'lfloor', 8971: 'rfloor', 9001: 'lang', 9002: 'rang', 9674: 'loz', 9824: 'spades', 9827: 'clubs', 9829: 'hearts', 9830: 'diams', 34: 'quot', 38: 'amp', 60: 'lt', 62: 'gt', 338: 'OElig', 339: 'oelig', 352: 'Scaron', 353: 'scaron', 376: 'Yuml', 710: 'circ', 732: 'tilde', 8194: 'ensp', 8195: 'emsp', 8201: 'thinsp', 8204: 'zwnj', 8205: 'zwj', 8206: 'lrm', 8207: 'rlm', 8211: 'ndash', 8212: 'mdash', 8216: 'lsquo', 8217: 'rsquo', 8218: 'sbquo', 8220: 'ldquo', 8221: 'rdquo', 8222: 'bdquo', 8224: 'dagger', 8225: 'Dagger', 8240: 'permil', 8249: 'lsaquo', 8250: 'rsaquo', 8364: 'euro' },
        getQueryValue: function (url, name) {
            name = name.replace(/[\[]/, '\\\[').replace(/[\]]/, '\\\]');
            var regexS = '[\\?&]' + name + '=([^&#]*)';
            var regex = new RegExp(regexS);
            var results = regex.exec(url);
            if (results == null)
                return '';
            else
                return results[1].replace(/\+/g, ' ')
                //return decodeURIComponent(results[1].replace(/\+/g, ' ')); <-- The entire url has already been decoded. We don't need to decode the individual parts again. This breaks when % is actaully part of the value.
        }
    };
    $(document).ready(function() {
        dsIdxLinkBuilder.init();
    });
})(jQuery);
