<?php

class KD_Hero extends KD_Widget2 {

    public function __construct() {

        add_action('admin_enqueue_scripts', array($this, 'scripts'));

        parent::__construct(
            'kd_hero', // Base ID
            __( 'KD Hero', 'kd' ), // Name
            array( 'description' => __( 'Hero slider.', 'kd' ), ) // Args
        );

        $this->filename = 'kd-hero';

        $this->controls = [
            ['name' => 'Settings', 'fields' =>
                [
                    'primary_text' => ['type' => 'text', 'label' => 'Primary text', 'description' => 'Hero block title.'],
                    'secondary_text' => ['type' => 'text', 'label' => 'Secondary text', 'description' => 'Hero block subtitle.'],
                    'button_value' => ['type' => 'text', 'label' => 'Button text', 'description' => 'Hero block button text.'],
                    'button_link' => ['type' => 'text', 'label' => 'Button link', 'description' => "Choose the button's link."],
                ]
            ],
            ['name' => 'Slides', 'fields' =>
                [
                    'slides' => ['type' => 'media', 'label' => 'Slides', 'multiple' => true, 'description' => 'Drag slide image below to reorder.'],
                    'effect' => ['type' => 'select', 'label' => 'Slider effect', 'options' => ['slide' => 'Slide', 'fade' => 'Fade', 'coverflow' => 'Coverflow'], 'description' => 'Choose your desired slider effect (transition between slides).'],
                    'speed' => ['type' => 'text', 'label' => 'Slider speed', 'description' => 'Time (in seconds) it takes to change slides.<br>Empty value disables autoplay.'],
//                    'transition_speed' => ['type' => 'text', 'label' => 'Transition speed', 'description' => 'Time (in seconds) it takes to transition from one slide to the next.'],
                    'direction' => ['type' => 'select', 'options' => ['horizontal' => 'Horizontal', 'vertical' => 'Vertical'], 'label' => 'Slider direction', 'description' => 'Direction in which slides transition from one to another.'],
                    'freemode' => ['type' => 'checkbox', 'label' => 'Freemode', 'description' => "Slides don't have a fixed position (touch enabled devices)."],
                    'pagination' => ['type' => 'checkbox', 'label' => 'Display slider pagination', 'description' => 'Display slider pagination.'],
                    'loop' => ['type' => 'checkbox', 'label' => 'Loop slides', 'description' => 'Enable slider looping (first & last slides are connected).'],
                    'slidesPerView' => ['type' => 'text', 'label' => 'Slides per view', 'description' => 'Choose how many slides to display at once.'],
                    'centeredSlides' => ['type' => 'checkbox', 'label' => 'Center slides', 'description' => 'When viewing more than 1 slide per view, centers the active slide.'],
                ]
            ],
            ['name' => 'Style', 'fields' =>
                [
                    'color' => ['type' => 'color', 'label' => 'Color', 'description' => 'Choose font color.'],
                    'primary_font' => ['type' => 'font', 'label' => 'Select Title font', 'description' => 'Choose hero block title font family.'],
                    'secondary_font' => ['type' => 'font', 'label' => 'Select Subtitle font', 'description' => 'Choose hero block subtitle font family.'],
                ]
            ],
        ];
    }

    public function scripts()
    {
        $commonPath = get_template_directory_uri().'/kd-common';
        wp_enqueue_script( 'media-upload' );
        wp_enqueue_media();
        wp_enqueue_script('admin-script', $commonPath . '/js/admin.js', array('jquery'));
    }

}

add_action( 'widgets_init', function(){
    register_widget( 'KD_Hero' );
});