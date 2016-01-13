<?php
/**
 * Insta theme08 functions and definitions.
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
 * Theme: instatheme08
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
		'name' => __( 'Kigo Home Testimonial', 'instaparent' ),
		'id' => 'insta-home-testimonial',
		'description' => __( 'Insta Home Testimonial', 'instaparent' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s testimonials">',
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
		'name' => __( 'Footer Social widget', 'instaparent' ),
		'id' => 'insta-bottom-social',
		'description' => __( 'The footer social widget', 'instaparent' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s social-btm-widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title">',
		'after_title' => '</h3>',
	) );
}
/*
*
* setting the widgets for the Common Widget Areas
*
*/
function childtheme_override_presetwidgets_commonareas(){
	update_option( 'widget_bapi_hp_logo', array( 2 => array( 'title' => '' )) );
	update_option( 'widget_mr-social-sharing-toolkit-widget', array( 2 => array( 'title' => '' )) );
	update_option( 'widget_bapi_footer', array( 2 => array( 'title' => '' )) );
}

function childtheme_override_setwidgets_commonareas($arrayOfSidebars) {
	    $arrayOfSidebars['insta-top-fixed'][0] = 'bapi_hp_logo-2';
		$arrayOfSidebars['insta-bottom-social'][0] = 'mr-social-sharing-toolkit-widget-2';
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
	/*for insta-right-home*/
	//update_option( 'widget_text', array( 2 => array( 'title' => 'Featured in the press by','text' =>'<div class="span2 offset1"><img src="/wp-content/themes/instatheme08/images/ft-logo-1.jpg" alt="" /></div><div class="span2"><img src="/wp-content/themes/instatheme08/images/ft-logo-2.jpg" alt="" /></div><div class="span2"><img src="/wp-content/themes/instatheme08/images/ft-logo-3.jpg" alt="" /></div><div class="span2"><img src="/wp-content/themes/instatheme08/images/ft-logo-4.jpg" alt="" /></div><div class="span2"><img src="/wp-content/themes/instatheme08/images/ft-logo-5.jpg" alt="" /></div>' ),3 => array( 'title' => '','text' => '<h1>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</h1><h2>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</h2><div class="row-fluid"><div class="span6"><div class="content"><p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud ullamcorper.</p></div><div class="author"><h3>John Smith</h3><p>Owner, Company Name</p></div></div><div class="span6"><div class="content"><p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui delenit augue.</p></div><div class="author girl"><h3>Jane Smith</h3><p>Owner, Company Name</p></div></div></div>' ),4 => array( 'title' => '','text' => '<div class="block-photo"><a href="/specials/"><img src="/wp-content/themes/instatheme08/images/specials-img.jpg" alt="" /><h3>Specials</h3></a><p>View this page for great deals and year-round offers.</p><p><a href="/specials/">View All</a></p></div>' ),5 => array( 'title' => '','text' => '<div class="block-photo"><img src="/wp-content/themes/instatheme08/images/attractions-img.jpg" alt="" /><h3>Attractions</h3><p>New to our area or been here before?  Visit these great places while you enjoy your stay with us.</p><p><a href="/attractions/">View All</a></p></div>' ),6 => array( 'title' => '','text' => '<div class="block-photo"><a href="/rentals/searchbuckets/"><img src="/wp-content/themes/instatheme08/images/property-finder-img.jpg" alt="" /><h3>Property Finders</h3></a><p>Find the perfect place to stay with ease by viewing properties with the amenities you&rsquo;re looking for.</p><p><a href="/rentals/searchbuckets/">Read More</a></p></div>')));	
	/* For Insta Home Qsearch */
	update_option( 'widget_bapi_hp_search', array( 2 => array( 'title' => $textDataArray["Find your InstaVilla"], 'text' =>'<span>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</span>' )) );	
}	
/*
*
* Setting the widgets for the Home Page Widget Areas
* 
*/
function childtheme_override_setwidgets_hpareas($arrayOfSidebars) {
	/*$arrayOfSidebars['insta-bottom-left-home'][0] = 'text-4';
	$arrayOfSidebars['insta-bottom-middle-home'][0] = 'text-5';
	$arrayOfSidebars['insta-bottom-right-home'][0] = 'text-6';*/
	
	/*$arrayOfSidebars['insta-home-testimonial'][0] = 'text-3';
	$arrayOfSidebars['insta-bottom-full-home'][0] = 'text-2';*/
	
	$arrayOfSidebars['insta-home-qsearch'][0] = 'bapi_hp_search-2';
	return $arrayOfSidebars;
}
