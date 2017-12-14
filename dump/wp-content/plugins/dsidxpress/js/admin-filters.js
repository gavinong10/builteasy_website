dsIDXpressFilters = {
	FillHiddenWithValues: function (checkboxClass, hiddenID) {
		var g = jQuery('.'+checkboxClass), s = '', i;
		for (i = 0; i < g.length; i++) {
			if (g[i].checked) { s == '' ? s = s + g[i].value : s = s + ',' + g[i].value; }
		}
		document.getElementById(hiddenID).value = s;
	},
	FillHiddenWithSelected: function (divID, hiddenID) {
		var f = document.getElementById(divID), g = f.childNodes, s = '', i;
		for (i = 0; i < g.length; i++) {
			if (g[i].selected) { s == '' ? s = s + g[i].value : s = s + ',' + g[i].value; }
		}
		document.getElementById(hiddenID).value = s;
	}
}

jQuery(document).ready(function () {
    jQuery(".dsidxpress-proptype-filter").click(function () { dsIDXpressFilters.FillHiddenWithValues('dsidxpress-proptype-filter', 'dsidxpress-RestrictResultsToPropertyType'); });
    jQuery(".dsidxpress-proptype-default").click(function () { dsIDXpressFilters.FillHiddenWithValues('dsidxpress-proptype-default', 'dsidxpress-DefaultPropertyType') })
    jQuery(".dsidxpress-statustype-filter").click(function () { dsIDXpressFilters.FillHiddenWithValues('dsidxpress-statustype-filter', 'dsidxpress-DefaultListingStatusTypeIDs') })
    jQuery(".dsidxpress-states-filter").click(function () { dsIDXpressFilters.FillHiddenWithSelected('dsidxpress-states', 'dsidxpress-RestrictResultsToState'); });
});