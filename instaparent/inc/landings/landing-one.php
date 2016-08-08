<?php

add_action( 'cmb2_init', 'landing_one_metaboxes' );
add_action( 'cmb2_admin_init', 'landing_one_metaboxes' );

function landing_one_metaboxes() {

    //Hide editor & featured image
    $post_id = @$_GET['post'] ? : -1;
    if(get_post_meta($post_id, '_wp_page_template', true) ==  'page-templates/landing-one.php'){
        remove_post_type_support('page', 'editor');
        remove_post_type_support('page', 'thumbnail');
    }

    $prefix = 'landing_one_';
    $theme_url = get_template_directory_uri();

    $fontsOpts = customizer_library_get_font_choices();

    /* Generic */

    $cmb = new_cmb2_box( array(
        'id'            => $prefix.'general_metaboxes',
        'title'         => __( 'General' ),
        'object_types'  => array( 'page'), // Post type
        'context'       => 'side',
        'priority' => 'low',
        'show_on'      => array( 'key' => 'page-template', 'value' => 'page-templates/landing-one.php' ),
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

    /* Hero block  */

    $cmb = new_cmb2_box( array(
        'id'            => $prefix.'hb_metaboxes',
        'title'         => __( 'Hero block' ),
        'object_types'  => array( 'page'), // Post type
        'context'       => 'normal',
        'show_on'      => array( 'key' => 'page-template', 'value' => 'page-templates/landing-one.php' ),
    ) );

    $cmb->add_field( array(
        'id'   => $prefix . 'disabled_hero',
        'name' => 'Disable',
        'desc' => 'Disable the Hero block',
        'type' => 'checkbox',
        'default' => isset( $_GET['post'] ) ? '' : 'on',
    ) );

    $cmb->add_field( array(
        'name' => __( 'Hero image', 'cmb2' ),
        'desc' => __( 'Choose the hero block background image.' ),
        'id'   => $prefix . 'heroimg',
        'type' => 'file',
        'default' => $theme_url.'/images/landings/hero-one.png',
    ) );

    $cmb->add_field( array(
        'name'    => __( 'Form content', 'cmb2' ),
        'desc'    => __( 'Content to be displayed on top of the Inquire Form.', 'cmb2' ),
        'id'      => $prefix . 'form_content',
        'type'    => 'wysiwyg',
        'options' => array( 'textarea_rows' => 5, ),
        'default' => "<h1>[Client Name] Property Management</h1><p>We offer a wide range of property management services to ensure the property owner's experience is as satisfying and profitable as possible.</p>",
    ) );

    /* Items  */

    $cmb = new_cmb2_box( array(
        'id'            => $prefix.'item_metaboxes',
        'title'         => __( 'Items block' ),
        'object_types'  => array( 'page'), // Post type
        'context'       => 'normal',
        'show_on'      => array( 'key' => 'page-template', 'value' => 'page-templates/landing-one.php' ),
    ) );


    $cmb->add_field( array(
        'id'   => $prefix . 'disabled_items',
        'name' => 'Disable',
        'desc' => 'Disable the Items block',
        'type' => 'checkbox'
    ) );

    $cmb->add_field( array(
        'id'   => $prefix . 'items_title',
        'name' => 'Title',
        'type' => 'text',
        'default' => 'More than just management.',
    ) );

    $items_group = $cmb->add_field( array(
        'id'          => $prefix . 'items',
        'type'        => 'group',
        'description' => __( 'Create, modify, delete & sort items.', 'cmb2' ),
        'repeatable'  => true, // use false if you want non-repeatable group
        'options'     => array(
            'group_title'   => __( 'Item {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'    => __( 'Add Another item', 'cmb2' ),
            'remove_button' => __( 'Remove item', 'cmb2' ),
            'sortable'      => true, // beta
            'closed'        => true, // true to have the groups closed by default
        ),
    ) );

    $cmb->add_group_field( $items_group, array(
        'name' => 'Image',
        'id'   => 'item_image',
        'type' => 'file',
        'default' => $theme_url.'/images/landings/cutting-edge.png',
    ) );

    $cmb->add_group_field( $items_group, array(
        'name' => 'Title',
        'id'   => 'item_title',
        'type' => 'text',
        'default' => 'Cutting Edge',
    ) );

    $cmb->add_group_field( $items_group, array(
        'name' => 'Description',
        'id'   => 'item_description',
        'type' => 'textarea_small',
        'default' => 'Our commitment to technology is second to none, keeping our rates low and our properties full.',
    ) );

    /* Lists  */

    $cmb = new_cmb2_box( array(
        'id'            => $prefix.'list_metaboxes',
        'title'         => __( 'Lists block' ),
        'object_types'  => array( 'page'), // Post type
        'context'       => 'normal',
        'show_on'      => array( 'key' => 'page-template', 'value' => 'page-templates/landing-one.php' ),
    ) );


    $cmb->add_field( array(
        'id'   => $prefix . 'disabled_lists',
        'name' => 'Disable',
        'desc' => 'Disable the Lists block',
        'type' => 'checkbox'
    ) );

    $cmb->add_field( array(
        'name' => __( 'Title', 'cmb2' ),
        'id'   => $prefix . 'list_title',
        'type' => 'text',
        'default' => 'Being a Property owner has never been easier.',
    ) );

    $cmb->add_field( array(
        'name' => __( 'Subtitle', 'cmb2' ),
        'id'   => $prefix . 'list_subtitle',
        'type' => 'text',
        'default' => '[Client Name] is dedicated to maximizing the use of technology to manage your property.',
    ) );

    $cmb->add_field( array(
        'name'    => __( 'Left', 'cmb2' ),
        'desc'    => __( 'Left side content.', 'cmb2' ),
        'id'      => $prefix . 'list_left',
        'type'    => 'wysiwyg',
        'options' => array( 'textarea_rows' => 5, ),
        'default' => '<h4>We Offer:</h4>
<ul>
 	<li>Discounted Fast Leasing and Low Vacancy Rates</li>
 	<li>Great Tenant Retention Due to The Best Tenant Customer Service</li>
 	<li>Cloud Based Management with Owner and Tenant Portals to Access Your Account 24/7</li>
 	<li>Repairs and Maintenance without Coordination Fees</li>
 	<li>24 Hour Emergency Repair Line</li>
 	<li>Tenant Rental Payments Online</li>
 	<li>Direct Deposit of all Owner Payments at no Charge</li>
 	<li>Thorough and Professional Tenant Screening</li>
 	<li>Free Rental Property Assessments</li>
 	<li>Discounted Sales and Free Marketing</li>
</ul>',
    ) );

    $cmb->add_field( array(
        'name'    => __( 'Right', 'cmb2' ),
        'desc'    => __( 'Right side content.', 'cmb2' ),
        'id'      => $prefix . 'list_right',
        'type'    => 'wysiwyg',
        'options' => array( 'textarea_rows' => 5, ),
        'default' => '<h4>You Can Rely On Us To:</h4>
<ul>
 	<li>Handle all maintenance issues</li>
 	<li>Screen all potential tenants</li>
 	<li>Collect rental payments</li>
 	<li>Help you establish pricing strategies for your property</li>
 	<li>Provide you with access to online PropertyWare software, an owner portal that grants you real-time access to your cash flow, maintenance request and billing details.</li>
</ul>',
    ) );

    /* Image blocks  */

    $cmb = new_cmb2_box( array(
        'id'            => $prefix.'image_metaboxes',
        'title'         => __( 'Image / Text block' ),
        'object_types'  => array( 'page'), // Post type
        'context'       => 'normal',
        'show_on'      => array( 'key' => 'page-template', 'value' => 'page-templates/landing-one.php' ),
    ) );

    $cmb->add_field( array(
        'id'   => $prefix . 'disabled_chess',
        'name' => 'Disable',
        'desc' => 'Disable the Image / Text block',
        'type' => 'checkbox'
    ) );

    $cmb->add_field(array(
        'name' => 'Image top',
        'id'   => $prefix . 'chess_top_image',
        'type' => 'file',
        'default' => $theme_url.'/images/landings/couch.png',
    ) );

    $cmb->add_field(array(
        'name' => 'Image bottom',
        'id'   => $prefix . 'chess_bottom_image',
        'type' => 'file',
        'default' => $theme_url.'/images/landings/kitchen.png',
    ) );

    $cmb->add_field( array(
        'name'    => __( 'Text top', 'cmb2' ),
        'id'      => $prefix . 'chess_top_text',
        'type'    => 'wysiwyg',
        'options' => array( 'textarea_rows' => 5, ),
        'default' => "<h3>Locals, Here to Help You</h3>
We are a dedicated team of professional property managers in [client's area] committed to the quick, effective and affordable rental and management of your investment property. We bring many years of property management experience to the table, and our staff has managed hundreds of properties in the [client's area].",
    ) );

    $cmb->add_field( array(
        'name'    => __( 'Text bottom', 'cmb2' ),
        'id'      => $prefix . 'chess_bottom_text',
        'type'    => 'wysiwyg',
        'options' => array( 'textarea_rows' => 5, ),
        'default' => '<h3>Free Quote</h3>
Call: (000) 000-0000

Call Us Today For an Immediate Quote on Managing or Leasing Your Property.',
    ) );

    /* Last block  */

    $cmb = new_cmb2_box( array(
        'id'            => $prefix.'text_metaboxes',
        'title'         => __( 'Text block' ),
        'object_types'  => array( 'page'), // Post type
        'context'       => 'normal',
        'show_on'      => array( 'key' => 'page-template', 'value' => 'page-templates/landing-one.php' ),
    ) );


    $cmb->add_field( array(
        'id'   => $prefix . 'disabled_content',
        'name' => 'Disable',
        'desc' => 'Disable the Text block',
        'type' => 'checkbox'
    ) );

    $cmb->add_field( array(
        'name' => __( 'Title', 'cmb2' ),
        'id'   => $prefix . 'last_title',
        'type' => 'text',
        'default' => 'Trusted in the Community.',
    ) );

    $cmb->add_field( array(
        'name'    => __( 'Content', 'cmb2' ),
        'id'      => $prefix . 'last_content',
        'type'    => 'wysiwyg',
        'options' => array( 'textarea_rows' => 5, ),
        'default' => 'Whether you are looking to eventually sell your rental property or acquire more assets,
        our company has extensive experience in the brokerage field.
        We will work with you to evaluate the current market conditions in order to help you purchase
        or sell your property at price point that is most beneficial to you. We understand that the needs
        of our clients are always evolving, and whether you are looking to purchase, rehab, sell or lease
        your property, we work diligently to ensure that we can always help you acomplish your goals.',
    ) );

    /* Call to action  */

    $cmb = new_cmb2_box( array(
        'id'            => $prefix.'banner_metaboxes',
        'title'         => __( 'Banner block' ),
        'object_types'  => array( 'page'), // Post type
        'context'       => 'normal',
        'show_on'      => array( 'key' => 'page-template', 'value' => 'page-templates/landing-one.php' ),
        'default' => '',
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