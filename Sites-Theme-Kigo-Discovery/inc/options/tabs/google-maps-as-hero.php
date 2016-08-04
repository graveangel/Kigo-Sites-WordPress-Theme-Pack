<div class="kd-properties-hero">
                <h1>Use Google map as Hero</h1>
                <form action="" method="post">
                    <?php
                    //Bapi properties
                    $sd = json_decode(get_option('bapi_keywords_array'));
                    $properties = array();
                    foreach($sd as $ent){
                        if($ent->entity === "property"){
                            $properties[]=$ent;
                        }
                    }
                    //Theme selected properties;
                    $selected_props = json_decode(get_theme_mod('kd_usemap_properties'));


                    ?>
                    <h3>Select the properties for which you wish to use a Google map instead of a Hero image.</h3>
                    <select name="kd-option-properties[usemap][]" id="kd-option-properties-usemap" multiple>
                        <?php
                        foreach($properties as $property){
                            $selected =  '';
                            foreach($selected_props as $selpropID){
                                if($selpropID == $property->pkid){


                                    $selected = 'selected';
                                    break;
                                }

                            }
                            ?>
                            <option value="<?php echo $property->pkid; ?>" <?php echo $selected; ?>><?php echo $property->Name; ?></option>
                        <?php
                        }
                        ?>
                    </select>


                    <fieldset class="fieldset-layout">
                        <h2>Select layout</h2>

                        <label>
                            <?php if((int)get_theme_mod('kd_usemap_properties_layout') !== 0 && (int)get_theme_mod('kd_usemap_properties_layout') !== 1 && (int)get_theme_mod('kd_usemap_properties_layout') !== 2){
                                set_theme_mod('kd_usemap_properties_layout',1);
                            }?>
                            <input type="radio" name="kd-option-properties[layout]" value=0 <?php if((int)get_theme_mod('kd_usemap_properties_layout')===0){echo "checked";} ?>> Only map</label>

                        <label>
                            <input type="radio" name="kd-option-properties[layout]" value=1 <?php if((int)get_theme_mod('kd_usemap_properties_layout')===1){echo "checked";} ?>> Map & StreetView</label>

                        <label>
                            <input type="radio" name="kd-option-properties[layout]" value=2 <?php if((int)get_theme_mod('kd_usemap_properties_layout')===2){echo "checked";} ?>> Only StreetView</label>
                    </fieldset>

                    <input type="submit" name="kd-option-properties[send]" value="Save" class="kd-button filled">
                </form>
            </div>

<script type="text/javascript">
    $('#kd-option-properties-usemap').multiSelect({
        selectableHeader: "<div class='custom-header'><a href='#' class='kd-button filled selectall'>Select All</a></div><input type='text' class='search-input' autocomplete='off' placeholder='Search within options'>",
        selectionHeader: "<div class='custom-header'><a href='#' class='kd-button filled deselectall'>Deselect All</a></div><input type='text' class='search-input' autocomplete='off' placeholder='Search within selected'>",
        afterInit: function(ms){
            var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e){
                    if (e.which === 40){
                        that.$selectableUl.focus();
                        return false;
                    }
                });

            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e){
                    if (e.which == 40){
                        that.$selectionUl.focus();
                        return false;
                    }
                });
        }
    });

    $('.selectall').click(function(e){
        e.preventDefault();
        $('#kd-option-properties-usemap').multiSelect('select_all');
    });

    $('.deselectall').click(function(e){
        e.preventDefault();
        $('#kd-option-properties-usemap').multiSelect('deselect_all');
    });

    function cookieTab(e){
        setCookie('kd-option-tab',e.getAttribute('aria-controls'),365);
    }

    $('.tab-link').click(function(e){
        cookieTab(e.target);
    });

    $(function(){
        var tab = getCookie('kd-option-tab');
        if(tab=='') $('a.tab-link[aria-controls="tab1"]').click();
        $('a.tab-link[aria-controls="'+tab+'"]').click();
    })
</script>
