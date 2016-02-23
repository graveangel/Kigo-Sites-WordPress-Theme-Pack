<?php

class KD_Block extends KD_Widget2 {

    public function __construct() {

        parent::__construct(
            'kd_block', // Base ID
            __( 'KD Media Block', 'kd' ), // Name
            array( 'description' => __( 'Display content over an image.', 'kd' ), ) // Args
        );

        $this->filename = 'kd-block';

        $this->controls = [
            ['name' => 'Settings', 'fields' =>
                [
                    'title' => ['type' => 'text', 'label' => 'Title'],
                    'content' => ['type' => 'editor', 'label' => 'Content'],
                ]
            ],
            ['name' => 'Image', 'fields' =>
                [
                    'image' => ['type' => 'media', 'label' => 'Image', 'description' => "Choose an image to use as background."],
                ]
            ],
            ['name' => 'Video', 'fields' =>
                [
                    'useVideo' => ['type' => 'checkbox', 'label' => 'Display video', 'description' => "Display a background video instead of an image."],
                    'whichVideo' => ['type' => 'radio', 'label' => __('Choose video source','kd'), 'dir' => 'horizontal', 'choices' => ['yt' => 'YouTube', 'ct' => 'Custom']],
                    'video-yt' => ['type' => 'text', 'label' => 'YouTube video ID', 'description' => "If displaying a 'YouTube' video, insert video ID (.../watch?v=<strong>VIDEO_ID</strong>)."],
                    'video-ct' => ['type' => 'media', 'mediaType' => 'video', 'label' => 'Custom video', 'description' => "Upload your custom video."],
                ]
            ],
            ['name' => 'Style', 'fields' =>
                [
                    'bgColor' => ['type' => 'color', 'label' => 'Background color', 'description' => 'Choose block background color.'],
                    'font' => ['type' => 'font', 'label' => 'Font family', 'description' => 'Choose content font family.'],
                ]
            ],
        ];

    }

}

add_action( 'widgets_init', function(){
    register_widget( 'KD_Block' );
});