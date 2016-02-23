<?php
//******************************************************
$section = 'aboutus_content';
$sections[] = array(
    'id' => $section,
    'title' => __('Page: About us', 'kigodiscovery'),
    'priority' => '180'
);
$options['about-title'] = array(
    'id' => 'about-title',
    'label' => __('Title', 'kigodiscovery'),
    'section' => $section,
    'type' => 'text',
    'default' => __('What Makes Us Unique.', 'kigodiscovery'),
);
$options['about-subtitle'] = array(
    'id' => 'about-subtitle',
    'label' => __('Subtitle', 'kigodiscovery'),
    'section' => $section,
    'type' => 'textarea',
//    'description' => 'You can write down html markup. Press "Go Fullscreen" to expand the editor to fullscreen mode, and then you can press ESC to get out of fullscreen mode.',
    'default' => __('Please complete this field with a subtitle', 'kigodiscovery'),
);
$options['about-title-subtitle-color'] = array(
    'id' => 'about-title-subtitle-color',
    'label' => __('Color', 'kigodiscovery'),
    'section' => $section,
    'type' => 'color',
    'default' => __('#fff', 'kigodiscovery'),
);
$options['about-hero'] = array(
    'id' => 'about-hero',
    'label' => __('Hero Image', 'kigodiscovery'),
    'section' => $section,
    'type' => 'upload',
    'default' => get_template_directory_uri() . '/kd-common/img/dummy-about-us-hero.jpg'
);
