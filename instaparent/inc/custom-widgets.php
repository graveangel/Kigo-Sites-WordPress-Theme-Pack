<?php

/*----------------------- Insta custom widgets ----------------------------------*/

/*
*
* Latest Blog Post Widget
* 
*/
class Insta_Latest_Blog_Posts extends WP_Widget {	
	
	/**
	 * Register widget with WordPress.
	 */
    public function __construct() {
		parent::__construct(
	 		'insta_latest_blog_posts', // Base ID
			'Kigo Latest Blog Posts', // Name
			array( 'description' => __( 'Kigo Latest Blog Posts', 'text_domain' ), ) // Args
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
	public function widget( $args, $instance ) {
		extract($args);
		/* we get the title */
		$title = apply_filters('widget_title',$instance['title']);
		/* we get the number of posts */
		$numberOfPosts = @$instance['numberOfPosts'];
		/* we get the number of rows */
		$rowSize = @$instance['rowSize'] < 1 ? 1 : @$instance['rowSize'];
		/* Do we display images? */
		$bDisplayImage =  @$instance['displayImage'];
		/* Do we display the date? */		
		$bDisplayDate =  @$instance['displayDate'];
		/* we get the format of the date */
		$sDateFormat =  @$instance['dateFormat'];
		/* Do we display the title?*/
		$bDisplayTitle =  @$instance['displayTitle'];
		/* we get the string for the read more link */
		$sPostLink =  trim(@$instance['postLinkString']);
		/* we calculate the number of post for each row, we round the result so we dont get decimal values, this value cant be below 1 */
		$numberOfPostsForEachRow = ceil($numberOfPosts / $rowSize);
                $numberOfPostsForEachRow = $numberOfPostsForEachRow < 1 ? 1 : $numberOfPostsForEachRow;
		/* we calculate the number of the column this value can only be multiples of 12 (12,6,4,3,2,1) cant be a decimal and it cant be greater than 12 or less than 1*/				
		$spanValue = ceil(12/$numberOfPostsForEachRow);
		if($spanValue > 12)
		{$spanValue = 12;}
		if($spanValue < 1)
		{$spanValue = 1;}
		
		echo $before_widget;
		if(!empty($title))
			echo $before_title.'<span class="glyphicons pen"><i></i>'.$title.'</span>'.$after_title;	
				
		?>

<?php 
/* we do the query to retun a number of posts based on the captured value */
$the_query = new WP_Query( 'showposts='.$numberOfPosts );
/* We count the number of outputs we are doing */
$numPostsWeOutputted = 0;
/* We count the number of rows */
$numRowsWeOutputted = 1;
/* we output the start of the first row */
echo '<div class="row-fluid row-0">';
/* we start looping the posts*/
while ($the_query -> have_posts()) : $the_query -> the_post();
/* we start outputing each post with his respective classname, here we add the bootstrap column number we calculated before */
?>
<div class="span<?php echo $spanValue?> post-<?php echo $numPostsWeOutputted?>" >
<div class="post-block">
<?php
/* we output the featured image if the post has one and if the widget was set to display images */
if ( $bDisplayImage && has_post_thumbnail()  ) {
	echo '<div class="post-image"><a href="'. get_permalink(@$post->ID) . '">';
	the_post_thumbnail();
	echo '</a></div>';
}

/* we display the post excerpt */
?>
  <div class="post-excerpt">
  <?php
  /* we display the date of the post */
	if($bDisplayDate){
		 echo '<h5 class="post-date">'.get_the_date($sDateFormat).'</h5>';
	}
	/* we display the title of the post */
	 if($bDisplayTitle){
		 echo '<h4 class="post-title"><a href="'. get_permalink(@$post->ID) . '">'.get_the_title().'</a></h4>';
	} 
	?>
  <p>
    <?php
	/* we get the excerpt */ 
	$subject = get_the_excerpt();
	/* if a link text was specified, we replace the default string for a link to the full post */
	  if($sPostLink != '')
	  {
		  /* we get the position of the default string*/
	  	$pos = strrpos($subject,'[...]');
		/* it will be a number if it was found, false otherwise */
		  if($pos !== false)
		  {
			  /* we replace the default string */	  
			  $subject = substr_replace($subject, ' <a class="full-post-link" href="'. get_permalink(@$post->ID) . '">'.$sPostLink.'</a>', $pos, strlen('[...]'));
		  }
	  }
	/* we output the excerpt */	
    echo $subject;	
	?>
    </p>
  </div>
  </div>
</div>
<?php
/* we increment our counter to keep track of the post we already outputted */
$numPostsWeOutputted ++;
/* we check if we outputted all the post specified in the widget */
if($numPostsWeOutputted != $numberOfPosts)
{
	/* we check we outputted the number of post for each row */
	if($numPostsWeOutputted == $numberOfPostsForEachRow * $numRowsWeOutputted)
	{
		/* we output the cloding of the row and start a new one */
		echo '</div><div class="row-fluid row-'.$numRowsWeOutputted.'">';
		/* we keep track of the number of rows we outputted*/
		$numRowsWeOutputted++;
	}
}

endwhile;
/* we close the last row */
echo '</div>';
?>

<?php
		echo $after_widget;
	}
	
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		// the title
		if ( isset( $instance[ 'title' ] ) ) { $title = esc_attr($instance[ 'title' ]); }
		else { $title = 'Latest Blog Posts'; }
		// the number of posts
		if ( isset( $instance[ 'numberOfPosts' ] ) && !empty($instance[ 'numberOfPosts' ])) { 
			$numberOfPosts =  esc_attr($instance['numberOfPosts']); 
		}else { $numberOfPosts = '1'; }
		
		// the link to the full post
		if ( isset( $instance[ 'postLinkString' ] ) && !empty($instance[ 'postLinkString' ])) { 
			$sPostLink =  esc_attr($instance['postLinkString']); 
		}
		
		// the number of rows
		if ( isset( $instance[ 'rowSize' ] ) && !empty($instance[ 'rowSize' ]) ) { $rowSize =  esc_attr($instance['rowSize']); }
		else { $rowSize = '1'; }
		
		// Show image checkbox
		if ( isset( $instance[ 'displayImage' ] ) ) { $bDisplayImage = esc_attr($instance[ 'displayImage' ]); }
		else{ $bDisplayImage = false;}
		
		// Show date checkbox
		if ( isset( $instance[ 'displayDate' ] ) ) { $bDisplayDate = esc_attr($instance[ 'displayDate' ]); }
		else{ $bDisplayDate = false;}
		
		// Show date format input
		if ( isset( $instance[ 'dateFormat' ] ) && !empty($instance[ 'dateFormat' ]) ) { $sDateFormat = esc_attr($instance[ 'dateFormat' ]); }	
		else { $sDateFormat = 'F, Y'; }
			
		// Show title checkbox
		if ( isset( $instance[ 'displayTitle' ] ) ) { $bDisplayTitle = esc_attr($instance[ 'displayTitle' ]); }
		else{ $bDisplayTitle = false;}
				
		?>
<p>
  <label for="<?php echo $this->get_field_id( 'title' ); ?>">
    <?php _e( 'Title:' ); ?>
  </label>
  <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'postLinkString' ); ?>">
    <?php _e( 'Text Link for Full Post:' ); ?>
  </label>
  <input class="widefat" id="<?php echo $this->get_field_id( 'postLinkString' ); ?>" name="<?php echo $this->get_field_name( 'postLinkString' ); ?>" type="text" value="<?php echo esc_attr( $sPostLink ); ?>" />
  <br/>
  <small>If left blank it will default to [...]</small>
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'numberOfPosts' ); ?>">
    <?php _e( '# of posts:' ); ?>
  </label>
  <input id="<?php echo $this->get_field_id( 'numberOfPosts' ); ?>" name="<?php echo $this->get_field_name( 'numberOfPosts' ); ?>" type="text" value="<?php echo esc_attr( $numberOfPosts ); ?>" size="2" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'rowSize' ); ?>">
  <?php _e( '# of Rows:' ); ?>
