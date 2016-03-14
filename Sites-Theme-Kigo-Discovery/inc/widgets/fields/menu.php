<?php
$menus = wp_get_nav_menus();

$aux = sprintf('<select id="%s" name="%s">', $fi['id'], $fi['name']);
$aux .= sprintf('<option value="0">%s</option>', _( '&mdash; Select &mdash;' ));
foreach ( $menus as $menu ) :
    $aux .= sprintf('<option value="%s" %s >', esc_attr( $menu->term_id ), selected( $fi['value'], $menu->term_id, false ));
    $aux .= sprintf('%s', esc_html( $menu->name ));
    $aux .= '</option>';
endforeach;
$aux .= '</select>';

return $aux;