<?php
$address = $fi['value'] ? : 'Barcelona';
$aux = '<iframe
          width="100%"
          height="300"
          frameborder="0"
          style="border:0"
          src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAY7wxlnkMG6czYy9K-wM4OWXs0YFpFzEE&q='.$address.'" allowfullscreen>
        </iframe>';

$aux .= sprintf('<label for="%s">%s</label>', $fi['id'], __('Location'));
$aux .= sprintf('<input type="text" id="%s" name="%s" value="%s" placeholder="%s" />', $fi['id'], $fi['name'], $fi['value'], __('Address or coordinates...', 'kd'));
return $aux;
