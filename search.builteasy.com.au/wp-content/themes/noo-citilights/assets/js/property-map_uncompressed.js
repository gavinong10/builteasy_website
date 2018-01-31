//global infoBox
var infoBox;
//global map
var map;
// global list properties
var gmarkers = [];
// global cureent properties index
var gmarker_index = 1;
// global map search box
var mapSearchBox;
// global MarkerClusterer
var mcluster;
function noo_number_format(number, decimals, dec_point, thousands_sep) {
  number = (number + '')
    .replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + (Math.round(n * k) / k)
        .toFixed(prec);
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
    .split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '')
    .length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1)
      .join('0');
  }
  return s.join(dec);
}

;(function($){
	"use strict";
	$.fn.nooLoadmore = function(options,callback){
		var defaults = {
					agentID:0,
					contentSelector: null,
					contentWrapper:null,
					nextSelector: "div.navigation a:first",
					navSelector: "div.navigation",
					itemSelector: "div.post",
					dataType: 'html',
					finishedMsg: "<em>Congratulations, you've reached the end of the internet.</em>",
					loading:{
						speed:'fast',
						start: undefined
					},
					state: {
				        isDuringAjax: false,
				        isInvalidPage: false,
				        isDestroyed: false,
				        isDone: false, // For when it goes all the way through the archive.
					    isPaused: false,
					    isBeyondMaxPage: false,
					    currPage: 1
					}
		};
		var options = $.extend(defaults, options);
		
		return this.each(function(){
			var self = this;
			var $this = $(this),
				wrapper = $this.find('.loadmore-wrap'),
				action = $this.find('.loadmore-action'),
				btn = action.find(".btn-loadmore"),
				loading = action.find('.loadmore-loading');
			
			options.contentWrapper = options.contentWrapper || wrapper;
			
			
				
			var _determinepath = function(path){
				if (path.match(/^(.*?)\b2\b(.*?$)/)) {
	                path = path.match(/^(.*?)\b2\b(.*?$)/).slice(1);
	            } else if (path.match(/^(.*?)2(.*?$)/)) {
	                if (path.match(/^(.*?page=)2(\/.*|$)/)) {
	                    path = path.match(/^(.*?page=)2(\/.*|$)/).slice(1);
	                    return path;
	                }
	                path = path.match(/^(.*?)2(.*?$)/).slice(1);

	            } else {
	                if (path.match(/^(.*?page=)1(\/.*|$)/)) {
	                    path = path.match(/^(.*?page=)1(\/.*|$)/).slice(1);
	                    return path;
	                } else {
	                	options.state.isInvalidPage = true;
	                }
	            }
				return path;
			}
			var path = $(options.nextSelector).attr('href');
			path = _determinepath(path);
			
			// callback loading
			options.callback = function(data, url) {
	            if (callback) {
	                callback.call($(options.contentSelector)[0], data, options, url);
	            }
	        };
	        
	        options.loading.start = options.loading.start || function() {
				 	btn.hide();
	                $(options.navSelector).hide();
	                loading.show(options.loading.speed, $.proxy(function() {
	                	loadAjax(options);
	                }, self));
	         };
			
			var loadAjax = function(options){
				var callback=options.callback,
					desturl,frag,box,children,data;
				
				options.state.currPage++;
				// Manually control maximum page
	            if ( options.maxPage !== undefined && options.state.currPage > options.maxPage ){
	            	options.state.isBeyondMaxPage = true;
	                return;
	            }
	            desturl = path.join(options.state.currPage);
	            $.post(nooGmapL10n.ajax_url,{
	            	action:'noo_agent_ajax_property',
	            	page:options.state.currPage,
	            	agent_id:options.agentID
	            },function(res){
	            	
	            	 if(res.content != '' && res.content !=null && res.content != undefined){
	            		$(options.contentWrapper).append(res.content);
	                    loading.hide();
	                    btn.show(options.loading.speed);
	            	 }else{
	            		btn.hide();
	            		action.append('<div style="margin-top:5px;">' + options.finishedMsg + '</div>').animate({ opacity: 1 }, 2000, function () {
	            			action.fadeOut(options.loading.speed);
	                    });
                        return ;
	            	 }
	            },'json');
			}
			
			
			btn.on('click',function(e){
				 e.stopPropagation();
				 e.preventDefault();
				 options.loading.start.call($(options.contentWrapper)[0],options);
			});
		});
	};

	function noo_price(price){
		var $currency_position = nooGmapL10n.currency_position,
			$format;
		switch ( $currency_position ) {
			case 'left' :
				$format = '%1$s%2$s';
				break;
			case 'right' :
				$format = '%2$s%1$s';
				break;
			case 'left_space' :
				$format = '%1$s&nbsp;%2$s';
				break;
			case 'right_space' :
				$format = '%2$s&nbsp;%1$s';
				break;
		}
		price = noo_number_format(price,nooGmapL10n.num_decimals,nooGmapL10n.decimal_sep,nooGmapL10n.thousands_sep)
		return $format.replace('%1$s',nooGmapL10n.currency).replace('%2$s',price);
	}

	
	function search_initialize(){
			mapSearchBox = $('.noo-map');
		var mapBox = mapSearchBox.find('#gmap'),
			latitude = nooGmapL10n.latitude,
			longitude = nooGmapL10n.longitude;
		if(mapSearchBox.length && mapBox.length){
			var myPlace    = new google.maps.LatLng(latitude,longitude); 
			var myOptions = {
				    flat:false,
				    noClear:false,
				    zoom: parseInt(nooGmapL10n.zoom),
				    scrollwheel: false,
				    streetViewControl:false,
				    disableDefaultUI: true,
				    draggable: (Modernizr.touch ? false  : nooGmapL10n.draggable),
				    center: myPlace,
				    mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(mapBox.get(0),myOptions );
			google.maps.visualRefresh = true;

			google.maps.event.addListener(map, 'tilesloaded', function() {
				mapSearchBox.find('.gmap-loading').hide();
			});
			
			var input = document.getElementById('gmap_search_input');
			map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
			var searchBox = new google.maps.places.SearchBox(input);
			google.maps.event.addListener(searchBox, 'places_changed', function() {
			    var places = searchBox.getPlaces();

			    if (places.length == 0) {
			      return;
			    }
			    var bounds = new google.maps.LatLngBounds();
			    for (var i = 0, place; place = places[i]; i++) {
			      // Create a marker for each place.
			      var _marker = new google.maps.Marker({
			        map: map,
			        zoom:parseInt(nooGmapL10n.zoom),
			        title: place.name,
			        position: place.geometry.location
			      });
			      bounds.extend(place.geometry.location);
			    }
			    map.fitBounds(bounds);
			    map.setZoom(parseInt(nooGmapL10n.zoom));
		    });
			
			var infoboxOptions = {
                    content: document.createElement("div"),
                    disableAutoPan: true,
                    maxWidth: 500,
                    boxClass:"myinfobox",
                    zIndex: null,			
                    closeBoxMargin: "-13px 0px 0px 0px",
                    closeBoxURL: "",
                    infoBoxClearance: new google.maps.Size(1, 1),
                    isHidden: false,
                    pane: "floatPane",
                    enableEventPropagation: false                   
            };               
			infoBox = new InfoBox(infoboxOptions);
			
			var clickMarkerListener = function(maker){
				 var infoContent = '<div class="gmap-infobox"><a class="info-close" onclick="return infoBox.close();" href="javascript:void(0)">x</a>\
					 <div class="info-img">' + maker.image + '</div> \
						 <div class="info-summary"> \
						 	<h5 class="info-title">' + maker.title + '</h5>';
				if( maker.area != '' || marker.bedrooms != '' || marker.bathrooms != '' ) {
					infoContent += '<div class="info-detail">';
					if( maker.area != '' ) {
						infoContent += '<div class="size"><span>' + maker.area + '</span></div>';
					}
					if( maker.bedrooms != '' ) {
						infoContent += '<div class="bedrooms"><span>' + maker.bedrooms + '</span></div>';
					}
					if( maker.bathrooms != '' ) {
						infoContent += '<div class="bathrooms"><span>' + maker.bathrooms + '</span></div>';
					}
					infoContent += '</div>';
				}

				infoContent += '<div class="info-more"> \
					 			<div class="info-price">' + maker.price_html + '</div> \
					 			<div class="info-action"><a href="' + maker.url + '"><i class="fa fa-plus"></i></a></div> \
					 		</div> \
					 	</div> \
				 	</div>';
				 infoBox.setContent(infoContent);
			     infoBox.open(map,maker);    
			     map.setCenter(maker.position); 
			     map.panBy(50,-120);
			}
			 
			//create markers
			if($(mapBox).data('source') =='IDX' && (typeof(dsidx) == 'object') && !$.isEmptyObject( dsidx.dataSets ) ){
				var idxCode = null;
				var bounds = new google.maps.LatLngBounds();
				var searchParams = getSearchParams();
				$.each(dsidx.dataSets,function(i,e){
					idxCode = i;
				});
				for (var i = 0; i < dsidx.dataSets[idxCode].length; i++) {
					var marker = dsidx.dataSets[idxCode][i];
					if (marker.ShortDescription !== undefined){
					   var title_arr = marker.ShortDescription.split(",");
					    var title = title_arr[0] +', '+ title_arr[1];
					}
					else{
					    var title = marker.Address + ", " + marker.City;
					}
					var num_bed = parseInt( (marker.BedsShortString).charAt(0) );
					var num_bath = parseInt( (marker.BathsShortString).charAt(0) );
					var idx_price = parseFloat( (marker.Price).replace( /[^\d.]/g, '' ) );

					//$('#idx-q-ImprovedSqFtMin').val(num_bath);
					//alert(num_bath);
					var markerPlace = new google.maps.LatLng(marker.Latitude,marker.Longitude);
					var gmarker = new google.maps.Marker({
					    position: markerPlace,
					    map: map,
					    area: marker.ImprovedSqFt+' '+nooGmapL10n.area_unit,
					    image:	marker.PhotoUriBase,
					    title: title,
					    bedrooms: num_bed,
					    bathrooms: num_bath,
					    price: idx_price,
					    price_html:marker.Price,
					    url: nooGmapL10n.home_url + '/idx/' + marker.PrettyUriForUrl
				    });
					gmarker.setIcon(nooGmapL10n.theme_uri + '/assets/images/marker-icon.png');
					gmarkers.push(gmarker);
					if( setMarkerVisible( gmarker, searchParams ) ) {
						bounds.extend( gmarker.getPosition() );
						if( nooGmapL10n.fitbounds ) map.fitBounds(bounds);
					}
					google.maps.event.addListener(gmarker, 'click', function(e) {
						clickMarkerListener(this);
					});
				}
				 
			}else{
				var markers = $.parseJSON(nooGmapL10n.markers);
				if(markers.length){
					var bounds = new google.maps.LatLngBounds();
					var searchParams = getSearchParams();

					for(var i = 0; i < markers.length ; i ++){
						var marker = markers[i];
						
						var markerPlace = new google.maps.LatLng(marker.latitude,marker.longitude);
						var gmarker = new google.maps.Marker({
						    position: markerPlace,
						    map: map,
						    image:	marker.image,
						    title: marker.title,
						    area:	marker.area,
						    bedrooms: marker.bedrooms,
						    bathrooms: marker.bathrooms,
						    price: marker.price,
						    price_html:marker.price_html,
						    url: marker.url,
						    category:marker.category,
						    status:marker.status,
						    sub_location:marker.sub_location,
						    location:marker.location
					    });
						if(marker.icon != ''){
							gmarker.setIcon(marker.icon);
						}
						gmarkers.push(gmarker);
						if( setMarkerVisible( gmarker, searchParams ) ) {
							bounds.extend( gmarker.getPosition() );
							if( nooGmapL10n.fitbounds ) map.fitBounds(bounds);
						}
						google.maps.event.addListener(gmarker, 'click', function(e) {
							clickMarkerListener(this);
						});
					 }
				 }
			 }
			 
			 //MarkerClustererPlus
			var clusterStyles = [{
					textColor: '#ffffff',    
					opt_textColor: '#ffffff',
					url: nooGmapL10n.theme_uri+'/assets/images/cloud.png',
					height: 72,
					width: 72,
					textSize:15
				}
			];
			mcluster = new MarkerClusterer(map, gmarkers,{
				gridSize: 50,
				ignoreHidden:true, 
				styles: clusterStyles,
				maxZoom: 20
			});
			mcluster.setIgnoreHidden(true);
			 
				 
			//zoom in action
			if(mapSearchBox.find('.zoom-in').length){
				google.maps.event.addDomListener(mapSearchBox.find('.zoom-in').get(0), 'click', function (e) {      
					   e.stopPropagation();
					   e.preventDefault();
				       var current= parseInt( map.getZoom(),10);
				       current++;
				       if(current>20){
				           current=20;
				       }
				       map.setZoom(current);
				});  
				
			}
			
			// zoom out action
			if(mapSearchBox.find('.zoom-out').length){
				google.maps.event.addDomListener(mapSearchBox.find('.zoom-out').get(0), 'click', function (e) {      
					   e.stopPropagation();
					   e.preventDefault();
					   var current= parseInt( map.getZoom(),10);
				       current--;
				       if(current<0){
				           current=0;
				       }
				       map.setZoom(current);
				});  
				
			}
		}
	}
	
	google.maps.event.addDomListener(window, 'load', search_initialize);
	
	function mylocationCallback(pos){
		  var shape = {
		       coord: [1, 1, 1, 38, 38, 59, 59 , 1],
		       type: 'poly'
		   }; 
		   if( map.getZoom() != 15 ){
		        map.setZoom(15);
		   }
		   var myLocation =  new google.maps.LatLng( pos.coords.latitude, pos.coords.longitude);
		   map.setCenter(myLocation);   
		   var marker = new google.maps.Marker({
	             position: myLocation,
	             map: map,
	             icon: nooGmapL10n.theme_uri+'/assets/images/my-marker.png',
	             shape: shape,
	             zIndex: 9999,
	             infoWindowIndex : 9999,
	             radius: 1000
	            });
	    
	     var circleOptions = {
		      strokeColor: '#75b08a',
		      strokeOpacity: 0.6,
		      strokeWeight: 1,
		      fillColor: '#75b08a',
		      fillOpacity: 0.2,
		      map: map,
		      center: myLocation,
		      radius: 1000
	    };
	    var cityCircle = new google.maps.Circle(circleOptions);
	    
	}
	function mylocationError(){
		 alert(nooGmapL10n.no_geolocation_pos);
	}
	
	$(document).on('click','.gmap-mylocation',function(e){
		e.stopPropagation();
		e.preventDefault();
		if(navigator.geolocation){
	        navigator.geolocation.getCurrentPosition(mylocationCallback,mylocationError,{timeout:10000});
	    }
		else{
	        alert(nooGmapL10n.no_geolocation_msg);
	    }
	});
	
	$(document).on('click','.gmap-full',function(e){
		e.stopPropagation();
		e.preventDefault();
		if($(this).closest('.noo-map').hasClass('fullscreen')){
			$(this).closest('.noo-map').removeClass('fullscreen');
			$(this).empty().html('<i class="fa fa-expand"></i> '+nooGmapL10n.fullscreen_label);
		}else{
			$(this).closest('.noo-map').addClass('fullscreen');
			$(this).empty().html('<i class="fa fa-compress"></i> '+nooGmapL10n.default_label);
		}
		google.maps.event.trigger(map, "resize");
	});
	
	$(document).on('click','.gmap-prev',function(e){
		e.stopPropagation();
	    e.preventDefault();
	    gmarker_index -- ;  
	    if (gmarker_index < 1){
	    	gmarker_index = gmarkers.length;
	    }
	    while(gmarkers[gmarker_index - 1].visible === false){
	    	gmarker_index--; 
	        if (gmarker_index > gmarkers.length){
	        	gmarker_index = 1;
	        }
	    }
	    
	    if( map.getZoom() <15 ){
	        map.setZoom(15);
	    }
	    google.maps.event.trigger(gmarkers[gmarker_index - 1], 'click');
	});
	
	$(document).on('click','.gmap-next',function(e){
		e.stopPropagation();
	    e.preventDefault();
	    gmarker_index ++;  
	    if (gmarker_index > gmarkers.length){
	    	gmarker_index = 1;
	    }
	    while(gmarkers[gmarker_index - 1].visible === false){
	    	gmarker_index++; 
	        if (gmarker_index > gmarkers.length){
	        	gmarker_index = 1;
	        }
	    }
	    
	    if( map.getZoom() < 15 ){
	        map.setZoom(15);
	    }
	    google.maps.event.trigger(gmarkers[gmarker_index - 1], 'click');
	});
	
	function single_initialize(){
		var mapBox = $('.property-map-box'),
			searchMarker,
			zoom = mapBox.data('zoom'),
			latitude = mapBox.data('latitude'),
			longitude = mapBox.data('longitude'),
			marker = mapBox.data('marker');
		if(mapBox.length){
			
			var myPlace    = new google.maps.LatLng(latitude,longitude); 
			var map = new google.maps.Map(mapBox.get(0), {
				    flat:false,
				    noClear:false,
				    zoom: zoom,
				    scrollwheel: false,
				    draggable: (Modernizr.touch ? false  : nooGmapL10n.draggable),
				    center: myPlace,
				    streetViewControl:false,
				    mapTypeId: google.maps.MapTypeId.ROADMAP
			});
			if(marker == ''){
				marker = nooGmapL10n.theme_uri + '/assets/images/marker-icon.png'
			}
			var marker = new google.maps.Marker({
				icon: marker,
			    position: myPlace,
			    map: map
		    });
		   var input = /** @type {HTMLInputElement} */(
				      document.getElementById('property_map_search_input'));
				  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

			var searchBox = new google.maps.places.SearchBox(
				    /** @type {HTMLInputElement} */(input));
			
			google.maps.event.addListener(searchBox, 'places_changed', function() {
				if(searchMarker != null)
					searchMarker.setMap(null);
				
				var geocoder = new google.maps.Geocoder();
				var getAddress = function(Latlng) {
				    geocoder.geocode({'latLng': Latlng}, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
							if (results[0]) {
								return results[0].formatted_address;
							}
						}
				    });
				}
				var myAddress = getAddress(myPlace);
				geocoder.geocode( {
					'address': input.value
				}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						map.setCenter(results[0].geometry.location);
						searchMarker = new google.maps.Marker({
						      position: results[0].geometry.location,
						      map:map,
						      draggable: false,
						      animation: google.maps.Animation.DROP
						});
						var start = input.value;
					    var end = myPlace;
					    var directionsService = new google.maps.DirectionsService();
					    var directionsDisplay = new google.maps.DirectionsRenderer();
					    directionsDisplay.setMap(map);
					    var request = {
				    	      origin:start,
				    	      destination:end,
				    	      travelMode: google.maps.TravelMode.DRIVING
					    };
					    	
					    directionsService.route(request, function(response, status) {
					        if (status == google.maps.DirectionsStatus.OK) {
					          directionsDisplay.setDirections(response);
					        }
					    });
					} else {
						alert("Geocode was not successful for the following reason: " + status);
					}
				});
				
			   
			});
		}
	}
	google.maps.event.addDomListener(window, 'load', single_initialize);
	
	function search_filter(){
		
		$('.noo_advanced_search_property').each(function(){
			var $this = $(this);
			if($this.find('#gmap').length){
				var searchParams = getSearchParams();
				
				if(  typeof infoBox!=='undefined' && infoBox !== null ){
					infoBox.close(); 
				}
				var bounds = new google.maps.LatLngBounds();
				if(typeof mcluster !== 'undefined')
					mcluster.setIgnoreHidden(true);
				if(gmarkers.length){
					for (var i=0; i < gmarkers.length; i++) {  
						var gmarker = gmarkers[i];
						
						if( setMarkerVisible( gmarker, searchParams ) ) {
							bounds.extend( gmarker.getPosition() ); 
						}
					}
					if(typeof mcluster !== 'undefined')
						mcluster.repaint();        
				}
				map.setZoom(10);
				if( !bounds.isEmpty() ){
					map.fitBounds(bounds);
				}
			};
		});
	}

	function getSearchParams() {
		var $IDXForm = $('.noo_advanced_search_property .idx');
		var $PROPERTYForm = $('.noo_advanced_search_property .property');
		if ( $IDXForm.length == '1' ) {
			var searchParams = {
				form_map: '',
				city: '',
				bedrooms: NaN,
				bathrooms: NaN,
				min_price: NaN,
				max_price: NaN,
				sqft: NaN
			}
		} 
		if ( $PROPERTYForm.length == '1' ) {
			var searchParams = {
				form_map: '',
				location: '',
				sub_location: '',
				status: '',
				category: '',
				bedrooms: NaN,
				bathrooms: NaN,
				min_price: NaN,
				max_price: NaN,
				min_area: NaN,
				max_area: NaN
			}
		}

		if( !$('#gmap').length ) return searchParams;
		var $searchForm = $('.noo_advanced_search_property .gsearch');
		if ( $IDXForm.length == '1' ) {
			searchParams.form_map = 'idx';
			//searchParams.location = $IDXForm.find('input.dsidx-search-widget-propertyTypes').length > 0 ? $IDXForm.find('input.dsidx-search-widget-propertyTypes').val() : '';
			searchParams.city = $IDXForm.find('input#idx-q-Cities').length > 0 ? $IDXForm.find('input#idx-q-Cities').val() : '';
			searchParams.bedrooms = $IDXForm.find('input#idx-q-BedsMin').length > 0 ? parseInt( $IDXForm.find('input#idx-q-BedsMin').val() ) : NaN;
			searchParams.bathrooms = $IDXForm.find('input#idx-q-BathsMin').length > 0 ? parseInt( $IDXForm.find('input#idx-q-BathsMin').val() ) : NaN;
			searchParams.min_price = $IDXForm.find('input#idx-q-PriceMin').length > 0 ? parseInt( $IDXForm.find('input#idx-q-PriceMin').val() ) : NaN;
			searchParams.max_price = $IDXForm.find('input#idx-q-PriceMax').length > 0 ? parseInt( $IDXForm.find('input#idx-q-PriceMax').val() ) : NaN;
			searchParams.sqft = $IDXForm.find('input#idx-q-ImprovedSqFtMin').length > 0 ? parseInt( $IDXForm.find('input#idx-q-ImprovedSqFtMin').val() ) : NaN;
			//alert(searchParams.bathrooms);
		}
		if ( $PROPERTYForm.length == '1' ){
			if($searchForm.length){
				searchParams.form_map 		= 'property';
				searchParams.location 		= $searchForm.find('input.glocation_input').length > 0 ? $searchForm.find('input.glocation_input').val() : '';
				searchParams.sub_location 	= $searchForm.find('input.gsub_location_input').length > 0 ? $searchForm.find('input.gsub_location_input').val() : '';
				searchParams.status 		= $searchForm.find('input.gstatus_input').length > 0 ? $searchForm.find('input.gstatus_input').val() : '';
				searchParams.category 		= $searchForm.find('input.gcategory_input').length > 0 ? $searchForm.find("input.gcategory_input").val() : '';
				searchParams.bedrooms 		= $searchForm.find('input.gbedroom_input').length > 0 ? parseInt( $searchForm.find('input.gbedroom_input').val() ) : NaN;
				searchParams.bathrooms 		= $searchForm.find('input.gbathroom_input').length > 0 ? parseInt( $searchForm.find('input.gbathroom_input').val() ) : NaN;
				searchParams.min_price 		= $searchForm.find('input.gprice_min').length > 0 ? parseFloat( $searchForm.find('input.gprice_min').val() ) : NaN;
				searchParams.max_price 		= $searchForm.find('input.gprice_max').length > 0 ? parseFloat( $searchForm.find('input.gprice_max').val() ) : NaN;
				searchParams.min_area 		= $searchForm.find('input.garea_min').length > 0 ? parseInt( $searchForm.find('input.garea_min').val() ) : NaN;
				searchParams.max_area 		= $searchForm.find('input.garea_max').length > 0 ? parseInt( $searchForm.find('input.garea_max').val() ) : NaN;
			}
		}

		return searchParams;
	}

	function setMarkerVisible( gmarker, searchParams ) {
		if( gmarker == null || typeof gmarker === "undefined" ) return false;
		if( searchParams == null || typeof searchParams === "undefined" ) return false;
		if ( searchParams.form_map == 'idx') {
			//alert( searchParams.max_price + ' - ' + gmarker.price + ' - ' + searchParams.min_price );
			if( !isNaN( searchParams.bedrooms ) && gmarker.bedrooms !==  parseInt(searchParams.bedrooms) ){

				gmarker.setVisible(false);
				return false;
			}
			if( !isNaN( searchParams.bathrooms ) && gmarker.bathrooms !==  parseInt(searchParams.bathrooms) ){
				//alert( searchParams.bathrooms + ' - ' + gmarker.bathrooms )
				gmarker.setVisible(false);
				return false;
			}
			if( !isNaN( searchParams.min_price ) && parseFloat(gmarker.price) < parseFloat(searchParams.min_price) ){
				gmarker.setVisible(false);
				return false;
			}

			if( !isNaN( searchParams.max_price ) && parseFloat(gmarker.price) > parseFloat(searchParams.max_price) ){
				gmarker.setVisible(false);
				return false;
			}
		}
		if ( searchParams.form_map == 'property') {
			if ( gmarker.location.indexOf(searchParams.location) == -1 && searchParams.location !='' ) {
				gmarker.setVisible(false);
				return false;
			}
			if( gmarker.sub_location.indexOf(searchParams.sub_location) == -1 && searchParams.sub_location !='' ){
				gmarker.setVisible(false);
				return false;
			}

			if( gmarker.status.indexOf(searchParams.status) == -1 && searchParams.status !='' ){
				gmarker.setVisible(false);
				return false;
			}

			if( gmarker.category.indexOf(searchParams.category) == -1 && searchParams.category !='' ){
				gmarker.setVisible(false);
				return false;
			}

			/*if(gmarker.location !== searchParams.location && searchParams.location !='' ){
				gmarker.setVisible(false);
				return false;
			}

			if( gmarker.sub_location !== searchParams.sub_location && searchParams.sub_location !='' ){
				gmarker.setVisible(false);
				return false;
			}

			if( gmarker.status !== searchParams.status && searchParams.status !='' ){
				gmarker.setVisible(false);
				return false;
			}

			if( gmarker.category !== searchParams.category && searchParams.category !='' ){
				gmarker.setVisible(false);
				return false;
			}*/

			if( !isNaN( searchParams.bedrooms ) && gmarker.bedrooms !== searchParams.bedrooms ){
				gmarker.setVisible(false);
				return false;
			}

			if( !isNaN( searchParams.bathrooms ) && gmarker.bathrooms !== searchParams.bathrooms ){
				gmarker.setVisible(false);
				return false;
			}

			if( !isNaN( searchParams.min_price ) && parseFloat(gmarker.price) < searchParams.min_price ){
				gmarker.setVisible(false);
				return false;
			}

			if( !isNaN( searchParams.max_price ) && parseFloat(gmarker.price) > searchParams.max_price ){
				gmarker.setVisible(false);
				return false;
			}

			if( !isNaN( searchParams.min_area ) && parseInt(gmarker.area) < searchParams.min_area ){
				gmarker.setVisible(false);
				return false;
			}

			if( !isNaN( searchParams.max_area ) && parseInt(gmarker.area) > searchParams.max_area ){
				gmarker.setVisible(false);
				return false;
			}
		}

		gmarker.setVisible(true);
		return true;
	}
	
	$( document ).ready( function () {
		if($('.gsearch').length){
			$('.gsearch').find('.dropdown-menu > li > a').on('click',function(e){
				e.stopPropagation();
			    e.preventDefault();
			    var dropdown = $(this).closest('.dropdown'),
			    	val = $(this).data('value');
			    	dropdown.children('input').val(val);
			    	dropdown.children('input').trigger('change');
			    	dropdown.children('[data-toggle="dropdown"]').text($(this).text());
			    	
			    	dropdown.children('[data-toggle="dropdown"]').dropdown('toggle');
			});
			if($('.noo-map').length){
				$('.noo-map').each(function(){
					if(!$(this).hasClass('no-gmap')){
						$(this).find('.gsearch').find('.gsearch-field').find('input[type="hidden"]').on('change',function(){
							search_filter();
						});
					}
				});
				
			}
		}
		if($('.gprice').length){
			var gsearch_price = $('.gprice'),
			min_price = gsearch_price.find('.gprice_min').data('min'),
			max_price = gsearch_price.find('.gprice_max').data('max'),
			current_min_price = gsearch_price.find('.gprice_min').val(),
			current_max_price = gsearch_price.find('.gprice_max').val();

			// current_min_price = parseInt( min_price, 10 );
			// current_max_price = parseInt( max_price, 10 );
			gsearch_price.find( ".gprice-slider-range" ).slider({
				range: true,
				animate: true,
				min: min_price,
				max: max_price,
				values: [ current_min_price, current_max_price ],
				create : function( event, ui ) {
					// $this.tooltip({title:$this.text()});
					var controls = $(this).find('.ui-slider-handle');
					$(controls[0]).tooltip({title:noo_price(current_min_price),placement:'bottom',container:'body',html:true});
					$(controls[1]).tooltip({title:noo_price(current_max_price) ,placement:'bottom',container:'body',html:true});
				},
				slide: function( event, ui ) {
					var controls = $(this).find('.ui-slider-handle');
					if( ui.value == ui.values[0] ) {
						gsearch_price.find( 'input.gprice_min' ).val( ui.values[0] ).trigger('change');
						$(controls[0]).attr('data-original-title', noo_price(ui.values[0])).tooltip('fixTitle').tooltip('show');
					}

					if( ui.value == ui.values[1] ) {
						gsearch_price.find( 'input.gprice_max' ).val( ui.values[1] ).trigger('change');
						$(controls[1]).attr('data-original-title', noo_price(ui.values[1])).tooltip('fixTitle').tooltip('show');
					}
				}
			});
		}

		if($('.garea').length){
			var gsearch_area = $('.garea'),
			min_area = gsearch_area.find('.garea_min').data('min'),
			max_area = gsearch_area.find('.garea_max').data('max'),
			current_min_area = gsearch_area.find('.garea_min').val(),
			current_max_area = gsearch_area.find('.garea_max').val();

			// current_min_area = parseInt( min_area, 10 );
			// current_max_area = parseInt( max_area, 10 );
			gsearch_area.find( ".garea-slider-range" ).slider({
				range: true,
				animate: true,
				min: min_area,
				max: max_area,
				values: [ current_min_area, current_max_area ],
				create : function( event, ui ) {
					// $this.tooltip({title:$this.text()});
					var controls = $(this).find('.ui-slider-handle');
					$(controls[0]).tooltip({title:current_min_area+' '+nooGmapL10n.area_unit,placement:'bottom',container:'body',trigger:'hover focus',html:true});
					$(controls[1]).tooltip({title:current_max_area +' '+nooGmapL10n.area_unit ,placement:'bottom',container:'body',trigger:'hover focus',html:true});
				},
				slide: function( event, ui ) {
					var controls = $(this).find('.ui-slider-handle');
					if( ui.value == ui.values[0] ) {
						gsearch_area.find( 'input.garea_min' ).val( ui.values[0] ).trigger('change');
						$(controls[0]).attr('data-original-title', ui.values[0] +' '+nooGmapL10n.area_unit).tooltip('fixTitle').tooltip('show');
					}

					if( ui.value == ui.values[1] ) {
						gsearch_area.find( 'input.garea_max' ).val( ui.values[1] ).trigger('change');
						$(controls[1]).attr('data-original-title', ui.values[1] +' '+nooGmapL10n.area_unit).tooltip('fixTitle').tooltip('show');
					}
				}
			});
		}
	});
	
})(jQuery);