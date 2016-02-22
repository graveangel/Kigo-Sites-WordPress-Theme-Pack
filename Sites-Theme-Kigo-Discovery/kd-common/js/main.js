/* Edited - Mon Feb 22 2016 10:14:41 GMT+0100 (Romance Standard Time) */
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

            if(currentScroll >= scrollMax){
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
        var options = {};

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
/**
 * @license
 * Copyright (c) 2014 The Polymer Project Authors. All rights reserved.
 * This code may only be used under the BSD style license found at http://polymer.github.io/LICENSE.txt
 * The complete set of authors may be found at http://polymer.github.io/AUTHORS.txt
 * The complete set of contributors may be found at http://polymer.github.io/CONTRIBUTORS.txt
 * Code distributed by Google as part of the polymer project is also
 * subject to an additional IP rights grant found at http://polymer.github.io/PATENTS.txt
 */

(function(global) {

    // Don't allow this object to be redefined.
    if (global.JsMutationObserver) {
        return;
    }

    var registrationsTable = new WeakMap();

    var setImmediate;

    // As much as we would like to use the native implementation, IE
    // (all versions) suffers a rather annoying bug where it will drop or defer
    // callbacks when heavy DOM operations are being performed concurrently.
    //
    // For a thorough discussion on this, see:
    // http://codeforhire.com/2013/09/21/setimmediate-and-messagechannel-broken-on-internet-explorer-10/
    if (/Trident|Edge/.test(navigator.userAgent)) {
        // Sadly, this bug also affects postMessage and MessageQueues.
        //
        // We would like to use the onreadystatechange hack for IE <= 10, but it is
        // dangerous in the polyfilled environment due to requiring that the
        // observed script element be in the document.
        setImmediate = setTimeout;

        // If some other browser ever implements it, let's prefer their native
        // implementation:
    } else if (window.setImmediate) {
        setImmediate = window.setImmediate;

        // Otherwise, we fall back to postMessage as a means of emulating the next
        // task semantics of setImmediate.
    } else {
        var setImmediateQueue = [];
        var sentinel = String(Math.random());
        window.addEventListener('message', function(e) {
            if (e.data === sentinel) {
                var queue = setImmediateQueue;
                setImmediateQueue = [];
                queue.forEach(function(func) {
                    func();
                });
            }
        });
        setImmediate = function(func) {
            setImmediateQueue.push(func);
            window.postMessage(sentinel, '*');
        };
    }

    // This is used to ensure that we never schedule 2 callas to setImmediate
    var isScheduled = false;

    // Keep track of observers that needs to be notified next time.
    var scheduledObservers = [];

    /**
     * Schedules |dispatchCallback| to be called in the future.
     * @param {MutationObserver} observer
     */
    function scheduleCallback(observer) {
        scheduledObservers.push(observer);
        if (!isScheduled) {
            isScheduled = true;
            setImmediate(dispatchCallbacks);
        }
    }

    function wrapIfNeeded(node) {
        return window.ShadowDOMPolyfill &&
            window.ShadowDOMPolyfill.wrapIfNeeded(node) ||
            node;
    }

    function dispatchCallbacks() {
        // http://dom.spec.whatwg.org/#mutation-observers

        isScheduled = false; // Used to allow a new setImmediate call above.

        var observers = scheduledObservers;
        scheduledObservers = [];
        // Sort observers based on their creation UID (incremental).
        observers.sort(function(o1, o2) {
            return o1.uid_ - o2.uid_;
        });

        var anyNonEmpty = false;
        observers.forEach(function(observer) {

            // 2.1, 2.2
            var queue = observer.takeRecords();
            // 2.3. Remove all transient registered observers whose observer is mo.
            removeTransientObserversFor(observer);

            // 2.4
            if (queue.length) {
                observer.callback_(queue, observer);
                anyNonEmpty = true;
            }
        });

        // 3.
        if (anyNonEmpty)
            dispatchCallbacks();
    }

    function removeTransientObserversFor(observer) {
        observer.nodes_.forEach(function(node) {
            var registrations = registrationsTable.get(node);
            if (!registrations)
                return;
            registrations.forEach(function(registration) {
                if (registration.observer === observer)
                    registration.removeTransientObservers();
            });
        });
    }

    /**
     * This function is used for the "For each registered observer observer (with
     * observer's options as options) in target's list of registered observers,
     * run these substeps:" and the "For each ancestor ancestor of target, and for
     * each registered observer observer (with options options) in ancestor's list
     * of registered observers, run these substeps:" part of the algorithms. The
     * |options.subtree| is checked to ensure that the callback is called
     * correctly.
     *
     * @param {Node} target
     * @param {function(MutationObserverInit):MutationRecord} callback
     */
    function forEachAncestorAndObserverEnqueueRecord(target, callback) {
        for (var node = target; node; node = node.parentNode) {
            var registrations = registrationsTable.get(node);

            if (registrations) {
                for (var j = 0; j < registrations.length; j++) {
                    var registration = registrations[j];
                    var options = registration.options;

                    // Only target ignores subtree.
                    if (node !== target && !options.subtree)
                        continue;

                    var record = callback(options);
                    if (record)
                        registration.enqueue(record);
                }
            }
        }
    }

    var uidCounter = 0;

    /**
     * The class that maps to the DOM MutationObserver interface.
     * @param {Function} callback.
     * @constructor
     */
    function JsMutationObserver(callback) {
        this.callback_ = callback;
        this.nodes_ = [];
        this.records_ = [];
        this.uid_ = ++uidCounter;
    }

    JsMutationObserver.prototype = {
        observe: function(target, options) {
            target = wrapIfNeeded(target);

            // 1.1
            if (!options.childList && !options.attributes && !options.characterData ||

                    // 1.2
                options.attributeOldValue && !options.attributes ||

                    // 1.3
                options.attributeFilter && options.attributeFilter.length &&
                !options.attributes ||

                    // 1.4
                options.characterDataOldValue && !options.characterData) {

                throw new SyntaxError();
            }

            var registrations = registrationsTable.get(target);
            if (!registrations)
                registrationsTable.set(target, registrations = []);

            // 2
            // If target's list of registered observers already includes a registered
            // observer associated with the context object, replace that registered
            // observer's options with options.
            var registration;
            for (var i = 0; i < registrations.length; i++) {
                if (registrations[i].observer === this) {
                    registration = registrations[i];
                    registration.removeListeners();
                    registration.options = options;
                    break;
                }
            }

            // 3.
            // Otherwise, add a new registered observer to target's list of registered
            // observers with the context object as the observer and options as the
            // options, and add target to context object's list of nodes on which it
            // is registered.
            if (!registration) {
                registration = new Registration(this, target, options);
                registrations.push(registration);
                this.nodes_.push(target);
            }

            registration.addListeners();
        },

        disconnect: function() {
            this.nodes_.forEach(function(node) {
                var registrations = registrationsTable.get(node);
                for (var i = 0; i < registrations.length; i++) {
                    var registration = registrations[i];
                    if (registration.observer === this) {
                        registration.removeListeners();
                        registrations.splice(i, 1);
                        // Each node can only have one registered observer associated with
                        // this observer.
                        break;
                    }
                }
            }, this);
            this.records_ = [];
        },

        takeRecords: function() {
            var copyOfRecords = this.records_;
            this.records_ = [];
            return copyOfRecords;
        }
    };

    /**
     * @param {string} type
     * @param {Node} target
     * @constructor
     */
    function MutationRecord(type, target) {
        this.type = type;
        this.target = target;
        this.addedNodes = [];
        this.removedNodes = [];
        this.previousSibling = null;
        this.nextSibling = null;
        this.attributeName = null;
        this.attributeNamespace = null;
        this.oldValue = null;
    }

    function copyMutationRecord(original) {
        var record = new MutationRecord(original.type, original.target);
        record.addedNodes = original.addedNodes.slice();
        record.removedNodes = original.removedNodes.slice();
        record.previousSibling = original.previousSibling;
        record.nextSibling = original.nextSibling;
        record.attributeName = original.attributeName;
        record.attributeNamespace = original.attributeNamespace;
        record.oldValue = original.oldValue;
        return record;
    };

    // We keep track of the two (possibly one) records used in a single mutation.
    var currentRecord, recordWithOldValue;

    /**
     * Creates a record without |oldValue| and caches it as |currentRecord| for
     * later use.
     * @param {string} oldValue
     * @return {MutationRecord}
     */
    function getRecord(type, target) {
        return currentRecord = new MutationRecord(type, target);
    }

    /**
     * Gets or creates a record with |oldValue| based in the |currentRecord|
     * @param {string} oldValue
     * @return {MutationRecord}
     */
    function getRecordWithOldValue(oldValue) {
        if (recordWithOldValue)
            return recordWithOldValue;
        recordWithOldValue = copyMutationRecord(currentRecord);
        recordWithOldValue.oldValue = oldValue;
        return recordWithOldValue;
    }

    function clearRecords() {
        currentRecord = recordWithOldValue = undefined;
    }

    /**
     * @param {MutationRecord} record
     * @return {boolean} Whether the record represents a record from the current
     * mutation event.
     */
    function recordRepresentsCurrentMutation(record) {
        return record === recordWithOldValue || record === currentRecord;
    }

    /**
     * Selects which record, if any, to replace the last record in the queue.
     * This returns |null| if no record should be replaced.
     *
     * @param {MutationRecord} lastRecord
     * @param {MutationRecord} newRecord
     * @param {MutationRecord}
     */
    function selectRecord(lastRecord, newRecord) {
        if (lastRecord === newRecord)
            return lastRecord;

        // Check if the the record we are adding represents the same record. If
        // so, we keep the one with the oldValue in it.
        if (recordWithOldValue && recordRepresentsCurrentMutation(lastRecord))
            return recordWithOldValue;

        return null;
    }

    /**
     * Class used to represent a registered observer.
     * @param {MutationObserver} observer
     * @param {Node} target
     * @param {MutationObserverInit} options
     * @constructor
     */
    function Registration(observer, target, options) {
        this.observer = observer;
        this.target = target;
        this.options = options;
        this.transientObservedNodes = [];
    }

    Registration.prototype = {
        enqueue: function(record) {
            var records = this.observer.records_;
            var length = records.length;

            // There are cases where we replace the last record with the new record.
            // For example if the record represents the same mutation we need to use
            // the one with the oldValue. If we get same record (this can happen as we
            // walk up the tree) we ignore the new record.
            if (records.length > 0) {
                var lastRecord = records[length - 1];
                var recordToReplaceLast = selectRecord(lastRecord, record);
                if (recordToReplaceLast) {
                    records[length - 1] = recordToReplaceLast;
                    return;
                }
            } else {
                scheduleCallback(this.observer);
            }

            records[length] = record;
        },

        addListeners: function() {
            this.addListeners_(this.target);
        },

        addListeners_: function(node) {
            var options = this.options;
            if (options.attributes)
                node.addEventListener('DOMAttrModified', this, true);

            if (options.characterData)
                node.addEventListener('DOMCharacterDataModified', this, true);

            if (options.childList)
                node.addEventListener('DOMNodeInserted', this, true);

            if (options.childList || options.subtree)
                node.addEventListener('DOMNodeRemoved', this, true);
        },

        removeListeners: function() {
            this.removeListeners_(this.target);
        },

        removeListeners_: function(node) {
            var options = this.options;
            if (options.attributes)
                node.removeEventListener('DOMAttrModified', this, true);

            if (options.characterData)
                node.removeEventListener('DOMCharacterDataModified', this, true);

            if (options.childList)
                node.removeEventListener('DOMNodeInserted', this, true);

            if (options.childList || options.subtree)
                node.removeEventListener('DOMNodeRemoved', this, true);
        },

        /**
         * Adds a transient observer on node. The transient observer gets removed
         * next time we deliver the change records.
         * @param {Node} node
         */
        addTransientObserver: function(node) {
            // Don't add transient observers on the target itself. We already have all
            // the required listeners set up on the target.
            if (node === this.target)
                return;

            this.addListeners_(node);
            this.transientObservedNodes.push(node);
            var registrations = registrationsTable.get(node);
            if (!registrations)
                registrationsTable.set(node, registrations = []);

            // We know that registrations does not contain this because we already
            // checked if node === this.target.
            registrations.push(this);
        },

        removeTransientObservers: function() {
            var transientObservedNodes = this.transientObservedNodes;
            this.transientObservedNodes = [];

            transientObservedNodes.forEach(function(node) {
                // Transient observers are never added to the target.
                this.removeListeners_(node);

                var registrations = registrationsTable.get(node);
                for (var i = 0; i < registrations.length; i++) {
                    if (registrations[i] === this) {
                        registrations.splice(i, 1);
                        // Each node can only have one registered observer associated with
                        // this observer.
                        break;
                    }
                }
            }, this);
        },

        handleEvent: function(e) {
            // Stop propagation since we are managing the propagation manually.
            // This means that other mutation events on the page will not work
            // correctly but that is by design.
            e.stopImmediatePropagation();

            switch (e.type) {
                case 'DOMAttrModified':
                    // http://dom.spec.whatwg.org/#concept-mo-queue-attributes

                    var name = e.attrName;
                    var namespace = e.relatedNode.namespaceURI;
                    var target = e.target;

                    // 1.
                    var record = new getRecord('attributes', target);
                    record.attributeName = name;
                    record.attributeNamespace = namespace;

                    // 2.
                    var oldValue =
                        e.attrChange === MutationEvent.ADDITION ? null : e.prevValue;

                    forEachAncestorAndObserverEnqueueRecord(target, function(options) {
                        // 3.1, 4.2
                        if (!options.attributes)
                            return;

                        // 3.2, 4.3
                        if (options.attributeFilter && options.attributeFilter.length &&
                            options.attributeFilter.indexOf(name) === -1 &&
                            options.attributeFilter.indexOf(namespace) === -1) {
                            return;
                        }
                        // 3.3, 4.4
                        if (options.attributeOldValue)
                            return getRecordWithOldValue(oldValue);

                        // 3.4, 4.5
                        return record;
                    });

                    break;

                case 'DOMCharacterDataModified':
                    // http://dom.spec.whatwg.org/#concept-mo-queue-characterdata
                    var target = e.target;

                    // 1.
                    var record = getRecord('characterData', target);

                    // 2.
                    var oldValue = e.prevValue;


                    forEachAncestorAndObserverEnqueueRecord(target, function(options) {
                        // 3.1, 4.2
                        if (!options.characterData)
                            return;

                        // 3.2, 4.3
                        if (options.characterDataOldValue)
                            return getRecordWithOldValue(oldValue);

                        // 3.3, 4.4
                        return record;
                    });

                    break;

                case 'DOMNodeRemoved':
                    this.addTransientObserver(e.target);
                // Fall through.
                case 'DOMNodeInserted':
                    // http://dom.spec.whatwg.org/#concept-mo-queue-childlist
                    var changedNode = e.target;
                    var addedNodes, removedNodes;
                    if (e.type === 'DOMNodeInserted') {
                        addedNodes = [changedNode];
                        removedNodes = [];
                    } else {

                        addedNodes = [];
                        removedNodes = [changedNode];
                    }
                    var previousSibling = changedNode.previousSibling;
                    var nextSibling = changedNode.nextSibling;

                    // 1.
                    var record = getRecord('childList', e.target.parentNode);
                    record.addedNodes = addedNodes;
                    record.removedNodes = removedNodes;
                    record.previousSibling = previousSibling;
                    record.nextSibling = nextSibling;

                    forEachAncestorAndObserverEnqueueRecord(e.relatedNode, function(options) {
                        // 2.1, 3.2
                        if (!options.childList)
                            return;

                        // 2.2, 3.3
                        return record;
                    });

            }

            clearRecords();
        }
    };

    global.JsMutationObserver = JsMutationObserver;

    if (!global.MutationObserver) {
        global.MutationObserver = JsMutationObserver;
        // Explicltly mark MO as polyfilled for user reference.
        JsMutationObserver._isPolyfilled = true;
    }

})(self);
/* 
 * Array List of the 47 status code of the Yahoo weather
 */
var codeToClassname=["wi-tornado","wi-day-thunderstorm","wi-hurricane","wi-thunderstorm","wi-storm-showers","wi-rain-mix","wi-rain-mix","wi-rain-mix","wi-rain-mix","wi-snow","wi-rain-mix","wi-showers","wi-showers","wi-snow","wi-snow","wi-rain-mix","wi-snow","wi-rain-mix","wi-hail","wi-fog","wi-fog","wi-fog","wi-fog","wi-fog","wi-cloudy-gusts","wi-cloudy-gusts","wi-cloudy","wi-night-cloudy","wi-day-cloudy","wi-night-partly-cloudy","wi-day-cloudy","wi-night-clear","wi-day-sunny","wi-night-clear","wi-day-sunny","wi-rain-mix","wi-hot","wi-storm-showers","wi-storm-showers","wi-storm-showers","wi-showers","wi-snow","wi-showers","wi-rain-mix","wi-cloudy","wi-storm-showers","wi-hail","wi-storm-showers"];
function getClassnameForCode(code){
    if(code < 0 || code > 47){
        return 'wi-na';
    }
    return codeToClassname[code];
}

app.modules.templates.propertyDetails = {
    forceusemap: false,
    mobileBreak: 768,
    init: function () {
        if (this.cond())
        {
            this.fixHeroImage()
                    .openCloseAmenitiesList()
                    .lightBoxAndCarousel()
                    .imageHover()
                    .popUpBookingForm()
                    .checkPropSettings()
                    .checkThumbs()
                    .checkUseMap();
        }
    },
    lightBoxAndCarousel: function lightBoxAndCarousel() {

        var gallery = jQuery('.ppt-slides a').simpleLightbox();

        jQuery(document).on('click', '.more,.open-lightbox, .ppt-slides a', function (e) {
            e.preventDefault();
            if (typeof jQuery(this).attr('data-index') !== "undefined") {
                var data_index = jQuery(this).attr('data-index');
                gallery.open(jQuery(jQuery('.ppt-slides a[data-index="' + data_index + '"]')));
            }
            else
                gallery.open(jQuery(jQuery('.ppt-slides a')[0]));
        });

        //carousel
        var ob = this;
        if (jQuery(window).width() <= ob.mobileBreak)
            ob.swiperCarousel();


        //jQuery(window).resize(debounce(function (e) {
        //    this.checkThumbs();
        //    if (jQuery(window).width() <= ob.mobileBreak)
        //        ob.swiperCarousel();
        //    else if (typeof SWCarousel !== 'undefined' && SWCarousel) {
        //        //destroying swipeslider
        //        SWCarousel.destroy(true, true);
        //        SWCarousel = null;
        //        //removing swipeslider classes
        //        jQuery('.ppt-slides').removeClass('swiper-wrapper');
        //        jQuery('.ppt-slides > li').removeClass('swiper-slide');
        //    }
        //}.bind(this), 500));
        return this;
    },
    imageHover: function () {
        jQuery('.simple-lightbox').hover(function () {
            jQuery("#ppt-image-caption").text(jQuery(this).attr('title'));
        });
        return this;
    },
    swiperCarousel: function swiperCarousel() {
        if (typeof SWCarousel === 'undefined' || !SWCarousel)
        {
            //Adding swipeslider classes
            jQuery('.ppt-slides').addClass('swiper-wrapper');
            jQuery('.ppt-slides > li').addClass('swiper-slide');
            //Initializing swipeslider
            SWCarousel = new Swiper('.ppt-images', {
                centeredSlides: true,
                spaceBetween: 0,
                nextButton: '.next-slide',
                prevButton: '.prev-slide'
            });
        }

        return this;
    },
    openCloseAmenitiesList: function openCloseAmenitiesList() {
        //Open and close amenities list.
        jQuery(document).on('click', '.template-property .open-close', function (e) {
            //ToggleClass Active
            jQuery(this).parent().toggleClass('active');
        });

        return this;
    },
    fixHeroImage: function PDFixHeroImage() {
        if (app.exists('.hero-image') && !app.isMobile()) {


            var heroImage = jQuery('.hero-image');
            var HIHeight = heroImage.height();

            var HIContainer = heroImage.parent();
            var HIContainerHeight = HIContainer.height();


            if (HIHeight > HIContainerHeight) {
                var bottomHI = Math.round((HIHeight - HIContainerHeight) / 2);
                heroImage.css('bottom', "-" + bottomHI + "px");
            }
        }
        return this;
    },
    popUpBookingForm: function () {
        jQuery(document).on('click', '.pop-up-form-link', function (e) {
            e.preventDefault();
            jQuery('.pop-up-booking-form').addClass('active');
        });

        jQuery(document).on('click', '.booking-form', function (e) {
            e.stopPropagation();
        });

        jQuery(document).on('click', '.booking-form .close, .pop-up-booking-form.active', function (e) {
            e.preventDefault();
            jQuery('.pop-up-booking-form').removeClass('active');
        });

        return this;
    },
    checkUseMap: function () {
        var pid = parseInt(jQuery('.bapi-entityadvisor').attr('data-pkid'));
        var lat = jQuery('.bapi-entityadvisor').attr('data-lat');
        var long = jQuery('.bapi-entityadvisor').attr('data-long');
       
        var selected = selected_usemap;

        var usemap = false;
        selected.forEach(function (v) {
            if (v === pid) {
                usemap = true;
            }
        });
   
        
        if(/comingsoon\.gif/.test(jQuery('.bapi-entityadvisor').attr('data-bg'))){
            usemap = true;
        }
 
        if(this.forceusemap) usemap = true;
        
        var mapbox = document.querySelector('.hero-image');
        
        if (usemap) {
            
            
            
            var map = new google.maps.Map(mapbox, {
                center: {lat: parseFloat(lat), lng: parseFloat(long)},
                scrollwheel: false,
                zoom: 18
            });

            var marker_color = document.querySelector('.template-property[data-markercolor]').dataset.markercolor;
            
            function setMarker(){
                 var icon = {
                path: "M-0.5-41C-7.9-41-14-34.9-14-27.5c0,3,1.9,7.9,5.9,15c2.8,5,5.5,9.2,5.6,9.3l2,3l2-3c0.1-0.2,2.9-4.3,5.6-9.3" +
                        "c3.9-7.1,5.9-12,5.9-15C13-34.9,7-41-0.5-41z M-0.5-20.6c-3.9,0-7-3.1-7-7s3.1-7,7-7s7,3.1,7,7S3.4-20.6-0.5-20.6z",
                fillColor: marker_color,
                fillOpacity: 1,
                strokeColor: 'rgba(0,0,0,.25)',
                strokeWeight: 1
            };

            /* Create marker + store info window inside for later use (also in property ele) */
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat, long),
                map: map,
                iw: false,
                icon: icon
            });

            }
            
            function setStreetView(){
                var panorama = new google.maps.StreetViewPanorama(
                    document.getElementById('pano'), {
                position: {lat: parseFloat(lat), lng: parseFloat(long)},
                pov: {
                    heading: 34,
                    pitch: 10
                }
            });
            
            map.setStreetView(panorama);
            }
            
            function setMapStreetview(){
                jQuery(mapbox).css('width','50%');
                
                var center = map.getCenter();
                google.maps.event.trigger(map, "resize");
                map.setCenter(center); 
                
                 jQuery('#pano').css('width','50%').css('left','50%');
            }
            
            
            switch(selected_usemap_layout) {
                case 0:
                    setMarker();
                    jQuery(mapbox).css('width','100%');
                     jQuery('#pano').hide();
                    break;
                case 1:
                    setMarker();
                    setStreetView();
                    setMapStreetview();
                    break;
                case 2:
                    setStreetView();
                    jQuery(mapbox).hide;
                    break;
                    
                default:
                    setMarker();
                    setStreetView();
                    setMapStreetview();
                    break;
            }

        } else {
            //use background image
            var background_image = jQuery('.bapi-entityadvisor').attr('data-bg');
            jQuery(mapbox).css('background-image', 'url("' + background_image + '")');
        }

        return this;
    },
    checkThumbs: function(){
        if(/comingsoon\.gif/.test($('.simple-lightbox').attr('href'))){
            if(/comingsoon\.gif/.test(jQuery('.bapi-entityadvisor').attr('data-bg'))){
                //hide images
                $('.ppt-images').hide();
                //Desc full width
                $('.ppt-desc').css('width','100%');
            }else {
                $('.ppt-images').show();
                $('.simple-lightbox').attr('href',jQuery('.bapi-entityadvisor').attr('data-bg'));
                $('.thumb-img').attr('src',jQuery('.bapi-entityadvisor').attr('data-bg'));
            }
        }else {
            //check how many images. in only one then use that as thumb and set the map
            $('.ppt-images').show();
            if($('.thumb-img').length === 1){
                $('.hero-image').css('background','transparent');
                this.forceusemap = true;
                $('.more').hide();
                $('.ppt-slides li:first-child').css('width','100%');
            }
        }
      return this;  
    },
    checkPropSettings: function(){
        if(force_usemap){
            this.forceusemap = true;
        }
        
        if(forced_featured){
            this.forcedfeatured = true;
        }
        
        if(usemap_layout>=0 && force_usemap){
            selected_usemap_layout = usemap_layout;
        }
        
        return this;
    },
    cond: function cond() {
        return app.exists('.page-template-property-detail');
    }
};

