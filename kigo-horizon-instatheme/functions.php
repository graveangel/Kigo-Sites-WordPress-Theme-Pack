<?php
/**
 * Insta theme Horizon functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * WordPress
 * Child Theme : Yes
 * Theme: Horizon
 * Version: instatheme 1.0 
 * Date: 06-04-2015
 */

/*
*
* Creating the widgets for the Common Widget Areas
*
*/

function childtheme_widgets_init(){
    register_sidebar( array(
		'name' => __( 'Top Social Icons', 'instaparent' ),
		'id' => 'insta-top-social',
		'description' => __( 'Social icons at top right of the screen', 'instaparent' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
    register_sidebar( array(
		'name' => __( 'Top Header', 'instaparent' ),
		'id' => 'insta-top-header',
		'description' => __( 'Top header on the top right the screen', 'instaparent' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'Top Left Logo', 'instaparent' ),
		'id' => 'insta-top-logo',
		'description' => __( 'Position for logo (the left side of the menu)', 'instaparent' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'Home Quick Search', 'instaparent' ),
		'id' => 'insta-home-qsearch',
		'description' => __( 'The Quick Seach into home slideshow', 'instaparent' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title">',
		'after_title' => '</h3>',
	) );
}
//Add a Footer menu.  
add_action( 'init', 'register_footer_menu' );
function register_footer_menu() {
    	register_nav_menus(
    		array(	'footer' => __( 'Footer Menu')	)
    	);
}

function childtheme_override_presetwidgets_commonareas() {
if ( function_exists( 'getTextDataArray') )  {
			/* we get the array of textdata */
			$textDataArray = getTextDataArray();
		}
	/*for insta-header-left*/
    update_option( 'widget_bapi_header', array( 2 => array( 'title' => '' )) );	
	update_option( 'widget_bapi_hp_logo', array( 2 => array( 'title' => '' ),3 => array( 'title' => '' )) );	
	/* For Footer Content Menu */
	update_option( 'widget_mr-social-sharing-toolkit-widget', array( 2 => array( 'widget_title' => '' )) );
	update_option( 'widget_bapi_footer', array( 2 => array( 'title' => '' )) );
}
/*
*
* setting the widgets for the Common Widget Areas
*
*/
function childtheme_override_setwidgets_commonareas($arrayOfSidebars) {
	    $arrayOfSidebars['insta-top-header'][0] = 'bapi_header-2';
        $arrayOfSidebars['insta-top-social'][0] = 'mr-social-sharing-toolkit-widget-2';
        $arrayOfSidebars['insta-top-logo'][0] = 'bapi_hp_logo-2';
		$arrayOfSidebars['insta-footer'][0] = 'bapi_footer-2';
		return $arrayOfSidebars;
}
/*
*
* Creating the widgets for the Home Page Widget Areas
* Widgets areas that are in front-page.php
*/

function childtheme_override_presetwidgets_hpareas() {
if ( function_exists( 'getTextDataArray') )  {
			/* we get the array of textdata */
			$textDataArray = getTextDataArray();
		}
	update_option( 'widget_bapi_featured_properties', array( 2 => array( 'title' => $textDataArray["Featured Properties"], 'text' => 3, 'rowsize' => "3" )) );
	update_option( 'widget_bapi_property_finders', array( 2 => array( 'title' => $textDataArray["Property Finders"],'text' => 4,'rowsize' => "4" )) );
	update_option( 'widget_bapi_specials_widget', array( 2 => array( 'title' => 'Special Offers','text' => 4,'rowsize' => "4" )) );
    update_option( 'widget_text', array( 2 => array( 'title' => '','text' => '<div class="loadmore"><a href="/rentals/rentalsearch/">Load more Apartments</a></div>' )) );
	/* For Insta Home Qsearch */
	update_option( 'widget_bapi_hp_search', array( 2 => array( 'title' => '')) );	
}	
/*
*
* Setting the widgets for the Home Page Widget Areas
* 
*/
function childtheme_override_setwidgets_hpareas($arrayOfSidebars) {
	$arrayOfSidebars['insta-bottom-full-home'][0] = 'bapi_property_finders-2';	
    $arrayOfSidebars['insta-bottom-full-home'][1] = 'bapi_featured_properties-2';
	$arrayOfSidebars['insta-bottom-full-home'][2] = 'bapi_specials_widget-2';
    $arrayOfSidebars['insta-bottom-full-home'][3] = 'text-2';
	$arrayOfSidebars['insta-home-qsearch'][0] = 'bapi_hp_search-2';
	return $arrayOfSidebars;
}
