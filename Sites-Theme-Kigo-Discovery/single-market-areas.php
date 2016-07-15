<?php
/**
 * Market Areas Controller
 */
if(!defined(DS))
    define('DS', DIRECTORY_SEPARATOR);

class MarketAreasController
{
    private $template_path,
            $template_vars,
            $currency_from,
            $currency,
            $properties_ids,
            $page,
            $location,
            $current_page,
            $post;

    public function __construct()
    {
        global $post;

        $this->post = $post;

        $this->id = $post->ID; //The post id

        $this->set_template(); //Select the landing template

        $this->page             = ((filter_var($_GET['pag'],     FILTER_VALIDATE_INT) - 1 ) >= 1) ? (filter_var($_GET['pag'],     FILTER_VALIDATE_INT) - 1 ) : 0;
        $this->location         = filter_var($_GET['subarea'], FILTER_SANITIZE_STRING);

        $this->set_vars_and_metas(); // Set template vars

        $this->currency = $this->get_currency_selected(); // The currency selected from the bapicurrency selector

        $this->index_action();

    }

    /**
     * prepares the elements to render
     * @return void this function does not return a value
     */
    public function index_action($ajax=false)
    {
        if($ajax)
            $this->ajax($this->template_vars);
        else
            $this->render($this->template_vars);
    }

    /**
     * Returns the pagination data
     * @return void this function does not return a value
     * @todo ...!!!
     */
    public function ajax()
    {

    }

    /**
     * adds the template varibles to the array with
     * the metas and post contents.
     */
    private function set_vars_and_metas()
    {

        //title
        $this->template_vars['title'] = apply_filters('the_title', $this->post->post_title);

        //excerpt
        $this->template_vars['excerpt'] = apply_filters('the_excerpt',$this->post->post_excerpt);

        //featured image:
        $this->template_vars['featured_image'] = wp_get_attachment_url( get_post_thumbnail_id($this->id) );

        //description
        $this->template_vars['description'] = apply_filters('the_content',$this->post->post_content);


        if(!is_preview())
        {
            $metas = get_post_meta($this->id);

            //json_use_landing
            $this->template_vars['json_use_landing'] = array_key_exists('market_area_use_landing_page',$metas) ? $metas['market_area_use_landing_page'][0] : '[]';

            //json_tree
            $this->template_vars['tree'] = array_key_exists('market_area_props_n_areas',$metas) ? $metas['market_area_props_n_areas'][0] : '[]';

            //pics
            $this->template_vars['pics'] = json_decode($metas['market_area_photos'][0], true);
        }
        else
        {
            //get from session
            $metas = $_SESSION['market_area_preview_metas'];

            //json_use_landing
            $this->template_vars['json_use_landing'] = array_key_exists('market_area_use_landing_page',$metas) ? json_encode($metas['market_area_use_landing_page']) : '[]';

            //json_tree
            $this->template_vars['tree'] = array_key_exists('market_area_props_n_areas',$metas) ? json_encode($metas['market_area_props_n_areas']) : '[]';

            //pics
            $this->template_vars['pics'] = $metas['market_area_photos'];

        }




        //properties ids
        $this->properties_ids =  $this->get_properties_ids();

        $props = $this->fetch_properties();
    }

    /**
     * defines the template for this controller.
     */
    public function set_template()
    {
        $templates_dir          = 'page-templates';
        $template_name          = 'market-areas-tmpl';


        //Get the saved param:
        $landing_value          = json_decode(stripcslashes(get_post_meta( $this->id,'market_area_generate_landing', true)), true);

        $use_landing = $landing_value['landing'];
        $template_selected = $landing_value['template'];

        if($use_landing)
        {
            $template_name = $template_selected;
        }

        $template_path          =  $templates_dir . DS . $template_name . '.php';

        $this->template_path    = $template_path;
    }

    /**
     * Renders the template.
     * @param  array $template_vars The variables to include in the template
     * @return null                no return is defined.
     */
    public function render($template_vars=[])
    {
        extract($template_vars);

        $template_fullpath = dirname(__FILE__) . DS . $this->template_path;

        require $template_fullpath;
    }

    /**
     * Checks weather it has sub areas or Not
     * @return boolean true if has sub areas, false if doesn't
     */
    private function has_subareas()
    {
        $tree = json_decode($this->template_vars['tree'], true);
        if(count($tree))
            if(array_key_exists('originalName',$tree[0]))
                return true;
        return false;
    }

    /**
     * Checks weather it has properties or Not
     * @return boolean true if has properties, false if doesn't
     */
    private function has_properties()
    {
        $tree = json_decode($this->template_vars['tree'], true);
        $has_props = (0 < count($this->get_properties($tree)));
        return $has_props;
    }

