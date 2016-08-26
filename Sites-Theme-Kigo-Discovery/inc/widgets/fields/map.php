<?php
$address = $fi['value'] ? : 'Barcelona';
<<<<<<< HEAD
//$aux = '<div class="map-canvas init" style="height: 350px;" data-location="'.$fi['value'].'" ></div>';
=======
$aux = '<iframe
          width="100%"
          height="300"
          frameborder="0"
          style="border:0"
          src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAY7wxlnkMG6czYy9K-wM4OWXs0YFpFzEE&q='.$address.'" allowfullscreen>
        </iframe>';
>>>>>>> feature/Main_MKTA_STACKS_LANDING

$aux = sprintf('<label for="%s">%s</label>', $fi['id'], __('Location'));
$aux .= sprintf('<input type="text" id="%s" name="%s" value="%s" placeholder="%s" />', $fi['id'], $fi['name'], $fi['value'], __('Address or coordinates...', 'kd'));
<<<<<<< HEAD
$aux .= '<script>kd_admin.maps();</script>';
=======
>>>>>>> feature/Main_MKTA_STACKS_LANDING
return $aux;
