<?php //Template name: Confirmation One ?>
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

    if(pmeta('confirmation_font_heading')){
        echo "<link href='https://fonts.googleapis.com/css?family=".pmeta('confirmation_font_heading')."' rel='stylesheet' type='text/css'>";
    }
    if(pmeta('confirmation_font_body')){
        echo "<link href='https://fonts.googleapis.com/css?family=".pmeta('confirmation_font_body')."' rel='stylesheet' type='text/css'>";
    }

    $wrapper = getbapisolutiondata();
    $logo = str_replace("http:", "", $wrapper["site"]["company_logo_url"]);

    $head_script = pmeta('confirmation_script_head');
    $abody_script = pmeta('confirmation_script_body_open');
    $bbody_script = pmeta('confirmation_script_body_close');

    ?>
    <?php wp_head(); ?>
    <style>
        .primary-color{
            color: white;
            background-color: <?php echo pmeta('confirmation_primary_color') ?>;
        }
        .call-to-action .action-button:hover{
            color: <?php echo pmeta('confirmation_primary_color') ?> !important;
        }
        h1,h2,h3,h4,h5,h6,.title{
        <?php
        if(pmeta('confirmation_font_heading')){
            echo "font-family: '".pmeta('confirmation_font_heading')."';";
        }
        if(pmeta('confirmation_heading_color')){
            echo "color: ".pmeta('confirmation_heading_color').";";
        }
        ?>
        }
        div, p, span{
        <?php
        if(pmeta('confirmation_font_body')){
            echo "font-family: '".pmeta('confirmation_font_heading')."';";
        }
        if(pmeta('confirmation_body_color')){
            echo "color: ".pmeta('confirmation_body_color').";";
        }
        ?>
        }
    </style>
    <?php echo !empty($head_script) ? $head_script : ''; //Output '<head> script' if exists ?>
</head>
<body class="landing-page confirmation">
<?php echo !empty($abody_script) ? $abody_script : ''; //Output 'after <body> script' if exists ?>
<?php if(have_posts()): the_post(); ?>
    <header>
        <div class="maxw">
            <div class="logo bgimg" style="background-image: url(<?php echo pmeta('confirmation_logo') ? : $logo ?>)"></div>
            <div class="phone"><?php echo pmeta('confirmation_phone') ?></div>
        </div>
    </header>

    <div class="confirmation-hero">
        <?php echo pmeta('confirmation_hero_content') ?>
        <div class="buttons">
            <?php $buttons = pmeta('confirmation_buttons') ? : []; foreach($buttons as $key => $button): ?>
            <a class="button primary-color" href="<?php echo $button['button_url'] ?>"><?php echo $button['button_text'] ?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <footer class="maxw">
        <div class="nav"><?php wp_nav_menu() ?></div>
        <div class="bottom">
            <div class="logo bgimg" style="background-image: url(<?php echo pmeta('confirmation_logo') ? : $logo ?>)"></div>
            <div class="phone"><?php echo pmeta('confirmation_phone') ?></div>
        <div class="logo">powered by <a href="http://www.kigo.net" target="_blank">Kigo</a></div>
        </div>
    </footer>
<?php endif; wp_footer(); ?>
<?php echo !empty($bbody_script) ? $bbody_script : ''; //Output 'before </body> script' if exists ?>
</body>
</html>