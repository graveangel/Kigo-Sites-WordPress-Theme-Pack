<?php wp_nonce_field(basename(__FILE__), $this->id . '_nonce'); ?>
    <label for="<?php echo $this->id; ?>"><p><?php _e($this->description, 'kd'); ?></p></label>
    <ul class="photo-list"></ul>
    <input class="widefat photos-array" type="hidden" name="<?php echo $this->id; ?>" id="stack_area_photos" value="<?php echo esc_attr(get_post_meta($object->ID, $this->id, true)); ?>" />
    <a data-hidden=".photos-array" class="ma_upload_image_button button button-primary">Pick images</a>

    <script type="text/javascript">

    function update_image_box()
    {
        //put pictures in photo-list
        var url_array = [];
        try {
            url_array = JSON.parse($('.photos-array').val());
        } catch (e) {
            //
        }
        //clear photo list
        $('.photo-list').html('');

        for(e in url_array)
        {
            var img = new Image();
                img.src = url_array[e];
                img.height = 100;
            var box = document.createElement('li');
                box.className = "img-box";
                box.appendChild(img);
                $('.photo-list').append(box);
        }
    }
    jQuery(document).ready(function($) {
        jQuery(document).on("click", ".ma_upload_image_button", function (e) {
            e.preventDefault();

            data_hidden_selector = $(this).attr('data-hidden');

            // If the media frame already exists, reopen it.
            if ( typeof myframe != 'undefined' ) {
                myframe.open();
                return;
            }

            // Create a new media frame
            myframe = wp.media({
                title: 'Select or Upload Media Of Your Chosen Persuasion',
                button: {
                    text: 'Use this media'
                },
                multiple: true  // Set to true to allow multiple files to be selected
            });

            // When an image is selected in the media frame...
            myframe.on( 'select', function() {

                // Get media attachment details from the frame state
                var attachment = myframe.state().get('selection').toJSON();
                var urls = new Array();

                $.each(attachment, function(i,v)
                {
                    urls.push(this.url);
                });

                try{
            
                    $(data_hidden_selector).val(JSON.stringify(urls));
                }catch(e)
                {
                    $(data_hidden_selector).val(JSON.stringify('[]'));
                }



                //Force change
                $(data_hidden_selector).change();

            }.bind(this));

            // Finally, open the modal on click
            myframe.open();
        });



        //photos-array change
        $('.photos-array').on('change',function(e)
        {
            update_image_box(e.target);
        });

        $('.photo-list').sortable(
            {
                placeholder: '<li class="img-box" height="100" width="100"><img class="placeholder-image" alt="Put Here" /></li>',
                onDrop: function ($item, container, _super, event) {
                    //Default
                      $item.removeClass(container.group.options.draggedClass).removeAttr("style")
                      $("body").removeClass(container.group.options.bodyClass);

                      //Do
                      //update hidden val
                      var images = $('.photo-list img');
                      var urls = new Array();

                      $.each(images, function(i,v)
                      {
                          urls.push($(this).attr('src'));
                      });

                      $('.photos-array').val(JSON.stringify(urls));
                    }
            });
});

        //update image box
        update_image_box();
    </script>

    <style media="screen">
        .photo-list
        {
            margin-bottom: 10px;
            display: block;
        }
        .img-box
        {
            display: inline-block;
            padding: 2px;
            overflow: hidden;
        }
        .placeholder-image
        {
            height: 100px;
            width: 100px;
            back: #008899;
            color:  white;
        }
    </style>
