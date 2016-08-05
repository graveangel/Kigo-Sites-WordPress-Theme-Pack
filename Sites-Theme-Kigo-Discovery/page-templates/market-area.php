<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
/*
Template Name: Market Area Page
*/

class MarketAreaController
{
    private $template,
            $template_vars = [];
    /**
     *Sets the tempalte vars and executes the index action
     */
    function __construct()
    {
         # Page number
        $this->paged             = ((filter_var($_GET['pag'],     FILTER_VALIDATE_INT) - 1 ) >= 1) ? (filter_var($_GET['pag'],     FILTER_VALIDATE_INT) - 1 ) : 0;
        
        # First confugure the template vars
        $this->configure_vars();
        
        # Set the template
        $this->template = substr(__FILE__,0,-4) . '-tmpl.php';
        
       
    
        
        # Execute the index action
        $this->index();
    }
    
    /**
     * The index action.
     * @return void This function does not return any value
     */
    function index()
    {
        # Render the template
        $this->render();
    }
    
    /**
     * This function extracts the template vars and includes the template.
     * @return void
     */
    function render()
    {
        global $post;
        extract($this->template_vars);
        if(file_exists($this->template))
        {
            include $this->template;   
        }
    }
    
    /**
     * Inserts a new key in the template vars array with
     * the given value.
     * 
     * @param string $varname
     * @param mixed $varval
     */
    function set($varname, $varval)
    {
        $this->template_vars[$varname] = $varval;
    }
    
    function get($varname)
    {
        return $this->template_vars[$varname];
    }
    
    function configure_vars()
    {
        global $post;
        # The Market Area
        $this->set('ma',json_decode(get_post_meta($post->ID,'bapi_property_data',true)));
        
        # The Market Area Parent
        $this->set('pt',json_decode(get_post_meta($post->post_parent,'bapi_property_data',true)));
        
        # Description
        $this->set('description',$post->post_content);
        
        # Title
        $ma = $this->get('ma');
        
        $post_title = apply_filters('the_title',$post->post_title);
        
        $ma_name = empty($ma->Name) ? $post_title : $ma->Name;
        
        $title = __('Rentals in') . ' ' . $ma_name;
        $this->set('title', $title);
        
        # Pics
        $pics = [ wp_get_attachment_url( get_post_thumbnail_id($post->ID) )];
        $this->set('pics', $pics);
        
        # Set Subareas & properties
        $this->set_subareas_n_properties();
        
    }
    
    function set_subareas_n_properties()
    {
        global $post;
        $sub_areas = [];
        $pages = get_pages(
		array(
			'sort_order' => 'asc',
			'sort_column' => 'post_title',
			'hierarchical' => 1,
			'exclude' => '',
			'include' => '',
			'meta_key' => '',
			'meta_value' => '',
			'authors' => '',
			'child_of' => $post->ID,
			'parent' => -1,
			'exclude_tree' => '',
			'number' => '',
			'offset' => 0,
			'post_type' => 'page',
			'post_status' => 'publish'
		)
	);
	$c=0;
        $pa = [];
        foreach($pages as $page){
		
		if($bapikey = get_post_meta($page->ID,'bapikey')){
			//print_r($bapikey);
			$bk = explode(':',$bapikey[0]);
			if($bk[0]=='marketarea'){
				$c++;
                         $sub_areas[] = $page;
                        }
                        if($bk[0]=='property'){
                                $page->bpd = json_decode(get_post_meta($page->ID,'bapi_property_data', true), true);
//                                debug($page->bpd, true);
                                $pa[$page->bpd['ID']] = $page;
                        }
                }
        }
        
        $this->set('sub_areas', $sub_areas);
        
        # Set properties
        $this->set_properties($pa);
    }
    
    function set_properties($pa)
    {
        $updated_props = [];
        
        //BAPI
        $api_key = get_option('api_key');
        $base_url = json_decode(get_option('bapi_solutiondata'))->BaseURL;
        $BAPI = new BAPI($api_key, $base_url);
        
        
        # List of Ids
        $props_ids = array_keys($pa);
        
        if(empty($props_ids))
            return [];
        
//        debug($props_ids, true);
        
        # For each prop I need to get the updated data.
        # Need to chunk the ids
        $ppp = get_option('posts_per_page');
   
        $maxrequest = 20;
        $size = ($ppp > $maxrequest) ? $maxrequest : $ppp;
        $ids_chunk = array_chunk($props_ids,$size); //chunk the ids to make the request.
 
        $this->max_num_pages =  count($ids_chunk);
        
        $props = [];

        
        switch(true)
        {
            case ($this->paged <= 0): //The number passed is negative
            $paged = 0;
            break;

            case ($this->paged > $this->max_num_pages ): // The number passed is higher than the available
            $paged = count($ids_chunk)-1;
            break;

            default:
                $paged = $this->paged;

        }
        
        $this->current_page = $paged;
        
        
        
        $ids = $ids_chunk[$paged]; //page to request

      
        
        $options =
        [
            'seo' => true,
            'currency' => $this->currency,
        ];
        
//          debug($ids, true);

        $response = $BAPI->get('property',$ids, $options);
        $properties_from_app = !empty($response['result']) ? $response['result'] : [];
        $updated_props = array_merge($updated_props,$properties_from_app);
        
        $visible_props = []; //Need to exclude the ones that are not supposed to be displayed in the site.
        
        foreach($updated_props as $prop)
        {
           if($prop['HideAvailability']) continue;
//           $prop_page = $pa[$prop->ID];
            $prop_page = json_decode(json_encode($prop), true);
           $visible_props[$prop['ID']] = $prop_page;
        }
//        debug($visible_props, true);
        
        $this->set('properties', $visible_props);
    }
    
    
    function has_properties()
    {
        return (bool) count($this->get('properties'));
    }
    
    function has_subareas()
    {
        return (bool) count($this->get('sub_areas'));
    }
}

new MarketAreaController();