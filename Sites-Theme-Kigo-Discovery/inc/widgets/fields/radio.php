<?php
$choices = isset($field['choices']) ? $field['choices'] : [];
$aux = '';
$class = isset($field['dir']) && $field['dir'] == 'horizontal' ? 'inline' : '';
foreach($choices as $choice => $cn){
$aux .= '<label class="'.$class.'"><input type="radio" '. ($fi['value']==$choice ? 'checked ':'') .'name="'.$fi['name'].'" id="'.$fi['id'].'" value="'.$choice.'" />' . $cn . ' </label> ';
}

return $aux;