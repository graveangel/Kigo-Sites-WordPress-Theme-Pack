<?php

use Kigo\Themes\instaparent\XBAPI;

$xbapi = new XBAPI();
$pfinders = $xbapi->getPropertyFinders($ins['property_finders']);
//    dd($pfinders, true);
?>
<?php if (!empty($ins['title'])): ?>
    <div class="widget-title">
        <h1><?php echo $ins['title']; ?></h1>
    </div>
<?php endif; ?>

<div class="kigo-pfinders-items">
    <?php foreach ($pfinders as $pfind) { ?>
        <div class="kigo-pfinder width<?php echo $ins['items_per_row']; ?>">

                <div class="kigo-pfinder-content">
                    <a href="<?php echo $pfind->ContextData->SEO->DetailURL; ?>">
                    <div class="kigo-pptf-image" style="background-image: url(<?php echo $pfind->Images[0]->{'MediumURL'} ?>)">

                    </div>
                    </a>

                    <span class="kigo-pfinder-name"><a href="<?php echo $pfind->ContextData->SEO->DetailURL; ?>"><?php echo $pfind->Name ?></a></span>
                    <hr>
                    <?php echo $pfind->Description; ?>

                </div>

        </div>
    <?php }
    ?>
</div>
