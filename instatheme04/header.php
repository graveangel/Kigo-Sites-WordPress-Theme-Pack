<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
/*
File: header.php
This is the header code.
*/
?>
<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=yes"/>
<title>
	<?php
	$bapi_meta_title = get_post_meta($post->ID, 'bapi_meta_title', true);
	if (!empty($bapi_meta_title)) {
		echo $bapi_meta_title;
	} else {
		wp_title('');
	}
	?>
</title>
<?php $themeUrl = wp_make_link_relative(get_template_directory_uri()); ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo $themeUrl; ?>/insta-common/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php wp_head(); ?>
<link rel="stylesheet" href="<?php echo $themeUrl; ?>/insta-common/css/print.css" type="text/css" media="print" />
</head>

<body <?php body_class(); ?>>
<?php

if ( is_front_page() ) { ?>
<div id="maximage">
  <?php
        $slideshow = bapi_get_slideshow();
        $i = 0;
        foreach($slideshow as $ss){
            ?>
  <img src="<?= $ss['url'] ?>" alt="<?= $ss['caption'] ?>" />
  <?php
            $i++;
        }
      ?>
</div>
<?php    
} 
?> 

<!-- Start Main Content Wrapper -->
<article id="container">
<!-- Start Insta Top Fixed Widget Area -->
<div id="insta-top-fixed" class="container-fluid navbar-fixed-top <?php if (is_user_logged_in()) { echo "wpadminbarvisible"; } ?>">
  <div class="row-fluid maintainer">
    <div class="span12">
      <?php if ( is_active_sidebar( 'insta-top-fixed-left' ) ) : ?>
      <?php dynamic_sidebar( 'insta-top-fixed-left' ); ?>
      <?php endif; ?>
      <nav class="navbar navbar-inverse pull-right" id="navigation">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
          </a>
		  <?php 
          //an array with properties that overwrites the default          
          $defaults = array(
		  'menu'			=> 'main-navigation-menu',
          'menu_id'         => 'primary',
          'menu_class'      => 'nav pull-right',
          'items_wrap'      => '<div class="nav-collapse collapse"><ul id="%1$s" class="%2$s">%3$s</ul></div>',
          'theme_location'  => 'primary',					
          'walker'          => new instaparent_walker_nav_menu //here we are calling our new method we created in functions.php
          );                
          //the menu function of wp with our array of properties we created above
          wp_nav_menu( $defaults );            
          ?>
      </nav>
      <?php if ( is_active_sidebar( 'insta-top-fixed' ) ) : ?>
      <?php dynamic_sidebar( 'insta-top-fixed' ); ?>
      <?php endif; ?>
    </div>
  </div>
</div>
<!-- End Insta Top Fixed Widget Area -->
<!-- Start Insta Header Widget Area -->
<?php if ( is_active_sidebar( 'insta-header-right' ) || is_active_sidebar( 'insta-header-left' )) : ?>
<header id="insta-header" class="container-fluid site-header">
  <div class="row-fluid maintainer">
    <div class="span6 left-side-header">
		<?php if ( is_active_sidebar( 'insta-header-left' ) ) : ?>
        <?php dynamic_sidebar( 'insta-header-left' ); ?>
        <?php endif; ?>
    </div>
    <div class="span6 right-side-header">
		<?php if ( is_active_sidebar( 'insta-header-right' ) ) : ?>
        <?php dynamic_sidebar( 'insta-header-right' ); ?>
        <?php endif; ?>
    </div>
  </div>
</header>
<?php endif; ?>
<!-- End Insta Header Widget Area -->
<!-- Start pushdown section -->
<section class="pushdown <?php if (is_user_logged_in()) { echo "wpadminbarvisible"; } ?>">
<!-- Start page section -->
<div id="page" class="container-fluid">
<!-- Start main section -->
<div id="main" class="wrapper row-fluid maintainer">