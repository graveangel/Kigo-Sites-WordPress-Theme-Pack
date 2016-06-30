<?php
/**
 * MarketAreasLandings class
 * This class creates the Market areas post types, its custom fields and the widget to list them in the site..
 */
namespace Discovery\MarketAreas;

require_once 'MetaBox.php';
require_once 'GenerateLandingMetaBox.class.php';
require_once 'PropsNMarketAreas.class.php';

use Discovery\MarketAreas\MetaBox;

if(!defined(DS))
    define('DS', DIRECTORY_SEPARATOR);

class MarketAreasLandings
{
    private $templates_dir;

    function __construct($templatesdir='')
    {

        // Set the location of the market areas templates
        $this->templates_dir = $templatesdir;

        //Get Templates Info:
        $this->get_templates_info();

        //Meta boxes
        $this->meta_boxes();

        //Create the custom post
        add_action( 'init', [$this,'create_ma_custom_post'] );

        // Load admin scripts
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);

    }



    function create_ma_custom_post()
    {
        //Market areas post type
        $post_types = array(
            'market_areas'          => array(
                'label'             => 'Market Areas',
                'labels'            => array(
                    'name'          => __('Market Areas', 'kd'),
                    'singular_name' => __('Market Area', 'kd'),
                ),
                'description'       => __('Group properties in a single landing page.','kd'),
                'public'            => true,
                'capability_type'   => 'page',
                'has_archive'       => true,
                'menu_icon'         => 'dashicons-groups',
                'show_ui'           => true,
                'show_in_menu'      => true,
                'show_in_nav_menus' => true,
                'hierarchical'      => true,
                'show_in_admin_bar' => true,
                'taxonomies'        => array('category'),
                'menu_position'     => 10,
                'supports' => array('title', 'excerpt','thumbnail', 'page-attributes'),
                'description'       => 'Market Areas custom posts',
            ),
        );

        foreach ($post_types as $name => $args) {
            register_post_type($name, $args);
        }
    }

    function meta_boxes()
    {
          $args_array = array(
                // Market area description
                array(
                    'id'            => 'market_area_description',
                    'title'         => '<h4>Description</h4>',
                    'screen'        => 'market_areas',
                    'context'       => 'advanced',
                    'priority'      => 'default',
                    'template'      => 'market_area_description.php',
                    'need_sanitize' => false,
                    'description'   => 'A description of the market area.',
                ),
                // Images
                array(
                    'id'            => 'market_area_photos',
                    'title'         => '<h4>Photos</h4>',
                    'screen'        => 'market_areas',
                    'context'       => 'advanced',
                    'priority'      => 'default',
                    'template'      => 'market_area_photos.php',
                    'need_sanitize' => false,
                    'description'   => 'A set of images. You can sort them by dragging them.',
                ),
                // Use landing : select template
                array(
                    'id'            => 'market_area_generate_landing_pages',
                    'title'         => '<h4>Landing</h4>',
                    'screen'        => 'market_areas',
                    'context'       => 'advanced',
                    'priority'      => 'default',
                    'template'      => 'market_area_landing.php',
                    'need_sanitize' => false,
                    'description'   => '',
                ),
                // Market areas tree
                array(
                    'id'            => 'market_area_props_n_areas',
                    'title'         => '<h4>Properties and subareas</h4>',
                    'screen'        => 'market_areas',
                    'context'       => 'advanced',
                    'priority'      => 'default',
                    'template'      => 'market_area_props_n_areas.php',
                    'need_sanitize' => false,
                    'description'   => '',
                    'rules'         =>
                    [
                            '/`/',// No backticks
                            '/\'/',// No simplequotes
                            '/[<>]+/', // No tags
                    ]
                ),
                // Generate landing :
                //      This needs to be the after the tree meta box in this array in order to take the latest saved value of the market areas tree
                array(
                    'id'            => 'market_area_generate_landing',
                    'title'         => '<h4>Generate Landings</h4>',
                    'screen'        => 'market_areas',
                    'context'       => 'side',
                    'priority'      => 'default',
                    'template'      => 'market_area_landing_generate.php',
                    'need_sanitize' => false,
                    'description'   => 'Check this field if you want to create landing pages out of the locations in the Market areas tree',
                ),
        );

          foreach ($args_array as $args) {

                if($args['id']!=='market_area_generate_landing')
                    $newMeta = new MetaBox($args,dirname(__FILE__) . DIRECTORY_SEPARATOR .'meta_templates');
                else
                    $newMeta = new GemerateLandingMetaBox($args,dirname(__FILE__) . DIRECTORY_SEPARATOR .'meta_templates'); //special class to
            }

    }

    /**
     * Gets the first block comment of the page
     * @param  string $filename the name of the file to parse (a valid path)
     * @return array           the comments.
     */
    function getFileDocBlock($filename)
    {
        $docComments = array_filter(
            token_get_all( file_get_contents( $filename ) ), function($entry) {
                return $entry[0] == T_DOC_COMMENT;
            }
        );
        $fileDocComment = array_shift( $docComments );
        return $fileDocComment[1];
    }


    /**
     * Saves an array of the market areas templates info as a theme mod as a json string
     * @return void this function does not return any value
     */
    function get_templates_info()
    {
        $templates_info = [];
        // 1. get all templates inside the templates dir if available

        try {
            // echo $this->templates_dir; die;
            $files_in_dir = scandir($this->templates_dir);

            foreach($files_in_dir as $filename)
            {
                $template_name = substr($filename, 0, -4);
                if(!preg_match('/market-areas/', $filename))
                    continue;

                $filedoc = $this->getFileDocBlock($this->templates_dir . DS . $filename);
                preg_match_all('/@[a-z0-9A-Z]+:.*/',$filedoc, $matches);

                if(count($matches))
                {
                    $templates_info[$template_name] = [];

                    foreach($matches[0] as $infoline)
                    {
                        $parts = explode(':',substr($infoline, 1));
                        $templates_info[$template_name][$parts[0]] = trim($parts[1]);
                    }

                }

            }

        } catch (\Exception $e) {
            //pass
        }

        set_theme_mod('market-areas-templates-info', json_encode($templates_info)) ;
    }


    function enqueue_admin_scripts()
    {
        global $pagenow, $typenow;

        /*===============================
        - Only in market areas page -
        ==================================*/
        if (is_admin() && $pagenow=='post-new.php' OR $pagenow=='post.php' && $typenow=='market_areas')
        {
            wp_enqueue_script('sortable-jquery', get_stylesheet_directory_uri() . "/kd-common/js/vendor/jquery-sortable.js",[], '0.9.13', true); //Loaded in the footer to overwrite the one by default.
            wp_enqueue_script('market-areas-script',get_stylesheet_directory_uri() . "/inc/market-areas-posttype/static/js/market-areas.js",[],'28.06.2016',true); //Market area script
            wp_enqueue_style('market-areas-css', get_stylesheet_directory_uri(). "/inc/market-areas-posttype/static/css/market-areas.css", [],'28.06.2016'); //Market area style
        }
    }
}
