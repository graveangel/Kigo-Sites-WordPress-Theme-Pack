/**
 * Functions
 */
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


 // encode(decode) html text into html entity
function decodeHtmlEntity(str) {
  return str.replace(/&#(\d+);/g, function(match, dec) {
    return String.fromCharCode(dec);
  });
};

function encodeHtmlEntity(str) {
  var buf = [];
  for (var i=str.length-1;i>=0;i--) {
    buf.unshift(['&#', str[i].charCodeAt(), ';'].join(''));
  }
  return buf.join('');
};


 /**
  * Function to build the tree of the saved items
  * @param  {object} $container The jquery object of the container
  * @return {array}            returns tree array of objects
  */
 function getItemsIn($container)
 {
   var data = [];
   $.each($container.find('> *'), function(i,v)
   {
     var item;


     //if it is a property
     if(typeof $(v).attr('data-name') === 'undefined')
     {
       var uri_prop = JSON.parse(decodeURIComponent($(v).attr('data-prop')));
       item = uri_prop;
     }


     //If it is location
     else
     {
       var uri_name = $(v).attr('data-name');
       var uri_original_name = $(v).attr('data-original-name');
       item =  {};
       item.originalName = uri_original_name ? uri_original_name : uri_name;
       item.name = uri_name;
       item.contents = getItemsIn($(v).find('> ol'));
     }

   data.push(item);


   });

   return data;
 }

 /**
  * function that updates the value of the hidden item
  */
 function update_ma_json()
 {
   var updated = '';
   var $tree = $('.user-list .to-save'); //The ol list of the built tree
   updated  = getItemsIn($tree);
   console.log(updated);
   $(hidden_selector).val(JSON.stringify(updated)); //Encoded to json to
 }


 /**
  * builds the tree parts
  * @param  {array} root the root object_array of the items from which to build a tree branch
  * @return {string}      returns an html string
  */
 function setup_tree(root)
 {
   var content = '';
   for(var li in root)
   {
     //If the item is a property
     if(root[li].BookingURL)
     {
        var data_prop     =  encodeURIComponent(JSON.stringify(root[li]));//!!!
        var property_name = root[li].Name;
        var property_id   = root[li].id;
        content           += '<li class="prop-item active" data-prop="'+data_prop+'" data-prop-id="'+property_id+'"><input type="checkbox"><a class="button button-property" data-prop="'+data_prop+'" data-prop-id="'+property_id+'">'+decodeURIComponent(property_name)+'</a></li>';
     }
     // if it's a location
     else if(root[li].originalName){
       var data_original_name    = root[li].originalName;
       var data_name             = root[li].name;
       var type                  = root[li].originalName.split('::')[1].trim();
       var innerListItems        = setup_tree(root[li].contents);
       content                   += '<li class="ma-item active" data-name=\''+data_name+'\' data-type="'+type+'" data-original-name="'+data_original_name+'"><a data-name=\''+data_name+'\' data-original-name="'+data_original_name+'" class="button button-marketarea '+type+'">'+data_name+'</a><ol class="ma-list">'+innerListItems+'</ol></li>';
     }
   }

   return content;
 }



 /**
  * Clears the tree and cleans the input value
  * @param  {object} e The click event
  * @return {void}   This function does not return a value
  */
 function clear_areas(e)
 {
   if(e)
   e.preventDefault();
   //To clear the tree I need to put locations and properties to their list
   //Remove properties
   var properties = $('.user-list .to-save li.prop-item');
   $.each(properties, function(i,v)
   {
      //Append properties
       $('.prop-list-parent').append($(v));
   });

   // Remove locations
   var locations = $('.user-list .to-save li.ma-item');
   $.each(locations, function(i,v)
   {
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
 function reset_areas(e)
 {
   if(e)
   e.preventDefault();

   clear_areas(e); //clear the field

   //Reset

   var the_tree = setup_tree(field_value);
    $('.user-list .to-save').append(the_tree);// Build the tree
   //Need to remove items from other lists

   // Remove locations
   var locations = $('.user-list .to-save li.ma-item');
   $.each(locations, function(i,v)
   {
       var data_original_name = $(v).attr('data-original-name');
       //remove from locations
       $('.ma-list-parent li.ma-item[data-original-name="'+data_original_name+'"]').remove();
   });

   //Remove properties
   var properties = $('.user-list .to-save li.prop-item');
   $.each(properties, function(i,v)
   {
       var data_prop_id = $(v).attr('data-prop-id');
       //remove from locations
       $('.to-save li.prop-item[data-prop-id="'+data_prop_id+'"]').addClass('old-value');
       $('.prop-list-parent li.prop-item[data-prop-id="'+data_prop_id+'"]').insertAfter($('.to-save li.prop-item[data-prop-id="'+data_prop_id+'"]')); //put in tree
       $('.to-save li.prop-item.old-value[data-prop-id="'+data_prop_id+'"]').remove(); //remove old: This is necessary so the Rates can be updated when saving the market area.
   });

    update_ma_json();
 }

 function enable_quicksearches()
 {
   /**
    * Quicksearch:
    * Search/filter properties while typing on the field
    */
   var locSearchString = ".ma-list-parent li";
     $('#search-locations').quicksearch(locSearchString);
   $('#search-locations-hidden').quicksearch(locSearchString);

   var propSearchString = ".prop-list-parent li";
   $('#search-properties').quicksearch(propSearchString);

   $('#search-locations,#search-properties').on('search',function(e)
   {
     $(this).keyup();
   });
 }

function remove_active_container()
{
    $('.active-container').removeClass('active-container');
}

/**
 * On load
 */
$(function()
{
  /**
   * Drag and drop
   */
  $('ol.sortable').sortable({
    group: 'sortable-group',
    placeholder: 	'<li class="placeholder">Drop Here</li>',
    isValidTarget: function  ($item, container) {
      var $container = $(container.el[0]);
       if ($container.is('.ma-list-parent,.prop-list-parent, .to-save, .to-save > li > ol, .to-save > li > ol > li > ol,.to-save > li > ol > li > ol > li > ol, .to-save > li > ol > li > ol > li > ol > li > ol'))
       {
         if($container.hasClass('ma-list-parent') && $item.hasClass('ma-item')) //If dropping empty locations in parent list
            {
              if($item.find('.prop-item').length)
              return false;
              return true;
            }
         if($container.hasClass('prop-list-parent') && $item.hasClass('prop-item')) //if dropping property in parent list
            return true;
         if($container.is('.to-save, .to-save > li > ol, .to-save > li > ol > li > ol,.to-save > li > ol > li > ol > li > ol') && $item.is('.ma-item,.prop-item')) //if dropping location or properties to save list
            return true;
         if($container.is('.to-save > li > ol > li > ol,.to-save > li > ol > li > ol > li > ol, .to-save > li > ol > li > ol > li > ol > li > ol') && $item.is('.prop-item')) // if dropping properties to second level location in save list
            return true;
       }
       return false;
     },
     onDrop: function($item, container, _super, event)
     {
       //Default
       $item.removeClass(container.group.options.draggedClass).removeAttr("style")
       $("body").removeClass(container.group.options.bodyClass)
       var $container = $(container.el[0]);
       if($container.is('.to-save, .to-save > li > ol, .to-save > li > ol') && $item.is('.ma-item,.prop-item'))
       {
         $item.addClass('active');
       }
       else
       {
         $item.removeClass('active');
       }
       enable_quicksearches();
       //Update value
       update_ma_json();
     }


  });

  /**
   * location doubleclick to edit its name
   */
  $(document).on('dblclick','.market-areas-tree.to-save .button-marketarea',function(e)
  {
      e.preventDefault();
      // Add the input field inside
      $(this).html('<input type="text" class="edit-name" placeholder="'+$(this).attr('data-name')+'"/>');
      //focus on field
      $(this).find('.edit-name').focus();
  })
  //Keyup for edit-name;
  .on('keydown','.market-areas-tree.to-save .button-marketarea .edit-name', function(e)
  {


    if(e.which == 13) {
      e.preventDefault();
      e.stopPropagation();
      if($(this).val() !=='')//if it is not an empty string
      {
        $(this).parent().attr('data-name',encodeHtmlEntity($(this).val())); //Button name value
        $(this).parent().parent().attr('data-name',encodeHtmlEntity($(this).val())); //list name value
        $(this).parent().html($(this).parent().parent().attr('data-name')); // remove the input text
        //update input
        update_ma_json();
      }
    }

    if(e.which == 27)
    {
      $(this).parent().html($(this).parent().attr('data-name')); // remove the input text
    }

  })



  //focus on click
  $('#search-locations, #search-properties').on('click',function(e)
  {
    $(this).focus().select();
  });





  /**
   * Click and search: Click event for the location button to
   * trigger a search on the properties.
   */
  $(document).on('click','.button-marketarea', function(e)
  {
    e.preventDefault();
    e.stopPropagation();
    //Add class active-container
    if($(this).is('.to-save a'))
    {
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
  $('.market-areas-tree').click(function(e)
  {
    remove_active_container();
  });


  /**
   * Add selected
   */
  $('.add-selected').on('click', function(e)
  {
    $('.select-visible').attr('checked',false);
    //Agregar los seleccionados a ol.active-container
    //selected
    var $selected = $('.prop-list-parent li input[type="checkbox"]:checked');
    if($('ol.active-container').length)
    $.each($selected, function(i,v)
    {
      $(this).parent().appendTo('ol.active-container');
      $(this).attr('checked', false);
    });
    else
    $.each($selected, function(i,v)
    {
      $(this).parent().appendTo('ol.to-save');
      $(this).attr('checked', false);
    });

    //update input
    update_ma_json();
  });


  enable_quicksearches();

  /**
   * Checkbox
   */
  $('.button-property').on('click',function(e)
  {
    $(this).prev().click();
  });

  /**
   * Select visible
   */
  $('.select-visible').on('change', function(e)
  {
    var checked =  $(this).attr('checked') ? true : false;
    $('.prop-list-parent li:visible input[type="checkbox"]').attr('checked',checked);
  });


  /**
   * Location select filter:
   */
  $('.search-locations-select').on('change',function(e)
  {
    if($(this).val() !== '')
    {
      $('.ma-list-parent .ma-item').hide();
      $('.ma-list-parent [data-type="' + $(this).val() + '"]').show();
    }
    else
    {
        $('.ma-list-parent .ma-item').show();
    }
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
