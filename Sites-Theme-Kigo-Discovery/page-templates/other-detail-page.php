<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
/*
Template Name: "Other" Detail Page
*/
get_header();
?>
    <article class="other-detail-page">

        <div class="row page-width">
            <article class="col-md-9 col-xs-12">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <?php the_content(); ?>
                <?php endwhile; else: ?>
                    <p><?php _e('Sorry, this page does not exist.'); ?></p>
                <?php endif; ?>
            </article>

            <aside class="col-md-3 col-xs-12">
                <div class="detail-overview-target"></div>
                <div class="row">
                    <?php if ( is_active_sidebar( 'sidebar_detail' ) ) : ?>
                        <?php dynamic_sidebar( 'sidebar_detail' ); ?>
                    <?php endif; ?>
                </div>
            </aside>
        </div>

    </article>

<?php get_footer(); ?>