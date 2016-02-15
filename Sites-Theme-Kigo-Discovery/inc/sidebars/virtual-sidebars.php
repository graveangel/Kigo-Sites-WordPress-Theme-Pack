<?php

/* ============================================================================
  START VIRTUAL SIDEBARS DEPENDENCIES AND SCRIPTS
  ============================================================================= */
/* * ............................................................................
 * hiphenize
 */

function hiphenize($str) {
    if (is_null($str))
        return;
    return str_replace('-','_',str_replace(' ', '_', strtolower(preg_replace('[^A-Za-z0-9 ]', '', $str))));
}

/* * ............................................................................
 * Enable posts sidebars
 */
add_action('widgets_init', 'enable_posts_sidebars');

function enable_posts_sidebars() {
    //get all posts
    $posts_page = get_posts(array('post_type' => 'page', 'numberposts' => '-1'));

    $posts_post = get_posts(array('post_type' => 'post'));

    $posts = array_merge($posts_page, $posts_post);
    $post_names = array();
    foreach ($posts as $key => $post) {

        $post_meta_sidebar = json_decode(get_post_meta($post->ID, 'post-sidebar', true));

        if (!empty($post_meta_sidebar) && is_object($post_meta_sidebar)) {
            $post_names[] = $post->post_name;
            $used_by = implode(', ', $post_names);
            foreach ($post_meta_sidebar->name as $pms) {
                $post_sidebar_name = hiphenize($pms);

                if (!empty($post_sidebar_name))
                    register_sidebar(array(
                        'name' => $pms,
                        'id' =>   'sidebar_'.$post_sidebar_name,
                        'description' => 'This sidebar is being used in the following pages: ' . $used_by,
                        'class' => 'virtual-sidebar sidebar_',
                        'before_widget' => '<div id="%1$s" class="widget %2$s">',
                        'after_widget' => '</div>',
                        'before_title' => '<h2 class="widget-title">',
                        'after_title' => '</h2>'
                    ));
            }
        }
    }
}

/* * ............................................................................
 * post sidebar field
 */
add_action('admin_menu', 'post_sidebar_meta_box');
add_action('save_post', 'save_post_sidebar_meta_box', 10, 2);
/* * ............................................................................
 * enables the custom field in pages
 * @return [type] [description]
 */

function post_sidebar_meta_box() {
    add_meta_box('post-sidebar-meta-box', 'Virtual Sidebar', 'post_sidebar_meta_box_f', 'page', 'normal', 'high');
    add_meta_box('post-sidebar-meta-box', 'Virtual Sidebar', 'post_sidebar_meta_box_f', 'post', 'normal', 'high');
}

/* * ............................................................................
 * prints the custom field form in admin page page
 * @param  object $object The post
 * @param  object $box    The panel box
 * @return void         the function does not return anything
 */

function post_sidebar_meta_box_f($object, $box) {
    $baseNamefile = plugin_basename(__FILE__);
    include "post_sidebar_meta_box_f.php";
}

/* * ............................................................................
 * Function to save the sidebar name
 * @param  integer $post_id the post id
 * @param  object $post    the post
 * @return void          the function does not return anything
 */

function save_post_sidebar_meta_box($post_id, $post) {
    $baseNamefile = plugin_basename(__FILE__);
    if (empty($_POST['post_meta_box_nonce']) || !wp_verify_nonce($_POST['post_meta_box_nonce'], $baseNamefile)) {
        return $post_id;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    $meta_value = get_post_meta($post_id, 'post-sidebar', true);

    foreach ($_POST['post-sidebar']['name'] as $ind => $ps) {
        $_POST['post-sidebar']['name'][$ind] = stripslashes($ps);
    }

    $new_meta_value = $_POST['post-sidebar'];


    $nv = json_encode($new_meta_value);

    if ($nv && is_null($meta_value))
        add_post_meta($post_id, 'post-sidebar', $nv, true);

    elseif (empty($nv))
        delete_post_meta($post_id, 'post-sidebar', $meta_value);
    else
        update_post_meta($post_id, 'post-sidebar', $nv, $meta_value);
}

/*============================================================================
  END VIRTUAL SIDEBARS DEPENDENCIES AND SCRIPTS
  =============================================================================*/

