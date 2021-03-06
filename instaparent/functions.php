<?php
/**
 * Insta Parent functions and definitions.
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
 * Theme: instaparent
 * Version: instaparent 1.0 
 * Date: 04-15-2013
 */
$theOptionIsString = is_string(get_option('bapi_solutiondata'));
if ($theOptionIsString) {
    $bapi_solutiondata = json_decode(get_option('bapi_solutiondata'), true);
    $siteIsLive = $bapi_solutiondata['Site']['IsLive'];
    $sitePrimaryURL = $bapi_solutiondata['PrimaryURL'];
    $siteSecureURL = $bapi_solutiondata['SecureURL'];
    $siteUniquePrefix = $bapi_solutiondata['UniquePrefix'];
}
if (function_exists('getTextDataArray')) {
    /* we get the array of textdata */
    $textDataArray = getTextDataArray();
}

/* lets get the name of the theme folder to see which theme it is */
$currentThemeName = substr(strrchr(get_stylesheet_directory(), "/"), 1);
$itsInstaTheme = strpos($currentThemeName, "ct-") !== FALSE ? FALSE : TRUE;

function instaparent_setup() {

    // This theme styles the visual editor with editor-style.css to match the theme style.
    add_editor_style();

    // Adds RSS feed links to <head> for posts and comments.
    add_theme_support('automatic-feed-links');

    // This theme uses wp_nav_menu() in one location.
    function register_my_menus() {
        register_nav_menus(
                array(
                    'primary' => __('Main Menu')
                )
        );
    }

    add_action('init', 'register_my_menus');

    /*
     * This theme supports custom background color and image, and here
     * we also set up the default background color.
     */
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
    ));

    // This theme uses a custom image size for featured images, displayed on "standard" posts.
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(624, 9999); // Unlimited height, soft crop
}

add_action('after_setup_theme', 'instaparent_setup');

/**
 * Enqueues scripts and styles for front-end.
 *
 */
function instaparent_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );	
		
	/*
	 * This Removed the jquery and jquery migrate scripts
	 * from the instathemes
	*/
	if ( !is_admin() ) {
		wp_deregister_script('jquery');
	}
	/* This register the PickADate Translate script in instasite
	*/
	wp_register_script( 'pickadate', wp_make_link_relative(get_template_directory_uri()).'/insta-common/js/bapi.ui.pickadate.translate.js', array('bapi-combined'), '', true );
    wp_enqueue_script( 'pickadate' );
	
	/*
	 * Loads our main stylesheet.
	 */
	//$relUrl = wp_make_link_relative(get_stylesheet_uri());
	//wp_enqueue_style( 'instaparent-style', $relUrl );	
}

add_action('wp_enqueue_scripts', 'instaparent_scripts_styles');

/**
 * Sets the Fav Icon.
 *
 */
if (function_exists('childtheme_override_set_favicon')) {

    /**
     * run the child override
     */
    function set_favicon() {
        childtheme_override_set_favicon();
    }

} else {

    function set_favicon() {
        /* Lets set the default favicon url */
        $relUrlfavIcon = wp_make_link_relative(get_template_directory_uri());
        $faviconUrl = $relUrlfavIcon . '/insta-common/images/favicon.ico';
        /* Load our theme options */
        $OptionsSelected = get_option('instaparent_theme_options');
        /* Check if another favicon was set */
        if (@$OptionsSelected['faviconurl'] != '') {
            /* change the favicon Url to the one set in the theme options */
            $faviconUrl = wp_make_link_relative($OptionsSelected['faviconurl']);
        }

        echo '<link rel="shortcut icon" href="' . $faviconUrl . '" />';
    }

}
add_action('wp_head', 'set_favicon');

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function instaparent_wp_title($title, $sep) {
    /* global $paged, $page; */

    if (is_feed())
        return $title;

    // Add the site name.
    //$title .= get_bloginfo( 'name' );
    // Add the site description for the home/front page.
    /* $site_description = get_bloginfo( 'description', 'display' );
      if ( $site_description && ( is_home() || is_front_page() ) )
      $title = "This is the Home"; */

    // Add a page number if necessary.
    /* if ( $paged >= 2 || $page >= 2 )
      $title = "$title $sep " . sprintf( __( 'Page %s', 'instaparent' ), max( $paged, $page ) ); */

    if (is_home() || is_front_page())
        $title = get_the_title();

    return $title;
}

add_filter('wp_title', 'instaparent_wp_title', 10, 2);

/**
 * Registers all our widget areas.
 *
 */
