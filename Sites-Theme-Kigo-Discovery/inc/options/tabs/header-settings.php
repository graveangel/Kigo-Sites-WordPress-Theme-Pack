<form method="post">
    <div class="header-proportions">
        <h3>Header sidebar proportions:</h3>
        <p><strong>Left header / Right header</strong> proportions (12 column max between both)</p>
        <input name="hcols" type="hidden" id="hcols" value="<?php echo get_theme_mod('hcols') ?>" \>
        <div data-id="hcols" data-value="<?php echo get_theme_mod('hcols') ?>" class="nouislider"></div>
    </div>
    <div class="underheader-proportions">
        <h3>Under header sidebar proportions:</h3>
        <p><strong>Left under header / Right under header</strong> proportions (12 column max between both)</p>
        <input name="uhcols" type="hidden" id="uhcols" value="<?php echo get_theme_mod('uhcols') ?>" \>
        <div data-id="uhcols" data-value="<?php echo get_theme_mod('uhcols') ?>" class="nouislider"></div>
    </div>
    <br/>
    <input type="submit" value="Save" class="kd-button filled">
</form>

<script>
    window.addEventListener('DOMContentLoaded', function(){
        var sliders = document.querySelectorAll('.nouislider');

        for (var i = 0; i < sliders.length; i++) {

            var slider = sliders.item(i);

            if (slider.noUiSlider != undefined) {
                slider.noUiSlider.destroy();
            }

            /* Create slider */
            noUiSlider.create(slider, {
                start: slider.dataset.value, // Handle start position
                step: 1, // Slider moves in increments of '10'
                connect: 'lower', // Display a colored bar between the handles
                direction: 'ltr', // Put '0' at the bottom of the slider
                orientation: 'horizontal', // Orient the slider vertically
                //behaviour: 'tap-drag', // Move handle on tap, bar is draggable
                range: {// Slider can select 'a' to 'b'
                    'min': 0,
                    'max': 12
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
    });
</script>
