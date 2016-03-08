<?php
$mode = isset($i['mode']) ? $i['mode'] : false;
$image = isset($i['image']) ? $i['image'] : false;
$text = isset($i['text']) ? $i['text'] : false;
$font = isset($i['font']) ? $i['font'] : false;
$link = esc_url( home_url( '/' ) );

if($mode && $mode == 'image'){
    printf('<a class="logo-link" href="%s" ><img class="logo-image" src="%s" alt="" height="100px" /></a>', $link, $image);
}

if($mode && $mode == 'text'){ ?>
    <style>@import url(https://fonts.googleapis.com/css?family=<?php echo str_replace(' ', '+', $font) ?>); span.logoFont{font-family: <?php $onlyFontName = explode(':', $font); echo array_shift($onlyFontName) ?>}</style>
    <?php
    printf('<a href="%s" class="logo-link"><span class="logoFont">%s</span></a>', $link, $text);
}

if($mode && $mode == 'bapi'){
    $wrapper = getbapisolutiondata();
    $logo = $wrapper["site"]["SolutionLogo"];
    printf('<a class="logo-link" href="%s" ><img class="logo-image" src="%s" alt="" height="100px" /></a>', $link, $logo);
}