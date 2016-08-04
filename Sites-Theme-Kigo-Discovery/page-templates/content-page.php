<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;
/*
  Template Name: Full-screen Content Page
 */
?>
<?php get_header(); ?>
    <article class="full-screen-page">
        <div class="row-fluid">
            <article class="body col-xs-12">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <?php the_content(); ?>
                <?php endwhile;
                else: ?>
                    <p><?php _e('Sorry, this page does not exist.'); ?></p>
                <?php endif; ?>
            </article>
        </div>
    </article>
<?php get_footer(); ?>