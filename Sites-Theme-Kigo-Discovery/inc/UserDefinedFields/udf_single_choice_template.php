<?php wp_nonce_field(basename(__FILE__), $this->id . '_nonce'); 
$value = esc_attr(get_post_meta($object->ID, $this->id, true));
?>
<p>
    <label for="<?php echo $this->id; ?>"><p><?php _e($this->description, 'kd'); ?></p></label>
    <br />
    
    <select name="<?php echo $this->id; ?>" id="<?php echo $this->id; ?>" class="widefat">
        <?php foreach($this->options as $option): 
            if ($value == $option) 
                $selected = 'selected';
           else 
               $selected='';
           ?>
        <option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
        <?php endforeach; ?>
    </select>
</p>
