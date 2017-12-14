function fill_selects(data) {
    var timed_select = jQuery('#select_timed');
    var exit_select = jQuery('#select_exit');
    var data_array = data.data;
    for (var i = 0; i < data_array.length; i++) {
        var lb = data_array[i];
        var this_option = jQuery('<option />');
        this_option.attr({'value': lb.id}).text(lb.name);
        var timed_option = this_option.clone();
        timed_option.data('lb', lb);
        this_option.data('lb', lb);
        if (lb.id == selected_timed) {
            timed_option.prop('selected', true);
        }
        if (lb.id == selected_exit) {
            this_option.prop('selected', true);
        }
        var publish_settings = JSON.parse(lb.publish_settings);
        console.log('pubset', publish_settings);
        if (publish_settings && publish_settings.hasOwnProperty('time') && publish_settings.time.seconds) {
            timed_select.append(timed_option);
        }
        exit_select.append(this_option);
    }
    timed_select.change(function(){select_changed('timed', this)});
    exit_select.change(function(){select_changed('exit', this)});
    show_stats(timed_select.find('option:selected'), 'timed');
    show_stats(exit_select.find('option:selected'), 'exit');
    jQuery('#leadbox-loading').hide();
    jQuery('#leadbox-options').show();

    fixDisplay();
}
function select_changed(lb_type, self) {
    var submit_button = jQuery('#submit');
    submit_button.prop("disabled", true);
    var selected = jQuery(self).find('option:selected');
    show_stats(selected, lb_type);
    submit_button.prop("disabled", false);
}

function fixDisplay() {
    // Hide the empty rows
    jQuery('tr td input[type="hidden"]').parent().parent().hide();

}
function stat_row(label, value) {
    return '<div class="row-fluid">' +
    '<div class="span3">' + label + '</div>' +
    '<div class="span4">' + value + '</div>' +
    '</div>';
}

function disable_radio(lb_type) {
    jQuery('input[id*='+lb_type+'_radio_]').attr('disabled','disabled');
    jQuery('label[for*='+lb_type+'_radio_]').css({'opacity': 0.6});
}
function enable_radio(lb_type) {
    jQuery('input[id*='+lb_type+'_radio_]').removeAttr('disabled');
    jQuery('label[for*='+lb_type+'_radio_]').css({'opacity': 1});
}

function show_stats(element, lb_type) {
    var lb = element.data('lb');
    jQuery('#' + lb_type + '_stats').remove();
    if (!lb) {
        disable_radio(lb_type);
        return;
    }
    enable_radio(lb_type);
    var publish_settings = JSON.parse(lb.publish_settings);
    //console.log('pubset:', publish_settings);
    var new_data = jQuery('<div id="'+lb_type+'_stats" class="bootstrap-wpadmin" />');

    var detail_start = '<div id="'+lb_type+'_details" class="container-fluid"><div class="row-fluid settings-title">';
    var detail_end = '<div class="span4"><a href="https://my.leadpages.net">Go to LeadPages to change</a></div></div>';
    var setup_message = '<p>Your LeadBox&trade; appears to not be set up completely. Your LeadBox&trade; will use default ' +
        'settings. Please ' +
        '<a href="https://my.leadpages.net">go to LeadPages</a> to save the desired LeadBox configuration.';
    if (lb_type === 'timed'){
        if (publish_settings && publish_settings.hasOwnProperty('time') && publish_settings.time.seconds) {
            new_data.html(
                detail_start +
                '<div class="span8"><h4>Timed LeadBox&trade; Pop-Up Settings (from publish settings)</h4></div>' +
                detail_end +
                stat_row("Time before it appears:", publish_settings.time.seconds + ' seconds') +
                stat_row("Page views before it appears:", publish_settings.time.views + ' views') +
                stat_row("Don't reshow for the next ", publish_settings.time.days + ' days') +
                '</div>');
        } else {
            new_data.html(setup_message);
        }
    } else if (lb_type === 'exit') {
        if (!publish_settings || !publish_settings.hasOwnProperty('exit')) {
            new_data.html(setup_message);
        } else {
            new_data.html(
                detail_start +
                '<div class="span8"><h4>Exit LeadBox&trade; Pop-Up Settings (from publish settings)</h4></div>' +
                detail_end +
                stat_row("Don't reshow for the next ", publish_settings.exit.days + ' days') +
                '</div>');
        }
    }

    jQuery('#select_' + lb_type).parent().parent().parent().parent().after(new_data);
}

function get_leadboxes() {
    gapi.client.leadpages.leadbox.getLeadboxes(
        {'api_key': api_key}
    ).execute(function (resp) {
            jQuery(document).ready(fill_selects(resp))
        });
}
function init() {
    gapi.client.load('leadpages', 'v1.0', function () {
        get_leadboxes();
    }, API_URL);
}
jQLP.ajax({
    timeout: 5000,
    url: '//apis.google.com/js/client.js?onload=init',
    dataType: "script",
    cache: true,
    success: function(scr, st, jqxhr) { console.log("Loaded Google API client:", st);},
    error: function(jqxhr, st, error) {
        jQLP('#leadbox-loading').before('<div class="bootstrap-wpadmin"><p class="alert-error">Could not load the Google API client. Please check for relative root or other ' +
        'script tag modifying plugins that might conflict with this.</p></div>');
        jQLP('#leadbox-loading').hide();
    }
});
