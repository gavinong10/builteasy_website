// JavaScript Document
(function ( $ ) {
	$(document).ready(function(){
		$('.ult-easy-separator-wrapper').each(function(i,wr){
			var vc_row = $(this).attr('data-vc-row');
			var $row = $(this).parents('.'+vc_row+':first');
			$(this).appendTo($row).find('.ult-no-animation').css({'opacity':'1'});
		});
	});
}( jQuery ));