app.bapiModules.templates.propertyDetails = {
    forceusemap: false,
    mobileBreak: 768,
    init: function () {
        if (this.cond())
        {
            this
            //          .fixHeroImage()
                      .openCloseAmenitiesList()
                      .lightBoxAndCarousel()
            //          .imageHover()
                      .popUpBookingForm()
                      .checkPropSettings()
            //          .checkThumbs()
                      .checkUseMap();
        }
    },
    lightBoxAndCarousel: function lightBoxAndCarousel() {

        var gallery = jQuery('.ppt-slides a').simpleLightbox();

        jQuery(document).on('click', '.more,.open-lightbox, .ppt-slides a', function (e) {
            e.preventDefault();
            if (typeof jQuery(this).attr('data-index') !== "undefined") {
                var data_index = jQuery(this).attr('data-index');
                gallery.open(jQuery(jQuery('.ppt-slides a[data-index="' + data_index + '"]')));
            }
            else
                gallery.open(jQuery(jQuery('.ppt-slides a')[0]));
        });

        if(window.innerWidth <=  768){
            this.swiperCarousel();
        }

        return this;
    },
    imageHover: function () {
        jQuery('.simple-lightbox').hover(function () {
            jQuery("#ppt-image-caption").text(jQuery(this).attr('title'));
        });
        return this;
    },
    swiperCarousel: function swiperCarousel() {
        if (typeof SWCarousel === 'undefined' || !SWCarousel)
        {
            SWCarousel = new Swiper('.ppt-images', {
                spaceBetween: 0,
                nextButton: '.next-slide',
                prevButton: '.prev-slide',
                slidesPerView: 1
            });
        }

        return this;
    },
    openCloseAmenitiesList: function openCloseAmenitiesList() {
        //Open and close amenities list.
        jQuery(document).on('click', '.template-property .open-close', function (e) {
            //ToggleClass Active
            jQuery(this).parent().toggleClass('active');
        });

        return this;
    },
    fixHeroImage: function PDFixHeroImage() {
        if (app.exists('.hero-image') && !app.isMobile()) {


            var heroImage = jQuery('.hero-image');
            var HIHeight = heroImage.height();

            var HIContainer = heroImage.parent();
            var HIContainerHeight = HIContainer.height();


            if (HIHeight > HIContainerHeight) {
                var bottomHI = Math.round((HIHeight - HIContainerHeight) / 2);
                heroImage.css('bottom', "-" + bottomHI + "px");
            }
        }
        return this;
    },
    popUpBookingForm: function () {
        jQuery(document).on('click', '.pop-up-form-link', function (e) {
            e.preventDefault();
            jQuery('.pop-up-booking-form').addClass('active');
        });

        jQuery(document).on('click', '.booking-form', function (e) {
            e.stopPropagation();
        });

        jQuery(document).on('click', '.booking-form .close, .pop-up-booking-form.active', function (e) {
            e.preventDefault();
            jQuery('.pop-up-booking-form').removeClass('active');
        });

        return this;
    },
    checkUseMap: function () {
        var pid = parseInt(jQuery('.bapi-entityadvisor').attr('data-pkid'));
        var lat = jQuery('.bapi-entityadvisor').attr('data-lat');
        var long = jQuery('.bapi-entityadvisor').attr('data-long');
       
        var selected = selected_usemap;

        var usemap = false;
        selected.forEach(function (v) {
            if (v === pid) {
                usemap = true;
            }
        });
   
        
        if(/comingsoon\.gif/.test(jQuery('.bapi-entityadvisor').attr('data-bg'))){
            usemap = true;
        }
 
        if(this.forceusemap) usemap = true;
        
        var mapbox = document.querySelector('.hero-image');
        
        if (usemap) {
            
            var map = new google.maps.Map(mapbox, {
                center: {lat: parseFloat(lat), lng: parseFloat(long)},
                scrollwheel: false,
                zoom: 18
            });

            var marker_color = document.querySelector('.template-property[data-markercolor]').dataset.markercolor;
            
            function setMarker(){
                 var icon = {
                path: "M-0.5-41C-7.9-41-14-34.9-14-27.5c0,3,1.9,7.9,5.9,15c2.8,5,5.5,9.2,5.6,9.3l2,3l2-3c0.1-0.2,2.9-4.3,5.6-9.3" +
                        "c3.9-7.1,5.9-12,5.9-15C13-34.9,7-41-0.5-41z M-0.5-20.6c-3.9,0-7-3.1-7-7s3.1-7,7-7s7,3.1,7,7S3.4-20.6-0.5-20.6z",
                fillColor: marker_color,
                fillOpacity: 1,
                strokeColor: 'rgba(0,0,0,.25)',
                strokeWeight: 1
            };

            /* Create marker + store info window inside for later use (also in property ele) */
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat, long),
                map: map,
                iw: false,
                icon: icon
            });

            }
            
            function setStreetView(){
                var panorama = new google.maps.StreetViewPanorama(
                    document.getElementById('pano'), {
                position: {lat: parseFloat(lat), lng: parseFloat(long)},
                pov: {
                    heading: 34,
                    pitch: 10
                }
            });
            
            map.setStreetView(panorama);
            }
            
            function setMapStreetview(){
                jQuery(mapbox).css('width','50%');
                
                var center = map.getCenter();
                google.maps.event.trigger(map, "resize");
                map.setCenter(center); 
                
                 jQuery('#pano').css('width','50%').css('left','50%');
            }
            
            
            switch(selected_usemap_layout) {
                case 0:
                    setMarker();
                    jQuery(mapbox).css('width','100%');
                     jQuery('#pano').hide();
                    break;
                case 1:
                    setMarker();
                    setStreetView();
                    setMapStreetview();
                    break;
                case 2:
                    setStreetView();
                    jQuery(mapbox).hide;
                    break;
                    
                default:
                    setMarker();
                    setStreetView();
                    setMapStreetview();
                    break;
            }

        } else {
            //use background image
            var background_image = jQuery('.bapi-entityadvisor').attr('data-bg');
            jQuery(mapbox).css('background-image', 'url("' + background_image + '")');
        }

        return this;
    },
    checkThumbs: function(){
        if(/comingsoon\.gif/.test($('.simple-lightbox').attr('href'))){
            if(/comingsoon\.gif/.test(jQuery('.bapi-entityadvisor').attr('data-bg'))){
                //hide images
                $('.ppt-images').hide();
                //Desc full width
                $('.ppt-desc').css('width','100%');
            }else {
                $('.ppt-images').show();
                $('.simple-lightbox').attr('href',jQuery('.bapi-entityadvisor').attr('data-bg'));
                $('.thumb-img').attr('src',jQuery('.bapi-entityadvisor').attr('data-bg'));
            }
        }else {
            //check how many images. in only one then use that as thumb and set the map
            $('.ppt-images').show();
            if($('.thumb-img').length === 1){
                $('.hero-image').css('background','transparent');
                this.forceusemap = true;
                $('.more').hide();
                $('.ppt-slides li:first-child').css('width','100%');
            }
        }
      return this;  
    },
    checkPropSettings: function(){
        if(force_usemap){
            this.forceusemap = true;
        }
        
        if(forced_featured){
            this.forcedfeatured = true;
        }
        
        if(usemap_layout>=0 && force_usemap){
            selected_usemap_layout = usemap_layout;
        }
        
        return this;
    },
    cond: function cond() {
        return app.exists('.page-template-property-detail');
    }
};
