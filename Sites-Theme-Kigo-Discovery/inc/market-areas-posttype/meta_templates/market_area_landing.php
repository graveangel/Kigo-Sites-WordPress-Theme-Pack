<!-- form token -->
<?php wp_nonce_field(basename(__FILE__), $this->id . '_nonce'); ?>
<!-- Field description -->
<label for="<?php echo $this->id; ?>"><p><?php _e($this->description, 'kd'); ?></p></label>

<?php

    $value = json_decode(stripcslashes(get_post_meta($object->ID, $this->id, true)), true); // The field value.

    $use_landing        = $value['landing']; // Weather to use a landing or the default one
    $template_selected  = $value['template']; //

    $checked            = $use_landing ? 'checked' : '';
?>


    <input  type="hidden" name="<?php echo $this->id; ?>"  value="<?php echo $value; ?>"/>

    <input class="widefat" type="checkbox" id="<?php echo $this->id; ?>_check" name="<?php echo $this->id; ?>_check" <?php echo $checked; ?> />

    <label for="<?php echo $this->id; ?>_check"> <b>Use landing</b></label>
    <br>
    <?php
        $templates_info = json_decode(get_theme_mod('market-areas-templates-info'), true); //Templates info if available
    ?>
    <!-- The templates -->
    <div class="templates-info">
        <h3>Select the template</h3>
        <select name="<?php echo $this->id; ?>_select" id="<?php echo $this->id; ?>_select">
            <?php
                asort($templates_info); // sorting the info array so the default template is first in the list

                foreach($templates_info as $templatename => $info)
                {
                    $selected = '';
                    if($template_selected === $templatename)
                        $selected = 'selected';
                    $temp_name = $info['template'] ? : $templatename;
                    echo "<option value=\"$templatename\" $selected>$temp_name</option>";
                }

            ?>
        </select>
    </div>

    <div class="templates-preview"></div>

    <script type="text/javascript">

        //The templates info.
        var templates_info = <?php echo json_encode($templates_info); ?>;
        $(function()
        {
            $('#<?php echo $this->id; ?>_select').change(update_preview);


            $('#<?php echo $this->id; ?>_check').change(function()
            {
                $('#<?php echo $this->id; ?>_select').change();//Trigger change on load.
            });

            $('#<?php echo $this->id; ?>_check').change();


        });

        function update_preview(e)
        {
            var preview_box = $('.templates-preview');
            var to_append = '';
            var selected_template = templates_info[e.target.value];
            var use_landing = $('#<?php echo $this->id; ?>_check').attr('checked') ? 'yes' : '';

            $('input[name="<?php echo $this->id; ?>"]').val('{"landing": "' + use_landing + '","template":"' + e.target.value + '" }');
            
            if(typeof selected_template['preview'] !== 'undefined' && selected_template['preview'] !== '') // If a preview image url has been set
            to_append += '<img src="' + selected_template['preview'] + '" alt="' + selected_template['preview'] + '">'; //Add an image

            to_append += '<ul>';

            if(typeof selected_template['template'] !== 'undefined' && selected_template['template'] !== '') // If the template name has been defined in the info
            to_append += '<li><h1>' + selected_template['template'] + '</h1></li>'; //Set the name of the template

            if(typeof selected_template['description'] !== 'undefined' && selected_template['template'] !== '') // If the template description has been defined in the info
            to_append += '<li><p class="description">' + selected_template['description'] + '</p></li>'; //Set the description of the template

            if(typeof selected_template['author'] !== 'undefined' && selected_template['template'] !== '') // If the template author has been defined in the info
            to_append += '<li><p><b>Author:</b> ' + selected_template['author'] + '</p></li>'; //Set the author of the template

            if(typeof selected_template['version'] !== 'undefined' && selected_template['template'] !== '') // If the template version has been defined in the info
            to_append += '<li><p><b>Version:</b> ' + selected_template['version'] + '</p></li>'; //Set the version of the template

            to_append += '</ul>';

            preview_box.html(to_append);
        }

    </script>

    <style media="screen">

        .templates-info select
        {
            width: 100%;
        }
        .templates-info,
        .templates-preview
        {
            box-sizing: border-box;
            padding: 10px;
            display: inline-block;
            width: 15%;
            opacity: 0.3;
            vertical-align: top;
        }
        .templates-preview
        {
            font-size: 0;
            width: calc(85% - 10px);
            border-left: 1px solid #ddd;
        }

        .templates-preview  h1
        {
            width: 100%;
            padding-bottom: 10px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .templates-preview img
        {
            max-width: 300px;
            margin-right: 10px;
        }

        .templates-preview p
        {
            font-style: italic;
            color: #aaa;
            margin: 0px;
            margin-bottom: 5px;
        }

        .templates-preview p.description
        {
            font-family: Georgia, serif;
            color: #999;
            line-height: 1.5;
            margin-bottom: 10px;
            text-align: justify;
        }

        .templates-preview img + ul
        {
            max-width: calc(100% - 300px);
            vertical-align: top;
            display: inline-block;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #<?php echo $this->id; ?>_check:checked + label + br + .templates-info,
        #<?php echo $this->id; ?>_check:checked + label + br + .templates-info + .templates-preview
        {
            opacity: 1;
        }
    </style>
