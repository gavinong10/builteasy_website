
dsWidgetSearch = {
	InitFields : function(){
		var $ = jQuery;

		$('.search-widget-searchOptions input:checkbox').each( function(index, value) {
			var block_id = '#' + value.id.replace('-show_','-') + '_block';
			
			if(value.checked) $(block_id).show();
			else $(block_id).hide();
		});
	},

	ShowBlock: function(field) {
		var $ = jQuery;
		var block_id = '#' + field.id.replace('-show_','-') + '_block';

		if(field.checked) $(block_id).show();
		else $(block_id).hide();
	},
	
	LaunchLookupList : function(url){
		window.open(url, 'wpdslookuptypes', 'width=400,height=600,menubar=no,toolbar=no,location=no,resizable=yes,scrollbars=yes');
	}
}