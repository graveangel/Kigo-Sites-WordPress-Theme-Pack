<?php
$MktAPropsNAreas = new MktAPropsNAreas();
$value = get_post_meta($object->ID, $this->id, true); //Field value
?>
<!-- Form token -->
<?php wp_nonce_field(basename(__FILE__), $this->id . '_nonce'); ?>
<!-- Description -->
<label for="<?php echo $this->id; ?>"><p><?php _e($this->description, 'kd'); ?></p></label>


<!-- The list of market areas -->
<div class="market-areas-sorting">


    <!-- Sources -->
    <div class="sources">
      <!-- location tags -->
      <div class="locations-list">
        <h3>Locations <input type="text" id="search-locations" placeholder="Filter Locations" value=""></h3>
        <div class="list">
          <ol class="market-areas-tree ma-list-parent sortable sortable-group">
              <?php
                  echo $MktAPropsNAreas->get_ma_items($MktAPropsNAreas->locations);
              ?>
          </ol>
        </div>
      </div>

      <div class="properties-list">
        <!-- property tags -->
        <h3>Properties <input type="text" id="search-properties" placeholder="Filter Properties" value=""></h3>
        <div class="list">
          <ol class="market-areas-tree prop-list-parent sortable sortable-group">
              <?php
                  echo $MktAPropsNAreas->get_prop_items();
              ?>
          </ol>
        </div>
      </div>
    </div>

    <div class="user-list">
      <!-- User tree -->
      <h3>Build your areas</h3>
      <p class="description">
          Here you can build your market area's tree. To rename an area you can doubleclick on the location button in the tree.
      </p>
      <!-- Clear button -->
      <a class="clear-areas button button-primary" title="Clears the tree">Clear</a>
      <!-- Reset areas -->
      <a class="reset-areas button button-primary" title="Resets the tree to the saved value">Reset</a>

      <div class="drop-here">
         - DROP HERE -
        <ol class="market-areas-tree to-save sortable sortable-group">
        </ol>
      </div>
    </div>

</div>

<input class="widefat" type="hidden" name="<?php echo $this->id; ?>" id="<?php echo $this->id; ?>" value='<?php echo $value; ?>' size="30" />

<script type="text/javascript">
//Tree setup (when page loads)
<?php $fvalue = empty($value) ? '[]' : $value ; ?>
var field_value = '<?php echo addslashes($fvalue); ?>';
    field_value = JSON.parse(field_value);


var hidden_selector = 'input#<?php echo $this->id; ?>';
</script>
