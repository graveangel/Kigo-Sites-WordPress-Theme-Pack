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
        <div class="map-canvas <?php echo $col ?> <?php echo $align == 'right' ? 'col-md-pull-6' : '' ?> nopadding" data-location="<?php echo $i['map'] ? : 'Barcelona' ?>" data-zoom="<?php echo $i['zoom'] ?>">
        </div>
    </div>
</div>
