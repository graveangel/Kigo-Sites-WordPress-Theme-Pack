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
        add_action( 'wp_enqueue_scripts', array($this,'add_scripts') );
    }

    /**
 * Enqueue Dashicons style for frontend use when enqueuing your theme's style sheet
 */
function add_scripts() {
	wp_enqueue_style( 'dashicons' );
}

    public function form( $instance ) {
        // outputs the options form on admin

        /* If we find a custom form template we display it, if not we build form from controls  */
                $formTemplate = __DIR__ .'/'.$this->filename.'.form.php';
            if(file_exists($formTemplate)){
                $i = $instance;
                include $formTemplate;
            }else{
                echo $this->buildForm($instance);
                }
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
                    'inherit' => ['type' => 'radio', 'label' => 'Advanced Search', 'description' => '<p class="fa fa-info advanced-search-info"></p><p class="description">Enabling this option will let you select the filters to limit the range of the search.</p>', 'choices' =>
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
