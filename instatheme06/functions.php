<?php
/**
 * Insta theme06 functions and definitions.
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
 * Theme: instatheme06
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
		'name' => __( 'Home Featured Properties', 'instaparent' ),
		'id' => 'insta-home-fp',
		'description' => __( 'Home Featured Property section (Below of Slideshow)', 'instaparent' ),
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
    register_sidebar( array(
		'name' => __( 'Footer Navigation Menu', 'instaparent' ),
		'id' => 'insta-footer-nav',
		'description' => __( 'The Footer navigation Menu', 'instaparent' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s nav span3">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'Footer Company Info', 'instaparent' ),
		'id' => 'insta-footer-info',
		'description' => __( 'The Company info in the Footer', 'instaparent' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s footer-info">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title">',
		'after_title' => '</h3>',
	) );
}
function childtheme_override_presetwidgets_commonareas() {
	/*for insta-header-left*/
	update_option( 'widget_bapi_hp_logo', array( 2 => array( 'title' => '' )) );
	update_option( 'widget_bapi_header', array( 2 => array( 'title' => '' )) );
	/*for insta-footer*/
	update_option( 'widget_mr-social-sharing-toolkit-follow-widget', array( 2 => array( 'title' => '' )) );
	update_option( 'widget_bapi_footer', array( 2 => array( 'title' => '' )) );
	//update_option( 'widget_text', array( 2 => array( 'title' => '','text' => '<p><span class="phone">1-800-941-0868</span> <span class="address"> Address, City, Zip Code</span> <a class="email" href="#">address@email.com</a></p>'),3 => array( 'title' => '','text' => '<div class="hp-testimonials"><h3>Testimonials</h3><p class="quote">Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh. Donec id elit non mi porta gravida at eget metus. Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh.</p><p class="author"><span>John Smith</span>, Los Angeles, CA  -  February 17, 2013</p></div>')) );
	
}
/*
*
* setting the widgets for the Common Widget Areas
*
*/
function childtheme_override_setwidgets_commonareas($arrayOfSidebars) {
		$arrayOfSidebars['insta-top-fixed'][0] = 'bapi_header-2';
		$arrayOfSidebars['insta-header-left'][0] = 'bapi_hp_logo-2';
		$arrayOfSidebars['insta-header-right'][0] = '';
		$arrayOfSidebars['insta-footer'][0] = 'bapi_footer-2';
		$arrayOfSidebars['insta-footer-info'][0] = 'text-2';
		$arrayOfSidebars['insta-footer-nav'][0] = 'mr-social-sharing-toolkit-follow-widget-2';
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
/*for Full Content*/
update_option( 'widget_bapi_featured_properties', array( 2 => array( 'title' => $textDataArray["Featured Properties"], 'text' => 4, 'rowsize' => "4" )) );
update_option( 'widget_insta_latest_blog_posts', array( 2 => array( 'title' => $textDataArray["News & Events"],'text' => 3,'rowsize' => "3" )) );
update_option( 'widget_bapi_property_finders', array( 2 => array( 'title' => $textDataArray["Property Finders"],'text' => 3,'rowsize' => "3" )) );
/* For Follow us widget*/
update_option( 'widget_mr-social-sharing-toolkit-widget', array( 2 => array( 'widget_title' => $textDataArray["Connect With Us"] )) );
/*for insta-right-home*/
update_option( 'widget_bapi_specials_widget', array( 2 => array( 'title' => $textDataArray["Specials"],'text' => 3,'rowsize' => "1" )) );
/* For Insta Home Qsearch */
update_option( 'widget_bapi_hp_search', array( 2 => array( 'title' => '' )) );
}	
/*
*
* Setting the widgets for the Home Page Widget Areas
* 
*/
function childtheme_override_setwidgets_hpareas($arrayOfSidebars) {
	$arrayOfSidebars['insta-home-fp'][0] = 'bapi_featured_properties-2';
	//$arrayOfSidebars['insta-bottom-full-home'][0] = 'text-3';
	$arrayOfSidebars['insta-bottom-full-home'][1] = 'bapi_property_finders-2';	
	$arrayOfSidebars['insta-left-home'][0] = 'insta_latest_blog_posts-2';
	$arrayOfSidebars['insta-right-home'][0] = 'mr-social-sharing-toolkit-widget-2';	
	$arrayOfSidebars['insta-right-home'][1] = 'bapi_specials_widget-2';
	$arrayOfSidebars['insta-home-qsearch'][0] = 'bapi_hp_search-2';
	return $arrayOfSidebars;
}
