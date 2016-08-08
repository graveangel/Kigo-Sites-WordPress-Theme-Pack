<?php

add_action( 'cmb2_admin_init', 'confirmation_metaboxes' );

function confirmation_metaboxes() {

    //Hide editor & featured image
    $post_id = @$_GET['post'] ? : -1;
    if(get_post_meta($post_id, '_wp_page_template', true) ==  'page-templates/landing-one.php'){
        remove_post_type_support('page', 'editor');
        remove_post_type_support('page', 'thumbnail');
    }

    $prefix = 'confirmation_';

    $fontsOpts = customizer_library_get_font_choices();


    $cmb = new_cmb2_box( array(
        'id'            => $prefix . 'content',
        'title'         => __( 'Content' ),
        'object_types'  => array( 'page'), // Post type
        'priority'      => 'high',
        'show_names'    => true,
        'show_on'      => array( 'key' => 'page-template', 'value' => 'page-templates/confirmation-one.php' ),
    ) );

    $cmb->add_field( array(
        'name'    => __( 'Hero content', 'cmb2' ),
        'desc'    => __( 'Content to be displayed as main content.', 'cmb2' ),
        'id'      => $prefix . 'hero_content',
        'type'    => 'wysiwyg',
        'options' => array( 'textarea_rows' => 5, ),
        'default' => "<h1>Thank you for contacting us!</h1>
<h2>Someone from our offices will be reaching out to you shortly.</h2>",
    ) );

    /* Generic */

    $cmb = new_cmb2_box( array(
        'id'            => $prefix . 'generic',
        'title'         => __( 'General' ),
        'object_types'  => array( 'page'), // Post type
        'context'       => 'side',
        'priority'      => 'low',
        'show_names'    => true,
        'show_on'      => array( 'key' => 'page-template', 'value' => 'page-templates/confirmation-one.php' ),
    ) );

    $cmb->add_field( array(
        'name' => __( 'Logo', 'cmb2' ),
        'desc' => __( 'Upload an image or enter a URL. Leave blank to default to solution logo.' ),
        'id'   => $prefix . 'logo',
        'type' => 'file',
    ) );

    $cmb->add_field( array(
        'name'       => __( 'Phone number' ),
        'id'         => $prefix . 'phone',
        'type'       => 'text',
        'default'    => 'Call: (000) 000-0000'
    ) );

    $cmb->add_field( array(
        'name'    => __( 'Primary color', 'cmb2' ),
        'id'      => $prefix . 'primary_color',
        'type'    => 'colorpicker',
        'default' => '#e2574c'
    ) );

    $cmb->add_field( array(
        'name'    => __( 'Headings font color', 'cmb2' ),
        'desc'    => __( 'Main headings & titles font color.', 'cmb2' ),
        'id'      => $prefix . 'heading_color',
        'type'    => 'colorpicker',
    ) );

    $cmb->add_field( array(
        'name'    => __( 'Body font color', 'cmb2' ),
        'desc'    => __( 'Body & content font color.', 'cmb2' ),
        'id'      => $prefix . 'body_color',
        'type'    => 'colorpicker',
    ) );

    $cmb->add_field( array(
        'name'    => __( 'Heading font', 'cmb2' ),
        'desc'    => __( 'Font family to use on titles & headings.', 'cmb2' ),
        'id'      => $prefix . 'font_heading',
        'type'    => 'select',
        'options' => $fontsOpts,
        'default' => 'Source Sans Pro',
    ) );

    $cmb->add_field( array(
        'name'    => __( 'Body font', 'cmb2' ),
        'desc'    => __( 'Font family to use on body text.', 'cmb2' ),
        'id'      => $prefix . 'font_body',
        'type'    => 'select',
        'options' => $fontsOpts,
        'default' => 'Source Sans Pro',
    ) );

    /* Items  */

    $cmb = new_cmb2_box( array(
        'id'            => $prefix.'button_metaboxes',
        'title'         => __( 'Buttons' ),
        'object_types'  => array( 'page'), // Post type
        'context'       => 'normal',
        'show_on'      => array( 'key' => 'page-template', 'value' => 'page-templates/confirmation-one.php' ),
    ) );

    $items_group = $cmb->add_field( array(
        'id'          => $prefix . 'buttons',
        'type'        => 'group',
        'description' => __( 'Create, modify, delete & sort buttons.', 'cmb2' ),
        'repeatable'  => true, // use false if you want non-repeatable group
        'options'     => array(
            'group_title'   => __( 'Item {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'    => __( 'Add Another button', 'cmb2' ),
            'remove_button' => __( 'Remove button', 'cmb2' ),
            'sortable'      => true, // beta
            'closed'        => true, // true to have the groups closed by default
        ),
    ) );

    $cmb->add_group_field( $items_group, array(
        'name' => 'Text',
        'id'   => 'button_text',
        'type' => 'text',
        'default' => 'Button',
    ) );

    $cmb->add_group_field( $items_group, array(
        'name' => 'URL',
        'id'   => 'button_url',
        'type' => 'text_url',
    ) );

    //Scripts

    $cmb = new_cmb2_box( array(
        'id'            => $prefix.'scripts_metaboxes',
        'title'         => __( 'Scripts' ),
        'object_types'  => array( 'page'), // Post type
        'context'       => 'normal',
        'show_on'      => array( 'key' => 'page-template', 'value' => 'page-templates/confirmation-one.php' ),
    ) );

    $cmb->add_field(array(
        'name' => htmlentities('<head> Script'),
        'desc' => 'The following content will be output inisde the <head> tag.',
        'default' => '<script></script>',
        'id' => $prefix . 'script_head',
        'type' => 'textarea',
        'sanitization_cb' => false
    ) );
    $cmb->add_field(array(
        'name' => htmlentities('After <body> Script'),
        'desc' => 'The following content will be output after the opening <body> tag.',
        'default' => '<script></script>',
        'id' => $prefix . 'script_body_open',
        'type' => 'textarea',
        'sanitization_cb' => false
    ) );
    $cmb->add_field(array(
        'name' => htmlentities('Before </body> Script'),
        'desc' => 'The following content will be output before the closing </body> tag.',
        'default' => '<script></script>',
        'id' => $prefix . 'script_body_close',
        'type' => 'textarea',
        'sanitization_cb' => false
    ) );

}
