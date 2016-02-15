<?php

class KD_PageBlock extends KD_Widget2 {

    public function __construct() {

        parent::__construct(
            'kd_page_block', // Base ID
            __( 'KD Page Block', 'kd' ), // Name
            array( 'description' => __( 'Displays page content inside a block.', 'kd' ), ) // Args
        );

        $this->filename = 'kd-page_block';

        $this->fields = array(
        );


        $this->controls = [
            ['name' => 'Page', 'fields' =>
                [
                    'page' => ['type' => 'page', 'label' => 'Page', 'description' => 'Choose what page to grab content from.'],
                    'displayTitle' => ['type' => 'checkbox', 'label' => 'Display page title', 'description' => 'Display chosen page title.'],
                    'customTitle' => ['type' => 'text', 'label' => 'Display custom title', 'description' => 'Display a custom set title.'],
                ]
            ],
            ['name' => 'Image', 'fields' =>
                [
                    'useFeatured' => ['type' => 'checkbox', 'label' => __('Use featured image', 'kd'), 'description' => 'Display selected page featured image.'],
                    'image' => ['type' => 'media', 'label' => __('Use custom image', 'kd'), 'description' => 'Display a custom image.'],
                    'align' => ['type' => 'radio', 'label' => 'Align image', 'choices' => ['left' => 'Left', 'right' => 'Right'], 'description' => 'Set image to a side of the content.'],
                ]
            ],
            ['name' => 'Style', 'fields' =>
                [
                    'bgcolor' => ['type' => 'color', 'label' => __('Background color', 'kd')],
                ]
            ],
            ['name' => 'Buttons', 'fields' =>
                [
                    'button1' => ['type' => 'button', 'label' => __('Button', 'kd')],
                    'button2' => ['type' => 'button', 'label' => __('Button', 'kd')],
                    'button3' => ['type' => 'button', 'label' => __('Button', 'kd')],
                    'button4' => ['type' => 'button', 'label' => __('Button', 'kd')],
                ]
            ],
        ];
    }

}

add_action( 'widgets_init', function(){
    register_widget( 'KD_PageBlock' );
});