function instaparent_widgets_init() {
    global $itsInstaTheme;
    if (function_exists('childtheme_widgets_init')) :
        childtheme_widgets_init();
    endif;

    register_sidebar(array(
        'name' => __('Main Sidebar', 'instaparent'),
        'id' => 'main-sidebar',
        'description' => __('Sidebar for the Blog Page', 'instaparent'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div><hr/>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Top Fixed (Common)', 'instaparent'),
        'id' => 'insta-top-fixed',
        'description' => __('Fixed position at top of the screen (Possibly for weather widgets)', 'instaparent'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Bottom Fixed (Common)', 'instaparent'),
        'id' => 'insta-bottom-fixed',
        'description' => __('Fixed position at bottom of the screen (possibly for favorites)', 'instaparent'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Header Left (Common)', 'instaparent'),
        'id' => 'insta-header-left',
        'description' => __('Normal page header area (Logo, Title, etc) Left Side', 'instaparent'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Header Right (Common)', 'instaparent'),
        'id' => 'insta-header-right',
        'description' => __('Normal page header area (Logo, Title, etc) Right Side', 'instaparent'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Footer (Common)', 'instaparent'),
        'id' => 'insta-footer',
        'description' => __('The Footer of the page', 'instaparent'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    if ($itsInstaTheme) {
        register_sidebar(array(
            'name' => __('Kigo Footer 1 (Common)', 'instaparent'),
            'id' => 'insta-footer-1',
            'description' => __('Footer outer left area', 'instaparent'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        register_sidebar(array(
            'name' => __('Kigo Footer 2 (Common)', 'instaparent'),
            'id' => 'insta-footer-2',
            'description' => __('Footer middle left area', 'instaparent'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        register_sidebar(array(
            'name' => __('Kigo Footer 3 (Common)', 'instaparent'),
            'id' => 'insta-footer-3',
            'description' => __('Footer middle right area', 'instaparent'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        register_sidebar(array(
            'name' => __('Kigo Footer 4 (Common)', 'instaparent'),
            'id' => 'insta-footer-4',
            'description' => __('Footer outer right area', 'instaparent'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
    }
    register_sidebar(array(
        'name' => __('Kigo Left Home', 'instaparent'),
        'id' => 'insta-left-home',
        'description' => __('The Left Sidebar in the Home Page', 'instaparent'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Right Home', 'instaparent'),
        'id' => 'insta-right-home',
        'description' => __('The Right Content Below the Slideshow in the Home Page', 'instaparent'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Bottom Full Home', 'instaparent'),
        'id' => 'insta-bottom-full-home',
        'description' => __('The Bottom Wide Widget Zone below the left and right content in the Home Page', 'instaparent'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Bottom Left Home', 'instaparent'),
        'id' => 'insta-bottom-left-home',
        'description' => __('The Bottom Left Widget Zone in the Home Page', 'instaparent'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Bottom Middle Home', 'instaparent'),
        'id' => 'insta-bottom-middle-home',
        'description' => __('The Bottom Middle Widget Zone in the Home Page', 'instaparent'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Bottom Right Home', 'instaparent'),
        'id' => 'insta-bottom-right-home',
        'description' => __('The Bottom Right Widget Zone in the Home Page', 'instaparent'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Left Sidebar Search', 'instaparent'),
        'id' => 'insta-left-sidebar-search',
        'description' => __('The Left Sidebar Search of the Search Pages', 'instaparent'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Right Sidebar Search', 'instaparent'),
        'id' => 'insta-right-sidebar-search',
        'description' => __('The Right Sidebar Search of the Search Pages', 'instaparent'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Left Sidebar Property Detail', 'instaparent'),
        'id' => 'insta-left-sidebar-prop-detail',
        'description' => __('The Left Sidebar in the Property Detail Page', 'instaparent'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Right Sidebar Property Detail', 'instaparent'),
        'id' => 'insta-right-sidebar-prop-detail',
        'description' => __('The Right Sidebar in the Property Detail Page', 'instaparent'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Left Sidebar Other', 'instaparent'),
        'id' => 'insta-left-sidebar-other',
        'description' => __('The Left Sidebar in the Other Detail Pages', 'instaparent'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Right Sidebar Other', 'instaparent'),
        'id' => 'insta-right-sidebar-other',
        'description' => __('The Right Sidebar in the Other Detail Pages', 'instaparent'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Left Sidebar Content', 'instaparent'),
        'id' => 'insta-left-sidebar-content',
        'description' => __('The Left Sidebar Content in Content Pages', 'instaparent'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Right Sidebar Content', 'instaparent'),
        'id' => 'insta-right-sidebar-content',
        'description' => __('The Right Sidebar Content in Content Pages', 'instaparent'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Top Wide Content', 'instaparent'),
        'id' => 'insta-top-wide-content',
        'description' => __('The Top Wide Content in Content Pages', 'instaparent'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Bottom Wide Content', 'instaparent'),
        'id' => 'insta-bottom-wide-content',
        'description' => __('The Bottom Wide Content in Content Pages', 'instaparent'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Bottom Left Content', 'instaparent'),
        'id' => 'insta-bottom-left-content',
        'description' => __('The Bottom Left Content in Content Pages', 'instaparent'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Bottom Center Content', 'instaparent'),
        'id' => 'insta-bottom-center-content',
        'description' => __('The Bottom Center Content in Content Pages', 'instaparent'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Kigo Bottom Right Content', 'instaparent'),
        'id' => 'insta-bottom-right-content',
        'description' => __('The Bottom Right Content in Content Pages', 'instaparent'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}

add_action('widgets_init', 'instaparent_widgets_init');

/**
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when full-width template.
 * 2. Front Page template: thumbnail in use 
 * 3. White or empty background color to change the layout and spacing.
 * 4. Single or multiple authors.
 *
 *
 * @param array Existing class values.
 * @return array Filtered class values.
 */
function instaparent_body_class($classes) {
    global $post;
    $metaArray = get_post_meta($post->ID);

    /* checking if a particular key exist so we add a classname for static pages */
    if (!empty($metaArray) && !array_key_exists('bapi_page_id', $metaArray) &&
            !array_key_exists('bapikey', $metaArray) &&
            !array_key_exists('bapi_last_update', $metaArray)
    ) {
        $classes[] = "static-page";
    } else {
        $id = @$metaArray['bapi_page_id'][0];
        if (
                $id == "bapi_privacy_policy" ||
                $id == "bapi_tos" ||
                $id == "bapi_services" ||
                $id == "bapi_about_us" ||
                $id == "bapi_rental_policy" ||
                $id == "bapi_booking_terms" ||
                $id == "bapi_company_owner" ||
                $id == "bapi_company_guest" ||
                $id == "bapi_travel_insurance") {
            $classes[] = "static-page";
        }
    }

    $background_color = get_background_color();

    if (is_page_template('page-templates/full-width.php'))
        $classes[] = 'full-width';

    if (is_page_template('page-templates/front-page.php')) {
        $classes[] = 'template-front-page';
        if (has_post_thumbnail())
            $classes[] = 'has-post-thumbnail';
    }

    if (empty($background_color))
        $classes[] = 'custom-background-empty';
    elseif (in_array($background_color, array('fff', 'ffffff')))
        $classes[] = 'custom-background-white';

    if (!is_multi_author())
        $classes[] = 'single-author';

    /* lets get the name of the theme folder to the classes */
    $currentThemeName = substr(strrchr(get_stylesheet_directory(), "/"), 1);
    $classes[] = $currentThemeName;

    return $classes;
}

add_filter('body_class', 'instaparent_body_class');

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function instaparent_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
}

add_action('customize_register', 'instaparent_customize_register');

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 */
function instaparent_customize_preview_js() {
    wp_enqueue_script('instaparent-customizer', get_template_directory_uri() . '/insta-common/js/theme-customizer.js', array('customize-preview'), '20120827', true);
}

add_action('customize_preview_init', 'instaparent_customize_preview_js');

/**
 * Functions that create a bootstrap menu dropdown.
 *
 */
//This Filter Adds a Custom Classname to the menu items that are parents
function add_menu_parent_class($items) {
    //we create an array with the items that are parents
    $parents = array();
    foreach ($items as $item) {
        //we check the items
        if ($item->menu_item_parent && $item->menu_item_parent > 0) {
            //if its a parent we add it to the array
            $parents[] = $item->menu_item_parent;
        }
    }
    //we add the classname to all the items in the Parents array, since they are parents
    foreach ($items as $item) {
        if (in_array($item->ID, $parents)) {
            $item->classes[] = 'dropdown';
        }
    }

    return $items;
}

add_filter('wp_nav_menu_objects', 'add_menu_parent_class');

//this is the Custom Walker function that adds several conditional classes to the nav menu
class instaparent_walker_nav_menu extends Walker_Nav_Menu {

// add classes to ul sub-menus
    function start_lvl(&$output, $depth = 0, $args = array()) {
        // depth dependent classes
        $indent = ( $depth > 0 ? str_repeat("\t", $depth) : '' ); // code indent
        $display_depth = ( $depth + 1); // because it counts the first submenu as 0
        $classes = array('dropdown-menu',
            'sub-menu',
            ( $display_depth % 2 ? 'menu-odd' : 'menu-even' ),
            ( $display_depth >= 2 ? 'sub-sub-menu' : '' ),
            'menu-depth-' . $display_depth
        );
        $class_names = implode(' ', $classes);

        // build html
        $output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
    }

// add main/sub classes to li's and links
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        global $wp_query, $siteIsLive, $sitePrimaryURL, $siteSecureURL, $siteUniquePrefix, $theOptionIsString;
        $indent = ( $depth > 0 ? str_repeat("\t", $depth) : '' ); // code indent
        // depth dependent classes
        $depth_classes = array(
            ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
            ( $depth >= 2 ? 'sub-sub-menu-item' : '' ),
            ( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
            'menu-item-depth-' . $depth
        );
        $depth_class_names = esc_attr(implode(' ', $depth_classes));

        // passed classes
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $class_names = esc_attr(implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item)));

        // build html
        $output .= $indent . '<li id="nav-menu-item-' . $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';

        // we get the URL

        $menuItemUrl = esc_attr($item->url);
        $currentPageSecure = false;
        if (strpos($menuItemUrl, 'https') !== false) {
            $currentPageSecure = true;
        }
        /* are we in a secure page? */
        if ($theOptionIsString && $currentPageSecure) {
            /* its the site not live? */
            if ($siteIsLive != 1) {
                /* site not live use imbookingsecure */
                $menuItemUrl = str_replace('https', 'http', $menuItemUrl);
            } else {
                //The site is live!!
                /* its the user logged in? */
                if (is_user_logged_in()) {
                    /* logged in use imbookingsecure url */
                    if ($siteSecureURL != '') {
                        $menuItemUrl = 'http://' . $siteSecureURL . wp_make_link_relative($menuItemUrl);
                    } else {
                        $menuItemUrl = 'http://' . $siteUniquePrefix . '.imbookingsecure.com' . wp_make_link_relative($menuItemUrl);
                    }
                } else {
                    /* not logged in use base url */
                    $menuItemUrl = 'http://' . $sitePrimaryURL . wp_make_link_relative($menuItemUrl);
                    $menuItemUrl = str_replace('https', 'http', $menuItemUrl);
                }
            }
        }

        // link attributes
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .=!empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .=!empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .=!empty($item->url) ? ' href="' . $menuItemUrl . '"' : '';

        // normal link
        $theLink = '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s';

        //Lets check if the Array of classes is not empty
        if (!empty($item->classes)) {
            // Check if menu item has the class dropdown (this would mean is a parent)
            if (in_array("dropdown", $classes)) {
                // These lines adds the custom classnames and attributes to the menu item link
                $attributes .= ' class="menu-link dropdown-toggle ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';
                $attributes .= ' data-toggle="dropdown"';

                //link with the caret icon
                $theLink = '%1$s<a%2$s>%3$s%4$s%5$s <b class="caret"></b></a>%6$s';
            } else {
                $attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';
            }
        }

        $item_output = sprintf($theLink, $args->before, $attributes, $args->link_before, apply_filters('the_title', $item->title, $item->ID), $args->link_after, $args->after
        );

        // build html
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

}

if (!function_exists('instaparent_post_meta_data')) :

    /**
     * This function prints post meta data.
     */
    function instaparent_post_meta_data() {
        printf(__('<span class="%1$s">Posted on </span>%2$s<span class="%3$s"> by </span>%4$s', 'instaparent'), 'meta-prep meta-prep-author posted', sprintf('<a href="%1$s" title="%2$s" rel="bookmark"><span class="timestamp">%3$s</span></a>', get_permalink(), esc_attr(get_the_time()), get_the_date()
                ), 'byline', sprintf('<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>', get_author_posts_url(get_the_author_meta('ID')), sprintf(esc_attr__('View all posts by %s', 'instaparent'), get_the_author()), get_the_author()
                )
        );
    }

endif;

/*
*
* Creating the widgets for the Common Widget Areas
*
*/
if ( function_exists( 'childtheme_override_presetwidgets_commonareas') )  { 
    /**
     * run the child override
     */
    function presetwidgets_commonareas() {
        childtheme_override_presetwidgets_commonareas();
    }

} else {

    /**
     * Run the default Parent function
     */
    function presetwidgets_commonareas() {
        /* for insta-header-left */
        update_option('widget_bapi_hp_logo', array(2 => array('title' => '')));
        /* for insta-header-right */
        update_option('widget_mr-social-sharing-toolkit-follow-widget', array(2 => array('title' => '')));
        /* for insta-footer */
        update_option('widget_bapi_footer', array(2 => array('title' => '')));

        /* for insta-footer-1 */
        update_option('widget_kigo_social_icons', array());
        /* for insta-footer-4 */
        update_option('widget_kigo_custom_menu', array(2 => array('nav_menu' => 'main-navigation-menu')));
    }

}


/*
 *
 * Creating the widgets for the Home Page Widget Areas
 * Widgets areas that are in front-page.php
 */
if (function_exists('childtheme_override_presetwidgets_hpareas')) {

    /**
     * run the child override
     */
    function presetwidgets_hpareas() {
        childtheme_override_presetwidgets_hpareas();
    }

} else {

    /**
     * Run the default Parent function
     */
    function presetwidgets_hpareas() {
        if (function_exists('getTextDataArray')) {
            /* we get the array of textdata */
            $textDataArray = getTextDataArray();
        }
        /* for insta-left-home */
        update_option('widget_bapi_hp_search', array(2 => array('title' => $textDataArray["Search"])));
        update_option('widget_bapi_weather_widget', array(2 => array('title' => $textDataArray["Weather"])));
        update_option('widget_bapi_specials_widget', array(2 => array('title' => $textDataArray["Special Offers"])));
        update_option('widget_insta_latest_blog_posts', array(2 => array('title' => $textDataArray["Blog"], 'numberOfPosts' => 1, 'rowSize' => 1, 'displayImage' => 1, 'displayDate' => 0, 'displayTitle' => 1, 'postLinkString' => 'Read More')));
        /* for insta-right-home */
        update_option('widget_bapi_featured_properties', array(2 => array('title' => $textDataArray["Featured Properties"], 'text' => 4, 'rowsize' => 2)));
        update_option('widget_bapi_property_finders', array(2 => array('title' => $textDataArray["Property Finders"], 'text' => 3, 'rowsize' => 3)));
    }

}

/*

 *
 * Creating the widgets for the Inner Pages Widget Areas
 * Widgets areas that are in the templates found in /page-templates/
 * with the exception of front-page.php which it has his own function
 */
if (function_exists('childtheme_override_presetwidgets_innerareas')) {

    /**
     * run the child override
     */
    function presetwidgets_innerareas() {
        childtheme_override_presetwidgets_innerareas();
    }

} else {

    /**
     * Run the default Parent function
     */
    function presetwidgets_innerareas() {
        if (function_exists('getTextDataArray')) {
            /* we get the array of textdata */
            $textDataArray = getTextDataArray();
        }
        /* for insta-right-sidebar-search and insta-right-sidebar-other */
        update_option('widget_bapi__search', array(2 => array('title' => $textDataArray["Revise Search"]), 3 => array('title' => 'Search')));
        /* for insta-right-sidebar-search, insta-right-sidebar-prop-detail, insta-right-sidebar-other */
        update_option('widget_bapi_inquiry_form', array(2 => array('title' => $textDataArray["Have a Question?"]), 3 => array('title' => 'Have a Question?'), 4 => array('title' => 'Have a Question?')));
        /* for insta-right-sidebar-prop-detail and insta-right-sidebar-other */
        update_option('widget_bapi_detailoverview', array(2 => array('title' => ''), 3 => array('title' => '')));

        /* for insta-right-sidebar-prop-detail */
        update_option('widget_bapi_similar_properties', array(2 => array('title' => $textDataArray["Similar Properties"])));

        /* for main-sidebar */
        update_option('widget_search', array(2 => array('title' => '')));
        update_option('widget_categories', array(2 => array('title' => '')));
    }

}

/*
 *
 * Setting the Images of the Slideshow
 * 
 */
if (function_exists('childtheme_override_setSlideshowImages')) {

    /**
     * run the child override
     */
    function setSlideshowImages() {
        childtheme_override_setSlideshowImages();
    }

} else {

    /**
     * Run the default Parent function
     */
    function setSlideshowImages() {

        //setting default slideshow images
        /* There would be 2 states, 
          -There are no images meaning its the initial setup
          -All 3 images were populated and need to change to a selected
          preset */
        if (
                get_option('bapi_slideshow_image1') == '' &&
                get_option('bapi_slideshow_image2') == '' &&
                get_option('bapi_slideshow_image3') == '' ||
                checkIfUrlIsDefault(get_option('bapi_slideshow_image1')) &&
                checkIfUrlIsDefault(get_option('bapi_slideshow_image2')) &&
                checkIfUrlIsDefault(get_option('bapi_slideshow_image3'))) {

            if (isset($_GET['presetpreview']) && strpos($_GET['presetpreview'], 'style') !== false && strlen($_GET['presetpreview']) <= 7) {

                $styleName = htmlspecialchars($_GET['presetpreview']);
            } else {

                /* we get the options from the database */
                $OptionsSelected = get_option('instaparent_theme_options');
                /* we get the preset selected which is in radioinput */
                $styleName = $OptionsSelected['presetStyle'];
            }

            if (isset($styleName) && $styleName != "default" && $styleName != "") {
                /* if we are here the slideshow urls are empty or contain a default url
                  and the option selected in the theme options pages was not 'default'
                  lets check if the urls that are in the fields are not the ones we are
                  going to put, so we dont do 4 querys putting replacing what we already
                  have. we also need to check if this is theme04 which uses larger images
                 */

                if (get_option('bapi_slideshow_image1') == '' || strpos(get_option('bapi_slideshow_image1'), $styleName) === false) {
                    /* lets get the name of the theme folder to see which theme it is */
                    $currentThemeName = substr(strrchr(get_stylesheet_directory(), "/"), 1);
                    $largerImages = '';
                    if ($currentThemeName == 'instatheme04') {
                        $largerImages = "/larger";
                    }
                    update_option('bapi_slideshow_image1', '../wp-content/themes/instaparent/insta-common/themeoptions/presets/' . $styleName . '/images/slideshow' . $largerImages . '/1.jpg');
                    update_option('bapi_slideshow_image2', '../wp-content/themes/instaparent/insta-common/themeoptions/presets/' . $styleName . '/images/slideshow' . $largerImages . '/2.jpg');
                    update_option('bapi_slideshow_image3', '../wp-content/themes/instaparent/insta-common/themeoptions/presets/' . $styleName . '/images/slideshow' . $largerImages . '/3.jpg');
                }
            } else {

                update_option('bapi_slideshow_image1', '../wp-content/themes/instaparent/insta-common/images/slideshow/1.jpg');
                update_option('bapi_slideshow_image2', '../wp-content/themes/instaparent/insta-common/images/slideshow/2.jpg');
                update_option('bapi_slideshow_image3', '../wp-content/themes/instaparent/insta-common/images/slideshow/3.jpg');
            }
        }
    }

}
/*
  This function is already hooked to the Switch Theme Action
  we need to hook it to the updated theme options action
  but the wp_head action would do for now
 */
add_action('wp_head', 'setSlideshowImages');

function checkIfUrlIsDefault($Url) {
    if (strpos($Url, '/wp-content/themes/instaparent/insta-common/') === false) {
        return false;
    }

    return true;
}

/*
 *
 * setting the widgets for the Common Widget Areas
 *
 */
if (function_exists('childtheme_override_setwidgets_commonareas')) {

    /**
     * run the child override
     */
    function setwidgets_commonareas($arrayOfSidebars) {
        return childtheme_override_setwidgets_commonareas($arrayOfSidebars);
    }

} else {

    /**
     * Run the default Parent function
     */
    function setwidgets_commonareas($arrayOfSidebars) {
        $arrayOfSidebars['insta-header-left'][0] = 'bapi_hp_logo-2';
        $arrayOfSidebars['insta-header-right'][0] = 'mr-social-sharing-toolkit-follow-widget-2';
        $arrayOfSidebars['insta-footer'][0] = 'bapi_footer-2';

        $arrayOfSidebars['insta-footer-1'][0] = 'kigo_social_icons-2';
        $arrayOfSidebars['insta-footer-4'][0] = 'kigo_custom_menu-2';

        return $arrayOfSidebars;
    }

}

/*
 *
 * Setting the widgets for the Home Page Widget Areas
 * 
 */
if (function_exists('childtheme_override_setwidgets_hpareas')) {

    /**
     * run the child override
     */
    function setwidgets_hpareas($arrayOfSidebars) {
        return childtheme_override_setwidgets_hpareas($arrayOfSidebars);
    }

} else {

    /**
     * Run the default Parent function
     */
    function setwidgets_hpareas($arrayOfSidebars) {
        $arrayOfSidebars['insta-left-home'][0] = 'bapi_hp_search-2';
        $arrayOfSidebars['insta-left-home'][1] = 'bapi_weather_widget-2';
        $arrayOfSidebars['insta-left-home'][2] = 'bapi_specials_widget-2';
        $arrayOfSidebars['insta-left-home'][3] = 'insta_latest_blog_posts-2';
        $arrayOfSidebars['insta-right-home'][0] = 'bapi_featured_properties-2';
        $arrayOfSidebars['insta-right-home'][1] = 'bapi_property_finders-2';
        return $arrayOfSidebars;
    }

}

/*
 *
 * Setting the widgets for the Inner Pages Widget Areas
 */
if (function_exists('childtheme_override_setwidgets_innerareas')) {

    /**
     * run the child override
     */
    function setwidgets_innerareas($arrayOfSidebars) {
        return childtheme_override_setwidgets_innerareas($arrayOfSidebars);
    }

} else {

    /**
     * Run the default Parent function
     */
    function setwidgets_innerareas($arrayOfSidebars) {
        $arrayOfSidebars['insta-right-sidebar-search'][0] = 'bapi__search-2';
        $arrayOfSidebars['insta-right-sidebar-search'][1] = 'bapi_inquiry_form-2';
        $arrayOfSidebars['insta-right-sidebar-prop-detail'][0] = 'bapi_detailoverview-2';
        $arrayOfSidebars['insta-right-sidebar-prop-detail'][1] = 'bapi_inquiry_form-3';
        $arrayOfSidebars['insta-right-sidebar-prop-detail'][2] = 'bapi_similar_properties-2';
        $arrayOfSidebars['insta-right-sidebar-other'][0] = 'bapi_detailoverview-3';
        $arrayOfSidebars['insta-right-sidebar-other'][1] = 'bapi__search-3';
        $arrayOfSidebars['insta-right-sidebar-other'][2] = 'bapi_inquiry_form-4';
        $arrayOfSidebars['main-sidebar'][0] = 'search-2';
        $arrayOfSidebars['main-sidebar'][1] = 'categories-2';
        return $arrayOfSidebars;
    }

}

/**
 * set new widgets on theme activate 
 */
function set_default_theme_widgets() {
    //Clear the Sidebars of widgets
    update_option('sidebars_widgets', NULL);

    // Get the Array of Sidebars we have
    $active_widgets = get_option('sidebars_widgets');

    presetwidgets_commonareas();
    $active_widgets = setwidgets_commonareas($active_widgets);

    presetwidgets_hpareas();
    $active_widgets = setwidgets_hpareas($active_widgets);

    presetwidgets_innerareas();
    $active_widgets = setwidgets_innerareas($active_widgets);

    setSlideshowImages();

    /* Define default values for customizer settings */
    $mods = array(
        //Social settings
        'url-facebook' => '#',
        'url-twitter' => '#',
        'url-google' => '#',
        'url-blog' => '#',
    );

    foreach ($mods as $name => $val) {
        if (!get_theme_mod($name)) {
            set_theme_mod($name, $val);
        }
    }

    //lets update the current array of sidebars
    update_option('sidebars_widgets', $active_widgets);
}

add_action('after_switch_theme', 'set_default_theme_widgets');
add_action('wpmu_create_blog', 'set_default_theme_widgets');

function admin_instaparent_scripts_styles() {
    /**
     * Open Media Upload when selecting images for the slideshow.
     */
    if (is_admin()) {
        if (function_exists('wp_enqueue_media')) {
            wp_enqueue_script('custom-header');
            wp_enqueue_media();
        } else {
            wp_enqueue_style('thickbox');
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
        }
    }
}

add_action('admin_enqueue_scripts', 'admin_instaparent_scripts_styles');

/* lets execute this only if we are in the admin back end */
if (is_admin()) {
// Load up our awesome theme options
    if (function_exists('childtheme_override_get_theme_options')) {
        /**
         * run the child override
         */
        childtheme_override_get_theme_options();
    } else {
        /* if ( file_exists(get_template_directory_uri() . '/theme-options.php') ) {    
          }else{
          } */

        require_once ( get_template_directory() . '/insta-common/themeoptions/theme-options.php' );
    }
}

/**
 * Enqueues the preset styles for front-end.
 *
 */
function instaparent_preset_styles() {
	wp_enqueue_style( 'default-styles', get_stylesheet_uri() );
	/* we will put all the custom CSS here and output it in the <head> */
	$CustomCSSstyle="";
	/* lets get the name of the theme folder to see which theme it is */
		$currentThemeName = substr(strrchr(get_stylesheet_directory(), "/"), 1);
	
	/* lets check if the variable has been set and it contains the word style and is 7 chars long */
	if (isset($_GET['presetpreview']) && strpos($_GET['presetpreview'],'style') !== false && strlen($_GET['presetpreview']) <= 7) {	
	
	$styleName = htmlspecialchars($_GET['presetpreview']);
	
	}else{
		/* we get the options from the database */
		$OptionsSelected = get_option('instaparent_theme_options');
		/* we get the preset selected which is in radioinput*/
		$styleName = $OptionsSelected['presetStyle'];
		/* we get the menu style selected */
		$menuStyleName = @$OptionsSelected['menustyles'];
                /* we get the menu style selected */
		$footerStyleName = @$OptionsSelected['footerstyles'];
		/* we get the Featured Properties Selected */
		$FPstyle = @$OptionsSelected['FPstyles'];
                /* we get the h1 and titles color Selected */
		$h1styles = @$OptionsSelected['h1styles'];
                /* we get the h2 titles color Selected */
		$h2styles = @$OptionsSelected['h2styles'];
                /* we get the paragraphs color Selected */
		$paragraphs_styles = @$OptionsSelected['paragraphs_styles'];
		/* we get the Logo Size Selected */
		$logoSize = @$OptionsSelected['logoSize'];
		$customlogoSize = @$OptionsSelected['logoSize_custom'];
		/* we Custom CSS option */
		$customCSS = @$OptionsSelected['customCSS'];
	}
	
	if(isset($styleName) && $styleName!="default" && $styleName!=""){
		/* we use the selected preset name in the PATH to the style.css for that preset, this was removed array( 'instaparent-style' ) */
	wp_enqueue_style( $styleName . '-preset', get_template_directory_uri() . '/insta-common/themeoptions/presets/'.$styleName.'/style.css', FALSE, '1.0', 'all' );
	}
/* an array with the CSS for White and Gray Options */
$menustyles_CSS = array(
	'menustyle01' => array(
		'instatopfixed' => 'body #insta-top-fixed,body #insta-top-fixed .widget_bapi_weather_widget{background:#fff;color:#333;}body #insta-top-fixed .top-links a,body #insta-top-fixed .top-links a:hover,body #insta-top-fixed .halflings.white i:before{color:#333;}body #insta-top-fixed .navbar-inverse .brand,body #insta-top-fixed .navbar-inverse .nav > li > a{color:#333;text-shadow:none;}
body #insta-top-fixed .navbar-inverse .nav li.dropdown.open > .dropdown-toggle,
body #insta-top-fixed .navbar-inverse .nav li.dropdown.active > .dropdown-toggle,
body #insta-top-fixed .navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle{background:none;color:#333;}
	body #insta-top-fixed .navbar-inverse .nav li.dropdown > .dropdown-toggle .caret,
body #insta-top-fixed .navbar-inverse .nav li.dropdown > a:hover .caret,
body #insta-top-fixed .navbar .nav li.dropdown.open > .dropdown-toggle .caret,
body #insta-top-fixed .navbar .nav li.dropdown.active > .dropdown-toggle .caret,
body #insta-top-fixed .navbar .nav li.dropdown.open.active > .dropdown-toggle .caret,body #insta-top-fixed .top-links .caret{border-bottom-color:#333;border-top-color:#333;}',
            'brandmenu' => 'body #insta-header .navbar-inverse .navbar-inner,
body #insta-header .navbar-inverse .nav li.dropdown.open > .dropdown-toggle,
body #insta-header .navbar-inverse .nav li.dropdown.active > .dropdown-toggle,
body #insta-header .navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle{background:#fff;border-color:#fff;}body #insta-header .navbar-inverse .nav > li > a{color:#333;text-shadow:none;}body #insta-header .navbar-inverse .nav li.dropdown > .dropdown-toggle .caret{border-bottom-color:#333;border-top-color:#333;}',
            'navbar' => 'body .navbar-inverse .navbar-inner,
body .navbar-inverse .nav li.dropdown.open > .dropdown-toggle,
body .navbar-inverse .nav li.dropdown.active > .dropdown-toggle,
body .navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle{background:#fff;border-color:#fff;}body #navigation.navbar-inverse .nav > li > a{color:#333;text-shadow:none;}body #navigation.navbar-inverse .nav li.dropdown > .dropdown-toggle .caret{border-bottom-color:#333;border-top-color:#333;}'
        ),
        'menustyle02' => array(
            'instatopfixed' => "body #insta-top-fixed{background: #ffffff;
background: -moz-linear-gradient(top,  #ffffff 0%, #cccccc 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#cccccc));
background: -webkit-linear-gradient(top,  #ffffff 0%,#cccccc 100%);
background: -o-linear-gradient(top,  #ffffff 0%,#cccccc 100%);
background: -ms-linear-gradient(top,  #ffffff 0%,#cccccc 100%);
background: linear-gradient(to bottom,  #ffffff 0%,#cccccc 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#cccccc',GradientType=0 );
color:#333;}body #insta-top-fixed .top-links a,body #insta-top-fixed .top-links a:hover,body #insta-top-fixed .halflings.white i:before,body #insta-top-fixed .widget_bapi_weather_widget{color:#333;}body #insta-top-fixed .navbar-inverse .brand,body #insta-top-fixed .navbar-inverse .nav > li > a{color:#333;}
body #insta-top-fixed .navbar-inverse .nav li.dropdown.open > .dropdown-toggle,
body #insta-top-fixed .navbar-inverse .nav li.dropdown.active > .dropdown-toggle,
body #insta-top-fixed .navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle{background:none;color:#333;}
	body #insta-top-fixed .navbar-inverse .nav li.dropdown > .dropdown-toggle .caret,
body #insta-top-fixed .navbar-inverse .nav li.dropdown > a:hover .caret,
body #insta-top-fixed .navbar .nav li.dropdown.open > .dropdown-toggle .caret,
body #insta-top-fixed .navbar .nav li.dropdown.active > .dropdown-toggle .caret,
body #insta-top-fixed .navbar .nav li.dropdown.open.active > .dropdown-toggle .caret,body #insta-top-fixed .top-links .caret{border-bottom-color:#333;border-top-color:#333;}",
            'brandmenu' => "body #insta-header .navbar-inverse .navbar-inner,
body #insta-header .navbar-inverse .nav li.dropdown.open > .dropdown-toggle,
body #insta-header .navbar-inverse .nav li.dropdown.active > .dropdown-toggle,
body #insta-header .navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle{background:background: #ffffff;
background: -moz-linear-gradient(top,  #ffffff 0%, #cccccc 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#cccccc));
background: -webkit-linear-gradient(top,  #ffffff 0%,#cccccc 100%);
background: -o-linear-gradient(top,  #ffffff 0%,#cccccc 100%);
background: -ms-linear-gradient(top,  #ffffff 0%,#cccccc 100%);
background: linear-gradient(to bottom,  #ffffff 0%,#cccccc 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#cccccc',GradientType=0 );border-color:#ccc;}body #insta-header .navbar-inverse .nav > li > a{color:#333;text-shadow:none;}body #insta-header .navbar-inverse .nav li.dropdown > .dropdown-toggle .caret{border-bottom-color:#333;border-top-color:#333;}",
            'navbar' => "body .navbar-inverse .navbar-inner,
body .navbar-inverse .nav li.dropdown.open > .dropdown-toggle,
body .navbar-inverse .nav li.dropdown.active > .dropdown-toggle,
body .navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle{background:background: #ffffff;
background: -moz-linear-gradient(top,  #ffffff 0%, #cccccc 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#cccccc));
background: -webkit-linear-gradient(top,  #ffffff 0%,#cccccc 100%);
background: -o-linear-gradient(top,  #ffffff 0%,#cccccc 100%);
background: -ms-linear-gradient(top,  #ffffff 0%,#cccccc 100%);
background: linear-gradient(to bottom,  #ffffff 0%,#cccccc 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#cccccc',GradientType=0 );border-color:#ccc;}body #navigation.navbar-inverse .nav > li > a{color:#333;text-shadow:none;}body #navigation.navbar-inverse .nav li.dropdown > .dropdown-toggle .caret{border-bottom-color:#333;border-top-color:#333;}"
        )
    );

    /* we check if the var is set or empty or if it is the default option */
    if (isset($menuStyleName) && !empty($menuStyleName) && $menuStyleName != 'default') {
        /* lets create a var for the CSS */
        $theStyleForTheMenu = '';

        /* check if they selected custom */
        if ($menuStyleName == 'customMenuStyle') {

            /* we get the hex value for the background color selected */
            $menuBgColor = $OptionsSelected['menuBackgroundColor'];
            /* we get the hex value for the text color selected */
            $menuTxtColor = $OptionsSelected['menuTextColor'];
            /* we get the color for the Menu Hover background */
            $MenuHoverColor = $OptionsSelected['menuHoverColor'];
            /* we get the color for the Menu Hover text */
            $MenuHoverTextColor = $OptionsSelected['menuHoverTextColor'];

            /* check if this theme is theme02 which uses brandmenu */
            if ($currentThemeName == 'instatheme02') {
                $theStyleForTheMenu = 'body #insta-header .navbar-inverse .navbar-inner,
body #insta-header .navbar-inverse .nav li.dropdown.open > .dropdown-toggle,
body #insta-header .navbar-inverse .nav li.dropdown.active > .dropdown-toggle,
body #insta-header .navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle{background:' . $menuBgColor . ';border-color:' . $menuBgColor . ';}body #insta-header .navbar-inverse .nav > li > a{color:' . $menuTxtColor . ';text-shadow:none;}body #insta-header .navbar-inverse .nav li.dropdown > .dropdown-toggle .caret{border-bottom-color:' . $menuTxtColor . ';border-top-color:' . $menuTxtColor . ';}#insta-header .dropdown-menu li > a:hover,#insta-header .dropdown-menu li > a:focus,#insta-header .dropdown-submenu:hover > a{background:' . $MenuHoverColor . ';color:' . $MenuHoverTextColor . ';}';
            } else {
                if ($currentThemeName == 'instatheme05') {
                    $theStyleForTheMenu = 'body .navbar-inverse .navbar-inner,
body .navbar-inverse .nav li.dropdown.open > .dropdown-toggle,
body .navbar-inverse .nav li.dropdown.active > .dropdown-toggle,
body .navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle{background:' . $menuBgColor . ';border-color:' . $menuBgColor . ';}body #navigation.navbar-inverse .nav > li > a{color:' . $menuTxtColor . ';text-shadow:none;}body #navigation.navbar-inverse .nav li.dropdown > .dropdown-toggle .caret{border-bottom-color:' . $menuTxtColor . ';border-top-color:' . $menuTxtColor . ';}#navigation .dropdown-menu li > a:hover,#navigation .dropdown-menu li > a:focus,#navigation .dropdown-submenu:hover > a{background:' . $MenuHoverColor . ';color:' . $MenuHoverTextColor . ';}';
                } else {

                    $theStyleForTheMenu = 'body #insta-top-fixed{background:' . $menuBgColor . ';color:' . $menuTxtColor . ';}body #insta-top-fixed .top-links a,body #insta-top-fixed .top-links a:hover,body #insta-top-fixed .halflings.white i:before,body #insta-top-fixed .top-links #wishListLink .halflings i:before,body #insta-top-fixed .widget_bapi_weather_widget{color:' . $menuTxtColor . ';}body #insta-top-fixed .navbar-inverse .brand,body #insta-top-fixed .navbar-inverse .nav > li > a{color:' . $menuTxtColor . ';text-shadow:none;}
body #insta-top-fixed .navbar-inverse .nav li.dropdown.open > .dropdown-toggle,
body #insta-top-fixed .navbar-inverse .nav li.dropdown.active > .dropdown-toggle,
body #insta-top-fixed .navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle{background:none;color:' . $menuTxtColor . ';}
	body #insta-top-fixed .navbar-inverse .nav li.dropdown > .dropdown-toggle .caret,
body #insta-top-fixed .navbar-inverse .nav li.dropdown > a:hover .caret,
body #insta-top-fixed .navbar .nav li.dropdown.open > .dropdown-toggle .caret,
body #insta-top-fixed .navbar .nav li.dropdown.active > .dropdown-toggle .caret,
body #insta-top-fixed .navbar .nav li.dropdown.open.active > .dropdown-toggle .caret,body #insta-top-fixed .top-links .caret{border-bottom-color:' . $menuTxtColor . ';border-top-color:' . $menuTxtColor . ';}#insta-top-fixed .dropdown-menu li > a:hover,#insta-top-fixed .dropdown-menu li > a:focus,#insta-top-fixed .dropdown-submenu:hover > a{background:' . $MenuHoverColor . ';color:' . $MenuHoverTextColor . ';}';
                }
            }
        } else {
            /* check if this theme is theme02 which uses brandmenu */
            if ($currentThemeName == 'instatheme02') {
                $theStyleForTheMenu = $menustyles_CSS[$menuStyleName]['brandmenu'];
            } else {
                /* check if this theme is theme05 which uses navbar */
                if ($currentThemeName == 'instatheme05') {
                    $theStyleForTheMenu = $menustyles_CSS[$menuStyleName]['navbar'];
                } else {
                    $theStyleForTheMenu = $menustyles_CSS[$menuStyleName]['instatopfixed'];
                }
            }
        }
        /* add the CSS to the Custom CSS var so it gets outputted */
        $CustomCSSstyle = $CustomCSSstyle . $theStyleForTheMenu;
    }

    /* an array with the CSS for White and Gray Options */
    $footerstyles_CSS = array(
        'footerstyle01' => 'body #insta-footer{background:#fff;color:#333;}body #insta-footer a,body #insta-footer .widget_bapi_footer .footer-links a,body #insta-footer .halflings.white i:before,body #insta-footer a:hover,body #insta-footer .widget_bapi_footer .footer-links a:hover{color:#333;}body #insta-footer .top-links .caret {border-bottom-color: #333;border-top-color: #333;}',
        'footerstyle02' => "body #insta-footer{background: #ffffff;
background: -moz-linear-gradient(top,  #ffffff 0%, #cccccc 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#cccccc));
background: -webkit-linear-gradient(top,  #ffffff 0%,#cccccc 100%);
background: -o-linear-gradient(top,  #ffffff 0%,#cccccc 100%);
background: -ms-linear-gradient(top,  #ffffff 0%,#cccccc 100%);
background: linear-gradient(to bottom,  #ffffff 0%,#cccccc 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#cccccc',GradientType=0 );
color:#333;}body #insta-footer a,body #insta-footer .widget_bapi_footer .footer-links a,body #insta-footer a:hover,body #insta-footer .widget_bapi_footer .footer-links a:hover,body #insta-footer .halflings.white i:before{color:#333;}body #insta-footer .top-links .caret {border-bottom-color: #333;border-top-color: #333;}"
    );

    /* we check if the var is set or empty or if it is the default option */
    if (isset($footerStyleName) && !empty($footerStyleName) && $footerStyleName != 'default') {
        /* lets create a var for the CSS */
        $theStyleForTheFooter = '';

        /* check if they selected custom */
        if ($footerStyleName == 'customFooterStyle') {

            /* we get the hex value for the background color selected */
            $footerBgColor = $OptionsSelected['footerBackgroundColor'];
            /* we get the hex value for the text color selected */
            $footerTxtColor = $OptionsSelected['footerTextColor'];
            /* we get the color for the Menu Hover background */
            $footerLinkTextColor = empty($OptionsSelected['footerLinkTextColor']) ? $footerTxtColor : $OptionsSelected['footerLinkTextColor'];
            /* we get the color for the Menu Hover text */
            $footerLinkHoverTextColor = empty($OptionsSelected['footerLinkHoverTextColor']) ? $footerTxtColor : $OptionsSelected['footerLinkHoverTextColor'];

            $theStyleForTheFooter = 'body #insta-footer{background:' . $footerBgColor . ';color:' . $footerTxtColor . ';}'
                    . 'body #insta-footer a,'
                    . 'body #insta-footer .widget_bapi_footer .footer-links a{color:' . $footerLinkTextColor . ';}'
                    . 'body #insta-footer a:hover,'
                    . 'body #insta-footer .widget_bapi_footer .footer-links a:hover{color:' . $footerLinkHoverTextColor . ';}'
                    . 'body #insta-footer .halflings.white i:before{color:' . $footerTxtColor . ';}'
                    . 'body #insta-footer .top-links .caret {border-bottom-color:' . $footerTxtColor . ';border-top-color:' . $footerTxtColor . ';}';
        } else {
            $theStyleForTheFooter = $footerstyles_CSS[$footerStyleName];
        }
        /* add the CSS to the Custom CSS var so it gets outputted */
        $CustomCSSstyle = $CustomCSSstyle . $theStyleForTheFooter;
    }

    /* we check if the var is set or empty or if it is the default option,
      TODO: a function that checks this */
    if (isset($FPstyle) && !empty($FPstyle) && $FPstyle != 'default') {
        /* we get the color for the Featured Properties background */
        $FPBackgroundColor = $OptionsSelected['FPBackgroundColor'];
        /* we get the color for the Featured Properties text */
        $FPTextColor = $OptionsSelected['FPTextColor'];

        /* we check if the var is set or empty or if it is the default option */
		if (isset($footerStyleName) && !empty($footerStyleName) && $footerStyleName != 'default') {
			/* lets create a var for the CSS */
			$theStyleForTheFooter='';
			
			/* check if they selected custom */
			if($footerStyleName == 'customFooterStyle'){			
				
				/* we get the hex value for the background color selected */
				$footerBgColor = $OptionsSelected['footerBackgroundColor'];
				/* we get the hex value for the text color selected */
				$footerTxtColor = $OptionsSelected['footerTextColor'];
				/* we get the color for the Menu Hover background */
				$footerLinkTextColor = empty($OptionsSelected['footerLinkTextColor']) ? $footerTxtColor : $OptionsSelected['footerLinkTextColor'];
				/* we get the color for the Menu Hover text */
				$footerLinkHoverTextColor = empty($OptionsSelected['footerLinkHoverTextColor']) ? $footerTxtColor : $OptionsSelected['footerLinkHoverTextColor'];

				$theStyleForTheFooter='body #insta-footer{background:'.$footerBgColor.';color:'.$footerTxtColor.';}'
	                                . 'body #insta-footer a,'
	                                . 'body #insta-footer .widget_bapi_footer .footer-links a{color:'.$footerLinkTextColor.';}'
	                                . 'body #insta-footer a:hover,'
	                                . 'body #insta-footer .widget_bapi_footer .footer-links a:hover{color:'.$footerLinkHoverTextColor.';}'
	                                . 'body #insta-footer .halflings.white i:before{color:'.$footerTxtColor.';}'
	                                . 'body #insta-footer .top-links .caret {border-bottom-color:'.$footerTxtColor.';border-top-color:'.$footerTxtColor.';}';

			} else {
				$theStyleForTheFooter=$footerstyles_CSS[$footerStyleName];
			}
		}
		
		/* add the CSS to the Custom CSS var so it gets outputted*/
		$CustomCSSstyle = $CustomCSSstyle . $theStyleForTheFooter;
		
	}

	/* we check if the var is set or empty or if it is the default option,
	TODO: a function that checks this */
	if (isset($FPstyle) && !empty($FPstyle) && $FPstyle != 'default') {
		/* we get the color for the Featured Properties background */
		$FPBackgroundColor = $OptionsSelected['FPBackgroundColor'];	
		/* we get the color for the Featured Properties text */
		$FPTextColor = $OptionsSelected['FPTextColor'];
		
		/* we check if the var is set or empty or if it is the default option */
		if (isset($FPBackgroundColor) && !empty($FPBackgroundColor) && $FPBackgroundColor != 'default') {
			/* check if this theme is theme05 which has a different FP */
				if($currentThemeName == 'instatheme05') {
					$CustomCSSstyle = $CustomCSSstyle . 'body .widget_bapi_featured_properties .fp-featured{border-color:'.$FPBackgroundColor.';}body .widget_bapi_featured_properties .fp-image,body .widget_bapi_featured_properties .fp-outer{background:'.$FPBackgroundColor.';}';
				} else {
					$CustomCSSstyle = $CustomCSSstyle . 'body .insta-bottom-left-home .widget_bapi_featured_properties .fp-outer,body .widget_bapi_featured_properties .fp-featured .fp-outer{background:'.$FPBackgroundColor.';}body.instatheme04 .insta-bottom-left-home .widget_bapi_featured_properties .property-link{color:'.$FPBackgroundColor.';} ';
				}
		}
		/* we check if the var is set or empty or if it is the default option */
		if (isset($FPTextColor) && !empty($FPTextColor) && $FPTextColor != 'default') {
			$CustomCSSstyle = $CustomCSSstyle . 'body .insta-bottom-left-home .widget_bapi_featured_properties .fp-title a,body .insta-bottom-left-home .widget_bapi_featured_properties .fp-details,body .insta-bottom-left-home .widget_bapi_featured_properties .fp-rates,body .insta-bottom-left-home .widget_bapi_featured_properties .property-link,body .insta-bottom-left-home .widget_bapi_featured_properties .property-link span,body .widget_bapi_featured_properties .fp-title a,body .widget_bapi_featured_properties .fp-details,body .widget_bapi_featured_properties .fp-rates,body .widget_bapi_featured_properties .property-link,body .widget_bapi_featured_properties .property-link span,body.instatheme02 .widget_bapi_featured_properties .fp-details, body.instatheme02 .widget_bapi_featured_properties .fp-rates{color:'.$FPTextColor.';}body .widget_bapi_featured_properties hr{ border-bottom-color:'.$FPTextColor.';border-top-color:'.$FPTextColor.';}body .widget_bapi_featured_properties .property-link{background-color:transparent;}';
				
		}
	
	}
        
        /* we check if the var is set or empty or if it is the default option,
	TODO: a function that checks this */
	if (isset($h1styles) && !empty($h1styles) && $h1styles != 'default') {
		/* we get the color for the h1 and titles color */
		$h1TextColor = $OptionsSelected['h1TextColor'];
		
        /* we check if the var is set or empty or if it is the default option */
        if (isset($h1TextColor) && !empty($h1TextColor) && $h1TextColor != 'default') {
        	$CustomCSSstyle = $CustomCSSstyle . 'body h1,body h3.widget-title,body .widget_bapi_hp_search .widget-title{color:'.$h1TextColor.';}';
        }
	}
        /* we check if the var is set or empty or if it is the default option,
	TODO: a function that checks this */
	if (isset($h2styles) && !empty($h2styles) && $h2styles != 'default') {
		/* we get the color for the h1 and titles color */
		$h2TextColor = $OptionsSelected['h2TextColor'];
	
        /* we check if the var is set or empty or if it is the default option */
        if (isset($h2TextColor) && !empty($h2TextColor) && $h2TextColor != 'default') {
        	$CustomCSSstyle = $CustomCSSstyle . '.marker-infowindow .prop-title a,body h2.title,body .list-view-page .property-info h2,body .development-results .portal-info h2,body .marketarea-listview-page .portal-info h2,body .list-view-page .property-info h2 a,body .development-results .portal-info h2 a,body .marketarea-listview-page .portal-info h2 a,body .gallery-view-page .property-title a,body .map-view-page .marker-infowindow .prop-title a b,body .item-result .item-info .title{color:'.$h2TextColor.';}';
        }
	}
        
    /* we check if the var is set or empty or if it is the default option,
	TODO: a function that checks this */
	if (isset($paragraphs_styles) && !empty($paragraphs_styles) && $paragraphs_styles != 'default') {
		/* we get the color for the h1 and titles color */
		$paragraphs_TextColor = $OptionsSelected['paragraphs_TextColor'];
	
        /* we check if the var is set or empty or if it is the default option */
        if (isset($paragraphs_TextColor) && !empty($paragraphs_TextColor) && $paragraphs_TextColor != 'default') {
        	$CustomCSSstyle = $CustomCSSstyle . 'body p{color:'.$paragraphs_TextColor.';}';
        }
	}
        
	
	if (isset($logoSize) && !empty($logoSize) && $logoSize != 'default') {
		if( $customlogoSize <= 34 ) {
			$mtop = 62;
		} else {
			$mtop = $customlogoSize + 26; 	 
		}
		
		/* An array of CSS, each theme needs 2 different CSS, 1 for small and 1 for Large*/
		$logoSizeByTheme = array(
			'instatheme01' => array(
				'small' => 'body .bapi-logo img{height:30px}',
				'medium' => 'body .bapi-logo img{height:50px}',
				'large' => 'body .bapi-logo img{height:70px}',
				'custom' => 'body .bapi-logo img{height:'.$customlogoSize.'px}',
				'original' => ''
				),
			'instatheme02' => array(
				'small' => 'body .bapi-logo img{height:30px}',
				'medium' => 'body .bapi-logo img{height:50px}',
				'large' => 'body .bapi-logo img{height:70px}',
				'custom' => 'body .bapi-logo img{height:'.$customlogoSize.'px}',
				'original' => ''
				),
			'instatheme04' => array(
				'small' => 'body #insta-top-fixed .bapi-logo img{height:16px;}body .pushdown,body .pushdown.wpadminbarvisible{margin-top:62px;}@media (max-width: 979px) {body .pushdown,body .pushdown.wpadminbarvisible{margin-top:20px;}}',
				'medium' => 'body #insta-top-fixed .bapi-logo img{height:26px;}body .pushdown,body .pushdown.wpadminbarvisible{margin-top:60px;}@media (max-width: 979px) {body .pushdown,body .pushdown.wpadminbarvisible{margin-top:20px;}}',
				'large' => 'body #insta-top-fixed .bapi-logo img{height:36px;}body .pushdown,body .pushdown.wpadminbarvisible{margin-top:62px;}@media (max-width: 979px) {body .pushdown,body .pushdown.wpadminbarvisible{margin-top:20px;}}',
				'custom' => 'body #insta-top-fixed .bapi-logo img{height:'.$customlogoSize.'px;}body .pushdown,body .pushdown.wpadminbarvisible{margin-top:'.$mtop.'px;}@media (max-width: 979px) {body .pushdown,body .pushdown.wpadminbarvisible{margin-top:20px;}}',
				'original' => ''
				),
			'instatheme05' => array(
				'small' => 'body .bapi-logo img{height:30px}',
				'medium' => 'body .bapi-logo img{height:50px}',
				'large' => 'body .bapi-logo img{height:70px}',
				'custom' => 'body .bapi-logo img{height:'.$customlogoSize.'px}',
				'original' => ''
				),
			'instatheme07' => array(
				'small' => 'body #insta-top-fixed .bapi-logo img{height:16px;}body .pushdown,body .pushdown.wpadminbarvisible{margin-top:62px;}@media (max-width: 979px) {body .pushdown,body .pushdown.wpadminbarvisible{margin-top:20px;}}',
				'medium' => 'body #insta-top-fixed .bapi-logo img{height:26px;}body .pushdown,body .pushdown.wpadminbarvisible{margin-top:60px;}@media (max-width: 979px) {body .pushdown,body .pushdown.wpadminbarvisible{margin-top:20px;}}',
				'large' => 'body #insta-top-fixed .bapi-logo img{height:36px;}body .pushdown,body .pushdown.wpadminbarvisible{margin-top:62px;}@media (max-width: 979px) {body .pushdown,body .pushdown.wpadminbarvisible{margin-top:20px;}}',
				'custom' => 'body #insta-top-fixed .bapi-logo img{height:'.$customlogoSize.'px;}body .pushdown,body .pushdown.wpadminbarvisible{margin-top:74px;}@media (max-width: 979px) {body .pushdown,body .pushdown.wpadminbarvisible{margin-top:20px;}}',
				'original' => ''

				),
			'instatheme08' => array(
				'small' => 'body #insta-top-fixed .bapi-logo img{height:16px;}body .pushdown,body .pushdown.wpadminbarvisible{margin-top:62px;}@media (max-width: 979px) {body .pushdown,body .pushdown.wpadminbarvisible{margin-top:20px;}}',
				'medium' => 'body #insta-top-fixed .bapi-logo img{height:26px;}body .pushdown,body .pushdown.wpadminbarvisible{margin-top:60px;}@media (max-width: 979px) {body .pushdown,body .pushdown.wpadminbarvisible{margin-top:20px;}}',
				'large' => 'body #insta-top-fixed .bapi-logo img{height:36px;}body .pushdown,body .pushdown.wpadminbarvisible{margin-top:62px;}@media (max-width: 979px) {body .pushdown,body .pushdown.wpadminbarvisible{margin-top:20px;}}',
				'custom' => 'body #insta-top-fixed .bapi-logo img{height:'.$customlogoSize.'px;}',
				'original' => ''

				)	
		);
		
		/* lets check if the theme exist on the logosizearray if not we will use instatheme01 by default*/
		if($logoSizeByTheme[$currentThemeName][$logoSize] == '') {
			/* add the CSS to the Custom CSS var so it gets outputted*/
			$CustomCSSstyle = $CustomCSSstyle . $logoSizeByTheme['instatheme01'][$logoSize];
		} else {
			/* add the CSS to the Custom CSS var so it gets outputted*/
			$CustomCSSstyle = $CustomCSSstyle . $logoSizeByTheme[$currentThemeName][$logoSize];
		}
	}
	
	if (isset($customCSS) && !empty($customCSS) && $customCSS != '0') {
		/* add the CSS to the Custom CSS var so it gets outputted*/
		$CustomCSSstyle = $CustomCSSstyle . $OptionsSelected['customInlineCSS'];
	}
	
	/* if there is no custom CSS lets not output anything*/
	if($CustomCSSstyle!="") { 
		/* outputting the CSS in the head */
		$clean =  wp_strip_all_tags($CustomCSSstyle);
		wp_add_inline_style( 'default-styles', $clean );
	}

}
add_action('wp_enqueue_scripts', 'instaparent_preset_styles');


function load_selected_font() {

    /* we get the options from the database */
    $OptionsSelected = get_option('instaparent_theme_options');
    /* we get the preset selected which is in radioinput */
    $fontStyle = $OptionsSelected['fontStyle'];
    $paragraphStyle = $OptionsSelected['paragraphfontStyle'];

    if (!empty($fontStyle)) {
        // Font options
        $fonts = array($fontStyle, $paragraphStyle);
        include 'insta-common/themeoptions/extensions/fonts.php';
        $font_uri = customizer_library_get_google_font_uri($fonts);

        // Load Google Fonts
        wp_enqueue_style('demo_fonts', $font_uri, array(), null, 'screen');
    }
}

add_action('wp_enqueue_scripts', 'load_selected_font');

/* Load inline CSS this should be in the theme not here */

function applyFontStyle() {
    /* we get the options from the database */
    $OptionsSelected = get_option('instaparent_theme_options');
    /* we get the preset selected which is in radioinput */
    $fontStyle = $OptionsSelected['fontStyle'];
    $paragraphStyle = $OptionsSelected['paragraphfontStyle'];
    if (!empty($fontStyle)) {
        ?>
        <style type="text/css" media="screen">h1,h2,h3{font-family:<?php echo $fontStyle; ?>;}p{font-family:<?php echo $paragraphStyle; ?>;}</style>
        <?php
    }
}

add_action('wp_head', 'applyFontStyle', 100);

if (include('inc/custom-widgets.php')) {
    add_action("widgets_init", "load_custom_widgets");
}

function load_custom_widgets() {
    global $itsInstaTheme;
    //unregister_widget("WP_Nav_Menu_Widget");
    if ($itsInstaTheme) {
        register_widget('Kigo_Nav_Menu_Widget');
        register_widget('Kigo_Social_Icons_Widget');
    }
    register_widget('Insta_Latest_Blog_Posts');
}

/**
 * Customizer Library Demo functions and definitions
 *
 * @package Customizer Library Demo
 */
// Default styles
function default_styles() {
	wp_enqueue_style( 'default-styles', get_stylesheet_uri() );
}
//add_action( 'wp_enqueue_styles', 'default_styles' );


/* only for instathemes */
if ($itsInstaTheme && file_exists(get_template_directory() . '/inc/customizer/customizer-library/customizer-library.php')) :

// Helper library for the theme customizer.
    require get_template_directory() . '/inc/customizer/customizer-library/customizer-library.php';

// Define options for the theme customizer.
    require get_template_directory() . '/inc/customizer/customizer-options.php';

// Output inline styles based on theme customizer selections.
    require get_template_directory() . '/inc/customizer/styles.php';

// Additional filters and actions based on theme customizer selections.
    require get_template_directory() . '/inc/customizer/mods.php';

else :
    if ($itsInstaTheme) {
        add_action('customizer-library-notices', 'demo_customizer_library_notice_instaparent');
    }
endif;

function demo_customizer_library_notice_instaparent() {

    _e('<p>Notice: The "customizer-library" sub-module is not loaded.</p>', 'demo');
}

function instaparent_styles() {
    /* Font Awesome */
    wp_enqueue_style('fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
}
add_action('wp_enqueue_scripts', 'instaparent_styles');

/* We first load our widget */
add_action( 'widgets_init', 'register_my_widget' );

/* Register our widget in WordPress so that it is available under the widgets section. */
function register_my_widget() {  
    register_widget( 'Insta_Latest_Blog_Posts' );  
}

add_action('wp_enqueue_scripts', 'instaparent_styles');


//Include new widgets
add_action( 'after_setup_theme', function() { include dirname(__FILE__).'/widgets/widgets_include.php'; } );
