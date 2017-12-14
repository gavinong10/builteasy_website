(function ($) {

	$.mad_media_uploader = {

		uploader: function () {

			$('.uploader-upload-button').on('click', function (e) {
				e.preventDefault();

				var $this = $(this),
					title  = $this.attr('title'),
					parent = $this.parent('.upload-wrap'),
					input = parent.children('input:text'),
					thumbnail = parent.children('.preview-thumbnail-container');

				tb_show( title, 'media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true');

				window.send_to_editor = function (html) {
					var url = $('img', html).attr('src'), insert;
						input.val(url);
					insert = '<a href="#" class="uploader-remove-preview">Remove</a><img src="'+ url +'" />';
					thumbnail.html('').html(insert);
					tb_remove();
				}
			});

			$('.uploader-config-button').on('click', function (e) {
				e.preventDefault();

				var title  = 'Upload config file',
					data = $(this).data();

				tb_show( title, 'media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true');

				window.send_to_editor = function (html) {
					var href = $(html).attr('href');
						data.href = href;

					if (href.match(".txt")) {
						$.ajax({
							type: "POST",
							url: data.url,
							data: data,
							beforeSend: function () { },
							error: function () {
								window.location.reload(true);
							},
							success: function (response) {
								if (response.match('madImportConfig')) {
									response = response.replace('madImportConfig', '');

									$('#mad-options-page').frameworkPopup({
										message: madLocalize.importsuccessOptions,
										add_class: 'mad-message-success'
									}, function () {
										window.location.reload(true);
									});

								}
							},
							complete: function () {
								tb_remove();
							}
						});
					} else {
						tb_remove();
					}
				}
			});

			$('.uploader-remove-preview').on('click', function (e) {
				e.preventDefault();

				var $this = $(this),
					parent = $this.parents('.upload-wrap'),
					input = parent.children('input:text'),
					thumbnail = parent.children('.preview-thumbnail-container');
					input.val('');
					thumbnail.slideUp(300);
			});

		}

	}

	$(function () {
		$.mad_media_uploader.uploader();
	});

})(jQuery);