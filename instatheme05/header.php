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
<?php wp_title(''); ?>
</title>
<?php $themeUrl = get_template_directory_uri(); ?>
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
    <div class="span12 bgwhite">
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
  </div>
</header>
<?php endif; ?>
<!-- End Insta Header Widget Area -->
<!-- Start Menu -->
<nav class="navbar navbar-inverse" id="navigation">
              <div class="navbar-inner">
                <div class="container maintainer">
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
<!-- End Menu -->
<?php if(is_front_page()) : ?>
<!-- Home Slideshow -->
<div class="row-fluid top-header-home">
    <div class="home-slideshow">
    <div class="left-opacity"></div>
    <?php
            $slideshow = bapi_get_slideshow();            
			$lastslide = count($slideshow) - 1;
			echo '<div class="first-slide"><img src="'.$slideshow[$lastslide]['url'].'" alt="" /></div>';           
			echo '<div class="last-slide"><img src="'.$slideshow[0]['url'].'" alt="" /></div>'; 

	?>
    <div class="flexslider bapi-flexslider" data-options='{ "animation": "slide", "controlNav": false, "slideshow": true, "controlsContainer": ".flex-direction-nav-outside", "itemWidth": 1100, "minItems": 1, "maxItems": 1 }'>
      <ul class="slides">
        <?php            
            $i = 0;
            foreach($slideshow as $ss){
                ?>
          <li>
          <img src="<?= $ss['url'] ?>" alt="<?= $ss['caption'] ?>" />
          <?php
		  	if(!empty($ss['caption'])){
		 		 echo '<p class="flex-caption maintainer">' . $ss['caption'] . '</p>';
		  	   }
		  ?>
          </li>
          <?php
                $i++;
            }
          ?>
      </ul>
    </div>
    
    <div class="right-opacity"></div>
    <div class="flex-direction-nav-outside"></div>
    </div>
    <?php if ( is_active_sidebar('insta-home-qsearch' ) ) : ?>        
        <div class="home-qsearch">
            <?php dynamic_sidebar( 'insta-home-qsearch' ); ?>
        </div>
	<?php endif; ?>
 </div>
<!-- End Home Slideshow -->
<script type="text/javascript">
$(window).load(function() {
 slideshowUpdate();
	$(window).resize(function() {
	  slideshowUpdate();
	});
  
});
function slideshowUpdate()
{
	var theWidth = $('.top-header-home').width();
  if(theWidth > 1100)
  {
	  var theHeight = $('.top-header-home').height();
  $('.left-opacity,.right-opacity,.first-slide img,.last-slide img').css('height',theHeight);
  $('.left-opacity,.right-opacity').css('width',(theWidth - 1100) /2 );
  $('.first-slide').css('left',((theWidth - 1100) /2)*-1 );
  $('.left-opacity,.right-opacity').show();
  }
}
</script>
<?php endif; ?>
<!-- Start pushdown section -->
<section class="pushdown <?php if (is_user_logged_in()) { echo "wpadminbarvisible"; } ?>">
<!-- Start page section -->
<div id="page" class="container-fluid">
<!-- Start main section -->
<div id="main" class="wrapper row-fluid maintainer">