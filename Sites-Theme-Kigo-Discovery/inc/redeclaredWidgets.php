<?php
/**
 * These classes extend from the Insta-ones declared by the plugin.
 * This file won't be needed once the plugin is updated and all Insta-referrals have been disposed of.
 */
// if(class_exists('BAPI_Header'))
// {
//     class KD_Header extends BAPI_Header {
//
//         public function __construct() {
//             WP_Widget::__construct(
//             'bapi_old_header', // Base ID
//             'KIGO Header', // Name
//             array('description' => __('Displays the Header', 'text_domain'),) // Args
//         );
//     }
//
// }
add_action('widgets_init', function() {
    unregister_widget('BAPI_Header');
    // register_widget('KD_Header');
});
// }


/**
 *
 */
// if(class_exists ('BAPI_Footer'))
// {
//     class KD_Footer extends BAPI_Footer {
//
//         public function __construct() {
//             WP_Widget::__construct(
//             'bapi_old_fotoer', // Base ID
//             'KIGO Footer', // Name
//             array('description' => __('Displays the Header', 'text_domain'),) // Args
//         );
//     }
//
// }
add_action('widgets_init', function() {
    unregister_widget('BAPI_Footer');
    // register_widget('KD_Footer');
});
// }

/**
 *
 */
if(class_exists ('BAPI_HP_Slideshow'))
{
    class KD_BAPI_HP_Slideshow extends BAPI_HP_Slideshow {

        public function __construct() {
            WP_Widget::__construct(
                'old_bapi_hp_slideshow', // Base ID
                'KIGO Homepage Slideshow', // Name
                array('description' => __('Homepage Slideshow', 'text_domain'),) // Args
            );
        }

    }

    add_action('widgets_init', function() {
        unregister_widget('BAPI_HP_Slideshow');
        register_widget('KD_BAPI_HP_Slideshow');
    });
}

/**
 *
 */
if(class_exists ('BAPI_SiteSelector'))
{
    class KD_BAPI_SiteSelector extends BAPI_SiteSelector {

        public function __construct() {
            WP_Widget::__construct(
                'old_bapi_multisites', // Base ID
                'KIGO Site Selector', // Name
                array('description' => __('Displays Flags for each Site', 'text_domain'),) // Args
            );
        }

    }

    add_action('widgets_init', function() {
        unregister_widget('BAPI_SiteSelector');
        register_widget('KD_BAPI_SiteSelector');
    });
}
/**
 *
 */
// if(class_exists ('BAPI_HP_LogoWithTagline'))
// {
// class KD_BAPI_HP_LogoWithTagline extends BAPI_HP_LogoWithTagline {
//
//     public function __construct() {
//         WP_Widget::__construct(
//         'old_bapi_hp_logowithtagline', // Base ID
//         'KIGO Homepage Logo With Tagline', // Name
//         array('description' => __('Homepage Logo With Tagline', 'text_domain'),) // Args
//     );
// }
//
// }
//
add_action('widgets_init', function() {
    unregister_widget('BAPI_HP_LogoWithTagline');
    //register_widget('KD_BAPI_HP_LogoWithTagline');
});
//  }

/**
 *
 */
//  if(class_exists ('BAPI_HP_Logo'))
//  {
// class KD_BAPI_HP_Logo extends BAPI_HP_Logo {
//
//     public function __construct() {
//         WP_Widget::__construct(
//                 'old_bapi_hp_logo', // Base ID
//                 'KIGO Homepage Logo', // Name
//                 array('description' => __('Homepage Logo', 'text_domain'),) // Args
//         );
//     }
//
// }

add_action('widgets_init', function() {
    unregister_widget('BAPI_HP_Logo');
    // register_widget('KD_BAPI_HP_Logo');
});
// }
//
/**
 *
 */
//  if(class_exists ('BAPI_HP_Search'))
//  {
// class KD_BAPI_HP_Search extends BAPI_HP_Search {
//
//     public function __construct() {
//         WP_Widget::__construct(
//                 'old_bapi_hp_search', // Base ID
//                 'KIGO Search - Home Page', // Name
//                 array('description' => __('Availability Search Widget for Home Page', 'text_domain'),) // Args
//         );
//     }
//
// }
//
add_action('widgets_init', function() {
    unregister_widget('BAPI_HP_Search');
    //register_widget('KD_BAPI_HP_Search');
});
//  }


/**
 *
 */
