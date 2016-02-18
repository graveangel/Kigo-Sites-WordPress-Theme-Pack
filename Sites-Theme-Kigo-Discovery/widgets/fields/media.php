<?php
$value = empty($fi['value']) ? [] : (array)$fi['value']; //cast to array if single image to preserve iteration
$multiple = isset($field['multiple']) ? $field['multiple'] : false;
$type = isset($field['mediaType']) ? $field['mediaType'] : 'image';

$aux .= $multiple ? '<input name="'.$fi['name'].'" type="text" name="'.$fi['name'].'" value="">' : '';

$aux = '<div class="widget-media is_sortable">';
foreach($value as $pos => $url){
    $aux .= '<div class="widget-media-item" style="background-image: url('.$url.')"><input type="hidden" name="'.$fi['name'].($multiple ? '[]' : '').'" value="'.$url.'" /><div class="delete" onclick="this.parentElement.firstChild.dispatchEvent(new Event(\'change\', {\'bubbles\': true}));this.parentElement.remove();">Delete</div></div>';
}
$aux .= '</div><button data-type="'.$type.'" data-multiple="'.($multiple ? 1 : 0).'" data-id="'.$fi['id'].'" data-name="'.$fi['name'].'" class="upload_button button button-primary">'.($multiple ? ($type == 'video' ? 'Add Videos' : 'Add Images') : ($type == 'video' ? 'Add Video' : 'Add Image') ).'</button>';

$aux .= '<script>try{kd_admin.initSortables()}catch(e){}</script>';

return $aux;