</label>
<input id="<?php echo $this->get_field_id( 'rowSize' ); ?>" name="<?php echo $this->get_field_name( 'rowSize' ); ?>" type="text" value="<?php echo esc_attr( $rowSize ); ?>" size="2" />
</p>

<p>
<input id="<?php echo $this->get_field_id('displayImage'); ?>" name="<?php echo $this->get_field_name('displayImage'); ?>" class="checkbox" type="checkbox" value="1" <?php checked( '1', $bDisplayImage ); ?>/>
<label for="<?php echo $this->get_field_id( 'displayImage' ); ?>">
  <?php _e( 'Display Post Image?' ); ?>
</label>
</p>
<p>
<input id="<?php echo $this->get_field_id('displayTitle'); ?>" name="<?php echo $this->get_field_name('displayTitle'); ?>" class="checkbox" type="checkbox" value="1" <?php checked( '1', $bDisplayTitle ); ?>/>
<label for="<?php echo $this->get_field_id( 'displayTitle' ); ?>">
  <?php _e( 'Display Post Title?' ); ?>
</label>
</p>
<p id="<?php echo $this->get_field_id('displayDate'); ?>-block">
<input id="<?php echo $this->get_field_id('displayDate'); ?>" name="<?php echo $this->get_field_name('displayDate'); ?>" class="checkbox" type="checkbox" value="1" <?php checked( '1', $bDisplayDate ); ?>/>
<label for="<?php echo $this->get_field_id( 'displayDate' ); ?>">
  <?php _e( 'Display Post Date?' ); ?>
