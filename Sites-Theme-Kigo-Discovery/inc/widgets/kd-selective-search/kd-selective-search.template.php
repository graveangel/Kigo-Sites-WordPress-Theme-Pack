<div class="kd-selective-search-box">
  <!-- wiget title -->
  <h3><?php echo $i['widget_title']; ?></h3>
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
  <form class="searchform" action="/?<?php echo $search_types; ?>&s=" method="get">
    <input type="text" name="s" id ="s" value="" placeholder="Your search here...">
    <input type="hidden" name="types" value="<?php echo $search_types; ?>">
    <input type="submit" value="<?php echo render_this("{{#site}}{{textdata.Search}}{{/site}}")?>">
  </form>

</div>
