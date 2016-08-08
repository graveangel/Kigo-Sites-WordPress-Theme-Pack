<?php //Template name: Landing One ?>
<!DOCTYPE html>
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

    if(pmeta('landing_one_font_heading')){
        echo "<link href='https://fonts.googleapis.com/css?family=".pmeta('landing_one_font_heading')."' rel='stylesheet' type='text/css'>";
    }
    if(pmeta('landing_one_font_body')){
        echo "<link href='https://fonts.googleapis.com/css?family=".pmeta('landing_one_font_body')."' rel='stylesheet' type='text/css'>";
    }

    $wrapper = getbapisolutiondata();
    $logo = str_replace("http:", "", $wrapper["site"]["company_logo_url"]);
    $redirectPage = pmeta('landing_one_redirect_page');
    ?>

    <?php wp_head(); ?>
    <style>
        .primary-color, #test{
            color: white;
            background-color: <?php echo pmeta('landing_one_primary_color') ?>;
        }
        .call-to-action .action-button:hover{
            color: <?php echo pmeta('landing_one_primary_color') ?> !important;
        }
        h1,h2,h3,h4,h5,h6,.title{
        <?php
        if(pmeta('landing_one_font_heading')){
            echo "font-family: '".pmeta('landing_one_font_heading')."';";
        }
        if(pmeta('landing_one_heading_color')){
            echo "color: ".pmeta('landing_one_heading_color')." !important;";
        }
        ?>
        }
        div, p, span{
        <?php
        if(pmeta('landing_one_font_body')){
            echo "font-family: '".pmeta('landing_one_font_body')."';";
        }
        if(pmeta('landing_one_body_color')){
            echo "color: ".pmeta('landing_one_body_color')." !important;";
        }
        ?>
        }
    </style>
</head>
<body class="landing-page one font-small">
<?php if(have_posts()): the_post(); ?>

    <header>
        <div class="maxw">
            <div class="logo bgimg" style="background-image: url(<?php echo pmeta('landing_one_logo') ? : $logo  ?>)"></div>
            <div class="phone" data-meta="landing_one_phone"><?php echo pmeta('landing_one_phone') ?></div>
        </div>
    </header>

    <?php if(!pmeta('landing_one_disabled_hero')): ?>
        <div class="hero-block bgimg" style="background-image: url(<?php echo pmeta('landing_one_heroimg') ?>);">
            <div class="maxw">
                <div class="form">
                    <div class="content" data-meta="landing_one_form_content">
                        <?php echo pmeta('landing_one_form_content') ?>
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
    <?php endif; ?>

    <?php if(!pmeta('landing_one_disabled_items')): ?>
        <section class="ta-center">
            <h2 class="title font-large" data-meta="landing_one_items_title"><?php echo pmeta('landing_one_items_title') ?></h2>
            <ul class="items">
                <?php $items = pmeta('landing_one_items') ? : []; foreach($items as $key => $item): ?>
                    <li>
                        <div class="image bgimg" style="background-image: url(<?php echo $item['item_image'] ?>)"></div>
                        <h4 class="font-medium" data-meta="landing_one_items[<?php echo $key ?>][item_title]"><?php echo $item['item_title'] ?></h4>
                        <p data-meta="landing_one_items[<?php echo $key ?>][item_description]"><?php echo $item['item_description'] ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <?php if(!pmeta('landing_one_disabled_lists')): ?>
        <section>
            <h2 class="ta-center title font-large" data-meta="landing_one_list_title"><?php echo pmeta('landing_one_list_title') ?></h2>
            <p class="ta-center font-medium" data-meta="landing_one_list_subtitle"><?php echo pmeta('landing_one_list_subtitle') ?></p>

            <div class="ib-container">
                <div class="landing-list" data-meta="landing_one_list_left">
                    <?php echo pmeta('landing_one_list_left') ?>
                </div>
                <div class="landing-list" data-meta="landing_one_list_right">
                    <?php echo pmeta('landing_one_list_right') ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if(!pmeta('landing_one_disabled_chess')): ?>
        <section>
            <div class="image-text right">
                <div class="image bgimg" style="background-image: url(<?php echo pmeta('landing_one_chess_top_image') ?>);"></div>
                <div class="text" data-meta="landing_one_chess_top_text">
                    <?php echo pmeta('landing_one_chess_top_text') ?>
                </div>
            </div>
            <div class="image-text left">
                <div class="text" data-meta="landing_one_chess_bottom_text">
                    <?php echo pmeta('landing_one_chess_bottom_text') ?>
                </div>
                <div class="image bgimg" style="background-image: url(<?php echo pmeta('landing_one_chess_bottom_image') ?>);"></div>
            </div>
        </section>
    <?php endif; ?>

    <?php if(!pmeta('landing_one_disabled_content')): ?>
        <section class="pad-h ta-center ">
            <h2 class="title font-large" data-meta="landing_one_last_title"><?php echo pmeta('landing_one_last_title') ?></h2>
            <p class="font-medium" data-meta="landing_one_last_content"><?php echo pmeta('landing_one_last_content') ?></p>
        </section>
    <?php endif; ?>

    <div class="call-to-action primary-color">
        <div class="left">
            <h1 class="upper" data-meta="landing_one_cta_title"><?php echo pmeta('landing_one_cta_title') ?></h1>
            <h4 class="font-medium" data-meta="landing_one_cta_subtitle"><?php echo pmeta('landing_one_cta_subtitle') ?></h4>
        </div>
        <div class="right">
            <a class="action-button upper ta-center" href="<?php echo pmeta('landing_one_cta_button_url') ?>" data-meta="landing_one_cta_button_text"><?php echo pmeta('landing_one_cta_button_text') ?></a>
        </div>
    </div>

    <footer class="maxw">
        <div class="nav"><?php wp_nav_menu() ?></div>
        <div class="bottom">
            <div class="logo bgimg" style="background-image: url(<?php echo pmeta('landing_one_logo') ? : $logo  ?>)" data-meta="landing_one_logo"></div>
            <div class="phone" data-meta="landing_one_phone"><?php echo pmeta('landing_one_phone') ?></div>
            <div class="logo">powered by <a href="http://www.kigo.net" target="_blank">Kigo</a></div>
        </div>
    </footer>

    <?php if(is_super_admin()): ?>
        <div class="hidden"><?php echo get_meta_form(get_the_ID()); ?></div>
    <?php endif; ?>

<?php endif; wp_footer(); ?>
</body>
</html>