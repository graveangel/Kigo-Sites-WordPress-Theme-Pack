<?php

require_once ABSPATH . 'wp-admin/includes/widgets.php';


$widgets_access = get_user_setting( 'widgets_access' );
if ( isset($_GET['widgets-access']) ) {
    $widgets_access = 'on' == $_GET['widgets-access'] ? 'on' : 'off';
    set_user_setting( 'widgets_access', $widgets_access );
}

if ( 'on' == $widgets_access ) {
    add_filter( 'admin_body_class', 'wp_widgets_access_body_class' );
} else {
    wp_enqueue_script('admin-widgets');

    if ( wp_is_mobile() )
        wp_enqueue_script( 'jquery-touch-punch' );
}

//wp_list_widgets();

wp_list_widget_controls('header-left');

?>

<form method="post">
    <?php wp_nonce_field( 'save-sidebar-widgets', '_wpnonce_widgets', false ); ?>
</form>
