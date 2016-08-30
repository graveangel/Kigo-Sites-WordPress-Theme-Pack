<?php
$address = $fi['value'] ? : 'Barcelona';
//$aux = '<div class="map-canvas init" style="height: 350px;" data-location="'.$fi['value'].'" ></div>';

$aux = sprintf('<label for="%s">%s</label>', $fi['id'], __('Location'));
$aux .= sprintf('<input type="text" id="%s" name="%s" value="%s" placeholder="%s" />', $fi['id'], $fi['name'], $fi['value'], __('Address or coordinates...', 'kd'));
$aux .= '<script>kd_admin.maps();</script>';
return $aux;
