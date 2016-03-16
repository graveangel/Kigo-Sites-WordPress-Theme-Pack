var kd_admin = {
    init: function () {
    },
    loadInit: function () {
        //TODO: clean up this mess
        try {
            this.initWidgetTabs();
            this.initNoUISliders();
            this.initMedia();
            this.initCustomSidebarsPage();
            this.initColorPickers();
            this.initAceEditors();
            this.initVirtualSidebars();
            this.initIconPickers();
            this.initSortables();
            this.multiSelects();
            this.multiSelectsCheckbox();
        } catch (e) {
            console.log(e);
        }
    },
    DOMReadyInit: function () {
        this.addSidebarControls();
        this.addWidgetControls();
        this.openAllSidebars();
    },
    initNoUISliders: function () {
        initSliders();
        noUISliderToggle();
    },
    initMedia: function () {
        initMediaOverlay();
        bindMediaItemEvents();
    },
    initCustomSidebarsPage: function () {
        customSidebarsEvents();
    },
    initColorPickers: function () {
        var aux = $("#widgets-right .colorPicker:not('.init')").wpColorPicker({
            border: 'none',
            width: '100%',
            palettes: false,
            mode: 'hsl',
            change: debounce(function () { //Debounce color change event
                $(this).trigger('change');
            }, 250)

        });
        aux.addClass('init');
    },
    initCKEditors: function () {
        $('#widgets-right .kd_editor').ckeditor(debounce(function(){
            this.on('change', function(){ 
                var changeEvent = new Event('change', {'bubbles': true});
                this.updateElement();
                this.element.$.dispatchEvent(changeEvent);
            });
        }, 1000));
    },
    initAceEditor: function () {
        if (!document.querySelectorAll('#custom_css').length)
            return;

        var editor = ace.edit("custom_css"),
            input = document.querySelector('#custom_css_input');

        editor.setTheme("ace/theme/monokai");
        editor.getSession().setMode("ace/mode/css");

        editor.resize();

        document.querySelector('.kd-options form').addEventListener('submit', function () {
            input.value = editor.getValue();
        });
    },
    initAceEditors: function () {
        var editors = document.querySelectorAll('.aceEditor');
        if (!editors.length)
            return;

        for (var i = 0; i < editors.length; i++) {

            var editor = ace.edit(editors[i]),
                mode = editors[i].dataset.mode,
                input = document.querySelector('#' + editors[i].dataset.input);

            editor.setTheme("ace/theme/monokai");
            editor.getSession().setMode("ace/mode/" + mode);
            editor.myInput = input;

            editor.on('change', function () {
                this.myInput.value = this.getSession().getValue();
            }.bind(editor));
        }

    },
    initAceHtmlEditor: function () {
        try {
            var htmleditors = document.getElementsByClassName('editor-ace-html');
            for (var e in htmleditors)
            {
                if ('object' === typeof htmleditors[e])
                {
                    var editor = ace.edit(htmleditors[e].id);
                    var editor_id = htmleditors[e].id;

                    editor.setTheme("ace/theme/monokai");
                    editor.getSession().setMode("ace/mode/html");

                    var textarea = $('textarea[name="' + editor_id + '"]').hide();
                    var textarea_id = textarea.attr('id');
                    editor.getSession().setValue(textarea.val());
                    editor.getSession().on('change', function () {
                        textarea.val(editor.getSession().getValue());

                        wp.customize(textarea_id, function (obj) {
                            obj.set(editor.getSession().getValue());
                        });
                    });
                }

                $('.go-fullscreen').on('click', function (e) {
                    e.preventDefault();
                    container = $(this).prev().toggleClass('editor-html-fullscreen');
                    $('.editor-ace-html').toggleClass('editor-html-fullscreen');
                    $('.ace_content').toggleClass('editor-html-fs');
                })

                $(document).keyup(function (e) {
                    if (e.keyCode == 27) {
                        container.toggleClass('editor-html-fullscreen');

                        $('.editor-ace-html').toggleClass('editor-html-fullscreen');
                        $('.ace_content').toggleClass('editor-html-fs');
                    }   // esc
                });
            }
        } catch (e) {

        }
    },
    initVirtualSidebars: function () {
        if (jQuery('.sortable').length) {
            jQuery('.sidebar-add').live('click', function (e) {
                addSidebarField(e);
            });
            jQuery('.rem-sidebar').live('click', function (e) {
                e.preventDefault();
                jQuery(e.target).parent().remove();
            });
            var sortable_el = document.getElementById('sortable');
            sortable = Sortable.create(sortable_el);

            //add saved elements

            fields.forEach(function (e, i, a) {
                addSidebarField(null, e, sortable_el);
            });
        }
    },
    initSortables: function () {
        $('.is_sortable').each(function (i, ele) {
            Sortable.create(ele, {
                onUpdate: function (e) {
                    var changeEvent = new Event('change', {'bubbles': true});
                    this.el.querySelector('input').dispatchEvent(changeEvent);
                }
            });
        });
    },
    initWidgetTabs: function () {
        var widgetTabs = document.querySelectorAll('.widget-tabs');

        /* Loop through all widget tabs */
        $.each(widgetTabs, function (key, ele) {
            var tabControls = ele.querySelector('.tab-controls');
            var tabContents = ele.querySelector('.tab-contents');

            /* We mark tabs as initialized to not reinitialize them on widget save */
            ele.classList.add('tabs-initialized');

            tabControls.addEventListener('click', function (e) {
                var target = e.target;

                for (var index = 0; (target = target.previousElementSibling) !== null; index++)
                    ;

                var activeControl = tabControls.querySelector('.active'),
                    activeContent = tabContents.querySelector('.active'),
                    currentControl = tabControls.children.item(index),
                    currentContent = tabContents.children.item(index);

                if (activeControl !== currentControl) {
                    activeControl.classList.remove('active');
                    activeContent.classList.remove('active');
                    currentControl.classList.add('active');
                    currentContent.classList.add('active');
                }
            });
        });
    },
    addSidebarControls: function () {
        /* Check if we're inside Widgets admin page */
        var isWidgetsPage = document.querySelectorAll('body.wp-admin.widgets-php').length > 0;
        if (!isWidgetsPage)
            return;

        /* Add sidebar controls */
        var widgetsRight = document.querySelector('#widgets-right');
        widgetsRight.insertAdjacentHTML('afterbegin',
            '<ul class="sidebar-controls">' +
            '<li data-filter="only-pages" class="active">Pages</li>' +
            '<li data-filter="only-header">Header</li>' +
            '<li data-filter="only-footer">Footer</li>' +
            '<li data-filter="only-side">Sidebars</li>' +
            '</ul>');

        /* Add event listeners & callbacks to the controls */
        var controls = widgetsRight.querySelectorAll('.sidebar-controls li');

        for (var i = 0; i < controls.length; i++) {
            var current = controls.item(i);
            controls.item(i).addEventListener('click', function () {
                if (this.dataset.filter) {
                    var active = widgetsRight.querySelector('.sidebar-controls li.active') || false;
                    if (active && active !== this) {
                        active.classList.remove('active');
                        this.classList.toggle('active');
                        widgetsRight.className = '';
                        widgetsRight.classList.add(this.dataset.filter);
                    }
                }
            });
        }
    },
    addWidgetControls: function () {

        /* Check if we're inside Widgets admin page */
        var isWidgetsPage = document.querySelectorAll('body.wp-admin.widgets-php').length > 0;
        if (!isWidgetsPage)
            return;

        var widgetsLeft = document.querySelector('#widgets-left'),
            leftSidebar = widgetsLeft.querySelector('#widgets-left .sidebar-description');

        var legendMarkup =
            '<ul class="widgetLegend">' +
            '<li data-type="default"><span>WordPress widget</span></li>' +
            '<li data-type="theme"><span>Theme widget</span></li>' +
            '<li data-type="kigo"><span>Kigo widget</span></li>' +
            '</ul>';

        leftSidebar.insertAdjacentHTML('beforeend', legendMarkup);

        /* Attach event listeners */

        $('.widgetLegend li').click(function(){
            var type = this.dataset.type;

            widgetsLeft.classList.toggle('filter_'+type);
            this.classList.toggle('active');
        });
    },
    initIconPickers: function () {
        /* Initialize Icon Pickers & listen for icon value update */
        $(':not(#widgets-left) .iconPicker').iconpicker({placement: 'top'}).on('iconpickerSetValue', function (e) {
            e.iconpickerInstance.element[0].nextSibling.className = 'fa fa-' + e.iconpickerInstance['iconpickerValue'];
        });
    },
    openAllSidebars: function () {
        var sidebars = document.querySelectorAll('#widgets-right .widgets-holder-wrap');

        $.each(sidebars, function (key, sidebar) {
            sidebar.classList.remove('closed');
        });
    },
    multiSelectsCheckbox: function () {

        $.each($('*[id*="kd_featured"] input[id*="userandom"]'), function (i, v) {
            if ($(this).parent().find('input[id*="userandom"]').attr('checked')) {
                $(this).parent().find('.multiselectjs').next().hide();
                $(this).parent().find('.multiselectjs').prev().hide();
                $(this).parent().find('.multiselectjs').prev().prev().hide();
            } else {
                $(this).parent().find('.multiselectjs').next().show();
                $(this).parent().find('.multiselectjs').prev().show();
                $(this).parent().find('.multiselectjs').prev().prev().show();
            }
        });

    },
    multiSelects: function () {

        $('.multiselectjs').multiSelect({
            selectableHeader: "<div class='custom-header'><a href='#' class='kd-button filled multiselect-js-selectall'>Select All</a></div><br><input type='text' class='search-input' autocomplete='on' placeholder='Search within options'><br><br>",
            selectionHeader: "<div class='custom-header'><a href='#' class='kd-button filled multiselect-js-deselectall'>Deselect All</a></div><br><input type='text' class='search-input' autocomplete='on' placeholder='Search within selected'><br><br>",
            afterInit: function (ms) {

                var that = this,
                    $selectableSearch = that.$selectableUl.prev().prev().prev(),
                    $selectionSearch = that.$selectionUl.prev().prev().prev(),
                    selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

                $('.multiselect-js-selectall').click(function (e) {
                    e.preventDefault();
                    that.select_all();
                });

                $('.multiselect-js-deselectall').click(function (e) {
                    e.preventDefault();
                    that.deselect_all();
                });

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function (e) {
                        if (e.which === 40) {
                            that.$selectableUl.focus();

                            return false;
                        }
                    });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function (e) {
                        if (e.which == 40) {
                            that.$selectionUl.focus();

                            return false;
                        }
                    });

            }
        });

        $(document).on('click', '*[id*="kd_featured"] input[id*="userandom"]', function (e) {
            if ($(this).attr('checked')) {
                $(this).parent().find('.multiselectjs').next().hide();
                $(this).parent().find('.multiselectjs').prev().hide();
                $(this).parent().find('.multiselectjs').prev().prev().hide();
            } else {
                $(this).parent().find('.multiselectjs').next().show();
                $(this).parent().find('.multiselectjs').prev().show();
                $(this).parent().find('.multiselectjs').prev().prev().show();
            }
        });
        $('.multiselectjs').multiSelect('refresh');

    }
};

