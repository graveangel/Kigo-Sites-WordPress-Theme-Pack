<?php wp_nonce_field(basename(__FILE__), $this->id . '_nonce'); ?>
<p>
    <label for="<?php echo $this->id; ?>"><p><?php _e($this->description, 'kd'); ?></p></label>
    <br />
    <input class="widefat" type="text" name="<?php echo $this->id; ?>" id="<?php echo $this->id; ?>" value="<?php echo esc_attr(get_post_meta($object->ID, $this->id, true)); ?>" size="30" />
</p>
