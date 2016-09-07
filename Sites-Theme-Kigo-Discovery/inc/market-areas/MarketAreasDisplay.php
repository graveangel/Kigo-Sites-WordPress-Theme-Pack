<?php

namespace MarketAreasTable;

Class MA_Page {

    // class instance
    static $instance;
    // customer WP_List_Table object
    public $marketareas_obj;

    // class constructor
    public function __construct() {

        # Create Market Areas Main Page
        add_filter('set-screen-option', [ __CLASS__, 'set_screen'], 10, 3);
        add_action('admin_menu', [ $this, 'page_menu']);
        add_action('admin_enqueue_scripts', [$this, 'add_admin_scripts']);

    }

    public static function set_screen($status, $option, $value) {
        return $value;
    }

    /**
     * Removes the "in .. Vacation Rentals" text from market areas page titles.
     * @return array
     */
    function remove_suffixes() {


        try
        {
            # Get all market area pages
            $args = [
                'post_type' => 'page',
                'posts_per_page' => -1,
                'meta_key' => '_wp_page_template',
                'meta_value' => 'page-templates/market-area.php'
            ];
            $pages = get_posts($args);

            foreach ($pages as $page) {
                $new_title = preg_replace('#((in) .+)?(Vacation Rentals)#i', '', $page->post_title);
                $post = [
                    'ID' => $page->ID,
                    'post_title' => $new_title
                ];
                wp_update_post($post);
            }
        }catch(\Exception $err)
        {
            return $response = ['status' => 'error', 'message' => $err->getMessage()];
        }

        return $response = ['status' => 'ok', 'message' => 'Done'];
    }

    /**
     * Creates the maret-area page and/or sets the "page-templates/market-areas-controller.php"
     * to it so there is a market areas lansing page active.
     * @return array
     */
    function market_areas_main_page() {
        try {
            # Check if exists
            $market_areas_slug = 'market-areas';
            $market_areas_title = 'Market Areas';
            $market_areas_content = 'Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum a ante lacus. Nunc volutpat accumsan egestas. Morbi ultricies sodales nisl non sollicitudin. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris eu velit sit amet nulla gravida dignissim a in risus. Quisque posuere id turpis vitae condimentum. Curabitur posuere sem ut consectetur hendrerit. Aliquam lorem mauris, rhoncus ultricies mi vitae, pellentesque posuere lectus. Aenean bibendum dui sit amet vulputate mattis.';
            $args = [
                'pagename' => $market_areas_slug,
                'post_type' => 'page',
            ];
            $wp_query = new \WP_Query($args);
            $pages = !(bool) count($wp_query->posts);

            if ($pages) {
                # The page Does not exist. It needs to be created.
                $iargs = [
                    'post_type' => 'page',
                    'post_title' => $market_areas_title,
                    'post_status' => 'publish',
                    'post_name' => $market_areas_slug,
                    'post_content' => $market_areas_content,
                ];

                $maid = wp_insert_post($iargs);
            } else {
                $maid = $wp_query->posts[0]->ID;
            }


            # Now that I have the market areas page ID I can set the template
            $the_template = 'page-templates/market-areas-controller.php';
            update_post_meta($maid, '_wp_page_template', $the_template);
            return ['status' => 1, 'message' => "$market_areas_title landing page created successfully."];
        } catch (\Exception $err) {
            return ['status' => 0, 'message' => $err->getMessage()];
        }
    }

    function add_admin_scripts() {
        global $pagenow;
        $typenow = $_GET['page'];

        if (is_admin() && $pagenow == 'admin.php' && $typenow == 'market-areas') {
            wp_enqueue_script('inline-edit-post'); //Stack
            wp_enqueue_script('bootstrap-script', get_template_directory_uri() . "/inc/static/js/bootstrap.min.js", [], '13.07.2016', true); //Stack
            wp_enqueue_script('stacks-vars-and-funcs-script', get_template_directory_uri() . "/inc/static/js/mkta-admin-vars-and-functions.js", [], '13.07.2016', true); //Stack
            wp_enqueue_style('stacks-css', get_template_directory_uri() . "/inc/static/css/mka-admin.min.css", [], '13.07.2016'); //Stack area style
        }
    }

    public function page_menu() {

        # Check for post
        $create_mkta = $_POST['cmkta'];

        if (!empty($create_mkta)) {

            $response = $this->market_areas_main_page(); # Create the market areas main landing page
            header('Content-type: application/json');
            echo "{ \"status\": \"{$response['status']}\" , \"message\": \"{$response['message']}\"}";
            die;

        } elseif (!empty($_POST['remove_suffix'])) {

            $response = $this->remove_suffixes(); # remove market areas page title suffix
            header('Content-type: application/json');
            echo "{ \"status\": \"{$response['status']}\" , \"message\": \"{$response['message']}\"}";
            die;

        } elseif ($_POST['post']) {
            $this->update_post();
        } else {
            $hook = add_menu_page(
                'Kigo Market Areas', 'Market Areas', 'manage_options', 'market-areas', [ $this, 'theme_settings_page'], 'dashicons-groups', 299
            );

            add_action("load-$hook", [ $this, 'screen_option']);
        }
    }

    public function update_post() {
        $post = $_POST;

        if (!isset($_POST['marketarea_edit_nonce']) || !wp_verify_nonce($_POST['marketarea_edit_nonce'], 'save_market_area')
        ) {
            print 'Sorry, your nonce did not verify.';
            exit;
        } else {
            $args = [
                'ID' => $_POST['post']['post_id'],
                'post_title' => $_POST['post']['post_title'],
                'post_status' => $_POST['post']['status']
            ];
            if (!empty($_POST['post']['post_name'])) {
                $args['post_name'] = $_POST['post']['post_name'];
            }
            wp_update_post($args);

            wp_safe_redirect($_SERVER['REQUEST_URI']);
            die;
        }
    }

    /**
     * Screen options
     */
    public function screen_option() {
        $option = 'per_page';
        $args = [
            'label' => 'Market Areas',
            'default' => 5,
            'option' => 'market_areas_per_page',
        ];

        add_screen_option($option, $args);

        $this->marketareas_obj = new MarketAreasTable();
    }

    /**
     * Plugin settings page
     */
    public function theme_settings_page() {
        $themefoldername = get_template_directory();
        $preloader_url = get_template_directory_uri() . explode($themefoldername, dirname(__FILE__))[1] . '/static/img/Preloader_5.gif';
        include "market-areas-admin-page-template.php";
    }

    /** Singleton instance */
    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

}

add_action('init', function () {
    MA_Page::get_instance();
});






