function add_new_udf_to($container, udf)
{
    $container.append('<label><span class="udf-item"><input type="checkbox" class="delete-udf" data-delete="' + udf.slug + '"><strong>' + udf.name + '</strong></span></label>');
}
function remove_udf(udf_slug)
{
    return delete UDFS[udf_slug];
}
function print_udfs()
{
    var $container = $('.udf-content');
    //clear container
    $container.html('');

    for (var i in UDFS)
    {
        add_new_udf_to($container, UDFS[i]);
    }
}

function remove_selected()
{
    $to_temove = $('.delete-udf:checked');
    $.each($to_temove, function (i, v)
    {
        var udf_slug = $(v).attr('data-delete');
        remove_udf(udf_slug);
    });
    print_udfs();

}

(function ($)
{

    $(document).ready(function ()
    {
        //Create new user defined field
        $('.udf-create').on('click', function (e)
        {
            e.preventDefault();

            modal(
                    {
                        title: '<h3><i class="dashicons-before dashicons-admin-multisite" aria-hidden="true"></i> Add New User Defined Field:</h3>',
                        body: '<p class="description">Write the name of the user defined field. If the UDF is empty or only has blank spaces, it will not be created.\n\
                   <h3>UDF: <input type="text" class="udf-name"></h3></p>',
                        footer: '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" class="btn btn-primary btn-ok-udf">Ok</button>'
                    });

            $('.btn-ok-udf').on('click', function (e)
            {
                e.preventDefault();

                // container to append them to
                var $container = $('.udf-content');

                //get the value of the field
                var udf_name = $('.udf-name').val().trim();

                // If the value is empty don't do anything
                if (udf_name != '')
                {
                    var udf_name_sanitized = sanitize(udf_name);
                    var udf =
                            {
                                name: udf_name,
                                slug: udf_name_sanitized
                            };

                    UDFS[udf.slug] = udf;
                    add_new_udf_to($container, udf);
                }

                modal_close();

            });

        });

        $('.udf-delete').on('click', function (e)
        {
            e.preventDefault();
            remove_selected();
        });

        $('.udfs-save').on('click', function (e)
        {
            e.preventDefault();
            // Json encode all UFS
            console.log(UDFS);
            var json_udfs = JSON.stringify(UDFS);
            var $input = $('#udfs');
            $input.val(json_udfs);
            $('.form-udfs').submit();

        });

        print_udfs();
    });
}(jQuery));