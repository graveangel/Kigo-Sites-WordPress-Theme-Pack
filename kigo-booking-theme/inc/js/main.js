/* Kigo Booking Themne Scripts */
$(function()
{
    /**/

    /* make the menu toggle button work with the main navigation menu */
    $('#insta-top-fixed .menu').parent().addClass('nav-collapse collapse');

    /* Sub menu toggle */
    $('.dropdown > a').on('click', function(e)
    {
        e.preventDefault();
        e.stopPropagation();
         if($(this).hasClass('opened'))
         {
            $(this).removeClass('opened');
    $(this).removeClass('opened').next().removeClass('opened');
         }
    else
    {
        $('.opened').removeClass('opened');
        $(this).addClass('trans');
        $(this).addClass('opened').next().addClass('opened');
    }
    });

    $(document).on('click', function(e)
    {
       $('.opened').removeClass('opened');
    });

});
