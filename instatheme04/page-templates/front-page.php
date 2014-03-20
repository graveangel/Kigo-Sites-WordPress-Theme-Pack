<?php
/**
 * Template Name: Front Page Template
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 */

get_header(); ?>

<div class="span12">
  <div class="row-fluid">
    <div class="span3 insta-left-home-area">
      <?php if ( is_active_sidebar( 'insta-left-home' ) ) : ?>
      <?php dynamic_sidebar( 'insta-left-home' ); ?>
      <?php endif; ?>
    </div>
    <div class="span9 insta-right-home-area">
      <?php if ( is_active_sidebar( 'insta-right-home' ) ) : ?>
      <?php dynamic_sidebar( 'insta-right-home' ); ?>
      <?php endif; ?>
      <div class="row-fluid">
        <div class="span12">
          <div id="slide-caption"></div>
        </div>
      </div>
    </div>
  </div>
  <?php if ( is_active_sidebar( 'insta-bottom-full-home' ) ) : ?>
  <div class="row-fluid">
    <div class="span12">
      <?php dynamic_sidebar( 'insta-bottom-full-home' ); ?>
    </div>
  </div>
  <?php endif; ?>
  <div class="row-fluid">
    <div class="span12">
      <div class="tabbable">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab1" data-toggle="tab"><span class="glyphicons keys"><i></i><span class="hidden-phone">FEATURED PROPERTIES</span></span></a></li>
          <li><a href="#tab2" data-toggle="tab"><span class="glyphicons binoculars"><i></i><span class="hidden-phone">PROPERTY FINDERS</span></span> </a></li>
          <li><a href="#tab3" data-toggle="tab"><span class="glyphicons tags"><i></i><span class="hidden-phone">SPECIAL OFFERS</span></span> </a></li>
          <li><a href="#tab4" data-toggle="tab"><span class="glyphicons dislikes"><i></i><span class="hidden-phone">ABOUT US</span></span></a></li>
        </ul>
        <div class="tab-content">
          <div class="insta-bottom-left-home tab-pane active" id="tab1">
            <?php dynamic_sidebar( 'insta-bottom-left-home' ); ?>
          </div>
          <div class="insta-bottom-middle-home tab-pane" id="tab2">
            <?php dynamic_sidebar( 'insta-bottom-middle-home' ); ?>
          </div>
          <div class="insta-bottom-right-home tab-pane" id="tab3">
            <?php dynamic_sidebar( 'insta-bottom-right-home' ); ?>
          </div>
          <div class="insta-bottom-left-home tab-pane" id="tab4">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php the_content(); ?>
            <?php endwhile; endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>
