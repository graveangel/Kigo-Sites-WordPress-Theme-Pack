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
        $this->paged             = (get_query_var('paged')) ? get_query_var('paged') : 1;//((filter_var($_GET['pag'],     FILTER_VALIDATE_INT) - 1 ) >= 1) ? (filter_var($_GET['pag'],     FILTER_VALIDATE_INT) - 1 ) : 0;
        
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
        # First confugure the template vars
        $this->configure_vars();
        
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
    
    /**
     * Returns a template variable
     * @param type $varname the name of the templat evariable to get
     * @return mixed
     */
    function get($varname)
    {
        return $this->template_vars[$varname];
    }

    /**
     * This function sets all the template variables.
     * @global type $post The post of this page.
     * @return void
     */
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
        
        $ma_name = $post_title;//empty($ma->Name) ? $post_title : $ma->Name;
        
        $title = $ma_name;
        $this->set('title', $title);
        
        # Pics
        $pics = [ wp_get_attachment_url( get_post_thumbnail_id($post->ID) )];
        
        # bapi_property_data
        $bapi_property_data = json_decode(get_post_meta($post->ID,'bapi_property_data', true ));
        
        $this->set('latitude', $bapi_property_data->Latitude);
        
        $this->set('longitude', $bapi_property_data->Longitude);
        
        $this->set('pics', $pics);
        
        # Set Subareas & properties
        $this->set_subareas();
        # Set properties
        $this->set_properties();
        
        # all props json
        $all_props = [];
        
        foreach($this->get('properties') as $property_page)
            {
                $bpd = $property_page->bpd;
                $url        = $bpd['ContextData']['SEO']['DetailURL'];
                $title      = $bpd['ContextData']['SEO']['PageTitle'];
                $thumbnail  = $bpd['PrimaryImage']['ThumbnailURL'];
                $summary    = $bpd['Summary'];
                $latitude   = $bpd['Latitude'];
                $longitude  = $bpd['Longitude'];
            
                $prop_obj                   = new \stdClass();
                $prop_obj->url              = $url;
                $prop_obj->title            = $title;
                $prop_obj->thumbnail        = $thumbnail;
                $prop_obj->summary          = $summary;
                $prop_obj->location         = new \stdClass();
                $prop_obj->location->lat    = (float) $latitude;
                $prop_obj->location->lng    = (float) $longitude;
                
                
                $all_props[] = $prop_obj;
            }
            
        
        $all_props_json = json_encode($all_props);
        $this->set('all_props', $all_props_json);
    }
    
    function r($term)
    {
        $r = \render_this_str("{{#site}}{{textdata.$term}}{{/site}}");
        if(empty($r)) return $term;
        return $r;
    }
    
    function set_subareas()
    {
        global $post;
        $sub_areas = [];
        $pages = get_pages(
		array(
			'sort_order'        => 'asc',
			'sort_column'       => 'post_title',
                        'posts_per_page'    => -1,
			'hierarchical'      => 1,
			'exclude'           => '',
			'include'           => '',
			'authors'           => '',
			'exclude_tree'      => '',
			'number'            => '',
			'offset'            => 0,
                        'child_of'          => $post->ID,
			'post_type'         => 'page',
			'post_status'       => 'publish',
                        'meta_query' => 
                                    [
                                        'relation' => 'AND',
                                        [
                                            'key'          => 'bapikey',
                                            'value'        => 'marketarea',
                                            'compare'      => 'like'
                                        ]
                                    ]
		)
	);
        
	$c=0;
        $pa = [];
        foreach($pages as $page){
		
		if($bapikey = get_post_meta($page->ID,'bapikey')){
			
			$bk = explode(':',$bapikey[0]);
			if($bk[0]=='marketarea')
                        {
                            $c++;
                            $page->bpd     = json_decode(get_post_meta($page->ID,'bapi_property_data', true), true);
                            $sub_areas[]   = $page;
                        }
                }
        }
        
        $this->set('sub_areas', $sub_areas);
        
        # Set properties
        //$this->set('properties' , $pa);
        $this->set('ptotal'     , count($pa));
        
        $this->set('pvisible'   ,count($this->get('properties')));
    }
    
    /**
     * Sets the array of propeties inside this Market Area.
     * Enables pagination.
     * @todo Need to adapt this so it does not make any calls to the app.
     * @param array $pa
     * @return void 
     */
    function set_properties()
    {
       
        
        $ppp = get_option('posts_per_page');
   
//        $maxrequest = 20;
        $size       = $ppp; //($ppp > $maxrequest) ? $maxrequest : $ppp;
        switch(true)
        {
            case ($this->paged <= 0): //The number passed is negative
            $paged = 0;
            break;

            default:
                $paged = $this->paged;

        }
        
        global $post;
        
        $this->current_page =  $paged;
//        echo $this->current_page; die;
        $args = [
                        'sort_order'        => 'asc',
			'sort_column'       => 'post_title',
                        'posts_per_page'    => $size,
			'include_children'  => true,
                        'paged'             => $this->current_page,
                        'post_parent '      => $post->ID,
			'post_type'         => ['page'],
			'post_status'       => ['publish'],
                        'meta_key'          => '_wp_page_template',
                        'meta_value'        => 'page-templates/property-detail.php'
                                    
        ];
        $Query = new WP_Query($args);

        
        
        $this->max_num_pages = $Query->max_num_pages;//ceil(count($pa)/$size);
        
        
        
        $offset =  $size * $this->current_page;
        
        $visible_props = $Query->posts;//array_slice($pa, $offset, $size);
        
        foreach($visible_props as $ind => $page)
        {
         
            $visible_props[$ind]->bpd    = json_decode(get_post_meta($page->ID,'bapi_property_data', true), true);
          
        }
        
        $this->set('pvisible', count($visible_props));
        $this->set('ptotal', $Query->found_posts);
        
        $this->set('properties', $visible_props);
    }
    
    /**
     * Tells if this Market Area has properties
     * @return bool
     */
    function has_properties()
    {
        return (bool) count($this->get('properties'));
    }
    /**
     * Tells if this Market Area has sub areas.
     * @return bool
     */
    function has_subareas()
    {
        return (bool) count($this->get('sub_areas'));
    }
}

new MarketAreaController();