app.modules.templates.searchPage = {
    /* Element selectors */
    templateSelector: 'body.page-template-search-page',
    mapSelector: '#mapContainer',
    propertiesContainerSelector: '#propertiesContainer',
    propertySelector: '',
    mapObj: null,
    markers: [],
    bounds: null,
    openMarkers: [],
    /* Methods */
    init: function(){
        this.mapObj = this.initMap();
        this.initMarkers();
        this.mapResetEvents();
        this.viewEvents();
    },
    cond: function(){
        return document.querySelectorAll(this.templateSelector).length > 0;
    },
    initMap: function(){
        return new google.maps.Map(document.querySelector(this.mapSelector), {
            center: {lat: -34.397, lng: 150.644},
            zoom: 8
        });
    },
    mapResetEvents: function(){
        var ele = document.querySelector('#resetMap');
        ele.addEventListener('click', this.centerMap.bind(this));
    },
    initMarkers: function(){
        var properties = null;
        var propertiesContainer = document.querySelector(this.propertiesContainerSelector);

        /* Check if properties have been populated, if not observe until they arrive */
        if(propertiesContainer.children.length > 0){ //Properties already loaded
            properties = this.getProperties();
            this.propsToMarkers(properties);
            this.centerMap();
        }else{ //Properties not yet loaded
            var observer = new MutationObserver(function(mutations){
                    properties = this.getProperties();
                    this.propsToMarkers(properties);
                    this.centerMap();
                    observer.disconnect();
                }.bind(this)
            );
            observer.observe(propertiesContainer, {childList: true});
        }
    },
    getProperties: function(){
        return document.querySelectorAll(this.propertiesContainerSelector+' .property');
    },
    propsToMarkers: function(props){
        for(var i = 0; i < props.length; i++){

            var prop = props[i], //Get a property from the list
                mapObj = this.mapObj, //Save mapObj for later use
                propMarker = JSON.parse(prop.dataset.marker), //Parse dataset marker object
                coords = propMarker.coord, //Get marker coordinates
                iwEle = prop.querySelector('.prop-map-location'); //Get the marker's info window

            /* Create info window */
            var infoWindow = new google.maps.InfoWindow({
                content: iwEle.querySelector('.info-html').outerHTML
            });


            /* Get the primary color from the map container to set marker icon color */
            var markerColor = document.querySelector(this.mapSelector).dataset.markercolor;

            /* Create an SVG icon, fill in primary color */
            var icon = {
                path: "M-0.5-41C-7.9-41-14-34.9-14-27.5c0,3,1.9,7.9,5.9,15c2.8,5,5.5,9.2,5.6,9.3l2,3l2-3c0.1-0.2,2.9-4.3,5.6-9.3"+
                "c3.9-7.1,5.9-12,5.9-15C13-34.9,7-41-0.5-41z M-0.5-20.6c-3.9,0-7-3.1-7-7s3.1-7,7-7s7,3.1,7,7S3.4-20.6-0.5-20.6z",
                fillColor: markerColor,
                fillOpacity: 1,
                strokeColor: 'rgba(0,0,0,.25)',
                strokeWeight: 1
            };

            /* Create marker + store info window inside for later use (also in property ele) */
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(coords.lat, coords.lng),
                map: this.mapObj,
                iw: infoWindow,
                icon: icon
            });

            prop.marker = marker;

            /* Add event listeners to show info window */
            marker.addListener('click', function(marker) {
                this.openMarkers.map(function(m){m.iw.close()})
                marker.iw.open(mapObj, marker);
                this.openMarkers.push(marker);
            }.bind(this, marker));


            /* Add event listeners for list items */
            prop.querySelector('.viewInMap').addEventListener('click',
                function(prop) {
                    /* first, we close any open marker InfoWindows */
                    this.openMarkers.map(function(m){m.iw.close()})
                    /* then we can open the new marker InfoWindow */
                    prop.marker.iw.open(mapObj, prop.marker);
                    /* we store the open InfoWindows to keep track */
                    this.openMarkers.push(prop.marker);
                }.bind(this, prop)
            );

            /* We store markers for later use */
            this.markers.push(marker);
        }
    },
    centerMap: function(){
        /*
         If the map is initializing, we create new bounds to center all markers on the map.
         If not initializing (re-centering the map) we reset zoom & center position to initial values.
         */
        if(this.bounds == null) {
            var bounds = new google.maps.LatLngBounds();
            var markers = this.markers;
            /* Extend bounds to all markers and fit view */
            for (index in markers) {
                var data = markers[index];
                bounds.extend(new google.maps.LatLng(data.position.lat(), data.position.lng()));
            }
            this.bounds = bounds;
            this.mapObj.fitBounds(this.bounds);
        }else{
            /* We revert to the initial map state */
            this.mapObj.fitBounds(this.bounds);
        }
    },
    viewEvents: function(){
        var wrapper = document.querySelector('.split-search'),
            propContainer = document.querySelector('.propContainer'),
            listTmpl = 'tmpl-propertysearch-listview',
            mapTmpl = 'tmpl-propertysearch-mapview';

        propContainer.addEventListener('click', function(e){
            console.log(e.target);
            if(e.target.dataset.template == listTmpl){
                wrapper.classList.add('listView');
            }else if(e.target.dataset.template == mapTmpl){
                wrapper.classList.remove('listView');
            }
        });

    }
};
app.bapiModules.widgets.buckets = {
    selector: '.kd-buckets',
    init: function(){
        $(this.selector).each(function(index, ele){
            app.bapiRender('searches', 'tmpl-searches-quickview', function(html){
                ele.innerHTML = html;
            }, {class: ele.dataset.class});

        });
    },
    cond: function cond() {
        return app.exists(this.selector);
    }
};
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
    initSlider: function (key, ele){
        var config = this.getSliderConfig(ele);
        app.initSwiper(ele.querySelector(this.sliderSelector), config);
    }
};
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

