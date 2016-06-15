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


  <header class="row-fluid search-result-controls">
    <div class="span7 form-horizontal">&nbsp;</div>
    <div class="span5 form-horizontal">
    <div class="control-group pull-right">
      <label class="control-label"></label>
      <div class="controls">
        <div class="btn-group" data-toggle="buttons-radio">

          <?php if($config['searchmode-listview'] == 'on') { ?><button name="view" value="list" class="btn changeview <?php echo $view == 'list' ? 'active' : ''; ?>" data-template="tmpl-propertysearch-listview" data-rowfixselector="" data-rowfixcount"1"><i class="icon-list"></i>&nbsp;<?php echo $textdata['List']; ?></button><?php } ?>
          <?php if($config['searchmode-photo'] == 'on') { ?><button name="view" value="gallery" class="btn changeview <?php echo $view == 'gallery' ? 'active' : ''; ?>" data-template="tmpl-propertysearch-galleryview" data-rowfixselector=".gallery-view-page%20%3E%20.span6" data-rowfixcount="2"><i class="icon-th-large"></i>&nbsp;<?php echo $textdata['Photo']; ?></button><?php } ?>
          <?php if($config['searchmode-mapview'] == 'on') { ?><button name="view" value="map" class="btn changeview <?php echo $view == 'map' ? 'active' : ''; ?>" data-template="tmpl-propertysearch-mapview" data-showallresults="1"><i class="icon-map-marker"></i>&nbsp;<?php echo $textdata['Map']; ?></button><?php } ?>
        
        </div>        
      </div>
    </div>
    </div>  
  </header>

  <div id="results" class="list-view-page">

  <?php foreach($results as $result) { 
    $id = $result['ID'];
    $context = $result['ContextData'];
    $seodata = $seo[$id];

    ?>
    <div class="portal-result">
      <div class="portal-inner row-fluid shadow">
        <div class="portal-images span4">
          <div id="slider-deprecate">
          <?php if($keyword = $seodata[$id]['Keyword']) { ?><a href="<?php echo $seodata[$id]['DetailURL']; ?>"><?php } ?><img alt="<?php echo $result['PrimaryImage']['Caption']; ?>" src="https://placeholdit.imgix.net/~text?txtsize=33&txt=<?php _e('Loading'); ?>...&w=400&h=300" data-src="<?php echo $result['PrimaryImage']['ThumbnailURL']; ?>" /><?php if($keyword) { ?></a><?php } ?>
          </div>
        </div>
        <div class="portal-info span8">
        <div class="property-info"> 
          <h2 class="property-title">
            <?php if($seo[$id]['Keyword']) { ?><a href="<?php echo $seo[$id]['DetailURL']; ?>"><?php } ?>
              <?php echo $result['Headline']; ?>
            <?php if($seo[$id]['Keyword']) { ?></a><?php } ?>
          </h2>
            <?php if($config['hidestarsreviews'] != 'on') { ?>    
              <?php if($result['NumReviews']) { ?>
                  <div class="starsreviews"><div id="propstar-<?php echo $result['AvgReview']; ?>"><span class="stars"></span><i class="starsvalue"></i></div></div>  
              <?php } ?>     
            <?php } ?>
          <div class="location">
            <span><b><?php echo $textdata['City']; ?>:</b> <?php echo $result['City']; ?></span>  
            <?php if($beds = $result['Bedrooms']) { ?>
              <span class="hidden-phone">| </span><b><?php echo $textdata['Beds']; ?></b>: <?php echo $beds; ?> | 
            <?php } 
            if($baths = $result['Bathrooms']) { ?>
              <b><?php echo $textdata['Baths']; ?></b>: <?php echo $baths; ?> | 
            <?php } 
            if($sleeps = $result['Sleeps']) { ?>
              <b><?php echo $textdata['Sleeps']; ?></b>: <?php echo $sleeps; ?>
            <?php } ?>
          </div>
          <div class="brick-wrap">
            <div class="description bapi-truncate" data-trunclen="100"><?php echo $result['Summary']; ?></div>
          </div>
          
          <?php if(!$context['Quote']['IsValid']) { ?>
            <?php if(!$_SESSION['scheckin'] || !$_SESSION['scheckout']) { ?><div class="alert alert-info no-rate"><?php echo $context['Quote']['ValidationMessage']; ?></div><?php } ?>
          <?php } ?>
          
          <div class="row-fluid">
          <div class="span7 portal-rates">
          <?php if(['HidePrice']) { 
            $display = ['Quote']['QuoteDisplay'];
            if($display['value']) {
              if($display['prefix']) { ?><span class="prefix"><?php echo $display['prefix']; ?>:</span><?php } ?>
              <?php echo $display['value']; ?>
              <?php if($display['suffix']) { ?><span class="suffix">/<?php echo $display['suffix']; ?></span><?php } ?>
            <?php } ?>
          <?php } ?>
          </div>
          <div class="span5 right-side-actions">
            <button class="btn btn-mini add-wishlist bapi-wishlisttracker{{#inmylist}} active{{/inmylist}}" data-pkid="<?php echo $id; ?>" type="button" data-toggle="button">
            <span class="halflings heart-empty">
              <i></i>
              <?php 
              if(in_array($bapikey[1], $_SESSION['mylist'])) {
                echo $textdata['WishListed']; 
              } else {
                echo $textdata['WishList']; 
              } ?>
            </span></button>
            <?php if($seo[$id]['Keyword']) { ?>&nbsp;|&nbsp;<a class="property-link" href="<?php echo $seo[$id]['DetailURL']; ?>"><?php echo $textdata['Details']; ?> <span>&rarr;</span></a><?php } ?>
          </div>
          </div>
        </div>
        </div>
      </div>
    </div>
  <?php } ?>
  </div>
  <div class="clearfix"></div>

  <?php if($results) { ?>
  <nav>
    <ul class="pager">
      <?php if($page > 0) { ?><li><a href="?<?php echo get_pager('prev'); ?>"><?php _e('Previous'); ?></a></li><? } ?>
      <?php echo ($page*$perpage)+1; ?> &mdash; <?php echo ($page*$perpage)+$perpage; ?>
      <?php if(($page+1) * $perpage < count($all) ) { ?><li><a href="?<?php echo get_pager('next'); ?>"><?php _e('Next'); ?></a></li> <?php } ?>
    </ul>
  </nav>
  <?php } else { _e('No results.'); }

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