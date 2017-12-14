
/*================================================
[  Table of contents  ]
================================================

:: Predefined Variables
:: Preloader
:: Mega menu
:: Search Bar
:: Owl carousel
:: Counter
:: Slider range
:: Countdown
:: Tabs
:: Accordion
:: List group item
:: Slick slider 
:: Mgnific Popup
:: PHP contact form 
:: Placeholder
:: Isotope
:: Scroll to Top
:: POTENZA Window load and functions

======================================
[ End table content ]
======================================*/
 
//POTENZA var
var POTENZA = {};
 
 (function($){
  "use strict";


/*************************
      Predefined Variables
*************************/ 
var $window = jQuery(window),
	$document = jQuery(document),
	$body = jQuery('body'),
  $fullScreen = jQuery('.fullscreen') || $('.section-fullscreen'),
  $halfScreen = jQuery('.halfscreen');

//Check if function exists
$.fn.exists = function () {
	return this.length > 0;
};



  /*************************
       owl-carousel 
*************************/

 POTENZA.carousel = function () {
    jQuery(".owl-carousel").each(function () {
        var $this = $(this),
            $items = ($this.data('items')) ? $this.data('items') : 1,
            $loop = ($this.data('loop')) ? $this.data('loop') : true,
            $navdots = ($this.data('nav-dots')) ? $this.data('nav-dots') : false,
            $navarrow = ($this.data('nav-arrow')) ? $this.data('nav-arrow') : false,
            $autoplay = ($this.attr('data-autoplay')) ? $this.data('autoplay') : true,
            $space = ($this.attr('data-space')) ? $this.data('space') : 30;     
            $(this).owlCarousel({
                loop: $loop,
                items: $items,
                responsive: {
                  0:{items: $this.data('xx-items') ? $this.data('xx-items') : 1},
                  480:{items: $this.data('xs-items') ? $this.data('xs-items') : 1},
                  768:{items: $this.data('sm-items') ? $this.data('sm-items') : 2},
                  980:{items: $this.data('md-items') ? $this.data('md-items') : 3},
                  1200:{items: $items}
                },
                dots: $navdots,
                margin:$space,
                nav: $navarrow,
                navText:["<i class='fa fa-angle-left fa-2x'></i>","<i class='fa fa-angle-right fa-2x'></i>"],
                autoplay: $autoplay,
                autoplayHoverPause: true   
            }); 
           
    }); 
}


POTENZA.rangeslider = function () {
    if (jQuery(".range-slider").exists()) {
        jQuery(".range-slider").slider({
          tooltip: 'always'
        });
    }
  };


/*************************
     Back to top
*************************/

  POTENZA.screenSizeControl = function () {
        if ($fullScreen.exists()) {

            $fullScreen.each(function () {
                var $elem = $(this),
                    elemHeight = $window.height();

                if($window.width() < 768 ) $elem.css('height', elemHeight/ 1.1);
                else $elem.css('height', elemHeight);
            });
        }
        if ($halfScreen.exists()) {
            $halfScreen.each(function () {
                var $elem = $(this),
                    elemHeight = $window.height();

                $elem.css('height', elemHeight / 1.5);
            });
        }
    };

/*************************
       Magnific Popup
*************************/ 
  POTENZA.mediaPopups = function () {

      if (jQuery(".popup-youtube, .popup-vimeo, .popup-gmaps").exists()) {
           jQuery('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
                disableOn: 700,
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                fixedContentPos: false
          });
      }
  }

















/****************************************************
     POTENZA Window load and functions
****************************************************/

  //Window load functions
    $window.load(function () {
        POTENZA.mediaPopups();
    });

   $window.resize(function() {
       POTENZA.screenSizeControl();
    });

 //Document ready functions
    $document.ready(function () {
        POTENZA.carousel(),
        POTENZA.rangeslider(),
        POTENZA.screenSizeControl();
    });


})(jQuery);



jQuery( window ).load(function() {
  jQuery(".tab-content .tab-pane").removeClass("active");
 jQuery(".tab-content .tab-pane.in").addClass("active");
});