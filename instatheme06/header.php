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
<?php $bapi_meta_title = get_post_meta($post->ID,'bapi_meta_title', true);
      $custom_page_title_option = get_post_meta($post->ID, "meta-box-checkbox", true);
if(!empty($bapi_meta_title) && !empty($custom_page_title_option)){ 
  echo $bapi_meta_title;
}else{
  if(is_home())
  {
    $page_for_posts = get_option( 'page_for_posts' );
    $blog_page = get_post($page_for_posts);
    echo apply_filters('the_title', $blog_page->post_title);
  }
  else
    wp_title();
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
<!-- Start Main Content Wrapper -->
<article id="container">
<!-- Start Insta Top Fixed Widget Area -->
<?php if ( is_active_sidebar( 'insta-top-fixed' ) ) : ?>
<div id="insta-top-fixed" class="container-fluid navbar-fixed-top <?php if (is_user_logged_in()) { echo "wpadminbarvisible"; } ?>">
  <div class="row-fluid maintainer">
    <div class="span12">      
      <?php dynamic_sidebar( 'insta-top-fixed' ); ?>
    </div>
  </div>
</div>
<?php endif; ?>
<!-- End Insta Top Fixed Widget Area -->
<!-- Start Insta Header Widget Area -->
<?php if ( is_active_sidebar( 'insta-header-right' ) || is_active_sidebar( 'insta-header-left' )) : ?>
<header id="insta-header" class="container-fluid site-header <?php if ( !is_active_sidebar( 'insta-top-fixed' ) ) {echo "insta-top-fixed-invisible";}?>">
  <div class="row-fluid maintainer">
   <div class="span12">
    <div class="<?php if(!is_active_sidebar( 'insta-header-right' )) : ?>span12 <?php else: ?>span6 <?php endif; ?>left-side-header">
		<?php if ( is_active_sidebar( 'insta-header-left' ) ) : ?>
        <?php dynamic_sidebar( 'insta-header-left' ); ?>
        <?php endif; ?>
    </div>
    	<?php if ( is_active_sidebar( 'insta-header-right' ) ) : ?>
        <div class="span6 right-side-header">
		<?php dynamic_sidebar( 'insta-header-right' ); ?>
         </div>
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
<!-- Start Menu -->
	<nav class="navbar navbar-inverse" id="navigation">
              <div class="navbar-inner maintainer">
                <div class="container">
                  <a data-target=".navbar-inverse-collapse" data-toggle="collapse" class="btn btn-navbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </a>
                  <div class="brand hidden-desktop">Menu</div>
                  <?php 
          //an array with properties that overwrites the default          
          $defaults = array(
		  'menu'			=> 'main-navigation-menu',		
          'menu_id'         => 'primary',
          'menu_class'      => 'nav',
          'items_wrap'      => '<div class="nav-collapse collapse navbar-inverse-collapse"><ul id="%1$s" class="%2$s">%3$s</ul></div>',
          'theme_location'  => 'primary',					
          'walker'          => new instaparent_walker_nav_menu //here we are calling our new method we created in functions.php
          );                
          //the menu function of wp with our array of properties we created above
          wp_nav_menu( $defaults );            
          ?>
                  
                </div>
              </div><!-- /navbar-inner -->
     </nav> 
<!-- Start main section -->
<div id="main" class="wrapper row-fluid maintainer">