<?php
/**
 * @template: Default Landing Page Template
 * @description: Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam molestie bibendum sem at efficitur. Interdum et malesuada fames ac ante ipsum primis in faucibus. Suspendisse neque tellus, vestibulum sit amet mollis sit amet, ullamcorper eget nisi. Vestibulum commodo justo et tellus imperdiet, quis placerat ante gravida. Interdum et malesuada fames ac ante ipsum primis in faucibus. Sed id luctus nisi. Nullam tortor diam, finibus vehicula elementum vitae, faucibus sed turpis. Sed magna massa, accumsan ac tincidunt at, malesuada quis diam. Morbi posuere imperdiet sagittis. Phasellus ligula est, ultricies ut blandit nec, finibus a urna. Sed in magna euismod, finibus massa ornare, dictum justo.
 * @author: Jairo E. Vengoechea
 * @version: 0.1.0
 * @preview: /wp-content/themes/Sites-Theme-Kigo-Discovery/kd-common/img/ma-landing-preview-a.png
 */
?>
<?php get_header(); ?>
<div class="market-areas-default-template market-area-single page-width">

    <!-- Hero -->
    <div class="hero">
        <!-- Market area title -->
        <div class="center">
            <h1><?php echo $title; ?></h1>

            <!-- Description -->
            <div class="description">
                <h2><?php echo render_this('{{#site}}{{textdata.Description}}{{/site}}'); ?></h2>
                <div class="content">
                    <?php echo $description; ?>
                </div>
            </div>

        </div>


        <!-- Slider main container -->
        <div class="swiper-container">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->

                <?php if(!empty($pics)) foreach($pics as $pic_url):?>
                    <div class="swiper-slide" style="background-image: linear-gradient(to bottom, rgba(0,0,0,0.3),rgba(0,0,0,0.3)), url(<?php echo $pic_url; ?>);"></div>
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


    <!-- Widget area for property search -->
    <div class="widget-area-for-ppt-search">

        <ul class="tab-selector">

            <?php
            /**
             * This part should be available
             * only if the market area has sub-areas.
             */
            if($this->has_subareas()):?>
            <!-- Locations -->
            <li>
                <a class="pointer primary-fill-color" data-target=".location-list"><h2><?php echo render_this('{{#site}}{{textdata.Location}}{{/site}}'); ?></h2></a>
            </li>
            <?php endif; ?>

            <?php
            /**
             * This part should be available
             * only if the market area has properties.
             */
            if($this->has_properties()):?>
            <!-- properties -->
            <li>
                <a class="pointer" data-target=".property-list"><h2><?php echo render_this('{{#site}}{{textdata.Property}}{{/site}}'); ?></h2></a>
            </li>
            <?php endif; ?>

        </ul>

        <!-- Tabs contents -->
        <ul class="tabs-contents">
            <?php
            /**
             * This part should be available
             * only if the market area has sub-areas.
             */
            if($this->has_subareas()):
            ?>
            <!-- Locations content -->
            <li class="location-list active">

                    <?php foreach($this->get_locations(json_decode($tree, true)) as $location): ?>

                        <div class="location-box" <?php if(!empty($location['thumbnail_url'])):?> style="background-image: url(<?php echo $location['thumbnail_url']; ?>); "<?php endif;
                            $parsed_url = parse_url($_SERVER["REQUEST_URI"]);
                            $path = $parsed_url['query'];
                            $subarea_url = empty($path) ? '?' : '?' . $path . '&';
                        ?>>
                            <!-- Name -->
                            <a href="<?php echo $subarea_url . 'subarea=' . $location['name']; ?>" class="title"><h3><?php echo $location['name']; ?></h3></a>
                            <!-- If landing -->
                            <?php if(!empty($location['landing_url'])):?>
                                <a href="<?php echo $location['landing_url']; ?>" class="btn primary-fill-color">
                                    Visit
                                </a>
                            <?php endif; ?>
                        </div>

                    <?php endforeach; ?>

            </li>
            <?php
            endif;
            /**---------------------------------**/?>

            <?php
            /**
             * This part should be available
             * only if the market area has properties.
             */
            if($this->has_properties()):
            ?>
            <!-- Properties content -->
            <li class="property-list">
                <?php foreach($this->fetch_properties() as $property):
                        $amenities = $property['Amenities'];
                        $min_rate = $property['MinRate'];
                    ?>
                    <div class="property-box">
                        <a href="<?php echo $property['ContextData']['SEO']['DetailURL']; ?>">
                            <img class="ppt-thumbnail" src="<?php echo $property['PrimaryImage']['ThumbnailURL']; ?>" alt="<?php echo  $property['ContextData']['SEO']['PageTitle']; ?>">
                        </a>
                        <a href="<?php echo $property['ContextData']['SEO']['DetailURL']; ?>">
                            <span class="ppt-name"><?php echo $property['Headline']; ?></span>
                            <br> |
                            <?php foreach($amenities as $amenity): ?>
                                <span><?php echo $amenity['Values'][0]['Data']?></span> |
                            <?php endforeach; ?>
                        </a>
                        <div class="min-rate">
                            <h4>From</h4>
                            <span class="price">
                                <span class="symbol">
                                    <?php echo $min_rate['LocalCurrencySymbol']; ?>
                                </span>
                                <?php echo $min_rate['LocalSValue2']; ?>
                            </span>
                        </div>
                        <?php //debug($min_rate); ?>
                    </div>
                <?php endforeach; ?>

                <?php

						$big = 999999999; // need an unlikely integer

						echo paginate_links( array(
							'base' => str_replace( $big, '%#%', esc_url( "?pag=$big" ) ),
							'format' => '?pag=%#%',
							'current' => max( 1, $this->current_page + 1 ),
							'total' => $this->max_num_pages
						) );
				?>
            </li>
            <?php
            endif;
            /**---------------------------------**/?>
        </ul>

    </div>

    <!-- Market Areas Tab 1 -->

    <!-- Property list Areas Tab 2 -->


</div>
<?php get_footer(); ?>
