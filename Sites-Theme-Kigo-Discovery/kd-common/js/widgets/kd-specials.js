app.bapiModules.widgets.specials = {
    selector: '.kd-specials',
    init: function(){
        $(this.selector).each(function(index, ele){
            app.bapiRender('specials', 'tmpl-specials-quickview', function(html){
                ele.innerHTML = html;
            }, {class: ele.dataset.class});
        });
    },
    cond: function cond() {
        return app.exists(this.selector);
    }
};