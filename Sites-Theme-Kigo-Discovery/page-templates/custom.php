<?php //Template Name: Custom ?>
<?php get_header() ?>

    <div class="page-width">

        <div class="row">
            <?php
            $assignedSidebar = get_post_meta(get_the_ID(), '_custom_sidebar', true);

            if ( is_active_sidebar( $assignedSidebar ) ) {
                dynamic_sidebar($assignedSidebar);
            }
            ?>
        </div>


    </div>

<?php get_footer() ?>