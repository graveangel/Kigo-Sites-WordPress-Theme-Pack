<?php

include 'inc/themeActions.php';
include 'inc/themeActivation.php';

//TODO: comment everything accordingly.

$site = new KD();

class KD {

    public function __construct() {
        /* Default theme fallback */

        @define('KIGO_SELF_HOSTED', FALSE);
        @define('WP_DEFAULT_THEME', 'Sites-Theme-Kigo-Discovery');
        @define('ALLOW_UNFILTERED_UPLOADS', true);

//        remove_action('init', 'urlHandler_bapidefaultpages', 1 );
        remove_action('init', 'bapi_setup_default_pages', 5);

        /* Init the theme */
        $this->init();
    }

    private function init() {
        $this->rewrites();
        $this->themeSettings();
        $this->loadCPTs();
        $this->loadWidgets();
        $this->loadSidebars();
        $this->themePages();
        $this->themeActivation();
        $this->enqueueAssets();
        $this->initCustomizer();
        $this->themeActions();
        $this->themeHeader();
    }

    private function themeSettings() {
        /* Enable cpt-thumbnails */
        add_theme_support('post-thumbnails', array('item', 'team', 'page'));


        /* Customize "Read more" link */

        function custom_rm_link($link) {
            return '<div><a class="primary-stroke-color" href="' . get_the_permalink(get_the_ID()) . '">Learn more ></a></div>';
        }

        add_filter('the_content_more_link', 'custom_rm_link');
    }

    public function rewrites(){

    }

    public function loadCPTs() {
        /* Load Custom Post Types */

        include_once 'cpt/kd-items.php';
        include_once 'cpt/kd-team.php';
    }

    private function loadWidgets() {
        /* Load widgets */

        include_once 'widgets/KD_Widget.php';
        include_once 'widgets/KD_Widget2.php';

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
        ];

        array_walk($activeWidgets, function ($widget) {
            $prefix = 'kd';
            include_once "widgets/$prefix-$widget/$prefix-$widget.php";
        });

