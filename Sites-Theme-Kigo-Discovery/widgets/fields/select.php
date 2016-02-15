<?php
$optionsHTML   = '';
$multiple      = $field['multiple'] ? 'multiple' : '';
$multipleClass = $field['multiple'] ? 'class="multiselectjs"' : '';
$asarray       = $field['multiple'] ? '[]' : '';

foreach($field['options'] as $value => $option){

        if(is_array($fi['value']))
            foreach($fi['value'] as $fival){
                $selected = selected($fival, $value, false);
                if(!empty($selected))
                    break;
            }
         else
             $selected = selected($fi['value'], $value, false);
   
        $optionsHTML .= sprintf('<option value="%s" %s >%s</option>', $value, $selected, $option);

}

return sprintf('
<select name="%s%s" id="%s" %s %s>
%s
</select>
', $fi['name'],$asarray, $fi['id'],$multiple, $multipleClass, $optionsHTML) . '<script type="text/javascript">try{kd_admin.multiSelects(); kd_admin.multiSelectsCheckbox(); }catch(e){/*pass*/}</script>';
