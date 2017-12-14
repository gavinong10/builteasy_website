(function($) {
	"use strict"; 

   //Preloader
   $(window).load(function(){
      $('#status').fadeOut(); 
      $('#preloader').delay(350).fadeOut('slow'); 
      $('body').delay(350).css({'overflow':'visible'});
   });

   
   //Dropdown Menu
   $('.theme-menu').find('li:has(ul)').addClass('has-menu');
   $('ul.sf-menu').superfish({
      delay:       1000,                           
      animation:   { opacity:'show',height:'show' }, 
      speed:       'fast',                         
      autoArrows:  false                           
   });


   //ScrollTop
   $(function () {
      $(window).scroll(function () {
         if ($(this).scrollTop() > 100) {
            $('#toTop').fadeIn();
         } else {
            $('#toTop').fadeOut();
         }
      });

      $("#toTop").click(function(){
         $("html, body").animate({ scrollTop: 0 }, 600);
            return false;
      });  
   });


   //Fade Text
   setTimeout(function(){
      $(".agent-form .sent").fadeOut("slow", function () {
         $(".agent-form .sent").remove();
      });
   }, 7000);


   //Selectbox
   $('.advance-search-block select').selectBox({ mobile: true });


   //Top Slide
   $('a.slide-toggle').click(function() {
      $('.sliding-bar').slideToggle('fast', function() {
         $('a.slide-toggle').toggleClass('open', $(this).is(':visible'));
      });
      return false;
   });


   //Slide Search
   $('.sb-icon-search').click(function() {
      $('.header-search').slideToggle('fast', function() {
         $('.sb-icon-search').toggleClass('sb-icon-search-close', $(this).is(':visible'));
      });
      return false;
   });


   //Order and Sort
   $('select[name=filter-sort]').change(function () {
      $('.form-sorting-order').submit();
   });

   $('select[name=filter-order]').change(function () {
      $('.form-sorting-order').submit();
   });


   //Masonry
   $(window).load(function(){
      $('.masonry').masonry({
         itemSelector: '.masonry-item',
         columnWidth: 1,
         isResizable: true,
         isAnimated: true,
         isFitWidth: true,
         animationOptions: { //add animations if you want
            duration: 750,
            easing: 'easeInOutExpo'
        }
      });
   });


   //Carousel
   $('.es-carousel-wrapper').elastislide({ imageW: 330, minItems: 1, margin: 44 });

})(jQuery);