        /* Redeclared widgets */
        include 'inc/redeclaredWidgets.php';
    }

    private function loadSidebars() {
        /* Register sidebars */

        add_action('widgets_init', 'kd_fixed_sidebars');

        function kd_fixed_sidebars() {
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
                    register_sidebar($sidebars[$i] + ['before_title' => '', 'after_title' => '', 'before_widget' => '', 'after_widget' => '']);
                }
            }
        }

        /* Virtual Sidebars */
        include_once 'inc/sidebars/virtual-sidebars.php';
    }

    private function themePages() {
        /* KD Options */
        include 'inc/options/kd-options.php';

        /* Load sidebar admin page */
        include_once 'inc/sidebars/register.php';
    }

    private function themeActivation() {
        $activation = new ThemeActivation();
        add_action('after_switch_theme', array($activation, 'init'));
        add_action('after_switch_theme', array($this, 'loadCPTs'));
    }

    private function themeActions() {
        $themeActions = new ThemeActions();
        $themeActions->init();
    }

    private function enqueueAssets() {

        /* Enqueue site assets */
        add_action('wp_enqueue_scripts', 'kd_enqueue_assets');

        function kd_enqueue_assets() {

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
            wp_enqueue_script('kd-bootstrap-js', $commonPath . '/lib/bootstrap/js/bootstrap.min.js', array(), '', true);
            wp_enqueue_style('kd-bootstrap-css', $commonPath . '/lib/bootstrap/css/bootstrap.min.css');

            /* Wweather Icons */
//            wp_enqueue_style('weather-icons-css', $commonPath.'/lib/weather-icons/weather-icons.min.css');
//            wp_enqueue_style('weather-icons-wind-css', $commonPath.'/lib/weather-icons/weather-icons-wind.min.css');

            /* Pickadate.js styles */
            wp_enqueue_style('pickadate-css', $commonPath . '/lib/pickadate/default.css');
            wp_enqueue_style('pickadate-date-css', $commonPath . '/lib/pickadate/default.date.css');

            /* Swiper - http://www.idangero.us/swiper */
            wp_enqueue_script('swiper-js', $commonPath . '/lib/swiper/js/swiper.min.js', array(), '', false);
            wp_enqueue_style('swiper-css', $commonPath . '/lib/swiper/css/swiper.min.css');

            /* Flexslider - http://flexslider.woothemes.com/ */
            wp_enqueue_script('flexslider-js', $commonPath . '/lib/flexslider/jquery.flexslider-min.js', array(), '', false);
            wp_enqueue_style('flexslider-css', $commonPath . '/lib/flexslider/flexslider.css');

            /* Simple Lightbox - https://github.com/andreknieriem/simplelightbox */
            wp_enqueue_script('simple-lightbox-js', $commonPath . '/lib/simplelightbox/js/simple-lightbox.min.js', array(), '', false);
            wp_enqueue_style('simple-lightbox-css', $commonPath . '/lib/simplelightbox/css/simplelightbox.min.css');

            //TODO: Load only for search pages

            /* Google Maps */
            wp_enqueue_script('gmaps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDKR5k7Mbz9uUkO-TE2JuYeAwZfnMxfMaQ', array(), '', false);
            /* Google Maps Marker Manager */
            wp_enqueue_script('gmaps-clusterer',  $commonPath . '/lib/js-marker-clusterer/src/markerclusterer_compiled.js', array(), '', false);
            /* Google Maps Spidify */
            wp_enqueue_script('gmaps-spidify',  $commonPath . '/lib/oms/oms.min.js', array(), '', false);

            /* simple weather https://github.com/monkeecreate/jquery.simpleWeather/ */
            wp_enqueue_script('simple-weather-js', $commonPath . '/lib/simpleweather/jquery.simpleWeather.min.js', array(), '', false);

            /* weather icons http://erikflowers.github.io/weather-icons/ */
            wp_enqueue_style('weather-icons-css', $commonPath . '/lib/weather-icons/css/weather-icons.min.css');

            /* Custom */
            wp_enqueue_style('kd-style', $commonPath . '/css/main.css');
            wp_enqueue_script('kd-scripts', $commonPath . '/js/main.js', array(), '1.0.0', true);
        }

        /* Enqueue admin assets */

        function kd_enqueue_admin_assets($page) {
            $commonPath = get_template_directory_uri() . '/kd-common';

            /* Font Awesome */
            wp_enqueue_style('fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');

            /* noUISlider - http://refreshless.com/nouislider/ */
            wp_enqueue_style('nouislider-css', $commonPath . '/lib/nouislider/nouislider.min.css');
            wp_enqueue_script('nouislider-js', $commonPath . '/lib/nouislider/nouislider.min.js', array(), '', true);

            /* Ace Editor */
            wp_enqueue_script('ace-js', $commonPath . '/lib/ace/src-min-noconflict/ace.js', array(), '', true);

            /* Font Awesome Icon Picker */
            wp_enqueue_script('kd-bootstrap-js', $commonPath . '/lib/bootstrap/js/bootstrap.min.js', array(), '', true);
            wp_enqueue_style('faip-css', $commonPath . '/lib/fa-iconpicker/css/fontawesome-iconpicker.min.css');
            wp_enqueue_script('faip-js', $commonPath . '/lib/fa-iconpicker/js/fontawesome-iconpicker.min.js', array(), '', true);

            /* Sortable */
            wp_enqueue_script('sortable', $commonPath . '/lib/sortable/Sortable.min.js', array('jquery'), '1.0.0', true);

            /* WP ColorPicker */
            wp_enqueue_style('wp-color-picker');

            /* Lou multi select - http://loudev.com/ */
            wp_enqueue_style('kd-loumultiselect-css', $commonPath . '/lib/lou-multi-select/css/multi-select.css');
            wp_enqueue_script('kd-quicksearck-js', $commonPath . '/lib/quicksearch/jquery.quicksearch.js', array(), '', false);
            wp_enqueue_script('kd-loumultiselect-js', $commonPath . '/lib/lou-multi-select/js/jquery.multi-select.js', array(), '', false);


            wp_enqueue_script('cked', $commonPath . '/lib/ckeditor/ckeditor.js', '', '1.0.0', false);
            wp_enqueue_script('cked-jq', $commonPath . '/lib/ckeditor/adapters/jquery.js', '', '1.0.0', false);

            /* Custom */
            wp_enqueue_style('admin-style', $commonPath . '/css/admin.css');
            wp_enqueue_script('admin-script', $commonPath . '/js/admin.js', array('wp-color-picker', 'cked-jq'), '1.0.0', false);
        }

        add_action('admin_enqueue_scripts', 'kd_enqueue_admin_assets');
    }

    private function initCustomizer() {
        /**
         * Remove default Sections from Customizer
         */
        define('CONCATENATE_SCRIPTS', false);

        function customize_register_init($wp_customize) {
            $wp_customize->remove_section('title_tagline');
            $wp_customize->remove_section('static_front_page');
            $wp_customize->remove_section('nav');
            $wp_customize->remove_section('background_color');
        }

        add_action('customize_register', 'customize_register_init');

        function prefix_custom_site_icon_size($sizes) {
            $sizes = array(16, 32, 64, 192, 180);

            return $sizes;
        }

        add_filter('site_icon_image_sizes', 'prefix_custom_site_icon_size');

        function prefix_custom_site_icon_tag($meta_tags) {
            $meta_tags[] = sprintf('<link rel="icon" href="%s" sizes="64x64" />', esc_url(get_theme_mod("site-favicon")));

            return $meta_tags;
        }

        add_filter('site_icon_meta_tags', 'prefix_custom_site_icon_tag');

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
            add_action('customizer-library-notices', 'demo_customizer_library_notice');
        }

        function demo_customizer_library_notice() {
            _e('<p>Notice: The "customizer-library" sub-module is not loaded.</p><p>Please add it to the "inc/customizer" directory: <a href="https://github.com/devinsays/customizer-library">https://github.com/devinsays/customizer-library</a>.</p><p>The demo, including submodules, can also be installed via Git: "git clone --recursive git@github.com:devinsays/customizer-library-demo".</p>', 'demo');
        }

    }

    private function themeHeader() {
        add_action('wp_head', 'custom_header');
        add_action('wp_footer', 'custom_footer');

        add_filter('language_attributes', 'doctype_opengraph');
        add_action('wp_head', 'fb_opengraph', 5);

        function custom_header() {
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
        }

        function custom_footer() {
            $scriptsEnabled = get_theme_mod('kd-use-f-js', false);

            if ($scriptsEnabled) {
                $scripts = get_theme_mod('kd-custom-f-js-min', false);
                $output = '';
                $output .= $scripts ? '<script>' . $scripts . '</script>' : '';
                echo $output;
            }
        }

    }

}

