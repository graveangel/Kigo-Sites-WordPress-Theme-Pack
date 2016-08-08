<?php
namespace UserDefinedFields;
require "MetaBox.php";
require "UserDefinedFieldsController.php";

class UserDefinedFieldsDisplay
{
    // class instance
    static $instance;
        
    // class constructor
    public function __construct() {
            add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
            add_action( 'admin_menu', [ $this, 'page_menu' ] );
            add_action('admin_enqueue_scripts', [$this,'add_admin_scripts']);
            add_action('admin_init', [$this,'add_udpas_to_ppt_pages']);
    }
    
   
  function page_menu()
  {
      $hook = add_menu_page(
  		'User Defined Fields',
  		'UDF',
  		'manage_options',
  		'user-defined-fields',
  		[ $this, 'theme_settings_page' ],
                'dashicons-admin-multisite',
                280
  	);

  	add_action( "load-$hook", [ $this, 'screen_option' ] );
  }
  
  function screen_option()
  {
      
  }
  
  
  function theme_settings_page()
  {
      new UserDefinedFieldsController();
  }
    
    
  function add_admin_scripts()
  {
      $screen = $_GET['page'];

      if($screen && $screen == 'user-defined-fields')
      {
          # the scripts if needed
            wp_enqueue_script('stacks-vars-and-funcs-script',get_stylesheet_directory_uri() . "/inc/stacks-posttype/static/js/stacks-vars-and-functions.js",[],'13.07.2016',true); //Stack
            wp_enqueue_script('udf-scripts',get_stylesheet_directory_uri() . "/inc/UserDefinedFields/static/js/udf.js",[],'13.07.2016',true); //UDF scripts
            wp_enqueue_style('stacks-css', get_stylesheet_directory_uri(). "/inc/stacks-posttype/static/css/stacks.min.css", [],'13.07.2016'); //Stack area style
            wp_enqueue_style('udf-css', get_stylesheet_directory_uri(). "/inc/UserDefinedFields/static/css/udf.css", [],'13.07.2016'); //UDF styles
      }
      
  }
    
    /** Singleton instance */
  public static function get_instance() {
  	if ( ! isset( self::$instance ) ) {
  		self::$instance = new self();
  	}

  	return self::$instance;
  }
  
  /**
 * Create a meta box text field for all property pages.
 */
function add_udpas_to_ppt_pages()
{
	// get all property pages in wordpress:

	include_once "inc/MetaBox.php";
        $args_array = [];
        
        
	
        $udfs = json_decode(json_encode(get_option('udfs')), true);
        $udf_types = ['checkbox','single_line','multiple_line', 'single_choice', 'multiple_choice']; //0,1,2,3
        foreach($udfs as $udf)
        {
            $template = "udf_{$udf_types[$udf['type']]}_template.php";
            $args_array[] = array(
                        'id'            => $udf['slug'],
                        'title'         => $udf['name'],
                        'screen'        => 'page',
                        'context'       => 'side',
                        'priority'      => 'default',
                        'template'      => $template,
                        'need_sanitize' => false,
                        'description'   => $udf['description'],
                        'with_template' => 'page-templates/property-detail.php',
                        'options'       => $udf['options']
                    );
            
        }

   foreach ($args_array as $args) {
          $newMeta = new MetaBox($args,dirname(__FILE__));
      }
}


    
}

add_action( 'init', function () {
	UserDefinedFieldsDisplay::get_instance();
} );