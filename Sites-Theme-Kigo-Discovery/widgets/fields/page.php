<?php
$pagesDropdown = wp_dropdown_pages(['name' => $fi['name'], 'selected' => $fi['value'], 'echo' => 0]);
$aux = $pagesDropdown;
return $aux;