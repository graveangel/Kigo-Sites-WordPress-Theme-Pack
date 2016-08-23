<?php
namespace MarketAreasTable;

Class MA_Page
{
        // class instance
	static $instance;

	// customer WP_List_Table object
	public $marketareas_obj;

	// class constructor
	public function __construct() {

    # Create Market Areas Main Page
		# 

		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'page_menu' ] );
                add_action('admin_enqueue_scripts', [$this,'add_admin_scripts']);
		add_action('wp_enqueue_scripts',[$this,'front_scripts']);


	}

  public static function set_screen( $status, $option, $value ) {
	   return $value;
  }

	function front_scripts()
	{
		// wp_register_script( 'theme-location-script', '/theme_location' );
		// wp_enqueue_script( 'theme-location-script' );
		// $translation_array = array( 'template_url' => get_stylesheet_directory_uri() );
		// //after wp_enqueue_script
		// wp_localize_script( 'theme-location-script', 'theme_info', $translation_array );
		wp_enqueue_script('google-maps-marker-label', get_stylesheet_directory_uri() . "/kd-common/lib/js-marker-with-label/markerwithlabel.js");
	}

function market_areas_main_page()
{
  try
  {
      # Check if exists
  $market_areas_slug    = 'market-areas';
  $market_areas_title   = 'Market Areas';
  $market_areas_content = 'Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum a ante lacus. Nunc volutpat accumsan egestas. Morbi ultricies sodales nisl non sollicitudin. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris eu velit sit amet nulla gravida dignissim a in risus. Quisque posuere id turpis vitae condimentum. Curabitur posuere sem ut consectetur hendrerit. Aliquam lorem mauris, rhoncus ultricies mi vitae, pellentesque posuere lectus. Aenean bibendum dui sit amet vulputate mattis.';
  $args =
  [
    'pagename' => $market_areas_slug,
    'post_type' => 'page',
  ];
  $wp_query = new \WP_Query($args);
  $pages = !(bool) count($wp_query->posts);

  if($pages)
  {
    # The page Does not exist. It needs to be created.
    $iargs =
    [
      'post_type'     => 'page',
      'post_title'    => $market_areas_title,
      'post_status'   => 'publish',
      'post_name'     => $market_areas_slug,
      'post_content'  => $market_areas_content,
    ];

    $maid = wp_insert_post($iargs);

  }
  else
  {
    $maid = $wp_query->posts[0]->ID;
  }

  // debug($wp_query->posts, true);
  # Now that I have the market areas page ID I can set the template
  $the_template = 'page-templates/market-areas-controller.php';
  update_post_meta( $maid, '_wp_page_template', $the_template );
  return ['status' => 1, 'message' => "$market_areas_title created successfully."];
  }catch(\Exception $err)
  {
      return ['status' => 0, 'message' => $err->getMessage()];
  }
  
}

function add_admin_scripts()
{
    global $pagenow;
    $typenow = $_GET['page'];

    if(is_admin() && $pagenow=='admin.php' && $typenow=='market-areas')
        {
            wp_enqueue_script('bootstrap-script',get_template_directory_uri() . "/inc/static/js/bootstrap.min.js",[],'13.07.2016',true); //Stack
            wp_enqueue_script('stacks-vars-and-funcs-script',get_template_directory_uri() . "/inc/static/js/mkta-admin-vars-and-functions.js",[],'13.07.2016',true); //Stack
            wp_enqueue_style('stacks-css', get_template_directory_uri(). "/inc/static/css/mka-admin.min.css", [],'13.07.2016'); //Stack area style
        }
}

public function page_menu() {
    
        # Check for post
        $create_mkta = $_POST['cmkta'];
        
        if(!empty($create_mkta))
        {
            $response = $this->market_areas_main_page();
            header('Content-type: application/json');
            echo "{ status: {$response['status']} , message: {$response['message']}}";
            die;
        }
        else
        {
            $hook = add_menu_page(
  		'Kigo Market Areas',
  		'Market Areas',
  		'manage_options',
  		'market-areas',
  		[ $this, 'theme_settings_page' ],
                'dashicons-groups',
                299
            );

            add_action( "load-$hook", [ $this, 'screen_option' ] );
        }

  	

  }

  /**
* Screen options
*/
public function screen_option() {
  	$option = 'per_page';
  	$args   = [
  		'label'   => 'Market Areas',
  		'default' => 5,
  		'option'  => 'market_areas_per_page',

  	];

  	add_screen_option( $option, $args );

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
  		<h2>Market Areas Table</h2>

                <div style="margin-top:-5px;">
                        <input class="button-primary crawlpages" type ="button" value="Sync Market Areas">
                </div>
  		<div id="poststuff">
  			<div id="post-body" class="metabox-holder columns-2">
  				<div id="post-body-content">

  					<div class="meta-box-sortables ui-sortable">
  						<form method="post">
  							<?php
  							$this->marketareas_obj->prepare_items(); # Gets the items and prepares the insrance.
  							$this->marketareas_obj->display(); # Builds the table. ?>
  						</form>
  					</div>
  				</div>
  			</div>
  			<br class="clear">
  		</div>

                <script type="text/javascript">




                $(".crawlpages").on('click', function(e)
        {
            e.preventDefault();
            modal(
                    {
                        title: '<i class="fa fa-exclamation-triangle fa-3x" aria-hidden="true"></i> <h2>Heads up!</h2>',
                        body: '<p> This action will crawl the <b>sitemap.xml</b> and create all pages in it. This will take some time. <b>Are you sure you want to do this?</b></p>',
                        footer: '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="button" class="btn primary-button mkta-btn-ok">Ok</button>'
                    });
            $('.mkta-btn-ok').on('click', function(e)
            {
                e.preventDefault();
				var txtresult = $('.modal-body');
                                txtresult.css('background-image',"url('<?php echo $preloader_url; ?>')")
                                        .css('background-repeat','no-repeat')
                                        .css('background-position','right 20px bottom 20px');

				txtresult.html('<h5>Crawling Sitemap.xml</h5>\n\
                                                <p>Please wait. This can take a while...</p>\n\
                                                <br><br>');
				var url = '/sitemap_crawler.svc';
				jQuery.post(url, {}, function(res) {
                                        txtresult.css('background-image','none')
                                        txtresult.html('<h5>Completed</h5>');
                                       
                                        jQuery.ajax(
                                                {
                                                    url: '',
                                                    type: 'post',
                                                    dataType : 'json',
                                                    data: {cmkta: 1},
                                                    success: function(data)
                                                    {
                                                        console.log(data);
                                                        location.reload();
                                                    }
                                                });
                                         txtresult.append(res);
					
				});
                             $('.mkta-btn-ok').remove();
            });
        });
                </script>
  	</div>
<style type="text/css">
@import url(https://fonts.googleapis.com/css?family=Varela+Round);
.modal-title * {font-size: 40px; margin: 0; display: inline-block;}
.modal-body {font-family: 'Varela Round', sans-serif; color: white; background: #66ceff; font-size: 18px;}
.modal-body h5 { font-size: 25px;}
</style>
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
  	if ( ! isset( self::$instance ) ) {
  		self::$instance = new self();
  	}

  	return self::$instance;
  }
}

add_action( 'init', function () {
	MA_Page::get_instance();
} );
