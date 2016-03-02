<div class="property-detail-settings">
    <h1>Property details settings</h1><a href="#" class="kd-button filled" id="save-props-settings">SAVE</a>
    <p>Select a property from the options below, or filter the properties using the search box. <br> Clicking SAVE will save all the current setup for all properties.</p> 
        <hr>
    <form id="seach-props">
        <input type="text" placeholder="Type a property name" id="filter-props" class="search-input">
    </form>
    <ul class="properties-available inline-block">
        <?php foreach ($properties as $property): ?>
            <li data-prop="<?php echo $property->Name; ?>" data-pkid="<?php echo $property->pkid; ?>" data-detailurl="<?php echo $property->DetailURL; ?>" class="property-buttons kd-button filled">
                <?php echo $property->Name; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="inline-block setup-prop-form">
        <h1 id="property-name"></h1><br><label><input type="checkbox" id="set_as_featured"><strong>Mark as featured property</strong></label>
        
        <hr>
         <h2>Custom script</h2>
        <label><input type="checkbox" id="prop-custom-script"><strong>Enable custom script for this property page.</strong></label>
        <p>This script will load in the body.</p>
        <div id="prop-script-editor"></div>
        <hr>
        <label><input type="checkbox" id="force-usemap"><strong>Force use map as as Hero.</strong></label>
        <br>
        <h2>Select forced map layout</h2>
        <label><input type="radio" name="forcemap_layout" value=0 > Only map</label>

        <label><input type="radio" name="forcemap_layout" value=1> Map & StreetView</label>

        <label><input type="radio" name="forcemap_layout" value=2> Only StreetView</label>
        
    </div>

        
        <!-- FORM TO SEND -->
        <form method="post" id="property-detail-settings-form"><input type="hidden" name="property-detail-settings" id="property-scripts" /></form>
        <!-- FORM TO SEND -->
    <!-- Controls -->
    <!-- end controls -->
</div>
<style type="text/css">
    h1, h1+#save-props-settings { display:inline-block; margin-right: 20px; }
    #filter-props {
        max-width: 400px;
    }
    hr {
        margin: 20px auto 10px;
    }
    .properties-available{
        width: 400px;
    }
    .properties-available li{
        margin-right: 10px;
        margin-bottom: 10px;
    }
    .inline-block{
        display: inline-block;
    }
    .kd-options-panel .property-buttons.active, .property-buttons:hover{
        background-color: tomato!important;
    }
    .setup-prop-form {
        display: none;
        height: auto;
        vertical-align: top;
    }
    #prop-script-editor{
        width: 800px;
        height: 400px;
    }
    #save-props-settings {
        display: inline-block;
    }
</style>
<script type="text/javascript">
    var properties_setup = {};
        properties_setup_str = "<?php echo get_theme_mod('kd_properties_settings'); ?>";
        if(properties_setup_str!==""){
            properties_setup = JSON.parse(properties_setup_str);
        }
    var current_prop = '';
    var current_prop_pkid = '';
    var editor;
    
    function clear_form(){
        $('#prop-custom-script').attr('checked', false);
        editor.setValue("");
        $('#force-usemap').attr('checked', false);
        $('#set_as_featured').attr('checked', false);
        $('input[name="forcemap_layout"]').attr('checked', false);
    }
    function load_data(){
        
         if(typeof properties_setup[current_prop_pkid] === "undefined")
        {
            clear_form();
        }else{
            
            if(typeof properties_setup[current_prop_pkid]['script_enabled'] !== "undefined")
                $('#prop-custom-script').attr('checked', properties_setup[current_prop_pkid]['script_enabled']);
            else
                $('#prop-custom-script').attr('checked', false);
            
            if(typeof properties_setup[current_prop_pkid]['script_value'] !== "undefined")
                {
                    var script_val = '';
                    try{
                        script_val = properties_setup[current_prop_pkid]['script_value'];
                    }catch(e){
                        //
                    }
                    editor.setValue(script_val);
                }
            else
                editor.setValue("");
            
            if(typeof properties_setup[current_prop_pkid]['force_usemap'] !== "undefined")
                $('#force-usemap').attr('checked', properties_setup[current_prop_pkid]['force_usemap']);
            else
                $('#force-usemap').attr('checked', false);
            
            if(typeof properties_setup[current_prop_pkid]['usemap_layout'] !== "undefined")
                $('input[name="forcemap_layout"]').filter('[value="'+properties_setup[current_prop_pkid]['usemap_layout']+'"]').attr('checked', true);
            else
                $('input[name="forcemap_layout"]').attr('checked', false);
            
            if(typeof properties_setup[current_prop_pkid]['forced_featured'] !== "undefined")
                $('#set_as_featured').attr('checked',properties_setup[current_prop_pkid]['forced_featured']);
            else
                $('#set_as_featured').attr('checked',false);
        }
    }
    function setup_prop_form() {
        
        $('#property-name').html('<a href="' + current_prop_detailurl + '" target="_blank">' + current_prop + '</a>');
        
        setCookie('kd-props-settings',current_prop_pkid,365);
        
        load_data();
        
        if(typeof properties_setup[current_prop_pkid] === "undefined")
        {
            properties_setup[current_prop_pkid] = {};
        }
        
        //enable custom script for this property
        $('#prop-custom-script').change(function(e){
            properties_setup[current_prop_pkid]['script_enabled'] = this.checked;
        });
        //Save property script into array.
        editor.getSession().on('change', function(e) {
            try{
            properties_setup[current_prop_pkid]['script_value'] = editor.getValue();
            } catch(e){
                //console.log(e);
            }
        });
        //force usemap
        $('#force-usemap').change(function(e){
            properties_setup[current_prop_pkid]['force_usemap'] = this.checked;
        });
        //map layout
        $('input[name="forcemap_layout"]').change(function(e){
            properties_setup[current_prop_pkid]['usemap_layout'] = this.value;
        });
        //featured property
        $('#set_as_featured').change(function(e){
            properties_setup[current_prop_pkid]['forced_featured'] = this.checked;
        });
        
        
    }
    function sendform(e){
        e.preventDefault();
        $('#property-scripts').val(JSON.stringify(properties_setup));
        $('#property-detail-settings-form').submit();
    }
    $(function () {

        //Enable quicksearck
        $('input#filter-props').quicksearch('.properties-available li');
        //enable select prop
        $('.property-buttons').click(function (e) {
            $('.property-buttons.active').toggleClass('active');
            $(this).toggleClass('active');
            current_prop = $(this).attr('data-prop');
            current_prop_pkid = $(this).attr('data-pkid');
            current_prop_detailurl = $(this).attr('data-detailurl');
            setup_prop_form();
            //Display form
            $('.setup-prop-form').css('display', 'inline-block');
        });
        
        //Enable SAve button
        $('#save-props-settings').click(sendform);
        
        //enable ace editor.
        editor = ace.edit("prop-script-editor");
        editor.setTheme("ace/theme/monokai");
        editor.getSession().setMode("ace/mode/javascript");
        
        //Auto select last prop
        var last_selected = getCookie('kd-props-settings');
        $('li[data-pkid="'+last_selected+'"]').click();
    });
</script>
