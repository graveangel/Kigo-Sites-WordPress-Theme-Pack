app.bapiModules.widgets.selective_search = {
    selector: '.kd-selective-search-box',
    init: function(){

            /* Set 'More' toggle events */
            $('.kd-selective-search-box .toggle-filter').on('click', function(e)
            {
                e.preventDefault();

                $(this).toggleClass('active');

                    var types_field        = $($(this).attr('data-types'));
                    var post_types_filters = $(this).parent().parent().find('.toggle-filter');
                    var checkbox           = $(this).prev();

                    checkbox.prop('checked',!checkbox.prop('checked'));

                    var active_types= [];

                    $.each(post_types_filters, function(e,i)
                    {
                        if($(this).hasClass('active'))

                        active_types.push($(this).attr('data-toggle'))
                    });

                    types_field.get(0).value = active_types.join(',');
            });

            /* Filter by*/
            $('.filter-by').on('click', function(e)
            {
                e.preventDefault();

                //Preveinting to close when clicked
                $(this).parent().on('click', function(e)
                {
                    e.stopPropagation();
                });

                $(this).parent().next().toggleClass('active');

            });

            /* Clear filters button */
            $('.clearsearch').on('click', function(e)
            {
                e.preventDefault();
                $('.kd-selective-search-box .toggle-filter').removeClass('active');
                var types_field = $($(this).attr('data-types'));

                /* Checkboxes */
                var checkboxes = $(this).parent().parent().find('.ptype input');
                    checkboxes.prop('checked', false);

                types_field.get(0).value = '';
            });

            /* Click outside */
            $(window).on('click', function(e)
            {
                $('.currently-filtering').removeClass('active');
            });


            $('.currently-filtering').on('click', function(e)
            {
                e.stopPropagation();
            });



    },
    cond: function cond() {
        return app.exists(this.selector);
    }
};
