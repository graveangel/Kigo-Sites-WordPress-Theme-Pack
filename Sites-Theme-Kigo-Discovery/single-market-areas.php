<?php
/**
 * Market Areas Controller
 */
if(!defined(DS))
    define('DS', DIRECTORY_SEPARATOR);

class MarketAreasController
{
    private $template_path;
    private $template_vars;
    private $currency_from;
    private $currency_to;


    public function __construct()
    {

        $this->id = get_the_ID(); //The post id

        $this->set_template(); //Select the landing template

        $this->set_vars_and_metas();

        //$this->currency_from = $this->get_currency(); // The currency of the properties
        $this->currency_to = $this->get_currency_selected(); // The currency selected from the bapicurrency selector

        //debug($this->currency_from, true);

        $this->index_action();


    }

    /**
     * prepares the elements to render
     * @return void this function does not return a value
     */
    public function index_action()
    {
        $this->render($this->template_vars);
    }

    /**
     * adds the template varibles to the array with
     * the metas and post contents.
     */
    private function set_vars_and_metas()
    {
        $metas = get_post_meta($this->id);

        $post = get_post($this->id);

        //title
        $this->template_vars['title'] = apply_filters('the_title', $post->post_title);
        //excerpt
        $this->template_vars['excerpt'] = apply_filters('the_excerpt',$post->post_excerpt);

        //featured image:
        $this->template_vars['featured_image'] = wp_get_attachment_url( get_post_thumbnail_id($this->ID) );

        //pics
        $this->template_vars['pics'] = json_decode($metas['market_area_photos'][0], true);

        //description
        $this->template_vars['description'] = array_key_exists('market_area_description',$metas) ? apply_filters('the_content', $metas['market_area_description'][0]) : '';
        //json_use_landing
        $this->template_vars['json_use_landing'] = array_key_exists('market_area_use_landing_page',$metas) ? $metas['market_area_use_landing_page'][0] : '[]';
        //json_tree
        $this->template_vars['tree'] = array_key_exists('market_area_props_n_areas',$metas) ? $metas['market_area_props_n_areas'][0] : '[]';
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
                    $locs = array_merge($locs,$this->get_locations($contents));
                }
            }

        return $locs;
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



    private function get_currency_selected()
    {
        // Yahoo finance currency quotes WS
        $contents = file_get_contents('http://download.finance.yahoo.com/d/quotes.csv?s=EURUSD=X&f=sl1d1t1ba&e=.csv');
        //debug($contents, true);

        if(!empty($_COOKIE['BAPI2']))
            return json_decode(urldecode(urldecode($_COOKIE['BAPI2'])), true)['currency'];

        return false;
    }

    private function get_currency()
    {
        try {
            $tree = $this->template_vars['tree'];
            array_walk_recursive();
            throw new \Exception('EUR');
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }
}
$MAInit = new MarketAreasController();
