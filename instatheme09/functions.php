<?php
/**
 * Insta theme09 functions and definitions.
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
 * Theme: instatheme09
 * Version: instatheme 1.0 
 * Date: 06-15-2013
 */

/*
*
* Creating the widgets for the Common Widget Areas
*
*/
function childtheme_widgets_init(){
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
function childtheme_override_presetwidgets_commonareas() {
	/*for insta-header-left*/
	update_option( 'widget_bapi_hp_logo', array( 2 => array( 'title' => '' )) );
	update_option( 'widget_bapi_header', array( 2 => array( 'title' => '' )) );
	update_option( 'widget_bapi_weather_widget', array( 2 => array( 'title' => '' )) );
	/*for insta-footer*/
	update_option( 'widget_bapi_footer', array( 2 => array( 'title' => '' )) );
        
        /*for insta-footer-1*/
        update_option( 'widget_kigo_social_icons', array( 2 => array()) );
        /*for insta-footer-4*/
        update_option( 'widget_kigo_custom_menu', array( 2 => array( 'nav_menu' => 'main-navigation-menu' )) );
}
/*
*
* setting the widgets for the Common Widget Areas
*
*/
function childtheme_override_setwidgets_commonareas($arrayOfSidebars) {
		$arrayOfSidebars['insta-top-fixed'][0] = 'bapi_header-2';
		$arrayOfSidebars['insta-header-left'][0] = 'bapi_hp_logo-2';
		$arrayOfSidebars['insta-header-right'][0] = 'bapi_weather_widget-2';
		$arrayOfSidebars['insta-footer'][0] = 'bapi_footer-2';
                
                $arrayOfSidebars['insta-footer-1'][0] = 'kigo_social_icons-2';
                $arrayOfSidebars['insta-footer-4'][0] = 'kigo_custom_menu-2';
		return $arrayOfSidebars;
}
/*
*
* Creating the widgets for the Home Page Widget Areas
* Widgets areas that are in front-page.php
*/

function childtheme_override_presetwidgets_hpareas() {
/* we get the array of textdata */
$textDataArray = getTextDataArray();
/*for Full Content*/
update_option( 'widget_bapi_featured_properties', array( 2 => array( 'title' => $textDataArray["Featured Properties"], 'text' => 4, 'rowsize' => "4" )) );
update_option( 'widget_insta_latest_blog_posts', array( 2 => array( 'title' => $textDataArray["News & Events"])) );
update_option( 'widget_bapi_property_finders', array( 2 => array( 'title' => $textDataArray["Property Finders"],'text' => 4,'rowsize' => "4" )) );
/*for insta-right-home*/
update_option( 'widget_bapi_specials_widget', array( 2 => array( 'title' => $textDataArray["Specials"],'text' => 1,'rowsize' => "1" )) );
/* For Insta Home Qsearch */
update_option( 'widget_bapi_hp_search', array( 2 => array( 'title' => 'Find your InstaVilla' )) );	
}	
/*
*
* Setting the widgets for the Home Page Widget Areas
* 
*/
function childtheme_override_setwidgets_hpareas($arrayOfSidebars) {
	$arrayOfSidebars['insta-bottom-full-home'][0] = 'bapi_featured_properties-2';
	$arrayOfSidebars['insta-bottom-full-home'][1] = 'bapi_property_finders-2';	
	$arrayOfSidebars['insta-bottom-left-home'][1] = 'bapi_specials_widget-2';
	$arrayOfSidebars['insta-bottom-right-home'][0] = 'insta_latest_blog_posts-2';
	$arrayOfSidebars['insta-home-qsearch'][0] = 'bapi_hp_search-2';
	return $arrayOfSidebars;
}
