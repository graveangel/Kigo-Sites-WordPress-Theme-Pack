<?php

$aux = sprintf('

<div class="buttonInputs">
    <input type="text" placeholder="Text" class="third" id="%s" name="%s[text]" value="%s" />
    <input type="text" placeholder="URL" class="third" name="%s[url]" value="%s" />
    <div class="iconPickerWrapper third">
        <input type="text" placeholder="Icon" class="iconPicker" name="%s[icon]" value="%s" /><i data-holder class="%s"></i>
    </div>
</div>
', $fi['id'], $fi['name'], $fi['value']['text'],
    $fi['name'], $fi['value']['url'],
    $fi['name'], $fi['value']['icon'], 'fa '.$fi['value']['icon']);
return $aux;