</label>
</p>
<div id="<?php echo $this->get_field_id('displayDate'); ?>-format-block" <?php if(!$bDisplayDate){echo 'style="display:none;"';} ?>>
<p>
  <label for="<?php echo $this->get_field_id( 'dateFormat' ); ?>">
    <?php _e( 'Date Format:' ); ?>
  </label>
  <input class="widefat" id="<?php echo $this->get_field_id( 'dateFormat' ); ?>" name="<?php echo $this->get_field_name( 'dateFormat' ); ?>" type="text" value="<?php echo esc_attr( $sDateFormat ); ?>" />
  <br/>
  <small>Format Characters</small>
</p>
<div class="insta-lbp-date-help-block">
<ul class="ul-square">
<li><small>l = Full name for day of the week (lower-case L).</small></li>
<li><small>F = Full name for the month.</small></li>
<li><small>j = The day of the month.</small></li>
<li><small>Y = The year in 4 digits. (lower-case y gives the year's last 2 digits)</small></li>
</ul>
<p><small>(Commas are read literally.)</small></p>
</div>
</div>

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
		$instance = array();
		$instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		
		/* we check if the value is a number */
		if(is_numeric(strip_tags($new_instance['numberOfPosts'])))
		{
			$instance['numberOfPosts'] = strip_tags($new_instance['numberOfPosts']);
		}else{
			$instance['numberOfPosts'] = '';
		}
		
		/* we check if the value is a number */
		if(is_numeric(strip_tags($new_instance['rowSize'])))
		{
			$instance['rowSize'] =  strip_tags($new_instance['rowSize']);
		}else{
			$instance['rowSize'] = '';
		}
		
		$instance['displayImage'] =  strip_tags($new_instance['displayImage']);
		$instance['displayDate'] =  strip_tags($new_instance['displayDate']);
		
		$instance['dateFormat'] =  trim($new_instance['dateFormat']);
		
		$instance['displayTitle'] =  strip_tags($new_instance['displayTitle']);		
		$instance['postLinkString'] =  strip_tags($new_instance['postLinkString']);
		
		return $instance;
	}

}


class Kigo_Social_Icons_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'kigo_social_icons', // Base ID
			'Kigo Social Icons', // Name
			array( 'description' => __( 'Displays the Social Media Icons filled in the Customizer', 'instaparent' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract($args);
		echo $before_widget;
		?>
                <ul class="inline">
                    <?php if(get_theme_mod('url-facebook')):?>
                        <li><a href="<?php echo get_theme_mod('url-facebook'); ?>" target="_blank" title="Facebook"><i class="fa fa-facebook"></i></a></li>
                    <?php endif; ?>

                    <?php if(get_theme_mod('url-twitter')):?>
                        <li><a href="<?php echo get_theme_mod('url-twitter'); ?>" target="_blank" title="Twitter"><i class="fa fa-twitter"></i></a></li>
                    <?php endif; ?>

                    <?php if(get_theme_mod('url-google')):?>
                        <li><a href="<?php echo get_theme_mod('url-google'); ?>" target="_blank" title="Google plus"><i class="fa fa-google-plus"></i></a></li>
                    <?php endif; ?>

                    <?php if(get_theme_mod('url-linkedin')):?>
                        <li><a href="<?php echo get_theme_mod('url-linkedin'); ?>" target="_blank" title="Linkedin"><i class="fa fa-linkedin"></i></a></li>
                    <?php endif; ?>

                    <?php if(get_theme_mod('url-youtube')):?>
                        <li><a href="<?php echo get_theme_mod('url-youtube'); ?>" target="_blank" title="Youtube"><i class="fa fa-youtube"></i></a></li>
                    <?php endif; ?>

                    <?php if(get_theme_mod('url-pinterest')):?>
                        <li><a href="<?php echo get_theme_mod('url-pinterest'); ?>" target="_blank" title="Pinterest"><i class="fa fa-pinterest"></i></a></li>
                    <?php endif; ?>

                    <?php if(get_theme_mod('url-instagram')):?>
                        <li><a href="<?php echo get_theme_mod('url-pinterest'); ?>" target="_blank" title="Pinterest"><i class="fa fa-instagram"></i></a></li>
                    <?php endif; ?>

                    <?php if(get_theme_mod('url-blog')):?>
                        <li><a href="<?php echo get_theme_mod('url-blog'); ?>" target="_blank" title="Blog"><i class="fa fa-rss"></i></a></li>
                    <?php endif; ?>
                </ul>
		<?php
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		return $instance;
	}
        
        public function form( $instance ) {
		?>
		<p class="no-options-widget">There are no options for this widget. To update your icon settings please go to the Appearance > Customize section and click Social Icons.</p>
		<?php 
	}

} // class BAPI_DetailOverview_Widget

