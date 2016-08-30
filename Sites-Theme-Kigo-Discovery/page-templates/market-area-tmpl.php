<?php
/**
 * Template Name: Market Area Page
 */
?>
<?php get_header(); ?>
<div class="market-area-page page-width ">

    <!--The content-->
    <div class="market-area-content">



        <!--The title-->
        <div class="title-description">
            <!-- Search and Enquire buttons: for now this is just the widget, in the future the sidebars will be needed -->

            <div class="search-and-enquire">
                <!-- Search Button -->
                <a href="#" class="trigger-modal primary-stroke-color btn" data-modal="#search"><?php echo $this->r("Search"); ?></a>

                <!-- Enquire Button -->
                <a href="#" class="trigger-modal primary-stroke-color btn" data-modal="#enquire"><?php echo $this->r("Enquire"); ?></a>
            </div>    
            <h1 class="title"><?php echo __('Rentals in') . ' ' . $title; ?></h1>
            <div class="description">
                <?php echo apply_filters('the_content', $description); ?>
            </div>
        </div>

        <!--Subareas-->
        <div class="sub-areas">
            <div class="title">
                <?php if(count($sub_areas)): ?>
                Areas in <?php echo $title; ?>
                <?php endif; ?>
            </div>
            <!-- Slider main container -->
            <div class="swiper-container">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <!-- Slides -->                    
                    <?php foreach ($sub_areas as $subarea): ?>
                        <?php
                        $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id($subarea->ID), 'single-post-thumbnail')[0];
                        //$featured_image = empty($featured_image) ? $subarea->bpd['PrimaryImage']['MediumURL'] : $featured_image[0];
                        ?>
                        <div class="swiper-slide" style="background-image: url('<?php echo $featured_image; ?>'); background-color: #333;">
                            <a href="<?php echo $subarea->guid; ?>" class="permalink">
                            <h4><?php echo $subarea->post_title; ?></h4>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>

                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>

                <!-- If we need scrollbar -->
                <div class="swiper-scrollbar"></div>
            </div>

        </div>


        <!--Property Listing-->
        <div class="property-listing">
            <div class="photo-list-view">
                <b><?php echo _e(sprintf('Showing %d properties of %d', $pvisible, $ptotal), 'kd'); ?></b>
                <ul class="view-select">
                    <li>
                        <a href="#" class="photo vs-button active" data-list="photo"><i class="fa fa-camera" aria-hidden="true"></i> <?php echo $this->r("Photo"); ?></a>
                    </li>
                    <li>
                        <a href="#" class="list vs-button" data-list="list"><i class="fa fa-list" aria-hidden="true"></i><?php echo $this->r("List"); ?></a>
                    </li>
                </ul>
            </div>

            <div class="property-list photo">
                <?php foreach ($properties as $property): $ppd = $property->bpd; ?>

                    <!--Property Box-->
                    <div class="ppt-box">

                        <!--Thumb-->
                        <a href="<?php echo $property->guid; ?>" class="ppermalink">
                            <div class="ppt-thumb" style="background-image: url('<?php echo $ppd['PrimaryImage']['MediumURL'] ?>');"></div>
                        </a>
                        <!--Title-->
                        <div class="title-n-desc">
                            <a href="<?php echo $property->guid; ?>" class="ppermalink">
                                <h3 class="ppt-title">
                                    <?php echo $ppd['ContextData']['SEO']['PageTitle']; ?>
                                </h3>
                            </a>
                            <p>
                                <?php 
                                $location = $ppd['Neighborhood'] or $ppd['City'] or $ppd['State'] or $ppd['Country'] or '-';
                                echo str_replace('<br/>', ', ', sprintf("%s, %s", $ppd['Type'], $location)); ?>
                                <br>
                                <?php printf("%d %s / %d %s", $ppd['Bedrooms'], $this->r("Beds"), $ppd['Bathrooms'], $this->r("Baths")); ?>
                            </p>
                        </div>
                        <div class="from primary-fill-color">
                             <a href="<?php echo $property->guid; ?>" class="ppermalink">
                            <span><?php echo $this->r('From'); ?></span>
                            
                            
                            <?php if(strlen($ppd['MinRate']['LocalCurrencySymbol']) < 3): ?>
                            <p><?php printf("%s %s", $ppd['MinRate']['LocalCurrencySymbol'], $ppd['MinRate']['LocalSValue2']); ?></p>
                            <?php else: ?>
                            <p><?php printf("%s %s", '&#x' . substr($ppd['MinRate']['LocalCurrencySymbol'],1) . ';', $ppd['MinRate']['LocalSValue2']); ?></p>
                            <?php endif; ?>
                            
                             </a>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
            <div class="pagination-links">
                <?php
						$big = 999999999; // need an unlikely integer

						echo paginate_links( array(
							'base' => str_replace( $big, '%#%', esc_url( "?paged=$big" ) ),
							'format' => '?paged=%#%',
							'current' => max( 1, $this->current_page ),
							'total' => $this->max_num_pages
						) );
				?>
            </div>
        </div>
    </div>

    <!--The map-->
    <div class="market-area-map primary-stroke-color">
        <!--The image-->
        <div class="featured-image" style="background-image: url('<?php echo $pics[0] ?>');">
        </div>
        <div class="mpbx"></div>
    </div>
    
    
    <div class="modal fade" id="search" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
               
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php the_widget('KD_Search', ['title' => 'Revise Search']); ?>
                </div> 
               
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div class="modal fade" id="enquire" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
               
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php the_widget('KD_BAPI_Inquiry_Form', ['title' => 'Have a Question?']); ?>
                </div>
                
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<script type="text/javascript">
    var map_center = {lat: <?php echo $latitude; ?>, lng: <?php echo $longitude; ?>};
    var all_props = <?php echo $all_props; ?>;
</script>
<?php get_footer(); ?>
