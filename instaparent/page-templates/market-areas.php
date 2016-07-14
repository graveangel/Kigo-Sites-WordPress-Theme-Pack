<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
/*
Template Name: Market Area Page
*/

$pages = get_pages( array( 
		'child_of' 		=> $post->ID, 
		'sort_column' 	=> 'post_date', 
		'sort_order' 	=> 'desc'
	) 
);

//test($pages,1);

ob_start();
?>

<h1><?php echo the_title(); ?></h1>

<?php 
$props = true;
$loop = 1;
foreach( $pages as $page ) {		
	$data = json_decode(get_post_meta($page->ID, 'bapi_property_data')[0]);
	$children = get_pages( array( 'child_of' => $page->ID, 'sort_column' => 'post_date', 'sort_order' => 'desc' ) );
	if(count($children) && $props) { $props = false; }
	$title = get_post_meta($page->ID, 'bapi_last_update', true) == 0 ? ucwords(str_replace('-',' ', $page->post_title)) : $page->post_title;

	if($children || ($props && count($children) == 0)) {

		$pa = array();
		foreach($children as $prop){
			if($bapikey = get_post_meta($prop->ID,'bapikey')){
				$bk = explode(':',$bapikey[0]);
				if($bk[0]=='property'){
					$pa[] = $prop;
				}
			}
		}

		//test($pa);

		if($count = count($pa)) {
			$title .= " ($count)";
		}
?>
	<h2><a href="<?php echo $link = get_page_link( $page->ID ); ?>"><?php echo $title; ?></a></h2>
	<a href="<?php echo $link; ?>"><img src="<?php echo $data->PrimaryImage->ThumbnailURL; ?>" /></a>
<?php
	}
	$loop ++;
} ?>


<?php

$content = ob_get_clean();

?>
<?php get_header(); ?>
<article class="other-detail-page">	
<div class="row-fluid">

	<?php if ( is_active_sidebar( 'insta-left-sidebar-other' ) ) : ?>
    	<aside class="span3">		
           <?php dynamic_sidebar( 'insta-left-sidebar-other' ); ?>
        </aside>		
    <?php endif; ?>
    
	<?php if ( is_active_sidebar( 'insta-left-sidebar-other' ) ) : ?>
  		<article class="span6">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <?php echo $content; ?>
        <?php //the_content(); ?>
        <?php endwhile; else: ?>
            <p><?php _e('Sorry, this page does not exist.'); ?></p>
        <?php endif; ?>
      </article>
  	<?php else: ?>
	    <article class="span9">
			<?php /* The loop */ ?>
			
			<?php
				echo $content;
			?>

			<?php while ( have_posts() ) : the_post(); ?>
				<?php //the_content(); ?>
			<?php endwhile; ?>
        <?php endif; ?>
      </article>

      
  <aside class="span3">
  		<?php if ( is_active_sidebar( 'insta-right-sidebar-other' ) ) : ?>		
            <?php dynamic_sidebar( 'insta-right-sidebar-other' ); ?>		
        <?php endif; ?>
  </aside>
</div>

</article>
<?php get_footer(); ?>