<?php get_header() ?>

    <div class="page-width">

        <div class="row row-no-padding">
        <?php if ( is_active_sidebar( 'page_home' ) ) : ?>
                <?php dynamic_sidebar( 'page_home' ); ?>
        <?php endif; ?>
        </div>

    </div>

<?php get_footer() ?>
