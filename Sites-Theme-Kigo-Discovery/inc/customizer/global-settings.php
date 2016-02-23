<?php
//******************************************************
$section = 'content';
$sections[] = array(
    'id' => $section,
    'title' => __('Global settings', 'kigodiscovery'),
    'priority' => '160'
);
$options['site-favicon'] = array(
    'id' => 'site-favicon',
    'label' => __('Favicon', 'kigodiscovery'),
    'section' => $section,
    'type' => 'upload',
    'default' => get_template_directory_uri() . '/images/favicons/favicon.png',
);
// $options['site-company'] = array(
//     'id' => 'site-company',
//     'label' => __('Company Name', 'kigodiscovery'),
//     'section' => $section,
//     'type' => 'text',
//     'default' => '', //__( getSolutionDataValue('company_name','Coastal Inc.'), 'kigodiscovery'),
// );
$options['site-phone'] = array(
    'id' => 'site-phone',
    'label' => __('Phone Number', 'kigodiscovery'),
    'section' => $section,
    'type' => 'text',
    'default' => '', //__( getSolutionDataValue('company_phone_no',"Phone"), 'kigodiscovery'),
);
$options['site-phone-header'] = array(
    'id' => 'site-phone-header',
    'label' => __('Show Phone number in header', 'kigodiscovery'),
    'section' => $section,
    'type' => 'checkbox',
    'default' => 1,
);
$options['site-weather-woid'] = array(
    'id' => 'site-weather-woid',
    'label' => __('Weather WOID', 'kigodiscovery'),
    'section' => $section,
    'type' => 'text',
    'default' => '', 
    'description' => '<a href="//woeid.rosselliot.co.nz/lookup/" target="_blank">Lookup WOID</a>'
);
$options['site-weather'] = array(
    'id' => 'site-weather',
    'label' => __('Show Weather', 'kigodiscovery'),
    'section' => $section,
    'type' => 'checkbox',
    'default' => 0,
    'description' => 'For the weather to show a valid WOID needs to be specified.'
);
$options['site-weather-scale'] = array(
    'id' => 'site-weather-scale',
    'label' => __('Temperature Scale', 'kigodiscovery'),
    'section' => $section,
    'type' => 'radio',
    'default' => 'f',
    'choices' => array('f'=>'Fahrenheit','c'=>'Celsius')
);
$options['fb-like-share'] = array(
    'id' => 'fb-like-share',
    'label' => __('Facebook Buttons', 'kigodiscovery'),
    'section' => $section,
    'type' => 'checkbox',
    'default' => 1,
    'description' => 'Show Like and Share buttons in property detail pages'
);

// $options['site-address'] = array(
//     'id' => 'site-address',
//     'label' => __('Address', 'kigodiscovery'),
//     'section' => $section,
//     'type' => 'text',
//     'default' => '', //__( getSolutionDataValue('company_address',"Address"), 'kigodiscovery'),
// );
// $options['site-address-header'] = array(
//     'id' => 'site-address-header',
//     'label' => __('Show Address in header', 'kigodiscovery'),
//     'section' => $section,
//     'type' => 'checkbox',
//     'default' => 1,
// );
// $options['site-hours'] = array(
//     'id' => 'site-hours',
//     'label' => __('Hours', 'kigodiscovery'),
//     'section' => $section,
//     'type' => 'text',
//     'default' => __('Hours', 'kigodiscovery'),
// );
// $options['site-hours-header'] = array(
//     'id' => 'site-hours-header',
//     'label' => __('Show Hours in header', 'kigodiscovery'),
//     'section' => $section,
//     'type' => 'checkbox',
//     'default' => 1,
// );



//$options['owner-login-text'] = array(
//    'id' => 'owner-login-text',
//    'label' => __('Owner Login Text', 'kigodiscovery'),
//    'section' => $section,
//    'type' => 'text',
//    'default' => __('Owner Login', 'kigodiscovery'),
//);
//$options['owner-login-url'] = array(
//    'id' => 'owner-login-url',
//    'label' => __('Owner Login URL', 'kigodiscovery'),
//    'section' => $section,
//    'type' => 'text',
//    'default' => __('http://www.kigo.net', 'kigodiscovery'),
//);
//$options['hide-owner-login'] = array(
//    'id' => 'hide-owner-login',
//    'label' => __('Hide Owner Login button in the header', 'kigodiscovery'),
//    'section' => $section,
//    'type' => 'checkbox',
//    'default' => 0,
//);
// $options['site-logo'] = array(
//     'id' => 'site-logo',
//     'label' => __('Footer Image', 'kigodiscovery'),
//     'section' => $section,
//     'type' => 'upload',
//     'default' => '', //getSolutionDataValue('company_logo_url',""),
// );
// $options['footer-text'] = array(
//     'id' => 'footer-text',
//     'label' => __('Footer Text', 'kigodiscovery'),
//     'section' => $section,
//     'type' => 'kdtextarea'
// );
//$options['terms-privacy'] = array(
//    'id' => 'terms-privacy',
//    'label' => __('Hide links in footer for Terms and Privacy', 'kigodiscovery'),
//    'section' => $section,
//    'type' => 'checkbox',
//    'default' => 1,
//);
