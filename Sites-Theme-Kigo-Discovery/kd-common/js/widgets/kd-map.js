app.modules.widgets.map = {
    selector: '.kd-map .map-canvas',
    init: function(){
        geocoder = new google.maps.Geocoder();
        $(this.selector).each(function(index, ele){
            if (geocoder) {
                geocoder.geocode( { 'address': ele.dataset.location}, function(result, status) {
                    var location = result[0].geometry.location;
                    var map = new google.maps.Map(ele, {
                        center: location,
                        zoom: ele.dataset.zoom * 1 || 10
                    });

                    var marker = new google.maps.Marker({
                        map: map,
                        position: location
                    });
                });
            }
        });
    },
    cond: function cond() {
        return app.exists(this.selector);
    }
};