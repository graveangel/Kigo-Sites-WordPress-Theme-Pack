/*******************************************************************************
 *  Market areas Vars and functions
 *******************************************************************************/
/**
 * Adds slashes
 * @param  {string} string the string to escape
 * @return {string}        the scaped string
 */
function addslashes(string) {
    return string.replace(/\\/g, '\\\\').
    replace(/\u0008/g, '\\b').
    replace(/\t/g, '\\t').
    replace(/\n/g, '\\n').
    replace(/\f/g, '\\f').
    replace(/\r/g, '\\r').
    replace(/'/g, '\\\'').
    replace(/"/g, '\\"');
}


/**
 * decode html text from html entity
 * @param  {string} str the encoded string
 * @return {string}     the decoded string
 */
function decodeHtmlEntity(str) {
    return str.replace(/&#(\d+);/g, function(match, dec) {
        return String.fromCharCode(dec);
    });
};

/**
 *  encode(decode) html text into html entity
 * @param  {string} str the string to encode
 * @return {string}     the encoded string
 */
function encodeHtmlEntity(str) {
    var buf = [];

    if(typeof str === 'undefined') return '';

    for (var i = str.length - 1; i >= 0; i--) {
        buf.unshift(['&#', str[i].charCodeAt(), ';'].join(''));
    }
    return buf.join('');
};

/**
 * Function to build the tree of the saved items
 * @param  {object} $container The jquery object of the container
 * @return {array}            returns tree array of objects
 */
function getItemsIn($container) {
    var data = [];
    $.each($container.find('> *'), function(i, v) {
        var item;


        //if it is a property
        if (typeof $(v).attr('data-name') === 'undefined') {
            // var uri_prop = JSON.parse(decodeURIComponent($(v).attr('data-prop')));
            var uri_prop = {id: $(v).attr('data-prop-id')};
            item = uri_prop;

        }


        //If it is location
        else {
            var uri_name = $(v).attr('data-name');
            var uri_original_name = $(v).attr('data-original-name');
            item = {};
            item.originalName = uri_original_name ? uri_original_name : uri_name;
            item.name = uri_name;
            item.contents = getItemsIn($(v).find('> ol'));
        }

        data.push(item);
    });
    return data;
}

/**
 * The tree object
 * @type {Array}
 */
var updated = [];
/**
 * function that updates the value of the hidden item
 * @return {void} it does not return anything
 */
function update_ma_json() {
    var $tree = $('.user-list .to-save'); //The ol list of the built tree
    updated = getItemsIn($tree);
    $(hidden_selector).val(JSON.stringify(updated)); //Encoded to json to
}


/**
 * builds the tree parts
 * @param  {array} root the root object_array of the items from which to build a tree branch
 * @return {string}      returns an html string
 */
function setup_tree(root) {
    var content = '';
    for (var li in root) {
        //If the item is a property
        if (root[li].id) {
            var data_prop = encodeURIComponent(JSON.stringify(root[li])); //!!!
            var property_name = $('a[data-prop-id="'+root[li].id+'"]').html();
            var property_id = root[li].id;
            content += '<li class="prop-item active"  data-prop-id="' + property_id + '"><input type="checkbox"><a class="button button-property"  data-prop-id="' + property_id + '">' + decodeURIComponent(property_name) + '</a></li>';
        }
        // if it's a location
        else if (root[li].originalName) {
            var data_original_name = root[li].originalName;
            var data_name = root[li].name;
            var type = root[li].originalName.split('::')[1].trim();
            var innerListItems = setup_tree(root[li].contents);
            content += '<li class="ma-item active" data-name=\'' + data_name + '\' data-type="' + type + '" data-original-name="' + data_original_name + '"><a data-name=\'' + data_name + '\' data-original-name="' + data_original_name + '" class="button button-marketarea ' + type + '">' + data_name + '</a><ol class="ma-list">' + innerListItems + '</ol></li>';
        }
    }

    return content;
}



/**
 * Clears the tree and cleans the input value
 * @param  {object} e The click event
 * @return {void}   This function does not return a value
 */
function clear_areas(e) {
    if (e)
        e.preventDefault();
    //To clear the tree I need to put locations and properties to their list
    //Remove properties
    var properties = $('.user-list .to-save li.prop-item');
    $.each(properties, function(i, v) {
        //Append properties
        $('.prop-list-parent').append($(v));
    });

    // Remove locations
    var locations = $('.user-list .to-save li.ma-item');
    $.each(locations, function(i, v) {
        //append location
        $('.ma-list-parent').append($(v));
    });

    remove_active_container();
    enable_quicksearches();


    update_ma_json();
}

