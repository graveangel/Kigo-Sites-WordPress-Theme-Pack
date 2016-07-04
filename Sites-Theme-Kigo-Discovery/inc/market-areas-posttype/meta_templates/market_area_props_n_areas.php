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
        <h3>Select Location(s):
            <input type="search" id="search-locations" placeholder="Filter Locations" value="">
            <select class="search-locations-select">
                <option value=""> - Filter - </option>
                <?php foreach($MktAPropsNAreas->ma_types as $type):?>
                    <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                <?php endforeach; ?>
            </select>
        </h3>

        <div class="list">
          <ol class="market-areas-tree ma-list-parent sortable sortable-group">
              <?php
                  echo $MktAPropsNAreas->get_ma_items($MktAPropsNAreas->locations);
              ?>
          </ol>
        </div>
        <ul class="legend">
            <?php foreach($MktAPropsNAreas->ma_types as $type):?>
                <li><span class="<?php echo $type; ?>"></span><?php echo $type; ?></li>
            <?php endforeach; ?>
        </ul>
      </div>

      <div class="properties-list">
        <!-- property tags -->
        <h3>Properties: <input type="search" id="search-properties" placeholder="Search Properties" value=""></h3>
        <p><label><input type="checkbox" class="select-visible"> Select all visible.</label> <a class="button button-primary add-selected">Add selected</a></p>
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
      <h3>Market Area:</h3>
      <div class="drop-here">
        <ol class="market-areas-tree to-save sortable sortable-group">
        </ol>
      </div>
      <!-- Clear button -->
      <a class="clear-areas button button-primary" title="Clears the tree">Clear</a>
      <!-- Reset areas -->
      <a class="reset-areas button button-primary" title="Resets the tree to the saved value">Reset</a>

      <!-- Save -->
      <input name="save" type="submit" class="button button-primary button-save" id="publish" value="Save">
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