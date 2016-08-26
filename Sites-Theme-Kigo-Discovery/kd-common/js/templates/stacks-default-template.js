app.bapiModules.templates.stackAreasDefaultTemplate =
{
    init: function()
    {
        if(this.cond()) // If Default template
        {
            // Hero slider swiper
            var mySwiper = new Swiper ('.hero .swiper-container', {
                             // Optional parameters
                             direction: 'horizontal',
                             loop: true,
                             autoplay: 3000,
                             speed: 1000,
                             effect: 'fade',

                            // If we need pagination
                            pagination: '.swiper-pagination',

                            // Navigation arrows
                            nextButton: '.swiper-button-next',
                            prevButton: '.swiper-button-prev',

                            // And if we need scrollbar
                            scrollbar: '.swiper-scrollbar',
                        });

            //Tab Selector
            $('.tab-selector a').on('click', function(e)
            {
                    $('.tab-selector a').removeClass('primary-fill-color');

                    $(this).addClass('primary-fill-color');

                    //Dissable all tab contents
                    $('.tabs-contents li').removeClass('active');

                    //Activate target;
                    $($(this).attr('data-target')).addClass('active');

                    setCookie('active-tab', $(this).attr('data-target'), 9999);

            });
            
            $('.activate-tab').on('click', function(e)
            {
               setCookie('active-tab', $(this).attr('data-target'), 9999); 
            });

            //ActivateTab
            var subarea = getCookie('subarea');
            var active_tab = getCookie('active-tab');

            if(subarea == 1)
            {
                $('[data-target=".property-list"]').click();
            }
            else
            {
                $('[data-target="'+active_tab+'"]').click();
            }

        }
    },
    cond: function cond() {
        return app.exists('.stacks-default-template');
    }
};