window.addEventListener('load', kd_admin.loadInit.bind(kd_admin));
window.addEventListener('DOMContentLoaded', kd_admin.DOMReadyInit.bind(kd_admin));
kd_admin.init.bind(kd_admin)

/**
 * Initialize each noUISlider in widget options
 */
function initSliders() {
    var sliders = document.querySelectorAll('#widgets-right .nouislider');

    for (var i = 0; i < sliders.length; i++) {

        var slider = sliders.item(i);

        if (slider.noUiSlider != undefined) {
            slider.noUiSlider.destroy();
        }

        /* Create slider */
        noUiSlider.create(slider, {
            start: JSON.parse(slider.dataset.value), // Handle start position
            step: 1, // Slider moves in increments of '10'
            connect: 'lower', // Display a colored bar between the handles
            //direction: 'ltr', // Put '0' at the bottom of the slider
            //orientation: 'horizontal', // Orient the slider vertically
            //behaviour: 'tap-drag', // Move handle on tap, bar is draggable
            range: {// Slider can select 'a' to 'b'
                'min': Number(slider.dataset.min) || 3,
                'max': Number(slider.dataset.max) || 12
            }
            , pips: {// Show a scale with the slider
                mode: 'steps',
                density: 100
            }
        });
        /* Listen for changes & update input value */
        slider.noUiSlider.on('change', function (e) {
            var input = document.getElementById(this.dataset.id);
            input.value = this.noUiSlider.get();
            $(input).change(); //Fire change event on input to refresh customizer preview
        }.bind(slider));
    }

}

