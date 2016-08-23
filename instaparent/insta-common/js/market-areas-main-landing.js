marketAreasMainLanding =
        {
            init: function ()
            {
                if (this.cond())
                {
                    // console.log(all_market_areas);

                    /* Initialize google map */

                    /* Client Current location as center */
                    /* The initialLocation would be the location of the first property by default */

                    this.setMap('.map-popup .mini-map',
                            {
                                zoom: 5,
                                disableDefaultUI: true,
                                zoomControl: false,
                                mapTypeControl: false,
                                scaleControl: false,
                                streetViewControl: false,
                                rotateControl: false,
                                fullscreenControl: false,
                                draggable: false,
                                scalable: false,
                                scrollwheel: false
                            });

                    var ob = this;

                    $('.map-popup').on('click', function (e)
                    {
                        console.log('hit');
                        $('.market-areas-map').addClass('active'); // show the map container
                        //put the map inside:
                        ob.setMap('.market-areas-map .map-box .map', {zoom: 5});
                    });

                    // Catch esc pressed
                    $(document).on('keyup', function (e)
                    {
                        if (e.keyCode == 27)
                        {
                            //Esc from map

                            $('.market-areas-map').removeClass('active');

                        }
                    });

                    // Click outside the map box to close
                    $('.market-areas-map').on('click', function (e)
                    {
                        e.preventDefault();
                        //Esc from map

                        $(this).removeClass('active');

                    });

                    $('.close-map').on('click', function (e)
                    {
                        e.preventDefault();
                        //Esc from map

                        $('.market-areas-map').removeClass('active');

                    });

                    // Prevent from closing the map when click in map box
                    $('.market-areas-map .map-box').on('click', function (e)
                    {
                        e.preventDefault();
                        e.stopPropagation();
                    });
                }
            },
            setMap: function (selector, settings)
            {
                var defaultMapView = BAPI.config().mapviewType;
                settings = settings || {};

                var geocoder = new google.maps.Geocoder();
                var address = all_market_areas[0].name;
                geocoder.geocode({'address': address}, function (results, status)
                {

                    var options = {
                        center: results[0].geometry.location,
                        zoom: 4,
                        styles: this.mapStyles,
                        mapTypeId: google.maps.MapTypeId[defaultMapView]
                    };

                    jQuery.extend(options, settings);


                    if (status === google.maps.GeocoderStatus.OK) {
                        var gmap = new google.maps.Map(document.querySelector(selector), options);

                        // console.log(gmap);

                        var markers = []; // Create the markers you want to add and collect them into a array.


                        for (var indx in all_market_areas)
                        {
//                            console.log(all_market_areas[indx].props_inside);

                            var marker = new MarkerWithLabel({
                                position: new google.maps.LatLng(all_market_areas[indx].lat, all_market_areas[indx].lng),
                                map: gmap,
                                labelClass: "map-labels " + all_market_areas[indx].icon_class,
                                labelAnchor: new google.maps.Point(25, 42),
                                labelContent: all_market_areas[indx].props_inside+"",
                                width: '100px',
                                url: all_market_areas[indx].guid,
                                icon:
                                        {
                                            url: "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7", //Base64 1x1px transparent gif   
                                        }
                            });

                            /* Add event listeners to show info window */
                            marker.addListener('click', function ()
                            {
                                //  console.log(this);
                                document.location.href = this.url;
                            });
                        }
                    }
                    else {
                        console.log("Geocode was not successful for the following reason: " + status);
                    }
                });
            },
            cond: function cond() {
                return document.querySelector('.market-areas-main-landing');
            },
            mapStyles: [
                {
                    "featureType": "landscape",
                    "stylers": [
                        {
                            "hue": "#FFBB00"
                        },
                        {
                            "saturation": 43.400000000000006
                        },
                        {
                            "lightness": 37.599999999999994
                        },
                        {
                            "gamma": 1
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "stylers": [
                        {
                            "hue": "#FFC200"
                        },
                        {
                            "saturation": -61.8
                        },
                        {
                            "lightness": 45.599999999999994
                        },
                        {
                            "gamma": 1
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "stylers": [
                        {
                            "hue": "#FF0300"
                        },
                        {
                            "saturation": -100
                        },
                        {
                            "lightness": 51.19999999999999
                        },
                        {
                            "gamma": 1
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "stylers": [
                        {
                            "hue": "#FF0300"
                        },
                        {
                            "saturation": -100
                        },
                        {
                            "lightness": 52
                        },
                        {
                            "gamma": 1
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "stylers": [
                        {
                            "hue": "#0078FF"
                        },
                        {
                            "saturation": -13.200000000000003
                        },
                        {
                            "lightness": 2.4000000000000057
                        },
                        {
                            "gamma": 1
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "stylers": [
                        {
                            "hue": "#00FF6A"
                        },
                        {
                            "saturation": -1.0989010989011234
                        },
                        {
                            "lightness": 11.200000000000017
                        },
                        {
                            "gamma": 1
                        }
                    ]
                }
            ],
        };
