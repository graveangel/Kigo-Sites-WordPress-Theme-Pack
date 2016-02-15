<?php

class KD_Image extends KD_Widget {

    public function __construct() {

        parent::__construct(
            'kd_image', // Base ID
            __( 'KD Image', 'kd' ), // Name
            array( 'description' => __( 'Displays an image.', 'kd' ), ) // Args
        );

        $this->filename = 'kd-image';

        $this->fields = array(
            'image' => ['type' => 'media', 'label' => 'Image', 'multiple' => false],
            'link' => ['type' => 'text', 'label' => 'Link'],
            'height' => ['type' => 'text', 'label' => 'Image height'],

        );
    }
}

add_action( 'widgets_init', function(){
    register_widget( 'KD_Image' );
});