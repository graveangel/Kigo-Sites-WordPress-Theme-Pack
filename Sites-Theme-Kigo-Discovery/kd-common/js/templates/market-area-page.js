app.modules.templates.marketAreaPage =
        {
            init: function ()
            {
                if (this.cond())
                {
                    // Initialize Google map in .market-area-map
                    var center = map_center;
                    marker_array = [];

                    var ob = this;
                    $(window).resize(function (e)
                    {
                        ob.setMapContainer('.mpbx');

                    });
                    
                    $(document).on('headerFixed', function (e)
                    {
                       ob.setMapContainer('.mpbx');
                    });

                    $(document).on('headerunFixed', function (e)
                    {
                       $(window).resize();
                    });
                    
                    this.setMapContainer('.mpbx');

                    var map = this.put_map_in('.mpbx', center);
                    // put properties in map
                    this.put_properties_in_(map, '.mpbx');


                    // Toggle View
                    $('.vs-button').on('click', function (e)
                    {
                        e.preventDefault();
                        
                        $('.vs-button.active').removeClass('active');
                        
                        $(this).addClass('active');
                        
                        var attrClass = $(this).attr('data-list');
                        $('.property-list')
                                .removeClass('photo')
                                .removeClass('list')
                                .addClass(attrClass);
                        ob.setMapContainer('.mpbx');
                    });



                    // Swiper
                    //initialize swiper when document ready  
                    var mySwiper = new Swiper('.swiper-container', {
                        // Optional parameters
                        loop: true,
                        slidesPerView   : 3,
                        nextButton      : '.swiper-button-next',
                        prevButton      : '.swiper-button-prev',
                        autoplay        : 3000,
                        speed           : 300,
                        breakpoints: 
                                {
                                    768: 
                                    {
                                        slidesPerView: 1
                                    }
                                }
                    });

                    $.each($('.ppt-box'), function (i, v)
                    {

                        $(v).hover(function (e)
                        {
                            new google.maps.event.trigger(marker_array[i], 'click');
                        });
                    });
                    // Market Area Modal
                    this.enable_modals();
                }
            },
            put_properties_in_: function (map, selector)
            {
                var props = all_props;

                for (var idx in props)
                {
                    var prp = props[idx];

                    /* Create info window */
                    var infoWindow = new google.maps.InfoWindow({
                        content: '<div class="info-html prop-infowindow">' +
                                '<a href="' + prp.url + '" class="image" style="background-image: url(' + prp.thumbnail + ')">' +
                                '</a><div class="info">' +
                                '<h5 class="title">' + prp.title + '</h5>' +
                                '<p>' + prp.summary + '</p>'
                                + "</div></div>"
                    });

                    var marker_settings =
                            {
                                position    : prp.location,
                                map         : map,
                                title       : prp.title,
                                iw          : infoWindow,
                                icon        : {
                                    path        : "M-0.5-41C-7.9-41-14-34.9-14-27.5c0,3,1.9,7.9,5.9,15c2.8,5,5.5,9.2,5.6,9.3l2,3l2-3c0.1-0.2,2.9-4.3,5.6-9.3" +
                                                  "c3.9-7.1,5.9-12,5.9-15C13-34.9,7-41-0.5-41z M-0.5-20.6c-3.9,0-7-3.1-7-7s3.1-7,7-7s7,3.1,7,7S3.4-20.6-0.5-20.6z",
                                    fillColor   : $(selector).css('color'),
                                    fillOpacity : 1,
                                    strokeWeight: 0
                                }
                            };
                    var marker = new google.maps.Marker(marker_settings);
                    
                    marker_array.push(marker);
                    last_iw_opened = infoWindow;
                    
                    marker.addListener('click', function () {
                        last_iw_opened.close();
                        last_iw_opened = this.iw;
                        this.iw.open(map, this);
                    });
                }

            },
            put_map_in: function (selector, center)
            {
                var map_container   = document.querySelector(selector);

                var settings        =
                        {
                            center  : center,
                            zoom    : 10
                        };

                var map = new google.maps.Map(map_container, settings);
                return map;
            },
            scrTop: function (selector)
            {
                var scrollTop       = $(window).scrollTop(),
                    elementOffset   = $(selector).offset().top,
                    distance        = (elementOffset - scrollTop);
                
                return distance;
            },
            bottomReached: function (ob)
            {
                var WINDOW_HEIGHT   = $(window).height();
                var OBJECT_TOP      = $(ob).offset().top;
                var OBJECT_HEIGHT   = $(ob).height();
                var scrolled_height = $(window).scrollTop();
                var bottom_distance = -1 * (((OBJECT_HEIGHT + OBJECT_TOP) - WINDOW_HEIGHT) - scrolled_height);
                    
                return bottom_distance;
            },
            setMapContainer: function (map_container)
            {
                
                var header_top = $('.global-wrapper > .header-background .under_header.header-background');
                var headheight = ($(window).height()) + 'px';
                var bottom_distance = this.bottomReached('.property-list');
                var width = $(map_container).parent().width();
                var left = $('.market-area-content').offset().left + $('.market-area-content').width();
                var bottom_offset = $('header .under_header').height();
                var top = 0;


                if (header_top.css('position') == 'fixed')
                {
                    top = ($('.header + .under_header').height() + parseFloat($('.header + .under_header').css('padding-top')) * 2) + 'px';
                }
                else
                {
                    top = this.scrTop('.market-area-content') + 'px';
                }

                
                if (bottom_distance >= bottom_offset)
                {
                    $(map_container)
                            .css('height','calc(100vh - '+ $('.featured-image').height() +'px )')
                            .parent()
                            .addClass('ready')
                            .css('height', headheight)
                            .css('right', '0px')
                            .css('left', 'auto')
                            .css('width', width + 'px')
                            .css('top', 'auto')
                            .css('bottom', '0px')
                            .css('position', 'absolute');
                } else
                {
                   
                    $(map_container)
                            .css('height','calc(100vh - '+ $('.featured-image').height() +'px )')
                            .parent()
                            .addClass('ready')
                            .css('height', headheight)
                            .css('left', left + 'px')
                            .css('right', 'auto')
                            .css('width', width + 'px')
                            .css('top', top)
                            .css('bottom', 'auto')
                            .css('position', 'fixed');
                }
            },
             enable_modals: function()
            {
                $('.trigger-modal').on('click', function(e)
                {
                    e.preventDefault();
                    var target = $(this).attr('data-modal');
                    $(target).modal();
                });
            },
            cond: function ()
            {
                return app.exists('.market-area-page');
            }
        }