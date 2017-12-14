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
}! function(a) {
    "use strict";

    function b(a) {
        var c, b = nooGmapL10n.currency_position;
        switch (b) {
            case "left":
                c = "%1$s%2$s";
                break;
            case "right":
                c = "%2$s%1$s";
                break;
            case "left_space":
                c = "%1$s&nbsp;%2$s";
                break;
            case "right_space":
                c = "%2$s&nbsp;%1$s"
        }
        return a = noo_number_format(a, nooGmapL10n.num_decimals, nooGmapL10n.decimal_sep, nooGmapL10n.thousands_sep), c.replace("%1$s", nooGmapL10n.currency).replace("%2$s", a)
    }

    function c() {
        mapSearchBox = a(".noo-map");
        var b = mapSearchBox.find("#gmap"),
            c = nooGmapL10n.latitude,
            d = nooGmapL10n.longitude;
        if (mapSearchBox.length && b.length) {
            var e = new google.maps.LatLng(c, d),
                f = {
                    flat: !1,
                    noClear: !1,
                    zoom: parseInt(nooGmapL10n.zoom),
                    scrollwheel: !1,
                    streetViewControl: !1,
                    disableDefaultUI: !0,
                    draggable: Modernizr.touch ? !1 : nooGmapL10n.draggable,
                    center: e,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
            map = new google.maps.Map(b.get(0), f), google.maps.visualRefresh = !0, google.maps.event.addListener(map, "tilesloaded", function() {
                mapSearchBox.find(".gmap-loading").hide()
            });
            var g = document.getElementById("gmap_search_input");
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(g);
            var j = new google.maps.places.SearchBox(g);
            google.maps.event.addListener(j, "places_changed", function() {
                var a = j.getPlaces();
                if (0 != a.length) {
                    for (var d, b = new google.maps.LatLngBounds, c = 0; d = a[c]; c++) {
                        new google.maps.Marker({
                            map: map,
                            zoom: parseInt(nooGmapL10n.zoom),
                            title: d.name,
                            position: d.geometry.location
                        });
                        b.extend(d.geometry.location)
                    }
                    map.fitBounds(b), map.setZoom(parseInt(nooGmapL10n.zoom))
                }
            });
            var k = {
                content: document.createElement("div"),
                disableAutoPan: !0,
                maxWidth: 500,
                boxClass: "myinfobox",
                zIndex: null,
                closeBoxMargin: "-13px 0px 0px 0px",
                closeBoxURL: "",
                infoBoxClearance: new google.maps.Size(1, 1),
                isHidden: !1,
                pane: "floatPane",
                enableEventPropagation: !1
            };
            infoBox = new InfoBox(k);
            var l = function(a) {
                var b = '<div class="gmap-infobox"><a class="info-close" onclick="return infoBox.close();" href="javascript:void(0)">x</a>          <div class="info-img">' + a.image + '</div>             <div class="info-summary">               <h5 class="info-title">' + a.title + "</h5>";
                ("" != a.area || "" != q.bedrooms || "" != q.bathrooms) && (b += '<div class="info-detail">', "" != a.area && (b += '<div class="size"><span>' + a.area + "</span></div>"), "" != a.bedrooms && (b += '<div class="bedrooms"><span>' + a.bedrooms + "</span></div>"), "" != a.bathrooms && (b += '<div class="bathrooms"><span>' + a.bathrooms + "</span></div>"), b += "</div>"), b += '<div class="info-more">                <div class="info-price">' + a.price_html + '</div>                <div class="info-action"><a href="' + a.url + '"><i class="fa fa-plus"></i></a></div>               </div>            </div>          </div>', infoBox.setContent(b), infoBox.open(map, a), map.setCenter(a.position), map.panBy(50, -120)
            };
            if ("IDX" != a(b).data("source") || "object" != typeof dsidx || a.isEmptyObject(dsidx.dataSets)) {
                var y = a.parseJSON(nooGmapL10n.markers);
                if (y.length)
                    for (var n = new google.maps.LatLngBounds, o = h(), p = 0; p < y.length; p++) {
                        var q = y[p],
                            w = new google.maps.LatLng(q.latitude, q.longitude),
                            x = new google.maps.Marker({
                                position: w,
                                map: map,
                                image: q.image,
                                title: q.title,
                                area: q.area,
                                bedrooms: q.bedrooms,
                                bathrooms: q.bathrooms,
                                price: q.price,
                                price_html: q.price_html,
                                url: q.url,
                                category: q.category,
                                status: q.status,
                                sub_location: q.sub_location,
                                location: q.location
                            });
                        "" != q.icon && x.setIcon(q.icon), gmarkers.push(x), i(x, o) && (n.extend(x.getPosition()), nooGmapL10n.fitbounds && map.fitBounds(n)), google.maps.event.addListener(x, "click", function(a) {
                            l(this)
                        })
                    }
            } else {
                var m = null,
                    n = new google.maps.LatLngBounds,
                    o = h();
                a.each(dsidx.dataSets, function(a, b) {
                    m = a
                });
                for (var p = 0; p < dsidx.dataSets[m].length; p++) {
                    var q = dsidx.dataSets[m][p];
                    if (void 0 !== q.ShortDescription) var r = q.ShortDescription.split(","),
                        s = r[0] + ", " + r[1];
                    else var s = q.Address + ", " + q.City;
                    var t = parseInt(q.BedsShortString.charAt(0)),
                        u = parseInt(q.BathsShortString.charAt(0)),
                        v = parseFloat(q.Price.replace(/[^\d.]/g, "")),
                        w = new google.maps.LatLng(q.Latitude, q.Longitude),
                        x = new google.maps.Marker({
                            position: w,
                            map: map,
                            area: q.ImprovedSqFt + " " + nooGmapL10n.area_unit,
                            image: q.PhotoUriBase,
                            title: s,
                            bedrooms: t,
                            bathrooms: u,
                            price: v,
                            price_html: q.Price,
                            url: nooGmapL10n.home_url + "/idx/" + q.PrettyUriForUrl
                        });
                    x.setIcon(nooGmapL10n.theme_uri + "/assets/images/marker-icon.png"), gmarkers.push(x), i(x, o) && (n.extend(x.getPosition()), nooGmapL10n.fitbounds && map.fitBounds(n)), google.maps.event.addListener(x, "click", function(a) {
                        l(this)
                    })
                }
            }
            var z = [{
                textColor: "#ffffff",
                opt_textColor: "#ffffff",
                url: nooGmapL10n.theme_uri + "/assets/images/cloud.png",
                height: 72,
                width: 72,
                textSize: 15
            }];
            mcluster = new MarkerClusterer(map, gmarkers, {
                gridSize: 50,
                ignoreHidden: !0,
                styles: z,
                maxZoom: 20
            }), mcluster.setIgnoreHidden(!0), mapSearchBox.find(".zoom-in").length && google.maps.event.addDomListener(mapSearchBox.find(".zoom-in").get(0), "click", function(a) {
                a.stopPropagation(), a.preventDefault();
                var b = parseInt(map.getZoom(), 10);
                b++, b > 20 && (b = 20), map.setZoom(b)
            }), mapSearchBox.find(".zoom-out").length && google.maps.event.addDomListener(mapSearchBox.find(".zoom-out").get(0), "click", function(a) {
                a.stopPropagation(), a.preventDefault();
                var b = parseInt(map.getZoom(), 10);
                b--, 0 > b && (b = 0), map.setZoom(b)
            })
        }
    }

    function d(a) {
        var b = {
            coord: [1, 1, 1, 38, 38, 59, 59, 1],
            type: "poly"
        };
        15 != map.getZoom() && map.setZoom(15);
        var c = new google.maps.LatLng(a.coords.latitude, a.coords.longitude);
        map.setCenter(c);
        var e = (new google.maps.Marker({
            position: c,
            map: map,
            icon: nooGmapL10n.theme_uri + "/assets/images/my-marker.png",
            shape: b,
            zIndex: 9999,
            infoWindowIndex: 9999,
            radius: 1e3
        }), {
            strokeColor: "#75b08a",
            strokeOpacity: .6,
            strokeWeight: 1,
            fillColor: "#75b08a",
            fillOpacity: .2,
            map: map,
            center: c,
            radius: 1e3
        });
        new google.maps.Circle(e)
    }

    function e() {
        alert(nooGmapL10n.no_geolocation_pos)
    }

    function f() {
        var c, b = a(".property-map-box"),
            d = b.data("zoom"),
            e = b.data("latitude"),
            f = b.data("longitude"),
            g = b.data("marker");
        if (b.length) {
            var h = new google.maps.LatLng(e, f),
                i = new google.maps.Map(b.get(0), {
                    flat: !1,
                    noClear: !1,
                    zoom: d,
                    scrollwheel: !1,
                    draggable: Modernizr.touch ? !1 : nooGmapL10n.draggable,
                    center: h,
                    streetViewControl: !1,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });
            "" == g && (g = nooGmapL10n.theme_uri + "/assets/images/marker-icon.png");
            var g = new google.maps.Marker({
                    icon: g,
                    position: h,
                    map: i
                }),
                j = document.getElementById("property_map_search_input");
            i.controls[google.maps.ControlPosition.TOP_LEFT].push(j);
            var k = new google.maps.places.SearchBox(j);
            google.maps.event.addListener(k, "places_changed", function() {
                null != c && c.setMap(null);
                var a = new google.maps.Geocoder,
                    b = function(b) {
                        a.geocode({
                            latLng: b
                        }, function(a, b) {
                            return b == google.maps.GeocoderStatus.OK && a[0] ? a[0].formatted_address : void 0
                        })
                    };
                b(h);
                a.geocode({
                    address: j.value
                }, function(a, b) {
                    if (b == google.maps.GeocoderStatus.OK) {
                        i.setCenter(a[0].geometry.location), c = new google.maps.Marker({
                            position: a[0].geometry.location,
                            map: i,
                            draggable: !1,
                            animation: google.maps.Animation.DROP
                        });
                        var d = j.value,
                            e = h,
                            f = new google.maps.DirectionsService,
                            g = new google.maps.DirectionsRenderer;
                        g.setMap(i);
                        var k = {
                            origin: d,
                            destination: e,
                            travelMode: google.maps.TravelMode.DRIVING
                        };
                        f.route(k, function(a, b) {
                            b == google.maps.DirectionsStatus.OK && g.setDirections(a)
                        })
                    } else alert("Geocode was not successful for the following reason: " + b)
                })
            })
        }
    }

    function g() {
        a(".noo_advanced_search_property").each(function() {
            var b = a(this);
            if (b.find("#gmap").length) {
                var c = h();
                "undefined" != typeof infoBox && null !== infoBox && infoBox.close();
                var d = new google.maps.LatLngBounds;
                if ("undefined" != typeof mcluster && mcluster.setIgnoreHidden(!0), gmarkers.length) {
                    for (var e = 0; e < gmarkers.length; e++) {
                        var f = gmarkers[e];
                        i(f, c) && d.extend(f.getPosition())
                    }
                    "undefined" != typeof mcluster && mcluster.repaint()
                }
                map.setZoom(10), d.isEmpty() || map.fitBounds(d)
            }
        })
    }

    function h() {
        var b = a(".noo_advanced_search_property .idx"),
            c = a(".noo_advanced_search_property .property");
        if ("1" == b.length) var d = {
            form_map: "",
            city: "",
            bedrooms: NaN,
            bathrooms: NaN,
            min_price: NaN,
            max_price: NaN,
            sqft: NaN
        };
        if ("1" == c.length) var d = {
            form_map: "",
            location: "",
            sub_location: "",
            status: "",
            category: "",
            bedrooms: NaN,
            bathrooms: NaN,
            min_price: NaN,
            max_price: NaN,
            min_area: NaN,
            max_area: NaN
        };
        if (!a("#gmap").length) return d;
        var e = a(".noo_advanced_search_property .gsearch");
        return "1" == b.length && (d.form_map = "idx", d.city = b.find("input#idx-q-Cities").length > 0 ? b.find("input#idx-q-Cities").val() : "", d.bedrooms = b.find("input#idx-q-BedsMin").length > 0 ? parseInt(b.find("input#idx-q-BedsMin").val()) : NaN, d.bathrooms = b.find("input#idx-q-BathsMin").length > 0 ? parseInt(b.find("input#idx-q-BathsMin").val()) : NaN, d.min_price = b.find("input#idx-q-PriceMin").length > 0 ? parseInt(b.find("input#idx-q-PriceMin").val()) : NaN, d.max_price = b.find("input#idx-q-PriceMax").length > 0 ? parseInt(b.find("input#idx-q-PriceMax").val()) : NaN, d.sqft = b.find("input#idx-q-ImprovedSqFtMin").length > 0 ? parseInt(b.find("input#idx-q-ImprovedSqFtMin").val()) : NaN), "1" == c.length && e.length && (d.form_map = "property", d.location = e.find("input.glocation_input").length > 0 ? e.find("input.glocation_input").val() : "", d.sub_location = e.find("input.gsub_location_input").length > 0 ? e.find("input.gsub_location_input").val() : "", d.status = e.find("input.gstatus_input").length > 0 ? e.find("input.gstatus_input").val() : "", d.category = e.find("input.gcategory_input").length > 0 ? e.find("input.gcategory_input").val() : "", d.bedrooms = e.find("input.gbedroom_input").length > 0 ? parseInt(e.find("input.gbedroom_input").val()) : NaN, d.bathrooms = e.find("input.gbathroom_input").length > 0 ? parseInt(e.find("input.gbathroom_input").val()) : NaN, d.min_price = e.find("input.gprice_min").length > 0 ? parseFloat(e.find("input.gprice_min").val()) : NaN, d.max_price = e.find("input.gprice_max").length > 0 ? parseFloat(e.find("input.gprice_max").val()) : NaN, d.min_area = e.find("input.garea_min").length > 0 ? parseInt(e.find("input.garea_min").val()) : NaN, d.max_area = e.find("input.garea_max").length > 0 ? parseInt(e.find("input.garea_max").val()) : NaN), d
    }

    function i(a, b) {
        if (null == a || "undefined" == typeof a) return !1;
        if (null == b || "undefined" == typeof b) return !1;
        if ("idx" == b.form_map) {
            if (!isNaN(b.bedrooms) && a.bedrooms !== parseInt(b.bedrooms)) return a.setVisible(!1), !1;
            if (!isNaN(b.bathrooms) && a.bathrooms !== parseInt(b.bathrooms)) return a.setVisible(!1), !1;
            if (!isNaN(b.min_price) && parseFloat(a.price) < parseFloat(b.min_price)) return a.setVisible(!1), !1;
            if (!isNaN(b.max_price) && parseFloat(a.price) > parseFloat(b.max_price)) return a.setVisible(!1), !1
        }
        if ("property" == b.form_map) {
            if (-1 == a.location.indexOf(b.location) && "" != b.location) return a.setVisible(!1), !1;
            if (-1 == a.sub_location.indexOf(b.sub_location) && "" != b.sub_location) return a.setVisible(!1), !1;
            if (-1 == a.status.indexOf(b.status) && "" != b.status) return a.setVisible(!1), !1;
            if (-1 == a.category.indexOf(b.category) && "" != b.category) return a.setVisible(!1), !1;
            if (!isNaN(b.bedrooms) && a.bedrooms !== b.bedrooms) return a.setVisible(!1), !1;
            if (!isNaN(b.bathrooms) && a.bathrooms !== b.bathrooms) return a.setVisible(!1), !1;
            if (!isNaN(b.min_price) && parseFloat(a.price) < b.min_price) return a.setVisible(!1), !1;
            if (!isNaN(b.max_price) && parseFloat(a.price) > b.max_price) return a.setVisible(!1), !1;
            if (!isNaN(b.min_area) && parseInt(a.area) < b.min_area) return a.setVisible(!1), !1;
            if (!isNaN(b.max_area) && parseInt(a.area) > b.max_area) return a.setVisible(!1), !1
        }
        return a.setVisible(!0), !0
    }
    a.fn.nooLoadmore = function(b, c) {
        var d = {
                agentID: 0,
                contentSelector: null,
                contentWrapper: null,
                nextSelector: "div.navigation a:first",
                navSelector: "div.navigation",
                itemSelector: "div.post",
                dataType: "html",
                finishedMsg: "<em>Congratulations, you've reached the end of the internet.</em>",
                loading: {
                    speed: "fast",
                    start: void 0
                },
                state: {
                    isDuringAjax: !1,
                    isInvalidPage: !1,
                    isDestroyed: !1,
                    isDone: !1,
                    isPaused: !1,
                    isBeyondMaxPage: !1,
                    currPage: 1
                }
            },
            b = a.extend(d, b);
        return this.each(function() {
            var d = this,
                e = a(this),
                f = e.find(".loadmore-wrap"),
                g = e.find(".loadmore-action"),
                h = g.find(".btn-loadmore"),
                i = g.find(".loadmore-loading");
            b.contentWrapper = b.contentWrapper || f;
            var j = function(a) {
                    if (a.match(/^(.*?)\b2\b(.*?$)/)) a = a.match(/^(.*?)\b2\b(.*?$)/).slice(1);
                    else if (a.match(/^(.*?)2(.*?$)/)) {
                        if (a.match(/^(.*?page=)2(\/.*|$)/)) return a = a.match(/^(.*?page=)2(\/.*|$)/).slice(1);
                        a = a.match(/^(.*?)2(.*?$)/).slice(1)
                    } else {
                        if (a.match(/^(.*?page=)1(\/.*|$)/)) return a = a.match(/^(.*?page=)1(\/.*|$)/).slice(1);
                        b.state.isInvalidPage = !0
                    }
                    return a
                },
                k = a(b.nextSelector).attr("href");
            k = j(k), b.callback = function(d, e) {
                c && c.call(a(b.contentSelector)[0], d, b, e)
            }, b.loading.start = b.loading.start || function() {
                h.hide(), a(b.navSelector).hide(), i.show(b.loading.speed, a.proxy(function() {
                    l(b)
                }, d))
            };
            var l = function(b) {
                var d;
                b.callback;
                return b.state.currPage++, void 0 !== b.maxPage && b.state.currPage > b.maxPage ? void(b.state.isBeyondMaxPage = !0) : (d = k.join(b.state.currPage), void a.post(nooGmapL10n.ajax_url, {
                    action: "noo_agent_ajax_property",
                    page: b.state.currPage,
                    agent_id: b.agentID
                }, function(c) {
                    return "" == c.content || null == c.content || void 0 == c.content ? (h.hide(), void g.append('<div style="margin-top:5px;">' + b.finishedMsg + "</div>").animate({
                        opacity: 1
                    }, 2e3, function() {
                        g.fadeOut(b.loading.speed)
                    })) : (a(b.contentWrapper).append(c.content), i.hide(), h.show(b.loading.speed), void 0)
                }, "json"))
            };
            h.on("click", function(c) {
                c.stopPropagation(), c.preventDefault(), b.loading.start.call(a(b.contentWrapper)[0], b)
            })
        })
    }, google.maps.event.addDomListener(window, "load", c), a(document).on("click", ".gmap-mylocation", function(a) {
        a.stopPropagation(), a.preventDefault(), navigator.geolocation ? navigator.geolocation.getCurrentPosition(d, e, {
            timeout: 1e4
        }) : alert(nooGmapL10n.no_geolocation_msg)
    }), a(document).on("click", ".gmap-full", function(b) {
        b.stopPropagation(), b.preventDefault(), a(this).closest(".noo-map").hasClass("fullscreen") ? (a(this).closest(".noo-map").removeClass("fullscreen"), a(this).empty().html('<i class="fa fa-expand"></i> ' + nooGmapL10n.fullscreen_label)) : (a(this).closest(".noo-map").addClass("fullscreen"), a(this).empty().html('<i class="fa fa-compress"></i> ' + nooGmapL10n.default_label)), google.maps.event.trigger(map, "resize")
    }), a(document).on("click", ".gmap-prev", function(a) {
        for (a.stopPropagation(), a.preventDefault(), gmarker_index--, gmarker_index < 1 && (gmarker_index = gmarkers.length); gmarkers[gmarker_index - 1].visible === !1;) gmarker_index--, gmarker_index > gmarkers.length && (gmarker_index = 1);
        map.getZoom() < 15 && map.setZoom(15), google.maps.event.trigger(gmarkers[gmarker_index - 1], "click")
    }), a(document).on("click", ".gmap-next", function(a) {
        for (a.stopPropagation(), a.preventDefault(), gmarker_index++, gmarker_index > gmarkers.length && (gmarker_index = 1); gmarkers[gmarker_index - 1].visible === !1;) gmarker_index++, gmarker_index > gmarkers.length && (gmarker_index = 1);
        map.getZoom() < 15 && map.setZoom(15), google.maps.event.trigger(gmarkers[gmarker_index - 1], "click")
    }), google.maps.event.addDomListener(window, "load", f), a(document).ready(function() {
        if (a(".gsearch").length && (a(".gsearch").find(".dropdown-menu > li > a").on("click", function(b) {
                b.stopPropagation(), b.preventDefault();
                var c = a(this).closest(".dropdown"),
                    d = a(this).data("value");
                c.children("input").val(d), c.children("input").trigger("change"), c.children('[data-toggle="dropdown"]').text(a(this).text()), c.children('[data-toggle="dropdown"]').dropdown("toggle")
            }), a(".noo-map").length && a(".noo-map").each(function() {
                a(this).hasClass("no-gmap") || a(this).find(".gsearch").find(".gsearch-field").find('input[type="hidden"]').on("change", function() {
                    g()
                })
            })), a(".gprice").length) {
            var c = a(".gprice"),
                d = c.find(".gprice_min").data("min"),
                e = c.find(".gprice_max").data("max"),
                f = c.find(".gprice_min").val(),
                h = c.find(".gprice_max").val();
            c.find(".gprice-slider-range").slider({
                range: !0,
                animate: !0,
                min: d,
                max: e,
                values: [f, h],
                create: function(c, d) {
                    var e = a(this).find(".ui-slider-handle");
                    a(e[0]).tooltip({
                        title: b(f),
                        placement: "top",
                        container: "body",
                        html: !0
                    }), a(e[1]).tooltip({
                        title: b(h),
                        placement: "top",
                        container: "body",
                        html: !0
                    })
                },
                slide: function(d, e) {
                    var f = a(this).find(".ui-slider-handle");
					
                    e.value == e.values[0] && (c.find("input.gprice_min").val(e.values[0]).trigger("change"), a(f[0]).attr("data-original-title", b(e.values[0])).tooltip("fixTitle").tooltip("show")), e.value == e.values[1] && (c.find("input.gprice_max").val(e.values[1]).trigger("change"), a(f[1]).attr("data-original-title", b(e.values[1])).tooltip("fixTitle").tooltip("show"))
				//alert(noo_number_format(e.values[0]));
				 c.find('#min_price').html("$"+" "+ noo_number_format(e.values[0]));
				 c.find('#max_price').html("$"+" "+ noo_number_format(e.values[1]));
                }
            })
        }
        if (a(".garea").length) {
            var i = a(".garea"),
                j = i.find(".garea_min").data("min"),
                k = i.find(".garea_max").data("max"),
                l = i.find(".garea_min").val(),
                m = i.find(".garea_max").val();
            i.find(".garea-slider-range").slider({
                range: !0,
                animate: !0,
                min: j,
                max: k,
                values: [l, m],
                create: function(b, c) {
                    var d = a(this).find(".ui-slider-handle");
                    a(d[0]).tooltip({
                        title: l + " " + nooGmapL10n.area_unit,
                        placement: "top",
                        container: "body",
                        trigger: "hover focus",
                        html: !0
                    }), a(d[1]).tooltip({
                        title: m + " " + nooGmapL10n.area_unit,
                        placement: "top",
                        container: "body",
                        trigger: "hover focus",
                        html: !0
                    })
                },
                slide: function(b, c) {
                    var d = a(this).find(".ui-slider-handle");
                    c.value == c.values[0] && (i.find("input.garea_min").val(c.values[0]).trigger("change"), a(d[0]).attr("data-original-title", c.values[0] + " " + nooGmapL10n.area_unit).tooltip("fixTitle").tooltip("show")), c.value == c.values[1] && (i.find("input.garea_max").val(c.values[1]).trigger("change"), a(d[1]).attr("data-original-title", c.values[1] + " " + nooGmapL10n.area_unit).tooltip("fixTitle").tooltip("show"))
				 i.find('#min_area').html(c.values[0]+ " " + nooGmapL10n.area_unit);
				 i.find('#max_area').html(c.values[1]+ " " + nooGmapL10n.area_unit);
                }
            })
        }
    })
}(jQuery);