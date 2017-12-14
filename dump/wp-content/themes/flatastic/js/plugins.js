/*!
 * imagesLoaded PACKAGED v3.1.6
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

(function(){function e(){}function t(e,t){for(var n=e.length;n--;)if(e[n].listener===t)return n;return-1}function n(e){return function(){return this[e].apply(this,arguments)}}var i=e.prototype,r=this,o=r.EventEmitter;i.getListeners=function(e){var t,n,i=this._getEvents();if("object"==typeof e){t={};for(n in i)i.hasOwnProperty(n)&&e.test(n)&&(t[n]=i[n])}else t=i[e]||(i[e]=[]);return t},i.flattenListeners=function(e){var t,n=[];for(t=0;e.length>t;t+=1)n.push(e[t].listener);return n},i.getListenersAsObject=function(e){var t,n=this.getListeners(e);return n instanceof Array&&(t={},t[e]=n),t||n},i.addListener=function(e,n){var i,r=this.getListenersAsObject(e),o="object"==typeof n;for(i in r)r.hasOwnProperty(i)&&-1===t(r[i],n)&&r[i].push(o?n:{listener:n,once:!1});return this},i.on=n("addListener"),i.addOnceListener=function(e,t){return this.addListener(e,{listener:t,once:!0})},i.once=n("addOnceListener"),i.defineEvent=function(e){return this.getListeners(e),this},i.defineEvents=function(e){for(var t=0;e.length>t;t+=1)this.defineEvent(e[t]);return this},i.removeListener=function(e,n){var i,r,o=this.getListenersAsObject(e);for(r in o)o.hasOwnProperty(r)&&(i=t(o[r],n),-1!==i&&o[r].splice(i,1));return this},i.off=n("removeListener"),i.addListeners=function(e,t){return this.manipulateListeners(!1,e,t)},i.removeListeners=function(e,t){return this.manipulateListeners(!0,e,t)},i.manipulateListeners=function(e,t,n){var i,r,o=e?this.removeListener:this.addListener,s=e?this.removeListeners:this.addListeners;if("object"!=typeof t||t instanceof RegExp)for(i=n.length;i--;)o.call(this,t,n[i]);else for(i in t)t.hasOwnProperty(i)&&(r=t[i])&&("function"==typeof r?o.call(this,i,r):s.call(this,i,r));return this},i.removeEvent=function(e){var t,n=typeof e,i=this._getEvents();if("string"===n)delete i[e];else if("object"===n)for(t in i)i.hasOwnProperty(t)&&e.test(t)&&delete i[t];else delete this._events;return this},i.removeAllListeners=n("removeEvent"),i.emitEvent=function(e,t){var n,i,r,o,s=this.getListenersAsObject(e);for(r in s)if(s.hasOwnProperty(r))for(i=s[r].length;i--;)n=s[r][i],n.once===!0&&this.removeListener(e,n.listener),o=n.listener.apply(this,t||[]),o===this._getOnceReturnValue()&&this.removeListener(e,n.listener);return this},i.trigger=n("emitEvent"),i.emit=function(e){var t=Array.prototype.slice.call(arguments,1);return this.emitEvent(e,t)},i.setOnceReturnValue=function(e){return this._onceReturnValue=e,this},i._getOnceReturnValue=function(){return this.hasOwnProperty("_onceReturnValue")?this._onceReturnValue:!0},i._getEvents=function(){return this._events||(this._events={})},e.noConflict=function(){return r.EventEmitter=o,e},"function"==typeof define&&define.amd?define("eventEmitter/EventEmitter",[],function(){return e}):"object"==typeof module&&module.exports?module.exports=e:this.EventEmitter=e}).call(this),function(e){function t(t){var n=e.event;return n.target=n.target||n.srcElement||t,n}var n=document.documentElement,i=function(){};n.addEventListener?i=function(e,t,n){e.addEventListener(t,n,!1)}:n.attachEvent&&(i=function(e,n,i){e[n+i]=i.handleEvent?function(){var n=t(e);i.handleEvent.call(i,n)}:function(){var n=t(e);i.call(e,n)},e.attachEvent("on"+n,e[n+i])});var r=function(){};n.removeEventListener?r=function(e,t,n){e.removeEventListener(t,n,!1)}:n.detachEvent&&(r=function(e,t,n){e.detachEvent("on"+t,e[t+n]);try{delete e[t+n]}catch(i){e[t+n]=void 0}});var o={bind:i,unbind:r};"function"==typeof define&&define.amd?define("eventie/eventie",o):e.eventie=o}(this),function(e,t){"function"==typeof define&&define.amd?define(["eventEmitter/EventEmitter","eventie/eventie"],function(n,i){return t(e,n,i)}):"object"==typeof exports?module.exports=t(e,require("eventEmitter"),require("eventie")):e.imagesLoaded=t(e,e.EventEmitter,e.eventie)}(this,function(e,t,n){function i(e,t){for(var n in t)e[n]=t[n];return e}function r(e){return"[object Array]"===d.call(e)}function o(e){var t=[];if(r(e))t=e;else if("number"==typeof e.length)for(var n=0,i=e.length;i>n;n++)t.push(e[n]);else t.push(e);return t}function s(e,t,n){if(!(this instanceof s))return new s(e,t);"string"==typeof e&&(e=document.querySelectorAll(e)),this.elements=o(e),this.options=i({},this.options),"function"==typeof t?n=t:i(this.options,t),n&&this.on("always",n),this.getImages(),a&&(this.jqDeferred=new a.Deferred);var r=this;setTimeout(function(){r.check()})}function c(e){this.img=e}function f(e){this.src=e,v[e]=this}var a=e.jQuery,u=e.console,h=u!==void 0,d=Object.prototype.toString;s.prototype=new t,s.prototype.options={},s.prototype.getImages=function(){this.images=[];for(var e=0,t=this.elements.length;t>e;e++){var n=this.elements[e];"IMG"===n.nodeName&&this.addImage(n);var i=n.nodeType;if(i&&(1===i||9===i||11===i))for(var r=n.querySelectorAll("img"),o=0,s=r.length;s>o;o++){var c=r[o];this.addImage(c)}}},s.prototype.addImage=function(e){var t=new c(e);this.images.push(t)},s.prototype.check=function(){function e(e,r){return t.options.debug&&h&&u.log("confirm",e,r),t.progress(e),n++,n===i&&t.complete(),!0}var t=this,n=0,i=this.images.length;if(this.hasAnyBroken=!1,!i)return this.complete(),void 0;for(var r=0;i>r;r++){var o=this.images[r];o.on("confirm",e),o.check()}},s.prototype.progress=function(e){this.hasAnyBroken=this.hasAnyBroken||!e.isLoaded;var t=this;setTimeout(function(){t.emit("progress",t,e),t.jqDeferred&&t.jqDeferred.notify&&t.jqDeferred.notify(t,e)})},s.prototype.complete=function(){var e=this.hasAnyBroken?"fail":"done";this.isComplete=!0;var t=this;setTimeout(function(){if(t.emit(e,t),t.emit("always",t),t.jqDeferred){var n=t.hasAnyBroken?"reject":"resolve";t.jqDeferred[n](t)}})},a&&(a.fn.imagesLoaded=function(e,t){var n=new s(this,e,t);return n.jqDeferred.promise(a(this))}),c.prototype=new t,c.prototype.check=function(){var e=v[this.img.src]||new f(this.img.src);if(e.isConfirmed)return this.confirm(e.isLoaded,"cached was confirmed"),void 0;if(this.img.complete&&void 0!==this.img.naturalWidth)return this.confirm(0!==this.img.naturalWidth,"naturalWidth"),void 0;var t=this;e.on("confirm",function(e,n){return t.confirm(e.isLoaded,n),!0}),e.check()},c.prototype.confirm=function(e,t){this.isLoaded=e,this.emit("confirm",this,t)};var v={};return f.prototype=new t,f.prototype.check=function(){if(!this.isChecked){var e=new Image;n.bind(e,"load",this),n.bind(e,"error",this),e.src=this.src,this.isChecked=!0}},f.prototype.handleEvent=function(e){var t="on"+e.type;this[t]&&this[t](e)},f.prototype.onload=function(e){this.confirm(!0,"onload"),this.unbindProxyEvents(e)},f.prototype.onerror=function(e){this.confirm(!1,"onerror"),this.unbindProxyEvents(e)},f.prototype.confirm=function(e,t){this.isConfirmed=!0,this.isLoaded=e,this.emit("confirm",this,t)},f.prototype.unbindProxyEvents=function(e){n.unbind(e.target,"load",this),n.unbind(e.target,"error",this)},s});

// Generated by CoffeeScript 1.6.2
/*
 jQuery Waypoints - v2.0.2
 Copyright (c) 2011-2013 Caleb Troughton
 Dual licensed under the MIT license and GPL license.
 https://github.com/imakewebthings/jquery-waypoints/blob/master/licenses.txt
 */
