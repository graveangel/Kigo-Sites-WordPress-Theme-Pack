<?php

class KD_Team extends KD_Widget2 {

    public function __construct() {

        parent::__construct(
            'kd_team', // Base ID
            __( 'KD Team', 'kd' ), // Name
            array( 'description' => __( 'Displays team members.', 'kd' ), ) // Args
        );

        $this->filename = 'kd-team';

        $this->controls = [
            ['name' => 'Settings', 'fields' =>
                [
                    'title' => ['type' => 'text', 'label' => 'Title'],
                    'displayImages' => ['type' => 'checkbox', 'label' => 'Display images', 'description' => "Display each team member's featured image."],
                    'displayDesc' => ['type' => 'checkbox', 'label' => 'Display descriptions', 'description' => "Display each team member's description."],
                ]
            ],
            ['name' => 'Layout', 'fields' =>
                [
                    'columns' => ['type' => 'noUISlider', 'label' => 'Columns', 'attrs' => ['data-min' => 1, 'data-max' => 5], 'description' => "Choose how many items are displayed in a row."],
                    'displayList' => ['type' => 'checkbox', 'label' => 'Display as grid', 'description' => "Display team members in a grid fashion, disabling the slider."],
                ]
            ],
        ];

    }

}

add_action( 'widgets_init', function(){
    register_widget( 'KD_Team' );
});