(function($){
	$(document).ready(function(){
		$(".ult-carousel-wrapper").each(function(){
			var $this = $(this);
			if($this.hasClass("ult_full_width")){
				var rtl = $this.attr('data-rtl');
				var w = $("html").outerWidth();
				var cw = $this.width();
				var left = (w - cw)/2;
				if(rtl === 'true' || rtl === true)
					$this.css({"position":"relative","right":"-"+left+"px","width":w+"px"});
				else
					$this.css({"position":"relative","left":"-"+left+"px","width":w+"px"});
			}
		});
		$('.ult-carousel-wrapper').each(function(i,carousel) {
			var gutter = $(carousel).data('gutter');
			var id = $(carousel).attr('id');
			if(gutter != '')
			{
				var css = '<style>#'+id+' .slick-slide { margin:0 '+gutter+'px; } </style>';
				$('head').append(css);
			}
		});
		
		$(window).resize(function(){
			$(".ult-carousel-wrapper").each(function(){
				var $this = $(this);
				if($this.hasClass("ult_full_width")){
					var rtl = $this.attr('data-rtl');
					$this.removeAttr("style");
					var w = $("html").outerWidth();
					var cw = $this.width();
					var left = (w - cw)/2;
					if(rtl === 'true' || rtl === true)
						$this.css({"position":"relative","right":"-"+left+"px","width":w+"px"});
					else
						$this.css({"position":"relative","left":"-"+left+"px","width":w+"px"});
				}
			});
		});
	});
})(jQuery);