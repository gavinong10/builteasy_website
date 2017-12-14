(function ($) {

	$(function() {

		$(".mad-search").keyup(function () {
			var filter = $(this).val(), count = 0;
			$(".mad-icon-list li").each(function (){
				if ($(this).data('icon').search(new RegExp(filter, "i")) < 0) {
					$(this).fadeOut();
				} else {
					$(this).show();
					count++;
				}
			});
		});

		$("#mad-icon-dropdown li").on('click', function () {
			$(this).attr("class", "selected").siblings().removeAttr("class");
			var icon = $(this).attr("data-icon");
			$("#mad-trace").val(icon);
			$(".mad-icon-preview").html("<i class=\'fa fa-"+ icon +"\'></i>");
		});

	});

})(jQuery);