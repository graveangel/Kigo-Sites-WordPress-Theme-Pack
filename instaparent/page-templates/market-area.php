<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
/*
Template Name: Market Area Page
*/

ob_start();
?>

<h1><?php echo __('Rentals in').' '.$post->post_title; ?></h1>
<?php
$pt = get_the_title($post->post_parent);
if(!empty($pt) && $post->post_parent!=0){
?>
<p class=""><a href="<?php echo get_permalink($post->post_parent) ?>"><< Back to <?php echo $pt; ?></a></p>
<?php
}
?>
<br>
<?php
	$pages = get_pages(
		array(
			'sort_order' => 'asc',
			'sort_column' => 'post_title',
			'hierarchical' => 1,
			'exclude' => '',
			'include' => '',
			'meta_key' => '',
			'meta_value' => '',
			'authors' => '',
			'child_of' => get_the_ID(),
			'parent' => -1,
			'exclude_tree' => '',
			'number' => '',
			'offset' => 0,
			'post_type' => 'page',
			'post_status' => 'publish'
		)
	);
	$c=0;

	foreach($pages as $page){
		
		if($bapikey = get_post_meta($page->ID,'bapikey')){
			//print_r($bapikey);
			$bk = explode(':',$bapikey[0]);
			if($bk[0]=='marketarea'){
				$c++;
				//print_r($page);

				$props = get_pages(
					array(
						'sort_order' => 'asc',
						'sort_column' => 'post_title',
						'hierarchical' => 1,
						'exclude' => '',
						'include' => '',
						'meta_key' => '',
						'meta_value' => '',
						'authors' => '',
						'child_of' => $page->ID,
						'parent' => -1,
						'exclude_tree' => '',
						'number' => '',
						'offset' => 0,
						'post_type' => 'page',
						'post_status' => 'publish'
					)
				);
				$pa = array();
				foreach($props as $prop){
					if($bapikey = get_post_meta($prop->ID,'bapikey',true)){
						$bk = explode(':',$bapikey);
						if($bk[0]=='property'){
							$pa[] = $prop;
						}
					}
				}

				

				if( count($pa)>0 && wp_get_post_parent_id($page->ID)==get_the_ID() ) {
					echo '<h2 style="margin:0"><a href="'.$page->guid.'">'.$page->post_title.'</a>  <i>';
					echo sprintf( _n('%d Property', '%d Properties', count($pa)), count($pa) );
					echo "</i></h2>";
					if(count($pa) > 3) {
						echo '<h4 style="margin:0 0 1em 0">';
						echo sprintf(__('Showing %d out of %s'), 3, '<a href="'.$page->guid.'">'.count($pa));
						echo "</a></h4>";
					}
					echo "</h3>";
					?>
					<div class="row-fluid">
						<?php
						$pa_count = 1;
						shuffle($pa);
						$pa = array_slice($pa,0,3); //show 3 random
						foreach($pa as $p){
						?>
							<div class="span4">
								<?php
								$pm = get_post_meta($p->ID);
								$pm = json_decode($pm['bapi_property_data'][0],true);
								?>
								<a href="<?php echo $pa->guid;?>">
									<img src="<?php echo $pm['PrimaryImage']['ThumbnailURL'] ?>">
									<h4><?php echo $p->post_title ?></h4>	
								</a>
							</div>
						<?php
							if($pa_count % 3 == 0) { echo '</div><br /><br /><div class="row-fluid">'; }
							$pa_count++;
						}
						?>
					</div>
					<p><br></p>
					<div class="clear"></div>	
					<?php
					
				}
			}
		}
		else{
			//error
		}
	}
	if($c==0){
		$pa = array();
		foreach($pages as $page){
			if($bapikey = get_post_meta($page->ID,'bapikey')){
				$bk = explode(':',$bapikey[0]);
				if($bk[0]=='property'){
					$pa[] = $page;
				}
			}
		}
		if(count($pa)>0 && wp_get_post_parent_id($page->ID)==get_the_ID()){
			echo '<h2>';
			echo sprintf( _n('%d Property in the %s area', '%d Properties in the %s area', count($pa)), count($pa), $post->post_title );
			echo '</h2>';
			?>
				<?php
				foreach($pa as $p){
				$pm = get_post_meta($p->ID);
				$pm = json_decode($pm['bapi_property_data'][0],true);
				?>
			<div class="row-fluid">
				<div style="margin-bottom: 2em; box-shadow: 0px 3px 5px #ccc;">
					<div class="" style="background:url('<?php echo $pm['PrimaryImage']['MediumURL'] ?>') no-repeat;background-size:cover; background-position:center;height:250px;padding:8px;position:relative;">
						<h3 style="background:rgba(250,250,250,0.7);display:inline-block;padding:4px;margin:0 0 8px 0;padding:8px;"><?php echo $p->post_title ?></h3>
						<div class="clear"></div>
						<p style="background:rgba(250,250,250,0.7);display:inline-block;padding:4px; font-weight: bold;"><?php echo $pm['ContextData']['Quote']['PublicNotes'] ?></p>
						<div class="clear"></div>	
					</div>
					<div style="margin-bottom:12px; background: #f3f3f3; display: flex; justify-content: center; align-items: center;">
						<p style="float: left; padding:4px;margin:0; font-weight:bold;"><?php echo $pm['Bedrooms'].' Bedrooms | '.$pm['Bathrooms'].' Baths | Sleeps '.$pm['Sleeps'] ?></p>
						<div class="" style="margin-left: auto; margin-right:4px; padding: 4px;">
							<a class="btn btn-default" href="<?php echo $pm['ContextData']['SEO']['DetailURL'] ?>">More Info</a> | <a class="btn btn-primary" href="<?php echo $pm['ContextData']['SEO']['BookingURL'] ?>">Book Now</a>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
				<?php
				}
				?>
			<?php
		}
	}

	$output = ob_get_clean();
?>

<?php get_header(); ?>
<article class="market-area-page">	
<div class="row-fluid">

	<?php if ( is_active_sidebar( 'insta-left-sidebar-other' ) ) : ?>
    	<aside class="span3">		
           <?php dynamic_sidebar( 'insta-left-sidebar-other' ); ?>
        </aside>		
    <?php endif; ?>
    
	<?php if ( is_active_sidebar( 'insta-left-sidebar-other' ) ) : ?>
  		<article class="span6">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	        <?php //the_content(); ?>
	        <?php echo $output; ?>
        <?php endwhile; else: ?>
            <p><?php _e('Sorry, this page does not exist.'); ?></p>
        <?php endif; ?>
      </article>
  	<?php else: ?>
	    <article class="span9">
        	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		        <?php //the_content(); ?>
				<?php echo $output; ?>
        	<?php endwhile; else: ?>
            <p><?php _e('Sorry, this page does not exist.'); ?></p>
        <?php endif; ?>
      </article>
    <?php endif; ?>    	
		
      
  <aside class="span3">
  		<?php if ( is_active_sidebar( 'insta-right-sidebar-other' ) ) : ?>		
            <?php dynamic_sidebar( 'insta-right-sidebar-other' ); ?>		
        <?php endif; ?>
  </aside>
</div>

</article>
<?php get_footer(); ?>