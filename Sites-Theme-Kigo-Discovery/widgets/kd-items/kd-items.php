<?php

class KD_Items extends KD_Widget2 {

    public function __construct() {

        parent::__construct(
            'kd_items', // Base ID
            __( 'KD Items', 'kd' ), // Name
            array( 'description' => __( 'List defined items.', 'kd' ), ) // Args
        );

        $this->filename = 'kd-items';

        $this->controls = [
            ['name' => 'Settings', 'fields' =>
                [
                    'title' => ['type' => 'text', 'label' => 'Title'],
                    'type' => ['type' => 'term', 'label' => 'Item type', 'description' => 'Choose what <a href="/wp-admin/edit-tags.php?taxonomy=type&post_type=item" target="_blank">item type</a> to display.']
                ]
            ],
            ['name' => 'Layout', 'fields' =>
                [
                    'display' => ['type' => 'radio', 'label' => 'Display items as:', 'choices' => ['list' => 'List', 'slider' => 'Slider'], 'description' => 'Choose how to display items.'],
                    'columns' => ['type' => 'noUISlider', 'label' => 'Columns', 'attrs' => ['data-min' => 1, 'data-max' => 4], 'description' => 'Choose how many items to display per row.'],
                    'limit' => ['type' => 'text', 'label' => 'Limit items to display', 'description' => 'Limit items.']
                ]
            ],
        ];

    }

}

add_action( 'widgets_init', function(){
    register_widget( 'KD_Items' );
});