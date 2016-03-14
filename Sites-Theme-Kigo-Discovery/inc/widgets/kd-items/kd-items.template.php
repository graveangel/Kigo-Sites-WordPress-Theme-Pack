<?php
$limit = $i['limit'] ? : -1;
$items = new WP_Query(['post_type' => 'item', 'type' => $i['type'], 'orderby' => 'menu_order', 'posts_per_page' => $limit]);
$num = count($items->posts);

$cols = $i['columns'] ? : false;
$col = $cols ? 12 / $cols : ($num > 4 ? 4 : (12 / $num));

$display = $i['display'] ? : 'list';
?>
<div class="kd-widget kd-items page-block">
    <h1 class="widget_title"><?php echo $i['title'] ?></h1>


    <?php if($display == 'slider'): ?>

        <div class="swiper-container" data-columns="<?php echo (int)$i['columns'] ? : 4 ?>">
            <div class="swiper-wrapper">
                <?php while($items->have_posts()): $items->the_post(); ?>
                    <div class="item-block swiper-slide">
                        <div class="icon"><i class="fa <?php echo get_post_meta( get_the_ID(), 'item_icon', true ) ?>"></i></div>
                        <div class="title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></div><i class="kd-icon-down_arrow open-close"></i>
                        <div class="body"><?php the_content(); ?></div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="prev-slide"><i class="kd-icon-left_arrow"></i></div>
            <div class="next-slide"><i class="kd-icon-right_arrow"></i></div>
        </div>

    <?php else: ?>

        <?php while($items->have_posts()): $items->the_post(); ?>
            <div class="item-block col-xs-12 col-md-6 col-lg-<?php echo $col ?>">
                <div class="icon"><i class="fa <?php echo get_post_meta( get_the_ID(), 'item_icon', true ) ?>"></i></div>
                <div class="title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></div><i class="kd-icon-down_arrow open-close"></i>
                <div class="body"><?php the_content(); ?></div>
            </div>
        <?php endwhile; ?>

    <?php endif; ?>





</div>