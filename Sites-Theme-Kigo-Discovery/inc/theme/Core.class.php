<?php

namespace Discovery;

class Core {

    private $themePath;

    public function __construct() {
        $this->themePath = get_template_directory();
    }

    /**
     * Theme initializer - methods sorted by priority
     */
    public function init() {
        $this->load();
        $this->configure();
        $this->customize();
        $this->assets();
        $this->admin();
    }

    /**
     * Configure theme behaviour
     */
    private function configure() {

        global $allowedtags;

        $allowedtags += [
            'i' => array(
                'class' => true,
                'id' => true,
            ),
            'img' => array(
                'alt' => true,
                'align' => true,
                'border' => true,
                'height' => true,
                'hspace' => true,
                'longdesc' => true,
                'vspace' => true,
                'src' => true,
                'usemap' => true,
                'width' => true,
            ),
            'div' => array(
                'class' => true,
                'id' => true,
            ),
            'br' => []
        ];

        /* Enable custom post thumbnails */
        add_theme_support('post-thumbnails', array('item', 'team', 'page', 'post','stacks'));

        /* Default theme fallback */
//        @define('KIGO_SELF_HOSTED', FALSE);
//        @define('WP_DEFAULT_THEME', 'Sites-Theme-Kigo-Discovery');

        /* Allow unfiltered uploads */
//        @define('ALLOW_UNFILTERED_UPLOADS', true);  --> Disabled by request

//        remove_action('init', 'urlHandler_bapidefaultpages', 1 );
//        remove_action('init', 'bapi_setup_default_pages', 5);
//
        add_filter( 'query_vars', [$this,'add_query_vars_filter'] );
        add_action('init',[$this,'kd_get_post_types'],99999);
        add_action('pre_get_posts',[$this,'set_search_query']);

        if( class_exists('Discovery\StackAreas\StackAreasLandings') )
        {
          //Stack areas landings:
          $StackAreasLandings = new StackAreas\StackAreasLandings(dirname(__FILE__) . '/../../page-templates');
        }

    }

    /**
     * Customize theme behaviour
     */
    private function customize(){

        /* Customize "Read more" link */
        add_filter('the_content_more_link', function($link) {
            return '<div><a class="primary-stroke-color" href="' . get_the_permalink(get_the_ID()) . '">Learn more ></a></div>';
        });

        /* Clean up unwanted front-end markup */
        $this->cleanUp();
    }

