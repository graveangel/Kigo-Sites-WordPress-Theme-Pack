<?php get_header(); the_post(); ?>
    <div class="page-width">
        <article class="page  row">
            <article class="col-md-9 col-xs-12">
                <div class="row">
                    <?php if(has_post_thumbnail()){ ?>
                    <div class="col-md-4 col-xs-12">
                        <div class="image"><?php the_post_thumbnail('original') ?></div>
                    </div>
                    <div class="col-md-8 col-xs-12">
                        <?php }else{ ?>

                        <div class="col-md-12 col-xs-12">
                            <?php } ?>
                            <h1 class="title"><?php the_title() ?></h1>
                            <div class="body"><?php the_content() ?></div>
                        </div>
                    </div>
            </article>

            <aside class="col-md-3 col-xs-12">
                <?php if ( is_active_sidebar( 'sidebar_page' ) ) : ?>
                    <?php dynamic_sidebar( 'sidebar_page' ); ?>
                <?php endif; ?>
            </aside>
        </article>
    </div>
<?php get_footer(); ?>
