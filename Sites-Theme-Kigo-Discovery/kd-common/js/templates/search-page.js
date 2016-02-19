app.modules.templates.searchPage = {
    /* Element selectors */
    templateSelector: 'body.page-template-search-page',
    mapSelector: '#mapContainer',
    propertiesContainerSelector: '#propertiesContainer',
    propertySelector: '',
    mapObj: null,
    markers: [],
    bounds: null,
    openMarkers: [],
    /* Methods */
    init: function(){
        this.mapObj = this.initMap();
        this.initMarkers();
        this.mapResetEvents();
        this.viewEvents();
    },
    cond: function(){
        return document.querySelectorAll(this.templateSelector).length > 0;
    },
    initMap: function(){
        return new google.maps.Map(document.querySelector(this.mapSelector), {
            center: {lat: -34.397, lng: 150.644},
            zoom: 8
        });
    },
    mapResetEvents: function(){
        var ele = document.querySelector('#resetMap');
        ele.addEventListener('click', this.centerMap.bind(this));
    },
    initMarkers: function(){
        var properties = null;
        var propertiesContainer = document.querySelector(this.propertiesContainerSelector);

        /* Check if properties have been populated, if not observe until they arrive */
        if(propertiesContainer.children.length > 0){ //Properties already loaded
            properties = this.getProperties();
            this.propsToMarkers(properties);
            this.centerMap();
        }else{ //Properties not yet loaded
            var observer = new MutationObserver(function(mutations){
                    properties = this.getProperties();
                    this.propsToMarkers(properties);
                    this.centerMap();
                    observer.disconnect();
                }.bind(this)
            );
            observer.observe(propertiesContainer, {childList: true});
        }
    },
    getProperties: function(){
        return document.querySelectorAll(this.propertiesContainerSelector+' .property');
    },
    propsToMarkers: function(props){
        for(var i = 0; i < props.length; i++){

            var prop = props[i], //Get a property from the list
                mapObj = this.mapObj, //Save mapObj for later use
                propMarker = JSON.parse(prop.dataset.marker), //Parse dataset marker object
                coords = propMarker.coord, //Get marker coordinates
                iwEle = prop.querySelector('.prop-map-location'); //Get the marker's info window

            /* Create info window */
            var infoWindow = new google.maps.InfoWindow({
                content: iwEle.querySelector('.info-html').outerHTML
            });


            /* Get the primary color from the map container to set marker icon color */
            var markerColor = document.querySelector(this.mapSelector).dataset.markercolor;

            /* Create an SVG icon, fill in primary color */
            var icon = {
                path: "M-0.5-41C-7.9-41-14-34.9-14-27.5c0,3,1.9,7.9,5.9,15c2.8,5,5.5,9.2,5.6,9.3l2,3l2-3c0.1-0.2,2.9-4.3,5.6-9.3"+
                "c3.9-7.1,5.9-12,5.9-15C13-34.9,7-41-0.5-41z M-0.5-20.6c-3.9,0-7-3.1-7-7s3.1-7,7-7s7,3.1,7,7S3.4-20.6-0.5-20.6z",
                fillColor: markerColor,
                fillOpacity: 1,
                strokeColor: 'rgba(0,0,0,.25)',
                strokeWeight: 1
            };

            /* Create marker + store info window inside for later use (also in property ele) */
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(coords.lat, coords.lng),
                map: this.mapObj,
                iw: infoWindow,
                icon: icon
            });

            prop.marker = marker;

            /* Add event listeners to show info window */
            marker.addListener('click', function(marker) {
                this.openMarkers.map(function(m){m.iw.close()})
                marker.iw.open(mapObj, marker);
                this.openMarkers.push(marker);
            }.bind(this, marker));


            /* Add event listeners for list items */
            prop.querySelector('.viewInMap').addEventListener('click',
                function(prop) {
                    /* first, we close any open marker InfoWindows */
                    this.openMarkers.map(function(m){m.iw.close()})
                    /* then we can open the new marker InfoWindow */
                    prop.marker.iw.open(mapObj, prop.marker);
                    /* we store the open InfoWindows to keep track */
                    this.openMarkers.push(prop.marker);
                }.bind(this, prop)
            );

            /* We store markers for later use */
            this.markers.push(marker);
        }
    },
    centerMap: function(){
        /*
         If the map is initializing, we create new bounds to center all markers on the map.
         If not initializing (re-centering the map) we reset zoom & center position to initial values.
         */
        if(this.bounds == null) {
            var bounds = new google.maps.LatLngBounds();
            var markers = this.markers;
            /* Extend bounds to all markers and fit view */
            for (index in markers) {
                var data = markers[index];
                bounds.extend(new google.maps.LatLng(data.position.lat(), data.position.lng()));
            }
            this.bounds = bounds;
            this.mapObj.fitBounds(this.bounds);
        }else{
            /* We revert to the initial map state */
            this.mapObj.fitBounds(this.bounds);
        }
    },
    viewEvents: function(){
        var wrapper = document.querySelector('.split-search'),
            propContainer = document.querySelector('.propContainer'),
            listTmpl = 'tmpl-propertysearch-listview',
            mapTmpl = 'tmpl-propertysearch-mapview';

        propContainer.addEventListener('click', function(e){
            console.log(e.target);
            if(e.target.dataset.template == listTmpl){
                wrapper.classList.add('listView');
            }else if(e.target.dataset.template == mapTmpl){
                wrapper.classList.remove('listView');
            }
        });

    }
};