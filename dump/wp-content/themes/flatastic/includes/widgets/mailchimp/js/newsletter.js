(function ($) {

	$('form.mailchimp-newsletter').on('submit', function (e) {
		e.preventDefault();

		var $this = $(this),
			$submit = $this.find('button[type=submit]'),
			$response = $this.find('p.response');

		$this.ajaxSubmit({
			type : 'POST',
			url	 : global.ajaxurl,
			data : {
				ajax_nonce : global.ajax_nonce,
				action : 'add_to_mailchimp_list'
			},
			timeout	: 10000,
			dataType: 'json',
			beforeSubmit: function () {
				$submit.block({
					message: null,
					overlayCSS: {
						background: '#fff url(' + global.ajax_loader_url + ') no-repeat center',
						backgroundSize: '16px 16px',
						opacity: 0.6
					}
				});
			},
			success	: function (responseText) {
				$response.replaceWith('<p class="response">' + responseText.text + '</p>');
				$submit.unblock();
				$this.trigger('reset');
			}
		});
	});

})(jQuery);