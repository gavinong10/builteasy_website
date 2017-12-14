jQuery(document).ready(function($){

	//svg list
	var svg_imgs = ['audio','ball-triangle','bars','circles','grid','hearts','oval','puff','rings','spinning-circles','tail-spin','three-dots'],
		svg_list = "";

	for (var i = 0; i < 12; i++) {
		svg_list += '<div class="img-con"><img src="'+svg_url+svg_imgs[i]+'.svg" spinner="'+svg_imgs[i]+'"></div>';
	}
	$('.svg-img-list').html(svg_list+'<div class="clear"></div>');


	//gif list
	var gif_list = "";

	for (var i = 1; i < 29; i++) {
		gif_list += '<div class="img-con"><img src="'+gif_url+'Preloader_'+i+'.gif'+'" spinner="Preloader_'+i+'"></div>';
	}
	$('.gif-img-list').html(gif_list+'<div class="clear"></div>');


	//css list
	var css3_loaders = [
		{count:16,cls:'la-ball-8bits'},
		{count:4, cls:'la-ball-atom'},
		{count:3, cls:'la-ball-beat'},
		{count:5, cls:'la-ball-circus'},
		{count:4, cls:'la-ball-climbing-dot'},
		{count:1, cls:'la-ball-clip-rotate'},
		{count:2, cls:'la-ball-clip-rotate-multiple'},
		{count:2, cls:'la-ball-clip-rotate-pulse'},
		{count:5, cls:'la-ball-elastic-dots la-sm'},
		{count:3, cls:'la-ball-fall'},
		{count:4, cls:'la-ball-fussion'},
		{count:9, cls:'la-ball-grid-beat'},
		{count:9, cls:'la-ball-grid-pulse'},
		{count:4, cls:'la-ball-newton-cradle'},
		{count:3, cls:'la-ball-pulse'},
		{count:5, cls:'la-ball-pulse-rise'},
		{count:3, cls:'la-ball-pulse-sync"'},
		{count:1, cls:'la-ball-rotate'},
		{count:5, cls:'la-ball-running-dots'},
		{count:1, cls:'la-ball-scale'},
		{count:3, cls:'la-ball-scale-multiple'},
		{count:2, cls:'la-ball-scale-pulse'},
		{count:1, cls:'la-ball-scale-ripple'},
		{count:3, cls:'la-ball-scale-ripple-multiple'},
		{count:8, cls:'la-ball-spin'},
		{count:8, cls:'la-ball-spin-clockwise'},
		{count:8, cls:'la-ball-spin-clockwise-fade'},
		{count:8, cls:'la-ball-spin-clockwise-fade-rotating'},
		{count:8, cls:'la-ball-spin-fade'},
		{count:8, cls:'la-ball-spin-fade-rotating'},
		{count:2, cls:'la-ball-spin-rotate'},
		{count:8, cls:'la-ball-square-clockwise-spin'},
		{count:8, cls:'la-ball-square-spin'},
		{count:3, cls:'la-ball-triangle-path'},
		{count:2, cls:'la-ball-zig-zag'},
		{count:2, cls:'la-ball-zig-zag-deflect'},
		{count:1, cls:'la-cog'},
		{count:2, cls:'la-cube-transition'},
		{count:3, cls:'la-fire'},
		{count:5, cls:'la-line-scale'},
		{count:5, cls:'la-line-scale-party'},
		{count:5, cls:'la-line-scale-pulse-out'},
		{count:5, cls:'la-line-scale-pulse-out-rapid'},
		{count:8, cls:'la-line-spin-clockwise-fade'},
		{count:8, cls:'la-line-spin-clockwise-fade-rotating'},
		{count:8, cls:'la-line-spin-fade'},
		{count:8, cls:'la-line-spin-fade-rotating'},
		{count:6, cls:'la-pacman'},
		{count:2, cls:'la-square-jelly-box'},
		{count:1, cls:'la-square-loader'},
		{count:1, cls:'la-square-spin'},
		{count:1, cls:'la-timer'},
		{count:1, cls:'la-triangle-skew-spin'}
	];

	var css3_list = "";

	for (var i = 0; i < css3_loaders.length; i++) {
		var divs = "", ctr = 0;
		while(ctr < css3_loaders[i]['count']){
			divs += '<div></div>'; 
			ctr++;
		}
		css3_list += '<div class="img-con"><div spinner="'+css3_loaders[i]['cls'].replace('la-','')+'" class="'+css3_loaders[i]['cls']+'">'+divs+'</div></div>';
	}
	$('.css3-loader-list').html(css3_list+'<div class="clear"></div>');

});