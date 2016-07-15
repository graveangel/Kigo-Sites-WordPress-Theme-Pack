<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
/*
Template Name: Market Area Page
*/

ob_start();
?>

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

	$propList = true;
	foreach($pages as $page){
		if($bapikey = get_post_meta($page->ID, 'bapikey', true)){
			//print_r($bapikey);
			$bk = explode(':',$bapikey);
			if($bk[0]=='marketarea'){
				$propList = false;

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

				shuffle($props);

				$pa = array();
				foreach($props as $prop){
					if($bapikey = get_post_meta($prop->ID, 'bapikey', true)){
						$bk = explode(':',$bapikey);
						if($bk[0]=='property'){
							$pa[] = $prop;
						}
					}
				}


				$title = $page->post_title;
				if($count = count($pa)>0) { $title .= " (".count($pa).")"; }

				if(($count && wp_get_post_parent_id($page->ID)==get_the_ID()) )  {
					echo '<h2><a href="'.$page->guid.'">'.$title.'</a></h2>';
					?>
					<div class="row">
						<?php
						foreach($pa as $p){
						?>
							<div class="span3">
								<h4><?php echo $p->post_title ?></h4>
								<?php
								$pm = get_post_meta($p->ID);
								$pm = json_decode($pm['bapi_property_data'][0],true);
								?>
								<img src="<?php echo $pm['PrimaryImage']['ThumbnailURL'] ?>">
								<div class="clear"></div>	
							</div>
						<?php
						}
						?>
					</div>
					<p><br></p>
					<div class="clear"></div>	
					<?php
				}
			}

			if($bk[0]=='property' && $propList){
				$title = $page->post_title;
				echo '<h2><a href="'.$page->guid.'">'.$title.'</a></h2>';
				?>
				<div class="row">
					<div class="span3">
						<h4><?php echo $page->post_title ?></h4>
						<?php
						$pm = get_post_meta($page->ID);
						$pm = json_decode($pm['bapi_property_data'][0],true);
						?>
						<img src="<?php echo $pm['PrimaryImage']['ThumbnailURL'] ?>">
						<div class="clear"></div>	
					</div>
					<div class="span9">Details here</div>
				</div>
				<p><br></p>
				<div class="clear"></div>	
				<?php
			}
			
		}
		else{
			//error
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