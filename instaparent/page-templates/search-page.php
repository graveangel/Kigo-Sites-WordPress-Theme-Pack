<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
/*
Template Name: Search Page
*/
?>
<?php get_header(); ?>
<article class="search-page">	
<div class="row-fluid">

  <?php if ( is_active_sidebar( 'insta-left-sidebar-search' ) ) : ?>
      <aside class="span3">  	
          <?php dynamic_sidebar( 'insta-left-sidebar-search' ); ?>
      </aside>
  <?php endif; ?>
  
  <?php if ( is_active_sidebar( 'insta-left-sidebar-search' ) && is_active_sidebar( 'insta-right-sidebar-search' )  ) : ?>
  	<article class="span6">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<?php the_content(); ?>
	<?php endwhile; else: ?>
		<p><?php _e('Sorry, this page does not exist.'); ?></p>
	<?php endif; ?>
  </article>
  <?php else: ?>
  	<?php if ( !is_active_sidebar( 'insta-left-sidebar-search' ) && !is_active_sidebar( 'insta-right-sidebar-search' )  ) : ?>
        <article class="span12">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
        <?php endwhile; else: ?>
            <p><?php _e('Sorry, this page does not exist.'); ?></p>
        <?php endif; ?>	
        </article>
    <?php else: ?>
        <article class="span9">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
        <?php endwhile; else: ?>
            <p><?php _e('Sorry, this page does not exist.'); ?></p>
        <?php endif; ?>	
        </article>
    <?php endif; ?>
  <?php endif; ?>
  
  <?php if ( is_active_sidebar( 'insta-right-sidebar-search' ) ) : ?>    
  <aside class="span3">
      <?php dynamic_sidebar( 'insta-right-sidebar-search' ); ?>
  </aside>
  <?php endif; ?>
  
</div>

</article>
<?php get_footer(); ?>