/**
 * Resets the tree to the saved value and updates the input value
 * @param  {object} e The click event
 * @return {void}   This function does not return a value
 */
function reset_areas(e) {
    if (e)
        e.preventDefault();

    clear_areas(e); //clear the field

    //Reset

    var the_tree = setup_tree(field_value);

    $('.user-list .to-save').append(the_tree); // Build the tree
    //Need to remove items from other lists

    // Remove locations
    var locations = $('.user-list .to-save li.ma-item');
    $.each(locations, function(i, v) {
        var data_original_name = $(v).attr('data-original-name');
        //remove from locations
        $('.ma-list-parent li.ma-item[data-original-name="' + data_original_name + '"]').remove();
    });

    //Remove properties
    var properties = $('.user-list .to-save li.prop-item');
    $.each(properties, function(i, v) {
        var data_prop_id = $(v).attr('data-prop-id');
        //remove from locations
        $(v).addClass('old-value');
        $('.prop-list-parent li.prop-item[data-prop-id="' + data_prop_id + '"]').insertAfter($(v)); //put in tree
        $(v).remove(); //remove old: This is necessary so the Rates can be updated when saving the market area.
    });

    update_ma_json();
}

function enable_quicksearches() {
    /**
     * Quicksearch:
     * Search/filter properties while typing on the field
     */
    var locSearchString = ".ma-list-parent li";
    $('#search-locations').quicksearch(locSearchString);
    $('#search-locations-hidden').quicksearch(locSearchString);

    var propSearchString = ".prop-list-parent li";
    $('#search-properties').quicksearch(propSearchString);

    $('#search-locations,#search-properties').on('search', function(e) {
        $(this).keyup();
    });
}

function remove_active_container() {
    $('.active-container').removeClass('active-container');
}
/**
 * RequestAnimationFrame
 */
(function() {
    var lastTime = 0;
    var vendors = ['ms', 'moz', 'webkit', 'o'];
    for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x] + 'RequestAnimationFrame'];
        window.cancelAnimationFrame = window[vendors[x] + 'CancelAnimationFrame'] ||
            window[vendors[x] + 'CancelRequestAnimationFrame'];
    }

    if (!window.requestAnimationFrame)
        window.requestAnimationFrame = function(callback, element) {
            var currTime = new Date().getTime();
            var timeToCall = Math.max(0, 16 - (currTime - lastTime));
            var id = window.setTimeout(function() {
                    callback(currTime + timeToCall);
                },
                timeToCall);
            lastTime = currTime + timeToCall;
            return id;
        };

    if (!window.cancelAnimationFrame)
        window.cancelAnimationFrame = function(id) {
            clearTimeout(id);
        };
}());
var tree_watched = false;
/**
 * Count how many times appear in array
 * @param  {array} array the array to validate
 * @param  {mixed} what  the value to check (scalar)
 * @return {number}       the number of times the element appears in the array
 */
function countInArray(array, subject) {
    var count = 0;
    for (var i = 0; i < array.length; i++) {
        if (array[i].trim().toLowerCase() === subject.trim().toLowerCase()) {
            count++;
        }
    }
    return count;
}
/**
 * returns an array of the location names
 * @param  {array} treebranch the array to analyze
 * @return {array}            an array of the names found
 */
function tree_get_location_names(treebranch) {
    var names = new Array();
    for (var i in treebranch) {
        if (typeof treebranch[i].name !== 'undefined') {
            names.push(decodeHtmlEntity(treebranch[i].name).trim().replace(/\s\s+/g, ' ')); //All in lowercase to compare
            var subnames = tree_get_location_names(treebranch[i].contents);
            names = names.concat(subnames);
        }
    }
    return names;
}

function tree_set_alert($dom_object, alert_type) {
    switch (alert_type) {
        case 'name-repeated':
            var tttitle = 'The name of this location appears more than one time in the tree. There cannot be more than one market areas with the same name. FIX THIS BEFORE SAVING! otherwise you will not be allowed to.';

            try {
                $.each($dom_object, function(i,v)
                {
                    $(v).attr('data-animation', true)
                        .attr('data-toggle', 'tooltip')
                        .attr('data-placement', 'top')
                        .attr('data-original-title', tttitle)
                        .addClass('name-repeated');
                });

                console.log(selector);
            } catch (e) {
                //console.log(e);
            }




            $('[data-toggle="tooltip"]').tooltip();
            break;
    }
}

