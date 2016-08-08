<?php wp_nonce_field(basename(__FILE__), $this->id . '_nonce'); ?>
<p>
    <label for="<?php echo $this->id; ?>"><p class="description"><?php apply_filters('the_content',_e($this->description, 'kd')); ?></p></label>
    <textarea name="<?php echo $this->id; ?>" id="<?php echo $this->id; ?>" class="widefat" rows="10"><?php echo esc_attr(get_post_meta($object->ID, $this->id, true)); ?></textarea>
</p>
