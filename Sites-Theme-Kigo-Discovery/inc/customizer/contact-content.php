<?php

$section = 'contact_content';

$sections[] = array(
    'id' => $section,
    'title' => __('Page: Contact', 'kigodiscovery'),
    'priority' => '190'
);


$options['contact-subtitle'] = array(
    'id' => 'contact-subtitle',
    'label' => __('Subtitle', 'kigodiscovery'),
    'section' => $section,
    'type' => 'textarea',
    'default' => __('Drop us a line!', 'kigodiscovery'),
);
$options['contact-left'] = array(
    'id' => 'contact-left',
    'label' => __('Left', 'kigodiscovery'),
    'section' => $section,
    'type' => 'textarea',
    'default' => __('Left side content.', 'kigodiscovery'),
);
$options['contact-under'] = array(
    'id' => 'contact-under',
    'label' => __('Under map', 'kigodiscovery'),
    'section' => $section,
    'type' => 'textarea',
    'default' => __('Find us on the map.', 'kigodiscovery'),
);


//$options['contact-hero'] = array(
//    'id' => 'contact-hero',
//    'label' => __('Hero Image', 'kigodiscovery'),
//    'section' => $section,
//    'type' => 'upload',
//);

////******************************************************
//$section = 'home_header_section';
//$sections[] = array(
//    'id' => $section,
//    'title' => __('Home Header Settings', 'kigodiscovery'),
//    'priority' => '20',
//    'panel' => $panel
//);
//
//$options['home-getstarted-show'] = array(
//    'id' => 'pw_home-getstarted',
//    'label' => __('Show Get Started button', 'kigodiscovery'),
//    'section' => $section,
//    'type' => 'checkbox',
//    'default' => 1,
//);
//
//$options['map-embed'] = array(
//    'id' => 'map-embed',
//    'label' => __('Map', 'kigodiscovery'),
//    'section' => $section,
//    'type' => 'textarea',
//    'default' => 'Embed',
//);
