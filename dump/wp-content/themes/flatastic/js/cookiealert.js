(function ($) {

	function cwsetCookie(c_name,value,exdays) {
		var exdate = new Date();
		exdate.setDate(exdate.getDate() + exdays);
		var c_value=escape(value) + ((exdays == null) ? "" : "; expires="+exdate.toUTCString());
		document.cookie = c_name + "=" + c_value;
	}

	function cwgetCookie(c_name) {
		var i, x, y, ARRcookies = document.cookie.split(";");

		for (i = 0; i < ARRcookies.length; i++) {
			x = ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
			y = ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
			x = x.replace(/^\s+|\s+$/g,"");

			if (x == c_name) {
				return unescape(y);
			}
		}
	}


    $.fn.cwAllowCookies = function (options) {
		var dateObject = new Date();
		var timeOffset = - dateObject.getTimezoneOffset() / 60;
		var region = "TheWorld";

		switch (timeOffset) {
			case -1:
			case -0:
			case 0:
			case 1:
			case 2:
			case 3:
			case 3.5:
			case 4:
			case 4.5:
			region = "Europe";
			break;
			case 9:
			region = "Japan";
		}

		$(".cwallowcookies").live('click', function () {
			cwsetCookie("cwallowcookies",true,365);
			$(".cwcookielaw").slideUp('slow', function () { $(this).remove(); });
		});

		$(".cwcookiesmoreinfo").live('click', function () {
			$(".cwcookiebubble").fadeIn('slow');
		});

		$(".cwcookiebubble").live('click', function () {
			$(".cwcookiebubble").fadeOut('slow');
		});

		var cwallowcookies=cwgetCookie("cwallowcookies");

        var defaults = {
			cwmessage: 		"Please note this website requires cookies in order to function correctly, they do not store any specific information about you personally.",
			cwbubblemessage:	"On 26th May 2012, new laws came into force in the EU that affect most web sites. If cookies are used in a site, the Privacy and Electronic Communications (EC Directive) (Amendment) Regulations 2011 (UK Government Regulations) asks that the website user must give consent to the use and placing of the cookies.",
			cwbubbletitle:		"Cookie Laws within the EU",
			cwhref:			"",
			cwreadmore:		"",
			cwagree:		"Accept Cookies",
			cwmoreinfo:		"Read more...",
			cwmoreinfohref: "http://www.cookielaw.org/the-cookie-law",
			animate:				true,
			europeonly:				false
        };

        var options = $.extend(defaults, options);

		if (options.europeonly == true) { if (region !== "Europe") { return(false); } }

		if (options.cwhref !== "") {
			options.cwbubblemessage = options.cwbubblemessage + " <a href=\""+options.cwhref+"\">"+options.cwreadmore+"</a>";
		}

		var html = "<div class='cwcookielaw'><div class='container'><div class='row'><div class='col-sm-12'><p>" + options.cwmessage + "</p><a class='cwallowcookies button' href='#'>" + options.cwagree + "</a><a class='cwcookiesmoreinfo button' target='_blank' href='" + options.cwmoreinfohref + "'>" + options.cwmoreinfo + "</a><div class='cwcookiebubble'><div class='cwcookietitle'>" + options.cwbubbletitle + "</div><p>" + options.cwbubblemessage + "</p></div></div></div></div></div>";

		if (cwallowcookies) {
			cwsetCookie("cwallowcookies", true, 365);
		} else {

			$(this).prepend(html);

			if (options.animate) { 
				$(".cwcookielaw").slideDown('slow');
			} else {
				$(".cwcookielaw").show();
			}
		}

    };

})(jQuery);