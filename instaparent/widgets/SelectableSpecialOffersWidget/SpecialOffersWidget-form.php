<p class="description">
    Enable the special offers to display in this widget. You can sort them by dragging them.
</p>

<!--Widget title-->
<h3>Title</h3>
<p>
    <input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $ins['title']; ?>">
</p>


<label>
    <h3>Items per row:</h3>
    <select name="<?php echo $this->get_field_name('items_per_row'); ?>" id="<?php echo $this->get_field_id('items_per_row'); ?>">
        <?php
            for($i=1; $i<5; $i++){
                $selected = '';
                if($i == (int) $ins['items_per_row'] )
                {
                    $selected = 'selected';
                }
                ?>
                <option value="<?php echo $i; ?>" <?php echo $selected; ?>>
                    <?php echo $i; ?>
                </option>
            <?php }
        ?>
    </select>
</label>


<!--Select Special Offers-->
<h3>Select Special Offers</h3>
<ul class="sortable special_offers_sortable_list">
       <?php

       foreach($ins['special_offers'] as $spoffid => $name){

            ?>
            <li>
            <label>
                <input type="checkbox" name="<?php echo $this->get_field_name('special_offers'); ?>[<?php echo $spoffid; ?>]" checked value="<?php echo $spoffid; ?>">
                <b><?php echo $this->getSpecialOffers()[$name]; ?></b>
                <span class="draggme">&equiv;</span>
            </label>
            </li>
                <?php
        }

        foreach($this->getSpecialOffers() as $spoffid => $name){
            $checked = '';
            if(!empty($ins['special_offers'][$spoffid])) continue;
            ?>
            <li>
            <label>
                <input type="checkbox" name="<?php echo $this->get_field_name('special_offers'); ?>[<?php echo $spoffid; ?>]" value="<?php echo $spoffid; ?>">
                <b><?php echo $name; ?></b>
                <span class="draggme">&equiv;</span>
            </label>
            </li>
                <?php
        }
    ?>
</ul>
<script>
$('.sortable').sortable();
</script>
