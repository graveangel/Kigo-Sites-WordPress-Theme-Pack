<?php
/**
 * Insta theme01 functions and definitions.
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
 * Theme: instatheme01
 * Version: instatheme 1.0 
 * Date: 04-16-2013
 */


/*
*
* Creating the widgets for the Common Widget Areas
*
*/

function childtheme_override_presetwidgets_commonareas() {
	/*for insta-top-fix*/
	update_option( 'widget_bapi_header', array( 2 => array( 'title' => '' )) );
	/*for insta-header-left*/
	update_option( 'widget_bapi_hp_logo', array( 2 => array( 'title' => '' )) );
	/*for insta-header-right*/
	update_option( 'widget_mr-social-sharing-toolkit-follow-widget', array( 2 => array( 'title' => '' )) );
	/*for insta-footer*/
	update_option( 'widget_bapi_footer', array( 2 => array( 'title' => '' )) );
	
}

/*
*
* setting the widgets for the Common Widget Areas
*
*/

function childtheme_override_setwidgets_commonareas($arrayOfSidebars) {
		$arrayOfSidebars['insta-top-fixed'][0] = 'bapi_header-2';
		$arrayOfSidebars['insta-header-left'][0] = 'bapi_hp_logo-2';
		$arrayOfSidebars['insta-header-right'][0] = 'mr-social-sharing-toolkit-follow-widget-2';
		$arrayOfSidebars['insta-footer'][0] = 'bapi_footer-2';
		
		return $arrayOfSidebars;
}