<?php

class KD_Menu extends KD_Widget2 {

    public function __construct()
    {

        parent::__construct(
            'kd_menu', // Base ID
            __('KD Menu', 'kd'), // Name
            array('description' => __('Displays a menu.', 'kd'),) // Args
        );

        $this->controls = [
            ['name' => 'Settings', 'fields' =>
                [
                    'menu' => ['type' => 'menu', 'label' => 'Menu', 'description' => 'Choose one of the existing menus to display.'],
                    'mainMenu' => ['type' => 'checkbox', 'label' => 'Mobile menu', 'description' => 'Is this the main mobile menu?'],
                ]
            ],
            ['name' => 'Style', 'fields' =>
                [
                    'align' => ['type' => 'select', 'label' => 'Align menu', 'options' => [false => 'None', 'left' => 'Left', 'center' => 'Center', 'right' => 'Right'], 'description' => 'Push menu to a side.'],
                    'filled' => ['type' => 'checkbox', 'label' => 'Filled buttons', 'description' => 'Add a background color to menu items.'],
                    'bgColor' => ['type' => 'color', 'label' => 'Button color'],
                ]
            ],
        ];

        $this->filename = 'kd-menu';
    }

}

add_action( 'widgets_init', function(){
    register_widget( 'KD_Menu' );
});