/* Utils */

function debug($e, $die = true) {
    printf('<pre>%s</pre>', var_export($e, true));
    if ($die)
        die();
}

/**
 * Returns the URL for the original featured image file of a post.
 *
 * @param bool $id post ID
 * @return false|string Post featured image if it has any, else FALSE.
 */
function furl($id = false) {
    $postId = $id ? : get_the_ID();
    return wp_get_attachment_url(get_post_thumbnail_id($postId));
}

function bapiPageUrl($bapiPageId) {
    $resultArr = get_posts(['post_type' => 'page', 'meta_key' => 'bapi_page_id', 'meta_value' => $bapiPageId]);
    $page = array_pop($resultArr);
    return get_the_permalink($page->ID);
}

/**
 *
 * @param tstring $string_mustache_template
 * @return string filled template.
 */
function render_this($string_mustache_template, $addedArray = [], $onlyAdded = false) {

    if($onlyAdded)
        $data = $addedArray;
    else
        $data = getbapisolutiondata() + $addedArray;



    $m = new Mustache_Engine();

    $string = $m->render($string_mustache_template, $data);

    return $string;
}

function get_mustache_template($templatefile) {
    $filepath = __DIR__  . '/bapi/partials/' . $templatefile;
    if(!file_exists($filepath)) return false;

    $template = file_get_contents($filepath);

    return $template;
}