/**
 * Adds event listeners to widget size labels for noUISlider toggle
 */
function noUISliderToggle() {
    var labels = document.querySelectorAll('#widgets-right label[data-type="size"]');

    for (var i = 0; i < labels.length; i++) {
        labels.item(i).onclick = callback; // Use unique event handler binding to avoid re-initalization collisions.
    }

    function callback() {
        var control = document.querySelector('.nouislider[data-id="' + this.attributes.for.value + '"]');
        control.classList.toggle('visible');
        this.classList.toggle('active');
    }
}

/**
 * Initialize media button events & attachment collection handler
 */
function initMediaOverlay() {

    jQuery(document).on("click", ".upload_button", function (e) {
        e.preventDefault();
        var multiple = this.dataset.multiple == 1;
        var type = this.dataset.type || 'image';
        var dataName = multiple == 1 ? this.dataset.name + '[]' : this.dataset.name; // Expect an array of images
        var widgetMediaContainer = this.parentElement.querySelector('.widget-media');

        // Create the media frame.
        var file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select or upload',
            library: {
                type: type // Mime type
            },
            button: {
                text: 'Select' // Label
            },
            multiple: multiple == 1 ? true : false  // Set to true to allow multiple files to be selected
            //frame: 'manage'

        });

        // When an image is selected, run a callback.
        file_frame.on('select', function () {
            var attachments = file_frame.state().get('selection');
            // Iterate over every attachment and create a hidden input to store it's url
            attachments.each(function (attachment) {
                var url = attachment.toJSON().url;

                if (!multiple && document.querySelector('input[name="' + dataName + '"]') !== null) {
                    document.querySelector('input[name="' + dataName + '"]').value = url;
                    widgetMediaContainer.querySelector('.widget-media-item').style.backgroundImage = 'url(' + url + ')';
                    return;
                }

                var newInput = document.createElement('input'),
                    newContainer = document.createElement('div'),
                    newControl = document.createElement('div');

                // setup input for value storage
                newInput.setAttribute('type', 'hidden');
                newInput.setAttribute('name', dataName);
                newInput.value = url;

                // setup div for image & controls
                newContainer.setAttribute('style', "background-image: url('" + url + "')");
                newContainer.classList.add('widget-media-item');

                /* Change event to be usied in customizer */
                var changeEvent = new Event('change', {'bubbles': true});

                newControl.classList.add('delete');
                newControl.innerHTML = 'Delete';
                newControl.addEventListener('click', function () {
                    this.parentElement.dispatchEvent(changeEvent);
                    this.parentElement.remove();
                });

                newContainer.appendChild(newInput);
                newContainer.appendChild(newControl);

                this.parentElement.querySelector('.widget-media').appendChild(newContainer, this.parentElement.querySelector('.widget-media').firstChild);

                newInput.dispatchEvent(changeEvent);
            }.bind(this));
        }.bind(this));

        // Finally, open the modal
        file_frame.open();
    });
}

