<?php

/* Post meta helper */

function pmeta($key, $single = true, $id = false){
    return get_post_meta($id ? : get_the_ID(), $key, $single);
}

/**
 * Function bound to cmb2_after_init action.
 * Listens for a Landing Page front-end form submission & saves its meta-data accordingly.
 */
function landing_form_submission() {
    // If no landing form submission, bail
    if ( empty( $_POST ) || ! isset( $_POST['landing_submit'], $_POST['object_id'] ) ) {
        return false;
    }
    $post_id = $_POST['object_id']; //Page being saved

    //Check the wp nonce for validity
    $validNonce = wp_verify_nonce( $_POST['_wpnonce'], 'update-landing_'.$post_id );

    //Check current user for super-admin privilege
    $isSuperAdmin = is_super_admin();

    if(!$validNonce || !$isSuperAdmin){ //If either of the above fail, we exit.
        wp_die("There was an error submitting the form.", 'Oops!');
    }

    /* We iterate over each meta-key, save those related to landing page */
    foreach($_POST as $key => $value){
        if(strpos($key, 'landing_') === 0){ //If it's a landing page meta field, we update its value
            update_post_meta($post_id, $key, $value);
        }
    }

    wp_redirect( $_SERVER['HTTP_REFERER'] );
    exit;
}
add_action( 'cmb2_after_init', 'landing_form_submission' );


/**
 * @param $wp_admin_bar wp_admin_bar object
 *
 * Adds a button to the front-end admin bar, when user is Super Admin & in a Landing Page front-end.
 */
function landings_admin_bar($wp_admin_bar){
    if(is_super_admin() && !is_admin() &&
        (is_page_template('page-templates/landing-one.php') || is_page_template('page-templates/landing-two.php'))
    ){
        $args = array(
            'id' => 'save-landing-content',
            'title' => 'Save Content',
            'href' => '',
            'meta' => array(
                'class' => 'custom-button-class'
            )
        );
        $wp_admin_bar->add_node($args);
    }
}
add_action('admin_bar_menu', 'landings_admin_bar', 50);

/**
 * @param $post_id Landing page post_id to build the meta data form.
 *
 * Takes a post id and returns a  form with all it's related landing meta data.
 * Used in the landing page template to enable front-end editing.
 */
function get_meta_form($post_id){

    //Get all meta data for a given post
    $metas = get_post_custom($post_id);

    //We begin outputting the form
    echo '<form method="post" id="landing_form">';
    echo '<input type="hidden" name="landing_submit" value="1" />'; //Define the form as a landing form
    echo '<input type="hidden" name="object_id" value="'.$post_id.'" />'; //Define post id being edited
    wp_nonce_field( 'update-landing_'.$post_id ); //Output wp nonce

    foreach($metas as $key => $value){
        if(strpos($key, 'landing_') === false){ //Filter landing meta data
            continue;
        }

        $value = is_array($value) ? array_pop($value) : $value;
        $unserialized = unserialize($value); //Check for group-fields

        if(!empty($unserialized) && is_array($unserialized)) { //If it's a group-field
            //We iterate over each group item
            foreach ($unserialized as $i => $item){
                foreach ($item as $k => $v) {
                    printf('<input type="hidden" name="%s[%d][%s]" value="%s" />', $key, $i, $k, esc_html($v));
                }
            }
        }else{ //If it's a normal field (non-group)
            printf('<input type="text" name="%s" value="%s" />', $key, esc_html($value));
        }
    }

    echo '</form>';
}

