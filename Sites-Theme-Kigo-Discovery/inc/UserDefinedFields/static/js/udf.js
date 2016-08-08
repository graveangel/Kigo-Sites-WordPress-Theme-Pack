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
            var udf_types = ['Checkbox','Single Line','Multiple line', 'Single Choice', 'Multiple Choice']; //0,1,2,3
            var udf_type_options = '';
            for(var i in udf_types)
            {
                udf_type_options+='<option value="'+i+'">'+udf_types[i]+'</option>';
            }
            
            modal(
                    {
                        title: '<h3><i class="dashicons-before dashicons-admin-multisite" aria-hidden="true"></i> Add New User Defined Field:</h3>',
                        body: '<p class="description">Write the name of the user defined field. If the UDF is empty or only has blank spaces, it will not be created.\n\
                                <h3>UDF Name <small>(Required) </small>: <input type="text" class="udf-name widefat"></h3></p>\n\
                                <h3>Description: </h3><textarea name="description" id="" class="widefat udf-description"></textarea>\n\
                                <h3>(Required) Select UDF type: <select class="udf-type widefat">'+udf_type_options+'</select></h3>',
                        footer: '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" class="btn btn-primary btn-ok-udf">Ok</button>'
                    });

            $('.btn-ok-udf').on('click', function (e)
            {
                e.preventDefault();

                // container to append them to
                var $container = $('.udf-content');

                //get the value of the field
                var udf_name = $('.udf-name').val().trim();
                
                 //get the type of the field
                var udf_type = $('.udf-type').val();
                
                var options = false;
                
                if(udf_type == 3 || udf_type == 4)
                {
                    options = $('.udf-options').val().split(';');
                    for(var ind in options)
                    {
                        options[ind] = options[ind].trim();
                        if(options[ind] == '')
                        {
                            options.splice(ind,1);
                        }
                    }
                }
                
                //get the description of the field
                var udf_description = $('.udf-description').val();

                // If the value is empty don't do anything
                if (udf_name != '')
                {
                    var udf_name_sanitized = sanitize(udf_name);
                    var udf =
                            {
                                name: udf_name,
                                slug: udf_name_sanitized,
                                type: udf_type,
                                description: udf_description,
                                options: options
                            };

                    UDFS[udf.slug] = udf;
                    add_new_udf_to($container, udf);
                }

                modal_close();

            });

        });
        
        $(document).on('change','.udf-type',function(e)
        {
            if(($(this).val() == 3 || $(this).val() == 4) )
            {
                if($('.udf-options').length  == 0)
                {
                    $('.udf-options, .udf-options-lbl').remove();
                    $('<h3 class="udf-options-lbl">UDF options:</h3><p class="udf-options-lbl">Enter the difrerent option values separated by ;</p><input class="udf-options widefat" type="text">').insertAfter($(this));
                }
            }
            else
            {
                $('.udf-options, .udf-options-lbl').remove();
            }
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