/**
 * Widget media attachments delete event
 */
function bindMediaItemEvents() {
    jQuery('.widget-media-item .delete').on('click', function () {
        this.parentElement.remove();
    });
}

/**
 * Initialize custom sidebars page events
 */
function customSidebarsEvents() {
    var button = document.querySelector('.sidebar-generator #generator_button'),
        input = document.querySelector('.sidebar-generator #generator_input'),
        sidebarsContainer = document.querySelector('.sidebars-container');

    if (!button)
        return;

    // Add button events
    button.addEventListener('click', function () {
        var value = input.value.trim();

        if (value.length > 0) {
            //create input element, assign value, append to page
            var newContainer = document.createElement('div');
            var newButton = document.createElement('button');
            var newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.name = 'custom_sidebars[]';
            newInput.value = value;

            newButton.classList.add('button', 'button-secondary');
            newButton.type = 'button';
            newButton.innerText = 'Delete';

            newButton.addEventListener('click', function () {
                this.parentElement.remove()
            });

            newContainer.appendChild(newInput);
            newContainer.appendChild(newButton);
            sidebarsContainer.appendChild(newContainer);

            // Reset input value to prevent duplicate additions
            input.value = '';
        }
    });

    //Existing sidebars delete
    var existingButtons = document.querySelectorAll('.sidebars-container button');
    Array.prototype.forEach.call(existingButtons, function (ele) {
        ele.addEventListener('click', function () {
            this.parentElement.remove()
        });
    });
}

/**
 * virtual sidebar add fields
 */
