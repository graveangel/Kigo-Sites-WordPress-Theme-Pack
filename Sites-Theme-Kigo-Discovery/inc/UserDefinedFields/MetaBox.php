<?php

namespace UserDefinedFields;

class MetaBox {

    /**
     *
     * @var String
     */
    protected $templates_path;

    /**
     * Setting up everything needed to create the meta box
     * @param array $args the parameters needed to build the meta field
     * @param string $templatesPath Is the path to the meta boxes templates dir
     * @throws Exception If any of the required field is not set an exception is thrown.
     */
    function __construct($args, $templatesPath='.') {

        //Current dir:
        $this->templates_path = $templatesPath;

        $allVars = array('id', 'title', 'screen', 'context', 'priority', 'callback_args', 'template');
        $required = array('id', 'title', 'screen', 'context', 'priority', 'template');

        foreach ($required as $requiredfield) {
            if (!array_key_exists($requiredfield, $args)) {
                throw new Exception('BXMetaBox required field missing: ' . $requiredfield);
            }
        }

        foreach ($allVars as $var) {
            if (!array_key_exists($var, $args)) {
                $args[$var] = null;
            }
        }

        $this->id = $args['id'];

        $this->title         = $args['title'];
        $this->screen        = $args['screen'];
        $this->context       = $args['context'];
        $this->priority      = $args['priority'];
        $this->template      = $args['template'];
        $this->description   = $args['description'];
        $this->options       = $args['options'] ?: false;
        $this->callback_args = $args['callback_args'];
        $this->with_template = $args['with_template'] ?:null;
        $this->need_sanitize = empty($args['need_sanitize']) ? false : $args['need_sanitize'];



        add_action('load-post.php', array($this, 'setup'));
        add_action('load-post-new.php', array($this, 'setup'));

        //Save field
        add_action('save_post', array($this, 'saveMetaBox'), 10, 2);
    }

    /**
     * This function initializes the meta box
     * @return void this function does not return any value
     */
    function setup() {
        //Create the meta boxes
        add_action('add_meta_boxes', array($this, 'addMetaBox'));
    }

    /**
     * This function creates the metabox
     * @return void this function does not return anything
     */
    function addMetaBox() {
        
        global $post;
  
        if($this->with_template)
        {
            if ( $this->with_template === get_post_meta( $post->ID, '_wp_page_template', true ) ) {
                add_meta_box($this->id, $this->title, array($this, 'buildField'), $this->screen, $this->context, $this->priority, $this->callback_args);
            }
        }
        else
        {
            add_meta_box($this->id, $this->title, array($this, 'buildField'), $this->screen, $this->context, $this->priority, $this->callback_args);
        }
    }

    /**
     * This function inserts the template for the new field form
     * @param object $object
     * @param object $box
     */
    function buildField($object, $box) {
        include $this->templates_path . DIRECTORY_SEPARATOR . $this->template;
    }

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
        
        if(is_array($new_meta_value))
        {
            $new_meta_value = serialize($new_meta_value);
            //debug($new_meta_value, true);
        }

        /* Get the meta key. */
        $meta_key = $this->id;

        /* Get the meta value of the custom field key. */
        $meta_value = get_post_meta($post_id, $meta_key, true);

        /* If a new meta value was added and there was no previous value, add it. */
        if ($new_meta_value && '' == $meta_value)
            add_post_meta($post_id, $meta_key, $new_meta_value, true);

        /* If the new meta value does not match the old value, update it. */
        elseif ($new_meta_value && $new_meta_value != $meta_value)
            update_post_meta($post_id, $meta_key, $new_meta_value);

        /* If there is no new meta value but an old value exists, delete it. */
        elseif ('' == $new_meta_value && $meta_value)
            delete_post_meta($post_id, $meta_key, $meta_value);
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
