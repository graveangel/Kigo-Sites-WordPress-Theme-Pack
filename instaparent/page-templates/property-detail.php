<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Template Name: Property Detail Page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package kigo-blank
 */

get_header(); 

$data = get_post_meta(get_the_ID(), 'bapi_property_data', true);

if(isset($_GET['debug']) && $_GET['debug'] == 'raw') { echo "Raw data:<pre>"; print_r($data); echo "</pre>"; }

$data = json_decode($data);
$context = $data->ContextData;

if(isset($_GET['debug'])) { echo "Decoded data:<pre>"; print_r($data); echo "</pre>"; }

$translations = getbapitextdata();

global $bapi_all_options; 
//$settings = json_decode($bapi_all_options['bapi_sitesettings']);
$settings = get_option('bapi_sitesettings_raw');

if(isset($_GET['debug'])) { echo "Debug data ...<pre>"; print_r($settings); echo "</pre>"; }

if($_GET['debug'] == 'session') { echo "Session stuff ...<pre>"; print_r($_SESSION); echo "</pre>"; }
?>

<article class="span9">

<?php
if($data) {
?>


	<div class="bapi-entityadvisor" data-pkid="<?php echo $data->ID; ?>" data-entity="property"></div>
		<section class="row-fluid">
		<div class="span12 item-snapshot module shadow-border">        
			<div class="top-block">
			<div class="row-fluid">
				<div class="span7 left-side">
				<h2 class="title"><?php echo $data->Headline; ?></h2>
				<p class="location"><span><b><?php echo $translations['City']; ?>:</b> <?php echo $data->City; ?></span> <?php if($data->Bedrooms) { ?><b><?php echo $translations['Beds']; ?></b>: <?php echo $data->Bedrooms; ?> | <?php } ?><?php if($data->Bathrooms) { ?><b><?php echo $translations['Baths']; ?></b>: <?php echo $data->Bathrooms; ?> | <?php } ?><b><?php if($data->Sleeps) { ?><?php echo $translations['Sleeps']; ?></b>: <?php echo $data->Sleeps; ?><?php } ?>		
				<?php if($settings['averagestarsreviews'] == 'on') { ?>		
						<?php if($data->NumReviews > 0) { ?>
						 		<div class="starsreviews"><div id="propstar-<?php echo $data->AvgReview; ?>"><span class="stars"></span><i class="starsvalue"></i></div></div>	
						<?php } ?>
				<?php } ?>
				</p>                
				</div>	
				<div class="span5 nav-links">
					<a class="link" href="/rentalsearch"><span>&larr;</span>&nbsp;<?php echo $translations['Back to Results']; ?></a>
				</div>
				</div>  
			</div>		
			<div class="item-slideshow">
				<div id="slider" class="flexslider bapi-flexslider" data-options='{ "animation": "slide", "controlNav": false, "animationLoop": false, "slideshow": false, "sync": "#carousel" }'>
				<ul class="slides">
				<?php $imgCount = 1; foreach($data->Images as $img) { ?>
					<li>
						<div>
							<img alt="<?php echo $img->Caption; ?>" title="Open Slideshow" <?php if($imgCount > 3) { echo 'src="/wp-content/plugins/bookt-api/img/loading-816x600.gif" data-'; } ?>src="<?php echo $img->OriginalURL; ?>" />
							<?php if($img->Caption) { ?>
							<p class="flex-caption">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $img->Caption; ?></p>
							<?php } ?>
						</div>
					</li>
				<?php $imgCount++; } ?>
				</ul>
				</div>
				<div id="carousel" class="flexslider bapi-flexslider" data-options='{ "animation": "slide", "controlNav": false, "animationLoop": false, "slideshow": false, "itemWidth": 50, "itemMargin": 10, "asNavFor": "#slider" }'>
				<ul class="slides">
				<?php foreach($data->Images as $img) { ?>
					<li><img alt="" src="<?php echo $img->ThumbnailURL; ?>" height="50" /></li>
				<?php } ?>
				</ul>
				</div>
		 
		<!-- Modal -->
		<div id="fullScreenSlideshow" class="modal hide fade fullScreenSlideshow" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-body">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		  <div id="fullScreenCarousel" class="carousel slide fullScreenCarousel" data-interval="10800000">
		  <!-- Carousel items -->
		<div class="carousel-inner">
			<?php foreach($data->Images as $img) { ?>
			<div class="item" data-caption="<?php echo $img->Caption; ?>" data-imgurl="<?php $img->OriginalURL; ?>"><div class="carousel-caption"><h4 class="headline"><?php echo $img->Headline; ?></h4><p><?php echo $img->Caption; ?></p></div></div>
			<?php } ?>
		</div>
		<!-- Carousel nav -->
		<div class="carousel-controls"><a class="carousel-control left" href="#fullScreenCarousel" data-slide="prev">&lsaquo;</a>
		<a class="carousel-control right" href="#fullScreenCarousel" data-slide="next">&rsaquo;</a></div>
		</div>
		</div>
		</div>


			</div>
		</div>
		</section>
		<section class="row-fluid item-info module shadow-border">
		<div class="span12">
			<div class="tabbable">

			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab1" data-toggle="tab"><?php echo $translations['General']; ?></a></li>

				<?php 
				if($settings['propdetailrateavailtab'] != 'on') {
					if($settings['propdetailratestable'] != 'on') {
						$tab2 = $translations['Rates & Availability'];
					} else {
						$tab2 = $translations['Availability'];
					} 
				} else {
					if($settings['propdetailratestable'] != 'on') {
						$tab2 = $translations['Rates'];
					}
				}

				if($tab2) {
					echo "<li><a href='#tab2' data-toggle='tab'>$tab2</a></li>";
				}

				?>

				

				<li><a href="#tab3" data-toggle="tab"><?php echo $translations['Amenities']; ?></a></li>
				<li><a href="#tab4" id="tabs4" data-toggle="tab"><?php echo $translations['Attractions']; ?></a></li>
				<?php if($settings['propdetail-reviewtab']) { ?>	
				<li><a href="#tab5" data-toggle="tab"><?php echo $translations['Reviews']; ?></a></li>
				<?php } ?>
			</ul>		

			<div class="tab-content">
				<div class="tab-pane active" id="tab1">
				<div class="row-fluid">
				<div class="span12 box-sides">
					<div class="row-fluid">	
						<div class="span4 property-detail module shadow-border2">
							<div class="pd">
							<h4><?php echo $translations['Property Details']; ?></h4>
							<ul class="unstyled">
								<?php if($data->Development) { ?><li><?php echo $translations['Development']; ?>: <span><?php echo $data->Development; ?></span></li><?php } ?>
								<?php if($data->Type) { ?><li><?php echo $translations['Category']; ?>: <span><?php echo $data->Type; ?></span></li><?php } ?>
								<?php if($data->Beds) { ?><li><?php echo $translations['Beds']; ?>: <span><?php echo $data->Beds; ?></span></li><?php } ?>
								<?php if($data->Bathrooms) { ?><li><?php echo $translations['Baths']; ?>: <span><?php echo $data->Bathrooms; ?></span></li><?php } ?>
								<?php if($data->Sleeps) { ?><li><?php echo $translations['Sleeps']; ?>: <span><?php echo $data->Sleeps; ?></span></li><?php } ?>
								<?php if($data->Stories) { ?><li><?php echo $translations['Stories']; ?>: <span><?php echo $data->Stories; ?></span></li><?php } ?>
								<?php if($data->Floor) { ?><li><?php echo $translations['Floor']; ?>: <span><?php echo $data->Floor; ?></span></li><?php } ?>
								<?php if($data->AdjLivingSpace) { ?><li><?php echo $translations['Unit Size']; ?>: <span><?php echo $data->AdjLivingSpace." ".$data->AdjLivingSpaceUnit; ?></span></li><?php } ?>
								<?php if($data->LotSize) { ?><li><?php echo $translations['Lot Size']; ?>: <span><?php echo $data->LotSize." ".$data->LotSizeUnit; ?></span></li><?php } ?>
								<?php if($data->GarageSpaces) { ?><li><?php echo $translations['Garage Spaces']; ?>: <span><?php echo $data->GarageSpaces; ?></span></li><?php } ?>
								<?php if($settings['averagestarsreviews']) { ?>
									<?php if($data->NumReviews > 0) { ?>
						 				<div class="starsreviews"><div id="propstar-<?php echo $data->AvgReview; ?>"><span class="stars"></span><i class="starsvalue"></i></div></div>	
									<?php } ?>
								<?php } ?>
							</ul>
							</div>
						</div>
						<div class="span8">
							<?php echo preg_replace("/\brn\b/", "", $data->Description); ?>	
						</div>
					</div>
				
				</div>
				</div>
				</div>
					<div class="tab-pane" id="tab2">
					<div class="row-fluid">
					<div class="span12 box-sides">

					<?php
					if($settings['propdetailrateavailtab'] != 'on') { ?>

						<h3><?php echo $translations['Availability']; ?></h3>
						<div id="avail" class="bapi-availcalendar" data-options='{ "availcalendarmonths": <?php echo isset($settings['propdetail-availcal']) ? $settings['propdetail-availcal'] : 3; ?>, "numinrow": 3 }' data-pkid="<?php echo $data->ID; ?>" data-rateselector="bapi-ratetable"></div>
							
						<?php 
						if($settings['propdetailratestable'] != 'on') { echo '<hr />'; }					
					} 

					if($settings['propdetailratestable'] != 'on') { ?>
						<h3><?php echo $translations['Rates']; ?></h3>

						<?php if($context->Rates->Values) { ?>
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<?php foreach($context->Rates->Keys as $key) {
										echo "<th>".$key."</th>";
									} ?>
								</tr>
							</thead>
							<tbody>
								<?php foreach($context->Rates->Values as $value) { ?>
								<tr>
									<?php foreach($value as $v) { echo "<td>".$v."</td>"; } ?>		
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php } else {
							echo $translations['No rates available'];
						} ?>
					<?php }
					?>

					</div>
					</div>
					</div>
				<div class="tab-pane" id="tab3">
				<div class="row-fluid">
				<div class="span12 box-sides">
				<h3><?php echo $translations['Amenities']; ?></h3>
				<?php foreach($data->Amenities as $amenity) { ?>		
					<ul class="amenities-list unstyled clearfix">
						<li class="category-title"><?php echo $amenity->Key; ?></li>
						<?php foreach($amenity->Values as $value) { ?>
							<li><span class="halflings ok-sign"><i></i><?php echo $value->Label; ?></span></li>
						<?php } ?>		
					</ul>
					<div class="clearfix"></div>
				<?php } ?>
				</div>
				</div>
				</div>
				<div class="tab-pane" id="tab4">			
				<div class="row-fluid">
				<div class="span12 box-sides">
					<div id="poi-map-prop" class="bapi-map" data-loc-selector='.poi-map-location' data-refresh-selector='#tabs4' data-refresh-selector-event='shown' data-link-selector='.poi-map-item' style="width:100%; height:400px;"></div>			
					<div id="map-side-bar">
					<table class="table table-bordered table-striped poi-map-locations">
					<thead>
					<tr>
						<th></th>
						<th><?php echo $translations['Attractions']; ?></th>
						<th><?php echo $translations['Category']; ?></th>
						<th><?php echo $translations['Distance']; ?></th>
					</tr>
					</thead>
					<tbody id="map-locations">
					<tr>
						<td>
						<div class="poi-map-location" data-jmapping='{ "id": <?php echo $data->ID; ?>, "point": { "lng": <?php echo $data->Longitude; ?>, "lat": <?php echo $data->Latitude; ?> }, "category" : "property"}'>
							<a class="poi-map-item mapmarker-prop" href="#"><?php echo $translations['Property']; ?></a>
							<div class="info-html">
								<div class="marker-infowindow">
									<span class="prop-image pull-left"><img src="/wp-content/themes/instaparent/insta-common/images/loading.gif" data-src="<?php echo $data->PrimaryImage->ThumbnailURL; ?>" caption="<?php echo $data->PrimaryImage->Caption; ?>" alt="<?php echo $data->PrimaryImage->Caption; ?>"></span>
									<span class="prop-location pull-left">
										<span>
										<?php if($data->SEO->Keyword) { ?><a target="_blank" href="<?php echo $data->DetailURL; ?>"><?php } ?>
										<b><?php echo $data->Headline; ?></b>
										<?php if($data->SEO->Keyword) { ?></a><?php } ?><br/>			
										<?php if($data->Type) { ?><b><?php echo $translations['Category']; ?>:</b><?php echo $data->Type; ?><br/><?php } ?>
										<?php if($data->City) { ?><b><?php echo $translations['City']; ?>: </b><?php echo $data->City; ?><br/><?php } ?>
										<?php if($data->Beds) { ?><b><?php echo $translations['Beds']; ?>: </b><?php echo $data->Beds; ?><br/><?php } ?>
										<?php if($data->Bathrooms) { ?><b><?php echo $translations['Baths']; ?>: </b><?php echo $data->Baths; ?><br/><?php } ?>
										<?php if($data->Sleeps) { ?><b><?php echo $translations['Sleeps']; ?>: </b><?php echo $data->Sleeps; ?><?php } ?>
										</span>
									</span>
								</div>
							</div>
						</div>
						</td>
						<td><?php echo $data->Headline; ?></td>
						<td><?php echo $data->Type; ?></td>
						<td>-</td>
					</tr>
					<?php foreach($data->ContextData->Attractions as $attraction) { ?> 
					<tr>
						<td>
						<div class="poi-map-location" data-jmapping='{ "id": <?php echo $attraction->ID; ?>, "point": { "lng": <?php echo $attraction->Longitude; ?>, "lat": <?php echo $data->Latitude; ?> }, "category":"poi-<?php echo $attraction->ContextData->ItemIndex; ?>" }'>
							<a class="poi-map-item mapmarker-<?php echo $attraction->ContextData->ItemIndex; ?>" href="#"><?php echo $attraction->ContextData->ItemIndex; ?></a>
							<div class="info-html">
								<div class="marker-infowindow"> 
									<span class="prop-image pull-left"><img src="/wp-content/themes/instaparent/insta-common/images/loading.gif" data-src="<?php echo $attraction->PrimaryImage->ThumbnailURL; ?>" caption="<?php echo $attraction->PrimaryImage->Caption; ?>" alt="<?php echo $attraction->PrimaryImage->Caption; ?>"></span>
									<span class="prop-location pull-left">
										<span>
										<?php if($attraction->ContextData->SEO->Keyword) { ?><a target="_blank" href="<?php echo $attraction->ContextData->SEO->DetailURL; ?>"><?php } ?>
										<b><?php echo $data->Name; ?></b>
										<?php if($attraction->ContextData->SEO->Keyword) { ?></a><?php } ?><br/>
										<?php if($attraction->Type) { ?><b><?php echo $translations['Category']; ?>:</b><?php echo $attraction->Type; ?><br/><?php } ?>
										<?php if($attraction->Location) { ?><b><?php echo $translations['Address']; ?>: </b><?php echo $attraction->Location; ?><?php } ?>
										</span>
									</span>
							  </div>
							</div>
						</td>
						<td><?php if($attraction->ContextData->SEO->Keyword) { ?><a target="_blank" href="<?php echo $attraction->ContextData->SEO->DetailURL; ?>"><?php } ?><?php echo $attraction->Name; ?>
							<?php if($attraction->ContextData->SEO->Keyword) { ?></a><?php } ?></td>
						<td><?php echo $attraction->Type; ?></td>
						<td><?php echo $attraction->ContextData->Distance; ?></td>  
					</tr>
					<?php } ?>
					</tbody>
					</table>
					</div>
				</div>
				</div>
				</div>

				<?php if($settings['propdetail-reviewtab']) { ?>
				<div class="tab-pane" id="tab5">
					<div class="row-fluid">
						<div class="span12 box-sides">
							<?php if(!$data->ContextData->Reviews) { ?>
							<h5><?php _e('There are no reviews at this time.'); ?>
							<?php } else { ?>
								<div class="clearfix"></div>
								<?php foreach($data->ContextData->Reviews as $review) { ?>
									<div class="row-fluid review">
										<div class="span2 left-side">
											<span class="glyphicons chat" href=""><i></i></span>
											<h5 class="username"><?php echo $review->ReviewedBy->FirstName." ".$review->ReviewedBy->LastName; ?></h5>
										</div>
										<div class="span10">
											<h5 class="title"><?php echo $review->Title; ?></h5>
											<div class="rating"><span class="reviewrating-<?php echo $review->Rating; ?>"></span> <span><?php echo $translations['Posted on']; ?>: <?php echo $review->SubmittedOn->ShortDate; ?></span></div>
											<div class="comment">
											<?php echo $review->Comment; ?>
											</div>
											<?php foreach($review->Response as $response) { ?>
											<div class="response-block">
												<h5 class="response-title"><?php echo $translations['Response']; ?></h5>
												<div class="response"><?php echo $response; ?></div>
											</div>
											<?php } ?>
											<?php foreach($review->ExternalLink as $link) { ?>				
											<a class="full-rev-link" href="<?php echo $link; ?>" target="_blank"><?php echo $translations['See full review on']; ?> Flipkey</a>
											<?php } ?>
											<?php if($review->Source == 'FlipKey') {
												echo '<a class="flipkeyPowered" rel="nofollow" target="_blank" href="//www.flipkey.com"><span></span></a>';
											} ?>
										</div>
									</div>
								<hr/>
								<?php } ?>
							<?php } ?>
							</div>
						</div>					
					</div>
				</div>
				<?php } ?>
				
			</div>
		</div>
	</section>

<?php } ?>

</article>

<aside class="span3">

	<?php if ( is_active_sidebar( 'insta-right-sidebar-prop-detail' ) ) : ?>		
	    <?php dynamic_sidebar( 'insta-right-sidebar-prop-detail' ); ?>		
	<?php endif; ?>

	<?php /*
	<span class="end"></span>
	<div id="bapi-rateblock" class="bapi-rateblock" data-templatename="tmpl-search-rateblock" data-log="1"></div>
	<div class="bapi-moveme" data-from="#bapi-rateblock" data-to=".detail-overview-target" data-method="append"></div>
  	*/ ?>
        
</aside>
		

<?php /*
	<div id="primary" class="content-area">
		<h1>Property page template!</h1>
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
*/ ?>


<?php
get_footer();
