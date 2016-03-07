<?php

$aux = '<select id="'.$fi['id'].'" name="'.$fi['name'].'" id="'.$fi['name'].'"><option value="">-</option>';

$fonts = customizer_library_get_google_fonts();

$options = '';

foreach($fonts as $fontname => $font_props){
    $selected = '';
    if($fontname  === $fi['value']) $selected = 'selected';
    
    $option = '<option value="' . $fontname . '" ' . $selected . '>' . $fontname . '</option>';
    $options .= $option; 
}

$aux .= $options;
$aux .='</select>'
        . '<div id="preview_' .$fi['id']. '" class="font-preview">' 
        . $fi['value'] 
        . '</div>'
        . '<style id="style_' . $fi['id'] . '" type="text/css">'
        . '</style>'
        . '<script>'
        . '$(function(){'
        . '         $("#' .$fi['id']. '").change(setFont).change();'
        . '});'
        . '</script>';




return $aux;