<div class="kd-selective-search-box">
  <?php

      /**
       * Geting posts to filter for the form
       *
       */
      $filter_post_types = [];

       if($i['inherit'])
       {
         //Getting the search query
     		$search_query = kd_get_search_query();

     		$kd_post_types = [];

     		$search_query_types = $search_query[0]['types'] ? : $search_query[1]['types'];


     		if(!empty($search_query_types))
     		{
     			$filter_post_types = explode(',',urldecode($search_query_types));
     		}


       }
       else
       {
         $kd_post_types = get_theme_mod('kd_post_types', []);


         foreach($kd_post_types as $kd_post_type_val)
         {
           if(!empty($i[$kd_post_type_val]))
             $filter_post_types[] = $kd_post_type_val;
         }
       }


      $search_types = implode(',',array_values($filter_post_types));

  ?>
  <!-- wiget title -->
  <h3><?php echo $i['widget_title']; ?></h3>


    <?php if ($i['inherit']) :?>
      <h4 class="currently-filtering">
        <?php
            $ptypes = array_values(get_theme_mod('kd_post_types'));

            foreach($ptypes as $ind => $ptype):

              $isactive = '';

              if(in_array($ptype, array_values($filter_post_types)))
              $isactive = 'primary-stroke-color';

              ?>
              <a href="#" class="toggle-filter <?php echo $isactive; ?>" data-types="#<?php echo $this->id; ?>_search_types" data-toggle="<?php echo $ptype; ?>"><?php echo $ptype; ?></a>

            <?php if($ind != (count($ptypes) - 1)): ?>
              &middot;
            <?php

            endif;
            endforeach;

            ?>
      </h4>
      <h4 class="currently-filtering"><a href="#" class="clearsearch"><?php echo render_this("{{#site}}{{textdata.Clear}}{{/site}}");?></a></h4>
    <?php else: ?>
    <h4 class="currently-filtering"><?php echo implode(' &middot; ',array_values($filter_post_types));?></h4>
    <?php endif; ?>

  <form class="searchform" action="/?<?php echo $search_types; ?>&s=" method="get">
    <input type="text" name="s" id ="s" value="" placeholder="Your search here...">
    <input type="hidden" id="<?php echo $this->id; ?>_search_types" name="types" value="<?php echo $search_types; ?>">
    <input type="submit" value="<?php echo render_this("{{#site}}{{textdata.Search}}{{/site}}")?>">
  </form>

</div>
