<?php

namespace Kigo\Core\Included;

/**
 * Bypass to process search before
 * being catched by the plugin
 */
class SearchBypass {

    private $searchparams_bapi,
            $searchparams_bapi2,
            $udfs;
    
    /**
     * Prepeare the Bypass
     */
    function __construct() {
        # Get search params
        $this->setSearchParams();
        $this->udf_search = json_decode(urldecode($_COOKIE['udf_search']));
        
//        debug($_COOKIE['udf_search'], true);
        if ($_GET['ver'] && preg_match('#combined#', $_SERVER['REQUEST_URI'])) { # This will be rendered inside the bapi.combined.js
            # show udfs
            $udfs = $this->get_the_udfs();

            $this->udfs = $udfs;
            $udfs_search = unserialize($_SESSION['udf_search']);
            $this->udfs_search = $udfs_search;
            if(!count($this->to_array($udfs_search)))
                $this->udfs_search = null;
            
            
//            debug($this->udfs_search, true);
            
            # for each udf I need to get the options

            $udfs_array = $this->to_array($udfs);
            
            $this->udfs = json_encode($this->get_options_for($udfs_array));
            $this->bapi_bypass_js();
        }
        else
        {
            $_SESSION['udf_search'] = serialize($this->udf_search);
//            debug(unserialize($_SESSION['udf_search']), true);
        }
    }
    
    function get_the_udfs()
    {
        $to_filter = [0,3,4]; //Only using checkbox, single select and multiple select as added fields to property search.
        $to_return = [];
        $udfs = get_option('udfs');
        foreach($udfs as $udf)
        {
            if(in_array($udf->type, $to_filter))
            {
                $to_return[] = $udf;
            }
        }
        return $to_return;
    }

    function to_array($obj) {
        return json_decode(json_encode($obj), true);
    }

    function to_obj($array) {
        return json_decode(json_encode($array));
    }

    function get_options_for($udfs) {
        foreach ($udfs as $idx=>$udf) {
            switch($udf['type'])
            {
                case 0:
                    $udfs[$idx]['options'] = ['on','off'];
                break;
            
                case 1:
                    $udfs[$idx]['options'] = false;
                break;
            
                case 2:
                    $udfs[$idx]['options'] = false;
                break;
            }
        }
        $udfs = $this->to_obj($udfs);
        return $udfs;
    }

    function get_meta_values($key = '', $type = 'post', $status = 'publish') {

        global $wpdb;

        if (empty($key))
            return;

        $r = $wpdb->get_col($wpdb->prepare("
        SELECT pm.meta_value FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '%s' 
        AND p.post_status = '%s' 
        AND p.post_type = '%s'
    ", $key, $status, $type));

        return $r;
    }

    function setSearchParams() {
        # Params 1
        $this->searchparams_bapi = $this->getSearchVar($_COOKIE['BAPI2']);
        $this->udfs_search = $this->searchparams_bapi['udfs'];
        // $this->p($this->searchparams_bapi, false);
        # Params 2
        $this->searchparams_bapi2 = $this->getSearchVar($_COOKIE['BAPIS2']);
        $this->udfs_search2 = $this->searchparams_bapi2['udfs'];
        //$this->p($this->searchparams_bapi2);
    }

    function getSearchVar($cookie) {

        $BAPI_SEARCH = $cookie;
        $searchparams_bapi = json_decode(urldecode($BAPI_SEARCH), true);
        //debug($searchparams_bapi, true);
        return $searchparams_bapi;
    }

    function p($var, $exit = true) {
        var_dump($var);
        if ($exit)
            exit;
    }

    function make_script() {
        ob_start();
        include "js-bypass.php";
        $pattern = '/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/';
        $js = preg_replace($pattern, '', ob_get_contents());
        $js = str_replace(array("\r", "\n"), '', $js);
        ob_end_clean();
        return $js;
    }
    
   

    function getIdsCSV() {
        # Make search to get the ids
        $baseurl = json_decode(get_option('bapi_solutiondata'), true)['BaseURL'];
        $BAPI = new \BAPI(get_option('api_key'), $baseurl);
        $options = $this->searchparams_bapi2['searchparams'];
        $udfs = $this->udfs_search;
       
        if ($options) {
            $reply = $BAPI->search('property', $options);
        }

        $ids = $reply['result'];
        $idsarr = [];
        if (!empty($ids))
            $idsarr = $ids;
        
       
        $idsarr_filtered = $idsarr;
        
        if(!empty($udfs))
            $idsarr_filtered = array_unique($this->filter_by_udfs($idsarr, $udfs));
        
        //if(!count($idsarr_filtered)) $idsarr_filtered = $idsarr;

        return implode(',', $idsarr_filtered);
    }
    
    function filter_by_udfs($idsarr, $udfs)
    {
        $ids_array = [];
        
        
        if(!count($udfs))
        {
            $ids_array = $idsarr;
        }
        else
        {
            $search_pages = [];
            $args = 
                    [
                        'post_type' => 'page',
                         $args['meta_query'] = 
                            [
                                'relation' => 'AND',
                            ]
                    ];
            foreach($udfs as $udf)
            {
                 // Get all properties with the given ids that match the user defined field
                if(empty($udf->value)) continue;
                $value = $udf->value;
                if(is_array($value))
                {
                     $value = serialize(serialize($value));
                }
                
                                $args['meta_query'][] = 
                                [
                                    'key' => $udf->udf_slug,
                                    'value' => $value,
                                    'compare' => '=',
                                ];
                       
                 
            }
//               debug($args, true);
                
                 $search_pages = get_posts($args);
//           debug($search_pages, true);
                 
     
            // for each of the pages see if they are in the results
           foreach($search_pages as $search_page)
           {
               $bapikey = (int) str_replace('property:','',get_post_meta($search_page->ID, 'bapikey', true));
               
               if(in_array($bapikey, $idsarr))
               {
                   $ids_array[] = $bapikey;
               }
               
           }
            
        }
        
//       debug($ids_array, true);
        return $ids_array;
    }
    
  

    function bapi_bypass_js() {
        //header('Content-Type: application/javascript;');
        echo $this->make_script();
    }

}

