<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
/*
Template Name: Market Area Page
*/
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
        <?php endwhile; else: ?>
            <p><?php _e('Sorry, this page does not exist.'); ?></p>
        <?php endif; ?>
      </article>
  	<?php else: ?>
	    <article class="span9">
        	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <?php //the_content(); ?>
			<h1>Rentals in <?php echo $post->post_title; ?></h1>
		<?php
			// $urls = array();  
			// $xml= wp_remote_get(get_site_url().'/sitemap.xml');
			// $xml= $xml['body'];
			// $DomDocument = new DOMDocument();
			// $DomDocument->preserveWhiteSpace = false;
			// $DomDocument->loadXML("$xml"); // $DOMDocument->load('filename.xml');
			// $DomNodeList = $DomDocument->getElementsByTagName('loc');
			
			// //print_r($DomNodeList);exit();			
			// foreach($DomNodeList as $url) {
				// wp_remote_get( $url->nodeValue );
			// }
		
			$args = array(
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
			);
			$pages = get_pages($args);
			$c=0;

			foreach($pages as $page){
				
				if($bapikey = get_post_meta($page->ID,'bapikey')){
					//print_r($bapikey);
					$bk = explode(':',$bapikey[0]);
					if($bk[0]=='marketarea'){
						$c++;
						//print_r($page);
						$args = array(
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
						);
						$props = get_pages($args);
						$pa = array();
						foreach($props as $prop){
							if($bapikey = get_post_meta($prop->ID,'bapikey')){
								$bk = explode(':',$bapikey[0]);
								if($bk[0]=='property'){
									$pa[] = $prop;
								}
							}
						}

						

						if( count($pa)>0 && wp_get_post_parent_id($page->ID)==get_the_ID() ) {
							echo '<h2><a href="'.$page->guid.'">'.$page->post_title.' ('.count($pa).' properties)</a></h2>';
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
										<img src="<?php echo $pm['PrimaryImage']['ThumbnailURL'] ?>">
										<h4><?php echo $p->post_title ?></h4>	
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
					echo '<h2>'.count($pa).' properties in the '.$post->post_title.' area</a></h2>';
					?>
						<?php
						foreach($pa as $p){
						$pm = get_post_meta($p->ID);
						$pm = json_decode($pm['bapi_property_data'][0],true);
						?>
					<div class="row-fluid">
							<div class="" style="background:url('<?php echo $pm['PrimaryImage']['MediumURL'] ?>') no-repeat;background-size:100%;background-position:center;height:250px;padding:8px;margin-bottom:12px;position:relative;">
								<h3 style="background:rgba(250,250,250,0.7);display:inline-block;padding:4px;position:absolute;top:0;left:0;margin:0;padding:8px;"><?php echo $p->post_title ?></h3>
								<div class="clear"></div>
								<p style="background:rgba(250,250,250,0.7);display:inline-block;padding:4px;position:absolute;top:60px;left:0;"><?php echo $pm['ContextData']['Quote']['PublicNotes'] ?></p>
								<div class="clear"></div>
								<p style="background:rgba(250,250,250,0.7);display:inline-block;padding:4px;position:absolute;bottom:0;left:0;margin:0;padding:8px;"><?php echo $pm['Bedrooms'].' Bedrooms | '.$pm['Bathrooms'].' Baths | Sleeps '.$pm['Sleeps'] ?></p>
								<div class="clear"></div>
								<div class="" style="position:absolute;bottom:8px;right:8px">
									<a class="btn btn-primary" href="<?php echo $pm['ContextData']['SEO']['DetailURL'] ?>">More Info</a> | <a class="btn btn-primary" href="<?php echo $pm['ContextData']['SEO']['BookingURL'] ?>">Book Now</a>
								</div>
								<div class="clear"></div>	
							</div>
					</div>
						<?php
						}
						?>
					<?php
				}
			}
		?>
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