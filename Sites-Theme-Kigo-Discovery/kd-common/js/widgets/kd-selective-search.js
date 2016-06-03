app.bapiModules.widgets.selective_search = {
    selector: '.kd-selective-search-box',
    init: function(){

            /* Set 'More' toggle events */
            $('.kd-selective-search-box .toggle-filter').on('click', function(e)
            {
                e.preventDefault();

                $(this).toggleClass('primary-stroke-color');

                    var types_field = $($(this).attr('data-types'));


                    var post_types_filters = $(this).parent().parent().find('.toggle-filter');

                    var active_types= [];

                    $.each(post_types_filters, function(e,i)
                    {
                        if($(this).hasClass('primary-stroke-color'))
                        active_types.push($(this).attr('data-toggle'))
                    });

                    types_field.get(0).value = active_types.join(',');
            });

            /* Clear filters button */
            $('.clearsearch').on('click', function(e)
            {
                e.preventDefault();
                $('.kd-selective-search-box .toggle-filter').removeClass('primary-stroke-color');
            });

    },
    cond: function cond() {
        return app.exists(this.selector);
    }
};