(function(){var t=[].indexOf||function(t){for(var e=0,n=this.length;e<n;e++){if(e in this&&this[e]===t)return e}return-1},e=[].slice;(function(t,e){if(typeof define==="function"&&define.amd){return define("waypoints",["jquery"],function(n){return e(n,t)})}else{return e(t.jQuery,t)}})(this,function(n,r){var i,o,l,s,f,u,a,c,h,d,p,y,v,w,g,m;i=n(r);c=t.call(r,"ontouchstart")>=0;s={horizontal:{},vertical:{}};f=1;a={};u="waypoints-context-id";p="resize.waypoints";y="scroll.waypoints";v=1;w="waypoints-waypoint-ids";g="waypoint";m="waypoints";o=function(){function t(t){var e=this;this.$element=t;this.element=t[0];this.didResize=false;this.didScroll=false;this.id="context"+f++;this.oldScroll={x:t.scrollLeft(),y:t.scrollTop()};this.waypoints={horizontal:{},vertical:{}};t.data(u,this.id);a[this.id]=this;t.bind(y,function(){var t;if(!(e.didScroll||c)){e.didScroll=true;t=function(){e.doScroll();return e.didScroll=false};return r.setTimeout(t,n[m].settings.scrollThrottle)}});t.bind(p,function(){var t;if(!e.didResize){e.didResize=true;t=function(){n[m]("refresh");return e.didResize=false};return r.setTimeout(t,n[m].settings.resizeThrottle)}})}t.prototype.doScroll=function(){var t,e=this;t={horizontal:{newScroll:this.$element.scrollLeft(),oldScroll:this.oldScroll.x,forward:"right",backward:"left"},vertical:{newScroll:this.$element.scrollTop(),oldScroll:this.oldScroll.y,forward:"down",backward:"up"}};if(c&&(!t.vertical.oldScroll||!t.vertical.newScroll)){n[m]("refresh")}n.each(t,function(t,r){var i,o,l;l=[];o=r.newScroll>r.oldScroll;i=o?r.forward:r.backward;n.each(e.waypoints[t],function(t,e){var n,i;if(r.oldScroll<(n=e.offset)&&n<=r.newScroll){return l.push(e)}else if(r.newScroll<(i=e.offset)&&i<=r.oldScroll){return l.push(e)}});l.sort(function(t,e){return t.offset-e.offset});if(!o){l.reverse()}return n.each(l,function(t,e){if(e.options.continuous||t===l.length-1){return e.trigger([i])}})});return this.oldScroll={x:t.horizontal.newScroll,y:t.vertical.newScroll}};t.prototype.refresh=function(){var t,e,r,i=this;r=n.isWindow(this.element);e=this.$element.offset();this.doScroll();t={horizontal:{contextOffset:r?0:e.left,contextScroll:r?0:this.oldScroll.x,contextDimension:this.$element.width(),oldScroll:this.oldScroll.x,forward:"right",backward:"left",offsetProp:"left"},vertical:{contextOffset:r?0:e.top,contextScroll:r?0:this.oldScroll.y,contextDimension:r?n[m]("viewportHeight"):this.$element.height(),oldScroll:this.oldScroll.y,forward:"down",backward:"up",offsetProp:"top"}};return n.each(t,function(t,e){return n.each(i.waypoints[t],function(t,r){var i,o,l,s,f;i=r.options.offset;l=r.offset;o=n.isWindow(r.element)?0:r.$element.offset()[e.offsetProp];if(n.isFunction(i)){i=i.apply(r.element)}else if(typeof i==="string"){i=parseFloat(i);if(r.options.offset.indexOf("%")>-1){i=Math.ceil(e.contextDimension*i/100)}}r.offset=o-e.contextOffset+e.contextScroll-i;if(r.options.onlyOnScroll&&l!=null||!r.enabled){return}if(l!==null&&l<(s=e.oldScroll)&&s<=r.offset){return r.trigger([e.backward])}else if(l!==null&&l>(f=e.oldScroll)&&f>=r.offset){return r.trigger([e.forward])}else if(l===null&&e.oldScroll>=r.offset){return r.trigger([e.forward])}})})};t.prototype.checkEmpty=function(){if(n.isEmptyObject(this.waypoints.horizontal)&&n.isEmptyObject(this.waypoints.vertical)){this.$element.unbind([p,y].join(" "));return delete a[this.id]}};return t}();l=function(){function t(t,e,r){var i,o;r=n.extend({},n.fn[g].defaults,r);if(r.offset==="bottom-in-view"){r.offset=function(){var t;t=n[m]("viewportHeight");if(!n.isWindow(e.element)){t=e.$element.height()}return t-n(this).outerHeight()}}this.$element=t;this.element=t[0];this.axis=r.horizontal?"horizontal":"vertical";this.callback=r.handler;this.context=e;this.enabled=r.enabled;this.id="waypoints"+v++;this.offset=null;this.options=r;e.waypoints[this.axis][this.id]=this;s[this.axis][this.id]=this;i=(o=t.data(w))!=null?o:[];i.push(this.id);t.data(w,i)}t.prototype.trigger=function(t){if(!this.enabled){return}if(this.callback!=null){this.callback.apply(this.element,t)}if(this.options.triggerOnce){return this.destroy()}};t.prototype.disable=function(){return this.enabled=false};t.prototype.enable=function(){this.context.refresh();return this.enabled=true};t.prototype.destroy=function(){delete s[this.axis][this.id];delete this.context.waypoints[this.axis][this.id];return this.context.checkEmpty()};t.getWaypointsByElement=function(t){var e,r;r=n(t).data(w);if(!r){return[]}e=n.extend({},s.horizontal,s.vertical);return n.map(r,function(t){return e[t]})};return t}();d={init:function(t,e){var r;if(e==null){e={}}if((r=e.handler)==null){e.handler=t}this.each(function(){var t,r,i,s;t=n(this);i=(s=e.context)!=null?s:n.fn[g].defaults.context;if(!n.isWindow(i)){i=t.closest(i)}i=n(i);r=a[i.data(u)];if(!r){r=new o(i)}return new l(t,r,e)});n[m]("refresh");return this},disable:function(){return d._invoke(this,"disable")},enable:function(){return d._invoke(this,"enable")},destroy:function(){return d._invoke(this,"destroy")},prev:function(t,e){return d._traverse.call(this,t,e,function(t,e,n){if(e>0){return t.push(n[e-1])}})},next:function(t,e){return d._traverse.call(this,t,e,function(t,e,n){if(e<n.length-1){return t.push(n[e+1])}})},_traverse:function(t,e,i){var o,l;if(t==null){t="vertical"}if(e==null){e=r}l=h.aggregate(e);o=[];this.each(function(){var e;e=n.inArray(this,l[t]);return i(o,e,l[t])});return this.pushStack(o)},_invoke:function(t,e){t.each(function(){var t;t=l.getWaypointsByElement(this);return n.each(t,function(t,n){n[e]();return true})});return this}};n.fn[g]=function(){var t,r;r=arguments[0],t=2<=arguments.length?e.call(arguments,1):[];if(d[r]){return d[r].apply(this,t)}else if(n.isFunction(r)){return d.init.apply(this,arguments)}else if(n.isPlainObject(r)){return d.init.apply(this,[null,r])}else if(!r){return n.error("jQuery Waypoints needs a callback function or handler option.")}else{return n.error("The "+r+" method does not exist in jQuery Waypoints.")}};n.fn[g].defaults={context:r,continuous:true,enabled:true,horizontal:false,offset:0,triggerOnce:false};h={refresh:function(){return n.each(a,function(t,e){return e.refresh()})},viewportHeight:function(){var t;return(t=r.innerHeight)!=null?t:i.height()},aggregate:function(t){var e,r,i;e=s;if(t){e=(i=a[n(t).data(u)])!=null?i.waypoints:void 0}if(!e){return[]}r={horizontal:[],vertical:[]};n.each(r,function(t,i){n.each(e[t],function(t,e){return i.push(e)});i.sort(function(t,e){return t.offset-e.offset});r[t]=n.map(i,function(t){return t.element});return r[t]=n.unique(r[t])});return r},above:function(t){if(t==null){t=r}return h._filter(t,"vertical",function(t,e){return e.offset<=t.oldScroll.y})},below:function(t){if(t==null){t=r}return h._filter(t,"vertical",function(t,e){return e.offset>t.oldScroll.y})},left:function(t){if(t==null){t=r}return h._filter(t,"horizontal",function(t,e){return e.offset<=t.oldScroll.x})},right:function(t){if(t==null){t=r}return h._filter(t,"horizontal",function(t,e){return e.offset>t.oldScroll.x})},enable:function(){return h._invoke("enable")},disable:function(){return h._invoke("disable")},destroy:function(){return h._invoke("destroy")},extendFn:function(t,e){return d[t]=e},_invoke:function(t){var e;e=n.extend({},s.vertical,s.horizontal);return n.each(e,function(e,n){n[t]();return true})},_filter:function(t,e,r){var i,o;i=a[n(t).data(u)];if(!i){return[]}o=[];n.each(i.waypoints[e],function(t,e){if(r(i,e)){return o.push(e)}});o.sort(function(t,e){return t.offset-e.offset});return n.map(o,function(t){return t.element})}};n[m]=function(){var t,n;n=arguments[0],t=2<=arguments.length?e.call(arguments,1):[];if(h[n]){return h[n].apply(null,t)}else{return h.aggregate.call(null,n)}};n[m].settings={resizeThrottle:100,scrollThrottle:30};return i.load(function(){return n[m]("refresh")})})}).call(this);

