<?php
global $post;

/* Get page object */
$post = get_post($i['page']);

setup_postdata($post);

//If using featured image...
if($i['useFeatured'] == 'on'){
    $fimage = furl($page->ID);
}
//If using custom image...
$cimage = $i['image'] ? : false;

//Resolve final image. Featured image overwrites custom image.
$image = $fimage ? : $cimage;
$imageHTML = $image ? '<div class="image" style="background-image: url('.$image.')"></div>' : '';

$alignImage = $i['align'];

$contentRight = $alignImage == 'right' ? $imageHTML : '';
$contentLeft = $alignImage == 'right' ? '' : $imageHTML;

$buttons = [
    $i['button1'],
    $i['button2'],
    $i['button3'],
    $i['button4'],
];

?>
    <!-- Widget: Page Block -->
    <div class="kd-widget kd-page_block">
        <div class="wrapper primary-fill-color <?php echo $image ? 'has_image '.$alignImage : '' ?>" <?php echo $i['bgcolor'] ? 'style="background-color: '.$i['bgcolor'].'"' : '' ?> >

            <?php echo $contentLeft ?>

            <div class="info">
                <div class="inner">
                    <?php if($i['displayTitle'] == 'on' && empty($i['customTitle'])){ ?>
                        <h4 class="title"><?php the_title(); ?></h4>
                    <?php } ?>
                    <?php if(!empty($i['customTitle'])){ ?>
                        <h4 class="title"><?php echo $i['customTitle'] ?></h4>
                    <?php } ?>
                    <div class="body">
                        <?php the_content(); ?>
                    </div>
                    <div class="buttons">
                        <?php foreach($buttons as $button){ if($button['url']){ ?>
                            <a class="kd-button" href="<?php echo $button['url'] ?>"><i class="fa <?php echo $button['icon'] ?>"></i><span><?php echo $button['text'] ?></span></a>
                        <?php }} ?>
                    </div>
                </div>
            </div>

            <?php echo $contentRight ?>

        </div>
    </div>
<?php
wp_reset_postdata();