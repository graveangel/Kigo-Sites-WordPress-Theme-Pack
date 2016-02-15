<?php
/**
 * Functions used to implement options
 *
 * @package Customizer Library Demo
 */

/**
 * Enqueue Google Fonts Example
 */
function demo_fonts() {

	// Font options
	$fonts = array(
		get_theme_mod( 'body-font', customizer_library_get_default( 'body-font' ) ),
		get_theme_mod( 'title-font', customizer_library_get_default( 'title-font' ) ),
		get_theme_mod( 'home-title-font', customizer_library_get_default( 'home-title-font' ) ),
		get_theme_mod( 'home-tagline-font', customizer_library_get_default( 'home-tagline-font' ) )
	);

	$font_uri = customizer_library_get_google_font_uri( $fonts );

	// Load Google Fonts
	wp_enqueue_style( 'demo_fonts', $font_uri, array(), null, 'screen' );

}
add_action( 'wp_enqueue_scripts', 'demo_fonts' );