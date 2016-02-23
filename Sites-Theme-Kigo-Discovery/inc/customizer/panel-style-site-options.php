<?php

$panel = 'style_options';

$panels[] = array(
    'id' => $panel,
    'title' => __('Global styles', 'kigodiscovery'),
    'priority' => '150'
);

//******************************************************
// Color Option
$section = 'colors';

$sections[] = array(
    'id' => $section,
    'title' => __('Colors', 'kigodiscovery'),
    'priority' => '10',
    'panel' => $panel
);

$color_schemes = array(
    'default' => 'Default',
    'cool' => 'Cool',
    'fresh' => 'Fresh',
    'relaxed' => 'Relaxed',
    'custom' => 'Custom'
);
/*$options['color-scheme'] = array(
    'id' => 'color-scheme',
    'label' => __('Color Scheme', 'kigodiscovery'),
    'section' => $section,
    'type' => 'select',
    'choices' => $color_schemes,
    'default' => 'default',
);*/

$options['primary-color'] = array(
    'id' => 'primary-color',
    'label' => __('Main buttons color', 'kigodiscovery'),
    'section' => $section,
    'type' => 'color',
    'default' => $primary_color,
    'description' => 'This affects the default color for your primary buttons on your site.'
);
$options['secondary-color'] = array(
    'id' => 'secondary-color',
    'label' => __('Secondary buttons color', 'kigodiscovery'),
    'section' => $section,
    'type' => 'color',
    'default' => $secondary_color,
    'description' => 'This affects the default color for your secondary buttons on your site.'
);
$options['tertiary-color'] = array(
    'id' => 'tertiary-color',
    'label' => __('Links color', 'kigodiscovery'),
    'section' => $section,
    'type' => 'color',
    'default' => $tertiary_color,
    'description' => 'This affects the links color on your site.'
);
$options['tertiary-color-hover'] = array(
    'id' => 'tertiary-color-hover',
    'label' => __('Links hover color', 'kigodiscovery'),
    'section' => $section,
    'type' => 'color',
    'default' => $primary_color,
    'description' => 'This affects the links hover color on your site.'
);

//******************************************************
// Backgrounds Option
$section = 'backgrounds';

$sections[] = array(
    'id' => $section,
    'title' => __('Backgrounds', 'kigodiscovery'),
    'priority' => '10',
    'panel' => $panel
);
$options['bg-main'] = array(
    'id' => 'bg-main',
    'label' => __('Main Background Color', 'kigodiscovery'),
    'section' => $section,
    'type' => 'color',
    'default' => '#f9f9f9',
    'description' => 'This affects the main background color.'
);
$options['bg-header'] = array(
    'id' => 'bg-header',
    'label' => __('Header Background Color', 'kigodiscovery'),
    'section' => $section,
    'type' => 'color',
    'default' => '#f9f9f9',
    'description' => 'This affects the header background color.'
);
$options['bg-footer'] = array(
    'id' => 'bg-footer',
    'label' => __('Footer Background Color', 'kigodiscovery'),
    'section' => $section,
    'type' => 'color',
    'default' => '#f9f9f9',
    'description' => 'This affects the footer background color.'
);


//******************************************************
// Typography Option
$section = 'typography';
$font_choices = customizer_library_get_font_choices();

$sections[] = array(
    'id' => $section,
    'title' => __('Typography', 'kigodiscovery'),
    'priority' => '20',
    'panel' => $panel
);

$options['title-font'] = array(
    'id' => 'title-font',
    'label' => 'Title Font',
    'section' => $section,
    'type' => 'select',
    'choices' => $font_choices,
    'default' => 'Montserrat',
    'description' => 'Default: Montserrat'
);
$options['body-font'] = array(
    'id' => 'body-font',
    'label' => 'Body Font',
    'section' => $section,
    'type' => 'select',
    'choices' => $font_choices,
    'default' => 'Montserrat',
    'description' => 'Default: Montserrat'
);
