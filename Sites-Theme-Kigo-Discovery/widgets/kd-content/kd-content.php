<?php
/* ===========================================================================
Content widget
=============================================================================*/

class Content_Widget extends KD_Widget {
  /**
  * Sets up the widgets name etc
  */
  public function __construct() {
    // widget actual processes
    WP_Widget::__construct(
    'kd_content', // Base ID
    __( 'KD Content', 'smtm' ), // Name
    array( 'description' => __( 'Display a post\'s content.', 'smtm' ), ) // Args
  );
}



  /**
     * Display a widget on the frontend using it's template.
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        $class = '';

        $desktopSizes = json_decode($instance['sizeDesktop']);
        $desktopAlignment = $instance['deskopAlignment'];
        $dCols = floor($desktopSizes[1]);
        $desktopCols = $dCols ?  : 12;

        $mobileSizes = json_decode($instance['sizeMobile']);
        $mobileAlignment = $instance['mobileAlignment'];
        $mCols = floor($mobileSizes[1]);
        $mobileCols = $mCols ?  : 12;

        $alignments = (empty($instance['deskopAlignment']) ? '' : ' kd-align-lg-'.$desktopAlignment) . (empty($instance['mobileAlignment']) ? '' :  ' kd-align-xs-'. $mobileAlignment);

        $class .= ' col-xs-'.$mobileCols.' col-lg-'.$desktopCols . $alignments;

        echo '<div class="'.$class.'">';
        // outputs the content of the widget
        $posts = $GLOBALS['posts'];
            ?>

              <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <div class="the-content">
                <?php
                    if(count($posts) === 1){
                      ?><h2><?php the_title(); ?></h2><?php
                      the_content();
                    }else {
                      ?><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><?php
                      the_excerpt();
                    }
                ?>
                </div>
              <?php endwhile; else : ?>
                <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
              <?php endif; ?>

          <?php
        echo '</div>';
    }

}

// register Content_Widget widget
function register_content_widget() {
  register_widget( 'Content_Widget' );
}
add_action( 'widgets_init', 'register_content_widget' );



