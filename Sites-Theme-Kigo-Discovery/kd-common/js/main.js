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

        var body = document.querySelector('body'),
            header = document.querySelector('.header-background'),
            underHeader = document.querySelector('.header-background .under_header');

        var scrollMax = header.clientHeight - underHeader.clientHeight;

        //Listen page scroll to set / unset fixed header. Debounce scroll event.
        window.addEventListener('scroll', checkHeader); //ms

        checkHeader(); //Check in case page has loaded with scroll

        function checkHeader(){
            var currentScroll = window.scrollY;
            
            if(currentScroll >= scrollMax){
                header.classList.add('fixed');
                //body.style.paddingTop = underHeader.clientHeight + 'px';
            }
            else{
                header.classList.remove('fixed');
                //body.style.paddingTop = 0;
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
            var options = auxOptions || {pagesize: 20, waitForAll: false};
            BAPI.search(entity, options, function (sdata) {
                var pages = _.chunk(sdata.result, options.pagesize);

                if(options.waitForAll == 1){
                    var all = [], iterations = 0;
                    pages.forEach(function(page, i){
                        BAPI.get(page, entity, options, function (gdata) {
                            iterations++;
                            all = _.concat(all, gdata.result);

                            if(iterations == (pages.length)){ //Last iteration
                                callback({result: all, textdata: BAPI.textdata});
                            }
                        });
                    });
                }else{
                    pages.forEach(function(page, i){
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
/* (The MIT License)
 *
 * Copyright (c) 2012 Brandon Benvie <http://bbenvie.com>
 */

void function(c,h,a){function define(c,a,b){return typeof a==='function'&&(b=a,a=nameOf(b).replace(/_$/,'')),e(c,a,{configurable:!0,writable:!0,value:b})}function nameOf(a){return typeof a!=='function'?'':'name'in a?a.name:i.call(a).match(j)[1]}function createStorage(b){function storage(e,d){return d||arguments.length===2?c.set(e,d):(d=c.get(e),d===a&&(d=b(e),c.set(e,d))),d}var c=new WeakMap;return b||(b=k),storage}var l=Object.getOwnPropertyNames,e=Object.defineProperty,i=Function.prototype.toString,f=Object.create,g=Object.prototype.hasOwnProperty,j=/^\n?function\s?(\w*)?_?\(/;var b=function(){function b(){var a=d(),c={};this.unlock=function(h){var b=k(h);if(g.call(b,a))return b[a](c);var d=f(null,i);return e(b,a,{value:new Function('s','l',j)(c,d)}),d}}var i={value:{writable:!0,value:a}},j='return function(k){if(k===s)return l}',h=f(null),d=function(){var a=Math.random().toString(36).slice(2);return a in h?d():h[a]=a},c=d(),k=function(a){if(g.call(a,c))return a[c];if(!Object.isExtensible(a))throw new TypeError('Object must be extensible');var b=f(null);return e(a,c,{value:b}),b};return define(Object,function getOwnPropertyNames(b){var a=l(b);return g.call(b,c)&&a.splice(a.indexOf(c),1),a}),define(b.prototype,function get(a){return this.unlock(a).value}),define(b.prototype,function set(a,b){this.unlock(a).value=b}),b}();var d=function(i){function WeakMap(a){if(this===c||this==null||this===WeakMap.prototype)return new WeakMap(a);l(this,new b),m(this,a)}function get(b){e(b);var c=d(this).get(b);return c===h?a:c}function set(b,c){e(b),d(this).set(b,c===a?h:c)}function has(b){return e(b),d(this).get(b)!==a}function delete_(b){e(b);var c=d(this),f=c.get(b)!==a;return c.set(b,a),f}function toString(){return d(this),'[object WeakMap]'}var e=function(a){if(a==null||typeof a!=='object'&&typeof a!=='function')throw new TypeError('Invalid WeakMap key')};var l=function(b,c){var a=i.unlock(b);if(a.value)throw new TypeError('Object is already a WeakMap');a.value=c};var d=function(b){var a=i.unlock(b).value;if(!a)throw new TypeError('WeakMap is not generic');return a};var m=function(b,a){a!==null&&typeof a==='object'&&typeof a.forEach==='function'&&a.forEach(function(c,d){c instanceof Array&&c.length===2&&set.call(b,a[d][0],a[d][1])})};try{var f=('return '+delete_).replace('e_','\\u0065'),j=new Function('unwrap','validate',f)(d,e)}catch(a){var j=delete_}var f=(''+Object).split('Object');var g=function toString(){return f[0]+nameOf(this)+f[1]};define(g,g);var k={__proto__:[]}instanceof Array?function(a){a.__proto__=g}:function(a){define(a,g)};return k(WeakMap),[toString,get,set,has,j].forEach(function(a){define(WeakMap.prototype,a),k(a)}),WeakMap}(new b);var k=Object.create?function(){return Object.create(null)}:function(){return{}};typeof module!=='undefined'?module.exports=d:typeof exports!=='undefined'?exports.WeakMap=d:!('WeakMap'in c)&&(c.WeakMap=d),d.createStorage=createStorage,c.WeakMap&&(c.WeakMap.createStorage=createStorage)}((0,eval)('this'))

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

/**
 * Created by JVengoechea on 29/09/2016.
 */
app.bapiModules.templates.makeBooking =
{
    init: function()
    {
        // Dismiss modal with updating dates.
        $(document).on('click', '.bapi-revisedates', function(e)
        {
            $('.modal').modal('hide');
        });
    },
    cond: function cond() {

        if(app.exists('.bapi-bookingform'))
        {
            this.init();
        }
    }
}
app.bapiModules.templates.propertyDetails = {
    forceusemap: false,
    mobileBreak: 768,
    init: function () {
        if (this.cond())
        {
            this
                //          .fixHeroImage()
                .openCloseAmenitiesList()
                .lightBoxAndCarousel()
                .checkPropSettings()
                //          .checkThumbs()
                .checkUseMap();
        }
    },
    lightBoxAndCarousel: function lightBoxAndCarousel() {

        var gallery = jQuery('.ppt-slides a').simpleLightbox({
            captionSelector: 'self'
        });

        jQuery(document).on('click', '.more,.open-lightbox, .ppt-slides a', function (e) {
            e.preventDefault();
            if (typeof jQuery(this).attr('data-index') !== "undefined") {
                var data_index = jQuery(this).attr('data-index');
                gallery.open(jQuery(jQuery('.ppt-slides a[data-index="' + data_index + '"]')));
            }
            else
                gallery.open(jQuery(jQuery('.ppt-slides a')[0]));
        });

        if(window.innerWidth <=  768){
            this.swiperCarousel();
        }

        return this;
    },
    swiperCarousel: function swiperCarousel() {
        if (typeof SWCarousel === 'undefined' || !SWCarousel)
        {
            SWCarousel = new Swiper('.ppt-images', {
                spaceBetween: 0,
                nextButton: '.next-slide',
                prevButton: '.prev-slide',
                slidesPerView: 1
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

app.bapiModules.templates.searchPage = {
    /* Element selectors */
    defaultView: '',
    templateSelector: 'body.page-template-search-page',
    mapEle: document.querySelector('#mapContainer'),
    listInitted: false,
    mapInitted: false,
    /* Properties containers */
    mapPropContainer: document.querySelector('#mapPropertiesContainer'),
    listPropContainer: document.querySelector('#listPropertiesContainer'),
    properties: [],
    totalProps: 0,
    /* Map */
    mapObj: null,
    clustererObj: null,
    spiderfyObj: null,
    bounds: null,

    markers: [],
    propMarkers: {},
    openMarkers: [],
    currentViewMarkers: [],

    /* Methods */
    cond: function(){
        return document.querySelectorAll(this.templateSelector).length > 0;
    },
    init: function(){
        this.viewToggle();
        this.defaultView = BAPI.config().defaultsearchresultview;

        switch(this.defaultView){
            case 'tmpl-propertysearch-listview':
                this.doListView();
                break;
            case 'tmpl-propertysearch-mapview':
            default:
                this.doMapView();
                break;
        }

        this.mapResetEvents();
    },
    getProperties: function(success_callback, empty_callback){
        var chunkSize = 20;

        if(this.properties.length){
            this.properties.forEach(function(prop, prop_i){

                success_callback.call(this, prop, prop_i);

                this.updateCounters();

            }.bind(this));
        }else {

            app.bapi.search('property', function (sr) {
                var ids = sr.result, total = sr.result.length;

                /* Here we have ppty total amount */
                this.totalProps = total;
                this.updateCounters();

                if(total == 0){
                    empty_callback.call(this, {}, 0);
                    return;
                }

                //Split property id's into page-sized chunks
                var chunks = _.chunk(ids, chunkSize);

                chunks.forEach(function (chunk, chunk_i) {

                    BAPI.session.searchparams.pagesize = chunkSize;
                    BAPI.session.searchparams.seo = true;

                    app.bapi.get('property', chunk, function (gr) {
                        gr.result.forEach(function (prop, prop_i) {
                            //Store recovered properties
                            this.properties = _.concat(this.properties, [prop]);

                            success_callback.call(this, prop, prop_i);
                            this.updateCounters();

                        }.bind(this));

                    }.bind(this), BAPI.session.searchparams );

                }.bind(this));

            }.bind(this), BAPI.session.searchparams);

        }
    },
    /* Map view */
    updateCounters: function(){
        var current = this.properties.length;
        var percentage = current * 100 / this.totalProps;
        $('.map .loader .bar').css('width', percentage+'%');
        $('.ppty-count-current').text(current);
        $('.ppty-count-total').text(this.totalProps);
    },
    initMap: function(latitude, longitude){
        var defaultMapView = BAPI.config().mapviewType;

        var mapStyles = [
            {
                "featureType": "landscape",
                "stylers": [
                    {
                        "hue": "#FFBB00"
                    },
                    {
                        "saturation": 43.400000000000006
                    },
                    {
                        "lightness": 37.599999999999994
                    },
                    {
                        "gamma": 1
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "stylers": [
                    {
                        "hue": "#FFC200"
                    },
                    {
                        "saturation": -61.8
                    },
                    {
                        "lightness": 45.599999999999994
                    },
                    {
                        "gamma": 1
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "stylers": [
                    {
                        "hue": "#FF0300"
                    },
                    {
                        "saturation": -100
                    },
                    {
                        "lightness": 51.19999999999999
                    },
                    {
                        "gamma": 1
                    }
                ]
            },
            {
                "featureType": "road.local",
                "stylers": [
                    {
                        "hue": "#FF0300"
                    },
                    {
                        "saturation": -100
                    },
                    {
                        "lightness": 52
                    },
                    {
                        "gamma": 1
                    }
                ]
            },
            {
                "featureType": "water",
                "stylers": [
                    {
                        "hue": "#0078FF"
                    },
                    {
                        "saturation": -13.200000000000003
                    },
                    {
                        "lightness": 2.4000000000000057
                    },
                    {
                        "gamma": 1
                    }
                ]
            },
            {
                "featureType": "poi",
                "stylers": [
                    {
                        "hue": "#00FF6A"
                    },
                    {
                        "saturation": -1.0989010989011234
                    },
                    {
                        "lightness": 11.200000000000017
                    },
                    {
                        "gamma": 1
                    }
                ]
            }
        ];

        this.mapObj = new google.maps.Map(this.mapEle, {
            center: {lat: latitude, lng: longitude},
            zoom: 8,
            styles: mapStyles,
            mapTypeId: google.maps.MapTypeId[defaultMapView]
        });
    },

    initClusterer: function(){
        var size = [53, 56, 66, 78, 90];
        var clusterStyles = [
            {
                url: '/wp-content/themes/Sites-Theme-Kigo-Discovery/kd-common/img/markers/m1.png',
                height: size[0],
                width: size[0]
            },
            {
                url: '/wp-content/themes/Sites-Theme-Kigo-Discovery/kd-common/img/markers/m2.png',
                height: size[1],
                width: size[1]
            },
            {
                url: '/wp-content/themes/Sites-Theme-Kigo-Discovery/kd-common/img/markers/m3.png',
                height: size[2],
                width: size[2]
            },
            {
                url: '/wp-content/themes/Sites-Theme-Kigo-Discovery/kd-common/img/markers/m4.png',
                height: size[3],
                width: size[3]
            },
            {
                url: '/wp-content/themes/Sites-Theme-Kigo-Discovery/kd-common/img/markers/m5.png',
                height: size[4],
                width: size[4]
            }
        ];

        var mcOptions = {gridSize: 50, maxZoom: 13, styles: clusterStyles};
        this.clustererObj = new MarkerClusterer(this.mapObj, this.markers, mcOptions);
    },
    initSpiderfy: function(){
        this.spiderfyObj = new OverlappingMarkerSpiderfier(this.mapObj, {markersWontMove: true, markersWontHide: true, keepSpiderfied: true, legWeight : 2});
    },
    addMarker: function(prop){

        /* Create info window */
        var infoWindow = new google.maps.InfoWindow({
            content: '<div class="info-html prop-infowindow">'+
            '<a href="' + prop.ContextData.SEO.DetailURL + '" class="image" style="background-image: url(' + prop.PrimaryImage.ThumbnailURL + ')">'+
            '</a><div class="info">' +
            '<h5 class="title">' + prop.Headline + '</h5>'
            + prop.Location + "</div></div>"
        });

        /* Create marker + store info window inside for later use (also in property ele) */

        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(prop.Latitude, prop.Longitude),
            prop: prop,
            map: this.mapObj,
            iw: infoWindow,
            icon: {
                path: "M-0.5-41C-7.9-41-14-34.9-14-27.5c0,3,1.9,7.9,5.9,15c2.8,5,5.5,9.2,5.6,9.3l2,3l2-3c0.1-0.2,2.9-4.3,5.6-9.3"+
                "c3.9-7.1,5.9-12,5.9-15C13-34.9,7-41-0.5-41z M-0.5-20.6c-3.9,0-7-3.1-7-7s3.1-7,7-7s7,3.1,7,7S3.4-20.6-0.5-20.6z",
                fillColor: this.mapEle.dataset.color,
                fillOpacity: 1,
                strokeWeight: 0
            }
        });

        //Standard event handling
        /* Add event listeners to show info window */
        marker.addListener('click', this.openMarker.bind(this, marker));

        /* Add marker to Clusterer */
        this.clustererObj.addMarker(marker);

        /* Add marker to spidify */
        this.spiderfyObj.addMarker(marker);
        //Spidify event handling
        this.spiderfyObj.addListener('click', function(marker) {
            this.openMarker(marker);
        }.bind(this));

        /* We store markers for later use */
        this.markers.push(marker);
        this.propMarkers[prop.AltID] = marker;
    },
    openMarker: function(marker){
        /* first, we close any open marker InfoWindows */
        this.openMarkers.map(function(m){m.iw.close()});
        /* then we can open the new marker InfoWindow */
        this.mapObj.setZoom(15);

        var adjustedPos = new google.maps.LatLng({lat: marker.getPosition().lat() + 0.004623495678337974, lng: marker.getPosition().lng()});
        this.mapObj.panTo(adjustedPos);

        _.delay(function(){
            marker.iw.open(this.mapObj, marker);
        }.bind(this), 150);
        /* we store the open InfoWindows to keep track */
        this.openMarkers.push(marker);
    },
    addMapProps: function(properties){
        //Render properties
        var propHTML = app.bapi.render('tmpl-propertysearch-mapview', {result: properties, textdata: BAPI.textdata});
        this.mapPropContainer.innerHTML = propHTML;

        //Attach event listeneers
        var props = this.mapPropContainer.querySelectorAll('[data-altid]');

        _(props).forEach(function(prop){
            var markerToggle = prop.querySelector('.viewInMap');
            var altid = prop.dataset.altid;
            markerToggle.addEventListener('click', function(){
                var marker = this.propMarkers[altid];
                this.openMarker(marker);
            }.bind(this));
        }.bind(this));
    },
    mapResetEvents: function(){
        var eles = document.querySelectorAll('.resetMap');

        _.map(eles, function(ele){
            ele.addEventListener('click', this.centerMap.bind(this));
        }.bind(this));
    },
    centerMap: function(){

        if(this.bounds == null) {
            var bounds = new google.maps.LatLngBounds();
            var markers = this.markers;
            /* Extend bounds to all markers and fit view */
            for (i in markers) {
                var data = markers[i];
                bounds.extend(new google.maps.LatLng(data.position.lat(), data.position.lng()));
            }
            this.bounds = bounds;
            this.mapObj.fitBounds(this.bounds);
        }else{
            /* We revert to the initial map state */
            this.mapObj.fitBounds(this.bounds);
        }
    },
    mapBoundProps: function(){
        /* listen events for loading ui */
        google.maps.event.addListener(this.mapObj, 'dragstart', function(){
            this.mapPropContainer.classList.add('loading');
        }.bind(this));

        google.maps.event.addListener(this.mapObj, 'idle', function(){
            this.mapPropContainer.classList.remove('loading');
        }.bind(this));

        /* on map move (bounds change) we check to see what markers are visible to display related props */
        google.maps.event.addListener(this.mapObj, 'bounds_changed',
            _.debounce(
                function() {
                    this.currentViewMarkers = [];
                    this.markers.forEach(function(marker){
                        if(this.mapObj.getBounds().contains(marker.getPosition())){
                            this.currentViewMarkers.push(marker);
                        }
                    }.bind(this));

                    var visibleProps = [];

                    /* Grab visible marker properties */
                    this.currentViewMarkers.forEach(function(m){
                        visibleProps.push(m.prop);
                    });
                    this.addMapProps(visibleProps); //Render them

                    $('.ppty-count-current').text(visibleProps.length);
                }.bind(this), 250)
        );
    },
    /* View initializers */
    doMapView: function(){
        /* Update view layout */
        document.querySelector('.listView').classList.add('hidden');
        document.querySelector('.mapView').classList.remove('hidden');
        //document.querySelector('.viewToggle .v-map').classList.add('active');

        if(this.mapInitted){
            this.centerMap();
            return;
        }

        this.getProperties(function(prop, prop_i){
            //Search has returned properties

            if(this.markers.length == 0){
                //First iteration, we can initialize map on first location coordinates
                //Also initialize Marker Clusterer to start adding markers to it

                this.initMap(prop.Latitude, prop.Longitude);
                this.initClusterer();
                this.initSpiderfy();
                this.mapBoundProps();
            }

            this.addMarker(prop);

            //Last marker iteration
            if( this.markers.length == this.totalProps ){ //We check markers array, as properties can exist before we're in Map view
                this.centerMap();
                this.mapInitted = true;
                _.map(document.querySelectorAll('.viewToggle button'), function(button){button.removeAttribute('disabled')});
            }
        }, function(){
            //Search has returned no properties (empty)
            this.addMapProps(this.properties);
        });
    },
    doListView: function(){
        /* Update view layout */
        document.querySelector('.mapView').classList.add('hidden');
        document.querySelector('.listView').classList.remove('hidden');

        if(this.listInitted)return;

        this.getProperties(function(prop, prop_i){
                //Search has returned properties

                prop.Summary = prop.Summary.length <= 200
                    ? prop.Summary
                    : prop.Summary.replace(/(<([^>]+)>)/ig,"").substr(0, 200) + '... <a href="'+prop.ContextData.SEO.DetailURL+'">['+BAPI.textdata.more+']</a>';

                var propHTML = app.bapi.render('tmpl-propertysearch-listview', {result: [prop], textdata: BAPI.textdata});
                this.listPropContainer.innerHTML += propHTML;

                if(this.properties.length == this.totalProps){
                    _.map(document.querySelectorAll('.viewToggle button'), function(button){button.removeAttribute('disabled')});
                }
            },
            function(){
                //Search has returned no properties (empty)

                var propHTML = app.bapi.render('tmpl-propertysearch-listview', {result: [], textdata: BAPI.textdata});
                this.listPropContainer.innerHTML += propHTML;
            });

        this.listInitted = true;
    },
    /* View toggle */
    viewToggle: function(){
        /* Hide toggle if view options are disabled */
        var list_v = document.querySelector('.viewToggle .v-list');
        var map_v  = document.querySelector('.viewToggle .v-map');

        if(!BAPI.config().defaultsearchresultview)
        {

            list_v.parentNode.removeChild(list_v);
            map_v.parentNode.removeChild(map_v);
        }
        /* If only one list view is selected then only show list */
        else if(BAPI.config().searchmodes.listview && !BAPI.config().searchmodes.mapview)
        {
            map_v.parentNode.removeChild(map_v);
        }
        /* If only one list mapview is selected then only show map */
        else if(!BAPI.config().searchmodes.listview && BAPI.config().searchmodes.mapview)
        {
            list_v.parentNode.removeChild(list_v);
        }
        else
        {
            /* View toggle buttons */
            document.querySelector('.viewToggle .v-map').addEventListener('click', this.doMapView.bind(this));
            document.querySelector('.viewToggle .v-list').addEventListener('click', this.doListView.bind(this));
        }


    }

};
app.bapiModules.widgets.buckets = {
    selector: '.kd-buckets',
    init: function(){
        $(this.selector).each(function(index, ele){
            app.bapi.recursiveGet('searches',  function(data){
                var finalData = Object.assign(data, {class: ele.dataset.class});
                ele.innerHTML = app.bapi.render('tmpl-searches-quickview', finalData);
            });
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

app.modules.widgets.map = {
    selector: '.kd-map .map-canvas',
    init: function(){
        geocoder = new google.maps.Geocoder();
        $(this.selector).each(function(index, ele){
            if (geocoder) {
                geocoder.geocode( { 'address': ele.dataset.location}, function(result, status) {
                    var location = result[0].geometry.location;
                    var map = new google.maps.Map(ele, {
                        center: location,
                        zoom: ele.dataset.zoom * 1 || 10
                    });

                    var marker = new google.maps.Marker({
                        map: map,
                        position: location
                    });
                });
            }
        });
    },
    cond: function cond() {
        return app.exists(this.selector);
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
app.bapiModules.widgets.selective_search = {
    selector: '.kd-selective-search-box',
    init: function(){

            /* Set 'More' toggle events */
            $('.kd-selective-search-box .toggle-filter').on('click', function(e)
            {
                e.preventDefault();

                $(this).toggleClass('active');

                    var types_field        = $($(this).attr('data-types'));
                    var post_types_filters = $(this).parent().parent().find('.toggle-filter');
                    var checkbox           = $(this).prev();

                    checkbox.prop('checked',!checkbox.prop('checked'));

                    var active_types= [];

                    $.each(post_types_filters, function(e,i)
                    {
                        if($(this).hasClass('active'))

                        active_types.push($(this).attr('data-toggle'))
                    });

                    types_field.get(0).value = active_types.join(',');
            });

            /* Filter by*/
            $('.filter-by').on('click', function(e)
            {
                e.preventDefault();
                $(this).toggleClass('active');
                //Preveinting to close when clicked
                $(this).parent().on('click', function(e)
                {
                    e.stopPropagation();
                });

                $(this).parent().next().toggleClass('active');

            });

            /* Clear filters button */
            $('.clearsearch').on('click', function(e)
            {
                e.preventDefault();
                $('.kd-selective-search-box .toggle-filter').removeClass('active');
                var types_field = $($(this).attr('data-types'));

                /* Checkboxes */
                var checkboxes = $(this).parent().parent().find('.ptype input');
                    checkboxes.prop('checked', false);

                types_field.get(0).value = '';
            });

            /* Click outside */
            $(window).on('click', function(e)
            {
                $('.currently-filtering').removeClass('active');
                $('.filter-by').removeClass('active');
            });


            $('.currently-filtering').on('click', function(e)
            {
                e.stopPropagation();
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