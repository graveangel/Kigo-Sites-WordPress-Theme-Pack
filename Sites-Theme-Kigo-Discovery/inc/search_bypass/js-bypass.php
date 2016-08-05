function add_udf(udf)
{
var options = '<option value>Select '+udf.name+'</option>';
for(var e in udf.options)
{
    options += '<option value="'+udf.options[e]+'">'+udf.options[e]+'</option>'
}
$('#bapi-search .kd-search').append('<label class="udf-filter"><select class="udf-filter-select" name="udf['+udf.slug+']" data-udf="'+udf.slug+'">'+options+'</select></label>');

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
         $.cookie($cookiename, udf_search_cookie);
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
