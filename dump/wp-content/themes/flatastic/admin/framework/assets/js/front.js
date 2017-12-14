(function ($) {

	$(function () {

		/*	Aside Admin Panel												  */
		/* ------------------------------------------------------------------ */

		(function(){

			$('.panel-button').on('click',function () {
				$(this).parent().toggleClass('opened').siblings().removeClass('opened');
			});

			if ($('#contactform').length) {

				var $form = $('#contactform');
					$form.append('<div class="contact-form-responce" />');

				$form.each(function () {

					var $this = $(this),
						$response = $('.contact-form-responce', $this).append('<p></p>');

					$this.submit(function () {

						$.ajax({
							type: "POST",
							url: global.ajaxurl,
							dataType: 'json',
							data: {
								action: 'send_contact_form',
								values: $this.serialize()
							},
							error: function (response) { },
							success: function (response) {

								var $text = $response.find('p').html('');

								if (response.status === 'error') {
									$.each(response.text, function (name, label) {
										$text.append(label + '</br>');
									});
									$response.removeClass('alert-success').addClass('alert-danger')
										.fadeIn(400)
										.delay(3000)
										.fadeOut(350);
								} else if (response.status === 'success') {
									$text.html('').html(response.text);
									$response.removeClass('alert-danger').addClass('alert-success')
										.fadeIn(400)
										.delay(3000)
										.fadeOut(350);
								}

								$this.trigger('reset');

							}

						});

						return false;

					});
				});

			}

		})();

	});

})(jQuery);