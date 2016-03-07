<?php
$aux = '<input class="colorPicker" type="text" name="'.$fi['name'].'" id="'.$fi['id'].'" value="'.$fi['value'].'" />';
$aux .= '<script>try{kd_admin.initColorPickers()}catch(e){console.log(e)}</script>';

return $aux;
