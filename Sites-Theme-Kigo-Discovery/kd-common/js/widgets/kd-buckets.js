app.bapiModules.widgets.buckets = {
    selector: '.kd-buckets',
    init: function(){
        $(this.selector).each(function(index, ele){
            app.bapiRender('searches', 'tmpl-searches-quickview', function(html){
                ele.innerHTML = html;
            }, {class: ele.dataset.class});

        });
    },
    cond: function cond() {
        return app.exists(this.selector);
    }
};