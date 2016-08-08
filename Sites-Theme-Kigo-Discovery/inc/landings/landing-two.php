<?php

add_action( 'cmb2_init', 'landing_two_metaboxes' );
add_action( 'cmb2_admin_init', 'landing_two_metaboxes' );


function landing_two_metaboxes() {

    //Hide editor & featured image
    $post_id = @$_GET['post'] ? : -1;
    if(get_post_meta($post_id, '_wp_page_template', true) ==  'page-templates/landing-two.php'){
        remove_post_type_support('page', 'editor');
        remove_post_type_support('page', 'thumbnail');
    }

    $prefix = 'landing_two_';
    $theme_url = get_template_directory_uri();

    $fontsOpts = customizer_library_get_font_choices();

    /* Generic */

    $cmb = new_cmb2_box( array(
        'id'            => $prefix.'general_metaboxes',
        'title'         => __( 'General' ),
        'object_types'  => array( 'page'), // Post type
        'context'       => 'side',
        'priority' => 'low',
        'show_on'      => array( 'key' => 'page-template', 'value' => 'page-templates/landing-two.php' ),
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
        'default' => 'Call Us: (000) 000-0000',
    ) );

    $cmb->add_field( array(
        'name'    => __( 'Primary color', 'cmb2' ),
        'desc'    => __( 'Fill color submit button & call to action section.', 'cmb2' ),
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

    $args = array(
        'post_type' => 'page',
        'meta_query' => array(
            array(
                'key' => '_wp_page_template',
                'value' => 'confirmation', //TODO: Find a better solution for this?
                'compare' => 'LIKE'
            )
        )
    );

    $confirmationPages = get_posts( $args ); //We collect all pages using a Confirmation Page template
    $selectOptions = [];
    foreach($confirmationPages as $page){
        $selectOptions[$page->ID] = get_the_title($page->ID);
    }

    $cmb->add_field( array(
        'name'             => 'Redirect page',
        'desc'             => 'Choose a page to redirect users to on form submission.',
        'id'      => $prefix . 'redirect_page',
        'type'             => 'select',
        'show_option_none' => true,
        'default'          => 'custom',
        'options'          => $selectOptions,
    ) );

    /* Image / form  */

    $cmb = new_cmb2_box( array(
        'id'            => $prefix.'hero_metaboxes',
        'title'         => __( 'Hero Block' ),
        'object_types'  => array( 'page'), // Post type
        'context'       => 'normal',
        'show_on'      => array( 'key' => 'page-template', 'value' => 'page-templates/landing-two.php' ),
    ) );

    $cmb->add_field( array(
        'name' => __( 'Hero image', 'cmb2' ),
        'desc' => __( 'This image appears as the form block background.' ),
        'id'   => $prefix . 'heroimg',
        'type' => 'file',
        'default' => $theme_url.'/kd-common/img/landings/hero-two.png',
    ) );

    $cmb->add_field( array(
        'name'    => __( 'Hero content', 'cmb2' ),
        'desc'    => __( 'Displays content over the form.', 'cmb2' ),
        'id'      => $prefix . 'hero_content',
        'type'    => 'wysiwyg',
        'options' => array(
            'wpautop' => true,
            'textarea_rows' => 4,
        ),
        'default' => "<h1>[Client Name] Property Management</h1>
We offer a wide range of property management services to ensure the property's owner's experience is as satisfying and profitable as possible.
<ul>
 	<li>Rent Out Your Property Fast</li>
 	<li>No Hidden Fees</li>
 	<li>Get a Free Quote!</li>
</ul>"
    ) );

    $cmb->add_field( array(
        'id' => $prefix.'form_title',
        'name' => 'Form title',
        'type' => 'text',
        'default' => 'Contact us to request a FREE Consultation:'
    ) );

    /* Content block  */

    $cmb = new_cmb2_box( array(
        'id'            => $prefix.'content_metaboxes',
        'title'         => __( 'Content block' ),
        'object_types'  => array( 'page'), // Post type
        'context'       => 'normal',
        'show_on'      => array( 'key' => 'page-template', 'value' => 'page-templates/landing-two.php' ),
    ) );

    $cmb->add_field( array(
        'name'    => __( 'Content', 'cmb2' ),
        'id'      => $prefix . 'content',
        'type'    => 'wysiwyg',
        'options' => array(
            'textarea_rows' => 4,
        ),
        'default' =>   "<h1>800+ Residential Real Estate Properties Managed</h1>
                        <h1>150+ Commercial Properties Under Management</h1>
                        <h1>Over 20 Years of Property Management Experience</h1>
                        <h1>50+ Combined Years of Rental Property Management</h1>",
    ) );

    //Image / text chess block

    $cmb = new_cmb2_box( array(
        'id'            => $prefix.'chess_metaboxes',
        'title'         => __( 'Image / Text block' ),
        'object_types'  => array( 'page'), // Post type
        'context'       => 'normal',
        'show_on'      => array( 'key' => 'page-template', 'value' => 'page-templates/landing-two.php' ),
    ) );

    $cmb->add_field( array(
        'id' => $prefix.'chess_title',
        'name' => 'Image / Text block',
        'type' => 'title',
    ) );

    $cmb->add_field( array(
        'name' => 'Image',
        'id'   => $prefix . 'chess_image',
        'type' => 'file',
        'default' => $theme_url.'/kd-common/img/landings/couple-property.png',
    ) );

    $cmb->add_field( array(
        'name'    => __( 'Text', 'cmb2' ),
        'id'      => $prefix . 'chess_text',
        'type'    => 'wysiwyg',
        'options' => array(
            'textarea_rows' => 4,
        ),
        'default' => "<h3>'Best-in-Class' Property Management</h3><p>Work with the best provider of [client's area] property management. Lorem ipsum dolor sit amet, consectur adipiscing elit sed do eiusmod tempor incididunt ut labopre et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi nisi ut aliquip ex ea commodo consequat. Duis autre irure dolor in reprehenderit in voluptate valit esse cillium dolore eu fugiat nulla pariatur.</p>"
    ) );

    /* Call to action  */

    $cmb = new_cmb2_box( array(
        'id'            => $prefix.'banner_metaboxes',
        'title'         => __( 'Banner block' ),
        'object_types'  => array( 'page'), // Post type
        'context'       => 'normal',
        'show_on'      => array( 'key' => 'page-template', 'value' => 'page-templates/landing-two.php' ),
    ) );

    $cmb->add_field( array(
        'id' => $prefix.'cta_title',
        'name' => 'Call To Action',
        'type' => 'title',
    ) );

    $cmb->add_field( array(
        'name' => __( 'Title', 'cmb2' ),
        'id'   => $prefix . 'cta_title',
        'type' => 'text',
        'default' => 'Request a Free Consultation',
    ) );

    $cmb->add_field( array(
        'name' => __( 'Subtitle', 'cmb2' ),
        'id'   => $prefix . 'cta_subtitle',
        'type' => 'text',
        'default' => 'Please make it soon. We are filling fast.',
    ) );

    $cmb->add_field( array(
        'name' => __( 'Button text', 'cmb2' ),
        'id'   => $prefix . 'cta_button_text',
        'type' => 'text',
        'default' => 'Contact Us Today',
    ) );

    $cmb->add_field( array(
        'name' => __( 'Button URL', 'cmb2' ),
        'id'   => $prefix . 'cta_button_url',
        'type' => 'text',
        'default' => '#',
    ) );

}