<?php

/* Old*/
$aux = '<textarea class="kd_editor" id="'.$fi['id'].'" name="'.$fi['name'].'" id="'.$fi['name'].'">'.$fi['value'].'</textarea>';

$aux .= '
<script>

        try{
        kd_admin.initCKEditors();
        }catch(e){console.log(e)}

</script>
';

return $aux;
