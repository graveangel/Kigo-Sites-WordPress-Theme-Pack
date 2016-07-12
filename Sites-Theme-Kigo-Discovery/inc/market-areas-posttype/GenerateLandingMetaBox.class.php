<?php

namespace Discovery\MarketAreas;

class GemerateLandingMetaBox extends MetaBox{

    /**
     * This function saves, updates, deletes the meta value linked to this meta box.
     * @param integer $post_id
     * @param object $post
     * @return int
     */
    function saveMetaBox($post_id, $post) {

        /* Verify the nonce before proceeding. */
        if (!isset($_POST[$this->id . '_nonce']) || !wp_verify_nonce($_POST[$this->id . '_nonce'], basename($this->templates_path . DIRECTORY_SEPARATOR . $this->template)))
            return $post_id;

        /* Get the post type object. */
        $post_type = get_post_type_object($post->post_type);

        /* Check if the current user has permission to edit the post. */
        if (!current_user_can($post_type->cap->edit_post, $post_id))
            return $post_id;

        /* Get the posted data and sanitize it for use as an HTML class. */
        if ($this->need_sanitize)
            $new_meta_value = ( isset($_POST[$this->id]) ? sanitize_html_class($_POST[$this->id]) : '' );
        else
            $new_meta_value = ( isset($_POST[$this->id]) ? $_POST[$this->id] : '' );

        /* If the value is a tring then check if it is secure */
        if(is_string($new_meta_value) && !$this->is_secure($new_meta_value))
            return $post_id;

        /* Get the meta key. */
        $meta_key = $this->id;

        /* Get the meta value of the custom field key. */
        $meta_value = get_post_meta($post_id, $meta_key, true);


        if(!empty($new_meta_value))
        {
            $this->generate_landings($post_id, $post);
        }
    }

    /**
     * Generates landing pages out of the tree
     * @return void This function does not return anything
     */
    private function generate_landings($post_id, $post)
    {
        $meta_key   = 'market_area_props_n_areas';
        $json_tree  = get_post_meta($post_id, $meta_key, true); //Field value
        $tree       = json_decode($json_tree, true);

        if(empty($tree))
            return;

        foreach($tree as $branch)
        {
            if(array_key_exists('contents',$branch))
            {
                $this->create_landing_for($branch,$meta_key,$post_id);
            }
        }
    }

    private function create_landing_for($branch, $meta_key,$post_id)
    {
        /*
            The information available is this:
            Title: The name of the branch
            tree: The contents of the branch
            slug: the sanitized title
        */

       $title           = wp_strip_all_tags( $branch['name'] );
       $fallback_title  = wp_strip_all_tags( $branch['originalName'] );
       $content  = '';
       $images   = '';
       $template = '';



       $post_array =
       [
              'post_title'    => $title,
              'post_content'  => $content,
              'post_name'     => sanitize_title( $title, $fallback_title ),
              'post_status'   => 'draft', // Created as a draft because the information in it needs to be completed
              'post_type'     => 'market-areas',
              'post_parent'   => $post_id,
       ];


       //Try to get the parameters for this landing
       $alter_params = $this->get_subarea_params($title);

       $total = count($alter_params);
       $filled = 0;


       if(!empty($alter_params['name']))
       {
           $filled++;
           $post_array['post_title'] = $alter_params['name'];
       }
       if(!empty($alter_params['content']))
       {
          $filled++;
          $post_array['post_content'] = $alter_params['content'];
       }

       if(!empty($alter_params['images']))
       {
          $filled++;
       }
       if(!empty($alter_params['template']))
       {
          $filled++;
       }

        if($filled === $total)
               //publish
               $status = 'publish';
        else
            $status = 'draft';

        $post_array['post_status'] = $status;


        if(post_exists($post_array['post_title']))// Need to check if a landing with the same name already exists.
            return;

            try {
                $new_post_id = wp_insert_post($post_array, true); //this saves all meta boxes and will take the $_POST information. Need a control to prevent it
                 // while the parent posts remain as drafts their children will not show a parent assigned until their parents are published.
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage(), 1);
            }

        //Now, the tree under this branch is inside the contents

        $new_tree = json_encode($branch['contents']);

        //Save tree
        if ( ! add_post_meta($new_post_id, $meta_key, $new_tree, true) )
        {
           update_post_meta( $new_post_id, $meta_key, $new_tree );
        }




        $metas = get_post_meta($post_id); // Need to clear child meta boxes
        /**
         * @todo add an option to choose if inherit or not
         * for now I'll leave it with the $inherit variable set to false.
         * @var $inherit bool Tells if the new post is to inherit the parent's meta boxes values.
         */
        $inherit = false;
        if(!$inherit)
            foreach($metas as $key => $meta)
            {
                if($key === $meta_key)
                    continue;
                if(preg_match('/market_area/',$key))
                {
                    update_post_meta( $new_post_id, $key, '' );
                }
            }

        // debug($new_post_id);
        // debug($meta_key);
        // debug($new_tree, true);



        //save images :: saved
        if(!empty($alter_params['images']))
        {
            $images_string = html_entity_decode($alter_params['images']);
            $attachment_urls = json_decode($images_string, true);

            if(is_array($attachment_urls) && count($attachment_urls))
            {

                if ( !add_post_meta($new_post_id, 'market_area_photos', $images_string, true) )
                {
                   update_post_meta( $new_post_id, 'market_area_photos', $images_string );
                }

                //Add the featured image: the first one of the list:
                $attach_id = $this->get_attachment_id_from_url($attachment_urls[0]);
                add_post_meta($new_post_id, '_thumbnail_id', $attach_id);
            }

        }


        //save template

        if(!empty($alter_params['template']))
        {
            if ( !add_post_meta($new_post_id, 'market_area_use_landing_page', $alter_params['template'], true) )
            {
               update_post_meta( $new_post_id, 'market_area_use_landing_page', $alter_params['template'] );
            }

        }

        //If the branch contains an element with a 'contents' key then I need to create the post for it
        foreach($branch['contents'] as $tree_branch)
        {

            if(array_key_exists('contents',$tree_branch))
            {
                $this->create_landing_for($tree_branch,$meta_key,$new_post_id);
            }
        }

    }

    function get_subarea_params($name)
    {
        //The post parameter
        $subareas_settings = json_decode(filter_input( INPUT_POST, 'subareas-conf'), true);
        $subarea = $subareas_settings[html_entity_decode($name)];


        //title
        $subarea['name'] = apply_filters('the_title', $subarea['name']);
        //templateº
        $subarea['template'] = filter_var($subarea['template'] , FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        $subarea['template'] = '{\\"landing\\": \\"yes",\\"template\\":\\"' . $subarea['template'] . '\\" }';
        //images
        $subarea['images'] = filter_var($subarea['images'] , FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH);
        //the content
        $subarea['content'] = apply_filters('the_content',$subarea['content']);

        return $subarea;
    }

    function get_attachment_id_from_url($image_url) {
    	global $wpdb;
    	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
            return $attachment[0];
    }

}

/**
 * USAGE EXAMPLE
 *
 * $args_array = array(
        array(
            'id' => 'simple_text_uno',
            'title' => '<h1>Link URL</h1>',
            'screen' => 'page',
            'context' => 'side',
            'priority' => 'default',
            'template' => 'MetaBoxLinkTemplate.php',
            'need_sanitize' => false,
            'description' => 'Just a simple in put text. ;)'
        ),
    );
 *
 *  foreach ($args_array as $args) {
        $newMeta = new MetaBox($args,dirname(__FILE__) . DIRECTORY_SEPARATOR .'meta_templates');
    }
 */
