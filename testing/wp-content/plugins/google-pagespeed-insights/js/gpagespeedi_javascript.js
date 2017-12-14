jQuery(document).ready(function($) {

	$(".impact_chart_right a").hover(
		function () {
			$(this).stop().animate({
				textIndent: "-" + ( $(this).width() - $(this).parent().width() ) + "px"  
			}, 1000);  
		}, 
		function () {
			$(this).stop().animate({
				textIndent: "0"           
			}, 1000);  
		}
	);

	$(".boxheader.large.toggle").on('click', function() {
		$(this).find('span.right').toggleClass('open');
		$(this).next('.padded').toggle();
	});

});