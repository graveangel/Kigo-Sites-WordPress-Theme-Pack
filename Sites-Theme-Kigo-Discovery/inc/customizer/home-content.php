<?php

$panel = 'home_content';
$panels[] = array(
    'id' => $panel,
    'title' => __('Home Settings', 'kigodiscovery'),
    'priority' => '170'
);

//******************************************************
// Home Hero
$section = 'home_hero_section';
$sections[] = array(
    'id' => $section,
    'title' => __('Home Slideshow Settings', 'kigodiscovery'),
    'priority' => '20',
    'panel' => $panel
);

$options['home-title'] = array(
    'id' => 'pw_home_title',
    'label' => __('Title', 'kigodiscovery'),
    'section' => $section,
    'type' => 'text',
    'default' => __('Invest - Build - Manage', 'kigodiscovery'),
);
$options['home-title-font'] = array(
    'id' => 'home-title-font',
    'label' => 'Title Font',
    'section' => $section,
    'type' => 'select',
    'choices' => $font_choices,
    'default' => 'Roboto Slab',
    'description' => 'Default: Roboto Slab'
);
$options['home-title-size'] = array(
    'id' => 'home-title-size',
    'label' => __('Title size', 'kigodiscovery'),
    'section' => $section,
    'type' => 'range',
    'input_attrs' => array(
        'min' => 10,
        'max' => 60,
        'step' => 1,
        'style' => 'color: #0a0',
    ),
    'default' => 42,
);
$options['home-tagline'] = array(
    'id' => 'pw_home_tagline',
    'label' => __('Tagline', 'kigodiscovery'),
    'section' => $section,
    'type' => 'textarea',
    'default' => __('Please complete this field with a tagline', 'kigodiscovery'),
);
$options['home-tagline-font'] = array(
    'id' => 'home-tagline-font',
    'label' => 'Tagline Font',
    'section' => $section,
    'type' => 'select',
    'choices' => $font_choices,
    'default' => 'Roboto Slab',
    'description' => 'Default: Roboto Slab'
);
$options['home-tagline-size'] = array(
    'id' => 'home-tagline-size',
    'label' => __('Tagline size', 'kigodiscovery'),
    'section' => $section,
    'type' => 'range',
    'input_attrs' => array(
        'min' => 10,
        'max' => 60,
        'step' => 1,
        'style' => 'color: #0a0',
    ),
    'default' => 16,
);
$options['home-video-btn'] = array(
    'id' => 'pw_home_video_btn',
    'label' => __('Hide Video Button', 'kigodiscovery'),
    'section' => $section,
    'type' => 'checkbox',
    'default' => 0,
);

$options['home-video-url'] = array(
    'id' => 'pw_home_video_url',
    'label' => __('Video URL', 'kigodiscovery'),
    'section' => $section,
    'type' => 'url',
    'default' => 'http://brightcove.vo.llnwd.net/v1/uds/pd/3999581340001/201503/1386/3999581340001_4105796798001_propertywaresizzle-08212014.mp4',
);

$options['home-phone-btn'] = array(
    'id' => 'pw_home_phone_btn',
    'label' => __('Hide Phone Button', 'kigodiscovery'),
    'section' => $section,
    'type' => 'checkbox',
    'default' => 0,
);

$options['home-phone-no'] = array(
    'id' => 'pw_home_phone_no',
    'label' => __('Phone Number', 'kigodiscovery'),
    'section' => $section,
    'type' => 'text',
    'default' => '', //getSolutionDataValue('company_phone_no',"Phone"),
);

//******************************************************
// Home Other
$section = 'home_other_section';
$sections[] = array(
    'id' => $section,
    'title' => __('Home Other Settings', 'kigodiscovery'),
    'priority' => '30',
    'panel' => $panel
);
$options['home-solutions'] = array(
    'id' => 'pw_home_solutions',
    'label' => __('Solutions Title', 'kigodiscovery'),
    'section' => $section,
    'type' => 'text',
    'default' => __('Our Solutions', 'kigodiscovery'),
);
$options['footer-resources'] = array(
    'id' => 'pw_resources',
    'label' => __('Resources Title', 'kigodiscovery'),
    'section' => $section,
    'type' => 'text',
    'default' => __('Resources', 'kigodiscovery'),
);

