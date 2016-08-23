<?php

/**
 * Template Name: Market Areas Main Landing
 */

namespace MarketAreasPage;

class MarketAreasPageController {

    private $temmplate_path,
            $template_vars = [];

    /**
     * Sets the template path and executes the index method
     */
    function __construct() {
        # Set the template
        $this->template_path = 'market-areas-main-landing.php';

        # Index action
        $this->index();
    }
    
    /**
     * Sets the template variables and renders the template.
     * @global type $post
     * @return void
     */
    function index() {
        # Set the vars
        global $post;
        $this->set('post', $post);

        # Title
        $this->set('title', apply_filters('the_title', $post->post_title));

        # Content
        $this->set('content', apply_filters('the_content', $post->post_content));

        # Properties Objs
        $this->set('properties_objs', $this->get_properties_objs());

        # Market areas Objs
        $this->set('all_market_areas', $this->get_all_market_area_objs());

        # Market Areas
        $this->set('market_areas', $this->get_market_areas());

        $market_ateas = $this->get('market_areas');

        # Render
        $this->render();
    }

    /**
     * Returns the translation string of in the textdata of bapi.
     * If textdata is false then it will try to render the mustache template passed.
     * @param string $term
     * @return string
     */
    function r($term, $textdata = true) {
        if ($textdata)
            $r = \render_this("{{#site}}{{textdata.$term}}{{/site}}");
        else
            $r = \render_this($term);

        if (empty($r))
            return $term;
        return $r;
    }

    /**
     * Returns a 1 or 2 level array of nested market areas.
     * Returning two levels when no parameters are passed or if it is equal to zero.
     * @param int $parentId the parent id, default 0
     * @return array
     */
    function get_market_areas($parentId = 0) {
        $args = [
            'post_type' => 'page',
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-templates/market-area.php',
            'post_parent' => $parentId,
            'posts_per_page' => -1,
        ];

        $market_areas = get_posts($args);

        if (!$parentId) { // If no parent ID provided or equal to Zero
            foreach ($market_areas as $idx => $market_area) {

                $pid = $market_area->ID; // Int. The ID of the market area page
                $children = $this->get_market_areas($pid);

                $market_areas[$idx]->children = $children;
            }
        }

        $containers = ['Neighborhood', 'County', 'City', 'Region', 'State', 'Country']; // in order of size

        foreach ($market_areas as $indx => $market_area) {

            $bapi_property_data = json_decode(get_post_meta($market_area->ID, 'bapi_property_data', true));


            foreach ($containers as $container) {
                if ($bapi_property_data->{$container} != '') {
                    $ltype = next($containers);
                    if (empty($ltype)) {
                        $location = '';
                        break;
                    }
                    $location = $bapi_property_data->{$ltype};
                    $market_areas[$indx]->location = $location;
                    break;
                }
            }

            $props = $this->get_props_in($market_area->ID);
            
            $market_areas[$indx]->props  = $props;
        }

        return $market_areas;
    }

    function get_all_market_area_objs() {
        $market_areas = $this->get_market_areas();
        $market_areas_objs = [];
        foreach ($market_areas as $ind => $matpg) {
            $pdata = json_decode(get_post_meta($matpg->ID, 'bapi_property_data', true));

            //  debug($matpg, true);
            $market_areas_objs[$ind]                = new \StdClass();
            $market_areas_objs[$ind]->ID            = $pdata->ID;
            $market_areas_objs[$ind]->PageID        = $matpg->ID;
            $market_areas_objs[$ind]->guid          = $matpg->guid;
            $market_areas_objs[$ind]->name          = $pdata->Name;
            $market_areas_objs[$ind]->lat           = $pdata->Latitude;
            $market_areas_objs[$ind]->lng           = $pdata->Longitude;
            $market_areas_objs[$ind]->props_inside  = $this->get_props_in($matpg->ID);
            $market_areas_objs[$ind]->thumbnail     = $pdata->PrimaryImage->ThumbnailURL;
            $market_areas_objs[$ind]->icon_class    = $this->get_icon_class_for($market_areas_objs[$ind]->props_inside);
            $market_areas_objs[$ind]->icon_url      = $this->get_icon_class_for($market_areas_objs[$ind]->props_inside, true);
            
//            if(preg_match("#brooklyn#i",$market_areas_objs[$ind]->name))
//            {
//                echo "<pre>";
//                var_dump($market_areas_objs[$ind]);
//                die;
//            }   
        }

        $market_areas_objs_json = json_encode($market_areas_objs);
        // debug($market_areas_objs_json, true);
        return $market_areas_objs_json;
    }

