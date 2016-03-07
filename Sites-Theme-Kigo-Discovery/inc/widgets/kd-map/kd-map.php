<?php

class KD_Map extends KD_Widget2 {

    public function __construct() {

        parent::__construct(
            'kd_map', // Base ID
            __( 'KD Map', 'kd' ), // Name
            array( 'description' => __( 'Displays a map.', 'kd' ), ) // Args
        );

        $this->filename = 'kd-map';

        $this->controls = [
            ['name' => 'Map', 'fields' =>
                [
                    'map' => ['type' => 'map', 'label' => 'Map preview', 'description' => 'Enter address or coordinate pair (lat,lon).<br>Save widget to preview map location.'],
                    'zoom' => ['type' => 'text', 'label' => 'Map zoom level', 'description' => 'Initial map zoom level (1-20).'],
                ]
            ],
            ['name' => 'Content', 'fields' =>
                [
                    'displayContent' => ['type' => 'checkbox', 'label' => 'Display content next to map', 'description' => 'Split map in half & add content to a side.'],
                    'use' => ['type' => 'radio', 'label' => 'Choose content to display', 'dir' => 'horizontal', 'choices' => ['address' => 'Map address', 'custom' => 'Custom'], 'description' => 'Choose what content to display.'],
                    'customContent' => ['type' => 'editor', 'label' => 'Custom content'],
                    'alignContent' => ['type' => 'radio', 'label' => 'Align content', 'dir' => 'horizontal', 'choices' => ['left' => 'Left', 'right' => 'Right'], 'description' => 'Choose on what side the content is placed.'],
                ],
            ]
        ];
    }
}

add_action( 'widgets_init', function(){
    register_widget( 'KD_Map' );
});