/*	Popup
/* --------------------------------------------- */

(function ($) {

	"use strict";

	$.mad_popup_prepare = function (el, options) {
		this.el = el;
		this.options = $.extend({}, $.mad_popup_prepare.DEFAULTS, options);
		this.init();
	}

	$.mad_popup_prepare.DEFAULTS = {
		actionpopup : '',
		noncepopup: '',
		on_load : function () { }
	}

	$.mad_popup_prepare.openInstance = [];

	$.mad_popup_prepare.prototype = {
		init: function () {
			$.mad_popup_prepare.openInstance.unshift(this);
			var base = this;
				base.scope = false;
				base.doc = $(document);
				base.body = $('body');
				base.instance	= $.mad_popup_prepare.openInstance.length;
				base.namespace	= '.popup_modal_' + base.instance;
				base.support = {
					touch : Modernizr.touch,
					animations : Modernizr.cssanimations
				};
				base.eventtype = base.support.touch ? 'touchstart' : 'click';
			var animEndEventNames = {
				'WebkitAnimation' : 'webkitAnimationEnd',
				'OAnimation' : 'oAnimationEnd',
				'msAnimation' : 'MSAnimationEnd',
				'animation' : 'animationend'
			};
			base.animEndEventName = animEndEventNames[ Modernizr.prefixed('animation') ];
			base.ajaxLoad();
		},
		ajaxLoad: function () {
			this.body.on(this.eventtype, this.el, $.proxy(function (e) {
				if (!this.scope) {
					this.loadPopup(e);
				}
				this.scope = true;
			}, this));
		},
		loadPopup: function (e) {
			e.preventDefault();

			var base = this,
				el = $(e.target),
				data = el.data();
				data.action = base.options.actionpopup;
				data._madnonce_ajax = base.options.noncepopup;

			if (data.action == undefined) return;

			$.ajax({
				type: "POST",
				url: woocommerce_mod.ajaxurl,
				data: data,
				beforeSend: function() {
					el.block({
						message: null,
						overlayCSS: {
							background: '#fff url(' + global.ajax_loader_url + ') no-repeat center',
							backgroundSize: '16px 16px',
							opacity: 0.6
						}
					});
				},
				success: function (response) {
					el.unblock();

					if (response.match('exit')) {
						response = response.replace('exit', '');
						base.modal	= $('<div class="popup-modal modal-show"></div>');
						base.overlay = $('<div class="popup-modal-overlay"></div>');
						base.body.append(base.modal).append(base.overlay);
						base.modal.append(response);
						base.container = $(response).eq(0);
						base.onLoadCallback();
						base.behavior();
					}

				}
			});

		},
		closeModal: function () {
			var base = this;
			base.modal.removeClass('modal-show');
			setTimeout( function() {
				base.modal.addClass('modal-hide');
			}, 25);
			var onEndAnimationFn = function () {
				base.modal.add(base.overlay).remove();
				base.doc.off('keydown' + base.namespace);
			};
			if (base.support.animations) {
				base.modal.on( base.animEndEventName, onEndAnimationFn );
			} else {
				onEndAnimationFn();
			}
			base.scope = false;
			$.mad_popup_prepare.openInstance.shift();
		},
		behavior: function () {
			var base = this;

			$('.popup-close', base.modal).add(base.overlay).on(base.eventtype, function (e) {
				e.preventDefault();
				base.closeModal();
			});

			base.doc.on('keydown' + base.namespace, function (e) {
				var keycode = e.keyCode;
				switch (keycode) {
					case 27:
						setTimeout(function () {
							base.closeModal();
						}, 25);
						e.stopImmediatePropagation();
						break;
				}
			});
		},
		onLoadCallback: function () {
			var callback = this.options.on_load;
			if (typeof callback == 'function') {
				callback.call(this);
			}
		}
	}

})(jQuery);

/*	FitVids
/* --------------------------------------------- */

(function ($) {

	$.fn.fitVids = function(options) {

		var settings = {
			customSelector: null
		};

		if (!document.getElementById('fit-vids-style')) {

			var div = document.createElement('div'),
				ref = document.getElementsByTagName('base')[0] || document.getElementsByTagName('script')[0],
				cssStyles = '&shy;<style>.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}</style>';

			div.className = 'fit-vids-style';
			div.id = 'fit-vids-style';
			div.style.display = 'none';
			div.innerHTML = cssStyles;

			ref.parentNode.insertBefore(div,ref);

		}

		if (options) {
			$.extend(settings, options);
		}

		return this.each(function () {
			var selectors = [
				"iframe[src*='player.vimeo.com']",
				"iframe[src*='youtube.com']",
				"iframe[src*='youtube-nocookie.com']",
				"iframe[src*='kickstarter.com'][src*='video.html']",
				"iframe[src*='w.soundcloud.com']",
				"object",
				"embed"
			];

			if (settings.customSelector) {
				selectors.push(settings.customSelector);
			}

			var $allVideos = $(this).find(selectors.join(',')).not("iframe[src^='http:/\/\']");
			$allVideos = $allVideos.not("object object"); // SwfObj conflict patch

			$allVideos.each(function(){
				var $this = $(this);
				if (this.tagName.toLowerCase() === 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) {
					return;
				}
				var height = ( this.tagName.toLowerCase() === 'object' || ($this.attr('height') && !isNaN(parseInt($this.attr('height'), 10))) ) ? parseInt($this.attr('height'), 10) : $this.height(),
					width = !isNaN(parseInt($this.attr('width'), 10)) ? parseInt($this.attr('width'), 10) : $this.width(),
					aspectRatio = height / width;

				if(!$this.attr('id')) {
					var videoID = 'fitvid' + Math.floor(Math.random()*999999);
					$this.attr('id', videoID);
				}
				$this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+"%");
				$this.removeAttr('height').removeAttr('width');
			});
		});

	};

})(jQuery);

/*	Custom Click
/* --------------------------------------------- */

