<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
/*
Template Name: Search Page
*/

if($using_new_stuff) {
  $view = isset($_GET['view']) ? $_GET['view'] : 'list';

  $options = $_GET ? $_GET : array();

  global $bapi_all_options; 
  $config = unserialize( $bapi_all_options['bapi_sitesettings_raw'] );
  $textdata = getbapitextdata();

  $bapi = getBAPIObj(); 
  $options = $_GET;
  $page = isset($_GET['offset']) ? $_GET['offset'] : 0;
  $perpage = 5;

  $all = $bapi->search('property', $options)['result'];

  $ids = array_slice($all, $page*$perpage, $perpage);

  $results = $bapi->get('property', $ids)['result'];


  //Get SEO data
  if( empty($seo = get_transient('kigo_seo_data') ) || KIGO_DEBUG ) {
    $seo = $bapi->getseodata()['result']; 

    set_transient('kigo_seo_data', $seo, HOUR_IN_SECONDS);
  }

  //Parse and assign SEO data to be refereneced by key
  foreach($seo as $key => $value) {
    $seo[$value['pkid']] = $value;
    unset($seo[$key]);
  } 

  if(!is_array($_SESSION['mylist'])) { $_SESSION['mylist'] = array(); }


  function get_pager($dir='next',$amt=1) {
    global $page;
    $get = $_GET;

    if($dir == 'prev') { $amt *= -1; }

    $get['offset'] = ($page + $amt);

    return http_build_query($get);
  }

  ob_start();

  ?>


  

  $output = ob_get_clean(); 
} else {
  $output = '';
}
?>

<?php get_header(); ?>
<article class="search-page">	
<div class="row-fluid">

  <?php if ( is_active_sidebar( 'insta-left-sidebar-search' ) ) : ?>
      <aside class="span3">  	
          <?php dynamic_sidebar( 'insta-left-sidebar-search' ); ?>
      </aside>
  <?php endif; ?>
  
  <?php if ( is_active_sidebar( 'insta-left-sidebar-search' ) && is_active_sidebar( 'insta-right-sidebar-search' )  ) : ?>
  	<article class="span6">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <?php echo $output; ?>
      <?php the_content(); ?>
    <?php endwhile; else: ?>
		<p><?php _e('Sorry, this page does not exist.'); ?></p>
	<?php endif; ?>
  </article>
  <?php else: ?>
  	<?php if ( !is_active_sidebar( 'insta-left-sidebar-search' ) && !is_active_sidebar( 'insta-right-sidebar-search' )  ) : ?>
        <article class="span12">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <?php echo $output; ?>
        <?php the_content(); ?>
        <?php endwhile; else: ?>
            <p><?php _e('Sorry, this page does not exist.'); ?></p>
        <?php endif; ?>	
        </article>
    <?php else: ?>
        <article class="span9">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
          <?php echo $output; ?>
          <?php the_content(); ?>
        <?php endwhile; else: ?>
          <p><?php _e('Sorry, this page does not exist.'); ?></p>
        <?php endif; ?>	
        </article>
    <?php endif; ?>
  <?php endif; ?>
  
  <?php if ( is_active_sidebar( 'insta-right-sidebar-search' ) ) : ?>    
  <aside class="span3">
      <?php dynamic_sidebar( 'insta-right-sidebar-search' ); ?>
  </aside>
  <?php endif; ?>
  
</div>

</article>

<script>
  var data = '<?php echo json_encode($results); ?>',
      seo = '<?php echo json_encode($seo); ?>';
</script>
<?php get_footer(); ?>