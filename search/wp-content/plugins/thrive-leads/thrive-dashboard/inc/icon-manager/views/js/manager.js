(function ($) {

    $(function () {
        var $upload = $('#tve_icon_pack_upload'),
            $remove = $('#tve_icon_pack_remove'),
            $input = $('#tve_icon_pack_file'),
            $input_id = $('#tve_icon_pack_file_id'),
            $save = $('#tve_icon_pack_save'),
            initial_value = $input.val(),
            wp_file_frame = null;

        $input.focus(function () {
            $upload.click();
        });

        $upload.on('click', function (e) {
            e.preventDefault();
            if (wp_file_frame) {
                wp_file_frame.open();
                return false;
            }

            wp_file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Upload IcoMoon Font Pack',
                button: {
                    text: 'Use file'
                },
                multiple: false
            });
            wp_file_frame.on('select', function() {
                var attachment = wp_file_frame.state().get('selection').first().toJSON();
                $input_id.val(attachment.id);
                $input.val(attachment.filename);
            });
            wp_file_frame.open();

            return false;
        });

        $remove.on('click', function () {
            $input.val('');
            $input_id.val('');
        });

        var $redirect_to = $('#tve-redirect-to'),
            $redirect_in = $('#tve-redirect-count');

        if ($redirect_in.length && $redirect_to.length) {
            var interval = setInterval(function () {
                var _current = parseInt($redirect_in.text());
                _current--;
                $redirect_in.html(_current + '');
                if (_current == 0) {
                    clearInterval(interval);
                    location.href = $redirect_to.val();
                }
            }, 1000);
        }

    });

})(jQuery);
