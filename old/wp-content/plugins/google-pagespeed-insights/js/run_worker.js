(function($) {
	function gpiRunWorker() {
		$.post(
			GPI_WorkerAjax.ajaxurl,
			{
				// wp ajax action
				action : 'gpi_run_worker_service',

				// send the nonce along with the request
				gpiNonce : GPI_WorkerAjax.gpiNonce,

				// force recheck?
				recheck : GPI_WorkerAjax.recheck

			},
			function( response ) {
				return;
			}
		);
	}
	gpiRunWorker();

})(jQuery);