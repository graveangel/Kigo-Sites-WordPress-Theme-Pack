<?php

class KD_Social extends KD_Widget2 {

    public function __construct() {

        parent::__construct(
            'kd_social', // Base ID
            __( 'KD Social', 'kd' ), // Name
            array( 'description' => __( 'Social icons.', 'kd' ), ) // Args
        );

        $this->filename = 'kd-social';

        $this->controls = [
            ['name' => 'Icons', 'fields' =>
                [
                    'facebook' => ['type' => 'social', 'label' => __('Facebook', 'kd')],
                    'gplus' => ['type' => 'social', 'label' => __('Google +', 'kd')],
                    'instagram' => ['type' => 'social', 'label' => __('Instagram', 'kd')],
                    'linkedin' => ['type' => 'social', 'label' => __('Linkedin', 'kd')],
                    'pinterest' => ['type' => 'social', 'label' => __('Pinterest', 'kd')],
                    'tumblr' => ['type' => 'social', 'label' => __('Tumblr', 'kd')],
                    'twitter' => ['type' => 'social', 'label' => __('Twitter', 'kd')],
                    'vimeo' => ['type' => 'social', 'label' => __('Vimeo', 'kd')],
                    'youtube' => ['type' => 'social', 'label' => __('YouTube', 'kd')],
                ]
            ],
            ['name' => 'Style', 'fields' =>
                [
                    'color' => ['type' => 'color', 'label' => __('Icon color', 'kd')]
                ]
            ],
        ];

    }

}

add_action( 'widgets_init', function(){
    register_widget( 'KD_Social' );
});
