<?php

class KD_SelectiveSearch extends KD_Widget2 {

    /**
     * Selective search widget:
     *
     */
    public function __construct() {
        parent::__construct(
            'KD_SelectiveSearch', // Base ID
            __( 'KD Selective Search', 'kd' ), // Name
            array( 'description' => __( 'Blogs, pages, etc. filtered search.', 'kd' ), ) // Args
        );

        $this->filename = 'kd-selective-search';

        /* Appending wordpress dashicons for the frontend */
        add_action( 'wp_enqueue_scripts', array($this,'add_scripts') );
    }




    /**
    * Enqueue Dashicons style for frontend
    * use when enqueuing your theme's style sheet
    */
    function add_scripts() {
        wp_enqueue_style( 'dashicons' );
    }




    /**
     * The widget form.
     * @param  array $instance The instance of the widget.
     * @return void           This function does not return a value.
     */
    public function form( $instance ) {
        // outputs the options form on admin

        /* If we find a custom form template we display it, if not we build form from controls  */
        $formTemplate = __DIR__ .'/'.$this->filename.'.form.php';

        if(file_exists($formTemplate))
        {
            $i = $instance;
            include $formTemplate;
        }
        else
        {
            echo $this->buildForm($instance);
        }
    }




    /**
     * Add the keyword_search feature to the results
     * @param  string $query_string the query string to define the search
     * @return void               this function does not return anything
     */
    public function keyword_search($query_string)
    {
        //Add parameters to the search query
        //get all posts/pages/whatever of properties that contain all keyword search parameters passed
    }




}

add_action( 'widgets_init', function(){
    register_widget( 'KD_SelectiveSearch' );
});
