<?php
/**
 * Defines customizer options
 *
 * @package Customizer Library Demo
 */

function customizer_library_demo_options() {


    // Theme defaults TO DO
    $primary_color = '#33baaf';
    $secondary_color = '#f6af33';
    $tertiary_color = '#555';

    // Stores all the controls that will be added
    $options = array();

    // Stores all the sections to be added
    $sections = array();

    // Stores all the panels to be added
    $panels = array();

    // Adds the sections to the $options array
    $options['sections'] = $sections;



    // Panel Style Site Option
    include "panel-style-site-options.php";

    // General content
    include "global-settings.php";

    // Contact Content
	include "contact-content.php";

	// About us Content
	include "about-us-content.php";
        
    // Social options
    include "social-options.php";

    // Property detail options
    include "property-detail.php";

	// Adds the sections to the $options array
	$options['sections'] = $sections;

	// Adds the panels to the $options array
	$options['panels'] = $panels;

	$customizer_library = Customizer_Library::Instance();
	$customizer_library->add_options( $options );

	// To delete custom mods use: customizer_library_remove_theme_mods();


}
add_action( 'init', 'customizer_library_demo_options' );
