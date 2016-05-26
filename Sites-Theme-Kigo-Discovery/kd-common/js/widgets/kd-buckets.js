app.bapiModules.widgets.buckets = {
    selector: '.kd-buckets',
    init: function(){
        $(this.selector).each(function(index, ele){
            app.bapi.recursiveGet('searches',  function(data){
                var finalData = Object.assign(data, {class: ele.dataset.class});
                ele.innerHTML = app.bapi.render('tmpl-searches-quickview', finalData);
            });
        });
    },
    cond: function cond() {
        return app.exists(this.selector);
    }
};