<?php wp_nonce_field(basename(__FILE__), $this->id . '_nonce'); ?>
    <label for="<?php echo $this->id; ?>"><p><?php _e($this->description, 'kd'); ?></p></label>
    <!-- <textarea class="widefat editor" type="text" name="<?php echo $this->id; ?>" id="<?php echo $this->id; ?>" value="<?php echo esc_attr(get_post_meta($object->ID, $this->id, true)); ?>" size="30" /></textarea> -->

<?php wp_editor(  esc_attr(get_post_meta($object->ID, $this->id, true)), 'stack-area-description', ['textarea_name' => $this->id, 'editor_height' => 200] ); ?>
