/* --------------------------------------------- */
/* Author: http://codecanyon.net/user/CodingJack */
/* --------------------------------------------- */

(function($) {
	
	var vol = 0.75,
	titleStop,
	tweenText,
	isPlaying,
	tweenPos,
	theTitle,
	songText,
	fallback,
	success,
	theSong,
	played,
	isOver,
	mobile,
	html5,
	pause,
	music,
	audio,
	$this,
	play,
	auto,
	swf;
	
	// activates the player
	$.cjMusicPlayer = function() {
		
		// if the browser supports HTML5 Audio
		if(html5) {
			
			$("<source />").attr("src", theSong).attr("type", "audio/mpeg").prependTo(audio);
			$("<source />").attr("src", theSong.split("mp3").join("ogg")).attr("type", "audio/ogg").prependTo(audio);
			
			music = audio[0];
			music.volume = vol;
			music.addEventListener("ended", audioEnded, false);
			getStarted();
			
		}
		// if we need to use the Flash backup
		else {
				
			fallback = $("object");
			
			if(fallback.length) {
				
				fallback = fallback.attr("id");
				swf = setInterval(pingSwf, 250);
				
			}
			
		}
						
	};
	
	function pingSwf() {
		
		success = true;
		
		try {
			
			thisMovie().storeVars(theSong, vol);
			
		}
		catch(event){
			
			success = false;
				
		}
		
		if(success) {
			
			clearInterval(swf);
			getStarted();
			
		}
		
	}
	
	function getStarted() {
	
		play = $(".cj-music-play").click(handlePlayPause);
		pause = $(".cj-music-pause").click(handlePlayPause);
		songText = $(".cj-song-text").html(theTitle).mouseover(tOver).mouseout(tOut);

		if(auto) {
			
			handlePlayPause();
			
		}
		else {
		
			pause.hide();
			play.show();
			
		}
		
	}
	
	// play, pause button click event
	function handlePlayPause(event) {
		
		if(event) event.stopPropagation();
		
		// if the music should pause
		if(isPlaying) {
			
			clearInterval(tweenText);
			
			if(html5) {
				
				music.pause();
				
			}
			else {
				
				try {
					thisMovie().togglePlayPause(false);
				}
				catch(evt){}
				
			}
			
			pause.hide();
			play.show();
			isPlaying = false;
					
		}
		// if the music should play
		else {
			
			if(html5) {
					
				music.play();
				
			}
			else {
				
				try {
					thisMovie().togglePlayPause(true);
				}
				catch(evt){}
				
			}
			
			if(played) {
			
				if(event) tweenText = setInterval(textTween, 50);
			
			}
			else {
			
				tweenTitle();
				
			}
			
			isPlaying = true;
			play.hide();
			pause.show();
					
		}
				
	}
	
	// text mouse over, pauses song text movement
	function tOver(event) {
	
		event.stopPropagation();
		
		isOver = true;
		
	}
	
	// text mouse out, restarts song text movement
	function tOut(event) {
	
		event.stopPropagation();
		
		isOver = false;
		
	}
	
	// prepares the song text for movement
	function tweenTitle() {
		
		if(!theTitle) return;
		
		titleStop = -(getWidth(theTitle));
		played = true;		
		tweenPos = 0;
		
		songText.css("margin-left", 0);
		songText.html(theTitle + theTitle);
		tweenText = setInterval(textTween, 50);
		
	}
	
	// animates the the text
	function textTween() {
		
		if(isOver) return;
		
		(tweenPos > titleStop) ? tweenPos -= 1 : tweenPos = 0;
		
		songText.css("margin-left", tweenPos);
		
	}
	
	// calculates the total width of the song text
	function getWidth(st) {
		
		var span = $("<span />").html(st).css({display: "none", whiteSpace: "nowrap"}).appendTo($this), wid = span.width();
		
		span.remove();
		
		return wid === wid | 0 ? wid : (wid | 0) + 1;
		
	}
	
	// fires when a song ends
	function audioEnded(event) {
		
		event.stopPropagation();
		
		isPlaying = false;
		
		handlePlayPause();
		
	}
	
	// grabs the Flash object for passing information to the swf
	function thisMovie() {
		
		return navigator.userAgent.toLowerCase().search("msie") === -1 ? document[fallback] : window[fallback];
		
	}
	
	// grab url params
	$(document).ready(function() {
	
		var st = document.URL.split("?")[1];
		
		auto = st.substring(st.indexOf("autoplay=") + 9, st.length);
		theTitle = unescape(st.substring(st.indexOf("title=") + 6, st.indexOf("&autoplay")));
		theSong = st.substring(st.indexOf("audio=") + 6, st.indexOf("&title"));
		(theTitle === "false") ? theTitle = "" : theTitle = $.trim(theTitle) + "&nbsp";
		auto = auto === "true";
		
		if(typeof Modernizr !== "undefined") {
		
			html5 = Modernizr.audio;
			mobile = Modernizr.touch;
			
		}
		
		$this = $(".cj-music-player");
		audio = $(".cj-audio");
		
		if(mobile) auto = false;
	
	});

	
})(jQuery);

// global function called from Flash backup if backup is used
function cjFromFlash() {
	
	jQuery.cjMusicPlayer.changeFlash();
	
}

// initialized from parent frame
function cjInit() {

	jQuery.cjMusicPlayer();
	
}
















