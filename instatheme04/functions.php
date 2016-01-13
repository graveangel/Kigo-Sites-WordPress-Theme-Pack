<?php
/**
 * Insta theme04 functions and definitions.
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
 * Theme: instatheme04
 * Version: instatheme 1.0 
 * Date: 04-16-2013
 */

function childtheme_widgets_init(){
	
	register_sidebar( array(
		'name' => __( 'Kigo Top Fixed Left', 'instaparent' ),
		'id' => 'insta-top-fixed-left',
		'description' => __( 'Fixed position at top of the screen (the left side of the menu)', 'instaparent' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}

/*
*
* Creating the widgets for the Common Widget Areas
*
*/

function childtheme_override_presetwidgets_commonareas() {		
	/*for insta-header-left*/
	update_option( 'widget_bapi_hp_logo', array( 2 => array( 'title' => '' )) );
	
	/*for insta-footer*/
	update_option( 'widget_bapi_footer', array( 2 => array( 'title' => '' )) );
	
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
	/*for insta-left-home*/
	update_option( 'widget_bapi_hp_search', array( 2 => array( 'title' => $textDataArray["Search"] )) );		
	/*for insta-bottom-left-home*/
	update_option( 'widget_bapi_featured_properties', array( 2 => array( 'title' => $textDataArray["Featured Properties"], 'text' => 4, 'rowsize' => "4" )) );
	/*for insta-bottom-middle-home*/
	update_option( 'widget_bapi_property_finders', array( 2 => array( 'title' => $textDataArray["Property Finders"],'text' => 4,'rowsize' => "4" )) );
	/*for insta-bottom-right-home*/
	update_option( 'widget_bapi_specials_widget', array( 2 => array( 'title' => $textDataArray["Special Offers"],'rowsize' => "4" )) );	
}

/*
*
* setting the widgets for the Common Widget Areas
*
*/

function childtheme_override_setwidgets_commonareas($arrayOfSidebars) {
	$arrayOfSidebars['insta-top-fixed-left'][0] = 'bapi_hp_logo-2';
	
	
	$arrayOfSidebars['insta-footer'][0] = 'bapi_footer-2';
	
	return $arrayOfSidebars;
}


/*
*
* Setting the widgets for the Home Page Widget Areas
* 
*/

function childtheme_override_setwidgets_hpareas($arrayOfSidebars) {
	$arrayOfSidebars['insta-left-home'][0] = 'bapi_hp_search-2';
	$arrayOfSidebars['insta-bottom-left-home'][0] = 'bapi_featured_properties-2';
	$arrayOfSidebars['insta-bottom-middle-home'][0] = 'bapi_property_finders-2';	
	$arrayOfSidebars['insta-bottom-right-home'][0] = 'bapi_specials_widget-2';
	return $arrayOfSidebars;
}
