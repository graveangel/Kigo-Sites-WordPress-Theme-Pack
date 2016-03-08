<?php

add_action('init', 'register_kd_items');

/**
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function register_kd_items() {
    $singleName = 'Item';
    $pluralName = 'Items';

    $labels = array(
        'name' => _x($pluralName, 'post type general name', 'kd'),
        'singular_name' => _x($singleName, 'post type singular name', 'kd'),
        'menu_name' => _x($pluralName, 'admin menu', 'kd'),
        'name_admin_bar' => _x($singleName, 'add new on admin bar', 'kd'),
        'add_new' => _x('Add New', strtolower($singleName), 'kd'),
        'add_new_item' => __('Add New ' . $singleName, 'kd'),
        'new_item' => __('New ' . $singleName, 'kd'),
        'edit_item' => __('Edit ' . $singleName, 'kd'),
        'view_item' => __('View ' . $singleName, 'kd'),
        'all_items' => __('All ' . $pluralName, 'kd'),
        'search_items' => __('Search ' . $pluralName, 'kd'),
        'parent_item_colon' => __('Parent ' . $pluralName . ':', 'kd'),
        'not_found' => __('No ' . strtolower($pluralName) . ' found.', 'kd'),
        'not_found_in_trash' => __('No ' . strtolower($pluralName) . ' found in Trash.', 'kd'),
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
        'supports' => array('title', 'editor', 'thumbnail', 'page-attributes')
    );

    //We register the 'item' custom post type
    register_post_type(strtolower($singleName), $args);

    //We add 'Type' taxonomy to the item cpt

    $labels = array(
        'name'              => _x( 'Type', 'taxonomy general name' ),
        'singular_name'     => _x( 'Type', 'taxonomy singular name' ),
        'search_items'      => __( 'Search item types' ),
        'all_items'         => __( 'All types' ),
        'parent_item'       => __( 'Parent type' ),
        'parent_item_colon' => __( 'Parent type:' ),
        'edit_item'         => __( 'Edit type' ),
        'update_item'       => __( 'Update type' ),
        'add_new_item'      => __( 'Add New type' ),
        'new_item_name'     => __( 'New type name' ),
        'menu_name'         => __( 'Item Types' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'type' ),
    );

    register_taxonomy( 'type', array( 'item' ), $args );
}


/* Add custom fields */

function item_meta() {

    $screens = array( 'item' );

    foreach ( $screens as $screen ) {
        add_meta_box(
            'item_meta_form',
            __( 'Choose icon', 'kd' ),
            'item_meta_form',
            $screen,
            'side'
        );
    }
}
add_action( 'add_meta_boxes', 'item_meta' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function item_meta_form( $post ) {
    wp_nonce_field( 'item_meta_save', 'cpt_item_nonce' );
    $icon = get_post_meta( $post->ID, 'item_icon', true );
    ?>
    <div class="iconPickerWrapper third">
        <input type="text" placeholder="Icon" class="iconPicker" name="item_icon" value="<?php echo $icon ?>" /><i data-holder class="fa <?php echo $icon ?>"></i>
    </div>
<?php
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function item_meta_save( $post_id ) {

    /*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */

    // Check if our nonce is set.
    if ( ! isset( $_POST['cpt_item_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['cpt_item_nonce'], 'item_meta_save' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'item' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    update_post_meta( $post_id, 'item_icon', $_POST['item_icon'] );
}
add_action( 'save_post', 'item_meta_save' );