// if( class_exists('BAPI_Search'))
// {class KD_BAPI_Search extends BAPI_Search {
//
//     public function __construct() {
//         WP_Widget::__construct(
//                 'old_api__search', // Base ID
//                 'KIGO Search', // Name
//                 array('description' => __('Availability Search Widget', 'text_domain'),) // Args
//         );
//     }
//
// }
//
add_action('widgets_init', function() {
    unregister_widget('BAPI_Search');
    // register_widget('KD_BAPI_Search');
});
// }

/**
 *
 */
if(class_exists('BAPI_Inquiry_Form'))
{
    class KD_BAPI_Inquiry_Form extends BAPI_Inquiry_Form {

        public function __construct() {
            WP_Widget::__construct(
                'old_bapi_inquiry_form', // Base ID
                'KIGO Inquiry Form', // Name
                array('description' => __('Inquiry Form', 'text_domain'),) // Args
            );
        }

        public function widget( $args, $instance ) {
            extract( $args );
            $title = apply_filters( 'widget_title', $instance['title'] );

            if(isset( $instance[ 'inquiryModeTitle' ])){$inquiryModeTitle =  $instance['inquiryModeTitle'];}
            else{ $inquiryModeTitle = "Inquire for Booking Details";}

            /* Do we show the phone field ? */
            if(isset( $instance[ 'showPhoneField' ])){$bShowPhoneField =  $instance['showPhoneField'];}
            else{ $bShowPhoneField = true;}
            /* Its the Phone Field Required ? */
            if(isset( $instance[ 'phoneFieldRequired' ])){$bPhoneFieldRequired =  $instance['phoneFieldRequired'];}
            else{ $bPhoneFieldRequired = true;}

            /* Do we show the date fields ? */
            if(isset( $instance[ 'showDateFields' ])){$bShowDateFields =  $instance['showDateFields'];}
            else{ $bShowDateFields = true;}

            /* Do we show the number of guests fields ? */
            if(isset( $instance[ 'showNumberGuestsFields' ])){$bShowNumberGuestsFields =  $instance['showNumberGuestsFields'];}
            else{ $bShowNumberGuestsFields = true;}

            /* Do we show the how did you hear about us dropdown ? */
            if(isset( $instance[ 'showLeadSourceDropdown' ])){$bShowLeadSourceDropdown =  $instance['showLeadSourceDropdown'];}
            else{ $bShowLeadSourceDropdown = true;}
            /* Its the Lead Source Dropdown Required ? */
            if(isset( $instance[ 'leadSourceDropdownRequired' ])){$bLeadSourceDropdownRequired =  $instance['leadSourceDropdownRequired'];}
            else{ $bLeadSourceDropdownRequired = false;}

            /* Do we show the comments field ? */
            if(isset( $instance[ 'showCommentsField' ])){$bShowCommentsField =  $instance['showCommentsField'];}
            else{ $bShowCommentsField = true;}

            echo '<div class="inquiry-form">';
            if ( ! empty( $title ) )
                echo  '<h1 class="title">'.$title .'</h1>' ;
            if ( ! empty( $inquiryModeTitle ) )
                echo '<div class="inquirymodetitle hide">'. $inquiryModeTitle . '</div>';
            ?>
            <div id="bapi-inquiryform" class="bapi-inquiryform" data-templatename="tmpl-leadrequestform-propertyinquiry" data-log="0" data-showphonefield="<?= $bShowPhoneField ? 1 : 0; ?>" data-phonefieldrequired="<?= $bPhoneFieldRequired ? 1 : 0; ?>" data-showdatefields="<?= $bShowDateFields ? 1 : 0; ?>" data-shownumberguestsfields="<?= $bShowNumberGuestsFields ? 1 : 0; ?>" data-showleadsourcedropdown="<?= $bShowLeadSourceDropdown ? 1 : 0; ?>" data-leadsourcedropdownrequired="<?= $bLeadSourceDropdownRequired ? 1 : 0; ?>" data-showcommentsfield="<?= $bShowCommentsField ? 1 : 0; ?>" ></div>
            <?php

            $googleConversionkey = get_option( 'bapi_google_conversion_key');
            $googleConversionlabel = get_option( 'bapi_google_conversion_label');
            $googleConversionCode = '';
            if($googleConversionkey != '' && $googleConversionlabel != ''){
                $googleConversionCode = '<!-- Google Code Conversion -->
<script type="text/javascript">
function googleConversionTrack(){
	var image = new Image(1,1);
	image.src = "//www.googleadservices.com/pagead/conversion/'.$googleConversionkey.'/?value=0&amp;label='.$googleConversionlabel.'&amp;guid=ON&amp;script=0";
}
</script>';
            }

            echo $googleConversionCode;
            echo '</div>';
        }

    }

    add_action('widgets_init', function() {
        unregister_widget('BAPI_Inquiry_Form');
        register_widget('KD_BAPI_Inquiry_Form');
    });

}