app.modules.widgets.menu = {
    selector: '.kd-menu',
    init: function () {
        var instances = document.querySelectorAll(this.selector);
        for(var i = 0; i < instances.length; i++) {
            this.bindEvents(instances.item(i));
        }
    },
    cond: function (){
        return app.exists(this.selector);
    },
    bindEvents: function (ele){
        var toggle = ele.querySelector('.toggle');
        toggle.addEventListener('click', this.toggleMenu.bind(ele));
    },
    toggleMenu: function(){
        this.classList.toggle('active');
    }
};
app.bapiModules.widgets.search = {
    selector: '.kd-search',
    init: function(){
        $(this.selector).each(function(index, ele){
            /* Set 'More' toggle events */
            ele.querySelector('.search-button-block .more').addEventListener('click', function(e){
                ele.classList.toggle('open');
                this.classList.toggle('active');
            });
        });
    },
    cond: function cond() {
        return app.exists(this.selector);
    }
};
app.bapiModules.widgets.specials = {
    selector: '.kd-specials',
    init: function(){
        $(this.selector).each(function(index, ele){
            app.bapiRender('specials', 'tmpl-specials-quickview', function(html){
                ele.innerHTML = html;
            }, {class: ele.dataset.class});
        });
    },
    cond: function cond() {
        return app.exists(this.selector);
    }
};
app.modules.widgets.team = {
    mobileBreak:    991,
    selector: '.kd-team',
    init: function(){
        var teamSliders = document.querySelectorAll(this.selector+'.swiper-container');

        for(var i = 0; i < teamSliders.length; i++){
            var swiperEle = teamSliders[i];
            var swiper = new Swiper(swiperEle,
                {
                    spaceBetween: 15,
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