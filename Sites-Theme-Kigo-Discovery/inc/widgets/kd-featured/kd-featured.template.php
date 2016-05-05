<?php
$bg_left = $i['color'] ? 'style="background-color: '.$i['color'].'"' : '';
$bg_right = $i['color'] ? 'style="background-color: rgba(255,255,255,.1)"' : '';
?>
<div class="kd-widget" >
    <div class="kd-featured" <?php echo $bg_left ?>>

        <h1 class="title"><span><?php echo $i['title'] ? : __('Featured properties', 'kd') ?></span></h1>

        <div class="slider" <?php echo $bg_right ?> >
            <div class="swiper-container">
                <?php if($i['userandom']):?>
                <div id="kd_<?php echo $args['widget_id'] ?>" class="swiper-wrapper bapi-summary" data-templatename="tmpl-featuredproperties-quickview"  data-entity="property">
                    
                </div>
                <?php else: 
                        $featured_ones = get_marked_as_featured();
                        $visible = ['kdfeatured' => []];
                        foreach($featured_ones['kdfeatured'] as $featured_one){
                            foreach($i['visible_featured'] as $vf){
                                if($vf == $featured_one['ID'])
                                {
                                    $visible['kdfeatured'][] = $featured_one;
                                }
                            }
                         }
                
                        $template = get_mustache_template('marked-as-featured.tmpl');
                ?> 
                <div id="kd_<?php echo $args['widget_id'] ?>" class="swiper-wrapper"> 
                     <?php echo render_this($template,$visible, true); ?>
                </div>
                       <?php
                     endif; ?>
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