function tree_remove_alerts() {
    $('.name-repeated').removeClass('name-repeated'); //
    $('[data-toggle="tooltip"]').removeAttr('data-animation')
        .removeAttr('data-toggle')
        .removeAttr('data-placement')
        .removeAttr('data-original-title')
        .removeAttr('title');
}
function check_saved_settings()
{
    var saved_setts = saved_settings();

    for(var i in saved_setts)
    {
        $setting_button = $('[data-subarea="'+i+'"]');
        var settings_count = 0;

        if(saved_setts[i].name!='')
        settings_count++;

        if(saved_setts[i].images!='[]')
        settings_count++;

        if(saved_setts[i].template!='')
        settings_count++;

        if(saved_setts[i].content!='')
        settings_count++;

        if($setting_button.length)
        {

                switch(settings_count)
                {
                    case 4:
                    $setting_button.removeClass('settings-partial');
                    $setting_button.addClass('settings-filled');
                    break;

                    case 0:
                    $setting_button.removeClass('settings-partial');
                    $setting_button.removeClass('settings-filled');
                    break;

                    default:
                    $setting_button.removeClass('settings-filled');
                    $setting_button.addClass('settings-partial');
                    break;
                }

        }
    }
}
/**
 * validates the tree if the generate landing checkbox is checked
 * @return {boolean}     true id ok false if it's not
 */
function tree_validate() {

    if (tree_watched) {
        //Need to take the value of the stored json and look for invalid data.
        //Repeated names
        var names = tree_get_location_names(updated);

        var repeated = new Array();


        var isvalid = true;
        for (var i in names) {
            var coincidences = countInArray(names, names[i]);
            if (coincidences > 1) {
                var name = names[i];
                //SetAlert
                var selector = 'a[data-name="' + name + '"]';
                var selectorEnc = 'a[data-name="' + encodeHtmlEntity(name) + '"]';

                $obj = $(selector);
                if(!$obj.length)
                    $obj = $(selectorEnc);

                tree_set_alert($obj, 'name-repeated');
                isvalid = false;
            }
        }
        return isvalid;
    }

    return true;
}
/**
 * Starts requestAnimationFrame to watch the tree
 * @return {void} nothing is returned
 */
function watch_tree() {
    if (tree_watched) {
        var isvalid = tree_validate();
        if (isvalid) {
            //remove alerts
            tree_remove_alerts();
        }

        requestAnimationFrame(function() {
            watch_tree();
        });

    } else {
        tree_remove_alerts();
    }
}

function remove_all_edit_name()
{
    // Remove all previous inputs
    $.each($('.market-areas-tree.to-save .button-marketarea'), function(i,v)
    {
        $(this).html($(this).attr('data-name'));
    });

}

