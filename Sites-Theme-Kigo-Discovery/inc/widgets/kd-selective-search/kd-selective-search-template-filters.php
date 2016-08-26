<!-- FILTERS -->

<!-- Select types -->
<div class="inline select-types">


    <!-- Filter by -->
    <a href="#" class="filter-by">
      <div class="center"><svg class="filter-icon" viewBox="0 0 24 24"><title>icon-filter</title><g fill-rule="evenodd"><path d="M23.75 19H15v-1.75a.25.25 0 0 0-.25-.25h-8.5a.25.25 0 0 0-.25.25V19H.25a.25.25 0 0 0-.25.25v1.5c0 .138.112.25.25.25H6v1.75c0 .138.112.25.25.25h8.5a.25.25 0 0 0 .25-.25V21h8.75a.25.25 0 0 0 .25-.25v-1.5a.25.25 0 0 0-.25-.25zM8 19h5v2H8v-2zm15.75-8H21V9.25a.25.25 0 0 0-.25-.25h-8.5a.25.25 0 0 0-.25.25V11H.25a.25.25 0 0 0-.25.25v1.5c0 .138.112.25.25.25H12v1.75c0 .138.112.25.25.25h8.5a.25.25 0 0 0 .25-.25V13h2.75a.25.25 0 0 0 .25-.25v-1.5a.25.25 0 0 0-.25-.25zM14 11h5v2h-5v-2zm9.75-8H12V1.25a.25.25 0 0 0-.25-.25h-8.5a.25.25 0 0 0-.25.25V3H.25a.25.25 0 0 0-.25.25v1.5c0 .138.112.25.25.25H3v1.75c0 .138.112.25.25.25h8.5a.25.25 0 0 0 .25-.25V5h11.75a.25.25 0 0 0 .25-.25v-1.5a.25.25 0 0 0-.25-.25zM5 3h5v2H5V3z"></path></g></svg>
      <span><?php echo render_this('{{#site}}{{textdata.Filter by}}{{/site}}');?></span></div>
    </a>

</div>


<!-- Post types -->
<div class="currently-filtering advanced">
    
<?php
    $ptypes = array_values(get_theme_mod('kd_post_types'));

    foreach($ptypes as $ind => $ptype):

    $isactive = '';
    $checked = '';
    if(in_array($ptype, array_values($filter_post_types)))
    {
      $isactive = 'active';
      $checked = 'checked';
    }
    ?>
    <div class="ptype">
    <input type="checkbox" class="typecheck" <?php echo $checked; ?>> <a href="#" class="toggle-filter <?php echo $isactive; ?>" data-types="#<?php echo $this->id; ?>_search_types" data-toggle="<?php echo $ptype; ?>"><?php echo ucfirst($ptype); ?></a>
    </div>

    <?php


    endforeach;
?>

<div class="ptype clear">
  <a href="#" class="clearsearch primary-stroke-color" data-types="#<?php echo $this->id; ?>_search_types"><?php echo $clear_text . ' ' . $filter_by_text; ?></a>
</div>


</div>