function addSidebarField(e, v, t) {//<-Do not remove function name!!
    if (!t)
        e.preventDefault();

    //elements box
    var sbfbx = document.createElement("li");
    sbfbx.className = "sidebar-field-box sortable handle";
    sbfbx.style.padding = "10px";
    sbfbx.style.backgroundColor = "#fafafa";
    sbfbx.style.display = "block";

    //sidebar name label
    var sbfl = document.createElement('label');
    sbfl.style.display = "block";
    sbfl.className = "handle";
    var labeltext = document.createTextNode('Name:');
    sbfl.appendChild(labeltext);
    sbfbx.appendChild(sbfl);

    //sidebar name field
    var sbf = document.createElement('input');
    sbf.name = "post-sidebar[name][]";
    sbf.type = "text";
    sbf.style.display = "block";
    sbf.value = v ? v.value : "";
    sbfbx.appendChild(sbf);

    //sidebar width desktop label
    var sbfwl = document.createElement('label');
    sbfwl.style.display = "block";
    sbfwl.className = "handle";
    var labeltextw = document.createTextNode('Desktop Width:');
    sbfwl.appendChild(labeltextw);
    sbfbx.appendChild(sbfwl);

    //sidebar width desktop field
    var sbfwb = document.createElement('div');
    sbfwb.style.display = "block";
    sbfwb.className = "sidebar-width-box nouislider";
    sbfbx.appendChild(sbfwb);

    var sbfw = document.createElement('input');
    sbfw.name = "post-sidebar[width][]";
    sbfw.type = "hidden";
    sbfw.style.display = "block";
    sbfw.value = v ? v.width : "";
    sbfw.className = "sidebar-width";
    sbfbx.appendChild(sbfw);

    //sidebar width mobile label
    var sbfwlm = document.createElement('label');
    sbfwlm.style.display = "block";
    sbfwlm.className = "handle";
    var labeltextw = document.createTextNode('Mobile Width:');
    sbfwlm.appendChild(labeltextw);
    sbfbx.appendChild(sbfwlm);

    //sidebar width mobile field
    var sbfwbm = document.createElement('div');
    sbfwbm.style.display = "block";
    sbfwbm.className = "sidebar-width-mobile-box nouislider";
    sbfbx.appendChild(sbfwbm);

    var sbfwm = document.createElement('input');
    sbfwm.name = "post-sidebar[widthmob][]";
    sbfwm.type = "hidden";
    sbfwm.style.display = "block";
    sbfwm.value = v ? v.widthmob : "";
    sbfwm.className = "sidebar-width-mobile";

    sbfbx.appendChild(sbfwm);

    //delete button
    var rembt = document.createElement("a");
    txtnd = document.createTextNode("- Sidebar");
    rembt.className = "rem-sidebar";
    rembt.href = "#";
    rembt.style.display = "block";
    rembt.appendChild(txtnd);
    sbfbx.appendChild(rembt);

    sortable.destroy();
    if (t)
        t.appendChild(sbfbx);
    else
        e.target.parentNode.parentNode.appendChild(sbfbx);

    var nonLinearStepSlider = sbfwb;

    noUiSlider.create(nonLinearStepSlider, {
        start: sbfw.value ? sbfw.value : 100, // Handle start position
        step: 10, // Slider moves in increments of '10'
        margin: 3, // Handles must be more than '20' apart
        direction: 'ltr', // Put '0' at the bottom of the slider
        orientation: 'horizontal', // Orient the slider vertically
        // behaviour:   'tap-drag', // Move handle on tap, bar is draggable
        range: {// Slider can select 'a' to 'b'
            'min': 0,
            'max': 100
        },
        pips: {// Show a scale with the slider
            mode: 'steps',
            density: 10
        }
    });

    nonLinearStepSlider.noUiSlider.on('update', function (values, handle) {
        sbfw.value = values[handle];
    });

    var nonLinearStepSliderm = sbfwbm;

    noUiSlider.create(nonLinearStepSliderm, {
        start: sbfwm.value ? sbfwm.value : 100, // Handle start position
        step: 10, // Slider moves in increments of '10'
        margin: 3, // Handles must be more than '20' apart
        direction: 'ltr', // Put '0' at the bottom of the slider
        orientation: 'horizontal', // Orient the slider vertically
        // behaviour:   'tap-drag', // Move handle on tap, bar is draggable
        range: {// Slider can select 'a' to 'b'
            'min': 0,
            'max': 100
        },
        pips: {// Show a scale with the slider
            mode: 'steps',
            density: 10
        }
    });

    nonLinearStepSliderm.noUiSlider.on('update', function (values, handle) {
        sbfwm.value = values[handle];
    });

    var sortable_el = document.getElementById('sortable');
    sortable = Sortable.create(sortable_el);
}

/* Debounce util */
function debounce(func, wait, immediate) {
    var timeout;
    return function () {
        var context = this, args = arguments;
        var later = function () {
            timeout = null;
            if (!immediate)
                func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow)
            func.apply(context, args);
    };
}
;

function setFont(e) {
    if (!e.target.value)
        return;
    var styleid = '#style_' + e.target.id;
    var previewid = '#preview_' + e.target.id;
    var importval = '@import url(https://fonts.googleapis.com/css?family=' + e.target.value.split(' ').join('+') + ');';
    $(styleid).html(importval);
    $(previewid).html(e.target.value).css('font-family', e.target.value);
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ')
            c = c.substring(1);
        if (c.indexOf(name) == 0)
            return c.substring(name.length, c.length);
    }
    return "";
}


