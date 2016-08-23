(function($)
{
    $(function()
    {
        /* Ready */
        var modules = [marketAreasMainLanding, marketAreaPage];
        /* Initiate Modules */
        for( var i in modules )
        {
            modules[i].init();
        }
    });
}(jQuery));