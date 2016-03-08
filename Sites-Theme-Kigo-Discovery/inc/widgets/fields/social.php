<?php

//$fi['value'] = unserialize($fi['value']);

return sprintf('

<div class="social">
<input class="url" type="text" name="%s[url]" id="%s" value="%s" />
<input class="order" type="number" name="%s[order]" value="%d" />
<input class="icon" type="text" name="%s[icon]" value="%s" />
<a href="%s" target="_blank"><i class="fa %s"></i></a>
</div>

',
    $fi['name'], $fi['id'], $fi['value']['url'],
    $fi['name'], $fi['value']['order'],
    $fi['name'], $fi['value']['icon'],
    $fi['value']['url'], $fi['value']['icon']);