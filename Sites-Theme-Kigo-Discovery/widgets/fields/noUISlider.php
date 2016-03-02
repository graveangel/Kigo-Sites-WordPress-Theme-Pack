<?php
$value = empty($fi['value']) ? 0 : $fi['value'];
$aux = '<input name="'.$fi['name'].'" type="hidden" id="'.$fi['id'].'" value=\''.$value.'\'>';
$aux .= '<div data-id="'.$fi['id'].'" data-value=\''.$value.'\' class="nouislider" '.$attributes.' ></div>';
$aux .= '<script>try{kd_admin.initNoUISliders()}catch(e){}</script>';
return $aux;