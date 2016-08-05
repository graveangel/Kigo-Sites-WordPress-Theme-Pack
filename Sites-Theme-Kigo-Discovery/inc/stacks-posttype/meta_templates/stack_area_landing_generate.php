<?php wp_nonce_field(basename(__FILE__), $this->id . '_nonce'); ?>
    <label for="<?php echo $this->id; ?>"><p><?php _e($this->description, 'kd'); ?></p></label>
    <input class="widefat editor" type="checkbox" name="<?php echo $this->id; ?>" id="<?php echo $this->id; ?>" size="30" />
