jQuery('#noo_property_google_map_search_input').bind('keypress keydown keyup', function(e){
    if(e.keyCode == 13) { e.preventDefault(); }
});

google.maps.event.addDomListener(window, 'load', initialize);
var geocoder;
var map;
var infowindow;
var marker;
var autocomplete;
var directionsDisplay;
var directionsService;
function initialize() {
    geocoder = new google.maps.Geocoder();
    directionsDisplay = new google.maps.DirectionsRenderer({draggable: true});
    directionsService = new google.maps.DirectionsService();

    var mapOptions = {
        center: new google.maps.LatLng(nooGoogleMap.latitude,nooGoogleMap.longitude),
        zoom: 13,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('noo_property_google_map'), mapOptions);

    var input = document.getElementById('noo_property_google_map_search_input');
    autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.bindTo('bounds', map);

    infowindow = new google.maps.InfoWindow({
        position: map.getCenter()
    });

    directionsDisplay.setMap(map);
    directionsDisplay.setPanel(document.getElementById("route"));

    infowindow = new google.maps.InfoWindow({
        position: map.getCenter()
    });


    marker = new google.maps.Marker({
        map: map,
        position: new google.maps.LatLng(nooGoogleMap.latitude,nooGoogleMap.longitude),
        draggable: true
    });

    // infowindow.setContent("Select position on map.");
    // infowindow.open(map, marker);
    google.maps.event.addListener(map, 'click', function(e) {
        marker.setPosition(e.latLng);
        getMap(marker);
    });

   // document.id('detail_pane').getElement('dt.location_panel').addEvent('click', function(e){
   //     setTimeout( function() {
   //         google.maps.event.trigger(map, 'resize');
   //         map.setCenter(marker.getPosition());
   //     }, 10);
   // });

    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        infowindow.close();
        var place = autocomplete.getPlace();

        if (place) {
            if (place.geometry) {
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }

                marker.setPosition(place.geometry.location);
                var address = '';
                if (place.address_components) {
                    address = [(place.address_components[0] &&
                                place.address_components[0].short_name || ''),
                        (place.address_components[1] &&
                                place.address_components[1].short_name || ''),
                        (place.address_components[2] &&
                                place.address_components[2].short_name || '')
                    ].join(', ');
                }

                infowindow.setContent('<strong>' + place.name + '</strong><br>' + address);
                infowindow.open(map, marker);

                document.getElementById("_noo_property_gmap_latitude").value = place.geometry.location.lat();
                document.getElementById("_noo_property_gmap_longitude").value = place.geometry.location.lng()

                //  document.getElementById("jform_toAddress").value = content;
            }
        }
    });

    google.maps.event.addListener(map, "idle", function() {
        google.maps.event.trigger(map, 'resize');
    });

    // setupClickListener('changetype-all', []);
    // setupClickListener('changetype-establishment', ['establishment']);
    // setupClickListener('changetype-geocode', ['geocode']);
    drag(marker);
}

// Sets a listener on a radio button to change the filter type on Places
// Autocomplete.
function setupClickListener(id, types) {
    var radioButton = document.getElementById(id);
    google.maps.event.addDomListener(radioButton, 'click', function() {
        autocomplete.setTypes(types);
    });
}

function clearOverlays() {
    marker.setMap(null);
    marker = null;
}

function drag(marker) {
    google.maps.event.addListener(marker, 'drag', function() {
        getMap(marker);
    });
}

function getMap(marker) {

    geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                var content = results[0].formatted_address;

                infowindow.setContent(content);
                infowindow.open(map, marker);

                document.getElementById("_noo_property_gmap_latitude").value = marker.getPosition().lat();
                document.getElementById("_noo_property_gmap_longitude").value = marker.getPosition().lng();
            }
        }
    });
}
