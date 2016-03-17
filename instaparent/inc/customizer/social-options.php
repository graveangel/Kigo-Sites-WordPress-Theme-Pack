<?php
//Social Section ************************************
$section = "Social";
$sections[] = array(
    'id' => $section,
    'title' => __('Social icons', 'kigodiscovery'),
    'priority' => '160'
);
$options['url-facebook'] = array(
    'id' => 'url-facebook',
    'label' => __('Facebook URL', 'kigodiscovery'),
    'section' => $section,
    'type' => 'url',
    'description' => '(Leave empty to hide it)'
);
$options['url-twitter'] = array(
    'id' => 'url-twitter',
    'label' => __('Twitter URL', 'kigodiscovery'),
    'section' => $section,
    'type' => 'url',
    'description' => '(Leave empty to hide it)'
);
$options['url-google'] = array(
    'id' => 'url-google',
    'label' => __('Google+ URL', 'kigodiscovery'),
    'section' => $section,
    'type' => 'url',
    'description' => '(Leave empty to hide it)'
);
$options['url-linkedin'] = array(
    'id' => 'url-linkedin',
    'label' => __('Linkedin URL', 'kigodiscovery'),
    'section' => $section,
    'type' => 'url',
    'description' => '(Leave empty to hide it)'
);
$options['url-youtube'] = array(
    'id' => 'url-youtube',
    'label' => __('Youtube URL', 'kigodiscovery'),
    'section' => $section,
    'type' => 'url',
    'description' => '(Leave empty to hide it)'
);
$options['url-pinterest'] = array(
    'id' => 'url-pinterest',
    'label' => __('Pinterest URL', 'kigodiscovery'),
    'section' => $section,
    'type' => 'url',
    'description' => '(Leave empty to hide it)'
);
$options['url-instagram'] = array(
    'id' => 'url-instagram',
    'label' => __('Instagram URL', 'kigodiscovery'),
    'section' => $section,
    'type' => 'url',
    'description' => '(Leave empty to hide it)'
);
$options['url-blog'] = array(
    'id' => 'url-blog',
    'label' => __('Blog URL', 'kigodiscovery'),
    'section' => $section,
    'type' => 'url',
    'description' => '(Leave empty to hide it)'
);
