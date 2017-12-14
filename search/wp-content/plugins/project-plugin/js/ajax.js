$(document).ready(function()
{  
	$.ajax({
	   type: "POST",
	   url: "",
	   data: 'foo=bar&ca$libri=no$libri',
	   success: function(msg){
		 alert('wow'+msg);
	   }
	});
});