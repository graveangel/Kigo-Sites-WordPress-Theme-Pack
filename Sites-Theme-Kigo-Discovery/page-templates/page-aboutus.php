<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
/*
Template Name: About us Page
*/ ?>
<?php get_header(); ?>
<div class="about-us-template">

    <div class="kd-custom-hero nocollapse page-block" style="<?php echo get_theme_mod("about-hero", false) ? 'background-image:url('.get_theme_mod("about-hero").')' : ''; ?>">
        <div class="page-width">
            <h1 class="page-title">
                <?php echo get_theme_mod("about-title"); ?>
            </h1>
            <div class="subtitle col-lg-4 col-lg-offset-4 col-xs-12">
                <?php echo get_theme_mod("about-subtitle"); ?>
            </div>
        </div>
    </div>

    <div class="page-desc nocollapse page-block">
        <div class="page-width">
            <div class="title col-xs-12"><?php the_title(); ?></div>
            <div class="row row-nopadding">
                <div class="col-sm-12 col-md-6">
                    <div class="picture col-sm-12">
                        <?php
                        // check if the post has a Post Thumbnail assigned to it, or fallback to default
                        if (has_post_thumbnail()){
                            the_post_thumbnail();
                        }else{
                            echo '<img src="'.get_theme_mod("about-hero", false).'" />';
                        }
                        ?>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="description">
                        <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
                            the_content();
                        endwhile; else: ?>
                            <p>Sorry, no posts matched your criteria.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="nocollapse page-block">
        <div class="page-width">
            <?php if (is_active_sidebar('page_about_us')) : ?>
                <?php dynamic_sidebar('page_about_us'); ?>
            <?php endif; ?>
        </div>
    </div>

</div>
<?php get_footer(); ?>
