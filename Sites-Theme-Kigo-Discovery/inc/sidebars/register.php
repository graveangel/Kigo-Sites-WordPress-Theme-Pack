<?php

add_action('admin_menu', 'kd_dynamic_sidebars');

function kd_dynamic_sidebars() {

    // register custom page under theme options
//    add_theme_page(
//        'Sidebars',
//        'Sidebars',
//        'edit_theme_options',
//        'kd_register_sidebars',
//        'kd_sidebars_page'
//    );


    add_submenu_page('kigo-discovery','Custom Sidebars','Sidebars','manage_options','kd-sidebars','kd_sidebars_page');

    // save the form if submitted
    $nonce = filter_input(INPUT_POST, 'kd_sidebars_nonce', FILTER_SANITIZE_STRING);
    if ( ! empty($nonce) && wp_verify_nonce($nonce, 'kd_dynamic_sidebars') ) {
        $sidebars =  (array) $_POST['custom_sidebars'];
        update_option('kd_dynamic_sidebars', $sidebars);
        add_action('admin_notices', 'my_custom_sidebars_notice');
    }
}

/**
 * Displays administration page
 */
function kd_sidebars_page() {
    if (! current_user_can('edit_theme_options') ) return;
    include_once 'admin-page.php';
}

/**
 * Displays notice on page save
 */
function my_custom_sidebars_notice() {
    echo '<div class="updated"><p>Updated sidebars.</p></div>';
}

/**
 * Register dynamic sidebars on widgets page load
 */
function kd_register_sidebars() {
    $sidebars = get_option('kd_dynamic_sidebars'); // get all the sidebars names
    if ( ! empty($sidebars) ) {
        // add a sidebar for every sidebar name
        foreach ( $sidebars as $sidebar ) {
            if ( empty($sidebar) ) continue;
            register_sidebar(array(
                'name' => $sidebar,
                'id' => sanitize_title($sidebar),
                'before_title' => '',
                'after_title' => '',
                'before_widget' => '',
	            'after_widget'  => '',
            ));
        }
    }
}
add_action('widgets_init', 'kd_register_sidebars');


function my_custom_sidebar_box( $post ) {


    $sidebars = get_option('kd_dynamic_sidebars'); // get all the sidebars names
    if ( empty($sidebars) ) {
        echo 'No custom sidebars registered.';
        return;
    }
    wp_nonce_field( 'my_custom_sidebar', 'my_custom_sidebar_box_nonce' );
    $value = get_post_meta( $post->ID, '_custom_sidebar', true ); // actual value
    echo '<label>Select a Sidebar</label> ';
    echo '<select name="custom_sidebar">';
    // default option
    echo '<option value=""' . selected('', $value, false) . '>Default</option>';
    // an option for every sidebar
    foreach ($sidebars as $sidebar) {
        if ( empty($sidebar) ) continue;
        $v = sanitize_title($sidebar);
        $n = esc_html($sidebar);
        echo '<option value="' . $v . '"' . selected($v, $value, false) .'>' .$n .'</option>';
    }
    echo '<select>';
}

add_action( 'add_meta_boxes', 'kd_sidebar_metabox' );

function kd_sidebar_metabox() {
    $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
    $currentTemplate = get_post_meta($post_id,'_wp_page_template',TRUE);
    $templates = get_page_templates();

    if ($templates['Custom'] == $currentTemplate) {
        add_meta_box('my_custom_sidebar', 'Select a Sidebar','my_custom_sidebar_box', 'page', 'side');
    }
}

add_action( 'save_post', 'my_custom_sidebar_metabox_save' );

function my_custom_sidebar_metabox_save( $post_id ) {



    // If this is an autosave, our form has not been submitted, do nothing.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    // check nonce
    $nonce = filter_input(INPUT_POST, 'my_custom_sidebar_box_nonce', FILTER_SANITIZE_STRING);
    if ( empty($nonce) || ! wp_verify_nonce( $nonce, 'my_custom_sidebar' ) ) return;
    $type = get_post_type($post_id);
    // Check the user's permissions.
    $cap = ( 'page' === $type ) ? 'edit_page' : 'edit_post';
    if ( ! current_user_can( $cap, $post_id ) ) return;
    $custom = filter_input(INPUT_POST, 'custom_sidebar', FILTER_SANITIZE_STRING);
    // Update the meta field in the database.
    if ( empty($custom) ) {
        delete_post_meta( $post_id, '_custom_sidebar');
    } else {
        update_post_meta( $post_id, '_custom_sidebar', $custom );
    }
}