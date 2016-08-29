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
    
   
    function manage_posts_custom_column()
    {
      //
    }

    public static function set_screen($status, $option, $value) {
        return $value;
    }

    function remove_suffixes() {


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

        return $response = ['status' => 'ok', 'message' => 'Done'];
    }

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

            // debug($wp_query->posts, true);
            # Now that I have the market areas page ID I can set the template
            $the_template = 'page-templates/market-areas-controller.php';
            update_post_meta($maid, '_wp_page_template', $the_template);
            return ['status' => 1, 'message' => "$market_areas_title landing page created successfully."];
        } catch (\Exception $err) {
            return ['status' => 0, 'message' => $err->getMessage()];
        }
    }

    function add_admin_scripts()
{
    global $pagenow;
    $typenow = $_GET['page'];

    if(is_admin() && $pagenow=='admin.php' && $typenow=='market-areas')
        {
            wp_enqueue_script('inline-edit-post'); //Stack
            wp_enqueue_script('stacks-vars-and-funcs-script',get_stylesheet_directory_uri() . "/inc/market-areas/static/js/vars-and-functions.js",[],'13.07.2016',true); //Stack
            wp_enqueue_style('stacks-css', get_stylesheet_directory_uri(). "/inc/market-areas/static/css/mkta.css", [],'13.07.2016'); //Stack area style
        }
}

    public function page_menu() {

        # Check for post
        $create_mkta = $_POST['cmkta'];

        if (!empty($create_mkta)) {
            $response = $this->market_areas_main_page();
            header('Content-type: application/json');
            echo "{ \"status\": \"{$response['status']}\" , \"message\": \"{$response['message']}\"}";
            die;
        } elseif (!empty($_POST['remove_suffix'])) {
            $response = $this->remove_suffixes();
            header('Content-type: application/json');
            echo "{ \"status\": \"{$response['status']}\" , \"message\": \"{$response['message']}\"}";
            die;
        }
          elseif($_POST['post']){
              $this->update_post();
        } else {
            $hook = add_menu_page(
                    'Kigo Market Areas', 'Market Areas', 'manage_options', 'market-areas', [ $this, 'theme_settings_page'], 'dashicons-groups', 299
            );

            add_action("load-$hook", [ $this, 'screen_option']);
        }
    }
    
    public function update_post()
    {
        $post = $_POST;
//        var_dump($post); die;
        if ( ! isset( $_POST['marketarea_edit_nonce'] ) 
            || ! wp_verify_nonce( $_POST['marketarea_edit_nonce'], 'save_market_area' ) 
        ) {
           print 'Sorry, your nonce did not verify.';
           exit;
        } else {
            $args = [
               'ID'=> $_POST['post']['post_id'], 
               'post_title' => $_POST['post']['post_title'],
               'post_status' => $_POST['post']['status']
                   ];
            if(!empty($_POST['post']['post_name']))
            {
                $args['post_name'] = $_POST['post']['post_name'];
            }
           wp_update_post($args);
//          
           wp_safe_redirect( $_SERVER['REQUEST_URI'] );
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
        $themefoldername = 'instaparent';
        $preloader_url = get_template_directory_uri() . explode($themefoldername, dirname(__FILE__))[1] . '/static/img/Preloader_5.gif';
        ?>
        <div class="wrap">
            <h2>Market Area's</h2>

            <div style="margin-top:-5px;">
                <input class="button-primary create-main" type ="button" value="Create Market Area Landing Page"> 
                <input class="button-primary crawlpages" type ="button" value="Sync Market Areas">
            </div>
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">

                        <div class="meta-box-sortables ui-sortable">
                            <form method="post">
        <?php
        $this->marketareas_obj->prepare_items(); # Gets the items and prepares the insrance.
        $this->marketareas_obj->display(); # Builds the table. 
        ?>
                            </form>
                        </div>
                    </div>
                </div>
                <br class="clear">
            </div>

            <script type="text/javascript">

                $('.create-main').on('click', function (e)
                {
                    $('.modal-body').css('background-image: none;');
                    modal(
                            {
                                title: '<i class="fa fa-exclamation-triangle fa-3x" aria-hidden="true"></i> <h2>Heads up!</h2>',
                                body: '<p> This action will create a page called <b>Market Areas</b> if it does not exist yet. If it does it will assign the Main market area landing page template to it.</p>',
                                footer: '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" class="btn primary-button mkta-main-btn-ok">Ok</button>'
                            });

                    $('.mkta-main-btn-ok').on('click', function (e)
                    {

                        $.ajax(
                                {
                                    url: '',
                                    type: 'post',
                                    dataType: 'json',
                                    data: {cmkta: 1},
                                    complete: function (data)
                                    {
                                        var json_data = JSON.parse(data.responseText);
        //                                                        console.log(JSON.parse(data.responseText));

                                        modal(
                                                {
                                                    title: '<i class="fa fa-exclamation-triangle fa-3x" aria-hidden="true"></i> <h2>Done!</h2>',
                                                    body: json_data.message,
                                                    footer: '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>'
                                                });


                                    }
                                });

                        modal_close();
                    });

                });


                $(".crawlpages").on('click', function (e)
                {
                    e.preventDefault();
                    modal(
                            {
                                title: '<i class="fa fa-exclamation-triangle fa-3x" aria-hidden="true"></i> <h2>Heads up!</h2>',
                                body: '<p> This action will crawl the <b>sitemap.xml</b> and create all pages in it. This will take some time. <b>Are you sure you want to do this?</b></p>',
                                footer: '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" class="btn primary-button mkta-btn-ok">Ok</button>'
                            });
                    $('.mkta-btn-ok').on('click', function (e)
                    {
                        e.preventDefault();
                        var txtresult = $('.modal-body');
                        txtresult.css('background-image', "url('<?php echo $preloader_url; ?>')")
                                .css('background-repeat', 'no-repeat')
                                .css('background-position', 'right 20px bottom 20px');

                        txtresult.html('<h5>Crawling Sitemap.xml</h5>\n\
                                            <p>Please wait. This can take a while...</p>\n\
                                            <br><br>');
                        var url = '/sitemap_crawler.svc';
                        jQuery.post(url, {}, function (res) {
                            txtresult.css('background-image', 'none')
                            txtresult.html('<h5>Completed</h5>');

                            txtresult.append(res);
                            $.ajax(
                                    {
                                        url: '',
                                        type: 'post',
                                        dataType: 'json',
                                        data: {remove_suffix: 1},
                                        complete: function (data)
                                        {
                                            location.reload();
                                        }
                                    });

                        });
                        $('.mkta-btn-ok').remove();
                    });
                });
            </script>
        </div>

        <?php include('quick_edit_template.php'); ?>

        <style type="text/css">
            @import url(https://fonts.googleapis.com/css?family=Varela+Round);
            .modal-title * {font-size: 40px; margin: 0; display: inline-block;}
            .modal-body {color: #000; /*background: #66ceff*/; font-size: 18px;}
            .modal-body input { font-size: 14px;}
            .modal-title h4 { font-size: 25px; color:#214c59;}
            .modal-body h3 { font-size: 16px; color: #214c59;}
            .inline-edit-row fieldset { border: none;}
        </style>
        <?php $nonce = wp_nonce_field('save_market_area', 'marketarea_edit_nonce', false);?> 
        <script type="text/javascript">
            function create_quick_form(noncefield,$postrow, $postid, fieldvals)
            {
                try
                {
                    $templ.remove();
                    $lasHidden.show();
                    
                }catch(err)
                {
                    //
                }
                if(!fieldvals.post_name)
                    fieldvals.post_name = '';
                var template_string = $('#quick_edit_form tbody').html();
                    template_string = template_string.replace(/\[%post_title%\]/g,fieldvals.post_title)
                                                     .replace(/\[%post_name%\]/g,fieldvals.post_name)
                                                     .replace(/\[%post_id%\]/g,$postid)
                                                     .replace(/\[%nonce%\]/g,noncefield);
                    $templ = $(template_string).insertAfter($postrow);
                    $lasHidden = $postrow;
                    
                    $('.inline-edit-save .cancel').on('click', function(e)
                    {
                        $templ.remove();
                        $postrow.show();
                    });
                    
                    $('.inline-edit-save .save').on('click', function(e)
                    {
                        $('#qedit_form').submit();
                    });
                
            }
            (function ($) {

                $(function ()
                {
                    // we create a copy of the WP inline edit post function
                    var $wp_inline_edit = inlineEditPost.edit;

                    // and then we overwrite the function with our own code
                    inlineEditPost.edit = function (id) {

                        // "call" the original WP edit function
                        // we don't want to leave WordPress hanging
                        $wp_inline_edit.apply(this, arguments);

                        // now we take care of our business

                        // get the post ID
                        var $post_id = 0;
                        if (typeof (id) == 'object') {
                            $post_id = parseInt(this.getId(id));
                        }

                        if ($post_id > 0) {
                            // define the edit row
                            var $edit_row = $('#edit-' + $post_id);
                            var $post_row = $('#post-' + $post_id);

                            // get the data
                            var $post_title = $('.column-post_title > a:first-child', $post_row).text().replace(/\u2013|\u2014/g, "");
                            var str = $('.column-post_title .view a', $post_row).attr('href');
                            var $post_name = str.match(/\/.*\/(.+)\/+$/);
                             if($post_name)
                                $post_name = $post_name[1];
//                            console.log($post_name);
                            create_quick_form('<?php echo $nonce; ?>',$post_row, $post_id, {
                                post_title: $post_title,
                                post_name: $post_name,
                            });
                        }
                    };
                });

            })(jQuery);
        </script>
        <div class="modal fade" id="page-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
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


/* * ************************* */






