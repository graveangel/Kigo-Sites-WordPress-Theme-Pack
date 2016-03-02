<?php
/**
 * Adds BAPI_Weather widget.
 */
class KD_BAPI_Weather_Widget extends KD_Widget {

	public function __construct() {
		WP_Widget::__construct(
	 		'kd_bapi_weather_widget', // Base ID
			'KD Weather', // Name
			array( 'description' => __( 'KD Weather', 'text_domain' ), ) // Args
		);
	}

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
				extract($args);
				$title = apply_filters('widget_title',$instance['title']);
				$woid = esc_textarea($instance['text']);
				$unit = $instance['unit'];
				if(empty($woid)) return;
				if(empty($unit)){
					$unit = 'f';
				}
				echo $before_widget;
				if(!empty($title))
					echo $before_title . "<span class='glyphicons brightness_increase'><i></i>" . $title . "</span>" . $after_title;
				?>
		        <div id="weather-widget"></div>
				<script>
					$(document).ready(function () {
						// weather widget uses code found here: http://www.zazar.net/developers/jquery/zweatherfeed/
						// lookup woid here: http://woeid.rosselliot.co.nz/
						var woid = '<?= $woid ?>';
						var sTemperatureUnit = '<?= $unit ?>';
						if (woid!='') {
							if (sTemperatureUnit == null || sTemperatureUnit == '' && BAPI.defaultOptions.language=="en-US") { sTemperatureUnit = 'f'; }
							BAPI.UI.createWeatherWidget('#weather-widget', ['<?= $woid ?>'], { "link": false, "woeid": true, "unit": sTemperatureUnit });
						}

					});
		        </script>
						<?php
				echo '</div>';
	}

	public function update( $new_instance, $old_instance ) {
		$new_instance['title'] = strip_tags( $new_instance['title'] );
		if ( !current_user_can('unfiltered_html') )
			$new_instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed

		return $new_instance;
	}

	public function form( $instance ) {
        $controls = $this->buildForm($instance, true);
            $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
                echo $controls;

		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Weather', 'text_domain' );
		}
		if ( isset( $instance[ 'text' ] ) ) {
			$woid =  esc_textarea($instance['text']);
		}
		else {
			$woid = __( '2450022', 'text_domain' );
		}
		if ( isset( $instance[ 'unit' ] ) ) {
			$unit =  $instance['unit'];
		}
		else {
			$unit = 'f';
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'WOID:' ); ?></label>
        <input id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="<?php echo esc_attr( $woid ); ?>" />
        <br/>
        <small><a href="//woeid.rosselliot.co.nz/lookup/" target="_blank">Lookup WOID</a></small>
		<div class="clear"></div>
		<label for="<?php echo $this->get_field_id( 'unit' ); ?>">Unit</label>
		<select id="<?php echo $this->get_field_id( 'unit' ); ?>" name="<?php echo $this->get_field_name( 'unit' ); ?>">
			<option value="f" <?php if($unit=='f') echo 'selected'; ?>>Farenheit</option>
			<option value="c" <?php if($unit=='c') echo 'selected'; ?>>Celcius</option>
		</select>
		</p>
		<?php
	}

} // class BAPI_Weather_Widget

add_action('widgets_init', function() {
		unregister_widget('BAPI_Weather_Widget');
		register_widget('KD_BAPI_Weather_Widget');
});
 ?>
