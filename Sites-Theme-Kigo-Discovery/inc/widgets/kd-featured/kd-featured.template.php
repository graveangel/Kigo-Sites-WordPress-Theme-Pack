<?php
$bg_left = $i['color'] ? 'style="background-color: '.$i['color'].'"' : '';
$bg_right = $i['color'] ? 'style="background-color: rgba(255,255,255,.1)"' : '';

$searchOpts = '{"ids": [';
foreach($i['visible_featured'] as $fid){
    $searchOpts .= "{\"id\": $fid},";
}
$searchOpts .= '{"id": null}]}';
?>
<div class="kd-widget" >
    <div class="kd-featured" <?php echo $bg_left ?>>

        <h1 class="title"><span><?php echo $i['title'] ? : __('Featured properties', 'kd') ?></span></h1>

        <div class="slider" <?php echo $bg_right ?> >
            <div class="swiper-container">
                <?php if($i['userandom']): ?>
                    <div id="kd_<?php echo $args['widget_id'] ?>" class="swiper-wrapper bapi-summary" data-templatename="tmpl-featuredproperties-quickview"  data-entity="property"></div>
                <?php else: ?>
                    <div id="kd_<?php echo $args['widget_id'] ?>" class="swiper-wrapper bapi-summary" data-templatename="tmpl-featuredproperties-quickview" data-searchoptions='<?php echo $searchOpts ?>' data-entity="property"></div>
                <?php endif; ?>
            </div>
            <div class="controls">
                <div class="prev"><i class="kd-icon-left_arrow"></i></div>
                <div class="swiper-pagination"></div>
                <div class="next"><i class="kd-icon-right_arrow"></i></div>
            </div>
        </div>

        <div class="action">
            <a class="kd-button" href="<?php echo $i['link'] ? : '/rentals' ?>"><?php echo $i['text'] ? : 'See all properties' ?></a>
        </div>

    </div>
</div>