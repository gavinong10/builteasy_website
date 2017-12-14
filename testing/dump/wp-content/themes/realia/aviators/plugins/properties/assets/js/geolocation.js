jQuery(document).ready(function () {

    var input = document.getElementById('location-selector');

    if(jQuery('#location-selector').length == 0) {
        return;
    }

    var mapOptions = {
        center: new google.maps.LatLng(jQuery('.latitude').attr('value'), jQuery('.longitude').attr('value')),
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var map = new google.maps.Map(document.getElementById("map"), mapOptions);

    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(jQuery('.latitude').attr('value'), jQuery('.longitude').attr('value')),
        map: map,
        draggable: true
    });

    // prevent submitting for when hitting enter
    jQuery('#location-selector').keypress(function (e) {
        if (event.keyCode == 13) {
            e.preventDefault();
        }
    });

    var searchBox = new google.maps.places.SearchBox(input);
    google.maps.event.addListener(searchBox, 'places_changed', function () {
        var places = searchBox.getPlaces();
        var place = places[0];
        // For each place, get the icon, place name, and location.
        var bounds = new google.maps.LatLngBounds();
        bounds.extend(place.geometry.location);
        marker.position = place.geometry.location;
        map.fitBounds(bounds);
    });

    // Bias the SearchBox results towards places that are within the bounds of the
    // current map's viewport.
    google.maps.event.addListener(map, 'bounds_changed', function () {
        var bounds = map.getBounds();
        searchBox.setBounds(bounds);

        var position = marker.getPosition();
        var lat = position.lat();
        var lon = position.lng();

        jQuery('.latitude').attr('value', lat);
        jQuery('.longitude').attr('value', lon);
    });

    google.maps.event.addListener(marker, 'dragend', function () {
        var position = marker.getPosition();
        var lat = position.lat();

        jQuery('.latitude').attr('value', lat);
        var lon = position.lng();
        jQuery('.longitude').attr('value', lon);
    });
});