(function ($) {

	$.fn.customSelect = function () {

		return this.each(function () {
			var $this = $(this),
				list = $this.children('ul'),
				select = $this.children('select'),
				title = $this.children('.select-title'),
				options = select.children('option');
			({
				init: function () {
					var base = this;

						base.href = options.eq(0).data('href') !== undefined ? true : false;
						base.process();
						base.listeners();
				},
				process: function () {

					if ($this.find('[data-filter]').length) {
						$.each(options, function (idx, value) {
							list.append('<li data-filter="' + $(value).data('filter') + '">' + $(value).text() + '</li>');
						});
					} else {
						$.each(options, function (idx, value) {
							if ($(value).data('href') !== undefined) {
								list.append('<li><a href="' + $(value).data('href') + '">' + $(value).text() + '</a></li>');
							} else {
								list.append('<li>' + $(value).text() + '</li>');
							}
						});
					}

				},
				listeners: function () {

					// open list
					title.on('click', function () {
						list.slideToggle(400);
						$(this).toggleClass('active');
					});

					// selected option
					list.on('click', 'li', function () {
						var val = $(this).text();
						title.text(val);
						list.slideUp(400);
						select.val(val);
						title.toggleClass('active');
					});

					// Orderby
					if ($('.woocommerce-ordering').length) {
						$('.woocommerce-ordering').on('click', 'li', function() {
							var $index = $(this).index();
							$(this).parent().next().children('option').eq($index).attr("selected", "selected");
							$(this).closest('form').submit();
						});
					}

				}

			}).init();
		});
	}

})(jQuery);

/*	Search Click
/* --------------------------------------------- */

(function ($, window) {

	$.searchClick = function (el, options) {
		this.el = $(el);
		this.init(options);
	}

	$.searchClick.DEFAULTS = {
		key_esc: 27
	}

	$.searchClick.prototype = {
		init: function (options) {
			var base = this;
				base.o = $.extend({}, $.searchClick.DEFAULTS, options);
				base.key_esc = base.o.key_esc;
				base.searchWrap = $('.searchform-wrap', base.el);
				base.searchBtn = $('.search-button', base.el);
				base.searchClose = $('.close-search-form', base.el);
				base.searchField = $('#s', base.el);
				base.event = Modernizr.touch ? 'touchstart' : 'click';

				base.setUp();
				base.bind();
		},
		setUp: function () {
			var transEndEventNames = {
				'WebkitTransition': 'webkitTransitionEnd',
				'MozTransition': 'transitionend',
				'OTransition': 'oTransitionEnd',
				'msTransition': 'MSTransitionEnd',
				'transition': 'transitionend'
			};
			this.transEndEventName = transEndEventNames[Modernizr.prefixed( 'transition' )];
			this.support = { animations : Modernizr.csstransitions };
		},
		hide: function () {
			var base = this;
			base.searchWrap.removeClass('opened').addClass('closed');
			var onEndTransitionFn = function () {
				base.searchWrap.removeClass('closed');
			};
			if (base.support.animations) {
				base.searchWrap.on(base.transEndEventName, onEndTransitionFn);
			} else {
				onEndAnimationFn();
			}
		},
		bind: function () {
			this.searchBtn.on(this.event, $.proxy(this.display_show, this));
			this.searchClose.on(this.event, $.proxy(function (e) {
				this.display_hide(e, this.key_esc);
			}, this));
			this.keyDownHandler(this.key_esc);
		},
		display_show: function () {
			if (!this.searchWrap.hasClass('opened')) {
				this.searchWrap.addClass('opened');
				this.searchField.focus();
			}
		},
		display_hide: function (e, key) {
			var base = this;
			if (base.searchWrap.hasClass('opened')) {
				if (e.type == base.event || e.type == 'keydown' && e.keyCode === key) {
					e.preventDefault();
					base.hide();
					base.searchField.blur();
				}
			}
		},
		keyDownHandler: function (key) {
			$(window).on('keydown', $.proxy(function (e) {
				this.display_hide(e, key);
			}, this));
		}
	}

	$.fn.extend({
		searchClick: function (option) {
			if (!this.length) return this;
			return this.each(function () {
				var $this = $(this), data = $this.data('searchClick'),
					options = typeof option == 'object' && option;
				if (!data) {
					$this.data('searchClick', new $.searchClick(this, options));
				}
			});
		}
	});

})(jQuery, window);


/*	Scroll Spy											*/
/* ---------------------------------------------------- */

(function ($) {

	function MadScrollSpy(element, options) {
		var href;
		var self = this;
		var process = $.proxy(this.process, this);
		this.$element = $(element).is('body') ? $(window) : $(element);
		this.$body = $('body');
		this.$scrollElement = this.$element.on('scroll.bs.mad-scroll-spy.data-api', process);
		this.options = $.extend({}, MadScrollSpy.DEFAULTS, options);
		this.selector = (this.options.target || ((href = $(element).attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '')) || '') + ' li > a';

		this.offsets = $([]);
		this.targets = $([]);
		this.activeTarget = null;

		setTimeout(function() {
			self.refresh();
			self.process();
		}, 50);
	}

	MadScrollSpy.DEFAULTS = {
		offset: ($('.section-main').length) ? $('.section-main').offset().top : 0,
		applyClass: 'current-menu-item'
	}

	MadScrollSpy.prototype.refresh = function() {
		var offsetMethod = this.$element[0] == window ? 'offset' : 'position';

		var self = this;
		this.$body.find(this.selector).map(function() {
			var $el = $(this);
			var href = $el.data('target') || $el.attr('href');
			var $href = /^#\w/.test(href) && $(href);
			return ($href && $href.length && [[$href[offsetMethod]().top + (!$.isWindow(self.$scrollElement.get(0)) && self.$scrollElement.scrollTop()), href]]) || null
		}).sort(function(a, b) {
			return a[0] - b[0]
		}).each(function() {
			self.offsets.push(this[0]);
			self.targets.push(this[1]);
		});

	}

	MadScrollSpy.prototype.process = function() {
		var scrollTop = this.$scrollElement.scrollTop() + this.options.offset,
			scrollHeight = this.$scrollElement[0].scrollHeight || this.$body[0].scrollHeight,
			maxScroll = scrollHeight - this.$scrollElement.height(),
			offsets = this.offsets,
			targets = this.targets,
			activeTarget = this.activeTarget, i;

		if (scrollTop >= maxScroll) {
			return activeTarget != (i = targets.last()[0]) && this.activate(i);
		}

		for (i = offsets.length; i--; ) {
			activeTarget != targets[i] && scrollTop >= offsets[i] && (!offsets[i + 1] || scrollTop <= offsets[i + 1]) && this.activate(targets[i]);
		}

	}

	MadScrollSpy.prototype.activate = function (target) {
		this.activeTarget = target;
		$(this.selector)
			.parent('.' + this.options.applyClass)
			.removeClass(this.options.applyClass);

		var selector = this.selector + '[href="' + target + '"]';

		var active = $(selector).parent('li').addClass(this.options.applyClass);
		active.trigger('activate');
	}

	$.fn.mad_scrollspy = function (option) {
		return this.each(function() {
			var $this = $(this),
				data = $this.data('bs.madscrollspy'),
				options = typeof option == 'object' && option;
			if (!data) {
				$this.data('bs.madscrollspy', (data = new MadScrollSpy(this, options)));
			}
			if (typeof option == 'string') {
				data[option]();
			}
		});
	}

	$.fn.mad_scrollspy.Constructor = MadScrollSpy;

})(jQuery);

/*	Smooth Scroll
/* --------------------------------------------- */

