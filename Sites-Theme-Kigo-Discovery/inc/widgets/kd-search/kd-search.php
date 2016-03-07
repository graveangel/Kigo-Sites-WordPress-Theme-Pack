<?php

class KD_Search extends KD_Widget2 {

    public function __construct() {

        parent::__construct(
            'kd_search', // Base ID
            __( 'KD Search', 'kd' ), // Name
            array( 'description' => __( 'Property search.', 'kd' ), ) // Args
        );

        $this->filename = 'kd-search';

        $this->controls = [];

    }

}

add_action( 'widgets_init', function(){
    register_widget( 'KD_Search' );
});