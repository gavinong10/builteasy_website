dsWidgetListings = {
	LaunchLookupList : function(url, drop_down_id){
		window.open(url + '?type='+  jQuery('#'+drop_down_id).val(), 'wpdslookuptypes', 'width=400,height=600,menubar=no,toolbar=no,location=no,resizable=yes,scrollbars=yes');
	},
	
	SwitchType : function (drop_down, link_title_id){
		var value = jQuery(drop_down).val();

		jQuery('#'+link_title_id).text(value.substr(0, 1).toUpperCase() + value.substr(1));
	}
}