/**
 * Navigation Menu widget class
 *
 * @since 3.0.0
 */
 class Kigo_Nav_Menu_Widget extends WP_Widget {

	public function __construct() {
		$widget_ops = array( 'description' => __('Add a custom menu to your sidebar.') );
		parent::__construct( 'kigo_custom_menu', __('Kigo Custom Menu'), $widget_ops );
	}

	/**
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// Get menu
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;
                
                $menu_direction = empty( $instance['menu_direction'] ) ? 'dir_horizontal' : $instance['menu_direction'];

		if ( !$nav_menu )
			return;

		/** This filter is documented in wp-includes/default-widgets.php */
		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];

		if ( !empty($instance['title']) )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		$nav_menu_args = array(
			'fallback_cb' => '',
			'menu'        => $nav_menu,
                        'menu_class'      => $menu_direction,
		);

		/**
		 * Filter the arguments for the Custom Menu widget.
		 *
		 * @since 4.2.0
		 *
		 * @param array    $nav_menu_args {
		 *     An array of arguments passed to wp_nav_menu() to retrieve a custom menu.
		 *
		 *     @type callback|bool $fallback_cb Callback to fire if the menu doesn't exist. Default empty.
		 *     @type mixed         $menu        Menu ID, slug, or name.
		 * }
		 * @param stdClass $nav_menu      Nav menu object for the current menu.
		 * @param array    $args          Display arguments for the current widget.
		 */
		wp_nav_menu( apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args ) );

		echo $args['after_widget'];
	}

	/**
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		}
		if ( ! empty( $new_instance['nav_menu'] ) ) {
			$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		}
                if ( in_array( $new_instance['menu_direction'], array( 'dir_horizontal', 'dir_vertical' ) ) ) {
			$instance['menu_direction'] = $new_instance['menu_direction'];
		} else {
			$instance['menu_direction'] = 'dir_horizontal';
		}
		return $instance;
	}

	/**
	 * @param array $instance
	 */
	public function form( $instance ) {
                //Defaults
		$instance = wp_parse_args( (array) $instance, array( 'menu_direction' => 'dir_horizontal'));
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

		// Get menus
		$menus = wp_get_nav_menus();

		// If no menus exists, direct the user to go and create some.
		?>
                <p>
			<label for="<?php echo $this->get_field_id('menu_direction'); ?>"><?php _e( 'Menu Direction:' ); ?></label>
			<select name="<?php echo $this->get_field_name('menu_direction'); ?>" id="<?php echo $this->get_field_id('sortby'); ?>" class="widefat">
				<option value="dir_horizontal"<?php selected( $instance['menu_direction'], 'dir_horizontal' ); ?>><?php _e('Horizontal'); ?></option>
<!--				<option value="dir_vertical"<?php selected( $instance['menu_direction'], 'dir_vertical' ); ?>><?php _e('Vertical'); ?></option>-->
			</select>
		</p>
		<p class="nav-menu-widget-no-menus-message" <?php if ( ! empty( $menus ) ) { echo ' style="display:none" '; } ?>>
			<?php
			if ( isset( $GLOBALS['wp_customize'] ) && $GLOBALS['wp_customize'] instanceof WP_Customize_Manager ) {
				$url = 'javascript: wp.customize.panel( "nav_menus" ).focus();';
			} else {
				$url = admin_url( 'nav-menus.php' );
			}
			?>
			<?php echo sprintf( __( 'No menus have been created yet. <a href="%s">Create some</a>.' ), esc_attr( $url ) ); ?>
		</p>
		<div class="nav-menu-widget-form-controls" <?php if ( empty( $menus ) ) { echo ' style="display:none" '; } ?>>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ) ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'nav_menu' ); ?>"><?php _e( 'Select Menu:' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'nav_menu' ); ?>" name="<?php echo $this->get_field_name( 'nav_menu' ); ?>">
					<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
					<?php foreach ( $menus as $menu ) : ?>
						<option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $nav_menu, $menu->term_id ); ?>>
							<?php echo esc_html( $menu->name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
		</div>
		<?php
	}
}

/*-------------------------------------------------------------------------------------*/