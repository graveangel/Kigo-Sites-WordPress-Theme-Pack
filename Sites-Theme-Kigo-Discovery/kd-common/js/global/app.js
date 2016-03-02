var app = {

    /* Attributes */
    debug: true,
    modules: {
        widgets: {},
        templates: {}
    },
    bapiModules: {
        widgets: {},
        templates: {}
    },

    /* Initializing */
    init: function(){
        this.initModules(this.modules);
        this.fixedHeader();
        this.kdMove();
    },
    initBapi: function(){
        this.initModules(this.bapiModules);
    },
    initModules: function(modules){
        for(var module in modules){
            if(typeof modules[module].init == 'function' && typeof modules[module].cond == 'function'){
                if(modules[module].cond()){
                    app.log('Initializing module', module, modules[module]);
                    modules[module].init();
                }
            }else{
                this.initModules(modules[module]);
            }
        }
    },
    initSwiper: function(ele, config){
        return new Swiper(ele, config);
    },
    fixedHeader: function(){

        checkHeader(); //Check in case page has loaded with scroll

        //Listen page scroll to set / unset fixed header. Debounce scroll event.
        window.addEventListener('scroll', debounce(checkHeader, 25)); //ms

        function checkHeader(){

            var currentScroll = window.scrollY,
                header = document.querySelector('.header-background'),
                overHeader = document.querySelector('.header-background .header');

            var scrollMax = overHeader.clientHeight;

            if(currentScroll >= 121){
                header.classList.add('fixed');
            }else{
                header.classList.remove('fixed');
            }
        }
    },

    /* Utilities */
    exists : function(selector){
        var ele = document.querySelector(selector);
        return ele ? true : false;
    },
    isMobile: function() {
        return navigator.userAgent.match(/Android/i)    ||
            navigator.userAgent.match(/webOS/i)             ||
            navigator.userAgent.match(/iPhone/i)            ||
            navigator.userAgent.match(/iPad/i)              ||
            navigator.userAgent.match(/iPod/i)              ||
            navigator.userAgent.match(/BlackBerry/i)        ||
            navigator.userAgent.match(/Windows Phone/i);
    },
    log: function(){
        if(this.debug)
            console.log.apply(console, arguments);
    },
    bapiRender: function(entity, template, callback, data){
        var options = {pagesize: 20, seo: true};

        BAPI.search(entity, options, function (sdata) {
            BAPI.get(sdata.result, entity, {}, function (gdata) {
                var aux_data = {};
                aux_data.result = gdata.result;
                aux_data.config = BAPI.config();

                var final_data = typeof data == 'object' ? Object.assign(aux_data, data) : aux_data;

                var html = Mustache.render(BAPI.UI.mustacheHelpers.getPartials(template), final_data);

                callback(html);
            });
        });
    },
    bapi: {
        search: function(entity, callback, auxOptions){
            var options = auxOptions || {};
            BAPI.search(entity, options, function (sdata) {
                callback(sdata);
            });
        },
        get: function(entity, ids, callback, options){
            BAPI.get(ids, entity, _.assign(options, {}), function (gdata) {
                callback(gdata);
            });
        },
        recursiveGet: function(entity, callback, auxOptions){
            var options = auxOptions || {};
            BAPI.search(entity, options, function (sdata) {
                var propPages = _.chunk(sdata.result, options.pagesize);

                if(auxOptions.waitForAll == 1){
                    var all = [], iterations = 0;
                    propPages.forEach(function(page, i){
                        BAPI.get(page, entity, options, function (gdata) {
                            iterations++;
                            all = _.concat(all, gdata.result);

                            if(iterations == (propPages.length)){ //Last iteration
                                callback({result: all, textdata: BAPI.textdata});
                            }
                        });
                    });
                }else{
                    propPages.forEach(function(page, i){
                        BAPI.get(page, entity, options, function (gdata) {
                            callback(gdata);
                        });
                    });
                }
            })
        },
        render: function(template, data){
            return Mustache.render(BAPI.UI.mustacheHelpers.getPartials(template), data);
        }
    },
    kdMove: function(){
        var moveEles = document.querySelectorAll('[data-move]');

        for(var i = 0; i < moveEles.length; i++){
            var ele = moveEles[i];
            var target = document.querySelector(ele.dataset.move);
            target.appendChild(moveEles[i]);
            ele.classList.add('hasMoved');
        }
    }
};

/* Debounce util */

function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};

/* Main app init on full page load */
window.addEventListener('load', app.init.bind(app));

/* Bapi modules init on DOM load */
window.addEventListener('DOMContentLoaded', app.initBapi.bind(app));