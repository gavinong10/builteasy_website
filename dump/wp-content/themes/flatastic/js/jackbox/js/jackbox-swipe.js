/* --------------------------------------------- */
/* Author: http://codecanyon.net/user/CodingJack */
/* --------------------------------------------- */

;(function($) {
	
	var touchStop, touchMove, touchStart,
	
	methods = {
		
		touchSwipe: function($this, cb) {

			methods.touchSwipeLeft($this, cb);
			methods.touchSwipeRight($this, cb);
			
		},
		
		touchSwipeLeft: function($this, cb, prevent) {
			
			if(prevent) $this.stopProp = true;
			if(!$this.swipeLeft) $this.swipeLeft = cb;
			if(!$this.swipeRight) $this.addEventListener(touchStart, startIt);
			
		},
		
		touchSwipeRight: function($this, cb, prevent) {
			
			if(prevent) $this.stopProp = true;
			if(!$this.swipeRight) $this.swipeRight = cb;
			if(!$this.swipeLeft) $this.addEventListener(touchStart, startIt);
	
		},
		
		unbindSwipe: function($this) {
			
			$this.removeEventListener(touchStart, startIt);
			$this.removeEventListener(touchMove, moveIt);
			$this.removeEventListener(touchStop, endIt);
			
			clearData($this);
			
		}
		
	};
	
	if("ontouchend" in document) {
	
		touchStop = "touchend";
		touchMove = "touchmove";
		touchStart = "touchstart";
		
	}
	else {
	
		touchStop = "mouseup";
		touchMove = "mousemove";
		touchStart = "mousedown";
		
	}
	
	$.fn.cjSwipe = function(type, a, b) {
		
		methods[type](this[0], a, b);	
		
	};
	
	function clearData($this, typed) {
		
		if(!typed) {
			
			delete $this.swipeLeft;
			delete $this.swipeRight;
			delete $this.stopProp;
		
		}
		
		delete $this.newPageX;
		delete $this.pageX;
		
	}
	
	function startIt(event) {
		
		var pages = event.touches ? event.touches[0] : event;
		
		if(this.stopProp) event.stopImmediatePropagation();
		
		this.pageX = pages.pageX;
		this.addEventListener(touchStop, endIt);
		this.addEventListener(touchMove, moveIt);
		
	}
	
	function moveIt(event) {
		
		var pages = event.touches ? event.touches[0] : event,
		newPageX = this.newPageX = pages.pageX;
		
		if(Math.abs(this.pageX - newPageX) > 10) event.preventDefault();
		
	}
	
	function endIt() {
		
		var newPageX = this.newPageX, pageX = this.pageX, evt, typed = this.cjThumbs;
		
		this.removeEventListener(touchMove, moveIt);
		this.removeEventListener(touchStop, endIt);
		
		if(Math.abs(pageX - newPageX) < 30) return;
		
		if(!typed) this.removeEventListener(touchStart, startIt);
		
		if(pageX > newPageX) {
			
			if(this.swipeLeft) {
				
				evt = this.swipeLeft;
				clearData(this, typed);
				evt();
				
			}
			
		}
		else {
			
			if(this.swipeRight) {
				
				evt = this.swipeRight;
				clearData(this, typed);
				evt(1);
				
			}
			
		}
		
	}
	
		
})(jQuery);





