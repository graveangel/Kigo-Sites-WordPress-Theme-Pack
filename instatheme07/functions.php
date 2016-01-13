<?php
/**
 * Insta theme07 functions and definitions.
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
 * Theme: instatheme07
 * Version: instatheme 1.0 
 * Date: 04-16-2013
 */

/*
*
* Creating the widgets for the Common Widget Areas
*
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
	register_sidebar( array(
		'name' => __( 'Footer Content', 'instaparent' ),
		'id' => 'insta-footer-content',
		'description' => __( 'The Bottom Content in the Footer Area', 'instaparent' ),
		'before_widget' => '<div id="%1$s" class="span3 widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h5 class="widget-title">',
		'after_title' => '</h5>',
	) );
	register_sidebar( array(
		'name' => __( 'Footer Logo', 'instaparent' ),
		'id' => 'insta-footer-logo',
		'description' => __( 'The Logo in the Footer Area', 'instaparent' ),
		'before_widget' => '<div id="%1$s" class="span12 widget %2$s">',
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

function childtheme_override_presetwidgets_commonareas() {
if ( function_exists( 'getTextDataArray') )  {
			/* we get the array of textdata */
			$textDataArray = getTextDataArray();
		}
	/*for insta-header-left*/
	update_option( 'widget_bapi_hp_logo', array( 2 => array( 'title' => '' ),3 => array( 'title' => '' )) );
	
	/* For Footer Content Menu */
	update_option( 'widget_nav_menu', array( 2 => array( 'title' => $textDataArray["Search"] ),3 => array( 'title' => 'Explore' ),4 => array( 'title' => 'About Us' )) );
	update_option( 'widget_mr-social-sharing-toolkit-widget', array( 2 => array( 'widget_title' => '' )) );
	update_option( 'widget_bapi_footer', array( 2 => array( 'title' => '' )) );
}
/*
*
* setting the widgets for the Common Widget Areas
*
*/
function childtheme_override_setwidgets_commonareas($arrayOfSidebars) {
	    $arrayOfSidebars['insta-top-fixed-left'][0] = 'bapi_hp_logo-2';
		$arrayOfSidebars['insta-footer-logo'][0] = 'bapi_hp_logo-3';
		$arrayOfSidebars['insta-footer-content'][0] = 'nav_menu-2';
		$arrayOfSidebars['insta-footer-content'][1] = 'nav_menu-3';
		$arrayOfSidebars['insta-footer-content'][2] = 'nav_menu-4';
		$arrayOfSidebars['insta-footer-content'][3] = 'mr-social-sharing-toolkit-widget-2';
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
	/*for insta-left-home*/
	update_option( 'widget_bapi_featured_properties', array( 2 => array( 'title' => @$textDataArray["Featured Properties"], 'text' => 4, 'rowsize' => "2" )) );
	update_option( 'widget_bapi_property_finders', array( 2 => array( 'title' => @$textDataArray["Property Finders"],'text' => 3,'rowsize' => "3" )) );
	
	/*for insta-right-home*/
	update_option( 'widget_mr-social-sharing-toolkit-follow-widget', array( 2 => array( 'title' => @$textDataArray["Connect With Us"] )) );
	update_option( 'widget_bapi_weather_widget', array( 2 => array( 'title' => @$textDataArray["Weather"] )) );
	update_option( 'widget_bapi_specials_widget', array( 2 => array( 'title' => '','text' => 2,'rowsize' => "1" )) );
	update_option( 'widget_insta_latest_blog_posts', array( 2 => array( 'title' => '', 'numberOfPosts' => 1, 'rowSize' => 1, 'displayImage' => 1, 'displayDate' => 0, 'displayTitle' => 1, 'postLinkString' => '' )) );
	
	/* For Insta Home Qsearch */
	update_option( 'widget_bapi_hp_search', array( 2 => array( 'title' => @$textDataArray["Find your InstaVilla"].'...')) );
}	
/*
*
* Setting the widgets for the Home Page Widget Areas
* 
*/
function childtheme_override_setwidgets_hpareas($arrayOfSidebars) {
	$arrayOfSidebars['insta-left-home'][0] = 'bapi_featured_properties-2';
	$arrayOfSidebars['insta-left-home'][1] = 'bapi_property_finders-2';	
	$arrayOfSidebars['insta-right-home'][0] = 'MR-social-sharing-toolkit-follow-widget-2';
	$arrayOfSidebars['insta-right-home'][1] = 'bapi_weather_widget-2';
	$arrayOfSidebars['insta-right-home'][2] = 'bapi_specials_widget-2';
	$arrayOfSidebars['insta-right-home'][3] = 'insta_latest_blog_posts-2';
	$arrayOfSidebars['insta-home-qsearch'][0] = 'bapi_hp_search-2';
	return $arrayOfSidebars;
}
