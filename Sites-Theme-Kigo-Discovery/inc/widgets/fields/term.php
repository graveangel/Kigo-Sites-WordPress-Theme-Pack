<?php

$aux = sprintf('<select name="%s" id="%s">{options}</select>', $fi['name'], $fi['id']);

$terms = get_terms( 'type' );

$termOptions = '';

foreach($terms as $term){
    $termOptions .= sprintf('<option value="%s" %s>%s</option>', $term->slug, selected($fi['value'], $term->term_id, false),$term->name);
}

return str_replace('{options}', $termOptions, $aux);


