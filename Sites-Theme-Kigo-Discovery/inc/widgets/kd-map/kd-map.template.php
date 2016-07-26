<?php
$displayContent = $i['displayContent'] == 'on';
$use = $i['use'];
$customContent = $i['customContent'];
$align = $i['alignContent'];
$col = $displayContent ? 'col-xs-12 col-md-6' : 'col-xs-12';
?>

<div class="kd-widget kd-map">
    <div class="row row-nopadding">

        <?php if($displayContent){ ?>
            <div class="primary-fill-color <?php echo $col ?> <?php echo $align == 'right' ? 'col-md-push-6' : '' ?> nopadding"><div class="side-content"><?php echo $use == 'custom' ? $customContent : $i['map'] ?></div></div>
        <?php } ?>
            <iframe
                class="<?php echo $col ?> <?php echo $align == 'right' ? 'col-md-pull-6' : '' ?> nopadding"
                height="500"
                frameborder="0"
                style="border:0"
                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAY7wxlnkMG6czYy9K-wM4OWXs0YFpFzEE&q=<?php echo !empty($i['map']) ? $i['map'] : 'Barcelona' ?><?php echo $i['zoom'] ? '&zoom='.$i['zoom'] : '' ?>" allowfullscreen>
            </iframe>

    </div>
</div>
