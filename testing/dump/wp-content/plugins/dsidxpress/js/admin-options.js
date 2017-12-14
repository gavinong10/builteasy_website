var changes_made = false;
var xmlsitemap_page = false;

dsIDXpressOptions = {
	UrlBase: '',
	OptionPrefix: '',
	EnableDragDrop: false,

	FilterViews: function () {
		var a = jQuery('#dsidxpress-NumOfDetailsViews')
		if (a) {
			a.val(this.StripAlphaChars(a.val()));
			if (new Number(a.val()) > 32766) { a.val('32766'); }
		}
		a = jQuery('#dsidxpress-NumOfResultViews');
		if (a) { 
			a.val(this.StripAlphaChars(a.val()));
			if (new Number(a.val()) > 32766) { a.val('32766'); }
		}
	},

	StripAlphaChars: function (pstrSource) {
		var m_strOut = new String(pstrSource);
		m_strOut = m_strOut.replace(/\D/g, '');
		return m_strOut;
	},

	Init: function () {
		if (dsIDXpressOptions.EnableDragDrop) {
			jQuery("#dsidxpress-SitemapLocations").sortable({
				stop: function (event, ui) { dsIDXpressOptions.RepairOrder(); }
			});
			jQuery("#dsidxpress-SitemapLocations").disableSelection();
		}

		jQuery('.dsidxpress-api-checkbox').click(function () {
			jQuery('#' + this.id.replace('-check', '')).val(this.checked.toString());
		});

		jQuery('#dsidxpress-AgentID').blur(function () {
			jQuery('#dsidxpress-API-AgentID').val(this.value);
		});

		jQuery('#dsidxpress-OfficeID').blur(function () {
			jQuery('#dsidxpress-API-OfficeID').val(this.value);
		});
	},

	AddSitemapLocation: function () {
		changes_made = true;
		var location_name = jQuery('#dsidxpress-NewSitemapLocation').val(),
			location_type = jQuery('#dsidxpress-NewSitemapLocationType').val(),
			location_sanitized = encodeURIComponent(location_name.replaceAll('-', '_').replaceAll(' ', '-').toLowerCase());
		index = jQuery('#dsidxpress-SitemapLocations').children().length;

		var city_selected = '', community_selected = '', tract_selected = '', zip_selected = '';
		switch (location_type) {
			case 'city': city_selected = ' selected="selected"'; break;
			case 'community': community_selected = ' selected="selected"'; break;
			case 'tract': tract_selected = ' selected="selected"'; break;
			case 'zip': zip_selected = ' selected="selected"'; break;
		}

		jQuery('#dsidxpress-NewSitemapLocation').val('');

		var html = '<li class="ui-state-default dsidxpress-SitemapLocation">' +
			'<div class="action"><input type="button" value="Remove" class="button" onclick="dsIDXpressOptions.RemoveSitemapLocation(this)" /></div>' +
			'<div class="priority">' +
				'Priority: <select name="' + dsIDXpressOptions.OptionPrefix + '[SitemapLocations][' + index + '][priority]">' +
					'<option value="0.0">0.0</option>' +
					'<option value="0.1">0.1</option>' +
					'<option value="0.2">0.2</option>' +
					'<option value="0.3">0.3</option>' +
					'<option value="0.4">0.4</option>' +
					'<option value="0.5" selected="selected">0.5</option>' +
					'<option value="0.6">0.6</option>' +
					'<option value="0.7">0.7</option>' +
					'<option value="0.8">0.8</option>' +
					'<option value="0.9">0.9</option>' +
					'<option value="1.0">1.0</option>' +
				'</select>' +
			'</div>' +
			'<div class="type">' +
				'<select name="' + dsIDXpressOptions.OptionPrefix + '[SitemapLocations][' + index + '][type]">' +
					'<option value="city"' + city_selected + '>City</option>' +
					'<option value="community"' + community_selected + '>Community</option>' +
					'<option value="tract"' + tract_selected + '>Tract</option>' +
					'<option value="zip"' + zip_selected + '>Zip Code</option>' +
				'</select>' +
			'</div>' +
			'<div class="value">' +
				'<a href="' + dsIDXpressOptions.UrlBase + location_type + '/' + location_sanitized + '" target="_blank">' + location_name + '</a>' +
				'<input type="hidden" name="' + dsIDXpressOptions.OptionPrefix + '[SitemapLocations][' + index + '][value]" value="' + location_name + '" />' +
			'</div>' +
			'<div style="clear:both"></div>' +
			'</li>';

		jQuery('#dsidxpress-SitemapLocations').append(html);

		dsIDXpressOptions.RepairOrder();
	},

	RepairOrder: function () {
		var location_index = 0;

		location_index = 0;
		jQuery('#dsidxpress-SitemapLocations').children().each(function (i) {
			var location = jQuery(this);
			var value = location.find('input');
			var type = location.find('select');

			value.each(function (o) {
				var input = jQuery(this);
				if (input.attr('name') != undefined) {
					input.attr('name', input.attr('name').replace(/\[\d+\]/, '[' + location_index + ']'));
				}
			});

			type.each(function (o) {
				var input = jQuery(this);
				if (input.attr('name') != undefined) {
					input.attr('name', input.attr('name').replace(/\[\d+\]/, '[' + location_index + ']'));
				}
			});

			location_index++;
		});
	},


	RemoveSitemapLocation: function (button) {
		if (confirm("Are you sure you want to remove this item")) {
			changes_made = true;
			jQuery(button.parentNode.parentNode).remove();
			dsIDXpressOptions.RepairOrder();
		}
	},

	OptionCheckBoxClick: function (checkboxID) {
		checkboxID = checkboxID.id;
		checked = jQuery('#' + checkboxID).prop("checked");
		jQuery('#' + checkboxID.substring(0, checkboxID.length - 2)).val(checked);
	}
}

jQuery(document).ready(function () {
	dsIDXpressOptions.Init();

	window.onbeforeunload = function() {
		if (changes_made && xmlsitemap_page)
			return 'The changes you made will be lost if you navigate away from this page.';
	};

	/* Changes made additions */
	jQuery("#xml-options-saved").click(function() { changes_made = false; });
	jQuery("select:not(.ignore-changes)").change(function () { changes_made = true; });
});

String.prototype.replaceAll = function(target, replacement) {
	return this.split(target).join(replacement);
};
