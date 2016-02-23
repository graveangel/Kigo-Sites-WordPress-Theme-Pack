<?php
$team = new WP_Query(['post_type' => 'team', 'post_limit' => '-1']);
$isList = $i['displayList'] == 'on';
$listCols = $i['columns'] ? : 3;
?>
    <!-- Widget: KD Team -->
    <h1 class="widget_title"><?php echo $i['title'] ?></h1>
    <div class="kd-widget kd-team <?php echo !$isList ? 'swiper-container faded-out' : '' ?>" data-columns="<?php echo $i['columns'] ? : 4 ?>">

        <?php if(!$isList) { ?>
            <div class="kd-team-wrapper swiper-wrapper">
                <?php while($team->have_posts()): $team->the_post(); ?>
                    <div class="team-member swiper-slide">
                        <?php if($i['displayImages'] == 'on'){ ?>
                            <a href="<?php echo get_the_permalink($person->ID) ?>">
                                <div class="image" style="<?php echo furl($person->ID) ? 'background-size: cover; background-image: url('.furl($person->ID).')' : '' ?>"></div>
                            </a>
                        <?php } ?>
                        <div class="member-caption">
                            <div class="name">
                                <a href="<?php the_permalink() ?>">
                                    <?php the_title(); ?>
                                </a>
                            </div>
                            <div class="charge">
                                <?php echo get_post_meta(get_the_ID(), '_position', true) ?>
                            </div>
                            <div class="email primary-stroke-color">
                                <a href="mailto:<?php echo get_post_meta(get_the_ID(), '_email', true) ?>"><?php echo get_post_meta(get_the_ID(), '_email', true) ?></a>
                            </div>
                            <?php if($i['displayDesc'] == 'on'){ ?>
                                <div class="description"><?php the_content() ?></div>
                            <?php } ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="prev-slide"><i class="kd-icon-left_arrow"></i></div>
            <div class="next-slide"><i class="kd-icon-right_arrow"></i></div>

        <?php }else {
            while($team->have_posts()): $team->the_post(); ?>
                <div class="team-member is_list col-xs-12 col-md-<?php echo 12 / $listCols ?>">

                    <?php if($i['displayImages'] == 'on'){ ?>
                        <?php if($i['displayImages'] == 'on'){ ?>
                            <a href="<?php echo get_the_permalink($person->ID) ?>">
                                <div class="image" style="<?php echo furl($person->ID) ? 'background-size: cover; background-image: url('.furl($person->ID).')' : '' ?>"></div>
                            </a>
                        <?php } ?>
                    <?php } ?>

                    <div class="member-caption transall">
                        <div class="name">
                            <?php the_title(); ?>
                            </a>
                        </div>
                        <div class="charge">
                            <?php echo get_post_meta(get_the_ID(), '_position', true) ?>
                        </div>
                        <div class="email primary-stroke-color">
                            <a href="mailto:<?php echo get_post_meta($person->ID, '_email', true) ?>"><?php echo get_post_meta($person->ID, '_email', true) ?></a>
                        </div>
                        <?php if($i['displayDesc'] == 'on'){ ?>
                            <div class="description"><?php the_content() ?></div>
                        <?php } ?>
                    </div>
                </div>
            <?php endwhile;
        } ?>
    </div>
<?php wp_reset_postdata();