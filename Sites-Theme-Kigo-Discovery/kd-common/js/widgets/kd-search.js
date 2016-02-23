app.bapiModules.widgets.search = {
    selector: '.kd-search',
    init: function(){
        $(this.selector).each(function(index, ele){
            /* Set 'More' toggle events */
            ele.querySelector('.search-button-block .more').addEventListener('click', function(e){
                ele.classList.toggle('open');
                this.classList.toggle('active');
            });
        });
    },
    cond: function cond() {
        return app.exists(this.selector);
    }
};