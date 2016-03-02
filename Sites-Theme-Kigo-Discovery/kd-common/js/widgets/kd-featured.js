app.modules.widgets.featured = {
    selector: '.kd-featured',
    sliderSelector: '.swiper-container',
    observedSelector: '.swiper-wrapper',
    init: function init() {
        var instances = document.querySelectorAll(this.selector);

        $.each(instances, function(key, ele){
            var observedEle = ele.querySelector(this.observedSelector);

            if(observedEle.children.length > 0){
                this.initSlider(key, ele);
            }else {
                var observer = new MutationObserver(function (mutations) {
                    this.initSlider(key, ele);
                    observer.disconnect();
                }.bind(this));
                observer.observe(observedEle, {childList: true});
            }
        }.bind(this));
    },
    cond: function(){
        return app.exists(this.selector);
    },
    getSliderConfig: function(ele){
        return {
            autoplay: false,
            loop: true,
            paginationClickable: true,
            pagination: ele.querySelector('.swiper-pagination'),
            nextButton: ele.querySelector('.next'),
            prevButton: ele.querySelector('.prev'),
            bulletActiveClass: 'secondary-fill-color',
            simulateTouch: false
        };
    },
    initSlider: function(key, ele){
        var sliderEle = ele.querySelector(this.sliderSelector);
        app.initSwiper(sliderEle, this.getSliderConfig(ele));
    }
};