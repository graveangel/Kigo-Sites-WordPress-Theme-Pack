<?php
    /**
     * Geting posts to filter for the form
     */
    $filter_post_types = [];
    $search_text = render_this('{{#site}}{{textdata.Search}}{{/site}}');
    $clear_text = render_this('{{#site}}{{textdata.Clear}}{{/site}}');

     if(!empty($i['inherit']))
     {
       //Getting the search query
      $search_query = kd_get_search_query();

      $kd_post_types = [];

      $search_query_types = $search_query[0]['types'] ? : $search_query[1]['types'];

      if(!empty($search_query_types))
      {
        $filter_post_types = explode(',',urldecode($search_query_types));
      }


     $placeholder = $search_text;
     }
     else
     {
       $kd_post_types = get_theme_mod('kd_post_types');
       foreach($kd_post_types as $kd_post_type_val)
       {
         if(!empty($i[$kd_post_type_val]))
           {
             $filter_post_types[] = $kd_post_type_val;
           }
       }


      $placeholder = count($filter_post_types) ? $search_text . ': ' . implode(', ',array_values($filter_post_types)) : $search_text;

     }


    $search_types = !empty($filter_post_types) ? implode(',',array_values($filter_post_types)) : '';


    $advanced = '';

    if(!empty($i['inherit']))
    $advanced = 'advanced';



?>
<div class="kd-selective-search-box <?php echo $advanced; ?>">
  <form class="searchform" action="/?<?php echo $search_types; ?>&s=" method="get">

    <!-- wiget title -->
    <?php if(isset($i['widget_title'])):?>
      <h3 class="widget_title"><?php echo $i['widget_title']; ?></h3>
    <?php endif; ?>


    <!-- Fields -->
    <input type="text" name="s" id ="s" class="inline <?php echo $advanced; ?>" value="" placeholder="<?php echo $placeholder; ?>">
    <input type="hidden" class="inline <?php echo $advanced; ?>" id="<?php echo $this->id; ?>_search_types" name="types" value="<?php echo $search_types; ?>">

    <?php if (!empty($advanced)) : ?>
    <!-- Select types -->
    <div class="inline select-types">
        <!-- Filter by -->
        <a href="#" class="filter-by">
          <svg class="filter-icon" viewBox="0 0 24 24"><title>icon-filter</title><g fill-rule="evenodd"><path d="M23.75 19H15v-1.75a.25.25 0 0 0-.25-.25h-8.5a.25.25 0 0 0-.25.25V19H.25a.25.25 0 0 0-.25.25v1.5c0 .138.112.25.25.25H6v1.75c0 .138.112.25.25.25h8.5a.25.25 0 0 0 .25-.25V21h8.75a.25.25 0 0 0 .25-.25v-1.5a.25.25 0 0 0-.25-.25zM8 19h5v2H8v-2zm15.75-8H21V9.25a.25.25 0 0 0-.25-.25h-8.5a.25.25 0 0 0-.25.25V11H.25a.25.25 0 0 0-.25.25v1.5c0 .138.112.25.25.25H12v1.75c0 .138.112.25.25.25h8.5a.25.25 0 0 0 .25-.25V13h2.75a.25.25 0 0 0 .25-.25v-1.5a.25.25 0 0 0-.25-.25zM14 11h5v2h-5v-2zm9.75-8H12V1.25a.25.25 0 0 0-.25-.25h-8.5a.25.25 0 0 0-.25.25V3H.25a.25.25 0 0 0-.25.25v1.5c0 .138.112.25.25.25H3v1.75c0 .138.112.25.25.25h8.5a.25.25 0 0 0 .25-.25V5h11.75a.25.25 0 0 0 .25-.25v-1.5a.25.25 0 0 0-.25-.25zM5 3h5v2H5V3z"></path></g></svg>
          <?php echo render_this('{{#site}}{{textdata.Filter by}}{{/site}}');?>
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
          <a href="#" class="clearsearch" data-types="#<?php echo $this->id; ?>_search_types"><?php echo $clear_text . ' ' . $search_text; ?></a>
        </div>
    </div>
      <?php endif; ?>



      <button type="submit" class="inline <?php echo $advanced; ?> make-search primary-fill-color"><i class="fa fa-search" aria-hidden="true"></i></button>

  </form>

</div>