(function ($) {

	$.madScroll = function (el, options) {
		this.el = $(el);
		this.o = $.extend({}, $.madScroll.DEFAULTS, options);
		this.selector = 'a[href*=#]:not([href=#])';
		if (!this.el.find(this.selector).length) return;
		this.init();
	}

	$.madScroll.DEFAULTS = {
		body : '',
		duration : 1200,
		easing : 'easeInOutExpo'
	}

	$.madScroll.prototype = {
		init: function () {
			this.touch = Modernizr.touch;
			this.eventtype = Modernizr.touch ? 'touchstart' : 'click';
			this.window = $(window);
			this.events();
		},
		events: function() {
			var base = this;

			base.o.body.mad_scrollspy(base.o.body.data());

			var hash = window.location.hash.replace(/\//g, ""), toEl = $(hash);
			if (toEl.length) {
				base.window.on('scroll.hash_scroll', function () {
					setTimeout(function () {
						base.window.off('scroll.hash_scroll').scrollTop(
							toEl.offset().top
						);
					}, 10);
				});
			}

			base.el.on(base.eventtype, base.selector, function (e) {
				var newHash = this.hash.replace(/\//g, "");

				if (newHash != '' && newHash != '#') {
					var $section = $(newHash);
					if ($section.length) {
						base.sectionToAnimate(newHash, $section);
						e.preventDefault();
					}
				}
			});
		},
		sectionToAnimate: function (newHash, section) {
			var base = this,
				container_offset = section.offset().top,
				offset = base.o.data.offset;

				if ($('#header').hasClass('type-2')) {
					offset = base.o.data.height - 30;
				}
				target = container_offset - offset;

			$('html, body').stop(true, true).animate({
				scrollTop: target
			}, {
				duration: base.o.duration,
				easing: base.o.easing,
				complete: function () {
					if (window.history.replaceState) {
						window.history.replaceState("", "", newHash);
					}
				}
			});
		}
	}

	$.fn.madscroll = function (option) {
		return this.each(function() {
			var $this = $(this),
				data = $this.data('mad.scroll'),
				options = typeof option == 'object' && option;
			if (!data) {
				$this.data('mad.scroll', (data = new $.madScroll(this, options)));
			}
			if (typeof option == 'string') {
				data[option]();
			}
		});
	}

})(jQuery);

/*	Temp Main Init
/* --------------------------------------------- */

(function ($, window) {

	function Temp(el, options) {
		this.el = $(el);
		this.init(options);
	}

	Temp.DEFAULTS = {
		sticky: true,
		animatedCSS: true
	}

	Temp.prototype = {
		init: function (options) {
			var base = this;
			base.window = $(window);
			base.options = $.extend({}, Temp.DEFAULTS, options);
			base.goTop = $('<button class="go-to-top" id="go-to-top"></button>').appendTo(base.el);
			base.support = {
				touch : Modernizr.touch,
				animations : Modernizr.csstransitions && Modernizr.cssanimations
			}
			base.event = base.support.touch ? 'touchstart' : 'click';

			// Refresh elements
			base.refresh();

			// Navigation
			base.navInit();

			// Search
			base.search.init(base);

			// Sticky
			if (base.options.sticky && !base.support.touch) {
				base.sticky.stickySet.call(base, base.window);
				base.window.on('scroll', function (e) {
					base.sticky.stickyInit.call(base, e.currentTarget);
				});
			}

			// Scrollspy
			if ($.fn.madscroll) {
				base.nav.madscroll({
					body : base.el,
					data : base.stickydata
				});
			}

			base.window.on('scroll', function (e) {
				base.gotoTop.scrollHandler.call(base, e.currentTarget);
			});

			if (base.support.animations) {
				if (base.options.animatedCSS && !base.support.touch) {
					base.animateCSS.call(base);
				} else {
					base.el.removeClass('animated');
				}
			}

			base.gotoTop.clickHandler(base);

		},
		elements: {
			'body.logged-in.admin-bar' : 'logged',
			'.menu_wrap' : 'menuWrap',
			'#navigation' : 'nav',
			'#header' : 'header',
			'.header-in' : 'headerIn',
			'.searchform-wrap' : 'searchform'
		},
		$: function (selector) { return $(selector); },
		refresh: function() {
			for (var key in this.elements) {
				this[this.elements[key]] = this.$(key);
			}
		},
		navInit: function () {
			var base = this
			base.navButton = $('<div class="mobile-button" id="mobile-button"></div>');
			base.nav.before(base.navButton);

			base.navButton.on(base.event, function (e) {
				e.preventDefault();
				var $this = $(e.target);

				if (!base.nav.hasClass('active')) {
					base.nav.stop(true, true).slideDown('400').addClass('active');
					$this.addClass('active');
				} else {
					base.nav.stop(true, true).slideUp('400').removeClass('active');
					$this.removeClass('active');
				}
			});

			$(window).on('resize', function () { base.removeAttr.call(base); });

			if ( /iphone|ipad|ipod|android|webos|blackberry|iemobile|opera mini/i.test( navigator.userAgent.toLowerCase() ) ) {
				var clicked = false;

				$('.navigation ul li:has(.menu-item-has-children) > a').on(base.event, function(index) {
					if (clicked != this) {
						index.preventDefault();
						clicked = this;
					}
				});
			}

		},
		removeAttr: function() {
			if ($(window).width() > 959) { this.nav.attr('style', ''); }
		},
		search: {
			init: function (base) {
				if (base.searchform.length) {
					base.header.searchClick();
				}
			}
		},
		sticky: {
			stickySet: function () {
				var base = this,
					elSticky = base.menuWrap, offset;

				if (base.header.hasClass('type-2') || base.header.hasClass('type-5')) {
					elSticky = base.headerIn;
				}

				if (elSticky.length) {
					offset = elSticky.offset().top;

					if (base.logged.length) {
						offset = offset - 32;
					}

					base.spacer = $('<div/>', { 'class': 'spacer' }).insertBefore(elSticky);

					var height = elSticky.outerHeight(true);
					if (base.header.hasClass('type-5')) {
						height = $('.h_top_part').outerHeight(true);
					}

					var data = {
						offset: offset,
						height: height
					}

					$.data(elSticky, 'data', data);
					base.stickydata = data;
				}
			},
			stickyInit: function (win) {
				var base = this, data, elSticky = base.menuWrap;

				if (base.header.hasClass('type-2') || base.header.hasClass('type-5')) {
					elSticky = base.headerIn;
				}

				if (elSticky.length) {
					data = $.data(elSticky, 'data');
					base.sticky.stickyAction(data, win, base);
				}
			},
			stickyAction: function (data, win, base) {
				var scrollTop = $(win).scrollTop(), elSticky = base.menuWrap;
				if (base.header.hasClass('type-2')) {
					elSticky = base.headerIn;
				}
				if (base.header.hasClass('type-5')) {
					elSticky = base.header;
				}
				if (scrollTop > data.offset) {
					base.spacer.css({ height: data.height });
					if (!elSticky.hasClass('sticky')) {
						elSticky.addClass('sticky');
					}
				} else {
					base.spacer.css({ height: 'auto' });
					if (elSticky.hasClass('sticky')) {
						elSticky.removeClass('sticky');
					}
				}
			}
		},
		gotoTop: {
			scrollHandler: function (win) {

				$(win).scrollTop() > 200 ?
					this.goTop.addClass('animate_finished') :
					this.goTop.removeClass('animate_finished');
			},
			clickHandler: function (base) {
				base.goTop.on(base.event, function (e) {
					e.preventDefault();
					$('html, body').animate({ scrollTop: 0 }, 800);
				});
			}
		},
		animateCSS: function () {

			if ($('.wpb_layerslider_element[class*="animate-"]').length) {
				$('.wpb_layerslider_element[class*="animate-"]').waypointSynchronise({
					container : '.wpb_layerslider_element',
					delay : 200,
					offset : 1000
				});
			}

			if ($('.wpb_video_widget[class*="animate-"]').length) {
				$('.wpb_video_widget[class*="animate-"]').waypointSynchronise({
					container : '.wpb_video_widget',
					delay : 200,
					offset : 800
				});
			}

			if ($('.wpb_list_styles[class*="animate-"]').length) {
				$('.wpb_list_styles[class*="animate-"]').waypointSynchronise({
					container : '.wpb_list_styles',
					delay : 200,
					offset : 800
				});
			}

			if ($('.wpb_button[class*="animate-"]').length) {
				$('.wpb_button[class*="animate-"]').waypointSynchronise({
					container : '.wpb_button',
					delay : 200,
					offset : 800
				});
			}

			if ($('.images-item[class*="animate-"]').length) {
				$('.images-item').waypointSynchronise({
					container : '.wpb_images_carousel',
					delay : 200,
					offset : 800
				});
			}

			if ($('.vc_custom_heading[class*="animate-"]').length) {
				$('.vc_custom_heading[class*="animate-"]').each(function () {
					$(this).waypointSynchronise({
						container : $(this),
						delay : 200,
						offset : 800
					});
				});
			}

			if ($('.product-filter > [class*="animate-"]').length) {
				$('.product-filter > [class*="animate-"]').waypointSynchronise({
					container : '.product-filter',
					delay : 200,
					offset : 1000
				});
			}

			if ($('.products [class*="animate-"]').length) {
				$('.products [class*="animate-"]').waypointSynchronise({
					container : '.products',
					delay : 200,
					offset : 700
				});
			}

			if ($('.banner-area[class*="animate-"]').length) {
				$('.banner-area[class*="animate-"]').waypointSynchronise({
					container : '.banner-area',
					delay : 200,
					offset : 800
				});
			}

			if ($('.product-brands [class*="animate-"]').length) {
				$('.product-brands [class*="animate-"]').waypointSynchronise({
					container : '.product-brands',
					delay : 200,
					offset : 830
				});
			}

			if ($('.post-carousel [class*="animate-"]').length) {
				$('.post-carousel [class*="animate-"]').waypointSynchronise({
					container : '.post-carousel',
					delay : 200,
					offset : 830
				});
			}

			if ($('.tm-item [class*="animate-"]').length) {
				$('.tm-item [class*="animate-"]').waypointSynchronise({
					container : '.tm-item',
					delay : 200,
					offset : 830
				});
			}

			if ($('.product-carousel [class*="animate-"]').length) {
				$('.product-carousel [class*="animate-"]').waypointSynchronise({
					container : '.product-carousel',
					delay : 200,
					offset : 700
				});
			}

			if ($('.portfolio-items [class*="animate-"]').length) {
				$('.portfolio-items [class*="animate-"]').waypointSynchronise({
					container : '.portfolio-items',
					delay : 200,
					offset : 700
				});
			}

			if ($('.info-block[class*="animate-"]').length) {
				$('.info-block[class*="animate-"]').waypointSynchronise({
					container : '.wpb_wrapper',
					delay : 200,
					offset : '80%'
				});
			}

			if ($('.pricing-table[class*="animate-"]').length) {
				$('.pricing-table[class*="animate-"]').waypointSynchronise({
					container : '.wpb_wrapper',
					delay : 200,
					offset : 700
				});
			}

		}
	}

	/* Temp Plugin
	 * ================================== */

	$.fn.Temp = function (option) {
		return this.each(function () {
			var $this = $(this), data = $this.data('Temp'),
				options = typeof option == 'object' && option;
			if (!data) {
				$this.data('Temp', new Temp(this, options));
			}
		});
	}

})(jQuery, window);

/*
 Plugin Name: 	BrowserSelector
 Written by: 	Crivos - (http://www.crivos.com)
 Version: 		0.1
 */

(function($, navigator) {
	$.extend({

		browserSelector: function() {

			var u = navigator.userAgent,
				ua = u.toLowerCase(),
				is = function (t) {
					return ua.indexOf(t) > -1;
				},
				g = 'gecko',
				w = 'webkit',
				s = 'safari',
				o = 'opera',
				h = document.documentElement,
				b = [(!(/opera|webtv/i.test(ua)) && /msie\s(\d)/.test(ua)) ? ('ie ie' + parseFloat(navigator.appVersion.split("MSIE")[1])) : is('firefox/2') ? g + ' ff2' : is('firefox/3.5') ? g + ' ff3 ff3_5' : is('firefox/3') ? g + ' ff3' : is('gecko/') ? g : is('opera') ? o + (/version\/(\d+)/.test(ua) ? ' ' + o + RegExp.jQuery1 : (/opera(\s|\/)(\d+)/.test(ua) ? ' ' + o + RegExp.jQuery2 : '')) : is('konqueror') ? 'konqueror' : is('chrome') ? w + ' chrome' : is('iron') ? w + ' iron' : is('applewebkit/') ? w + ' ' + s + (/version\/(\d+)/.test(ua) ? ' ' + s + RegExp.jQuery1 : '') : is('mozilla/') ? g : '', is('j2me') ? 'mobile' : is('iphone') ? 'iphone' : is('ipod') ? 'ipod' : is('mac') ? 'mac' : is('darwin') ? 'mac' : is('webtv') ? 'webtv' : is('win') ? 'win' : is('freebsd') ? 'freebsd' : (is('x11') || is('linux')) ? 'linux' : '', 'js'];

			c = b.join(' ');
			h.className += ' ' + c;

		}

	});
})(jQuery, navigator);

/*
 Plugin Name: 	smoothScroll for jQuery.
 Written by: 	Crivos - (http://www.crivos.com)
 Version: 		0.1

 Based on:

 SmoothScroll v1.2.1
 Licensed under the terms of the MIT license.

 People involved
 - Balazs Galambosi (maintainer)
 - Patrick Brunner  (original idea)
 - Michael Herf     (Pulse Algorithm)

 */
(function($) {
	$.extend({

		smoothScroll: function() {

			// Scroll Variables (tweakable)
			var defaultOptions = {

				// Scrolling Core
				frameRate        : 150, // [Hz]
				animationTime    : 700, // [px]
				stepSize         : 80, // [px]

				// Pulse (less tweakable)
				// ratio of "tail" to "acceleration"
				pulseAlgorithm   : true,
				pulseScale       : 8,
				pulseNormalize   : 1,

				// Acceleration
				accelerationDelta : 20,  // 20
				accelerationMax   : 1,   // 1

				// Keyboard Settings
				keyboardSupport   : true,  // option
				arrowScroll       : 50,     // [px]

				// Other
				touchpadSupport   : true,
				fixedBackground   : true,
				excluded          : ""
			};

			var options = defaultOptions;

			// Other Variables
			var isExcluded = false;
			var isFrame = false;
			var direction = { x: 0, y: 0 };
			var initDone  = false;
			var root = document.documentElement;
			var activeElement;
			var observer;
			var deltaBuffer = [ 120, 120, 120 ];

			var key = { left: 37, up: 38, right: 39, down: 40, spacebar: 32,
				pageup: 33, pagedown: 34, end: 35, home: 36 };


			/***********************************************
			 * INITIALIZE
			 ***********************************************/

			/**
			 * Tests if smooth scrolling is allowed. Shuts down everything if not.
			 */
			function initTest() {

				var disableKeyboard = false;

				// disable keys for google reader (spacebar conflict)
				if (document.URL.indexOf("google.com/reader/view") > -1) {
					disableKeyboard = true;
				}

				// disable everything if the page is blacklisted
				if (options.excluded) {
					var domains = options.excluded.split(/[,\n] ?/);
					domains.push("mail.google.com"); // exclude Gmail for now
					for (var i = domains.length; i--;) {
						if (document.URL.indexOf(domains[i]) > -1) {
							observer && observer.disconnect();
							removeEvent("mousewheel", wheel);
							disableKeyboard = true;
							isExcluded = true;
							break;
						}
					}
				}

				// disable keyboard support if anything above requested it
				if (disableKeyboard) {
					removeEvent("keydown", keydown);
				}

				if (options.keyboardSupport && !disableKeyboard) {
					addEvent("keydown", keydown);
				}
			}

			/**
			 * Sets up scrolls array, determines if frames are involved.
			 */
			function init() {

				if (!document.body) return;

				var body = document.body;
				var html = document.documentElement;
				var windowHeight = window.innerHeight;
				var scrollHeight = body.scrollHeight;

				// check compat mode for root element
				root = (document.compatMode.indexOf('CSS') >= 0) ? html : body;
				activeElement = body;

				initTest();
				initDone = true;

				// Checks if this script is running in a frame
				if (top != self) {
					isFrame = true;
				}

				/**
				 * This fixes a bug where the areas left and right to
				 * the content does not trigger the onmousewheel event
				 * on some pages. e.g.: html, body { height: 100% }
				 */
				else if (scrollHeight > windowHeight &&
					(body.offsetHeight <= windowHeight ||
						html.offsetHeight <= windowHeight)) {

					// DOMChange (throttle): fix height
					var pending = false;
					var refresh = function () {
						if (!pending && html.scrollHeight != document.height) {
							pending = true; // add a new pending action
							setTimeout(function () {
								html.style.height = document.height + 'px';
								pending = false;
							}, 500); // act rarely to stay fast
						}
					};
					html.style.height = 'auto';
					setTimeout(refresh, 10);

					var config = {
						attributes: true,
						childList: true,
						characterData: false
					};

					observer = new MutationObserver(refresh);
					observer.observe(body, config);

					// clearfix
					if (root.offsetHeight <= windowHeight) {
						var underlay = document.createElement("div");
						underlay.style.clear = "both";
						body.appendChild(underlay);
					}
				}

				// gmail performance fix
				if (document.URL.indexOf("mail.google.com") > -1) {
					var s = document.createElement("style");
					s.innerHTML = ".iu { visibility: hidden }";
					(document.getElementsByTagName("head")[0] || html).appendChild(s);
				}
				// facebook better home timeline performance
				// all the HTML resized images make rendering CPU intensive
				else if (document.URL.indexOf("www.facebook.com") > -1) {
					var home_stream = document.getElementById("home_stream");
					home_stream && (home_stream.style.webkitTransform = "translateZ(0)");
				}
				// disable fixed background
				if (!options.fixedBackground && !isExcluded) {
					body.style.backgroundAttachment = "scroll";
					html.style.backgroundAttachment = "scroll";
				}
			}


			/************************************************
			 * SCROLLING
			 ************************************************/

			var que = [];
			var pending = false;
			var lastScroll = +new Date;

			/**
			 * Pushes scroll actions to the scrolling queue.
			 */
			function scrollArray(elem, left, top, delay) {

				delay || (delay = 1000);
				directionCheck(left, top);

				if (options.accelerationMax != 1) {
					var now = +new Date;
					var elapsed = now - lastScroll;
					if (elapsed < options.accelerationDelta) {
						var factor = (1 + (30 / elapsed)) / 2;
						if (factor > 1) {
							factor = Math.min(factor, options.accelerationMax);
							left *= factor;
							top  *= factor;
						}
					}
					lastScroll = +new Date;
				}

				// push a scroll command
				que.push({
					x: left,
					y: top,
					lastX: (left < 0) ? 0.99 : -0.99,
					lastY: (top  < 0) ? 0.99 : -0.99,
					start: +new Date
				});

				// don't act if there's a pending queue
				if (pending) {
					return;
				}

				var scrollWindow = (elem === document.body);

				var step = function (time) {

					var now = +new Date;
					var scrollX = 0;
					var scrollY = 0;

					for (var i = 0; i < que.length; i++) {

						var item = que[i];
						var elapsed  = now - item.start;
						var finished = (elapsed >= options.animationTime);

						// scroll position: [0, 1]
						var position = (finished) ? 1 : elapsed / options.animationTime;

						// easing [optional]
						if (options.pulseAlgorithm) {
							position = pulse(position);
						}

						// only need the difference
						var x = (item.x * position - item.lastX) >> 0;
						var y = (item.y * position - item.lastY) >> 0;

						// add this to the total scrolling
						scrollX += x;
						scrollY += y;

						// update last values
						item.lastX += x;
						item.lastY += y;

						// delete and step back if it's over
						if (finished) {
							que.splice(i, 1); i--;
						}
					}

					// scroll left and top
					if (scrollWindow) {
						window.scrollBy(scrollX, scrollY);
					}
					else {
						if (scrollX) elem.scrollLeft += scrollX;
						if (scrollY) elem.scrollTop  += scrollY;
					}

					// clean up if there's nothing left to do
					if (!left && !top) {
						que = [];
					}

					if (que.length) {
						requestFrame(step, elem, (delay / options.frameRate + 1));
					} else {
						pending = false;
					}
				};

				// start a new queue of actions
				requestFrame(step, elem, 0);
				pending = true;
			}

			/***********************************************
			 * EVENTS
			 ***********************************************/

			/**
			 * Mouse wheel handler.
			 * @param {Object} event
			 */
			function wheel(event) {

				if (!initDone) {
					init();
				}

				var target = event.target;
				var overflowing = overflowingAncestor(target);

				// use default if there's no overflowing
				// element or default action is prevented
				if (!overflowing || event.defaultPrevented ||
					isNodeName(activeElement, "embed") ||
					(isNodeName(target, "embed") && /\.pdf/i.test(target.src))) {
					return true;
				}

				var deltaX = event.wheelDeltaX || 0;
				var deltaY = event.wheelDeltaY || 0;

				// use wheelDelta if deltaX/Y is not available
				if (!deltaX && !deltaY) {
					deltaY = event.wheelDelta || 0;
				}

				// check if it's a touchpad scroll that should be ignored
				if (!options.touchpadSupport && isTouchpad(deltaY)) {
					return true;
				}

				// scale by step size
				// delta is 120 most of the time
				// synaptics seems to send 1 sometimes
				if (Math.abs(deltaX) > 1.2) {
					deltaX *= options.stepSize / 120;
				}
				if (Math.abs(deltaY) > 1.2) {
					deltaY *= options.stepSize / 120;
				}

				scrollArray(overflowing, -deltaX, -deltaY);
				event.preventDefault();
			}

			/**
			 * Keydown event handler.
			 * @param {Object} event
			 */
			function keydown(event) {

				var target   = event.target;
				var modifier = event.ctrlKey || event.altKey || event.metaKey ||
					(event.shiftKey && event.keyCode !== key.spacebar);

				// do nothing if user is editing text
				// or using a modifier key (except shift)
				// or in a dropdown
				if ( /input|textarea|select|embed/i.test(target.nodeName) ||
					target.isContentEditable ||
					event.defaultPrevented   ||
					modifier ) {
					return true;
				}
				// spacebar should trigger button press
				if (isNodeName(target, "button") &&
					event.keyCode === key.spacebar) {
					return true;
				}

				var shift, x = 0, y = 0;
				var elem = overflowingAncestor(activeElement);
				var clientHeight = elem.clientHeight;

				if (elem == document.body) {
					clientHeight = window.innerHeight;
				}

				switch (event.keyCode) {
					case key.up:
						y = -options.arrowScroll;
						break;
					case key.down:
						y = options.arrowScroll;
						break;
					case key.spacebar: // (+ shift)
						shift = event.shiftKey ? 1 : -1;
						y = -shift * clientHeight * 0.9;
						break;
					case key.pageup:
						y = -clientHeight * 0.9;
						break;
					case key.pagedown:
						y = clientHeight * 0.9;
						break;
					case key.home:
						y = -elem.scrollTop;
						break;
					case key.end:
						var damt = elem.scrollHeight - elem.scrollTop - clientHeight;
						y = (damt > 0) ? damt+10 : 0;
						break;
					case key.left:
						x = -options.arrowScroll;
						break;
					case key.right:
						x = options.arrowScroll;
						break;
					default:
						return true; // a key we don't care about
				}

				scrollArray(elem, x, y);
				event.preventDefault();
			}

			/**
			 * Mousedown event only for updating activeElement
			 */
			function mousedown(event) {
				activeElement = event.target;
			}


			/***********************************************
			 * OVERFLOW
			 ***********************************************/

			var cache = {}; // cleared out every once in while
			setInterval(function () { cache = {}; }, 10 * 1000);

			var uniqueID = (function () {
				var i = 0;
				return function (el) {
					return el.uniqueID || (el.uniqueID = i++);
				};
			})();

			function setCache(elems, overflowing) {
				for (var i = elems.length; i--;)
					cache[uniqueID(elems[i])] = overflowing;
				return overflowing;
			}

			function overflowingAncestor(el) {
				var elems = [];
				var rootScrollHeight = root.scrollHeight;
				do {
					var cached = cache[uniqueID(el)];
					if (cached) {
						return setCache(elems, cached);
					}
					elems.push(el);
					if (rootScrollHeight === el.scrollHeight) {
						if (!isFrame || root.clientHeight + 10 < rootScrollHeight) {
							return setCache(elems, document.body); // scrolling root in WebKit
						}
					} else if (el.clientHeight + 10 < el.scrollHeight) {
						overflow = getComputedStyle(el, "").getPropertyValue("overflow-y");
						if (overflow === "scroll" || overflow === "auto") {
							return setCache(elems, el);
						}
					}
				} while (el = el.parentNode);
			}


			/***********************************************
			 * HELPERS
			 ***********************************************/

			function addEvent(type, fn, bubble) {
				window.addEventListener(type, fn, (bubble||false));
			}

			function removeEvent(type, fn, bubble) {
				window.removeEventListener(type, fn, (bubble||false));
			}

			function isNodeName(el, tag) {
				return (el.nodeName||"").toLowerCase() === tag.toLowerCase();
			}

			function directionCheck(x, y) {
				x = (x > 0) ? 1 : -1;
				y = (y > 0) ? 1 : -1;
				if (direction.x !== x || direction.y !== y) {
					direction.x = x;
					direction.y = y;
					que = [];
					lastScroll = 0;
				}
			}

			var deltaBufferTimer;

			function isTouchpad(deltaY) {
				if (!deltaY) return;
				deltaY = Math.abs(deltaY)
				deltaBuffer.push(deltaY);
				deltaBuffer.shift();
				clearTimeout(deltaBufferTimer);
				var allEquals    = (deltaBuffer[0] == deltaBuffer[1] &&
					deltaBuffer[1] == deltaBuffer[2]);
				var allDivisable = (isDivisible(deltaBuffer[0], 120) &&
					isDivisible(deltaBuffer[1], 120) &&
					isDivisible(deltaBuffer[2], 120));
				return !(allEquals || allDivisable);
			}

			function isDivisible(n, divisor) {
				return (Math.floor(n / divisor) == n / divisor);
			}

			var requestFrame = (function () {
				return  window.requestAnimationFrame       ||
					window.webkitRequestAnimationFrame ||
					function (callback, element, delay) {
						window.setTimeout(callback, delay || (1000/60));
					};
			})();

			var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;


			/***********************************************
			 * PULSE
			 ***********************************************/

			/**
			 * Viscous fluid with a pulse for part and decay for the rest.
			 * - Applies a fixed force over an interval (a damped acceleration), and
			 * - Lets the exponential bleed away the velocity over a longer interval
			 * - Michael Herf, http://stereopsis.com/stopping/
			 */
			function pulse_(x) {
				var val, start, expx;
				// test
				x = x * options.pulseScale;
				if (x < 1) { // acceleartion
					val = x - (1 - Math.exp(-x));
				} else {     // tail
					// the previous animation ended here:
					start = Math.exp(-1);
					// simple viscous drag
					x -= 1;
					expx = 1 - Math.exp(-x);
					val = start + (expx * (1 - start));
				}
				return val * options.pulseNormalize;
			}

			function pulse(x) {
				if (x >= 1) return 1;
				if (x <= 0) return 0;

				if (options.pulseNormalize == 1) {
					options.pulseNormalize /= pulse_(1);
				}
				return pulse_(x);
			}

			addEvent("mousedown", mousedown);
			addEvent("mousewheel", wheel);
			addEvent("load", init);

		}

	});
})(jQuery);


/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 *
 * Open source under the BSD License.
 *
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list
 * of conditions and the following disclaimer in the documentation and/or other materials
 * provided with the distribution.
 *
 * Neither the name of the author nor the names of contributors may be used to endorse
 * or promote products derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

// t: current time, b: begInnIng value, c: change In value, d: duration
jQuery.easing['jswing'] = jQuery.easing['swing'];

jQuery.extend( jQuery.easing,
	{
		def: 'easeOutQuad',
		swing: function (x, t, b, c, d) {
			//alert(jQuery.easing.default);
			return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
		},
		easeInQuad: function (x, t, b, c, d) {
			return c*(t/=d)*t + b;
		},
		easeOutQuad: function (x, t, b, c, d) {
			return -c *(t/=d)*(t-2) + b;
		},
		easeInOutQuad: function (x, t, b, c, d) {
			if ((t/=d/2) < 1) return c/2*t*t + b;
			return -c/2 * ((--t)*(t-2) - 1) + b;
		},
		easeInCubic: function (x, t, b, c, d) {
			return c*(t/=d)*t*t + b;
		},
		easeOutCubic: function (x, t, b, c, d) {
			return c*((t=t/d-1)*t*t + 1) + b;
		},
		easeInOutCubic: function (x, t, b, c, d) {
			if ((t/=d/2) < 1) return c/2*t*t*t + b;
			return c/2*((t-=2)*t*t + 2) + b;
		},
		easeInQuart: function (x, t, b, c, d) {
			return c*(t/=d)*t*t*t + b;
		},
		easeOutQuart: function (x, t, b, c, d) {
			return -c * ((t=t/d-1)*t*t*t - 1) + b;
		},
		easeInOutQuart: function (x, t, b, c, d) {
			if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
			return -c/2 * ((t-=2)*t*t*t - 2) + b;
		},
		easeInQuint: function (x, t, b, c, d) {
			return c*(t/=d)*t*t*t*t + b;
		},
		easeOutQuint: function (x, t, b, c, d) {
			return c*((t=t/d-1)*t*t*t*t + 1) + b;
		},
		easeInOutQuint: function (x, t, b, c, d) {
			if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
			return c/2*((t-=2)*t*t*t*t + 2) + b;
		},
		easeInSine: function (x, t, b, c, d) {
			return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
		},
		easeOutSine: function (x, t, b, c, d) {
			return c * Math.sin(t/d * (Math.PI/2)) + b;
		},
		easeInOutSine: function (x, t, b, c, d) {
			return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
		},
		easeInExpo: function (x, t, b, c, d) {
			return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
		},
		easeOutExpo: function (x, t, b, c, d) {
			return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
		},
		easeInOutExpo: function (x, t, b, c, d) {
			if (t==0) return b;
			if (t==d) return b+c;
			if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
			return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
		},
		easeInCirc: function (x, t, b, c, d) {
			return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
		},
		easeOutCirc: function (x, t, b, c, d) {
			return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
		},
		easeInOutCirc: function (x, t, b, c, d) {
			if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
			return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
		},
		easeInElastic: function (x, t, b, c, d) {
			var s=1.70158;var p=0;var a=c;
			if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
			if (a < Math.abs(c)) { a=c; var s=p/4; }
			else var s = p/(2*Math.PI) * Math.asin (c/a);
			return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
		},
		easeOutElastic: function (x, t, b, c, d) {
			var s=1.70158;var p=0;var a=c;
			if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
			if (a < Math.abs(c)) { a=c; var s=p/4; }
			else var s = p/(2*Math.PI) * Math.asin (c/a);
			return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
		},
		easeInOutElastic: function (x, t, b, c, d) {
			var s=1.70158;var p=0;var a=c;
			if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
			if (a < Math.abs(c)) { a=c; var s=p/4; }
			else var s = p/(2*Math.PI) * Math.asin (c/a);
			if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
			return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
		},
		easeInBack: function (x, t, b, c, d, s) {
			if (s == undefined) s = 1.70158;
			return c*(t/=d)*t*((s+1)*t - s) + b;
		},
		easeOutBack: function (x, t, b, c, d, s) {
			if (s == undefined) s = 1.70158;
			return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
		},
		easeInOutBack: function (x, t, b, c, d, s) {
			if (s == undefined) s = 1.70158;
			if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
			return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
		},
		easeInBounce: function (x, t, b, c, d) {
			return c - jQuery.easing.easeOutBounce (x, d-t, 0, c, d) + b;
		},
		easeOutBounce: function (x, t, b, c, d) {
			if ((t/=d) < (1/2.75)) {
				return c*(7.5625*t*t) + b;
			} else if (t < (2/2.75)) {
				return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
			} else if (t < (2.5/2.75)) {
				return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
			} else {
				return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
			}
		},
		easeInOutBounce: function (x, t, b, c, d) {
			if (t < d/2) return jQuery.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
			return jQuery.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
		}
	});

/*
 *
 * TERMS OF USE - EASING EQUATIONS
 *
 * Open source under the BSD License.
 *
 * Copyright © 2001 Robert Penner
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list
 * of conditions and the following disclaimer in the documentation and/or other materials
 * provided with the distribution.
 *
 * Neither the name of the author nor the names of contributors may be used to endorse
 * or promote products derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */