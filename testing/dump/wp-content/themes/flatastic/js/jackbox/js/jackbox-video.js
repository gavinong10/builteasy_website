/* --------------------------------------------- */
/* Author: http://codecanyon.net/user/CodingJack */
/* --------------------------------------------- */

(function($) {
	
	var videoSwf = "../swf/video_fallback.swf",
	initialVolume = 75,
	
	htmlControls = 
			
		'<div class="cj-vid-play-pause">' +
		
			'<span class="cj-vid-play"></span>' +
			'<span class="cj-vid-pause"></span>' +
			
		'</div>' +
		
		'<div class="cj-vid-time">00:00/00:00</div>' + 

		'<div class="cj-vid-lines">' +
		
			'<span class="cj-vid-total"></span>' +
			'<span class="cj-vid-progress"></span>' +
			
		'</div>' +

		'<div class="cj-vid-vol-buttons">' +
		
			'<span class="cj-vid-volume"></span>' +
			'<span class="cj-vid-mute"></span>' +
			
		'</div>' +

		'<div class="cj-vid-vol-lines">' +
		
			'<span class="cj-vid-vol-total"></span>' +
			'<span class="cj-vid-vol-current"></span>' +
			
		'</div>' +

		'<div class="cj-vid-fullscreen">' +
		
			'<span class="cj-vid-full"></span>' +
			'<span class="cj-vid-normal"></span>' +
		
		'</div>',
	
	
	vidOffset = null,
	myObject = null,
	isFull = false,
	chTimer = null,
	sized = false,
	cTimer = null,
	timer = null,
	isFF = false,
	usePlayPause,
	useVolLines,
	videoPlayed,
	waitChrome,
	videoTimer,
	onControls,
	canAnimate,
	container,
	totalTime,
	useVolBtn,
	playPause,
	volOffset,
	storedWid,
	storedVol,
	useStatus,
	htmlVideo,
	plusWidth,
	controls,
	vidLines,
	progress,
	volLines,
	fullNorm,
	volTotal,
	volWidth,
	winWidth,
	useLines,
	duration,
	isLight,
	oHeight,
	myMedia,
	bigPlay,
	useFull,
	volMute,
	volPlay,
	touched,
	oWidth,
	mMinus,
	bodies,
	vTotal,
	volume,
	volBtn,
	normal,
	isIE8,
	video,
	place,
	pause,
	agent,
	auto,
	mins,
	secs,
	play,
	full,
	docs,
	win,
	url,
	doc;
	
	$.jackboxVideo = function() {
		
		if(typeof Modernizr !== "undefined") {
		
			touched = Modernizr.touch;
			htmlVideo = Modernizr.video;
			canAnimate = Modernizr.csstransitions;
			
		}
		else {
		
			htmlVideo = touched = canAnimate = false;
			
		}
		
		doc = document;
		win = $(window);
		docs = $(doc);
		bodies = $("body");
		agent = navigator.userAgent.toLowerCase();
		container = $("<div />").addClass("cj-video-container").appendTo(bodies);
		
		initialVolume *= .01;
		isFF = agent.search("firefox") !== -1;
		
		isIE8 = agent.search("msie") !== -1;
		if(isIE8) isIE8 = parseFloat(agent.substr(agent.indexOf("msie") + 4)) < 9;
		
		var st = doc.URL.split("?")[1], 
		pIndex = st.indexOf("poster="),
		pst = st.substring(pIndex + 7, st.indexOf("&firefox"));
		
		auto = st.search("autoplay=true") !== -1;
		url = st.substring(st.indexOf("video=") + 6, st.indexOf("&autoplay")).split(".mp4")[0];
		
		if(isFF && !touched && st.substring(st.indexOf("firefox=") + 8, st.length) === "true") {
		
			htmlVideo = false;
			
		}
		
		if(htmlVideo && st.substring(st.indexOf("flashing=") + 9, st.indexOf("&width")) === "false" && !isIE8) {
			
			writeVideo(url);
			
			if(!touched) {
				
				controls = $("<div />").addClass("cj-vid-controls").html(htmlControls).appendTo(container);
				
				myMedia.prependTo(container);
				video.volume = 0;

				if(initialVolume === 0) initialVolume = 1;
				
				storedVol = initialVolume;

				video.addEventListener("canplay", onMetaData, false);
				video.play();
				
			}

			else {
				
				if(pst !== "false") myMedia.attr("poster", pst);
				if(auto) myMedia.attr("autoplay", "autoplay");
				myMedia.attr("controls", "controls").prependTo(container);
				
				if(parent.hasOwnProperty("jackboxFrameReady")) {
					
					isLight = true;
					parent.jackboxFrameReady();
					
				}
				else {
					
					$.jackboxVideo.fullyLoaded();
					
				}
				
			}
			
		}

		else if(!touched) {
			
			var hIndex = st.indexOf("height=");
			
			oWidth = st.substring(st.indexOf("width=") + 6, hIndex);
			oHeight = st.substring(hIndex + 7, pIndex);
			
			if("jackboxFrameReady" in parent) {
				
				parent.jackboxFrameReady(true);
				
			}
			else {
				
				$.jackboxVideo.fullyLoaded();
				
			}
			
		}

		else {

			writeVideo(url);
			
			if(pst !== "false") myMedia.attr("poster", pst);
			
			myMedia.attr("controls", "controls").prependTo(container);
			
			if(parent.hasOwnProperty("jackboxFrameReady")) {
				
				isLight = true;
				parent.jackboxFrameReady();
				
			}
			else {

				$.jackboxVideo.fullyLoaded();
					
			}
			
		}
		
	};
	
	function objectResize() {
		
		sized = true;
		myObject.attr({width: win.width(), height: win.height()});
		
	}
	
	function resizer() {
		
		sized = true;
		winWidth = win.width();
		mMinus = winWidth - 237 + plusWidth;

		if(useLines) {
			
			vidLines.css("width", mMinus);
			vTotal.css("width", mMinus);
			videoTime();
			
		}
		
		myMedia.width(winWidth);
		myMedia.height(win.height());
		
	}

	function writeVideo(url) {
		
		myMedia = $("<video />").addClass("cj-video");
		video = myMedia[0];
		
		$("<source />").attr("type", "video/webm").attr("src", url + ".webm").prependTo(myMedia);
		$("<source />").attr("type", "video/mp4").attr("src", url + ".mp4").prependTo(myMedia);
		
	}

	function checkVolume(vol, first) {

		if(vol !== 0) {
			
			if(first) {
			
				volMute.show();
				volPlay.hide();
				
			}
			
			volMute.hide();
			volPlay.show();
			
			volBtn.data("isOn", true);
			
		}
		else {
			
			if(first) {
			
				volPlay.show();
				volMute.hide();
				
			}
			
			volPlay.hide();
			volMute.show();
			volBtn.data("isOn", false);
			
			if(useVolLines) volume.css("width", 0);
			
		}
		
	}

	function killTimers() {
	
		if(timer !== null) {
			
			clearInterval(timer);
			timer = null;
			
		}
		
		if(cTimer !== null) {
			
			clearTimeout(cTimer);
			cTimer = null;
			
		}
		
		if(chTimer !== null) {
			
			clearTimeout(chTimer);
			chTimer = null;
			
		}
		
	}

	function onMetaData(event) {
		
		if(event) event.stopPropagation();
		
		video.removeEventListener("canplay", onMetaData, false);
		video.pause();

		full = $(".cj-vid-full");
		play = $(".cj-vid-play");
		pause = $(".cj-vid-pause");
		volMute = $(".cj-vid-mute");
		vTotal = $(".cj-vid-total");
		normal = $(".cj-vid-normal");
		volPlay = $(".cj-vid-volume");
		vidLines = $(".cj-vid-lines");
		videoTimer = $(".cj-vid-time");
		progress = $(".cj-vid-progress");
		volTotal = $(".cj-vid-vol-total");
		volume = $(".cj-vid-vol-current");
		volBtn = $(".cj-vid-vol-buttons");
		volLines = $(".cj-vid-vol-lines");
		fullNorm = $(".cj-vid-fullscreen");
		playPause = $(".cj-vid-play-pause");
		bigPlay = $("<span />").addClass("cj-vid-play-btn").appendTo(container);
		
		usePlayPause = playPause.length && play.length && pause.length;
		useVolBtn = volBtn.length && volPlay.length && volMute.length;
		useVolLines = volLines.length && volume.length && volTotal.length;
		useFull = fullNorm.length && full.length && normal.length;
		useLines = vidLines.length && progress.length && vTotal.length;
		useStatus = videoTimer.length;

		if(usePlayPause) {
			
			if(auto) {
			
				play.hide();
				pause.show();
			
			}
			else {
			
				pause.hide();
				play.show();
				
			}
			
			playPause.click(togglePlayPause);
			
		}
		else {
			
			if(playPause.length) {
				
				playPause.hide();
				
			}
			else {
			
				if(play.length) play.hide();
				if(pause.length) pause.hide();
				
			}
			
		}

		if(useVolBtn) {
			
			checkVolume(initialVolume, true);
			volBtn.click(toggleVolume);
			
		}
		else {

			if(volBtn.length) {
			
				volBtn.hide();
				
			}
			else {
			
				if(volPlay.length) volPlay.hide();
				if(volMute.length) volMute.hide();
				
			}
			
		}

		if(useVolLines) {
			
			volOffset = parseInt(volLines.css("padding-left"), 10) || 9;
			volWidth = parseInt(volTotal.css("width"), 10) || 55;
			volume.css("width", volWidth * initialVolume);
			
			volLines.click(changeVolume);
			
		}
		else {
		
			useVolLines = false;
			
			if(volLines.length) {
			
				volLines.hide();
				
			}
			else {
				
				if(volume.length) volume.hide();
				if(volTotal.length) volTotal.hide();
				
			}
			
		}
		
		if(useFull && ("webkitSupportsFullscreen" in video || "mozFullScreenEnabled" in doc)) {
			
			fullNorm.click(toggleFull);
			waitChrome = false;
			plusWidth = 0;
			
		}
		else {
			
			plusWidth = 22;
			
			if(fullNorm.length) {
			
				fullNorm.hide();
				
			}
			else {
			
				if(full.length) full.hide();
				if(normal.length) normal.hide();
				
			}
			
		}

		if(useLines) {
			
			vidLines.css("width", mMinus + plusWidth);
			vTotal.css("width", mMinus + plusWidth);
			progress.css("width", 0);
			
			duration = video.duration;
			vidLines.click(moveLine);
			
		}
		else {
			
			if(vidLines.length) {
				
				vidLines.hide();
				
			}
			else {
			
				if(progress.length) progress.hide();
				if(vTotal.length) vTotal.hide();
				
			}
			
		}

		if(useStatus) {
			
			mins = video.duration / 60;
			secs = parseInt((mins - parseInt(mins, 10)) * 60, 10);
			mins = parseInt(mins, 10);
			
			totalTime = "/" + (mins < 10 ? "0" + mins : mins) + ":" + (secs < 10 ? "0" + secs : secs);
			videoTimer.text("00:00" + totalTime);
			
		}
		
		onControls = false;	
		video.addEventListener("ended", videoEnded, false);	
		controls.mouseenter(controlsOver).mouseleave(controlsOut);
		
		myMedia.click(togglePlayPause);
		bigPlay.click(togglePlayPause);
		
		videoPlayed = false;
		video.pause();
		
		video.volume = initialVolume;
		
		if(parent.hasOwnProperty("jackboxFrameReady")) {
			
			isLight = true;
			parent.jackboxFrameReady();
			
		}
		else {

			$.jackboxVideo.fullyLoaded();
			
		}
		
	}
	
	function playIt() {
	
		video.play();
		
	}

	function setControlMouse() {
		
		videoPlayed = true;
		
		if(cTimer !== null) clearTimeout(cTimer);
		
		cTimer = setTimeout(hideControls, 3000);
		
		container.mousemove(showControls).mouseleave(hideControls);
		
	}

	function controlsOver() {
	
		onControls = true;
		
	}

	function controlsOut() {
	
		onControls = false;
		
	}

	function showControls(event) {
		
		if(event) event.stopPropagation();
		if(cTimer !== null) clearTimeout(cTimer);

		if(canAnimate) {
		
			controls.css("opacity", 1);
			
		}
		else {
			
			controls.animate({opacity: 1}, 300);
			
		}
		
		if(isFull) {
			
			if(!onControls && videoPlayed) {
			
				if(!waitChrome || isFF) cTimer = setTimeout(hideControls, 3000);
				
			}
			
		}
		
	}

	function hideControls() {
		
		if(cTimer !== null) clearTimeout(cTimer);

		if(canAnimate) {
			
			controls.css("opacity", 0);
				
		}
		else {
				
			controls.animate({opacity: 0}, 300);
				
		}
		
	}

	function videoEnded(event) {

		event.stopPropagation();
		killTimers(timer, cTimer, chTimer);
		
		video.pause();
		videoPlayed = false;
		video.currentTime = 0;
		videoTimer.text("00:00" + totalTime);
		
		container.unbind("mousemove", showControls).unbind("mouseleave", hideControls);
		if(useLines) progress.css("width", 0);
		
		if(usePlayPause) {
		
			pause.hide();
			play.show();
			
		}

		if(canAnimate) {
				
			controls.css("opacity", 1);	
			bigPlay.css({cursor: "pointer", opacity: 1});
		
		}
		else {
			
			controls.animate({opacity: 1}, 300);
			bigPlay.css("cursor", "pointer").animate({opacity: 1}, 300);
			
		}
		
	}

	function togglePlayPause() {
		
		if(video.paused) {
			
			video.play();
			
			if(usePlayPause) {
				
				play.hide();
				pause.show();
			
			}
			
			if(!videoPlayed) setControlMouse();
			if(useLines) timer = setInterval(videoTime, 250);
			
			if(canAnimate) {
				
				bigPlay.css({cursor: "auto", opacity: 0});
			
			}
			else {
			
				bigPlay.css("cursor", "auto").animate({opacity: 0}, 300);
				
			}
			
		} 
		else {
			
			pauseTheVideo();
			
		}

		showControls();
		
	}
	
	function thisMovie(fallback) {
					
		return agent.search("msie") === -1 ? doc[fallback] : win[0][fallback];
		
	}
	
	function pauseTheVideo() {
		
		video.pause();
	
		if(usePlayPause) {
		
			pause.hide();
			play.show();
			
		}
		
		if(timer !== null) {
			
			clearInterval(timer);
			timer = null;
			
		}

		if(canAnimate) {
			
			bigPlay.css({cursor: "pointer", opacity: 1});
		
		}
		else {
		
			bigPlay.css("cursor", "pointer").animate({opacity: 1}, 300);
			
		}
		
	}
	
	function changeVolume(event) {
		
		event.stopPropagation();
		
		var dif = event.pageX - volLines.offset().left - volOffset, 
		vol = dif / volWidth;
		
		volume.css("width", dif);
		video.volume = vol;
		
		if(useVolBtn) checkVolume(vol, false);
		
	}
	
	function toggleVolume() {

		if(volBtn.data("isOn")) {
			
			if(useVolLines) {
			
				storedWid = parseInt(volume.css("width"), 10);
				volume.css("width", 0);
				
			}
			
			storedVol = video.volume;
			video.volume = 0;
			checkVolume(0, false);
			
		}
		else {
		
			if(useVolLines) volume.css("width", storedWid);
			
			video.volume = storedVol;
			checkVolume(1, false);
			
		}
		
	}

	function moveLine(event) {
	
		event.stopPropagation();
		
		if(timer !== null) {
				
			clearInterval(timer);
			timer = null;
			
		}
		
		if(vidOffset === null) vidOffset = parseInt(vidLines.css("padding-left"), 10);
		
		video.pause();
		video.currentTime = video.duration * ((event.pageX - vidLines.offset().left - vidOffset) / mMinus);
		videoTime();
		
		togglePlayPause();
		
	}

	function videoTime() {	
		
		place = video.currentTime / video.duration;
		progress.css("width", (mMinus * place) | 0);
		
		if(useStatus) {
		
			mins = video.currentTime / 60;
			secs = parseInt((mins - parseInt(mins, 10)) * 60, 10);
			mins = parseInt(mins, 10);
			
			videoTimer.text((mins < 10 ? "0" + mins : mins) + ":" + (secs < 10 ? "0" + secs : secs) + totalTime);
			
		}
			
	}
	
	// Chrome fullscreen API message fires a mousemove event.
	// This causes a problem with our video controls auto-hide functionality 
	// so this function will reset some values for to compensate for this
	function changeChrome() {
		
		waitChrome = false;
		chTimer = null;
		
	}

	function toggleFull() {
		
		if(!isFull) {

			if(doc.mozFullScreenEnabled) {
				
				doc.addEventListener("mozfullscreenchange", mozChange, false);
				container[0].mozRequestFullScreen();
					
			}
			else if(video.webkitSupportsFullscreen) {
				
				if(chTimer !== null) clearTimeout(chTimer);
				
				waitChrome = true;
				chTimer = setTimeout(changeChrome, 4000);
				
				doc.addEventListener("webkitfullscreenchange", webkitChange, false);
				container[0].webkitRequestFullScreen();

			}

		}
		else {
			
			exitFull();
			
		}
		
		isFull = !isFull;
		
	}

	function mozChange() {
	
		if(doc.mozFullScreen) {
			
			isFull = true;
			goFull();
			
		}
		else {
			
			exitFull(true);
			
		}
		
	}

	function webkitChange() {
		
		if(doc.webkitIsFullScreen) {
			
			doc.removeEventListener("webkitfullscreenchange", webkitChange, false);
			
			isFull = true;
			setTimeout(goFull, 50);
				
		}
		else {
			
			exitFull(true);
			
		}
		
	}

	function goFull() {
		
		full.hide();
		normal.show();
		showControls();
		onControls = false;
		win.off("resize.cj").on("resize.cj", exitThis);
		
		if(video.webkitSupportsFullscreen) {
			
			doc.addEventListener("webkitfullscreenchange", webkitChange, false);
			
		}
		
	}
	
	function exitThis() {
		
		win.off("resize.cj");
		exitFull();
		
	}

	function exitFull(fromNative) {
		
		if(doc.mozFullScreenEnabled) {
			
			doc.removeEventListener("mozfullscreenchange", mozChange, false);
			
			if(fromNative) return;
			doc.mozCancelFullScreen();

		}
		else if(video.webkitSupportsFullscreen) {
			
			doc.removeEventListener("webkitfullscreenchange", webkitChange, false);
			
			if(chTimer !== null) {
				
				clearTimeout(chTimer);
				waitChrome = false;
				chTimer = null;
				
			}
			
			if(!fromNative) doc.webkitCancelFullScreen();
			
		}
		
		full.show();
		normal.hide();
		isFull = false;
		
		if(videoPlayed) hideControls();
		
	}
	
	$.jackboxVideo.videoReady = function() {
		
		objectResize();
		win.resize(objectResize);
		
		try {
			thisMovie("myflash").videoResized();
		}
		catch(event){}
		
		container.css("background-image", "none");
		myMedia.css("opacity", 1);
		
	};
	
	$.jackboxVideo.fullyLoaded = function() {
		
		if(myMedia) {
			
			resizer();
			win.resize(resizer);
			
			container.css("background-image", "none");
			myMedia.css("visibility", "visible");
			
			if(useStatus) videoTimer.text("00:00" + totalTime);
			if(isLight && isFF) container.addClass("cj-video-fix");
			if(!auto || touched) return;
			
			setTimeout(playIt, 100);
			if(useStatus) timer = setInterval(videoTime, 250);
		
			setControlMouse();
			bigPlay.css({cursor: "auto", opacity: 0});
			
			if(usePlayPause) pause.show();
			
		}
		else {
			
			myObject = $('<object id="myflash" type="application/x-shockwave-flash" data="' + videoSwf + '" width="640" height="360">' + 
							'<param name="movie" value="' + videoSwf + '" />' + 
							'<param name="allowScriptAccess" value="always" />' + 
							'<param name="bgcolor" value="#000000" />' + 
							'<param name="allowfullscreen" value="true" />' +  
							'<param name="wmode" value="transparent" />' + 
							'<param name="flashvars" value="url=' + url + '.mp4&vol=' + initialVolume + '&auto=' + auto + '&width=' + oWidth + '&height=' + oHeight + '" />' +
						'</object>');
		
			myMedia = $("<div />").css("opacity", 0).prependTo(container).append(myObject);
			
		}
		
	};
	
	$(document).ready(function() { 

		$.jackboxVideo();
		
	});

	
})(jQuery);


function videoReady() {

	$.jackboxVideo.videoReady();
	
}

// initialized from parent frame
function cjInit() {
	
	$.jackboxVideo.fullyLoaded();
	
}






