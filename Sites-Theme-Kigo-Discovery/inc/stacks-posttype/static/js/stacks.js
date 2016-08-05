/*******************************************************************************
 * On load
 *******************************************************************************/
$(function() {
    /**
     * Drag and drop
     */
    $('ol.sortable').sortable({
        group: 'sortable-group',
        placeholder: '<li class="placeholder">Drop Here</li>',
        isValidTarget: function($item, container) {
            var $container = $(container.el[0]);
            if ($container.is('.ma-list-parent,.prop-list-parent, .to-save, .to-save  li  ol')) {
                if ($container.hasClass('ma-list-parent') && $item.hasClass('ma-item')) //If dropping empty locations in parent list
                {
                    if ($item.find('.prop-item').length)
                        return false;
                    return true;
                }
                if ($container.hasClass('prop-list-parent') && $item.hasClass('prop-item')) //if dropping property in parent list
                    return true;
                if ($container.is('.to-save, .to-save li  ol') && $item.is('.ma-item,.prop-item')) //if dropping location or properties to save list
                    return true;
                if ($container.is('.to-save  li  ol') && $item.is('.prop-item')) // if dropping properties any level location in save list
                    return true;
            }
            return false;
        },
        onDrop: function($item, container, _super, event) {
            //Default
            $item.removeClass(container.group.options.draggedClass).removeAttr("style")
            $("body").removeClass(container.group.options.bodyClass)
            var $container = $(container.el[0]);
            if ($container.is('.to-save, .to-save > li > ol, .to-save > li > ol') && $item.is('.ma-item,.prop-item')) {
                $item.addClass('active');
                if($item.is('.ma-item') && tree_watched)
                {
                    $('.subarea-config').remove();
                    add_subarea_form_fill_buttons();
                }
            } else {
                $item.removeClass('active');
                $item.find('.subarea-config').remove();
            }
            enable_quicksearches();
            //Update value
            update_ma_json();
            //check
            check_saved_settings();
        }


    });

    /**
     * location doubleclick to edit its name
     */
    $(document).on('dblclick', '.stacks-tree.to-save .button-stackarea', function(e) {
            e.preventDefault();
                remove_all_edit_name();

            //save previous value
            $(this).attr('data-old-name', $(this).text());
            // Add the input field inside
            $(this).html('<input type="text" class="edit-name" placeholder="' + $(this).attr('data-name') + '" value="' + $(this).attr('data-name') + '"/>')
                //focus on field
                .find('.edit-name').focus().select();

        })
        //Keyup for edit-name;
        .on('keydown', '.stacks-tree.to-save .button-stackarea .edit-name', function(e) {

            if (e.which == 13) {
                e.preventDefault();
                e.stopPropagation();


                if ($(this).val() !== '') //if it is not an empty string
                {
                    //  var val = encodeHtmlEntity($(this).val().trim().replace(/\s\s+/g, ' '));
                    var val = $(this).val().trim().replace(/\s\s+/g, ' ');

                    // //update saved setting
                     update_setting_name($(this).parent().attr('data-old-name'),val);

                    $(this).parent().attr('data-name', val); //Button name value
                    $(this).parent().parent().attr('data-name', val); //list name value
                    $(this).parent().html(val); // remove the input text

                    //update input
                    update_ma_json();

                    //remove alerts to let the validation loop set them again.
                    tree_remove_alerts();

                    //update subarea config button if any
                    $('.subarea-config').remove();

                    add_subarea_form_fill_buttons();
                    check_saved_settings();
                }
            }

            if (e.which == 27) {
                $(this).parent().html($(this).parent().attr('data-name')); // remove the input text
            }

        })
        //click outside
        .on('click', function(e)
        {
                remove_all_edit_name();//Click outside means cancel
        });


    //focus on click
    $('#search-locations, #search-properties').on('click', function(e) {
        $(this).focus().select();
    });





    /**
     * Click and search: Click event for the location button to
     * trigger a search on the properties.
     */
    $(document).on('click', '.button-stackarea', function(e) {
        e.preventDefault();
        e.stopPropagation();
        //Remove edit name
        remove_all_edit_name();
        //Add class active-container
        if ($(this).is('.to-save a')) {
            remove_active_container();
            $(this).parent().find('>ol').addClass('active-container');
            $(this).addClass('active-container');
        }

        var text = $(this).attr('data-original-name').split('::')[0].trim();
        // console.log(text);
        $('#search-properties').val(text).keyup();
    });


    /**
     * Use main tree as active container
     */
    $('.stacks-tree').click(function(e) {
        remove_active_container();
    });


    /**
     * Add selected
     */
    $('.add-selected').on('click', function(e) {
        $('.select-visible').attr('checked', false);
        //Agregar los seleccionados a ol.active-container
        //selected
        var $selected = $('.prop-list-parent li input[type="checkbox"]:checked');
        if ($('ol.active-container').length)
            $.each($selected, function(i, v) {
                $(this).parent().appendTo('ol.active-container');
                $(this).attr('checked', false);
            });
        else
            $.each($selected, function(i, v) {
                $(this).parent().appendTo('ol.to-save');
                $(this).attr('checked', false);
            });

        //update input
        update_ma_json();
    });

    /**
     * Generate landings
     */
    $('.generate-landings').on('click', function(e) {
        var checked = $(this).attr('checked') ? true : false;

        switch (checked) {
            case true:
                tree_watched = true;
                $('.subarea-config').remove();
                add_subarea_form_fill_buttons();
                check_saved_settings();
                watch_tree();
                break;
            default:
                $('.subarea-config').remove();
                tree_watched = false;
        }

        $('input#stack_area_generate_landing').attr('checked', checked);
    });

    /**
     * tooltips
     */
    $('[data-toggle="tooltip"]').tooltip();

    /**
     * Hide tooltip when hover off
     */
    $('.generate-landings-label').hover(null, function() {
        $(this).find('input').blur();
    });

    /* Enable quick search bars */
    enable_quicksearches();

    /**
     * Checkbox
     */
    $('.button-property').on('click', function(e) {
        $(this).prev().click();
    });

    /**
     * Select visible
     */
    $('.select-visible').on('change', function(e) {
        var checked = $(this).attr('checked') ? true : false;
        $('.prop-list-parent li:visible input[type="checkbox"]').attr('checked', checked);
    });


    /**
     * Location select filter:
     */
    $('.search-locations-select').on('change', function(e) {
        if ($(this).val() !== '') {
            $('.ma-list-parent .ma-item').hide();
            $('.ma-list-parent [data-type="' + $(this).val() + '"]').show();
        } else {
            $('.ma-list-parent .ma-item').show();
        }
    });

    /**
     * Publish post validate before sending
     */
    $('[name="save"]').on('click', function(e)
    {
        e.preventDefault();
        if(tree_validate())
        {
            $('#post').submit();
        }
        else
        {
            //modal alert
            modal_error_repeated_names()

        }
    });
    
    /**
     * Add new node button
     */
    $('.new-stack-node').on('click', function(e)
    {
        e.preventDefault();
        add_stack_node();
    });

    /**
     * Subarea Config
     */
    $(document).on('click','.subarea-config',function(e)
    {
        if(!tree_validate())
        {
            modal_error_repeated_names();
        }
        else
            modal_form($(this).attr('data-subarea'));
    });

    /**
     * clear areas tree
     */
    $('.clear-areas').on('click', clear_areas);

    /**
     * Reset areas
     */
    $('.reset-areas').on('click', reset_areas);

    reset_areas();

});
