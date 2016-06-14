<div class="selective-search-form">
  <!-- Widget title -->
  <label>
    <h3>Title:</h3>
    <input type="text" id="<?php echo $this->get_field_id('widget_title');?>" name="<?php echo $this->get_field_name('widget_title');?>" value="<?php echo $i['widget_title']; ?>">
  </label>

<div>
  <!-- Post types -->
  <h3>Define the post types to search:</h3>
  <?php

  $post_types = get_theme_mod('kd_post_types');

  $post_type_controls = [];

  foreach($post_types as $post_type_val):
      $checked = isset($i[$post_type_val]) ? "checked" : '';
    ?>
      <label class="inline">
        <input type="checkbox" id="<?php echo $this->get_field_id($post_type_val);?>" name="<?php echo $this->get_field_name($post_type_val);?>" <?php echo $checked;?>> <strong><?php echo ucfirst($post_type_val); ?></strong>
      </label>
  <?php
  endforeach;

  ?>
</div>
<hr>
  <!-- Advanced search -->
  <label>
    <?php
      $checked = isset($i['inherit']) ? "checked" : '';
    ?>
    <input type="checkbox" id="<?php echo $this->get_field_id('inherit');?>" name="<?php echo $this->get_field_name('inherit');?>" <?php echo $checked; ?> > <strong>Advanced Search</strong>

    <p class="description">
        <i class="fa fa-info-circle" aria-hidden="true"></i> By activating Advanced Search the user can select the post types to search.
    </p>
  </label>

  <style media="screen">
    .selective-search-form label
    {
      margin-bottom: 30px;
      display: block;
    }
    .selective-search-form label.inline
    {
      display: inline-block;
      padding:5px;
      margin-bottom: 0;
    }
    .selective-search-form hr
    {
      margin:10px 0;
    }
    .selective-search-form p.description
    {
      margin-top: 10px;
      background: #fdfdfd;
      padding: 10px;
      box-shadow: 0 0 3px rgba(0,0,0,0.1) inset;
      padding-left: 30px!important;
      position: relative;
    }
    .selective-search-form p.description .fa-info-circle
    {
      position: absolute;
      top: 10px;
      left: 10px;
    }
  </style>

</div>