function get_marked_as_featured() {


    $props_settings = json_decode(stripslashes(get_theme_mod('kd_properties_settings'))) ? : [];

    $featured_pkids = array();
    foreach ($props_settings as $pkid => $ps) {
        if ($ps->forced_featured) {
            $featured_pkids[] = $pkid;
        }
    }


    $sd = json_decode(get_option('bapi_solutiondata'));//safer this way.

    $apikey = $sd->apikey;
    $baseurl = $sd->BaseURL;


    $BAPI = new BAPI($apikey,$baseurl);

    $props = $BAPI->get('property',$featured_pkids);



    $featured = array('kdfeatured'=>$props['result']);


    $sd = json_decode(get_option('bapi_keywords_array'));
    $sd_properties = array();
    foreach($sd as $ent){
        if($ent->entity === "property"){
            $sd_properties[]=$ent;
        }
    }


    foreach($featured['kdfeatured'] as $ind => $ftrd ){


        foreach($sd_properties as $sd_prop){


            if($ftrd['ID'] == $sd_prop->pkid){
                $featured['kdfeatured'][$ind]['ContextData']['SEO'] = array();
                $featured['kdfeatured'][$ind]['ContextData']['SEO']['DetailURL'] = $sd_prop->DetailURL;
                break;
            }
        }
    }


    return $featured;
}

function doctype_opengraph($output) {
    return $output . '
    xmlns:og="http://opengraphprotocol.org/schema/"
    xmlns:fb="http://www.facebook.com/2008/fbml"';
}

function fb_opengraph() {
    global $post;
    $meta_words = get_post_custom($post->ID, '', true);
    $bapikey = $meta_words['bapikey'][0];
    //we check if this is a property page
    if(strpos($bapikey, 'property') !== false ) {
        $img_src = "";
        $title_string = $meta_words['bapi_meta_title'][0];
        //check if there is a featured image
        if(has_post_thumbnail($post->ID)) {
            $img_src = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'medium');
        } else {
            //check if there is a custom field containing the primary image URL
            if(!empty($meta_words['primary_image_url'][0]) && !empty($meta_words['headline'][0])){
                $img_src = "http:".$meta_words['primary_image_url'][0];
                $title_string = $meta_words['headline'][0];
            }else{

                $bapikey = str_replace("property:", "", $bapikey);
                //doing a call, this should be in the plugin IMHO
                $bapi = getBAPIObj();
                $pkid = array(intval($bapikey));
                if (!$bapi->isvalid()) { return; }
                //pulling from connect the data of this property
                $c = $bapi->get("property", $pkid, NULL);

                //getting the primary image URL 
                if(is_array($c)){
                    $img_src = "http:".$c['result'][0]['PrimaryImage']['OriginalURL'];
                    //setting the custom field so we dont do calls to connect anymore
                    add_post_meta($post->ID, 'primary_image_url', $c['result'][0]['PrimaryImage']['OriginalURL'],true);
                    $title_string = $c['result'][0]['Headline'];
                    add_post_meta($post->ID, 'headline', $c['result'][0]['Headline'],true);
                }

            }
        }

        if(empty($meta_words['bapi_meta_title'][0])){
            $meta_words['bapi_meta_title'][0] = $post->post_title;
        }
        ?>
        <meta property="og:title" content="<?php echo $title_string; ?>"/>
        <meta property="og:description" content="<?php echo $meta_words['bapi_meta_description'][0]; ?>"/>
        <meta property="og:type" content="website" />
        <meta property="og:url" content="<?php echo the_permalink(); ?>"/>
        <meta property="og:site_name" content="<?php echo get_bloginfo(); ?>"/>
        <meta property="og:image" content="<?php echo $img_src; ?>"/>
    <?php
    } else {
        return;
    }
}