    private function get_locations($tree)
    {
        $locs = [];

        if(is_array($tree))
            foreach($tree as $branch)
            {
                if(array_key_exists('originalName',$branch))
                {
                    $contents = $branch['contents'];
                    unset($branch['contents']);

                    //landing if exists for it.
                    $post = get_page_by_title( html_entity_decode($branch['name']) , 'OBJECT', 'market-areas' );

                    //if a landing has been published then add the url and thumbnail.
                    if($post && $post->post_status === 'publish')
                    {
                        $branch['landing_url'] = $post->guid;
                        $branch['thumbnail_url'] = wp_get_attachment_url( get_post_thumbnail_id($post->ID));
                    }

                    $locs[] = $branch;
                    //$locs = array_merge($locs,$this->get_locations($contents));
                }
            }

        return $locs;
    }


    /**
     * Gets the properties from the app
     * @return array The array of properties requested
     */
    private function fetch_properties()
    {
        //BAPI
        $api_key = get_option('api_key');
        $base_url = json_decode(get_option('bapi_solutiondata'))->BaseURL;
        $BAPI = new BAPI($api_key, $base_url);


        if(!empty($this->location))
        {
            $ids = $this->get_properties_ids_in_node($this->location);
            setcookie('subarea',1,time()+3600); //Seting cookie to activate tab in the page
        }
        else
        {
            $ids = $this->properties_ids;
            setcookie('subarea',0,time()+3600);
        }

        $ppp = get_option('posts_per_page');

        $options =
        [
            'seo' => true,
            'currency' => $this->currency,
        ];

        $options[$this->location_type] = $this->location; //Find a way to filter locations

        // The maximum number of results per page: this takes wordpress configuration until the maximum the app permits
        $maxrequest = 20;
        $size = ($ppp > $maxrequest) ? $maxrequest : $ppp;

        $ids_chunk = array_chunk($ids,$size); //chunk the ids to make the request.
        $this->max_num_pages =  count($ids_chunk);
        $props = [];

        switch(true)
        {
            case ($this->page <= 0): //The number passed is negative
            $page = 0;
            break;

            case ($this->page > $this->max_num_pages ): // The number passed is higher than the available
            $page = count($ids_chunk)-1;
            break;

            default:
                $page = $this->page;

        }

        $this->current_page = $page;


        $ids = $ids_chunk[$page]; //page to request

        // debug($options, true);

        $response = $BAPI->get('property',$ids, $options);
        $properties_from_app = !empty($response['result']) ? $response['result'] : [];
        $props = array_merge($props,$properties_from_app);

        return $props;
    }

    /**
     * Gets all properties in the given tree
     * @param  array $tree a multi-level array of locations and properties
     * @return array       an array that contains all found properties
     */
    private function get_properties($tree)
    {
        $props = [];
        if(is_array($tree))
            foreach($tree as $branch)
            {
                if(is_array($branch))
                {
                    if(array_key_exists('id',$branch))
                        $props[$branch['id']] = $branch;
                    if(array_key_exists('originalName',$branch))
                        $props = array_merge($props,$this->get_properties($branch['contents']));
                }
            }
        return $props;
    }


    /**
     * returns an array of ids of all the properties in the given tree: if not especified then it uses the main tree
     * @param  array $tree the tree to get the ids from
     * @return array       the array of ids
     */
    function get_properties_ids($tree=false)
    {
        $tree = $tree ? : json_decode($this->template_vars['tree'], true);
        $properties = $this->get_properties($tree);
        $ids=[];
        foreach($properties as $property)
        {
            $ids[] = $property['id'];
        }
        return $ids;
    }

    /**
     * Get all the propeties in a subnode.
     * @param  string $nodename the name of the node
     * @return array           the array if ids
     */
    function get_properties_ids_in_node($nodename)
    {
        $maintree = json_decode($this->template_vars['tree'], true);
        $tree = $this->get_node($nodename, $maintree);
        $ids  = $this->get_properties_ids($tree);
        return $ids;
    }


    function get_node($nodename, $branch)
    {
        $node_found = [];
        foreach($branch as $node)
        {
            if(!empty($node['name']))
                {
                    // echo $node['name'] . '::' .  $nodename . "<br>";
                    if($nodename === $node['name'])
                        {
                            // debug($node['contents'], true);
                            return $node['contents'];
                        }
                    else
                        {
                            if($node_found = $this->get_node($nodename, $node['contents']))
                                return $node_found;
                        }
                }

        }

        return false;
    }



    private function get_currency_selected()
    {

        if(!empty($_COOKIE['BAPI2']))
            return json_decode(urldecode(urldecode($_COOKIE['BAPI2'])), true)['currency'];

        return false;
    }


}
$MAInit = new MarketAreasController();
