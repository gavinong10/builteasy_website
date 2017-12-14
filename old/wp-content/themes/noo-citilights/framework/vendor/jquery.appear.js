/*
 * jQuery.appear
 * https://github.com/bas2k/jquery.appear/
 * http://code.google.com/p/jquery-appear/
 *
 * Copyright (c) 2009 Michael Hixson
 * Copyright (c) 2012 Alexander Brovikov
 * Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php)
 */
(function(a){a.fn.appear=function(c,d){var b=a.extend({data:undefined,one:true,accX:0,accY:0},d);return this.each(function(){var g=a(this);g.appeared=false;if(!c){g.trigger("appear",b.data);return}var f=a(window);var e=function(){if(!g.is(":visible")){g.appeared=false;return}var l=f.scrollLeft();var i=f.scrollTop();var r=g.offset();var p=r.left;var q=r.top;var n=b.accX;var s=b.accY;var k=g.height();var t=f.height();var j=g.width();var m=f.width();if(q+k+s>=i&&q<=i+t+s&&p+j+n>=l&&p<=l+m+n){if(!g.appeared){g.trigger("appear",b.data)}}else{g.appeared=false}};var h=function(){g.appeared=true;if(b.one){f.unbind("scroll",e);var j=a.inArray(e,a.fn.appear.checks);if(j>=0){a.fn.appear.checks.splice(j,1)}}c.apply(this,arguments)};if(b.one){g.one("appear",b.data,h)}else{g.bind("appear",b.data,h)}f.scroll(e);a.fn.appear.checks.push(e);(e)()})};a.extend(a.fn.appear,{checks:[],timeout:null,checkAll:function(){var b=a.fn.appear.checks.length;if(b>0){while(b--){(a.fn.appear.checks[b])()}}},run:function(){if(a.fn.appear.timeout){clearTimeout(a.fn.appear.timeout)}a.fn.appear.timeout=setTimeout(a.fn.appear.checkAll,20)}});a.each(["append","prepend","after","before","attr","removeAttr","addClass","removeClass","toggleClass","remove","css","show","hide"],function(b,c){var d=a.fn[c];if(d){a.fn[c]=function(){var e=d.apply(this,arguments);a.fn.appear.run();return e}}})})(jQuery);
(function($){
	var animatedInit = function() {
		$('.animatedParent').appear(function(){
			var ele = $(this).find('.animated');
			var parent = $(this);
			ele.addClass('go');
		});
	};
	$(document).ready( function () {animatedInit();});	
	$(document).bind('noo-layout-changed',function(){animatedInit();});
})(jQuery);
