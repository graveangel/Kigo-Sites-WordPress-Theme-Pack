app.modules.widgets.items = {
    selector: '.kd-items',
    init: function(){
        this.toggleEvents();
        this.initSliders();
    },
    toggleEvents: function(){
        var toggles = document.querySelectorAll('.item-block .open-close');

        $.each(toggles, function(i, toggle){
            toggle.addEventListener('click', function(e){
                this.parentElement.classList.toggle('active');
            })
        });
    },
    initSliders: function(){
        var itemSliders = document.querySelectorAll(this.selector+' .swiper-container');

        for(var i = 0; i < itemSliders.length; i++){
            var swiperEle = itemSliders[i];

            var swiper = new Swiper(swiperEle,
                {
                    spaceBetween: 15,
                    nextButton: '.next-slide',
                    prevButton: '.prev-slide',
                    slidesPerView: swiperEle.dataset.columns || 5,
                    loop: true,
                    loopAdditionalSlides: 5,
                    breakpoints: {
                        992:{
                            slidesPerView: 3
                        },
                        780: {
                            centeredSlides: true,
                            slidesPerView: 2
                        },
                        480:{
                            centeredSlides: true,
                            slidesPerView: 1
                        }
                    },
                });
        }
    },
    cond: function() {
        return app.exists('.kd-items');
    }
};
