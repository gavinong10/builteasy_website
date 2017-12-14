(function ($) {
    $(function () {
        var $upload = $("#thrive_upload"),
            $remove = $("#thrive_remove"),
            wp_file_frame = null,
            $input = $("#thrive_attachment_name"),
            $input_id = $("#thrive_attachment_id");

        $upload.click(function (event) {
            event.preventDefault();

            if (wp_file_frame) {
                wp_file_frame.open();
                return false;
            }
            wp_file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Upload .zip fonts files',
                button: {
                    text: 'Use file'
                },
                multiple: false
            });
            wp_file_frame.on("select", function () {
                var attachment = wp_file_frame.state().get('selection').first().toJSON();
                $input.val(attachment.filename);
                $input_id.val(attachment.id);
            });
            wp_file_frame.open();
        });

        $remove.click(function () {
            $input.val('');
            $input_id.val(-1);
        });
    });
})(jQuery);
