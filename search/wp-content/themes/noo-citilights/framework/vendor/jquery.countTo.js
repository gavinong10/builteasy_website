
(function(a){a.fn.countTo=function(c){c=c||{};return a(this).each(function(){var g=a.extend({},a.fn.countTo.defaults,{from:a(this).data("from"),to:a(this).data("to"),speed:a(this).data("speed"),refreshInterval:a(this).data("refresh-interval"),decimals:a(this).data("decimals")},c);var l=Math.ceil(g.speed/g.refreshInterval),i=(g.to-g.from)/l;var e=this,j=a(this),d=0,k=g.from,m=j.data("countTo")||{};j.data("countTo",m);if(m.interval){clearInterval(m.interval)}m.interval=setInterval(h,g.refreshInterval);f(k);function h(){k+=i;d++;f(k);if(typeof(g.onUpdate)=="function"){g.onUpdate.call(e,k)}if(d>=l){j.removeData("countTo");clearInterval(m.interval);k=g.to;if(typeof(g.onComplete)=="function"){g.onComplete.call(e,k)}}}function f(n){var o=g.formatter.call(e,n,g);j.text(o)}})};a.fn.countTo.defaults={from:0,to:0,speed:1000,refreshInterval:100,decimals:0,formatter:b,onUpdate:null,onComplete:null};function b(c,d){return c.toFixed(d.decimals)}}(jQuery));

jQuery(document).ready(function($) {
	if($('.noo-counter').length){
		$('.noo-counter').appear( function() {
			$this = $(this);
			if( !$this.hasClass('executed') ){
				$this.parent().css('opacity', '1');
				var $max = parseFloat($this.text());
				$this.countTo({
					from: 0,
					to: $max,
					speed: 1500,
					refreshInterval: 100
				});

				$this.addClass('executed');
			}	
		});
	}
		
	//Progress
	$('.progress').each(function(i){
		var progress_bar = $(this).find('.progress-bar');
		progress_bar.appear(function(){
			
			var percent = $(this).data('valuenow');
			var $endNum = parseInt($(this).find('.progress_label > span').text());
			var $that = $(this);
			
			$(this).css({'width':0+'%'}).animate({
				'width' : percent + '%'
			},1600,'easeOutCirc',function(){});
			
			$(this).find('.progress_label').animate({
				'opacity' : 1
			},1350);

			
			$(this).find('.progress_label > span').countTo({
				from: 0,
				to: $endNum,
				speed: 1100,
				refreshInterval: 30,
				onComplete: function(){
		
				}
			});	
			
			////100% progress bar 
			if(percent == '100'){
				$that.addClass('full');
			}
	
		});

	});
});
