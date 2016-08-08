var instaparent = {
    init: function(){
        this.landingForm();
    },
    landingForm: function(){
        var fields = document.querySelectorAll('[data-meta]');
        var form = document.querySelector('#landing_form');

        if(!form)return;

        fields.forEach(function(field){
            field.contentEditable = true;
            field.addEventListener('input', function(e){
                e.preventDefault();

                var input = document.querySelector('[name="'+this.dataset.meta+'"]');

                input.value = this.innerHTML;
            });
        });

        var saveButton = document.querySelector('#wp-admin-bar-save-landing-content');
        saveButton.addEventListener('click', function(e){
            form.submit();
        });
    }
}

window.addEventListener('DOMContentLoaded', instaparent.init.bind(instaparent));