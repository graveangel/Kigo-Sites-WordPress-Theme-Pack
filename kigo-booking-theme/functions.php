<?php
/**
 * Kigo Booking Theme functions and definitions.
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
 * Theme: Kigo Booking Theme
 * Version: instatheme 1.0
 * Date: 04-16-2013
 */
/*
*
* Creating the widgets for the Common Widget Areas
*
*/
function default_mainmenu() {

    $html = '<ul class="nav pull-right" id="primary">';
    $html .= '<li class="menu-item menu-item-type-post_type menu-item-object-page">';
    $html .= '<a href="' . esc_url( home_url() ) . '" title="' . __( 'Home', '' ) . '">';
    $html .= __( 'Home', '' );
    $html .= '</a>';
    $html .= '</li>';
    $html .= '<li class="menu-item menu-item-type-post_type menu-item-object-page">';
    $html .= '<a href="/rentals/rentalsearch/" title="' . __( 'Search', '' ) . '">';
    $html .= __( 'Search', '' );
    $html .= '</a>';
    $html .= '</li>';
    $html .= '<li class="menu-item menu-item-type-post_type menu-item-object-page">';
    $html .= '<a href="/rentals/allrentals/" title="' . __( 'All Rentals', '' ) . '">';
    $html .= __( 'All Rentals', '' );
    $html .= '</a>';
    $html .= '</li>';
    $html .= '</ul>';

    echo $html;

} // default_mainmenu

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
    $arrayOfSidebars['insta-bottom-social'][0] = '';
    $arrayOfSidebars['insta-footer'][0] = '';
    $arrayOfSidebars['insta-footer-1'][0] = 'kigo_social_icons-2';
    $arrayOfSidebars['insta-footer-4'][0] = 'kigo_custom_menu-2';
    return $arrayOfSidebars;
}
/* Creating widget for inner pages - this change the title for search */
function childtheme_override_presetwidgets_innerareas(){
    /*for insta-right-sidebar-search and insta-right-sidebar-other*/
    update_option( 'widget_bapi__search', array( 2 => array( 'title' => "Search" ),3 => array( 'title' => 'Search' )) );
    update_option( 'widget_bapi_detailoverview', array( 2 => array( 'title' => '' ),3 => array( 'title' => '' )) );

}
function childtheme_override_setwidgets_innerareas($arrayOfSidebars){
    $arrayOfSidebars['insta-right-sidebar-search'][0] = 'bapi__search-2';
    $arrayOfSidebars['insta-right-sidebar-other'][0] = 'bapi__search-3';
    $arrayOfSidebars['insta-right-sidebar-prop-detail'][0] = 'bapi_detailoverview-2';
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
    /* For Insta Home Qsearch */
    update_option( 'widget_bapi_hp_search', array( 2 => array( 'title' => "Search", 'text' =>'' )) );
}
/*
*
* Setting the widgets for the Home Page Widget Areas
* 
*/
function childtheme_override_setwidgets_hpareas($arrayOfSidebars) {
    $arrayOfSidebars['insta-left-home'][0] = 'bapi_hp_search-2';
    return $arrayOfSidebars;
}

/**
 *
 * Add a css to hide home elements: insta-top-fixed and footer
 *
 *
 */
function hide_home_elements(){
    wp_enqueue_style( 'hide_home_elements', get_stylesheet_directory_uri() . '/inc/css/hide-home-elements.css' );
}

add_action( 'wp_enqueue_scripts', 'hide_home_elements' );