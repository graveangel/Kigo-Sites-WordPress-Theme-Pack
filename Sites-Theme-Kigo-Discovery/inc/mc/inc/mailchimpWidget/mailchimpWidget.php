<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * Custom Carousel Slider widget
 * Author: Jairo E. Vengoechea Rueda
 *
 * This widget inserts a carousel slider with the HTML elements inserted in each slide.
 */
class Mailchimp_Widget extends KD_Widget
{
  /**
	 * Register widget with WordPress.
	 */
	function __construct()
  {
		parent::__construct(
			'kd_mailchimp_widget', // Base ID
			__( 'Mailchimp', 'text_domain' ), // Name
			array( 'description' => __( 'this widget inserts the mailchimp form.', 'text_domain' ), ) // Args
		);
	}



  /**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance )
  {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}

		/*==============================================================
    Widget starts from here.
    ===============================================================*/
		echo "<div class='widget-mailchimp' id='" . $this->get_field_id( 'mailchimp_newsletter' ) . "'>";
    get_kigoaddon_mailchimp_form(true);
		echo "</div>";

		/*==============================================================
    Widget ends here.
    ===============================================================*/
		echo $args['after_widget'];
	}

    /**
  	 * Back-end widget form.
  	 *
  	 * @see WP_Widget::form()
  	 *
  	 * @param array $instance Previously saved values from database.
  	 */
  	public function form( $instance ) {
			$controls = $this->buildForm($instance, true);
  		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
			echo $controls;
      ?>
  		<p>
  		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
  		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
  		</p>
  		<?php

  	}


		/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $new_instance;
		return $instance;
	}


}

// register Mailchimp_Widget widget
function register_mailchimp_widget() {
    register_widget( 'Mailchimp_Widget' );
}
add_action( 'widgets_init', 'register_mailchimp_widget' );
