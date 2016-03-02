<?php
$url = $instance['image'] ? : '';
$link = $instance['link'] ? : '#';
$height = $instance['height'] ? : '200px';

$img = '<img src="'.$url.'" />';

if(!empty($link)){
    $img = '<div class="kd-widget kd-image row row-nopadding"><a href="'.$link.'">'.$img.'</a></div>';
}

echo $img;
