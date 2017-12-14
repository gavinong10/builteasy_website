var showErrorMessage = function(text) {
    var msg = '<div class="alert alert-error" id="set-err"><a class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> &nbsp;' + text + '</div>';
    jQLP('.msg-space').html(msg);
    jQLP('#set-err').hide().slideDown(300).delay(6000).slideUp(300);
    jQLP('.modal').modal('hide');
};
var showSuccessMessage = function(text, permanent) {
    if (text === '') return;
    var msg = '<div class="alert alert-success" id="set-save"><a class="close" data-dismiss="alert">&times;</a>' + text + '</div>';
    jQLP('.msg-space').html(msg);
    if (typeof(permanent) != 'undefined' && permanent === true) return;
    jQLP('#set-save').hide().slideDown(300).delay(6000).slideUp(300);
};
var reqFail = function() { showErrorMessage('Operation failed &hellip; please check your connection and try again.'); };
var serverFail = function() { showErrorMessage('Server error. If problem persist, please contact support.'); };

// general form controls
var form_controls = function() {

};

jQLP(document).ready(function() {

    // is this main page?
    if (!(typeof(leadpages_post_type_area) != 'undefined' && leadpages_post_type_area === true)) {
        return;
    }

    jQLP('.leadpages-help-ico').popover({'placement': 'right'});
    jQLP('.leadpages-warn-ico').popover({'placement': 'right'});

    function toggle_subsec(elem, active) {
        var sub = elem.attr('data-subsection');
        elem.closest('form').find(".subsection_" + sub).css('display', active ? 'block' : 'none' );
    }

    // multiple button choice
    var update_multichoice = function(value, target) {
        target.children().removeClass('active btn-success btn-primary btn-info btn-warning');
        var inpt = target.attr('data-target');
        var cls;
        var enabled;
        var tgt = target.find('.btn[data-value=' + value + ']');
        var dt = jQLP('#wg-options');
        switch (value) {
            case 'lp':
                cls = 'btn-success';
                enabled = true;
                dt.hide();
                break;
            case 'fp':
                cls = 'btn-primary';
                enabled = false;
                dt.hide();
                break;
            case 'nf':
                cls = 'btn-warning';
                enabled = false;
                dt.hide();
                break;
            case 'wg':
                cls = 'btn-info';
                enabled = true;
                dt.show();
                break;
            case 'http':
                cls = 'btn-info';
                break;
            case 'js':
                cls = 'btn-info';
                break;
        }
        target.find(tgt).addClass('active ' + cls);
        target.parent().find('.' + inpt).val(value);
        if (target.hasClass('subsection'))
            toggle_subsec(target, enabled);
    };

    jQLP('.btn-group.multichoice').each(function() {
        var value = jQLP(this).closest('.control-group').find('input[name="' + jQLP(this).attr('data-target') + '"]').val();
        update_multichoice(value, jQLP(this));
    });

    // multiple buttons click actions
    jQLP('.btn-group.multichoice button').click(function() {
        var slf = jQLP(this);
        var value = slf.attr('data-value');
        update_multichoice(value, slf.parent());
        return false;
    });

    // multiple buttons
    jQLP('.btn-group.but-nums button').click(function() {
        var slf = jQLP(this);
        var mum = slf.parent();
        mum.children().removeClass('active');
        slf.addClass('active');
        var val = slf.attr('value');
        jQLP(this).closest('form').find('input[name=' + mum.attr('data-target')+']').removeAttr('checked');
        jQLP(this).closest('form').find('input[name=' + mum.attr('data-target')+'][value=' + val + ']').attr('checked', 'checked');
        return false;
    });

});