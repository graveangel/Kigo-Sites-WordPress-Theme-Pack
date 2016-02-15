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