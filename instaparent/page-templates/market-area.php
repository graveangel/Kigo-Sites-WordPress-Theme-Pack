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
						shuffle($props);
						$pa = array();
						foreach($props as $prop){
							if($bapikey = get_post_meta($prop->ID,'bapikey')){
								$bk = explode(':',$bapikey[0]);
								if($bk[0]=='property'){
									$pa[] = $prop;
								}
							}
						}
						if($count = count($pa)>0 && wp_get_post_parent_id($page->ID)==get_the_ID()){
							$title = $page->post_title;
							if($count) { $title .= " (".$count.")"; }
							echo '<h2><a href="'.$page->guid.'">'.$title.'</a></h2>';
							?>
							<div class="row">
								<?php
								$pa_count = 1;
								foreach($pa as $p){
								?>
									<div class="span3">
										<?php
										$pm = get_post_meta($p->ID);
										$pm = json_decode($pm['bapi_property_data'][0],true);
										?>
										<img src="<?php echo $pm['PrimaryImage']['ThumbnailURL'] ?>">
										<h4><?php echo $p->post_title ?></h4>	
									</div>
									<?php if($pa_count % 4 == 0) { echo '</div><br /><br /><div class="row">'; }
									$pa_count ++; ?>
								<?php
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
						?>
					<div class="row">
							<div class="span8">
								<h4><?php echo $p->post_title ?></h4>
								<?php
								$pm = get_post_meta($p->ID);
								$pm = json_decode($pm['bapi_property_data'][0],true);
								?>
								<p><?php echo $pm['ContextData']['Quote']['PublicNotes'] ?></p>
								<img src="<?php echo $pm['PrimaryImage']['MediumURL'] ?>" alt="">
								<div class="clear"></div>	
							</div>
					</div>
						<?php
						}
						?>
					<p><br></p>
					<div class="clear"></div>	
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