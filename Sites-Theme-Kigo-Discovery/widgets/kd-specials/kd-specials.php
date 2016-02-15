<?php

class KD_Specials extends KD_Widget2 {

    public function __construct() {

        parent::__construct(
            'kd_specials', // Base ID
            __( 'KD Specials', 'kd' ), // Name
            array( 'description' => __( 'Displays specials.', 'kd' ), ) // Args
        );

        $this->filename = 'kd-specials';

        $this->controls = [
            ['name' => 'Settings', 'fields' =>
                [
                    'title' => ['type' => 'text', 'label' => 'Title']
                ]
            ],
            ['name' => 'Layout', 'fields' =>
                [
                    'columns' => ['type' => 'noUISlider', 'label' => 'Columns', 'attrs' => ['data-min' => 1, 'data-max' => 4], 'description' => 'Choose how many items to display per row.']
                ]
            ],
        ];
    }
}

add_action( 'widgets_init', function(){
    register_widget( 'KD_Specials' );
});