function modal(params)
{
    if(params.title)
        $('.modal-title').html(params.title);
    else
        $('.modal-title').html('');

    if(params.body)
        $('.modal-body').html(params.body);
    else
        $('.modal-body').html('');

    if(params.footer)
        $('.modal-footer').html(params.footer);
    else
        $('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

    $('#page-modal').modal().on('hidden.bs.modal', function (e) {
          $('.modal-body').html('');
        });
}

function get_subarea_form(e)
{

    var form_html = '';
    //the template select
    form_html += '<h3>Select Template</h3><select class="subarea-template">' + $('select#market_area_use_landing_page_select').html() + '</select>'; //options

    //the pictures select
    form_html += '<h3>Select pictures</h3><p>The first image will be set as Featured.</p><ul class="subarea-photo-list"></ul><input class="widefat subarea-photos" type="hidden" value="" /><a data-hidden=".subarea-photos" class="ma_upload_image_button button button-primary subarea-photo-pick">Pick images</a>';

    //The content box
    form_html += '<h3>Content:</h3><textarea class="subarea-content"></textarea>';

    return form_html;
}

function saved_settings()
{
    var input_areas_val = $('input#subareas-conf').val();
        input_areas_val = (input_areas_val=='') ? '{}' : input_areas_val;
    return JSON.parse(input_areas_val);
}



function modal_form(subarea_name)
{
    var params =
    {
            title: 'Configure Sub Area: ' + subarea_name,
            body: get_subarea_form(),
            footer: '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary button-ok">OK</button>'
    };
    modal(params);

    /* Enable TinyMCE */
    var editor = null;
    try {
         tinyMCE.init({
          selector : ".subarea-content",
          setup : function(ed) {
                      editor = ed;
                      ed.onChange.add(function(ed, e) {
                          $('.subarea-content').val(ed.getContent());
                      });

                      ed.onKeyUp.add(function(ed, e) {
                          $('.subarea-content').val(ed.getContent());
                      });

                  }
       });
    } catch (e) {
        $('.subarea-content').css('visibility','visible');
    }

    /* Pictures Preview*/
    $('.subarea-photos').on('change', function(e)
    {
        update_subarea_image_box();
    });

    /* sort pics */
    $('.subarea-photo-list').sortable(
        {
            placeholder: '<li class="img-box" height="100" width="100"><img class="placeholder-image" alt="Put Here" /></li>',
            onDrop: function ($item, container, _super, event) {
                //Default
                  $item.removeClass(container.group.options.draggedClass).removeAttr("style")
                  $("body").removeClass(container.group.options.bodyClass);

                  //Do
                  //update hidden val
                  var images = $('.subarea-photo-list img');
                  var urls = new Array();

                  $.each(images, function(i,v)
                  {
                      urls.push($(this).attr('src'));
                  });

                  $('.subarea-photos').val(JSON.stringify(urls));
                }
        });

    /* ok action */
    $('.button-ok').on('click', function(e)
    {
        // sub area name
        var sbname = subarea_name;
        // console.log(sbname);

        // chosen template
        var template = $('.modal-body .subarea-template').val();
        // console.log(template);

        // images
        var images_ = $('.modal-body .subarea-photos').val() == '' ? '[]': $('.modal-body .subarea-photos').val();
        // console.log(images_);

        // content
        var content = $('.modal-body .subarea-content').val();
        // console.log(content);


        var subarea_config =
        {
            name: subarea_name,
            template: template,
            images: images_,
            content: content
        };

        //subareas configs
        var sub_ar_conf_val = $('#subareas-conf').val() == '' ? '{}' : $('#subareas-conf').val();
        //Add to subarea
        var subareas_conf = JSON.parse(sub_ar_conf_val);
        //adding the value
        subareas_conf[subarea_name] = subarea_config;
        //updating the field
        $('input#subareas-conf').val(JSON.stringify(subareas_conf));

        // Modal close
        $('#page-modal').modal('hide');

        // Check the saved elements and add an indicator
        check_saved_settings()
    });



    var sub_ar_conf_val = $('#subareas-conf').val() == '' ? '{}' : $('#subareas-conf').val();
    var subareas_conf = JSON.parse(sub_ar_conf_val);
    /* Set pictures if any */
    var photo_array = subareas_conf[subarea_name] ? subareas_conf[subarea_name].images : '[]';
    //photos hidden
    $('.subarea-photos').val(photo_array);
    //update preview
    console.log(photo_array);
    update_subarea_image_box(photo_array);
    /* Set template */
    var template = subareas_conf[subarea_name] ? subareas_conf[subarea_name].template : '';
    $('.modal-body .subarea-template').val(template);
    /* Set content */
    var content = subareas_conf[subarea_name] ? subareas_conf[subarea_name].content : '';
    try {
        $('.modal-body .subarea-content').val(content); //textarea
        editor.setContent(content); //editor
    } catch (e) {
        //pass
    }

}

function add_subarea_form_fill_buttons()
{
    if(tree_watched)
    {
        //after each button-marketarea
        $.each($('.to-save .button-marketarea'), function(i,v)
        {
            var subarea_name = $(this).attr('data-name');
            $('<a class="button button-primary subarea-config" data-subarea="'+subarea_name+'"><i class="fa fa-wrench" aria-hidden="true"></i> Configure</a>').insertAfter($(this));
        });
    }
}

function modal_error_repeated_names()
{
    modal(
        {
            title: '<i class="fa fa-exclamation-triangle fa-3x" aria-hidden="true"></i>',
            body:'<h1>Error:</h1><p class="description">The Market areas tree has repeated names. <br /> Please fix this in order to save and create the landing pages.</p>',
            footer: '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>'
        });
}

function update_subarea_image_box(photo_array_json)
{
    //put pictures in photo-list
    var url_array = [];
    if(photo_array_json)
        url_array = JSON.parse(photo_array_json);
    else
        try {
            url_array = JSON.parse($('.subarea-photos').val());
        } catch (e) {
            //
        }
    //clear photo list
    $('.subarea-photo-list').html('');

    for(e in url_array)
    {
        var img = new Image();
            img.src = url_array[e];
            img.height = 100;
        var box = document.createElement('li');
            box.className = "img-box";
            box.appendChild(img);
            $('.subarea-photo-list').append(box);
    }
}


function update_setting_name(old_value, new_value)
{
    var settings = saved_settings();
    var old_setting = settings[old_value];
    if(old_setting)
    {
        old_setting.name = new_value;
        var new_setting = old_setting;
        settings[new_value] = new_setting;
        var new_settings = JSON.stringify(settings);
        $('input#subareas-conf').val(new_settings);
    }

}
