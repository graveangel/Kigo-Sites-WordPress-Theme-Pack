<?php

class KD_Logo extends KD_Widget2 {

    public function __construct() {

        parent::__construct(
            'kd_logo', // Base ID
            __( 'KD Logo', 'kd' ), // Name
            array( 'description' => __( 'Displays a logo.', 'kd' ), ) // Args
        );

        $this->filename = 'kd-logo';
//        $this->fields = ['mode', 'image', 'text', 'font'];

        $this->controls = [
            ['name' => 'Settings', 'fields' =>
                [
                    'mode' => ['type' => 'radio', 'label' => 'Choose what to use as logo:', 'choices' => ['bapi' => 'App logo', 'image' => 'Image logo', 'text' => 'Text logo']],
                    'image' => ['type' => 'media', 'label' => 'Choose custom image'],
                    'text' => ['type' => 'editor', 'label' => 'Choose custom text'],
                    'font' => ['type' => 'font', 'label' => 'Choose text font family'],
                ]
            ],
        ];
    }
}

add_action( 'widgets_init', function(){
    register_widget( 'KD_Logo' );
});