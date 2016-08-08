<?php //Template name: Landing Two ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">

    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php
    $faviconUrl = get_theme_mod('site-favicon', get_template_directory_uri() . '/images/favicons/favicon.png');
    echo '<link rel="apple-touch-icon" href="/img.php?src=' . $faviconUrl . '&w=140&h=140&q=85&zc=1">';
    echo '<link rel="shortcut icon" type="image/png" href="/img.php?src=' . $faviconUrl . '&w=32&h=32&q=85&zc=1" />';

    if(pmeta('landing_two_font_heading')){
        echo "<link href='https://fonts.googleapis.com/css?family=".pmeta('landing_two_font_heading')."' rel='stylesheet' type='text/css'>";
    }
    if(pmeta('landing_two_font_body')){
        echo "<link href='https://fonts.googleapis.com/css?family=".pmeta('landing_two_font_body')."' rel='stylesheet' type='text/css'>";
    }

    $wrapper = getbapisolutiondata();
    $logo = str_replace("http:", "", $wrapper["site"]["company_logo_url"]);
    $redirectPage = pmeta('landing_two_redirect_page');

    ?>

    <?php wp_head(); ?>
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri().'/css/landing-pages.css' ?>"/>
    <style>
        .primary-color, #btn-leadrequest{
            color: white;
            background: <?php echo pmeta('landing_two_primary_color') ?>;
        }
        .call-to-action .action-button:hover{
            color: <?php echo pmeta('landing_two_primary_color') ?> !important;
        }
        h1,h2,h3,h4,h5,h6,.title{
        <?php
        if(pmeta('landing_two_font_heading')){
            echo "font-family: '".pmeta('landing_two_font_heading')."';";
        }
        if(pmeta('landing_two_heading_color')){
            echo "color: ".pmeta('landing_two_heading_color').";";
        }
        ?>
        }
        body, div, p, span{
        <?php
        if(pmeta('landing_two_font_body')){
            echo "font-family: '".pmeta('landing_two_font_heading')."';";
        }
        if(pmeta('landing_two_body_color')){
            echo "color: ".pmeta('landing_two_body_color').";";
        }
        ?>
        }
    </style>
</head>
<body class="landing-page two font-small">
<?php if(have_posts()): the_post(); ?>

    <header>
        <div class="maxw">
            <div class="logo bgimg" style="background-image: url(<?php echo pmeta('landing_two_logo') ? : $logo  ?>)"></div>
            <div class="phone" data-meta="landing_two_phone"><?php echo pmeta('landing_two_phone') ?></div>
        </div>
    </header>

    <div class="hero-block bgimg" style="background-image: url(<?php echo pmeta('landing_two_heroimg') ?>);">
        <div class="maxw">
            <div class="hero-content font-medium" data-meta="landing_two_hero_content">
                <?php echo pmeta('landing_two_hero_content') ?>
            </div>
            <div class="form box">
                <div class="content primary-color font-medium" data-meta="landing_two_form_content">
                    <?php echo pmeta('landing_two_form_title') ?>
                </div>
                <div id="bapi-inquiryform" class="bapi-inquiryform"
                     data-templatename="tmpl-leadrequestform-propertyinquiry"
                     data-log="0"
                     data-showphonefield="1"
                     data-phonefieldrequired="0"
                     data-showdatefields="0"
                     data-shownumberguestsfields="0"
                     data-showleadsourcedropdown="0"
                     data-leadsourcedropdownrequired="0"
                     data-showcommentsfield="1"
                     data-redirecturl="<?php echo $redirectPage ? get_the_permalink($redirectPage) : ''  ?>"></div>
            </div>
        </div>
    </div>

    <section class="margin-v pad-h ta-center font-large lh2" data-meta="landing_two_content">
        <?php echo pmeta('landing_two_content') ?>
    </section>

    <section>
        <div class="image-text right">
            <div class="image bgimg" style="background-image: url(<?php echo pmeta('landing_two_chess_image') ?>);"></div>
            <div class="text" data-meta="landing_two_chess_text">
                <?php echo pmeta('landing_two_chess_text') ?>
            </div>
        </div>
    </section>

    <div class="call-to-action primary-color">
        <div class="left">
            <h1 class="upper" data-meta="landing_two_cta_title"><?php echo pmeta('landing_two_cta_title') ?></h1>
            <h4 data-meta="landing_two_cta_subtitle"><?php echo pmeta('landing_two_cta_subtitle') ?></h4>
        </div>
        <div class="right">
            <a class="action-button upper ta-center" href="<?php echo pmeta('landing_two_cta_button_url') ?>" data-meta="landing_two_cta_button_text"><?php echo pmeta('landing_two_cta_button_text') ?></a>
        </div>
    </div>

    <footer class="maxw">
        <div class="nav"><?php wp_nav_menu() ?></div>
        <div class="bottom">
            <div class="logo bgimg" style="background-image: url(<?php echo pmeta('landing_two_logo') ? : $logo  ?>)"></div>
            <div class="phone" data-meta="landing_two_phone"><?php echo pmeta('landing_two_phone') ?></div>
            <div class="logo">powered by <a href="http://www.kigo.net" target="_blank">Kigo</a></div>
        </div>
    </footer>


    <?php if(is_super_admin()): ?>
        <div class="hidden"><?php echo get_meta_form(get_the_ID()); ?></div>
    <?php endif; ?>

<?php endif; wp_footer(); ?>
</body>
</html>