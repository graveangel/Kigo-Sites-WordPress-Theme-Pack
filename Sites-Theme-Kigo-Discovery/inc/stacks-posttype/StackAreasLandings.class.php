<?php

/**
 * StackAreasLandings class
 * This class creates the Stack areas post types, its custom fields and the widget to list them in the site..
 */

namespace Discovery\StackAreas;

if (!session_id()) {
    session_start();
}
require_once 'MetaBox.php';
require_once 'GenerateLandingMetaBox.class.php';
require_once 'PropsNStackAreas.class.php';

use Discovery\StackAreas\MetaBox;

if (!defined(DS))
    define('DS', DIRECTORY_SEPARATOR);

class StackAreasLandings {

    private $templates_dir;
    private $custom_fields;

    function __construct($templatesdir = '') {
        /* Auto Generate */
        $current_url = parse_url($_SERVER['REQUEST_URI']);
        $current_url['query'] = $_GET;

        switch (true) {
            case (preg_match('/edit\.php/', $current_url['path']) && $current_url['query']['post_type'] == 'stacks' && $current_url['query']['generate-from'] == 'market-areas'):
                $this->generate_from_market_areas();
                break;
            case (preg_match('/edit\.php/', $current_url['path']) && $current_url['query']['post_type'] == 'stacks' && $current_url['query']['generate-from'] == 'property-finders'):
                $this->generate_from_property_finders();
                break;
        }

        $this->custom_fields = $this->get_custom_fields();
        // Set the location of the stack areas templates
        $this->templates_dir = $templatesdir;

        //Get Templates Info:
        $this->get_templates_info();

        //Meta boxes
        $this->meta_boxes();

        //Create the custom post
        add_action('init', [$this, 'create_stk_custom_post']);

        // Load admin scripts
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);

        // Add auto create buttons
        add_action('admin_head-edit.php', [$this, 'add_auto_create_buttons']);

        //Move  Stacks to the bottom:
        add_action('admin_menu', [$this, 'move_stacks_menu']);

        /*
          Debug preview with custom fields
         */


        /* source: https://wordpress.org/support/topic/preview-and-post-meta-custom-fields-solution */
        add_filter('_wp_post_revision_fields', [$this, 'add_field_debug_preview']);
        add_action('edit_form_after_title', [$this, 'add_input_debug_preview']);

        /* source: https://johnblackbourn.com/post-meta-revisions-wordpress */
        add_action('save_post', [$this, 'stack_areas_save_post']);
        add_action('wp_restore_post_revision', [$this, 'stack_areas_restore_revision'], 10, 2);
        add_filter('_wp_post_revision_fields', [$this, 'stack_areas_revision_fields']);


