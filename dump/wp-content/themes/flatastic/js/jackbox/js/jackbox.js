/* --------------------------------------------- */
/* Author: http://codecanyon.net/user/CodingJack */
/* --------------------------------------------- */

;(function($) {
	
	// These can be overriden with the main jackBox() call, see "Global Settings" in the help documentation
	var defaults = {
		
		useThumbs: true,
		deepLinking: true,
		autoPlayVideo: false,
		flashVideoFirst: false,
		
		defaultVideoWidth: 960,
		defaultVideoHeight: 540,
		
		thumbnailWidth: 75,
		thumbnailHeight: 50,
		useThumbTooltips: true,
		
		dynamic: false,
		baseName: "jackbox",
		className: ".jackbox",
		preloadGraphics: true,
		showInfoByDefault: false,
		thumbsStartHidden: false,
		showPageScrollbar: false,
		useKeyboardControls: true,
		fullscreenScalesContent: true,
		defaultShareImage: "jackbox/img/default_share.jpg"
		
	},
	
	// The padding/buffer for the lightbox main content container
	boxBuffer = 10,
	
	// Accounts for the thumb panel's border
	thumbnailMargin = 2,
	
	// Accounts for the large prev/next buttons you'll find on the left and right sides of the screen
	// This number makes sure the content always fits between these buttons
	thumbPanelBuffer = 160,
	
	// The width and height of the social iframe that's loaded in
	socialFrameWidth = 200,
	socialFrameHeight = 21,
	
	// The path to the JackBox graphics folder for preloading
	graphicsPath = "../img/graphics/",
	
	// The url to the preloader script
	preloaderUrl = "/php/graphics.php",
	
	// The url to the swf module
	swfPlayer = "/modules/jackbox_swf.html",
	
	// The url to the fallback thumbnail
	defaultThumb = "/img/thumbs/default.jpg",
	
	// The url to the video player module
	videoPlayer = "/modules/jackbox_video.html",
	
	// The url to the audio player module
	audioPlayer = "/modules/jackbox_audio.html",
	
	// The url to the social buttons module
	socialbuttons = "/modules/jackbox_social.php",
	
	// The Vimeo video iframe to be used (edit with caution)
	vimeoMarkup = "http://player.vimeo.com/video{url}?title=0&byline=0&portrait=0&autoplay={autoplay}&color=FFFFFF&wmode=transparent",
	
	// The Youtube video iframe to be used (edit with caution)
	youTubeMarkup = "http://www.youtube.com/embed/{url}?autoplay={autoplay}&autohide=1&hd=1&iv_load_policy=3&showinfo=0&showsearch=0&wmode=transparent",
	
	// The markup or "header" that will be placed above the item's content
	topMarkup = 
		
		'<div class="jackbox-top clearfix">' + 
			
			/* ******************************************** */
			/* delete this line below for no social buttons */
			/* ******************************************** */
			'<div class="jackbox-social"></div>' + 
			
			// fullscreen and close buttons
			'<div class="jackbox-top-icons">' + 
				
				'<span class="jackbox-fullscreen"><span class="jackbox-button jackbox-fs"></span><span class="jackbox-button jackbox-ns"></span></span>' + 
				'<span class="jackbox-button jackbox-button-margin jackbox-close"></span>' + 
				
			'</div>' + 
						
		'<div />',
	
	// The markup or "footer" that will be placed below the item's content
	bottomMarkup = 
	
		'<div class="jackbox-bottom clearfix">' + 
			
			// prev/next buttons
			'<div class="jackbox-controls">' + 
			
				'<span class="jackbox-button jackbox-arrow-left"></span>' + 
				'<span class="jackbox-button jackbox-arrow-right"></span>' +
				
			'</div>' +  
			
			// title text plus current number
			'<div class="jackbox-title-text">' + 
				
				'<span class="jb-current"></span><span class="jb-divider">/</span><span class="jb-total"></span>' +
				'<span class="jackbox-title-txt"></span>' + 
			
			'</div>' +
			
			// info and thumb toggle buttons
			'<div class="jackbox-bottom-icons">' +
				
				'<span class="jackbox-button jackbox-info"></span>' + 
				'<span class="jackbox-button-margin jackbox-button-thumbs"><span class="jackbox-button jackbox-hide-thumbs"></span><span class="jackbox-button jackbox-show-thumbs"></span></span>' + 
				
			'</div>' +
			
		'</div>',
		
	
	////////////////////////////////////////////////////////
	// END SETTINGS ////////////////////////////////////////
	////////////////////////////////////////////////////////
	
	resizeThrottle,
	bottomContent, 
	hasFullscreen, 
	holderPadLeft,
	holderPadTop,
	thumbTipText,
	thumbBufTall, 
	currentWidth, 
	holderHeight, 
	isFullscreen,
	itemCallback,
	thumbTipBuf, 
	paddingTall,
	paddingWide,
	totalThumbs,
	description,
	panelBuffer,
	thumbMinus,
	thumbStrip,
	thumbPanel,
	stripWidth,
	thumbRight,
	topContent,
	descHolder,
	descHeight,
	showThumbs,
	hideThumbs,
	panelRight,
	fullNormal,
	scrollPos,
	panelLeft,
	titleText,
	thumbLeft,
	container,
	preloader,
	numThumbs,
	descripts,
	oldHeight,
	unescaped,
	swipeable,
	className,
	baseName,
	oldWidth,
	thumbTip,
	thumbVis,
	showHide,
	closeBtn,
	descText,
	controls,
	elements,
	useTips,
	scaleUp,
	divider,
	descVis,
	thumbOn,
	wrapper,
	current,
	goRight,
	legged,
	preBuf,
	goLeft,
	upsize,
	fader,
	touch,
	boxed,
	title,
	total,
	cover,
	words,
	$this,
	items,
	info,
	docs,
	doc,
	url,
	wid,
	win,
	leg,
	isIE,
	cats,
	high,
	hash,
	list,
	isIE8,
	pushW,
	pushH,
	timer,
	toCall,
	thumbs,
	bodies,
	holder,
	lights,
	oWidth,
	isLocal,
	runTime,
	scaling,
	content,
	oHeight,
	isImage,
	winWide,
	winTall,
	bufferW,
	bufferH,
	theType,
	parents,
	browser,
	thmWidth,
	dataDesc,
	isActive,
	preAdded,
	scroller,
	keyboard,
	isYoutube,
	useThumbs,
	isLoading,
	fromThumb,
	hasThumbs,
	holOrigTop,
	firstCheck,
	currentCat,
	showScroll,
	deepLinking,
	lightSocial,
	socialFrame,
	thumbHolder,
	eventsAdded,
	preThumbBuf,
	holOrigLeft,
	instantiated,
	arrowClicked,
	arrowsActive,
	thumbsChecked,
	autoPlayVideo,
	thumbnailWidth,
	jackBoxIsReady,
	thumbnailHeight,
	flashVideoFirst,
	defaultShareImage,
	showInfoByDefault,
	thumbsStartHidden,
	defaultVideoWidth,
	defaultVideoHeight,
	
	isOn = 1,
	catOn = -1,
	numCats = 0,
	firstRun = true,
	initialLoad = true,
	
	methods = {
		
		init: function($this, settings) {
			
			if(!instantiated) {
				
				if(typeof Jacked === "undefined") return;
				if(settings) $.extend(defaults, settings);
				
				className = defaults.className;
				useThumbs = defaults.useThumbs;
				deepLinking = defaults.deepLinking;
				useTips = defaults.useThumbTooltips;
				autoPlayVideo = defaults.autoPlayVideo;
				keyboard = defaults.useKeyboardControls;
				showScroll = defaults.showPageScrollbar;
				thumbnailWidth = defaults.thumbnailWidth;
				scaling = defaults.fullscreenScalesContent;
				thumbnailHeight = defaults.thumbnailHeight;
				flashVideoFirst = defaults.flashVideoFirst;
				showInfoByDefault = defaults.showInfoByDefault;
				thumbsStartHidden = defaults.thumbsStartHidden;
				defaultShareImage = defaults.defaultShareImage;
				defaultVideoWidth = defaults.defaultVideoWidth;
				defaultVideoHeight = defaults.defaultVideoHeight;
				
				baseName = defaults.baseName;
				swfPlayer = baseName + swfPlayer;
				videoPlayer = baseName + videoPlayer;
				audioPlayer = baseName + audioPlayer;
				preloaderUrl = baseName + preloaderUrl;
				defaultThumb = baseName + defaultThumb;
				socialbuttons = baseName + socialbuttons;

				doc = document;
				docs = $(document);
				instantiated = true;
				thmWidth = thumbnailWidth + thumbnailMargin;
				
				isLocal = doc.URL.search("file:///") !== -1;
				Jacked.setEngines({ios: true, safari: true, opera: true, firefox: true});
				
				if(defaults.preloadGraphics && !isLocal) {

					$.getJSON(preloaderUrl + "?jackbox_path=" + graphicsPath, jsonLoaded);
					
				}
				
				lights = $this;
				isIE8 = Jacked.getIE();
				touch = Jacked.getMobile();
				browser = Jacked.getBrowser();
				isIE = browser === "ie";
				
				if(touch) showScroll = false;
				
				if(typeof $.address !== "undefined" && deepLinking) {
					
					if(!isIE && !defaults.dynamic) {
						
						$.address.init(init);
						
					}
					else {
						
						init();
						$.address.update();
						
					}
					
				}
				else {
				
					deepLinking = false;
					init();
					
				}
				
				if(typeof StackBlurImage !== "undefined" && !isLocal && !isIE8) {
					
					$(".jackbox-hover-blur").each(drawBlur);
					
				}
				
				$(".jackbox-tooltip").each(addTip);
				defaults = null;
				
			}
			
		},
		
		frameReady: function() {
		
			if(isActive) loaded();
			
		},
		
		// API Method for adding a lightbox item to the stack on the fly
		newItem: function($this, settings) {
			
			$this.each(apiCall, [settings]);
			
		},
		
		// API Method for removing a lightbox item from the stack
		removeItem: function($this) {
		
			$this.each(apiCall, [false, true]);
			
		}
		
	},
	
	loadFrame = (function() {
		
		var obj = {
		
			type: "text/html",
			frameborder: 0,
			mozallowfullscreen: "mozallowfullscreen",
			webkitallowfullscreen: "webkitallowfullscreen",
			allowfullscreen: "allowfullscreen"
			
		};
		
		return function(st, special, scrolls) {
			
			obj.width = wid;
			obj.height = high;
			obj.scrolling = !scrolls ? "no" : "auto";
			
			content = $("<iframe />").attr(obj);
			
			if(!isYoutube) {
				
				content.addClass("jackbox-content");
				
			}
			else {
				
				content.addClass("jackbox-youtube");
				
			}
			
			content.prependTo(container);
			
			if(!special) content.one("load.jackbox", loaded);
			content.attr("src", st);
			
		};
		
	})(),
	
	loaded = (function() {
		
		var obj = {};
		
		return function(event) {
			
			if(event) event.stopPropagation();
			if(isImage) {
			
				oWidth = this.width || content.width();
				oHeight = this.height || content.height();
				setSize();
					
			}
			
			obj.width = wid;
			obj.height = high;
			
			content.css(obj);
			tweenContent(true);
			
			if(itemCallback) itemCallback();
			win.on("resize.jackbox", resized);
			
		};
		
	})(),
	
	tweenContent = (function() {
		
		var tw = {}, tw2 = {}, props = {};
		
		return function(callback) {
		
			var newW;
			
			if(callback) {
				
				if(wid < 260) pushW += 260 - wid;
				newW = Math.max(wid, 260);
				
				if(newW === oldWidth && high === oldHeight) {
					
					showContent();
					return;
					
				}
				
				tw.callback = showContent;
				tw.duration = oldWidth ? Math.abs(newW - oldWidth) > 50 || Math.abs(high - oldHeight) > 50 ? 600 : 300 : 600;
				
			}
			else {
				
				newW = wid;
				props.width = newW;
				
				tw.duration = 300;
				delete tw.callback;
				
				if(topContent) Jacked.tween(topContent[0], props, tw);
				if(bottomContent) Jacked.tween(bottomContent[0], props, tw);
				
				props.height = high;
				Jacked.stopTween(content[0], true);
				Jacked.tween(content[0], props, tw);
				
			}
			
			tw2.marginLeft = -(((pushW >> 1) + 0.5) | 0);
			tw2.marginTop = -(((pushH >> 1) + 0.5) | 0);
			tw2.height = high;
			tw2.width = newW;
			
			if(!oldWidth) tw2.opacity = 1; 

			Jacked.tween(holder[0], tw2, tw);
			
			oldWidth = newW;
			oldHeight = high;
			
		};
		
	})(),
	
	showContent = (function() {
		
		var dur = {}, style = {opacity: 1, visibility: "visible"};
		
		return function() {
			
			showElements();
			preloader.removeClass("jackbox-spin-preloader");
			
			var maxed = Math.max(wid, 260);
			dur.duration = 600;
			
			if(isImage && !isIE8) {
	
				Jacked.fadeIn(content[0], dur);
				
			}
			else {
				
				if(!isYoutube) {
					
					content.show();
					
				}
				else {
					
					content.css('visibility', 'visible');	
					
				}
				
				if(theType === "audio" || theType === "local") {
					
					content[0].contentWindow.cjInit();
					
				}
				
			}
			
			dur.duration = 300;
			
			if(!isIE8) {
				
				if(topContent) {
					
					topContent.css("width", maxed);
					Jacked.fadeIn(topContent[0], dur);
					
				}
				
				if(bottomContent) {
					
					bottomContent.css("width", maxed);
					Jacked.fadeIn(bottomContent[0], dur);
					
				}
				
			}
			else {
			
				if(topContent) topContent.css("width", maxed).show();
				if(bottomContent) bottomContent.css("width", maxed).show();
				
			}
			
			if(info && descText) {
				
				info.show();
				descHolder.html(descText).show();
				
				descHeight = -descHolder.outerHeight();
				description.css("height", -descHeight < high ? -descHeight : high);
				
				if(!showInfoByDefault) {
					
					descVis = false;
					descHolder.css("margin-top", descHeight);
					
				}
				else {
					
					descVis = true;
					info.addClass("jb-info-inactive");
					descHolder.css({visibility: 'visible', marginTop: 0});
					
				}
				
			}
			else if(info) {
				
				info.hide();
				descHolder.hide();
				
			}
			
			isLoading = false;
			
			if(!eventsAdded && legged) {
					
				panelLeft.css(style);
				panelRight.css(style);
				
			}
			
			if(!thumbsChecked && useThumbs && legged) loadThumbs();
			if(!eventsAdded) addEvents();
			
			if(touch) {
				
				content[0].removeEventListener("touchstart", returnFalse, false);
				content[0].removeEventListener("touchmove", returnFalse, false);
				content[0].removeEventListener("touchend", returnFalse, false);	
				removeTouches();
				
			}
			
			if(legged) turnOn();
			if(theType === "inline") sizer();
			
		};
		
	})(),
	
	showElements = (function() {
		
		var obj = {
					
			type: "text/html",
			frameborder: 0,
			mozallowfullscreen: "mozallowfullscreen",
			webkitallowfullscreen: "webkitallowfullscreen",
			allowfullscreen: "allowfullscreen",
			scrolling: "no"
				
		};
		
		return function() {
		
			if(!elements) createElements();
			
			if(!legged) {
			
				if(controls) controls.hide();
				if(showHide) showHide.hide();
				
			}
			
			if(title) {
				
				if(titleText === "false") titleText = false;
				var hasContent = words && titleText, amper = hasContent ? " -&nbsp;" : "";
				
				if(current && legged) {
					
					current.text((isOn + 1)).show();
					total.html(leg + amper).show();
					if(divider) divider.show();
					
				}
				else {
					
					if(total) total.hide();
					if(current) current.hide();
					if(divider) divider.hide();
					
				}
				
				if(hasContent) {
					
					words.html(unescaped);
					
					var a = words.find("a");
					
					if(a.length) {
						
						a.on("click.jackbox", stopProp);
						words.data("links", a);
					
					}
					
				}
				
			}
			
			if(!lightSocial || isLocal) return;
				
			var poster, ur, domain = doc.URL.split("#")[0], len = domain.length - 1;
			
			if(domain.search("/") !== -1) {
			
				if(domain.charAt(len) !== "/") {
						
					(deepLinking) ? ur = domain + "#/" + hash + "/" + (isOn + 1) : ur = domain;
					domain = domain.substring(0, domain.lastIndexOf("/"));
					
				}
				else {
				
					domain = domain.substring(0, len);
					(deepLinking) ? ur = domain + "/#/" + hash + "/" + (isOn + 1) : ur = domain;
					
				}
				
			}
		
			if(isImage) {
				
				poster = $this.attr("href") || $this.attr("data-href");
					
			}
			else {
				
				var alt = $this.children("img");
				poster = alt.length ? alt.attr("src") : defaultShareImage;
				
			}
			
			if(poster.search("http") === -1) poster = poster.charAt(0) !== "/" ? domain + "/" + poster : domain + poster;
			
			var titlText = titleText ? titleText.replace(/(<([^>]+)>)/ig, "") : doc.title;
			titlText = titlText.split('.').join('');
			
			obj.width = socialFrameWidth;
			obj.height = socialFrameHeight;
			obj.src = socialbuttons + 
				
				"?url=" + encodeURIComponent(ur) + 
				"&poster=" + encodeURIComponent(poster) + 
				"&title=" + escape(titlText);
			
			socialFrame = $("<iframe />").attr(obj).appendTo(lightSocial);
			
		};
		
	})(),
	
	toggleInfo = (function() {
		
		var obj1 = {}, obj2 = {duration: 300};
		
		return function(event) {
		
			if(event) event.stopPropagation();
			

			if(!descVis) {
				
				info.addClass("jb-info-inactive");
				description.css("visibility", "visible");
				
				obj1.marginTop = 0;
				delete obj2.callback;
					
			}
			else {
				
				obj1.marginTop = descHeight;
				obj2.callback = infoIndex;
				info.removeClass("jb-info-inactive");
				
			}
			
			Jacked.tween(descHolder[0], obj1, obj2);
			descVis = !descVis;
			
		};
		
	})(),
	
	overThumb = (function() {
		
		var obj = {opacity: 1, visibility: "visible"};
		
		return function() {
		
			if(touch) {
				
				clearTimeout(fader);	
				fader = setTimeout(outThumb, 2000);
				
			}
			
			var $this = $(this), ww, xx, buffer, lft;
			thumbTipText.text($this.data("theTitle"));
			
			ww = parseInt(thumbTipText.css("width"), 10);
			lft = thumbPanel.data("offLeft");
			xx = $this.offset().left;
			
			buffer = lft + thumbPanel.width() - ww - thumbTipBuf;
			
			obj.width = ww;
			obj.left = xx < lft ? lft : xx > buffer ? buffer : xx;
			
			thumbTip.css(obj);
			
		};
		
	})(),
	
	toggleThumbs = (function() {
		
		var obj1 = {}, obj2 = {duration: 300};
		
		return function(event) {
		
			if(event) event.stopPropagation();
			
			if(thumbVis === 0) {
				
				thumbVis = holderHeight;
				
				if(showHide) {
					
					hideThumbs.hide();
					showThumbs.show();
					
				}
					
			}
			else {
				
				thumbVis = 0;
				
				if(showHide) {
					
					showThumbs.hide();
					hideThumbs.show();
					
				}
				
			}
			
			obj1.bottom = thumbVis;
			Jacked.tween(thumbHolder[0], obj1, obj2);
			
			if(winWide < 569) return;
			
			sizer("true");
			tweenContent();
			
		};
		
	})(),
	
	posThumbs = (function() {
		
		var obj = {};
		
		return function(resize) {
		
			var maxWidth = winWide - thumbPanelBuffer;
			
			if(stripWidth < maxWidth) {
			
				numThumbs = totalThumbs;
				arrowsActive = false;
				
			}
			else {
			
				numThumbs = (maxWidth / thmWidth) | 0;
				arrowsActive = true;
				
			}		
			
			currentWidth = (thmWidth * numThumbs) - thumbnailMargin;
			thumbMinus = numThumbs - 1;
			
			obj.marginLeft = -(currentWidth >> 1) - thumbnailMargin;
			obj.width = currentWidth;
			
			thumbPanel.css(obj);
			thumbStrip.css("width", stripWidth);
			
			checkThumbs(resize);
			
		};
		
	})(),
	
	checkThumbs = (function() {
		
		var obj1 = {}, obj2 = {duration: 300};
		
		return function(resize, tween) {
		
			if(resize) {
				
				thumbOn = isOn;
				
				if(isOn !== 0 && isOn + numThumbs > leg) thumbOn = leg - numThumbs;
				
				Jacked.stopTween(thumbStrip[0]);
				thumbStrip.css("left", thumbOn * -thmWidth);
				
			}
			
			else {
				
				if(isOn === 0) {
					
					thumbOn = 0;	
	
				}
				else if(isOn > thumbOn + thumbMinus) {
				
					while(isOn > thumbOn + thumbMinus) thumbOn++;
					
				}
				
				if(tween) {
					
					obj1.left = thumbOn * -thmWidth;
					Jacked.tween(thumbStrip[0], obj1, obj2);
					
				}
				else {
					
					Jacked.stopTween(thumbStrip[0]);
					thumbStrip.css("left", thumbOn * -thmWidth);
					
				}
				
			}
			
			checkArrows(resize, false);
			
		};
		
	})(),
	
	checkArrows = (function() {
		
		var obj1 = {}, obj2 = {duration: 300};
		
		return function(resize, fromArrow) {
			
			thumbLeft.off(".jackbox");
			thumbRight.off(".jackbox");
			
			if(!arrowsActive) {
				
				thumbLeft.hide();
				thumbRight.hide();
				
			}
			else {
				
				if(touch) thumbPanel.cjSwipe("unbindSwipe");
				
				if(thumbOn < leg - numThumbs) {
				
					thumbRight.on("click.jackbox", thumbArrowNext).show();
					
					if(touch) thumbPanel.cjSwipe("touchSwipeLeft", thumbArrowNext, true);
					
				}
				else {
					
					thumbRight.hide();
					
				}
				
				if(thumbOn > 0) {
					
					thumbLeft.on("click.jackbox", thumbArrowPrev).show();
					
					if(touch) thumbPanel.cjSwipe("touchSwipeRight", thumbArrowPrev, true);
					
				}
				else {
					
					thumbLeft.hide();
		
				}
				
				if(fromArrow) {
					
					obj1.left = thumbOn * -thmWidth;
					Jacked.tween(thumbStrip[0], obj1, obj2);
					
				}
				else if(resize || !firstCheck) {
					
					var off = thumbPanel.offset().left;
	
					thumbLeft.css("left", off);
					thumbRight.css("left", off + currentWidth);
					firstCheck = true;
					
				}
					
			}
			
		};
		
	})(),
	
	sizer = (function() {
		
		var obj1 = {opacity: 1}, obj2 = {};
		
		return function(noResize) {
			
			winWide = win.width();
			winTall = Math.max(win.height(), 226);
			
			var tBuf = winWide > 568 && thumbVis === 0 ? thumbBufTall : 0;
			
			if(theType !== "audio" && theType !== "inline") {
			
				scaleUp = !isFullscreen ? upsize : upsize || scaling;
				
			}
			else {
			
				scaleUp = false;
				
			}
			
			if(bufferW < winWide && bufferH + tBuf < winTall && !scaleUp) {
					
				wid = oWidth;
				high = oHeight;
				
			}
			else {
				
				wid = winWide / bufferW;
				high = winTall / bufferH;
	
				var perc = (wid > high) ? high : wid;
				
				wid = oWidth * perc;
				high = oHeight * perc;
				
				if(winWide > winTall) {
				
					if(high + boxed + tBuf > winTall) {
						
						high = winTall - paddingTall - boxBuffer - tBuf;
						wid = oWidth * (high / oHeight);
						
					}
					
				}
				else {
					
					if(wid > high) {
					
						if(wid + bufferW > winWide) {
							
							wid = winWide - boxBuffer;
							high = oHeight * (wid / oWidth);
							
						}
						
					}
					else {
					
						if(high + boxed + tBuf > winTall) {
						
							high = winTall - paddingTall - boxBuffer - tBuf;
							wid = oWidth * (high / oHeight);
							
						}
						
					}
					
				}
				
				if(wid !== (wid | 0)) wid = (wid + 1) | 0;
				if(high !== (high | 0)) high = (high + 1) | 0;
				
			}
			
			if(theType === "inline") {
				
				var w = winWide - paddingWide - panelBuffer - boxBuffer;
				var h = winTall - paddingTall - boxBuffer - tBuf;
				
				wid = oWidth > w ? w : oWidth;
				high = oHeight < h ? oHeight : high;
				
			}
			
			pushW = wid + paddingWide;
			pushH = high + paddingTall + tBuf;

			if(noResize === "true") return;
			
			Jacked.stopTween(holder[0], false, true);
			if(content) Jacked.stopTween(content[0], true, true);
			
			if(wid < 260) pushW += 260 - wid;
			var maxed = Math.max(260, wid);
			
			obj1.width = maxed;
			obj1.height = high;
			
			obj2.marginLeft = -(((pushW * 0.5) + 0.5) | 0);
			obj2.marginTop = -(((pushH * 0.5) + 0.5) | 0);
			obj2.width = maxed;
			obj2.height = high;
			
			holder.css(obj2);
			content.css(obj1);
			
			if(bottomContent) {
				
				Jacked.stopTween(bottomContent[0]);
				bottomContent.css("width", maxed);
				
			}
			
			if(topContent) {
				
				Jacked.stopTween(topContent[0]);	
				topContent.css("width", maxed);
				
			}
			
			if(info && descText) {
				
				descHeight = -descHolder.outerHeight();
				description.css("height", -descHeight < high ? -descHeight : high);
				
				if(!descVis) {
					
					Jacked.stopTween(descHolder[0], false, true);	
					descHolder.css("margin-top", descHeight);
					
				}
				
			}
			
			if(hasThumbs) posThumbs(true);
			
		};
		
	})(),
	
	moveTip = (function() {
		
		var obj = {};
		
		return function(event) {
		
			var data = $(this).data();
			
			obj.left = event.pageX - data.tipX - data.tipWidth;
			obj.top = event.pageY - data.tipY - data.tipHeight;
			
			data.tip.css(obj);
			
		};
		
	})();
	
	$.fn.jackBox = function(func, params) {
		
		if(methods.hasOwnProperty(func)) {
		
			methods[func](this, params);
			
		}
		
		return this;
		
	};
	
	$.jackBox = {
		
		available: function(callback) {
		
			if(!callback) return;
			
			if(jackBoxIsReady) {
			
				(deepLinking) ? setTimeout(callback, 250) : callback();
				
			}
			else {
			
				toCall = callback;
				
			}
			
		},
		
		itemLoaded: function(callback) {
		
			itemCallback = callback;
			
		}
		
	};
	
	function init() {
		
		win = $(window);
		bodies = $("body");
		scroller = $("body, html");
		cover = $("<div />").addClass("jackbox-modal");
		holder = $("<div />").addClass("jackbox-holder");
		wrapper = $("<div />").addClass("jackbox-wrapper");
		preloader = $("<div />").addClass("jackbox-preloader");
		
		panelLeft = $("<span />").addClass("jackbox-panel jackbox-panel-left");
		panelRight = $("<span />").addClass("jackbox-panel jackbox-panel-right");
		
		var frag = doc.createDocumentFragment();
		frag.appendChild(wrapper[0]);
		frag.appendChild(preloader[0]);
		cover[0].appendChild(frag);
		
		frag = doc.createDocumentFragment();
		frag.appendChild(panelLeft[0]);
		frag.appendChild(panelRight[0]);
		frag.appendChild(holder[0]);	
		wrapper[0].appendChild(frag);
	
		container = $("<div />").addClass("jackbox-container");
		
		if(!isIE8) {
			
			var preOutside = $("<span />").addClass("jackbox-pre-outside").appendTo(preloader);
			$("<span />").addClass("jackbox-pre-inside").appendTo(preOutside);
			
		}
		
		boxBuffer *= 2;
		scrollPos = 0;
		frag = doc.createDocumentFragment();
		
		if(!!topMarkup) {
			
			topContent = $(topMarkup).hide();
			frag.appendChild(topContent[0]);
			
		}
		
		frag.appendChild(container[0]);
		
		if(!!bottomMarkup) {
			
			bottomContent = $(bottomMarkup).hide();
			frag.appendChild(bottomContent[0]);
			
		}
		
		holder[0].appendChild(frag);
		holderHeight = -(thumbnailHeight + thumbnailMargin);
		
		descripts = [];
		items = [];
		list = [];
		cats = [];
		
		lights.each(catEach);
		
		if(deepLinking) {
			
			$.address.internalChange(insideChange);
			$.address.externalChange(browserChange);
			
		}
		
		jackBoxIsReady = true;
		
		if(toCall) {
			
			(deepLinking) ? setTimeout(toCall, 250) : toCall();
			toCall = null;
			
		}
		
		lights = items = topMarkup = bottomMarkup = descripts = null;
		
	}
	
	function apiCall(settings, remove) {
	
		var $this = $(this),
		i = cats.length, 
		found = -1,
		listed,
		group,
		fnd,
		str,
		j;
		
		if(settings && typeof settings === "object") {
			
			var prop, itm, href = $this.attr("href");
			
			for(prop in settings) {
				
				if(settings.hasOwnProperty(prop) && prop !== "trigger") {
							
					itm = settings[prop]; 
					if(prop !== "href" || !href) $this.attr("data-" + prop, itm);
					if(prop === "group") group = itm;
					
				}
				
			}
			
		}
		
		if(!group) group = $this.attr("data-group");
		if(!group) return;
		
		str = group.split(" ").join("").toLowerCase();
		
		while(i--) {
			
			if(cats[i] === str) {
			
				found = i;
				break;
				
			}
			
		}
		
		if(found > -1) {
		
			listed = list[found];
			i = listed.length;
			
			while(i--) {
				
				fnd = listed[i];
				
				if(fnd[0] === $this[0]) {
					
					if(remove) {
						
						listed.splice(i, 1);
						fnd.off('click.jackbox');
						j = listed.length;
						
						if(j) {
							
							for(var k = 0; k < j; k++) {
								
								listed[k].data('id', k);
								
							}
							
						}
						else {
							
							list.splice(found, 1);
							cats.splice(found, 1);
							numCats--;
							
						}
						
					}
					
					return;
					
				}
				
			}
			
			i = listed.length;
			listed[i] = $this;
			
		}
		else {
			
			if(remove) return;
			
			found = cats.length;
			i = list.length;
			
			cats[found] = str;
			numCats++;
			
			list[i] = [$this];
			i = 0;
			
		}
		
		itemEach($this);
		$this.data({id: i, cat: found});
		if(settings && settings.trigger) $this.trigger("click");
		
	}
	
	function catEach() {
		
		var str = $(this).attr("data-group").split(" ").join("").toLowerCase();
		
		if(!isIE8) {
		
			if(cats.indexOf(str) === -1) addCat(str);
			
		}
		else {
		
			var i = cats.length, found = false;
			
			while(i--) {
				
				if(cats[i] === str) {
				
					found = true;
					break;
					
				}
				
			}
			
			if(!found) addCat(str);
			
		}
		
	}
	
	function addCat(str) {
	
		cats[cats.length] = str;
		items = [];
	
		$(className + "[data-group=" + str + "]").each(itemEach);
		
		list[list.length] = items;
		numCats++;
		
	}
	
	function itemEach(i) {
		
		if(!isNaN(i)) {
			
			$this = $(this).data({id: i, cat: numCats});	
			items[i] = $this;
			
		}
		else {
		
			$this = i;
			
		}
		
		url = $this.attr("href") || $this.attr("data-href");
		if(!url) return;
			
		var popped, video;
		
		if(url.charAt(0) !== "#") {
		
			popped = url.toLowerCase().split(".").pop();
			
		}
		else {
		
			popped = "inline";
			
		}
		
		video = checkVideo(url, popped);
		
		if(video) {
		
			$this.data("type", video);	
			
			if(!$this.attr("data-thumbnail")) {
			
				if(video === "vimeo") {
					
					getVimeoThumb($this, url);
					
				}
				else if(video === "youtube") {
					
					$this.attr("data-thumbnail", "http://img.youtube.com/vi/" + url.split("http://www.youtube.com/watch?v=")[1] + "/1.jpg");
					
				}
				
			}
			
		}
		else if(checkImage(popped)) {
			
			$this.data("type", "image");
			
		}
		else {
			
			switch(popped) {
			
				case "mp3":
				
					$this.data("type", "audio");
				
				break;
				
				case "swf":
				
					$this.data("type", "swf");
				
				break;
				
				case "inline":
				
					$this.data("type", "inline");
				
				break;
				
				default:
				
					$this.data("type", "iframe");
				
				// end default
				
			}
			
		}
		
		$this.on("click.jackbox", clicked);
		dataDesc = $this.attr("data-description");
		
		if(!dataDesc) return;
		
		// if the same description is used for more than one item we only hit the DOM once for it
		if(descripts) {
		
			var dIndex = descripts.indexOf(dataDesc);
			
			if(dIndex === -1) {
				
				dataDesc = $(dataDesc);
				
				if(!dataDesc.length) return;
				descripts[descripts.length] = dataDesc;
				
			}
			else {
			
				dataDesc = descripts[dIndex];
				
			}
			
		}
		else {
		
			dataDesc = $(dataDesc);
			if(!dataDesc.length) return;
			
		}
		
		$this.data("description", dataDesc);
		
	}
	
	function browserChange(event) {
		
		if(initialLoad) {
		
			initialLoad = false;
			
			var url = doc.URL, splits = url.split("?url=");
			
			if(splits.length === 2) {
				
				window.location = splits[0] + "#/" + splits[1];
				return;
				
			}
			
		}
		
		clearTimeout(runTime);
		getHash(event.value);
		
		if(catOn !== -1) {
		
			if(firstRun) {
				
				firstRun = false;
				insideChange();	
				
			}
			else {
				
				runTime = setTimeout(insideChange, 750);
				
			}
			
		}
		else if(isActive) {
			
			closing();
			
		}
		
	}
	
	function insideChange(event) {
		
		if(typeof event === "object") {
			
			clearTimeout(runTime);
			getHash(event.value);
			
		}
		
		if(catOn !== -1) {
			
			loadItem();
				
		}
		else if(isActive) {
			
			closing();
			
		}
		
	}
	
	function getHash(val) {
		
		if(hasThumbs && !fromThumb && !arrowClicked) thumbs[isOn].removeClass("jb-thumb-active");
			
		if(val !== "/") {
			
			var ars = val.split("/");
			
			if(ars.length === 3) {
				
				isOn = parseInt(ars[2], 10) - 1;
				
				if(isNaN(isOn)) isOn = 0;
				hash = ars[1];
				
			}
			else {
				
				if(isNaN(ars[1])) {
					
					isOn = 0;
					hash = ars[1];
					
				}
				else {
					
					isOn = parseInt(ars[1], 10) - 1;
					hash = "/";
					
				}
				
			}
			
		}
		else {
			
			hash = "/";
			isOn = 0;
			
		}
		
		if(hash !== "/") {
			
			var i = numCats;
			
			while(i--) {
				
				if(cats[i] === hash) {
					
					catOn = i;
					leg = list[catOn].length;
					legged = leg !== 1;
					break;
					
				}
				
			}
			
		}
		else {
			
			catOn = -1;
			
		}
		
		arrowClicked = false;
		
	}
	
	function resized() {
			
		clearTimeout(resizeThrottle);
		resizeThrottle = setTimeout(sizer, 100);
		
	}
	
	function clicked(event) {
		
		event.stopPropagation();
		event.preventDefault();
		
		var data = $(this).data();
		runItem(data.cat, data.id, true);
		
	}
	
	function runItem(cat, id, first) {
		
		clearTimeout(runTime);
		if(!first) turnOff();
		
		if(deepLinking) {
			
			$.address.value(cats[cat] + "/" + (id + 1));
			
		}
		else {
			
			if(hasThumbs && !fromThumb && !arrowClicked) thumbs[isOn].removeClass("jb-thumb-active");
			
			isOn = id;
			catOn = cat;
			leg = list[catOn].length;
			legged = leg !== 1;
			loadItem();
			
		}
		
	}
	
	function nextItem(event) {
		
		if(event) {
			
			event.stopPropagation();
			if(isLoading) return false;
			
		}
		
		if(!isActive) return;
		if(hasThumbs && !fromThumb) thumbs[isOn].removeClass("jb-thumb-active");
		
		((isOn) < list[catOn].length - 1) ? isOn++ : isOn = 0;
		
		arrowClicked = true;
		runItem(catOn, isOn);
		
	}
	
	function previousItem(event) {
		
		if(event) {
			
			event.stopPropagation();
			if(isLoading) return false;
			
		}
		
		if(!isActive) return;
		if(hasThumbs && !fromThumb) thumbs[isOn].removeClass("jb-thumb-active");
		
		((isOn) > 0) ? isOn-- : isOn = list[catOn].length - 1;
		
		arrowClicked = true;
		runItem(catOn, isOn);
		
	}
	
	function addTouches() {
		
		var ar = [cover[0], wrapper[0], holder[0], container[0]], i = 4;
		
		while(i--) {
		
			ar[i].addEventListener("touchstart", returnFalse, false);
			ar[i].addEventListener("touchmove", returnFalse, false);
			ar[i].addEventListener("touchend", returnFalse, false);
			
		}
		
	}
	
	function removeTouches() {
		
		var ar = [cover[0], wrapper[0], holder[0], container[0]], i = 4;
		
		while(i--) {
		
			ar[i].removeEventListener("touchstart", returnFalse, false);
			ar[i].removeEventListener("touchmove", returnFalse, false);
			ar[i].removeEventListener("touchend", returnFalse, false);
			
		}
		
	}
	
	function loadItem() {
		
		currentCat = list[catOn];
		$this = currentCat[isOn];
		
		if(!$this) return;
		
		if(touch) addTouches();
		url = $this.attr("href") || $this.attr("data-href");
		
		if(isActive) {
			
			isLoading = true;
			killActive();
			loadContent();
			
		}
		else {
			
			isActive = true;
			if(!touch) scroller.stop();
			scrollPos = win.scrollTop();
			
			cover.appendTo(bodies).one("click.jackbox", closer);
			if(!showScroll) parents = cover.parents().each(addOverflow);
			if(keyboard) docs.on("keydown.jackbox_keyboard", checkKeyClose); 
			
			if(!preAdded) {
				
				preAdded = true;
				paddingWide = parseInt(holder.css("padding-left"), 10) + parseInt(holder.css("padding-right"), 10);
				paddingTall = parseInt(holder.css("padding-top"), 10) + parseInt(holder.css("padding-bottom"), 10);
				panelBuffer = parseInt(panelLeft.css("width"), 10) + 14;
				
				boxed = paddingTall + boxBuffer;
				thumbBufTall = thumbnailHeight + (thumbnailMargin << 1);
				preBuf = parseInt(preloader.css("margin-top"), 10);
				preThumbBuf = preBuf - (thumbBufTall >> 1);

				holderPadLeft = parseInt(holder.css("padding-left"), 10);
				holderPadTop = parseInt(holder.css("padding-top"), 10);
				
				fullNormal = $(".jackbox-fullscreen");
				if(!fullNormal.length) fullNormal = null;
				
				hasFullscreen = !touch && browser !== "safari" && ("webkitRequestFullScreen" in cover[0] || "mozFullScreenEnabled" in doc);
				
			}
			
			thumbVis = !thumbsStartHidden && useThumbs && legged ? 0 : holderHeight;
			winWide = win.width();
			winTall = win.height();
			
			holder.css({
				
				width: winWide, 
				height: winTall, 
				marginLeft: -(winWide >> 1) - holderPadLeft,
				marginTop: -(winTall >> 1) - holderPadTop
				
			});
			
			Jacked.fadeIn(cover[0], {callback: addScroll});
			timer = setTimeout(loadContent, 250);
			wrapper.on("click.jackbox", preventDefault);
				
		}
		
	}
	
	function addOverflow() {
		
		$(this).addClass("jackbox-overflow");
		
	}
	
	function removeOverflow() {
	
		$(this).removeClass("jackbox-overflow");
		
	}
	
	function addScroll() {
		
		if(!showScroll) win[0].scrollTo(0, 0);
		
	}
	
	function convert(st) {
	
		return st === "true" || st === true;
		
	}
	
	function loadContent() {
		
		if(hasThumbs) {
			
			thumbs[isOn].addClass("jb-thumb-active");
			
			(fromThumb) ? fromThumb = false : checkThumbs(false, true);
			
		}
		
		var autoplay = convert($this.attr("data-autoplay") ? $this.attr("data-autoplay") : autoPlayVideo), 
		thisDesc = $this.data("description") || null,
		thisTitle = $this.attr("data-title") || "",
		passedLocal;
		
		upsize = $this.attr("data-scaleUp") === "true";
		descText = thisDesc && typeof thisDesc !== "string" ? thisDesc.html() : false;
		theType = $this.data("type");
		autoplay = convert(autoplay);
		
		if(thisTitle) {
		
			unescaped = thisTitle;
			titleText = escape(unescaped);
			
		}
		else {
		
			titleText = false;
			
			if(typeof words !== "undefined") {
				
				if(words.data("links")) words.data("links").off(".jackbox");
				words.empty();
				
			}
			
		}
		
		if(touch) {
			
			swipeable = theType === "image";
			if(theType !== "inline" && theType !== "iframe") doc.addEventListener("touchmove", returnFalse, false);
			
		}
		
		if(theType !== "image") writeSize();
		isYoutube = false;
		
		if(winWide > 568) {
			
			preloader.css("margin-top", thumbVis === 0 ? preThumbBuf : preBuf);
			
		}
		else {
		
			preloader.css("margin-top", preBuf);
			
		}
		
		wrapper.show();
		preloader.addClass("jackbox-spin-preloader");
		
		switch(theType) {
		
			case "image":
				
				isImage = true;
				content = $("<img />").addClass("jackbox-content").one("load.jackbox", loaded).prependTo(container);
				
				if(touch) {
					
					content[0].addEventListener("touchstart", returnFalse, false);
					content[0].addEventListener("touchmove", returnFalse, false);
					content[0].addEventListener("touchend", returnFalse, false);
					
				}
				
				content.attr("src", url);
			
			break;
			
			case "youtube":
				
				if(touch) isYoutube = true;
				loadFrame(youTubeMarkup.split("{url}").join(url.split("watch?v=")[1]).split("{autoplay}").join(autoplay ? 1 : 0));
				
			break;
			
			case "vimeo":
				
				if(touch) isYoutube = true;
				loadFrame(vimeoMarkup.split("{url}").join(url.substring(url.lastIndexOf("/"))).split("{autoplay}").join(autoplay));
			
			break;
			
			case "local":
				
				var vPoster = fullPath(),
				ffUsesFlash = $this.attr("data-firefoxUsesFlash") === "true" ? "true" : "false",
				flashing = ($this.attr("data-flashHasPriority") ? $this.attr("data-flashHasPriority") : flashVideoFirst.toString());
				passedLocal = flashing === "false" && hasFullscreen && browser !== "firefox";
				
				if($this.attr("data-poster")) {
					
					vPoster += $this.attr("data-poster");
					
				}
				else {
					
					vPoster = "false";
					
				}
				
				loadFrame(
				
					videoPlayer + 
					"?video=" + url + 
					"&autoplay=" + autoplay + 
					"&flashing=" + flashing + 
					"&width=" + oWidth +
					"&height=" + oHeight +
					"&poster=" + vPoster +
					"&firefox=" + ffUsesFlash,
					
				true);
				
			break;
			
			case "audio":
				
				fullPath();
				loadFrame(audioPlayer + "?audio=" + url + "&title=" + ($this.attr("data-audiotitle") ? $this.attr("data-audiotitle") : titleText) + "&autoplay=" + autoplay);
			
			break;
			
			case "swf":
				
				fullPath();
				loadFrame(swfPlayer + "?swf=" + url + "&width=" + (wid.toString() + "&height=" + high.toString()));
			
			break;
			
			case "inline":
				
				var htm = $(url), con = htm.length ? htm.html() : "";
				content = $("<div />").addClass("jackbox-content jackbox-html").html(con).prependTo(container);
				content.css("width", wid).find("a").on("click", stopProp);
				
				$this.attr("data-height", content.outerHeight(true));
				writeSize();
				loaded();
			
			break;
			
			default:
				
				loadFrame(url, false, true);
			
			// end default
			
		}
		
		if(!hasFullscreen) return;
		(!passedLocal) ? fullNormal.show() : fullNormal.hide();
		
	}
	
	function fullPath() {
		
		if(url.search("http") !== -1) return "";
					
		var root = doc.URL.split("#")[0];
		
		if(root[root.length - 1] !== "/") {
			
			root = root.substring(0, root.lastIndexOf("/") + 1);
			
		}
		
		url = root + url;
		return root;
		
	}
	
	function turnOn() {
		
		if(hasThumbs) {
	
			var i = thumbs.length;
			while(i--) thumbs[i].on("click.jackbox", thumbClick);
			
		}
		
		panelLeft.on("click.jackbox", previousItem);
		panelRight.on("click.jackbox", nextItem);
	
		if(keyboard) docs.on("keydown.jackbox", checkKey); 
		if(touch && swipeable) content.cjSwipe("touchSwipe", catchSwipe);
		
	}
	
	function turnOff() {
		
		if(hasThumbs) {
		
			var i = thumbs.length;
			while(i--) thumbs[i].off("click.jackbox");
			
		}
		
		panelLeft.off(".jackbox");
		panelRight.off(".jackbox");
	
		if(keyboard) docs.off("keydown.jackbox"); 
		if(touch && swipeable) content.cjSwipe("unbindSwipe");
		
	}
	
	function checkKey(event) {
		
		switch(event.keyCode) {
			
			case 39:
			
				nextItem();
			
			break;
			
			case 37:
			
				previousItem();
			
			break;
			
			case 40:
			
				toggleThumbs();
			
			break;
			
			case 38:
			
				toggleThumbs();
			
			break;
			
		}
		
	}
	
	function checkKeyClose(event) {
		
		if(event.keyCode === 27) closer(event);

	}
	
	function infoIndex() {
		
		description.css("visibility", "hidden");
		
	}
	
	function ripThumbs() {
		
		var frag = doc.createDocumentFragment(), halfHeight = thumbnailHeight >> 1;
		
		thumbHolder = $("<div />").addClass("jackbox-thumb-holder").css("height", thumbnailHeight).appendTo(cover);
		thumbPanel = $("<div />").addClass("jackbox-thumb-panel").css("height", thumbnailHeight);
		thumbRight = $("<div />").addClass("jackbox-thumb-right");
		thumbLeft = $("<div />").addClass("jackbox-thumb-left");
		
		frag.appendChild(thumbPanel[0]);
		frag.appendChild(thumbRight[0]);
		frag.appendChild(thumbLeft[0]);
		
		thumbPanel[0].cjThumbs = true;
		thumbHolder[0].appendChild(frag);
		thumbStrip = $("<div />").addClass("jackbox-thumb-strip").appendTo(thumbPanel);

		thumbLeft.css("top", halfHeight);
		thumbRight.css("top", halfHeight);
		
	}
	
	function loadThumbs() {
		
		var cur = list[catOn], 
		ar = [], 
		i = leg, 
		titles,
		holds, 
		$this,
		frag,
		imgs, 
		img,
		ww,
		hh,
		pc;
		
		thumbsChecked = true;
		
		while(i--) {
			
			$this = cur[i];
			if($this.attr("data-thumbnail")) {
				
				ar[i] = false;	
				continue;
				
			}
			
			imgs = $this.children("img");
			if(imgs.length) {
			
				$this.attr("data-thumbnail", imgs.attr("src"));
				ar[i] = imgs;
				
			}
			else if($this.data("type") === "image") {
			
				$this.attr("data-thumbnail", $this.attr("href") || $this.attr("data-href"));
				ar[i] = false;	
				
			}
			else {
			
				$this.attr("data-thumbnail", defaultThumb);
				ar[i] = false;	
				
			}
				
		}
		
		thumbs = [];
		if(!thumbHolder) ripThumbs();
		frag = doc.createDocumentFragment();
		
		for(i = 0; i < leg; i++) {
			
			holds = thumbs[i] = $("<div />").data("id", i).addClass("jackbox-thumb").css({
				
				width: thumbnailWidth, 
				height: thumbnailHeight, 
				left: thmWidth * i
				
			}).on("click.jackbox", thumbClick);
			
			if(useTips) {
			
				titles = currentCat[i].attr("data-thumbTooltip") || currentCat[i].attr("data-title");
				if(titles) holds.data("theTitle", titles).on("mouseenter.jackbox", overThumb).on("mouseleave.jackbox", outThumb);
				
			}
			
			frag.appendChild(holds[0]);
			img = $("<img />").addClass("jb-thumb").one("load.jackbox", thumbLoaded).appendTo(holds);
			holds.data("theThumb", img);
			
			if(ar[i]) {
			
				ww = ar[i].attr("width") || ar[i].width();
				hh = ar[i].attr("height") || ar[i].height();
				
			}
			else {
			
				ww = thumbnailWidth;
				hh = thumbnailHeight;
				
			}
			
			if(ww > thumbnailWidth && hh > thumbnailHeight) {
				
				pc = ww > hh ? thumbnailWidth / ww : thumbnailHeight / hh;
				
				ww *= pc;
				hh *= pc;
				
				if(hh < thumbnailHeight) {
				
					var dif = (thumbnailHeight - hh) / thumbnailHeight;
					
					ww += ww * dif;
					hh += hh * dif;
					
				}
				
				if(ww < thumbnailWidth) {
				
					var difs = (thumbnailWidth - ww) / thumbnailWidth;
					
					ww += ww * difs;
					hh += hh * difs;
					
				}
				
				if(ww !== (ww | 0)) ww = (ww + 1) | 0;
				if(hh !== (hh | 0)) hh = (hh + 1) | 0;
				
			}
			
			img.attr({width: ww, height: hh, src: cur[i].attr("data-thumbnail")});
						
		}
		
		thumbStrip[0].appendChild(frag);
		totalThumbs = thumbs.length;
		stripWidth = thmWidth * i;
		hasThumbs = true;
		
		thumbOn = 0;
		thumbHolder.on("click.jackbox", preventDefault).show();
		posThumbs();
		
		if(!showHide) return;
		if(!thumbsStartHidden) {
			
			showThumbs.hide();
			hideThumbs.show();
			thumbHolder.css("bottom", 0);
			
		}
		else {

			hideThumbs.hide();
			showThumbs.show();
			thumbHolder.css("bottom", thumbVis);
			
		}
		
		showHide.on("click.jackbox", toggleThumbs);

	}
	
	function thumbEnter() {
	
		thumbPanel.data("offLeft", thumbPanel.offset().left);
		
	}
	
	function outThumb() {
	
		thumbTip.css({opacity: 0, visibility: "hidden"});
		
	}
	
	function thumbArrowNext(event) {
		
		if(typeof event === "object") event.stopPropagation();
		
		if(thumbOn < leg - numThumbs) {
					
			thumbOn++;
			checkArrows(false, true);
			
		}
		
	}
	
	function thumbArrowPrev(event) {
		
		if(typeof event === "object") event.stopPropagation();
		
		if(thumbOn > 0) {
					
			thumbOn--;
			checkArrows(false, true);
			
		}
		
	}
	
	function thumbLoaded(event) {
		
		event.stopPropagation();
		
		var $this = $(this).parent();
		$this.addClass("jb-thumb-fadein");
		
		if(!touch) $this.addClass("jb-thumb-hover");
		if($this.data("id") === isOn) $this.addClass("jb-thumb-active");
		
	}
	
	function thumbClick(event) {
		
		event.stopPropagation();
		if(isLoading) return false;
		
		var $this = $(this), id = $this.data("id");
		
		if(id === isOn) return;
		if(hasThumbs) thumbs[isOn].removeClass("jb-thumb-active");
		
		isOn = id;
		fromThumb = true;
		runItem(catOn, isOn);
		
	}
	
	function addEvents() {
		
		eventsAdded = true;
		
		if(hasFullscreen) fullNormal.on("click.jackbox", toggleFull);
		if(closeBtn) closeBtn.one("click.jackbox", closer);
		if(info) info.on("click.jackbox", toggleInfo);
		
		if(!legged) return;
		if(goRight) goRight.on("click.jackbox", nextItem);
		if(goLeft) goLeft.on("click.jackbox", previousItem);
		if(useTips && thumbPanel) thumbPanel.on("mouseenter.jackbox", thumbEnter);
		
		if(touch && description) {
				
			description[0].addEventListener("touchstart", stopProp, false);
			description[0].addEventListener("touchmove", stopProp, false);
			description[0].addEventListener("touchend", stopProp, false);	
			
		}

	}
	
	function toggleFull() {
		
		if(!isFullscreen) {
			
			win.off(".jackbox");
			isFullscreen = true;
			
			if(doc.mozFullScreenEnabled) {
				
				doc.addEventListener("mozfullscreenchange", fsChange, false);
				cover[0].mozRequestFullScreen();
					
			}
			else if(cover[0].webkitRequestFullScreen) {
				
				doc.addEventListener("webkitfullscreenchange", fsChange, false);
				cover[0].webkitRequestFullScreen();
				
			}
			
		}
		else {
			
			exitFull();
			
		}
		
	}
	
	function fsChange() {
	
		if(doc.webkitIsFullScreen || doc.mozFullScreen) {
			
			sizer();	
			
		}
		else {
		
			exitFull(true);
			
		}
		
	}
	
	function nativeExit(event) {
		
		doc.removeEventListener(event.type, nativeExit, false);
		
		sizer();
		
		win.on("resize.jackbox", resized);
		
	}
	
	function exitFull(fromNative) {
		
		isFullscreen = false;
		
		if(doc.mozFullScreenEnabled) {
			
			doc.removeEventListener("mozfullscreenchange", fsChange, false);
			
			if(fromNative) {
				
				sizer();
				win.on("resize.jackbox", resized);
				
			}
			else {
			
				doc.addEventListener("mozfullscreenchange", nativeExit, false);
				doc.mozCancelFullScreen();
				
			}

		}
		else if(cover[0].webkitRequestFullScreen) {
			
			doc.removeEventListener("webkitfullscreenchange", fsChange, false);
			
			if(fromNative) {
				
				sizer();
				win.on("resize.jackbox", resized);
				
			}
			else {
				
				doc.addEventListener("webkitfullscreenchange", nativeExit, false);
				doc.webkitCancelFullScreen();
				
			}
			
		}
		
	}
	
	function writeSize() {
		
		isImage = false;
		
		oWidth = $this.attr("data-width") ? parseInt($this.attr("data-width"), 10) : defaultVideoWidth;
		oHeight = $this.attr("data-height") ? parseInt($this.attr("data-height"), 10) : defaultVideoHeight;
		upsize = $this.attr("data-scaleUp") === "true";
		
		setSize();
		
	}
	
	function setSize() {
		
		bufferW = oWidth + paddingWide + panelBuffer + boxBuffer;
		bufferH = oHeight + boxed;
		
		sizer("true");
		
	}
	
	function killActive() {
		
		clearTimeout(timer);
		Jacked.stopTween(holder[0]);
		
		win.off(".jackbox");
		if(touch) doc.removeEventListener("touchmove", returnFalse, false);
		
		if(content) {
			
			Jacked.stopTween(content[0]);
			content.remove();
			content = null;
				
		}
		
		if(socialFrame) {
		
			socialFrame.remove();
			socialFrame = null;
			
		}
		
		if(topContent) {
			
			Jacked.stopTween(topContent[0], true);
			topContent.hide();
			
		}
		
		if(bottomContent) {
			
			Jacked.stopTween(bottomContent[0], true);
			bottomContent.hide();
			
		}
		
		if(!info) return;
			
		info.removeClass("jb-info-inactive");
		Jacked.stopTween(descHolder[0]);
		descHolder.empty().hide();
		
	}
	
	function closer(event) {
		
		event.stopPropagation();
		
		(deepLinking) ? $.address.value("") : closing();
		
	}
	
	function killThumbs() {
		
		Jacked.stopTween(thumbHolder[0]);
		thumbHolder.off(".jackbox").hide();
		
		var thumber;	
			
		while(thumbs.length) {
			
			thumber = thumbs[0];
			Jacked.stopTween(thumber[0]);
			
			thumber.remove();
			thumbs.shift();
			
		}
		
		thumbLeft.off(".jackbox").hide();
		thumbRight.off(".jackbox").hide();
		
		if(touch) thumbPanel.cjSwipe("unbindSwipe");
		
		Jacked.stopTween(thumbStrip[0]);
		thumbStrip.empty().css("margin-left", 0);
		
		if(showHide) {
			
			showHide.off(".jackbox");
			
			if(showHide) {
				
				showThumbs.hide();
				hideThumbs.show();
				
			}
			
		}
		
		hasThumbs = thumbs = null;
		
	}
	
	function closing() {
		
		clearTimeout(runTime);
		
		killActive();
		cover.unbind(".jackbox");
		if(keyboard) docs.off("keydown.jackbox_keyboard"); 
		
		if(legged) {
			
			if(keyboard) docs.off("keydown.jackbox"); 
			if(goLeft) goLeft.off(".jackbox");
			if(goRight) goRight.off(".jackbox");
			if(useTips && thumbPanel) thumbPanel.off(".jackbox");
			
			Jacked.stopTween(panelRight[0], true);
			Jacked.stopTween(panelLeft[0], true);
			
			var style = {opacity: 0, visibility: "hidden"};
			panelRight.off(".jackbox").css(style);
			panelLeft.off(".jackbox").css(style);
			
		}
		else {
		
			if(controls) controls.show();
			if(showHide) showHide.show();
			
		}
		
		wrapper.hide().off(".jackbox");
		preloader.removeClass("jackbox-spin-preloader");
		
		if(typeof words !== "undefined") {
			
			if(words.data("links")) words.data("links").off(".jackbox");
			words.empty();
			
		}
		
		if(hasFullscreen) fullNormal.off(".jackbox");
		if(closeBtn) closeBtn.unbind(".jackbox");
		if(info) info.off(".jackbox");
		if(hasThumbs) killThumbs();
		
		if(!showScroll) parents.each(removeOverflow);
		Jacked.fadeOut(cover[0], {duration: 1000, callback: onFaded});
		holder.css({marginLeft: holOrigLeft, marginTop: holOrigTop});
		
		if(touch) {
			
			removeTouches();
			doc.removeEventListener("touchmove", returnFalse, false);
			
		}
		
		setTimeout(scrollback, 10);
		
		if(description) {
			
			description.css("visibility", "hidden");
			
			if(touch) {
				
				description[0].removeEventListener("touchstart", stopProp, false);
				description[0].removeEventListener("touchmove", stopProp, false);
				description[0].removeEventListener("touchend", stopProp, false);	
				
			}
			
		}
		
		$this = isActive = isFullscreen = fromThumb = firstCheck = eventsAdded = arrowClicked = thumbsChecked = oldWidth = null;
		
	}
	
	function scrollback() {
	
		if(scrollPos !== 0) {
			
			if(!showScroll && !touch) {
			
				scroller.animate({scrollTop: scrollPos}, {duration: 300, queue: false});
				
			}
			else {
			
				scroller.scrollTop(scrollPos);
				
			}
			
		}
		
	}
	
	function onFaded() {
		
		cover.detach();
		
	}
	
	function catchSwipe(left) {
		
		(!left) ? nextItem() : previousItem();
		
	}
	
	function returnFalse(event) {
	
		event.preventDefault();
		
	}
	
	function createElements() {
		
		elements = true;
		total = $(".jb-total");
		info = $(".jackbox-info");
		divider = $(".jb-divider");
		current = $(".jb-current");
		closeBtn = $(".jackbox-close");
		title = $(".jackbox-title-text");
		words = $(".jackbox-title-txt");
		controls = $(".jackbox-controls");
		goLeft = $(".jackbox-arrow-left");
		lightSocial = $(".jackbox-social");
		goRight = $(".jackbox-arrow-right");
		showHide = $(".jackbox-button-thumbs");
		showThumbs = $(".jackbox-show-thumbs");
		hideThumbs = $(".jackbox-hide-thumbs");
		
		if(!words.length) words = null;
		if(!title.length) title = null;
		if(!goLeft.length) goLeft = null;
		if(!divider.length) divider = null;
		if(!goRight.length) goRight = null;
		if(!controls.length) controls = null;
		if(!closeBtn.length) closeBtn = null;
		if(!lightSocial.length) lightSocial = null;
		if(!current.length || !total.length) current = null;
		
		if(hasFullscreen) {
			
			$(".jackbox-ns").hide();
			if(!fullNormal.length) fullNormal = hasFullscreen = null;
			
		}
		else if(fullNormal) {
		
			fullNormal.hide();
			
		}
		
		if(useThumbs) {
		
			if(showHide.length && showThumbs.length && hideThumbs.length) {
				
				showThumbs.hide();
				
			}
			else {
				
				showHide = showThumbs = hideThumbs = null;
				
			}
			
		}
		else {
		
			showHide.hide();
			showHide = showThumbs = hideThumbs = null;
			
		}
		
		if(info.length) {
			
			description = $("<div />").addClass("jackbox-info-text").appendTo(container).css("visibility", "hidden");
			descHolder = $("<div />").addClass("jackbox-description-text").appendTo(description);
			
		}
		else {
		
			info = null;
			
		}
		
		if(!useTips) return;
		
		thumbTip = $("<span />").addClass("jackbox-thumb-tip").css("bottom", thumbnailHeight);
		thumbTipText = $("<span />").addClass("jackbox-thumb-tip-text").text("render me").appendTo(thumbTip);
		
		thumbTip.appendTo(cover);
		thumbTipBuf = (parseInt(thumbTip.css("padding-left"), 10) << 1) - (thumbnailMargin << 1);
		
	}
	
	function jsonLoaded(data, response) {
		
    	if(isActive || response.toLowerCase() !== "success" || !data) return;
			
		var i = data.length, base = document.URL;
		base = base.substring(0, base.lastIndexOf("/"));
		
		while(i--) {
			
			$("<img />").attr("src", baseName + "/" + data[i].split("../").join(""));
			
		}
		
	}
	
	function getVimeoThumb($video, url) {
						
		$.getJSON("http://vimeo.com/api/v2/video/" + url.split("http://vimeo.com/")[1] + ".json?callback=?", {format: "json"}, function(data) {
			
			$video.attr("data-thumbnail", data[0].thumbnail_small);
			
		});
		
	}
				
	function drawBlur() {
		
		var $this = $(this), img = $this.next("img"), newImg, href = img.attr("src");
		
		if(!img.length) return;
			
		newImg = $("<img />").attr({
			
			width: img.attr("width"),
			height: img.attr("height")
			
		}).data("parent", $this).one("load.jackbox", blurThumbLoaded).insertAfter(img);
		
		img.remove();
		newImg.attr("src", href);
		
	}
	
	function blurThumbLoaded() {
		
		var img = $(this), 
		$this = img.data("parent"),
		
		width = parseInt($this.css("width"), 10) || $this.width(),
		height = parseInt($this.css("height"), 10) || $this.height(),
		
		canvas = $("<canvas />").addClass("jackbox-canvas-blur").attr({
			
			width: width, 
			height: height
			
		}).insertBefore($this),
			
		now = Date.now(),
		imgId = now + 1,
		canvasId = now + 2;
		
		img.attr("id", imgId);
		canvas.attr("id", canvasId);
		StackBlurImage(imgId, canvasId, 29);
		
	}
	
	function addTip() {
	
		var $this = $(this);
		
		$this.parent().data({
			
			tip: $this,
			tipWidth: $this.width() - 27,
			tipHeight: $this.height() + 17
			
		}).on("mouseenter.jackbox", overTip).on("mouseleave.jackbox", outTip);
		
	}
	
	function overTip() {
		
		var $this = $(this), off = $this.offset(), data = $this.data();
			
		data.tipX = off.left,
		data.tipY = off.top,
		data.tip.css({opacity: 1, visibility: "visible"});
		
		$this.on("mousemove.jackbox", moveTip);
		
	}
	
	function outTip() {
		
		var $this = $(this).off("mousemove.jackbox");
		
		if(!isIE8) {
			
			$this.data("tip").css({opacity: 0, visibility: "hidden"});
			
		}
		else {
		
			$this.data("tip").css("opacity", 0);
			
		}
		
	}
	
	function checkImage(st) {
		
		return st === "jpg" || st === "png" || st === "jpeg" || st === "gif";
		
	}
	
	function checkVideo(st, popped) {
		
		if(st.search("youtube.com") !== -1) {
			
			return "youtube";
			
		}
		else if(st.search("vimeo.com") !== -1) {
			
			return "vimeo";
			
		}
		else if(popped === "mp4") {
			
			return "local";
			
		}
		else {
		
			return false;
			
		}
		
	}
	
	function stopProp(event) {
	
		event.stopImmediatePropagation();
		
	}
	
	function preventDefault(event) {
	
		if(!$(event.target).is("a")) {
	
			event.stopPropagation();
			event.preventDefault();	
			
		}
		
	}
	
		
})(jQuery);

function jackboxFrameReady() {

	jQuery.fn.jackBox("frameReady");	
	
}

























