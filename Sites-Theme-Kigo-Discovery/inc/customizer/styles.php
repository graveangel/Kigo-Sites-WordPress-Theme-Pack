<?php

/**
 * Implements styles set in the theme customizer
 *
 * @package Customizer Library Demo
 */
if (!function_exists('customizer_library_demo_build_styles') && class_exists('Customizer_Library_Styles')) :

    /**
     * Process user options to generate CSS needed to implement the choices.
     *
     * @since  1.0.0.
     *
     * @return void
     */
    function customizer_library_demo_build_styles() {

        // Primary Button Color
        $setting = 'primary-color';
        $mod = get_theme_mod($setting, customizer_library_get_default($setting));

            $color = sanitize_hex_color($mod);

            Customizer_Library_Styles()->add(array(
                'selectors' => array(
                    '.primary-fill-color',
                ),
                'declarations' => array(
                    'background-color' => $color . " !important",
                    'color' => 'white',
                )
            ));
            Customizer_Library_Styles()->add(array(
                'selectors' => array(
                    '.primary-fill-color:hover',
                ),
                'declarations' => array(
                    'color' => "white !important"
                )
            ));

            Customizer_Library_Styles()->add(array(
                'selectors' => array(
                    '.primary-stroke-color',
                    '.menu-item:hover > a'
                ),
                'declarations' => array(
                    'color' => $color . " !important"
                )
            ));

        // Secondary Button Color
        $setting = 'secondary-color';
        $mod = get_theme_mod($setting, customizer_library_get_default($setting));


            $color = sanitize_hex_color($mod);

            Customizer_Library_Styles()->add(array(
                'selectors' => array(
                    '.secondary-fill-color',
                ),
                'declarations' => array(
                    'background-color' => $color . " !important"
                )
            ));
            Customizer_Library_Styles()->add(array(
                'selectors' => array(
                    '.secondary-stroke-color',
                ),
                'declarations' => array(
                    'color' => $color . " !important"
                )
            ));

        // Links Color
        $setting = 'tertiary-color';
        $mod = get_theme_mod($setting, customizer_library_get_default($setting));



            $color = sanitize_hex_color($mod);

            Customizer_Library_Styles()->add(array(
                'selectors' => array(
                    'a',
                ),
                'declarations' => array(
                    'color' => $color
                )
            ));
        // Links Hover Color
        $setting = 'tertiary-color-hover';
        $mod = get_theme_mod($setting, customizer_library_get_default($setting));



            $color = sanitize_hex_color($mod);

            Customizer_Library_Styles()->add(array(
                'selectors' => array(
                    'a:hover'
                ),
                'declarations' => array(
                    'color' => $color,
                )
            ));

        // Main background Color
        $setting = 'bg-main';
        $mod = get_theme_mod($setting, customizer_library_get_default($setting));

        if ($mod !== customizer_library_get_default($setting)) {

            $color = sanitize_hex_color($mod);

            Customizer_Library_Styles()->add(array(
                'selectors' => array(
                    '.main-background',
                ),
                'declarations' => array(
                    'background-color' => $color . "!important"
                )
            ));
        }
        // Header background Color
        $setting = 'bg-header';
        $mod = get_theme_mod($setting, customizer_library_get_default($setting));

        if ($mod !== customizer_library_get_default($setting)) {

            $color = sanitize_hex_color($mod);

            Customizer_Library_Styles()->add(array(
                'selectors' => array(
                    '.header-background',
                ),
                'declarations' => array(
                    'background-color' => $color . "!important"
                )
            ));
        }
        // Footer background Color
        $setting = 'bg-footer';
        $mod = get_theme_mod($setting, customizer_library_get_default($setting));

        if ($mod !== customizer_library_get_default($setting)) {

            $color = sanitize_hex_color($mod);

            Customizer_Library_Styles()->add(array(
                'selectors' => array(
                    '.footer-background',
                ),
                'declarations' => array(
                    'background-color' => $color . "!important"
                )
            ));
        }
        
        // Body font
        $setting = 'body-font';
        $mod = get_theme_mod($setting, customizer_library_get_default($setting));

        if ($mod !== customizer_library_get_default($setting)) {

            $font = $mod;

            Customizer_Library_Styles()->add(array(
                'selectors' => array(
                    'body',
                    'p',
                    '.conversation',
                    '.info',
                    '.from'
                ),
                'declarations' => array(
                    'font-family' => $font . " !important"
                )
            ));
        }
        
        
        
        // Headings font
        $setting = 'title-font';
        $mod = get_theme_mod($setting, customizer_library_get_default($setting));

        if ($mod !== customizer_library_get_default($setting)) {

            $font = $mod;

            Customizer_Library_Styles()->add(array(
                'selectors' => array(
                    '.widget_title',
                    '.widget-title',
                    '.page-title',
                    '.title',
                    'header.pagination-centered > h1',
                    '.kd-widget .title',
                    '.kd-widget .title span'
                ),
                'declarations' => array(
                    'font-family' => $font . " !important"
                )
            ));
        }
        


        //About color:
        $setting = 'about-title-subtitle-color';
        $mod = get_theme_mod($setting, customizer_library_get_default($setting));

        if ($mod !== customizer_library_get_default($setting)) {

            $color = sanitize_hex_color($mod);

            Customizer_Library_Styles()->add(array(
                'selectors' => array(
                    '.about-us-template .kd-custom-hero h1.page-title',
                    '.about-us-template .kd-custom-hero .subtitle'
                ),
                'declarations' => array(
                    'color' => $color . "!important"
                )
            ));
        }


}
endif;

add_action('customizer_library_styles', 'customizer_library_demo_build_styles');

if (!function_exists('customizer_library_demo_styles')) :

    /**
     * Generates the style tag and CSS needed for the theme options.
     *
     * By using the "Customizer_Library_Styles" filter, different components can print CSS in the header.
     * It is organized this way to ensure there is only one "style" tag.
     *
     * @since  1.0.0.
     *
     * @return void
     */
    function customizer_library_demo_styles() {

        do_action('customizer_library_styles');

        // Echo the rules
        $css = Customizer_Library_Styles()->build();

        if (!empty($css)) {
            echo "\n<!-- Begin Custom CSS -->\n<style type=\"text/css\" id=\"demo-custom-css\">\n";
            echo $css;
            echo "\n</style>\n<!-- End Custom CSS -->\n";
        }
    }

endif;

add_action('wp_head', 'customizer_library_demo_styles', 11);
