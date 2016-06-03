<?php

class KD_SelectiveSearch extends KD_Widget2 {

    public function __construct() {

        parent::__construct(
            'KD_SelectiveSearch', // Base ID
            __( 'KD Selective Search', 'kd' ), // Name
            array( 'description' => __( 'Blogs, pages, etc. filtered search.', 'kd' ), ) // Args
        );

        $this->filename = 'kd-selective-search';

        $this->controls = $this->get_controls();

    }


    /**
     * Returns the array of controls of the widget
     * Creates the checkboxes of the post types to be
     * included them in the search results.
     *
     * @return array The array of the controls
     */
    public function get_controls()
    {
        $controls =[
            ['name' => 'Settings',
            'fields' =>
                [
                    'widget_title' => ['type' => 'text', 'label' => 'Title', 'description' => 'The search widget title.'],
                    'inherit' => ['type' => 'radio', 'label' => 'Inherit', 'description' => 'If enabled, the search criteria will inherit from the search query. If the search query does not contain any criteria it will use the criteria defined in the widget. If no criteria is defined in the widget then results will not be filtered.', 'choices' =>
                                        [
                                            'disabled',
                                            'enabled',
                                        ]
                                ],
                ]
            ],
        ];

        $post_types = get_theme_mod('kd_post_types');

        $post_type_controls = [];

        foreach($post_types as $post_type_val)
        {
            $post_type_controls[$post_type_val] =
            [
                'type' => 'checkbox',
                'label' => ucfirst($post_type_val),
                'description' => 'Include <b>' . ucfirst($post_type_val) . 's</b> in the search results',
            ];
        }

        $controls[0]['fields'] = array_merge($controls[0]['fields'], $post_type_controls);

        return $controls;
    }





}

add_action( 'widgets_init', function(){
    register_widget( 'KD_SelectiveSearch' );
});
