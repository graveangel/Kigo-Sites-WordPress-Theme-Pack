app.modules.widgets.team = {
    mobileBreak:    991,
    selector: '.kd-team',
    init: function(){
        var teamSliders = document.querySelectorAll(this.selector+'.swiper-container');

        for(var i = 0; i < teamSliders.length; i++){
            var swiperEle = teamSliders[i];
            var swiper = new Swiper(swiperEle,
                {
                    nextButton: '.next-slide',
                    prevButton: '.prev-slide',
                    slidesPerView: swiperEle.dataset.columns || 5,
                    loop: true,
                    loopAdditionalSlides: 5,
                    simulateTouch: false,
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
                    onInit: function(){
                        swiperEle.classList.remove('faded-out');
                    }
                });
        }
    },
    cond: function cond() {
        return app.exists('.kd-team');
    }
};