        foreach ($this->custom_fields as $custom_field) {
            add_filter('_wp_post_revision_field_' . $custom_field['id'], [$this, 'stack_areas_revision_field'], 10, 2);
        }
    }

    function move_stacks_menu() {

        global $menu;
        $menu[300] = $menu[11];
        unset($menu[11]);
    }

    function generate_from_market_areas() {
        # Get all market areas
        $args = [
                    'posts_per_page' => -1,
                    'post_type' => 'page',
                    'meta_key' => '_wp_page_template',
                    'meta_value' => 'page-templates/market-area.php',
                    'hierarchical' => 1,
                    'meta_compare' => '!=',
        ];
        $market_areas = get_pages($args);

        # for each market area I need to create a stack
        foreach ($market_areas as $market_area) {

            # Hierarchy
            $arr = [
                        'child_of' => $market_area->ID,
                        'post_type' => 'page',
                        'meta_key' => '_wp_page_template',
                        'meta_value' => 'page-templates/market-area.php',
                        'hierarchical' => 1,
            ];
            $mktas = get_pages($arr);

            $mkta_hierarchy = $this->build_hierarchy($market_area->ID, $mktas);
            $treearr = $this->build_tree_from($mkta_hierarchy);
            $mkta_tree = json_encode($treearr);




//           if($market_area->post_title == 'New York')
//           {
//               //echo  $mkta_parent_id; 
//               debug($mkta_hierarchy, true);
//               
//           }

            $parent_id = 0;


            # Create stack
            $post_array = [
                        'post_status' => 'publish',
                        'post_title' => $market_area->post_title,
                        'post_name' => sanitize_title($market_area->post_title),
                        'post_content' => $market_area->post_content,
                        'post_type' => 'stacks',
                        'post_parent' => $parent_id,
            ];

            /**
             * @todo Need to take the info of:
             * - the slideshow images 
             * - the featured image.
             */
            $new_stack_id = wp_insert_post($post_array, true);

            /**
             * @todo update parent and set the tree after everything is created.
             */
            $mkta_parent_id = $market_area->post_parent;

            if ($mkta_parent_id) {
                $mkta_parent = get_post($mkta_parent_id);

                $parent_stack = get_page_by_title($mkta_parent->post_title, 'OBJECT', 'stacks');
                $parent_id = $parent_stack->ID;
            }

            $newpost = get_post($new_stack_id);
            $newpost->post_parent = $parent_id;

            wp_update_post($newpost);


            # Save the tree
            if (!add_post_meta($new_stack_id, 'stack_area_props_n_areas', $mkta_tree, true)) {
                update_post_meta($new_stack_id, 'stack_area_props_n_areas', $mkta_tree);
            }
        }
        wp_redirect('/wp-admin/edit.php?post_type=stacks', 301);
        exit;
    }

    function build_hierarchy($parentid, $arr) {
        $children = [];
        foreach ($arr as $idx => $ar) {
            if ($ar->post_parent === $parentid) {
                $children[$idx] = $ar;
                $children[$idx]->children = $this->build_hierarchy($ar->ID, $arr);
            }
        }

        return $children;
    }

    function build_tree_from($mkta_hierarchy) {
        $tree = [];
        foreach ($mkta_hierarchy as $idx => $branch) {
            $tree[$idx] = [];

            # Get the type: market area or ppt page
            $template = get_page_template_slug($branch->ID);
            switch (true) {
                case (preg_match('/page-templates\/market-area\.php/', $template)):
                    $tree[$idx]['name'] = $branch->post_title;
                    $tree[$idx]['originalName'] = $branch->post_title;
                    $tree[$idx]['contents'] = [];
                    # Add direct ppt pages to children
                    $children_ppts = $this->get_direct_children_pp($branch->ID);
                    if (count($children_ppts))
                        $branch->children = array_merge($children_ppts, $branch->children);



                    if (count($branch->children))
                        $tree[$idx]['contents'] = $this->build_tree_from($branch->children);
                    break;
                default:
                    $bapid = explode(':', get_post_meta($branch->ID, 'bapikey', true))[1];
                    $tree[$idx]['id'] = $bapid;
                    break;
            }
            $tree[$idx]['parent'] = $branch->ID;
        }
        return $tree;
    }

    function get_direct_children_pp($parentid) {

        # Put all child property pages
        # Hierarchy
        $args = [
//                'child_of' => $parentid->ID,
                    'parent' => $parentid,
                    'post_type' => 'page',
                    'meta_key' => '_wp_page_template',
                    'meta_value' => 'page-templates/property-detail.php',
        ];
        $child_ppts = get_pages($args);

        return $child_ppts;
    }

    function add_auto_create_buttons() {

        global $current_screen;

        // Not our post type, exit earlier
        // You can remove this if condition if you don't have any specific post type to restrict to. 
        if ('stacks' != $current_screen->post_type)
            return;

        include "StacksEditInsert.php";
    }

    function stack_areas_save_post($post_id) {

        $parent_id = wp_is_post_revision($post_id);

        if ($parent_id) {

            $parent = get_post($parent_id);
            $metas_array = [];
            foreach ($this->custom_fields as $custom_field) {

                $my_meta = filter_input(INPUT_POST, $custom_field['id']);

                if (false !== $my_meta) {
                    //Putting the meta data in the revision excerpt
                    $metas_array[$custom_field['id']] = json_decode($my_meta);
                }
            }
            $this->hold_preview_metas($parent_id, $metas_array);
        }
    }

    function hold_preview_metas($post_id, $metas_array) {

        if (gettype($_SESSION['stack_area_preview_metas']) != 'array')
            $_SESSION['stack_area_preview_metas'] = [];

        $_SESSION['stack_area_preview_metas'][$post_id] = $metas_array;
    }

    function stack_areas_restore_revision($post_id, $revision_id) {

        $post = get_post($post_id);
        $revision = get_post($revision_id);
        foreach ($this->custom_fields as $custom_field) {
            $my_meta = get_metadata('post', $revision->ID, $custom_field['id'], true);

            if (false !== $my_meta)
                update_post_meta($post_id, $custom_field['id'], $my_meta);
            else
                delete_post_meta($post_id, $custom_field['id']);
        }
    }

    function stack_areas_revision_fields($fields) {
        foreach ($this->custom_fields as $custom_field) {
            $fields[$custom_field['id']] = $custom_field['id'];
        }

        return $fields;
    }

    function stack_areas_revision_field($value, $field) {

        global $revision;
        return get_metadata('post', $revision->ID, $field, true);
    }

    function add_field_debug_preview($fields) {
        $fields['stack_area_props_n_areas'] = 'debug_preview';
        return $fields;
    }

    function add_input_debug_preview() {
        echo '<input type="hidden" name="stack_area_props_n_areas" value="stack_area_props_n_areas">';
    }

    function create_stk_custom_post() {
        //Stack areas post type
        $post_types = array(
            'stacks' => array(
                'label' => 'Stacks',
                'labels' => array(
                    'name' => __('Stacks', 'kd'),
                    'singular_name' => __('Stack', 'kd'),
                ),
                'description' => __('Group properties in a single landing page.', 'kd'),
                'public' => true,
                'capability_type' => 'page',
                'has_archive' => true,
                'menu_icon' => 'dashicons-editor-alignright',
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
                'hierarchical' => true,
                'show_in_admin_bar' => true,
                'taxonomies' => array('category'),
                'menu_position' => 10,
                'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
                'description' => 'Stacks custom posts',
            ),
        );

        foreach ($post_types as $name => $args) {
            register_post_type($name, $args);
        }
    }

    function meta_boxes() {
        $args_array = $this->custom_fields;

        foreach ($args_array as $args) {

            if ($args['id'] !== 'stack_area_generate_landing')
                $newMeta = new MetaBox($args, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'meta_templates');
            else
                $newMeta = new GemerateLandingMetaBox($args, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'meta_templates'); //special class to
        }
    }

    /**
     * Gets the first block comment of the page
     * @param  string $filename the name of the file to parse (a valid path)
     * @return array           the comments.
     */
    function getFileDocBlock($filename) {
        $docComments = array_filter(
                token_get_all(file_get_contents($filename)), function($entry) {
            return $entry[0] == T_DOC_COMMENT;
        }
        );
        $fileDocComment = array_shift($docComments);
        return $fileDocComment[1];
    }

    /**
     * Saves an array of the stack areas templates info as a theme mod as a json string
     * @return void this function does not return any value
     */
    function get_templates_info() {
        $templates_info = [];
        // 1. get all templates inside the templates dir if available

        try {
            // echo $this->templates_dir; die;
            $files_in_dir = scandir($this->templates_dir);

            foreach ($files_in_dir as $filename) {
                $template_name = substr($filename, 0, -4);
                if (!preg_match('/stacks/', $filename))
                    continue;

                $filedoc = $this->getFileDocBlock($this->templates_dir . DS . $filename);
                preg_match_all('/@[a-z0-9A-Z]+:.*/', $filedoc, $matches);

                if (count($matches)) {
                    $templates_info[$template_name] = [];

                    foreach ($matches[0] as $infoline) {
                        $parts = explode(':', substr($infoline, 1));
                        $templates_info[$template_name][$parts[0]] = trim($parts[1]);
                    }
                }
            }
        } catch (\Exception $e) {
            //pass
        }

        set_theme_mod('stacks-templates-info', json_encode($templates_info));
    }

    function enqueue_admin_scripts() {
        global $pagenow, $typenow;

        /* ===============================
          - Only in stack areas page -
          ================================== */
        if (is_admin() && $pagenow == 'post-new.php' OR $pagenow == 'post.php' && $typenow == 'stacks') {
            wp_enqueue_script('sortable-jquery', get_stylesheet_directory_uri() . "/kd-common/js/vendor/jquery-sortable.js", [], '0.9.13', true); //Loaded in the footer to overwrite the one by default.
            wp_enqueue_script('stacks-vars-and-funcs-script', get_stylesheet_directory_uri() . "/inc/stacks-posttype/static/js/stacks-vars-and-functions.js", [], '13.07.2016', true); //Stack
            wp_enqueue_script('stacks-script', get_stylesheet_directory_uri() . "/inc/stacks-posttype/static/js/stacks.js", [], '13.07.2016', true); //Stack area script
            wp_enqueue_style('stacks-css', get_stylesheet_directory_uri() . "/inc/stacks-posttype/static/css/stacks.min.css", [], '13.07.2016'); //Stack area style
        }

        if (is_admin() && $pagenow == 'edit.php' && $typenow == 'stacks') {
            wp_enqueue_script('stacks-vars-and-funcs-script', get_stylesheet_directory_uri() . "/inc/stacks-posttype/static/js/stacks-vars-and-functions.js", [], '13.07.2016', true); //Stack
            wp_enqueue_style('stacks-css', get_stylesheet_directory_uri() . "/inc/stacks-posttype/static/css/stacks.min.css", [], '13.07.2016'); //Stack area style
        }
    }

    function get_custom_fields() {
        return array(
            // Stack area description
            // array(
            //     'id'            => 'stack_area_description',
            //     'title'         => '<h4>Description</h4>',
            //     'screen'        => 'stacks',
            //     'context'       => 'normal',
            //     'priority'      => 'default',
            //     'template'      => 'stack_area_description.php',
            //     'need_sanitize' => false,
            //     'description'   => 'A description of the stack area.',
            // ),
            // Images
            array(
                'id' => 'stack_area_photos',
                'title' => '<h4>Photos</h4>',
                'screen' => 'stacks',
                'context' => 'normal',
                'priority' => 'default',
                'template' => 'stack_area_photos.php',
                'need_sanitize' => false,
                'description' => 'A set of images. You can sort them by dragging them.',
            ),
            // Use landing : select template
            array(
                'id' => 'stack_area_use_landing_page',
                'title' => '<h4>Landing</h4>',
                'screen' => 'stacks',
                'context' => 'normal',
                'priority' => 'default',
                'template' => 'stack_area_landing.php',
                'need_sanitize' => false,
                'description' => '',
            ),
            // Stack areas tree
            array(
                'id' => 'stack_area_props_n_areas',
                'title' => '<h4>Define Location, Sub-areas (optional) and Properties.</h4>',
                'screen' => 'stacks',
                'context' => 'advanced',
                'priority' => 'default',
                'template' => 'stack_area_props_n_areas.php',
                'need_sanitize' => false,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean molestie luctus lorem, sed sollicitudin eros suscipit vitae. Suspendisse potenti. Nunc fermentum purus in mi porttitor, non congue erat rhoncus. Duis ut turpis a ex bibendum sollicitudin. Vivamus condimentum rutrum mi, ultrices commodo ex egestas in. Vestibulum sapien justo, suscipit in commodo et, sodales vitae libero. Nulla id luctus nisi, ac porttitor neque. Integer laoreet, augue nec sollicitudin vestibulum, arcu ligula suscipit elit, vel ornare arcu ipsum et ligula. Maecenas porta sapien quis facilisis porta. Maecenas placerat et dolor nec facilisis. Quisque molestie cursus urna, ut tincidunt ante malesuada vitae. Fusce odio ante, lacinia at tortor ut, egestas vehicula velit.',
                'rules' =>
                [
                    '/`/', // No backticks
                    // '/\'/',// No simplequotes
                    '/[<>]+/', // No tags
                ]
            ),
            // Generate landing :
            //      This needs to be the after the tree meta box in this array in order to take the latest saved value of the stack areas tree
            array(
                'id' => 'stack_area_generate_landing',
                'title' => '<h4>Generate Landings</h4>',
                'screen' => 'stacks',
                'context' => 'normal',
                'priority' => 'default',
                'template' => 'stack_area_landing_generate.php',
                'need_sanitize' => false,
                'description' => 'Check this field if you want to create landing pages out of the locations in the Stack areas tree',
            ),
        );
    }

}
