(function($) {
	"use strict";

	//Alert

   $(".typo_failed span.close").click(function () {
		$(".typo_failed").fadeOut("slow");
	});

	$(".typo_success span.close").click(function () {
		$(".typo_success").fadeOut("slow");
	});

	$(".typo_info span.close").click(function () {
		$(".typo_info").fadeOut("slow");
	});


	//Toggle

	$(document).ready(function(){ 
		$(".toggle_container").hide(); 
		$("h4.toggle").click(function(){ 
			$(this).toggleClass("active").next().slideToggle("normal"); 
			return false; 
		}); 
	}); 


	//Tabs

	$(document).ready(function() {

		//When page loads...
		$(".tab-content").hide(); //Hide all content
		$("ul.tab-menu li:first").addClass("active").show(); //Activate first tab
		$(".tab-content:first").show(); //Show first tab content

		//On Click Event
		$("ul.tab-menu li").click(function() {
			$("ul.tab-menu li").removeClass("active"); //Remove any "active" class
			$(this).addClass("active"); //Add "active" class to selected tab
			$(".tab-content").hide(); //Hide all tab content

			var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
			$(activeTab).fadeIn(); //Fade in the active ID content
			return false;
		});

	});

	//Skill Bar
	jQuery('.skillbar').each(function(){
		jQuery(this).find('.skillbar-bar').animate({
			width:jQuery(this).attr('data-percent')
		},6000);
	});

})(jQuery);