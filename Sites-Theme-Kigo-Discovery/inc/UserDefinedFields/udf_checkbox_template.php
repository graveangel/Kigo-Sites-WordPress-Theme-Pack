<?php wp_nonce_field(basename(__FILE__), $this->id . '_nonce');  
           $val =  esc_attr(get_post_meta($object->ID, $this->id, true)); 
           $checked = !empty($val) ? 'checked' : '';
    ?>
<p>
    <label for="<?php echo $this->id; ?>"><p><input class="widefat" type="checkbox" name="<?php echo $this->id; ?>" id="<?php echo $this->id; ?>" <?php echo $checked; ?> size="30" /> <?php _e($this->description, 'kd'); ?></p></label></p>
    <br />
   
    
</p>
