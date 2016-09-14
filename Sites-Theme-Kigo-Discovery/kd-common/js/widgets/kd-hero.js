app.modules.widgets.hero = {
    selector: '.kd-hero',
    sliderSelector: '.swiper-container',
    init: function () {
        var instances = document.querySelectorAll(this.selector);
        $.each(instances, this.initSlider.bind(this));
    },
    cond: function (){
        return app.exists(this.selector);
    },
    getSliderConfig: function (ele){
        var dataset = ele.querySelector(this.sliderSelector).dataset;

        var config =  {
            autoplay: dataset.speed || false,
            loop: dataset.loop == 'on',
            nextButton: ele.querySelector('.next'),
            prevButton: ele.querySelector('.prev'),
            effect: dataset.effect || 'slide',
            speed: 500,
            freeMode: dataset.freemode == 'on',
            direction: dataset.direction || 'horizontal',
            pagination: dataset.pagination == 'on' ? ele.querySelector('.swiper-pagination') : false,
            paginationClickable: true,
            bulletActiveClass: 'active',
            centeredSlides: dataset.centered_slides == 'on',
            slidesPerView: dataset.slides_per_view || 1
        };

        return config;
    },
    initSlider: function (key, ele){ alert('hi');
        var config = this.getSliderConfig(ele);
        app.initSwiper(ele.querySelector(this.sliderSelector), config);
    }
};