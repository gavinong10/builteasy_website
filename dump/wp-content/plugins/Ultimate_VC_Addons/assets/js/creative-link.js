jQuery(document).ready(function(){
  

   jQuery('.ult_colorlink').hover(function() {
   
     var style = jQuery(this).data('style');
  

     if(style=='Style_6'){
      var shadowcolor = jQuery(this).find('.ult_btn6_link_top').data('color');
      jQuery( this ).find('.ult_btn6_link_top').css("text-shadow","10px 0 "+shadowcolor+", -10px 0 "+shadowcolor);
      jQuery( this ).find('.ult_btn6_link_top').css("color",shadowcolor);

     }

     if(style=='Style_10'){
      var bhover = jQuery(this).find('.ult_btn10_span').data('bhover');
      var bstyle = jQuery(this).find('.ult_btn10_span').data('bstyle');
       if(bstyle!=' '){
           jQuery( this ).find('.ult_btn10_span').css("border-top-style","solid");
           jQuery( this ).find('.ult_btn10_span').css("border-top-color",bhover);
        }
     }

    

      var texthover = jQuery(this).data('texthover');
      var bghover= jQuery(this).data('bghover');
   
      jQuery( this ).css("color",texthover);
      jQuery( this ).find('.ult_btn10_span').css("color",texthover);


      if(style=='Style_2'){
        jQuery( this ).find('.ult_btn10_span').css("background",bghover);
      }

  },
    function() {
      

      var style = jQuery(this).data('style');
      

     if(style=='Style_6'){

       jQuery( this ).find('.ult_btn6_link_top').removeAttr('style');

     }
    if(style=='Style_10'){
      var bcolor = jQuery(this).find('.ult_btn10_span').data('color');
      var bstyle = jQuery(this).find('.ult_btn10_span').data('bstyle');
      if(bstyle!=' '){
          jQuery( this ).find('.ult_btn10_span').css("border-top-style",bstyle);
          jQuery( this ).find('.ult_btn10_span').css("border-top-color",bcolor);
      }
     }
     
      var textcolor = jQuery(this).data('textcolor');
      var bgcolor= jQuery(this).data('bgcolor');

      jQuery( this ).css("color",textcolor);
      jQuery( this ).find('.ult_btn10_span').css("color",textcolor);

      if(style=='Style_2'){
        jQuery( this ).find('.ult_btn10_span').css("background",bgcolor);
      }


    }
  );

  });
jQuery(document).ready(function(){
jQuery( ".ult_cl_link_9" ).each(function( index ) {

var ht=jQuery(this).find('.ult_colorlink').outerHeight();
var ht=parseInt(ht/2);
//alert("hi");
jQuery(this).find('.ult_btn9_link_top').css({"-webkit-transform":"translateY(-"+ht+"px)","-ms-transform":"translateY(-"+ht+"px)","-moz-transform":"translateY(-"+ht+"px)","transform":"translateY(-"+ht+"px)"});
jQuery(this).find('.ult_btn9_link_btm').css({"-webkit-transform":"translateY("+ht+"px)","-moz-transform":"translateY("+ht+"px)","-ms-transform":"translateY("+ht+"px)","transform":"translateY("+ht+"px)"});

});
  });