
function delete_noo_property_custom_field(el){
	jQuery(el).closest('tr').remove();
	return false;
}

function delete_noo_property_feature(el){
	jQuery(el).closest('tr').remove();
	return false;
}

(function($){
	$(document).ready(function(){
		
		$(".noo_property_custom_field_table").sortable({
			'items': 'tbody tr',
			'axis': 'y',
			 placeholder: "noo-property-state-highlight"
		});
		
		$('#add_noo_property_custom_field').click(function(){
			var table = $('.noo_property_custom_field_table'),
				n = 0,
				num = table.data('num');
			
			n = num + 1;
			var tmpl = noopropertyL10n.custom_field_tmpl.replace( /__i__|%i%/g, n );
			table.append(tmpl);
			table.data('num',n);
			
			$(".noo_property_custom_field_table").sortable({
				'items': 'tbody tr',
				'axis': 'y',
				 placeholder: "noo-property-state-highlight"
			});
		});
		
		$(".noo_property_feature_table").sortable({
			'items': 'tbody tr',
			'axis': 'y',
			 placeholder: "noo-property-state-highlight"
		});
		
		$('#add_noo_property_feature').click(function(){
			var table = $('.noo_property_feature_table');
			table.append(noopropertyL10n.feature_tmpl);
			
			$(".noo_property_custom_field_table").sortable({
				'items': 'tbody tr',
				'axis': 'y',
				 placeholder: "noo-property-state-highlight"
			});
		});
		
	});
})(jQuery);
