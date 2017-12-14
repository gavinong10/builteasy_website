dsWidgetListAreas = {
	AddArea: function(title_id, lookup_id, list_id){
		var original =  jQuery.trim(jQuery('#'+list_id).val());
		var title = jQuery.trim(jQuery('#'+title_id).val());
		var lookup = jQuery.trim(jQuery('#'+lookup_id).val());
		var new_pair = '';
		
		if(title == '' && lookup == '') return;
		else if(title == '') new_pair = lookup;
		else if(lookup == '') new_pair = title;
		else new_pair = title + "|" + lookup;
		
		jQuery('#'+list_id).val(original + "\r\n" + new_pair);
		jQuery('#'+title_id).val('');
		jQuery('#'+lookup_id).val('');
	},

	LaunchLookupList : function(url, drop_down_id){
		window.open(url + '?type='+  jQuery('#'+drop_down_id).val(), 'wpdslookuptypes', 'width=400,height=600,menubar=no,toolbar=no,location=no,resizable=yes,scrollbars=yes');
	},
	
	SwitchType : function (drop_down, link_title_id){
		var value = jQuery(drop_down).val();

		jQuery('.'+link_title_id).text(value);
	}
}