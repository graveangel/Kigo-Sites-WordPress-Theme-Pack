<?php


/**
 * Print information abut a php expression, optionally halting execution.
 *
 * @param $e PHP Expression / Variable
 * @param bool $die Indicates if debug stop execution
 */
function debug($e, $die = false) {
    printf('<pre>%s</pre>', var_export($e, true));
    if ($die)
        die();
}

/**
 * Returns the URL for the original featured image file of a post.
 *
 * @param bool $id post ID
 * @return false|string Post featured image if it has any, else FALSE.
 */
function furl($id = false) {
    $postId = $id ? : get_the_ID();
    return wp_get_attachment_url(get_post_thumbnail_id($postId));
}

/**
 * Returns the permalink for a BAPI created page
 *
 * @param $bapiPageId BAPI page id
 * @return false|string Page permalink if exists, otherwise false
 */
function bapiPageUrl($bapiPageId) {
    $resultArr = get_posts(['post_type' => 'page', 'meta_key' => 'bapi_page_id', 'meta_value' => $bapiPageId]);
    $page = array_pop($resultArr);
    return get_the_permalink($page->ID);
}


/**
 * Renders a given Mustache template, with or without extra data
 *
 * @param $string_mustache_template Mustache template name
 * @param array $addedArray Added array of data to be used by template
 * @param bool $onlyAdded Use added array exclusively
 * @return string HTML rendered markup
 */
function render_this($string_mustache_template, $addedArray = [], $onlyAdded = false) {

    if($onlyAdded)
        $data = $addedArray;
    else
        $data = getbapisolutiondata() + $addedArray;

    $m = new Mustache_Engine();
    $string = $m->render($string_mustache_template, $data);
    return $string;
}

//TODO: Finish documenting the rest of functions

function get_mustache_template($templatefile) {
    $filepath = __DIR__  . '/bapi/partials/' . $templatefile;
    if(!file_exists($filepath)) return false;

    $template = file_get_contents($filepath);

    return $template;
}

function get_marked_as_featured() {

    $props_settings = json_decode(stripslashes(get_theme_mod('kd_properties_settings'))) ? : [];

    $featured_pkids = array();
    foreach ($props_settings as $pkid => $ps) {
        if ($ps->forced_featured) {
            $featured_pkids[] = $pkid;
        }
    }

    $sd = json_decode(get_option('bapi_solutiondata'));//safer this way.

    $apikey = $sd->apikey;
    $baseurl = $sd->BaseURL;

    $BAPI = new BAPI($apikey,$baseurl);
    $props = $BAPI->get('property',$featured_pkids);
    $featured = array('kdfeatured'=> $props['result'] ? : []);

    $sd = json_decode(get_option('bapi_keywords_array')) ? : [];
    $sd_properties = array();
    foreach($sd as $ent){
        if($ent->entity === "property"){
            $sd_properties[]=$ent;
        }
    }

    foreach($featured['kdfeatured'] as $ind => $ftrd ){
        foreach($sd_properties as $sd_prop){
            if($ftrd['ID'] == $sd_prop->pkid){
                $featured['kdfeatured'][$ind]['ContextData']['SEO'] = array();
                $featured['kdfeatured'][$ind]['ContextData']['SEO']['DetailURL'] = $sd_prop->DetailURL;
                break;
            }
        }
    }

    return $featured;
}

function doctype_opengraph($output) {
    return $output . '
xmlns:og="http://opengraphprotocol.org/schema/"
xmlns:fb="http://www.facebook.com/2008/fbml"';
}

function fb_opengraph() {
    global $post;
    $meta_words = get_post_custom($post->ID, '', true);
    $bapikey = $meta_words['bapikey'][0];
//we check if this is a property page
    if(strpos($bapikey, 'property') !== false ) {
        $img_src = "";
        $title_string = $meta_words['bapi_meta_title'][0];
//check if there is a featured image
        if(has_post_thumbnail($post->ID)) {
            $img_src = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'medium');
        } else {
//check if there is a custom field containing the primary image URL
            if(!empty($meta_words['primary_image_url'][0]) && !empty($meta_words['headline'][0])){
                $img_src = "http:".$meta_words['primary_image_url'][0];
                $title_string = $meta_words['headline'][0];
            }else{

                $bapikey = str_replace("property:", "", $bapikey);
//doing a call, this should be in the plugin IMHO
                $bapi = getBAPIObj();
                $pkid = array(intval($bapikey));
                if (!$bapi->isvalid()) { return; }
//pulling from connect the data of this property
                $c = $bapi->get("property", $pkid, NULL);

//getting the primary image URL
                if(is_array($c)){
                    $img_src = "http:".$c['result'][0]['PrimaryImage']['OriginalURL'];
//setting the custom field so we dont do calls to connect anymore
                    add_post_meta($post->ID, 'primary_image_url', $c['result'][0]['PrimaryImage']['OriginalURL'],true);
                    $title_string = $c['result'][0]['Headline'];
                    add_post_meta($post->ID, 'headline', $c['result'][0]['Headline'],true);
                }

            }
        }

        if(empty($meta_words['bapi_meta_title'][0])){
            $meta_words['bapi_meta_title'][0] = $post->post_title;
        }
        ?>
        <meta property="og:title" content="<?php echo $title_string; ?>"/>
        <meta property="og:description" content="<?php echo $meta_words['bapi_meta_description'][0]; ?>"/>
        <meta property="og:type" content="website" />
        <meta property="og:url" content="<?php echo the_permalink(); ?>"/>
        <meta property="og:site_name" content="<?php echo get_bloginfo(); ?>"/>
        <meta property="og:image" content="<?php echo $img_src; ?>"/>
    <?php
    } else {
        return;
    }
}
