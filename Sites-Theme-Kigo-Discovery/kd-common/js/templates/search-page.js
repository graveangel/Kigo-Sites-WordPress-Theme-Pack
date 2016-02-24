app.modules.templates.searchPage = {
    /* Element selectors */
    templateSelector: 'body.page-template-search-page',
    mapSelector: '#mapContainer',
    mapPropContainer: document.querySelector('#mapPropertiesContainer'),
    propertySelector: '',
    mapObj: null,
    properties: [],
    markers: [],
    propMarkers: {},
    bounds: null,
    openMarkers: [],
    icon: {
        path: "M-0.5-41C-7.9-41-14-34.9-14-27.5c0,3,1.9,7.9,5.9,15c2.8,5,5.5,9.2,5.6,9.3l2,3l2-3c0.1-0.2,2.9-4.3,5.6-9.3"+
        "c3.9-7.1,5.9-12,5.9-15C13-34.9,7-41-0.5-41z M-0.5-20.6c-3.9,0-7-3.1-7-7s3.1-7,7-7s7,3.1,7,7S3.4-20.6-0.5-20.6z",
        fillColor: 'green',
        fillOpacity: 1,
        strokeColor: 'rgba(0,0,0,.25)',
        strokeWeight: 1
    },

    /* Methods */
    cond: function(){
        return document.querySelectorAll(this.templateSelector).length > 0;
    },
    init: function(){
        this.doMapView();
        this.mapResetEvents();
    },
    updateCounters: function(current, total){
        $('.ppty-count-current').text(current);
        $('.ppty-count-total').text(total);
    },
    initMap: function(latitude, longitude){
        this.mapObj = new google.maps.Map(document.querySelector(this.mapSelector), {
            center: {lat: latitude, lng: longitude},
            zoom: 8
        });
    },
    initClusterer: function(){
        var mcOptions = {gridSize: 30, maxZoom: 15};
        var mc = new MarkerClusterer(this.mapObj, this.markers, mcOptions);
    },
    addMarker: function(prop){
        /* Create info window */
        var infoWindow = new google.maps.InfoWindow({
            content: '<div class="info-html prop-infowindow">'+
            '<a href="'+prop.ContextData.SEO.DetailURL + '" class="image" style="background-image: url(' + prop.PrimaryImage.ThumbnailURL + ')">'+
            '<div class="from secondary-fill-color">' +
            '<div class="tag">From:</div>' +
            '<div class="price">' + prop.MinRate.Value + ' ' + prop.MinRate.Currency +' / Night</div>' +
            '</div>' +
            '</a>' +
            '<div class="info">' +
            '<h5 class="title">' + prop.Headline + '</h5>' +
            + prop.Type + ', ' + prop.Location + '<br>'+
            BAPI.textdata.Beds + ' ' + prop.Bedrooms + ' / ' + BAPI.textdata.Baths + ' ' + prop.Bathrooms +
            '<i class="kd-icon-right_arrow"></i>' +
            '</div>' +
            '</div>'
        });

        /* Create marker + store info window inside for later use (also in property ele) */
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(prop.Latitude, prop.Longitude),
            map: this.mapObj,
            iw: infoWindow,
            icon: this.icon
        });

        /* Add event listeners to show info window */
        marker.addListener('click', function(marker) {
            this.openMarkers.map(function(m){m.iw.close()})
            marker.iw.open(this.mapObj, marker);
            this.openMarkers.push(marker);
        }.bind(this, marker));

        /* We store markers for later use */
        this.markers.push(marker);
        this.propMarkers[prop.AltID] = marker;

    },
    addProps: function(){
        //Render properties
        var propHTML = app.bapi.render('tmpl-propertysearch-mapview', {result: this.properties, textdata: BAPI.textdata});
        this.mapPropContainer.innerHTML = propHTML;

        //Attach event listeneers
        var props = this.mapPropContainer.querySelectorAll('[data-altid]');

        _(props).forEach(function(prop){
            var markerToggle = prop.querySelector('.viewInMap');
            var altid = prop.dataset.altid;
            markerToggle.addEventListener('click', function(e){
                var marker = this.propMarkers[altid];
                /* first, we close any open marker InfoWindows */
                this.openMarkers.map(function(m){m.iw.close()})
                /* then we can open the new marker InfoWindow */
                console.log(altid, marker);
                this.propMarkers[altid].iw.open(this.mapObj, marker);

                /* we store the open InfoWindows to keep track */
                this.openMarkers.push(marker);
            }.bind(this, prop));
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

    doMapView: function(){
        var chunkSize = 20;

        app.bapi.search('property', function(sr){
            var ids = sr.result, total = sr.result.length;

            /* Here we have ppty total amount */
            this.updateCounters(0, total);

            //Split property id's into page-sized chunks
            var chunks = _.chunk(ids, chunkSize);

            chunks.forEach(function(chunk, chunk_i){

                app.bapi.get('property', chunk, function(gr){

                    //Store recovered properties
                    this.properties = _.concat(this.properties, gr.result);

                    gr.result.forEach(function(prop, prop_i){

                        /* First ppty from first chunk iteration.
                         We can initialize map & center on first markers. */
                        if(chunk_i == 0 && prop_i == 0){
                            this.initMap(prop.Latitude, prop.Longitude);
                        }

                        this.addMarker(prop);
                        this.updateCounters(this.markers.length, total);

                        //Last marker iteration
                        if(this.markers.length == total){
                            //this.initClusterer();
                            this.centerMap();
                            this.addProps();
                        }

                    }.bind(this));

                }.bind(this), {pagesize: chunkSize, seo: true});

            }.bind(this));

        }.bind(this));
    },
    doListView: function(){
        app.bapi.get('property', function(r){
            console.log(r);
            var html = app.bapi.render('tmpl-propertysearch-listview', _.assign(r, {textdata: BAPI.textdata}));
            document.querySelector('.propContainer').innerHTML = html;
        }, {seo: true, pagesize: 20})
    }
};