    function get_icon_class_for($props_num, $giveUrl=false) {
        $size = [10, 30, 50, 70, 90];
        $class = 'marker-icon-%d';
        if($giveUrl)
            $class = '/markers/m%d.png';
        $icon_class = sprintf($class, 1);
        $prev = 2;
        foreach ($size as $idx => $sz) {
            $icon_class = sprintf($class, ($idx+1));
            if ($sz >= $props_num)
                break;
            
            $prev = $size[$idx];
        }
        $prefix = '';
        if($giveUrl)
            $prefix = get_template_directory_uri() .'/insta-common/img';
        return $prefix . $icon_class;
    }
    
    

    /**
     * Returns the number of property detail pages that are children of the given page ID
     * If $count is set to false it returns the array of property pages.
     * @param int $pageId
     * @return mixed ( int | array )
     */
    function get_props_in($pageId, $count=true) {
        $args = [
                        'posts_per_page'    => -1,
			'hierarchical'      => 1,
                        'parent'            => -1,
			'offset'            => 0,
			'post_type'         => 'page',
			'post_status'       => 'publish',
                        'child_of'          => $pageId
        ];
        /**
         * @note I've tried with meta_key meta_value but when adding those it does not seem to search recursively
         */
        $pages          = get_pages($args);
        $ppt_page = [];
        foreach($pages as $page)
        {
            $template = get_post_meta($page->ID, '_wp_page_template', true);
            if($template === 'page-templates/property-detail.php')
                $ppt_page[] = $page;
        }
        $count_pages    = count($ppt_page);
        
        $return = $count ? $count_pages : $ppt_page;
        
        return $return;
    }

    function get_properties_objs() {
        $props_objs = [];

        # Get all property pages
        $args = [
            'post_type' => 'page',
            'meta_key' => '_wp_page_template',
            'posts_per_page' => -1,
            'meta_value' => 'page-templates/property-detail.php'
        ];
        $propety_pages = get_posts($args);

        foreach ($propety_pages as $ind => $pptpg) {
            $prop_data = json_decode(get_post_meta($pptpg->ID, 'bapi_property_data', true));

            // debug($prop_data, true);
            $props_objs[$ind]               = new \StdClass();
            $props_objs[$ind]->ID           = $prop_data->ID;
            $props_objs[$ind]->guid         = $pptpg->guid;
            $props_objs[$ind]->lat          = $prop_data->Latitude;
            $props_objs[$ind]->lng          = $prop_data->Longitude;
            $props_objs[$ind]->title        = $prop_data->Headline;
            $props_objs[$ind]->summary      = $prop_data->Summary;
            $props_objs[$ind]->thumbnail    = $prop_data->PrimaryImage->ThumbnailURL;
            $props_objs[$ind]->location     = $prop_data->Location;
        }

        $props_objs_json = json_encode($props_objs);
        return $props_objs_json;
    }

    function set($varname, $varval) {
        $this->template_vars[$varname] = $varval;
    }

    function get($varname) {
        return $this->template_vars[$varname];
    }

    function render() {
        extract($this->template_vars);
        include $this->template_path;
    }

}

new MarketAreasPageController();
