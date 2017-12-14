jQuery(document).ready(function($) {

    $( ".attributes .fields" ).sortable({
        cursor: "move",
        scrollSensitivity: 10,
        tolerance: "pointer",
        axis: "y",
        stop: function(event, ui) {
            var list = ui.item.parents('.fields'),
                fields = new Array();
            $('input[type="checkbox"]', list).each(function(i){
                fields[i] = $(this).val();
            });

            list.next().val( fields.join(',') );
        }
    });

});