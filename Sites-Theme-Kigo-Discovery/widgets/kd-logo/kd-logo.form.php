<div class="kd-image-form" style="margin: 10px 0">
    <!-- BAPI Logo form -->
    <p>
        <label class="inline" for="mode_bapi">Use logo from the App</label>
        <input class="inline" type="radio" name="<?php echo $fields['mode']['name'] ?>" value="bapi" <?php checked($fields['mode']['value'], 'bapi')?> id="mode_bapi" />
    </p>
    <hr>
    <!-- Image Logo form -->
    <p>
        <label class="inline" for="mode_image">Use image as logo</label>
        <input class="inline" type="radio" name="<?php echo $fields['mode']['name'] ?>" value="image" <?php checked($fields['mode']['value'], 'image')?> id="mode_image" />
    </p>
    <p class="control">
    <div class="widget-media" style="margin: 10px 0">
        <?php if(isset($fields['image']) && !empty($fields['image']['value'])){ ?>
            <div class="widget-media-item" style="background-image: url('<?php echo $fields['image']['value'] ?>')"><input type="hidden" name="<?php echo $fields['image']['name'] ?>" value="<?php echo $fields['image']['value'] ?>" /><div class="delete">Delete</div></div>
        <?php } ?>
    </div>
    <button data-multiple="0" data-id="<?php echo $fields['image']['id'] ?>" data-name="<?php echo $fields['image']['name'] ?>" class="upload_image_button button button-primary">Add Image</button>
    </p>

    <hr>
    <!-- Text Logo form -->
    <p>
        <label class="inline" for="mode_text">Use text as logo</label>
        <input class="inline" type="radio" name="<?php echo $fields['mode']['name'] ?>" value="text" <?php checked($fields['mode']['value'], 'text') ?> id="mode_text" />
    </p>
    <p class="control">
        <label for="<?php echo $fields['text']['id'] ?>">Logo text</label>
        <!--        <textarea class="wp_tinymce" id="--><?php //echo $fields['text']['id'] ?><!--"-->
        <!--                  name="--><?php //echo $fields['text']['name'] ?><!--"-->
        <!--                  id="--><?php //echo $fields['text']['name'] ?><!--">--><?php //echo $fields['text']['value'] ?><!--</textarea>-->

        <?php
        wp_editor($fields['text']['value'], $fields['text']['id'], ['textarea_name' => $fields['text']['name'], 'textarea_rows' => 7,
            'tinymce' => [
                'setup' => "function (ed) {
                ed.on('change', function() {
                    tinymce.triggerSave();
                });
                ed.on('KeyUp', function (e) {
                    $(ed.targetElm).html(ed.save()).trigger('change');
                    tinyMCE.triggerSave();
                    return true;
                });
                }"
            ]]);
        ?>
    </p>
    <p class="control">
        <label for="<?php echo $fields['font']['id'] ?>">Google font name</label>
        <input type="text" name="<?php echo $fields['font']['name'] ?>" id="<?php echo $fields['font']['id'] ?>" value="<?php echo $fields['font']['value'] ?>"/>
    </p>
    <div class="logo-preview">
        <?php if($fields['font']['value']){ ?>
            <style>@import url(https://fonts.googleapis.com/css?family=<?php echo str_replace(' ', '+', $fields['font']['value']) ?>); span.previewFont{font-family: <?php $onlyFontName = explode(':', $fields['font']['value']); echo array_shift($onlyFontName) ?>}</style>
        <?php } ?>
        <?php if($fields['text']['value']){ ?>
            <span class="previewFont"><?php echo $fields['text']['value'] ?></span>
        <?php } ?>
    </div>
</div>
<script>try{kd_admin.initTinyMCE()}catch(e){}</script>