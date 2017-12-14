(function($) {
  $(document).ready(function(){

	var gpi_check_status = $('#gpi_status_ajax');

	if(gpi_check_status.length > 0) {
		$.fn.gpiCheckStatus = function() {
			$.post(
				GPI_Ajax.ajaxurl,
				{
					// wp ajax action
					action : 'gpi_check_status',

					// send the nonce along with the request
					gpiNonce : GPI_Ajax.gpiNonce
				},
				function( response ) {
					if(response == 'nonce_failure') {
						return;
					}
					if(response == 'done') {
						gpi_check_status.hide();
						$('#gpi_status_finished').show();
						clearInterval(gpi_interval_id);
					} else {
						gpi_check_status.html('<div class="loading_bar_shell"><div class="reportscore_outter_bar"><div class="reportscore_inner_bar" style="width:' + response + '%;"></div></div><span>' + response + '%</span></div>');
					}
				}
			);
			return false;
		};
		var gpi_interval_id = setInterval(function() {
			gpi_check_status.gpiCheckStatus();
		}, 2000);
	}

  });
})(jQuery);