/**
 *
 */
if(class_exists('BAPI_Featured_Properties')){
    class KD_BAPI_Featured_Properties extends BAPI_Featured_Properties {

        public function __construct() {
            WP_Widget::__construct(
                'old_bapi_featured_properties', // Base ID
                'KIGO Featured Properties', // Name
                array('description' => __('KIGO Featured Properties', 'text_domain'),) // Args
            );
        }

    }

    add_action('widgets_init', function() {
        unregister_widget('BAPI_Featured_Properties');
        register_widget('KD_BAPI_Featured_Properties');
    });
}


/**
 *
 */
if(class_exists('BAPI_Developments_Widget'))
{
    class kd_BAPI_Developments_Widget extends BAPI_Developments_Widget {

        public function __construct() {
            WP_Widget::__construct(
                'old_bapi_developments_widget', // Base ID
                'KIGO Developments', // Name
                array('description' => __('KIGO Developments', 'text_domain'),) // Args
            );
        }

    }

    add_action('widgets_init', function() {
        unregister_widget('BAPI_Developments_Widget');
        register_widget('kd_BAPI_Developments_Widget');
    });
}


/**
 *
 */
// if(class_exists('BAPI_Property_Finders')){
//     class KD_BAPI_Property_Finders extends BAPI_Property_Finders {
//
//         public function __construct() {
//             WP_Widget::__construct(
//                     'old_bapi_property_finders', // Base ID
//                     'KIGO Search Buckets', // Name
//                     array('description' => __('KIGO Search Buckets', 'text_domain'),) // Args
//             );
//         }
//
//     }
//
add_action('widgets_init', function() {
    unregister_widget('BAPI_Property_Finders');
    // register_widget('KD_BAPI_Property_Finders');
});
//
// }

/**
 *
 */
if(class_exists('BAPI_Specials_Widget')){
    class KD_BAPI_Specials_Widget extends BAPI_Specials_Widget {

        public function __construct() {
            WP_Widget::__construct(
                'old_bapi_specials_widget', // Base ID
                'KIGO Specials', // Name
                array('description' => __('KIGO Specials', 'text_domain'),) // Args
            );
        }

    }

    add_action('widgets_init', function() {
        unregister_widget('BAPI_Specials_Widget');
        register_widget('KD_BAPI_Specials_Widget');
    });
}


/**
 *
 */
// if(class_exists('BAPI_Similar_Properties')){
//     class KD_BAPI_Similar_Properties extends BAPI_Similar_Properties {
//
//         public function __construct() {
//             WP_Widget::__construct(
//                     'old_bapi_similar_properties', // Base ID
//                     'KIGO Similar Properties', // Name
//                     array('description' => __('KIGO Similar Properties', 'text_domain'),) // Args
//             );
//         }
//
//     }
//
add_action('widgets_init', function() {
    unregister_widget('BAPI_Similar_Properties');
    //    register_widget('KD_BAPI_Similar_Properties');
});
// }


/**
 *
 */
if(class_exists('BAPI_Weather_Widget')){
    class KD_BAPI_Weather_Widget extends BAPI_Weather_Widget {

        public function __construct() {
            WP_Widget::__construct(
                'old_bapi_weather_widget', // Base ID
                'KIGO Weather', // Name
                array('description' => __('KIGO Weather', 'text_domain'),) // Args
            );
        }

    }

    add_action('widgets_init', function() {
        unregister_widget('BAPI_Weather_Widget');
        register_widget('KD_BAPI_Weather_Widget');
    });
}


/**
 *
 */
if(class_exists('BAPI_DetailOverview_Widget'))
{
    class KD_BAPI_DetailOverview_Widget extends BAPI_DetailOverview_Widget {

        public function __construct() {
            WP_Widget::__construct(
                'old_bapi_detailoverview', // Base ID
                'KIGO Detail Overview', // Name
                array('description' => __('Displays the overview section of a detail screen', 'text_domain'),) // Args
            );
        }

    }

    add_action('widgets_init', function() {
        unregister_widget('BAPI_DetailOverview_Widget');
        register_widget('KD_BAPI_DetailOverview_Widget');
    });

}
