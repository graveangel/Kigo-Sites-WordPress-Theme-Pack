<?php

add_action('init', 'register_kd_team');

/**
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function register_kd_team() {
    $singleName = 'Team';
    $pluralName = 'Team';

    $labels = array(
        'name' => _x($pluralName, 'post type general name', 'kd'),
        'singular_name' => _x($singleName, 'post type singular name', 'kd'),
        'menu_name' => _x($pluralName, 'admin menu', 'kd'),
        'name_admin_bar' => _x($singleName, 'add new on admin bar', 'kd'),
        'add_new' => _x('Add New', strtolower($singleName). 'Member', 'kd'),
        'add_new_item' => __('Add New ' . $singleName. ' Member', 'kd'),
        'new_item' => __('New ' . $singleName. ' Member', 'kd'),
        'edit_item' => __('Edit ' . $singleName. ' Member', 'kd'),
        'view_item' => __('View ' . $singleName. ' Member', 'kd'),
        'all_items' => __('All ' . $pluralName. ' Members', 'kd'),
        'search_items' => __('Search ' . $pluralName, 'kd'),
        'parent_item_colon' => __('Parent ' . $pluralName . ':', 'kd'),
        'not_found' => __('No ' . strtolower($pluralName) . ' Member found.', 'kd'),
        'not_found_in_trash' => __('No ' . strtolower($pluralName) . ' Member found in Trash.', 'kd')
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => strtolower($singleName)),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'menu_icon' => 'dashicons-groups',
        'supports' => array('title', 'editor', 'thumbnail', 'page-attributes')
    );

    register_post_type(strtolower($singleName), $args);
}

/* Add custom fields */

function team_meta() {

    $screens = array( 'team' );

    foreach ( $screens as $screen ) {
        add_meta_box(
            'team_meta_form',
            __( 'Additional information', 'kd' ),
            'team_meta_form',
            $screen,
            'side'
        );
    }
}
add_action( 'add_meta_boxes', 'team_meta' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function team_meta_form( $post ) {

    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'team_meta_save', 'cpt_team_nonce' );

    /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
//    $fields['name'] = get_post_meta( $post->ID, '_name', true );
    $fields['position'] = get_post_meta( $post->ID, '_position', true );
    $fields['email'] = get_post_meta( $post->ID, '_email', true );

    foreach($fields as $name => $value){
        echo '<p>';
        echo '<label for="member_'.$name.'">'.$name.'</label>';
        echo '<input id="member_'.$name.'" type="text" name="'.$name.'" value="'.$value.'" />';
        echo '</p>';
    }
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function team_meta_save( $post_id ) {

    /*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */

    // Check if our nonce is set.
    if ( ! isset( $_POST['cpt_team_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['cpt_team_nonce'], 'team_meta_save' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'team' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    update_post_meta( $post_id, '_position', $_POST['position'] );
    update_post_meta( $post_id, '_email', $_POST['email'] );
}
add_action( 'save_post', 'team_meta_save' );


/* Rename Team "Featured image" text */

add_action('do_meta_boxes', 'change_image_box');
function change_image_box()
{
    remove_meta_box( 'postimagediv', 'custom_post_type', 'side' );
    add_meta_box('postimagediv', __('Photo', 'kd'), 'post_thumbnail_meta_box', 'team', 'side', 'default');
}