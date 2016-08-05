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
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'page_menu' ] );
                add_action('admin_enqueue_scripts', [$this,'add_admin_scripts']);
	}

  public static function set_screen( $status, $option, $value ) {
	return $value;
}

function add_admin_scripts()
{
    global $pagenow;
    $typenow = $_GET['page'];
    
    if(is_admin() && $pagenow=='admin.php' && $typenow=='market-areas')
        {
            wp_enqueue_script('stacks-vars-and-funcs-script',get_stylesheet_directory_uri() . "/inc/stacks-posttype/static/js/stacks-vars-and-functions.js",[],'13.07.2016',true); //Stack
            wp_enqueue_style('stacks-css', get_stylesheet_directory_uri(). "/inc/stacks-posttype/static/css/stacks.min.css", [],'13.07.2016'); //Stack area style
        }
}

public function page_menu() {

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
                                txtresult.css('background-image','url(http://smashinghub.com/wp-content/uploads/2014/08/cool-loading-animated-gif-1.gif)')
                                        .css('background-repeat','no-repeat')
                                        .css('background-size','100% auto');
                                
				txtresult.html('<h5>Crawling Sitemap.xml</h5>\n\
                                                <p>Please wait. This can take a while...</p>\n\
                                                <br><br>');
				var url = '/sitemap_crawler.svc';
				jQuery.post(url, {}, function(res) {
                                        txtresult.css('background-image','none')
                                        txtresult.html('<h5>Completed</h5>');
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
