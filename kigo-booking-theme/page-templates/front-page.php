<?php
/**
 * Template Name: Front Page Template
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 */

get_header(); ?>

<div class="span12">
    <?php if ( is_active_sidebar( 'insta-bottom-full-home' ) ) : ?>
  <div class="row-fluid">
    <div class="span12 bottom-full-home">
      <?php dynamic_sidebar( 'insta-bottom-full-home' ); ?>
    </div>
  </div>
  <?php endif; ?>
  <div class="row-fluid">
      <?php if ( is_active_sidebar( 'insta-left-home' ) ) : ?>
    	   <div class="span12">
			<?php dynamic_sidebar( 'insta-left-home' ); ?>
      	   </div>	
	  <?php endif; ?>
<!-- removed all this block here to the booking theme --> 
<!--    
      <?php if ( is_active_sidebar( 'insta-right-home' ) ) : ?>
	   	<div class="span3">
    	  <?php dynamic_sidebar( 'insta-right-home' ); ?>
  		</div>
      <?php endif; ?>
-->
<!-- End removed all this block here to the booking theme --> 

  
<!-- removed all this block here to the booking theme --> 
<!--

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <?php 
    // Get the content
    $content = get_the_content();

    if(!trim($content) == "") // Check if the string is empty or only whitespace
    {        
	?>
    <div class="row-fluid">
      <div class="span12">
        <div class="hp-content module shadow">
          <?php the_content(); ?>
        </div>
      </div>
    </div>
    <?php } ?>
    <?php endwhile; endif; ?>
 -->
<!-- End removed all this block here to the booking theme --> 


  </div>
<!-- removed all this block here to the booking theme --> 
<!--

  <?php if ( is_active_sidebar( 'insta-bottom-left-home' ) && is_active_sidebar( 'insta-bottom-middle-home' ) && is_active_sidebar( 'insta-bottom-right-home' ) ) : ?>
  <div class="row-fluid">
    <div class="span4">
      <?php dynamic_sidebar( 'insta-bottom-left-home' ); ?>
    </div>
    <div class="span4">
      <?php dynamic_sidebar( 'insta-bottom-middle-home' ); ?>
    </div>
    <div class="span4">
      <?php dynamic_sidebar( 'insta-bottom-right-home' ); ?>
    </div>
  </div>
  <?php else: ?>
  <?php if ( is_active_sidebar( 'insta-bottom-left-home' ) ) : ?>
  <div class="row-fluid">
    <?php if ( !is_active_sidebar( 'insta-bottom-middle-home' ) && !is_active_sidebar( 'insta-bottom-right-home' ) ) : ?>
    <div class="span12">
      <?php dynamic_sidebar( 'insta-bottom-left-home' ); ?>
    </div>
    <?php else: ?>
    <div class="span6">
      <?php dynamic_sidebar( 'insta-bottom-left-home' ); ?>
    </div>
    <div class="span6">
      <?php dynamic_sidebar( 'insta-bottom-middle-home' ); ?>
      <?php dynamic_sidebar( 'insta-bottom-right-home' ); ?>
    </div>
    <?php endif; ?>
  </div>
  <?php else: ?>
  <?php if ( is_active_sidebar( 'insta-bottom-middle-home' ) ) : ?>
  <div class="row-fluid">
    <?php if ( !is_active_sidebar( 'insta-bottom-left-home' ) && !is_active_sidebar( 'insta-bottom-right-home' ) ) : ?>
    <div class="span12">
      <?php dynamic_sidebar( 'insta-bottom-middle-home' ); ?>
    </div>
    <?php else: ?>
    <?php if ( is_active_sidebar( 'insta-bottom-right-home' ) ) : ?>
    <div class="span6">
      <?php dynamic_sidebar( 'insta-bottom-middle-home' ); ?>
    </div>
    <div class="span6">
      <?php dynamic_sidebar( 'insta-bottom-right-home' ); ?>
    </div>
    <?php else: ?>
    <div class="span6">
      <?php dynamic_sidebar( 'insta-bottom-left-home' ); ?>
    </div>
    <div class="span6">
      <?php dynamic_sidebar( 'insta-bottom-middle-home' ); ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
  </div>
  <?php else: ?>
  <?php if ( is_active_sidebar( 'insta-bottom-right-home' ) ) : ?>
  <div class="row-fluid">
    <?php if ( !is_active_sidebar( 'insta-bottom-left-home' ) && !is_active_sidebar( 'insta-bottom-middle-home' ) ) : ?>
    <div class="span12">
      <?php dynamic_sidebar( 'insta-bottom-right-home' ); ?>
    </div>
    <?php else: ?>
    <div class="span6">
      <?php dynamic_sidebar( 'insta-bottom-right-home' ); ?>
    </div>
    <div class="span6">
      <?php dynamic_sidebar( 'insta-bottom-left-home' ); ?>
      <?php dynamic_sidebar( 'insta-bottom-middle-home' ); ?>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>
  <?php endif; ?>
  <?php endif; ?>
  <?php endif; ?>
  <?php if ( is_active_sidebar( 'insta-home-testimonial' ) ) : ?>		
  <div class="row-fluid">
     <div class="span12">
        <?php dynamic_sidebar( 'insta-home-testimonial' ); ?>
     </div>
     </div>
  <?php endif; ?>
-->

<!-- End removed all this block here to the booking theme --> 
  </div>
 </div>
<?php get_footer(); ?>
