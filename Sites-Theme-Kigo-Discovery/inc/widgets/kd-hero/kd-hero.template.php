<?php
$slides = empty($i['slides']) ? [] : $i['slides'];
$importFonts = false;

if($i['primary_font'] || $i['secondary_font']){
    $importFonts = str_replace(' ', '+', implode('|', [$i['primary_font'], $i['secondary_font']]));
}
?>
<!-- Widget: Hero -->
<?php if($importFonts): ?>
    <style>
        @import url('https://fonts.googleapis.com/css?family=<?php echo $importFonts ?>');
    </style>
<?php endif; ?>

<div class="kd-widget">
    <div class="kd-hero"  style="color: <?php echo $i['color'] ?>">
        <div class="slider">
            <div class="swiper-container"
                <?php echo $i['effect'] ? ' data-effect="'.$i['effect'].'"' : '' ?>
                <?php echo $i['speed'] ? ' data-speed="'.$i['speed'] * 1000 .'"' : '' ?>
                <?php echo $i['transition_speed'] ? ' data-tspeed="'.$i['transition_speed'] * 1000 .'"' : '' ?>
                <?php echo $i['direction'] ? ' data-direction="'.$i['direction'].'"' : '' ?>
                <?php echo $i['freemode'] ? ' data-freemode="'.$i['freemode'].'"' : '' ?>
                <?php echo $i['pagination'] ? ' data-pagination="'.$i['pagination'].'"' : '' ?>
                <?php echo $i['loop'] ? ' data-loop="'.$i['loop'].'"' : '' ?>
                <?php echo $i['centeredSlides'] ? ' data-centered_slides="'.$i['centeredSlides'].'"' : '' ?>
                <?php echo $i['slidesPerView'] ? ' data-slides_per_view="'.$i['slidesPerView'].'"' : '' ?>
                >
                <div class="swiper-wrapper">
                    <?php foreach($slides as $url){ ?>
                        <div class="swiper-slide slide" style="background-image: url('<?php echo $url ?>')"></div>
                    <?php } ?>
                </div>
            </div>
            <div class="controls">
                <div class="prev"><i class="kd-icon-left_arrow"></i></div>
                <div class="swiper-pagination"></div>
                <div class="next"><i class="kd-icon-right_arrow"></i></div>
            </div>
        </div>
        <div class="markup">
            <?php if($i['primary_text']): ?>
            <h1 style="<?php echo $i['primary_font'] ? 'font-family: '.$i['primary_font'] : '' ?>" ><?php echo $i['primary_text'] ?></h1>
            <?php endif; if($i['secondary_text']): ?>
            <h3 style="<?php echo $i['secondary_font'] ? 'font-family: '.$i['secondary_font'] : '' ?>" ><?php echo $i['secondary_text'] ?></h3>
            <?php endif; ?>
            <?php if($i['button_value']): ?>
            <br>
            <a href="<?php echo $i['button_link'] ?>" class="kd-button" style="<?php echo $i['secondary_font'] ? 'font-family: '.$i['secondary_font'] : '' ?>"><?php echo $i['button_value'] ?></a>
            <?php endif; ?>
        </div>
        <div class="arrow visible-xs">
            <i class="kd-icon-down_arrow"></i>
        </div>
    </div>
</div>