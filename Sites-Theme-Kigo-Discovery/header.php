<?php

//Custom header columns

$hcols_left = get_theme_mod('hcols', 6);
$hcols_right = 12 - $hcols_left;

$uhcols_left = get_theme_mod('uhcols', 6);
$uhcols_right = 12 - $uhcols_left;

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png"  href="<?php echo get_theme_mod('site-favicon', ''); ?>" />
    <title><?php echo get_theme_mod('site-company', wp_title()); ?></title>
    <?php wp_head() ?>
</head>

<body <?php body_class('main-background') ?> >

<div class="global-wrapper">

    <div class="header-background">
        <header>
            <div class="header row row-nopadding page-width">
                <div class="col-sm-<?php echo $hcols_left ?> col-xs-12 hidden-xs"><?php  if (is_active_sidebar('header_left')) { dynamic_sidebar('header_left'); }  ?></div>
                <div class="col-sm-<?php echo $hcols_right ?> col-xs-12  align-r">
                    <div class="customizer row">
                        <div class="col-xs-12 items">
                            <ul>
                                <?php if(get_theme_mod('site-weather')):?>
                                    <li id="weather-place"></li>
                                <?php endif; ?>
                                <?php if(get_theme_mod('url-facebook')):?>
                                    <li><a href="<?php echo get_theme_mod('url-facebook'); ?>" target="_blank" title="Facebook"><i class="fa fa-facebook"></i></a></li>
                                <?php endif; ?>

                                <?php if(get_theme_mod('url-twitter')):?>
                                    <li><a href="<?php echo get_theme_mod('url-twitter'); ?>" target="_blank" title="Twitter"><i class="fa fa-twitter"></i></a></li>
                                <?php endif; ?>

                                <?php if(get_theme_mod('url-google')):?>
                                    <li><a href="<?php echo get_theme_mod('url-google'); ?>" target="_blank" title="Google plus"><i class="fa fa-google-plus"></i></a></li>
                                <?php endif; ?>

                                <?php if(get_theme_mod('url-linkedin')):?>
                                    <li><a href="<?php echo get_theme_mod('url-linkedin'); ?>" target="_blank" title="Linkedin"><i class="fa fa-linkedin"></i></a></li>
                                <?php endif; ?>

                                <?php if(get_theme_mod('url-youtube')):?>
                                    <li><a href="<?php echo get_theme_mod('url-youtube'); ?>" target="_blank" title="Youtube"><i class="fa fa-youtube"></i></a></li>
                                <?php endif; ?>

                                <?php if(get_theme_mod('url-pinterest')):?>
                                    <li><a href="<?php echo get_theme_mod('url-pinterest'); ?>" target="_blank" title="Pinterest"><i class="fa fa-pinterest"></i></a></li>
                                <?php endif; ?>

                                <?php if(get_theme_mod('url-instagram')):?>
                                    <li><a href="<?php echo get_theme_mod('url-instagram'); ?>" target="_blank" title="Instagram"><i class="fa fa-instagram"></i></a></li>
                                <?php endif; ?>

                                <?php if(get_theme_mod('url-blog')):?>
                                    <li><a href="<?php echo get_theme_mod('url-blog'); ?>" target="_blank" title="Blog"><i class="fa fa-rss"></i></a></li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <?php if(get_theme_mod('site-phone-header')): ?>
                            <div class="col-xs-12 phone">
                                <a href="tel:<?php echo get_theme_mod('site-phone'); ?>"><?php echo get_theme_mod('site-phone'); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if(is_active_sidebar('header_right')): ?>
                        <div class="col-xs-12">
                            <?php dynamic_sidebar('header_right');   ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="under_header page-width row row-nopadding header-background">
                <div class="col-sm-<?php echo $uhcols_left ?> col-xs-12">
                    <div class="row row-nopadding"><?php  if (is_active_sidebar('under_header_left')) { dynamic_sidebar('under_header_left'); }  ?></div>
                </div>
                <div class="col-sm-<?php echo $uhcols_right ?> col-xs-12 align-r">
                    <div class="row row-nopadding">
                        <div class="hidden visible-xs mobile-only">
                            <?php  if (is_active_sidebar('header_left')) { dynamic_sidebar('header_left'); }  ?>
                        </div>
                        <?php  if (is_active_sidebar('under_header_right')) { dynamic_sidebar('under_header_right'); }  ?>
                    </div>
                </div>
            </div>

        </header>
    </div>