<?php
//******************************************************
$section = 'foter';
$sections[] = array(
    'id' => $section,
    'title' => __('Footer', 'kigodiscovery'),
    'priority' => '300'
);
$options['footer-left-text'] = array(
    'id' => 'footer-left-text',
    'label' => __('Left side text', 'kigodiscovery'),
    'section' => $section,
    'type' => 'textarea',
);
$options['footer-right-text'] = array(
    'id' => 'footer-right-text',
    'label' => __('Right side text', 'kigodiscovery'),
    'section' => $section,
    'type' => 'textarea',
);
