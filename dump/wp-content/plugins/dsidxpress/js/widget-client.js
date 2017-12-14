var dsidx_w = {};

dsidx_w.searchWidget = (function () {
	var $ = jQuery;
	
    function isLocationValid() {
        // Deactivating the location validation for case 9360
        // We will now accept no location fields and the - Any - value
        // This file doesn't do anything now.
    	var valid = true;
    	$('.idx-q-Location-Filter :selected').each(function(index) {
    		if($(this).val().length)
    			valid = true;
    	});
    	return valid;
    }   
    
    function isFieldShown() {
    	var returnStr = "";
    	$('.idx-q-Location-Filter').each( function() {
    		returnStr += $("label[for=" + $(this).attr('id') + "]").text() + ", ";
    	});
    	return returnStr.substring(0, returnStr.length - 2);
    }
    
    function MLSExists() {
    	if ($('#idx-q-MlsNumbers').length > 0 && $('#idx-q-MlsNumbers').val().length > 0)
    		return true;
    	else
    		return false;
    }
    
    var returnObj = {
    	validate: function () {
            if (!isLocationValid() && !MLSExists())
            {
            	$("#idx-search-invalid-msg").text("Please select at least one of the following fields: " + isFieldShown()).show();
            	return false;
            }
            else
            	return true;
        }
    };
    
    return returnObj;
})();