    /**
     * Removes unwanted markup from theme front-end
     */
    private function cleanUp(){
        add_action('after_setup_theme', function () {
            remove_action('wp_head', 'wp_generator');                       // #1
            remove_action('wp_head', 'wlwmanifest_link');                   // #2
            remove_action('wp_head', 'rsd_link');                           // #3
            remove_action('wp_head', 'wp_shortlink_wp_head');               // #4

            remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);// #5

            add_filter('the_generator', '__return_false');                  // #6
//            add_filter('show_admin_bar','__return_false');                  // #7

            remove_action( 'wp_head', 'print_emoji_detection_script', 7 );  // #8
            remove_action( 'wp_print_styles', 'print_emoji_styles' );       // #9
        });
    }

    /**
     * Takes care of any data-structure loading & registering.
     */
    private function load(){

        /* Load custom post types */
        include_once $this->themePath.'/inc/cpt/kd-items.php';
        include_once $this->themePath.'/inc/cpt/kd-team.php';



        /* Load sidebars */
        $this->sidebars();
        /* Load widgets */
        $this->widgets();
    }

    /**
     * Takes care of loading widgets
     */
    private function widgets() {
        /* Load widgets */

        include_once $this->themePath.'/inc/widgets/KD_Widget.php'; //TODO: Update missing widgets & discontinue 'KD_Widget'
        include_once $this->themePath.'/inc/widgets/KD_Widget2.php';

        $activeWidgets = [
            'hero',
            'search',
            'buckets',
            'page_block',
            'block',
            'items',
            'team',
            'social',
            'image',
            'logo',
            'featured',
            'menu',
            'map',
            'content',
            'specials',
            'selective-search',
        ];

        /* Include active widgets */
        array_walk($activeWidgets, function ($widget) {
            $prefix = 'kd';
            include_once $this->themePath."/inc/widgets/$prefix-$widget/$prefix-$widget.php";
        });

        include_once $this->themePath . '/inc/widgets/redeclaredWidgets.php';

        /* Remove unwanted widgets */

        $unwantedWidgets = [
            'BAPI_Header',
            'BAPI_Footer',
            'BAPI_HP_Slideshow',
            'BAPI_HP_LogoWithTagline',
            'BAPI_HP_Logo',
            'BAPI_HP_Search',
            'BAPI_Search',
            'BAPI_Property_Finders',
            'BAPI_Featured_Properties',
            'BAPI_Similar_Properties',
            'BAPI_Specials_Widget',
            'WP_Widget_Search',
        ];

        add_action('widgets_init', function() use ($unwantedWidgets) {
            array_map(function($widgetId){
                unregister_widget($widgetId);
            }, $unwantedWidgets);
        });

    }

    /**
     * Takes care of registering sidebars
     */
    private function sidebars() {
        /* Page Sidebars */
        add_action('widgets_init', function () {
            $group = [];

            $group['header'] = array(
                array(
                    'name' => 'Header Left',
                    'id' => 'header_left',
                ),
                array(
                    'name' => 'Header Right',
                    'id' => 'header_right',
                ),
            );

            $group['under_header'] = array(
                array(
                    'name' => 'Under Header Left',
                    'id' => 'under_header_left',
                ),
                array(
                    'name' => 'Under Header Right',
                    'id' => 'under_header_right',
                ),
            );

            $group['footer'] = array(
                array(
                    'name' => 'Footer left',
                    'id' => 'footer_left',
                ),
                array(
                    'name' => 'Footer right',
                    'id' => 'footer_right',
                ),
            );

            $group['pages'] = array(
                array(
                    'name' => 'Home',
                    'id' => 'page_home',
                ),
                array(
                    'name' => 'About Us',
                    'id' => 'page_about_us',
                ),
            );

            $group['pages_listing'] = array(
                array(
                    'name' => 'Blog Listing Top',
                    'id' => 'page_blog_listing',
                ),
                array(
                    'name' => 'Search page Listing Top',
                    'id' => 'page_search_listing',
                ),
            );

            $group['pages_listing2'] = array(
                array(
                    'name' => 'Archive Listing Top',
                    'id' => 'page_archive_listing',
                ),
            );


            $group['pages_listing_right'] = array(
                array(
                    'name' => 'Blog Listing Right',
                    'id' => 'page_blog_listing_right',
                ),
                array(
                    'name' => 'Search page Listing Right',
                    'id' => 'page_search_listing_right',
                ),
            );

            $group['pages_listing_right2'] = array(
                array(
                    'name' => 'Archive Listing Right',
                    'id' => 'page_archive_listing_right',
                ),
            );



            $group['sidebars'] = array(
                array(
                    'name' => 'Default page sidebar',
                    'id' => 'sidebar_page',
                ),
                array(
                    'name' => 'Detail page sidebar',
                    'id' => 'sidebar_detail',
                ),
            );

            for ($i = 0; $i < 2; $i++) {

                foreach ($group as $name => $sidebars) {

                    if(empty($sidebars[$i])) continue;

                    register_sidebar($sidebars[$i] + ['before_title' => '', 'after_title' => '', 'before_widget' => '', 'after_widget' => '']);
                }

            }
        }
        );

        /* Virtual Sidebars */
        include_once $this->themePath.'/inc/sidebars/virtual-sidebars.php';
    }

    /**
     * Administraion panel related functionality
     */
    private function admin(){
        //Register admin pages
        $this->adminPages();
        //Initialize customizer options
        $this->customizer();
    }

    private function adminPages() {
        /* Options */
        include $this->themePath.'/inc/options/options.php';

        /* Custom Sidebars */
        include_once $this->themePath.'/inc/sidebars/register.php';
    }

    private function customizer() {
        /**
         * Remove default Sections from Customizer
         */
        define('CONCATENATE_SCRIPTS', false);

        add_action('customize_register', function ($wp_customize) {
            $wp_customize->remove_section('title_tagline');
            $wp_customize->remove_section('static_front_page');
            $wp_customize->remove_section('nav');
            $wp_customize->remove_section('background_color');
        });


        add_filter('site_icon_image_sizes', function ($sizes) {
            $sizes = array(16, 32, 64, 192, 180);

            return $sizes;
        });

        add_filter('site_icon_meta_tags', function ($meta_tags) {
            $meta_tags[] = sprintf('<link rel="icon" href="%s" sizes="64x64" />', esc_url(get_theme_mod("site-favicon")));

            return $meta_tags;
        });

        /* Customizer */
        if (file_exists(get_template_directory() . '/inc/customizer/customizer-library/customizer-library.php')) {
            // Helper library for the theme customizer.
            require get_template_directory() . '/inc/customizer/customizer-library/customizer-library.php';
            // Define options for the theme customizer.
            require get_template_directory() . '/inc/customizer/customizer-options.php';
            // Output inline styles based on theme customizer selections.
            require get_template_directory() . '/inc/customizer/styles.php';
            // Additional filters and actions based on theme customizer selections.
            require get_template_directory() . '/inc/customizer/mods.php';
        } else {
            add_action('customizer-library-notices', function () {
                _e('<p>Notice: The "customizer-library" sub-module is not loaded.</p><p>Please add it to the "inc/customizer" directory: <a href="https://github.com/devinsays/customizer-library">https://github.com/devinsays/customizer-library</a>.</p><p>The demo, including submodules, can also be installed via Git: "git clone --recursive git@github.com:devinsays/customizer-library-demo".</p>', 'demo');
            });
        }

    }

    /**
     * Takes care of loading any assets
     */
    private function assets(){
        /* Enqueue default assets */
        $this->defaultAssets();
        /* Add custom scripts & styles */
        $this->customAssets();
    }

    private function defaultAssets() {
        //TODO: Refactor enqueuing - could be an array iteration

        /* Enqueue site assets */
        add_action('wp_enqueue_scripts', function () {

            wp_deregister_script('jquery');

            $commonPath = get_template_directory_uri() . '/kd-common';

            /* Google Fonts - https://www.google.com/fonts */
            wp_enqueue_style('gf-montserrat', 'https://fonts.googleapis.com/css?family=Montserrat:400,700');
            wp_enqueue_style('gf-opensans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,700');

            /* Font Awesome */
            wp_enqueue_style('fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');

            /* Normalize - https://necolas.github.io/normalize.css */
            wp_enqueue_style('kd-normalize', $commonPath . '/lib/normalize/normalize.css');

            /* Lodash - https://lodash.com/ */
            wp_enqueue_script('lodash', 'https://cdn.jsdelivr.net/lodash/4.5.1/lodash.min.js' , array(), '', false);

            /* Bootstrap - https://github.com/dbushell/Pikaday */
            wp_enqueue_script('bootstrap', $commonPath . '/lib/bootstrap/js/bootstrap.min.js', array(), '', false);
            wp_enqueue_style('bootstrap', $commonPath . '/lib/bootstrap/css/bootstrap.min.css');

            /* Bootstrap DropdownCheckbox - https://github.com/Nelrohd/bootstrap-dropdown-checkbox */
            wp_enqueue_script('bootstrap-dropdown', $commonPath . '/lib/bootstrap-dropdown-checkbox/js/bootstrap-dropdown-checkbox.min.js', array(), '', false);
            wp_enqueue_style('bootstrap-dropdown', $commonPath . '/lib/bootstrap-dropdown-checkbox/css/bootstrap-dropdown-checkbox.css');

            /* Pickadate.js styles */
            wp_enqueue_style('pickadate', $commonPath . '/lib/pickadate/default.css');
            wp_enqueue_style('pickadate-date', $commonPath . '/lib/pickadate/default.date.css');

            /* Swiper - http://www.idangero.us/swiper */
            wp_enqueue_script('swiper', $commonPath . '/lib/swiper/js/swiper.min.js', array(), '', false);
            wp_enqueue_style('swiper', $commonPath . '/lib/swiper/css/swiper.min.css');

            /* Flexslider - http://flexslider.woothemes.com/ */
            wp_enqueue_script('flexslider', $commonPath . '/lib/flexslider/jquery.flexslider-min.js', array(), '', false);
            wp_enqueue_style('flexslider', $commonPath . '/lib/flexslider/flexslider.css');

            /* Simple Lightbox - https://github.com/andreknieriem/simplelightbox */
            wp_enqueue_script('simple-lightbox', $commonPath . '/lib/simplelightbox/js/simple-lightbox.min.js', array(), '', false);
            wp_enqueue_style('simple-lightbox', $commonPath . '/lib/simplelightbox/css/simplelightbox.min.css');

            //TODO: Load only for search pages

            /* Google Maps */
            wp_enqueue_script('gmaps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDKR5k7Mbz9uUkO-TE2JuYeAwZfnMxfMaQ', array(), '', false);
            /* Google Maps Marker Clusterer - */
            wp_enqueue_script('gmaps-clusterer',  $commonPath . '/lib/js-marker-clusterer/src/markerclusterer_compiled.js', array(), '', false);
            /* Google Maps Spiderfy - */
            wp_enqueue_script('gmaps-spidify',  $commonPath . '/lib/oms/oms.min.js', array(), '', false);

            /* simple weather https://github.com/monkeecreate/jquery.simpleWeather/ */
            wp_enqueue_script('simple-weather', $commonPath . '/lib/simpleweather/jquery.simpleWeather.min.js', array(), '', false);

            /* weather icons http://erikflowers.github.io/weather-icons/ */
            wp_enqueue_style('weather-icons', $commonPath . '/lib/weather-icons/css/weather-icons.min.css');

            /* Custom */
            wp_enqueue_style('kd-style', $commonPath . '/css/main.css');
            wp_enqueue_script('kd-scripts', $commonPath . '/js/main.js', array(), '1.0.0', true);
        });

        /* Enqueue admin assets */

        add_action('admin_enqueue_scripts', function () {
            $commonPath = get_template_directory_uri() . '/kd-common';

            /* Font Awesome */
            wp_enqueue_style('fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');

            /* noUISlider - http://refreshless.com/nouislider/ */
            wp_enqueue_style('nouislider', $commonPath . '/lib/nouislider/nouislider.min.css');
            wp_enqueue_script('nouislider', $commonPath . '/lib/nouislider/nouislider.min.js', array(), '', true);

            /* Ace Editor */
            wp_enqueue_script('ace', $commonPath . '/lib/ace/src-min-noconflict/ace.js', array(), '', true);

            /* Font Awesome Icon Picker */
            wp_enqueue_script('kd-bootstrap', $commonPath . '/lib/bootstrap/js/bootstrap.min.js', array(), '', true);
            wp_enqueue_style('faip', $commonPath . '/lib/fa-iconpicker/css/fontawesome-iconpicker.min.css');
            wp_enqueue_script('faip', $commonPath . '/lib/fa-iconpicker/js/fontawesome-iconpicker.min.js', array(), '', true);

            /* Sortable */
            wp_enqueue_script('sortable', $commonPath . '/lib/sortable/Sortable.min.js', array('jquery'), '1.0.0', true);

            /* WP ColorPicker */
            wp_enqueue_style('wp-color-picker');

            /* Lou multi select - http://loudev.com/ */
            wp_enqueue_style('kd-loumultiselect', $commonPath . '/lib/lou-multi-select/css/multi-select.css');
            wp_enqueue_script('kd-quicksearck', $commonPath . '/lib/quicksearch/jquery.quicksearch.js', array(), '', false);
            wp_enqueue_script('kd-loumultiselect', $commonPath . '/lib/lou-multi-select/js/jquery.multi-select.js', array(), '', false);

            wp_enqueue_script('cked', $commonPath . '/lib/ckeditor/ckeditor.js', '', '1.0.0', false);
            wp_enqueue_script('cked-jq', $commonPath . '/lib/ckeditor/adapters/jquery.js', '', '1.0.0', false);

            /* Custom */
            wp_enqueue_style('admin-style', $commonPath . '/css/admin.css');
            wp_enqueue_script('admin-script', $commonPath . '/js/admin.js', array('wp-color-picker', 'cked-jq'), '1.0.0', false);
        });

    }

    private function customAssets() {
        add_filter('language_attributes', 'doctype_opengraph');
        add_action('wp_head', 'fb_opengraph', 5);

        add_action('wp_head', function () {
            $stylesEnabled = get_theme_mod('kd-use-css', false);
            $scriptsEnabled = get_theme_mod('kd-use-h-js', false);

            if ($stylesEnabled || $scriptsEnabled) {
                $styles = get_theme_mod('kd-custom-css-min', false);
                $scripts = get_theme_mod('kd-custom-h-js-min', false);

                $output = '';
                $output .= $stylesEnabled && $styles ? '<style>' . $styles . '</style>' : '';
                $output .= $scriptsEnabled && $scripts ? '<script>' . $scripts . '</script>' : '';
                echo $output;
            }
        });

        add_action('wp_footer', function () {
            $scriptsEnabled = get_theme_mod('kd-use-f-js', false);

            if ($scriptsEnabled) {
                $scripts = get_theme_mod('kd-custom-f-js-min', false);
                $output = '';
                $output .= $scripts ? '<script>' . $scripts . '</script>' : '';
                echo $output;
            }
        });
    }


    /**
     * Adds the "types" parameter to the search query
     * @param array $vars The variables in the search query.
     */
    public function add_query_vars_filter( $vars ){
        $vars[] = "types";
        return $vars;
    }

    /**
     * Updates the post types theme mod
     */
    function kd_get_post_types()
    {
        $args = array(
            '_builtin' => false,
            'public'   => true,
        );

        $output = 'names'; // names or objects, note names is the default
        $operator = 'or'; // 'and' or 'or'


        $kd_post_types = get_post_types( $args, $output, $operator );

        set_theme_mod('kd_post_types',$kd_post_types);
    }







    /**/
    function set_search_query($wpq)
    {

        if($wpq->is_search() && $wpq->is_main_query())
        {

            // Getting the search query
            $search_query = kd_get_search_query();

            // Array to store the post types to filter
            $post_types_to_filter = [];

            $search_query_types = empty(urldecode($_GET['types'])) ? '' : urldecode($_GET['types']);

            // The search query
            $s = empty(urldecode($_GET['s'])) ? '' : urldecode($_GET['s']);


            // The post types that come in the search query
            if(!empty($search_query_types))
            {
                // The post types to filter
                $post_types_to_filter = explode(',',urldecode($search_query_types));
            }



            //Query 1 will get the s results
            $args1 =
                [
                    'post_type'         => $post_types_to_filter ?: null,
                    's' 			    => $s,
                    'posts_per_page'    => -1,
                ];

            $q1 = get_posts($args1); // array



            $q2 = [];

            //Query 2 will get the meta fields results
            $meta_query = $this->get_meta_query_array($s);

            if((in_array('page',$post_types_to_filter) || empty($post_types_to_filter)) && !empty($s))
            {

                $args2 =
                    [
                        'post_type'         => get_post_types(),
                        'posts_per_page'    => -1,
                        'meta_query'        => $meta_query,
                    ];

                $q2 = get_posts($args2);// array

            }

            $arraymerged = array_merge($q1, $q2);
            $ids = [];

            foreach($arraymerged as $merged)
            {
                $ids[] = $merged->ID;
            }



            $unique = array_unique($ids);

            $page_number = (get_query_var('paged')) ? get_query_var('paged') : 1;

            $args =
                [
                    'post_type'                 => !empty($post_types_to_filter) ?$post_types_to_filter: get_post_types(),
                    'post__in'                  => empty($unique) ? [uniqid(time())] : $unique,
                    'paged'                     => $page_number,
                ];

            $wpq->query_vars = $args;


        }
    }

    /**
     * returns an array of the meta fields to include in the search
     * @return array the meta fields array
     */
    function get_meta_query_array($s)
    {
        $mini_queries = $this->get_mini_queries($s);

        $meta_query = [
            'relation' => 'AND',
        ];
        $meta_query = array_merge($meta_query, $mini_queries);

        return $meta_query;
    }

    function get_mini_queries($s)
    {
        $string_parts = explode(' ',$s);
        $mini_queries = [];

        global $wpdb;
        //meta table
        $meta_table = $wpdb->prefix . 'postmeta';
        $custom_query_string = "SELECT meta_key FROM $meta_table ";
        $all_metas_results =  $wpdb->get_results( $custom_query_string, ARRAY_A );
        $all_metas = [];
        foreach($all_metas_results as $meta)
        {
            if(!in_array($meta['meta_key'],$all_metas))
                $all_metas[] = $meta['meta_key'];
        }



        foreach ($string_parts as $key => $value) {

            $all_metas_arrays = [];

            foreach($all_metas as $meta)
            {
                $all_metas_arrays[] =
                    [
                        'key'       => $meta,
                        'value'     => "$value",
                        'compare'   => 'LIKE'
                    ];
            }

            $mini_query =
                [
                    'relation'  => 'OR',
                ];

            $mini_queries = array_merge($mini_query, $all_metas_arrays);
        }

        return $mini_queries;
    }

}
