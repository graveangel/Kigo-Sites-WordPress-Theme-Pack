<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
?>
<?php get_header(); ?>
<div class="about-us-template">


    <!-- HERO BLOCK -->
    <div class="kd-custom-hero nocollapse page-block" style="background-image:url(<?php echo get_theme_mod("about-hero"); ?>);">
        <div class="page-width">
            <h1 class="page-title">
                <?php echo get_theme_mod("about-title"); ?>
            </h1>
            <div class="subtitle col-lg-4 col-lg-offset-4 col-xs-12">
                <?php echo get_theme_mod("about-subtitle"); ?>
            </div>
        </div>
    </div>
    <!-- END HERO BLOCK -->


    <!-- PAGE DESC -->
    <div class="page-desc nocollapse page-block">
        <div class="page-width">
            <div class="title col-xs-12"><?php the_title(); ?></div>

            <div class="col-sm-12 col-md-6 description nopadding">
                <!-- <div class="subtitle">PLEASE ALLOW US TO INTRODUCE OURSELVES</div>
                <div class="title">We are Discovery Rentals</div>
                <p class="paragraph">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <p class="paragraph">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <p class="paragraph">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt</p>
                <p class="paragraph">Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.</p> -->

                <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
                the_content();
                endwhile; else: ?>
                <p>Sorry, no posts matched your criteria.</p>
            <?php endif; ?>
            </div>
            <div class="col-sm-12 col-md-6 picture nopadding nocollapse">
                <?php if ( has_post_thumbnail() ){the_post_thumbnail();} // check if the post has a Post Thumbnail assigned to it.?>
            </div>

        </div>
    </div>
    <!-- END PAGE DESC -->


    <div class="nocollapse page-block">
        <div class="page-width">
            <?php if (is_active_sidebar('about_us')) : ?>
                <?php dynamic_sidebar('about_us'); ?>
            <?php endif; ?>
        </div>
    </div>


<script src="//localhost:35729/livereload.js"></script>

</div>
<?php get_footer(); ?>
<!-- TODO: put JS and resolve description on tablets -->
