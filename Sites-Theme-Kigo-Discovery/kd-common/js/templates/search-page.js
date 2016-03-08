app.bapiModules.templates.searchPage = {
    /* Element selectors */
    defaultView: '',
    templateSelector: 'body.page-template-search-page',
    mapEle: document.querySelector('#mapContainer'),
    listInitted: false,
    mapInitted: false,
    /* Properties containers */
    mapPropContainer: document.querySelector('#mapPropertiesContainer'),
    listPropContainer: document.querySelector('#listPropertiesContainer'),
    properties: [],
    totalProps: 0,
    /* Map */
    mapObj: null,
    clustererObj: null,
    spiderfyObj: null,
    markers: [],
    propMarkers: {},
    bounds: null,
    openMarkers: [],

    /* Methods */
    cond: function(){
        return document.querySelectorAll(this.templateSelector).length > 0;
    },
    init: function(){
        this.viewToggle();
        this.defaultView = BAPI.config().defaultsearchresultview;

        switch(this.defaultView){
            case 'tmpl-propertysearch-listview':
                this.doListView();
                break;
            case 'tmpl-propertysearch-mapview':
            default:
                this.doMapView();
                this.mapResetEvents();
                break;
        }
    },
    getProperties: function(success_callback, empty_callback){
        var chunkSize = 10;

        if(this.properties.length){
            this.properties.forEach(function(prop, prop_i){

                success_callback.call(this, prop, prop_i);

                this.updateCounters();

            }.bind(this));
        }else {

            app.bapi.search('property', function (sr) {
                var ids = sr.result, total = sr.result.length;

                /* Here we have ppty total amount */
                this.totalProps = total;
                this.updateCounters();

                if(total == 0){
                    empty_callback.call(this, {}, 0);
                    return;
                }

                //Split property id's into page-sized chunks
                var chunks = _.chunk(ids, chunkSize);

                chunks.forEach(function (chunk, chunk_i) {

                    app.bapi.get('property', chunk, function (gr) {

                        gr.result.forEach(function (prop, prop_i) {

                            //Store recovered properties
                            this.properties = _.concat(this.properties, [prop]);

                            success_callback.call(this, prop, prop_i);
                            this.updateCounters();

                        }.bind(this));

                    }.bind(this), {pagesize: chunkSize, seo: true});

                }.bind(this));

            }.bind(this), BAPI.session.searchparams);

        }
    },
    /* Map view */
    updateCounters: function(){
        var current = this.properties.length;
        var percentage = current * 100 / this.totalProps;
        $('.map .loader .bar').css('width', percentage+'%');
        $('.ppty-count-current').text(current);
        $('.ppty-count-total').text(this.totalProps);
    },
    initMap: function(latitude, longitude){
        var defaultMapView = BAPI.config().mapviewType;

        this.mapObj = new google.maps.Map(this.mapEle, {
            center: {lat: latitude, lng: longitude},
            zoom: 8,
            mapTypeId: google.maps.MapTypeId[defaultMapView]
        });
    },
    initClusterer: function(){
        var mcOptions = {gridSize: 50, maxZoom: 13};
        this.clustererObj = new MarkerClusterer(this.mapObj, this.markers, mcOptions);
    },
    initSpiderfy: function(){
        this.spiderfyObj = new OverlappingMarkerSpiderfier(this.mapObj);
    },
    addMarker: function(prop){

        /* Create info window */
        var infoWindow = new google.maps.InfoWindow({
            content: '<div class="info-html prop-infowindow">'+
            '<a href="' + prop.ContextData.SEO.DetailURL + '" class="image" style="background-image: url(' + prop.PrimaryImage.ThumbnailURL + ')">'+
            '</a><div class="info">' +
            '<h5 class="title">' + prop.Headline + '</h5>'
            + prop.Location + "</div></div>"
        });

        /* Create marker + store info window inside for later use (also in property ele) */

        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(prop.Latitude, prop.Longitude),
            map: this.mapObj,
            iw: infoWindow,
            icon: {
                path: "M-0.5-41C-7.9-41-14-34.9-14-27.5c0,3,1.9,7.9,5.9,15c2.8,5,5.5,9.2,5.6,9.3l2,3l2-3c0.1-0.2,2.9-4.3,5.6-9.3"+
                "c3.9-7.1,5.9-12,5.9-15C13-34.9,7-41-0.5-41z M-0.5-20.6c-3.9,0-7-3.1-7-7s3.1-7,7-7s7,3.1,7,7S3.4-20.6-0.5-20.6z",
                fillColor: this.mapEle.dataset.color,
                fillOpacity: 1,
                strokeColor: 'rgba(0,0,0,.25)',
                strokeWeight: 1
            }
        });

        //Standard event handling
        /* Add event listeners to show info window */
        //marker.addListener('click', this.openMarker.bind(this, marker));

        /* Add marker to Clusterer */
        this.clustererObj.addMarker(marker);

        /* Add marker to spidify */
        this.spiderfyObj.addMarker(marker);
        //Spidify event handling
        this.spiderfyObj.addListener('click', function(marker) {
            this.openMarker(marker);
        }.bind(this));

        /* We store markers for later use */
        this.markers.push(marker);
        this.propMarkers[prop.AltID] = marker;
    },
    openMarker: function(marker){
        /* first, we close any open marker InfoWindows */
        this.openMarkers.map(function(m){m.iw.close()});
        /* then we can open the new marker InfoWindow */
        this.mapObj.setZoom(15);

        var adjustedPos = new google.maps.LatLng({lat: marker.getPosition().lat() + 0.004623495678337974, lng: marker.getPosition().lng()});
        this.mapObj.panTo(adjustedPos);

        _.delay(function(){
            marker.iw.open(this.mapObj, marker);
        }.bind(this), 250);
        /* we store the open InfoWindows to keep track */
        this.openMarkers.push(marker);
    },
    addMapProps: function(){
        //Render properties
        var propHTML = app.bapi.render('tmpl-propertysearch-mapview', {result: this.properties, textdata: BAPI.textdata});
        this.mapPropContainer.innerHTML = propHTML;

        //Attach event listeneers
        var props = this.mapPropContainer.querySelectorAll('[data-altid]');

        _(props).forEach(function(prop){
            var markerToggle = prop.querySelector('.viewInMap');
            var altid = prop.dataset.altid;
            markerToggle.addEventListener('click', function(){
                var marker = this.propMarkers[altid];
                this.openMarker(marker);
            }.bind(this));
        }.bind(this));
    },
    mapResetEvents: function(){
        var ele = document.querySelector('#resetMap');
        ele.addEventListener('click', this.centerMap.bind(this));
    },
    centerMap: function(){

        if(this.bounds == null) {
            var bounds = new google.maps.LatLngBounds();
            var markers = this.markers;
            /* Extend bounds to all markers and fit view */
            for (i in markers) {
                var data = markers[i];
                bounds.extend(new google.maps.LatLng(data.position.lat(), data.position.lng()));
            }
            this.bounds = bounds;
            this.mapObj.fitBounds(this.bounds);
        }else{
            /* We revert to the initial map state */
            this.mapObj.fitBounds(this.bounds);
        }
    },
    /* View initializers */
    doMapView: function(){
        /* Update view layout */
        document.querySelector('.listView').classList.add('hidden');
        document.querySelector('.mapView').classList.remove('hidden');
        //document.querySelector('.viewToggle .v-map').classList.add('active');

        if(this.mapInitted){return;}

        this.getProperties(function(prop, prop_i){
            //Search has returned properties

            if(this.markers.length == 0){
                //First iteration, we can initialize map on first location coordinates
                //Also initialize Marker Clusterer to start adding markers to it

                this.initMap(prop.Latitude, prop.Longitude);
                this.initClusterer();
                this.initSpiderfy();
            }

            this.addMarker(prop);

            //Last marker iteration
            if(this.properties.length == this.totalProps){
                this.centerMap();
                this.addMapProps();
                this.mapInitted = true;
            }
        }, function(){
            //Search has returned no properties (empty)
            this.addMapProps();
        });
    },
    doListView: function(){
        /* Update view layout */
        document.querySelector('.mapView').classList.add('hidden');
        document.querySelector('.listView').classList.remove('hidden');
        //document.querySelector('.viewToggle .v-list').classList.add('active');

        if(this.listInitted)return;

        this.getProperties(function(prop, prop_i){

                prop.Summary = prop.Summary.substr(0, 200) + '...';
                //Search has returned properties
                var propHTML = app.bapi.render('tmpl-propertysearch-listview', {result: [prop], textdata: BAPI.textdata});
                this.listPropContainer.innerHTML += propHTML;
            },
            function(){
                //Search has returned no properties (empty)

                var propHTML = app.bapi.render('tmpl-propertysearch-listview', {result: [], textdata: BAPI.textdata});
                this.listPropContainer.innerHTML += propHTML;
            });


        this.listInitted = true;
    },
    /* View toggle */
    viewToggle: function(){
        /* View toggle buttons */
        document.querySelector('.viewToggle .v-map').addEventListener('click', this.doMapView.bind(this));
        document.querySelector('.viewToggle .v-list').addEventListener('click', this.doListView.bind(this));
    }

};