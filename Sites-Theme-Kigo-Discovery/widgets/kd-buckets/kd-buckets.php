<?php

class KD_Buckets extends KD_Widget2 {

    public function __construct() {

        parent::__construct(
            'kd_buckets', // Base ID
            __( 'KD Buckets', 'kd' ), // Name
            array( 'description' => __( 'Displays property buckets.', 'kd' ), ) // Args
        );

        $this->filename = 'kd-buckets';

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
    register_widget( 'KD_Buckets' );
});