function add_udf(udf)
{   var options = '';
    switch(parseInt(udf.type))
    {
        case 0:
        $('#bapi-search .kd-search').append('<label class="udf-filter"><strong>Select '+udf.name+'</strong><input type="checkbox" style="-webkit-appearance: checkbox" class="udf-filter-checkbox"  name="udf['+udf.slug+']" data-udf="'+udf.slug+'"></label>');
        break;
        case 3:
        options += '<option value="">Select option</option>';
        case 4:
            var multiple = '';
            if(udf.type == 4) 
                multiple = 'multiple';

            
            
            for(var e in udf.options)
            {
                options += '<option value="'+udf.options[e]+'">'+udf.options[e]+'</option>'
            }
            
            $('#bapi-search .kd-search').append('<label class="udf-filter"><strong>Select '+udf.name+'</strong><select class="udf-filter-select" '+multiple+' name="udf['+udf.slug+']" data-udf="'+udf.slug+'">'+options+'</select></label>');
        break;
        
        default:
        //pass
        break;
    }

}


function add_udfs()
{
    if($('.udf-filter').length) return;
    for(var i in UDFS)
    {
        add_udf(UDFS[i]);
    }
    
    $('.udf-filter-select').on('change', function(e)
    {
        add_to_cookies('udf_search',$(this).attr('data-udf'), $(this).val());
    });
    
    $('.udf-filter-checkbox').on('change', function(e)
    {
        if($(this).attr('checked'))
        {
        add_to_cookies('udf_search',$(this).attr('data-udf'), 'on');
        }
        else
        {
        add_to_cookies('udf_search',$(this).attr('data-udf'), null);
        }
    });
}


function add_to_cookies($cookiename, $udf, $val)
{
    var udf_search = JSON.parse(decodeURIComponent($.cookie($cookiename)));
        if(!udf_search)
           {
             udf_search = {};
           }
        
        udf_search[$udf] = {udf_slug: $udf,value: $val};
        var udf_search_cookie = encodeURIComponent(JSON.stringify(udf_search));
        $.cookie($cookiename, udf_search_cookie, { expires: 7, path: '/' });
        
            
}


function clear_udf($cookiename)
{
        var udf_search = {};
        var udf_search_cookie = encodeURIComponent(JSON.stringify(udf_search));
         $.cookie($cookiename, udf_search_cookie,{ expires: 7, path: '/' });
}

var UDFS = {};
$(function()
{
    //select Ids
    var params = [<?php echo $this->getIdsCSV(); ?>];
    var old_bapi_get = BAPI.get;
    
     //User defined fields
    UDFS = <?php if(!empty($this->udfs)) echo $this->udfs; else echo '{}'; ?>;
    
     BAPI.get = function()
    {
        old_bapi_get(params, arguments[1], arguments[2], arguments[3]);
        add_udfs();
    };
    
    
    clear_udf('udf_search');
    
});
(function($)
{
$.fn.checkmultiselect = function(options)
        {
            var settings = $.extend(
                    {
                        //nothing for now
                    }, options);

            var selector = settings.selector;

            // Do nothing id no selector passed


            // Check if selector is select multiple
            var node_type = $(this).prop('nodeName');
            
            if(/select/i.test(node_type))
            {
                // is a select
                // need to test if it is multiple
                var ismultiple = $(this).attr('multiple');
                var $ob = $(this);
                console.log(ismultiple);
                if(ismultiple)
                {
                    // Can do stuff
                    // 1, create the list of items
                    var checkbx_list = document.createElement('ul');
                        checkbx_list.className = 'check-multi-select';
                        
                        // options
                        var options = $(this).find('option');
                        
                        $.each(options, function(i,v)
                        {
                            var val = $(v).attr('value');
                            var label = $(v).text();
                            
                            var li_element = document.createElement('li');
                            var li_element_label = document.createElement('label');
                            var checkbox = document.createElement('input');
                                checkbox.value = val;
                                checkbox.style = "-webkit-appearance: checkbox; display: inline-block; margin-right: 5px;";
                                checkbox.type = 'checkbox';
                                
                                $(checkbox).on('click', function(e)
                                {
                                    var $checked = $ob.next().find('input[type="checkbox"]:checked');
                                    var varray = new Array();
                                    $.each($checked, function(i,v)
                                    {
                                       varray.push($(v).val());
                                    });
                                    
                                    $ob.val(varray);
                                    
                                    console.log($ob);
                                });
                                
                            var label_txt = document.createTextNode(label);
                            
                            //apend to li
                            li_element_label.appendChild(checkbox);
                            li_element_label.appendChild(label_txt);
                            
                            li_element.appendChild(li_element_label);
                            
                            //Append to ul
                            checkbx_list.appendChild(li_element);
                        });
                        
                        //put the list after this
                        var $list = $(checkbx_list);
                        $list.insertAfter($(this));
                        
                    // 2. link each item with the select option
                    // 3. hide the multiselect
                    $(this)
                    .css('height', '0px')
                    .css('width', '0px')
                    .css('padding', '0')
                    .css('margin', '0')
                    .css('visibility', 'hidden')
                    .css('position', 'static');
                }
                
            }
            
                
            return this;
            

            
        }

}(jQuery));