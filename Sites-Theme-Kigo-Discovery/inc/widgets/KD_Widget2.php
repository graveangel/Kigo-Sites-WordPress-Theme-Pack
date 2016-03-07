<?php
//TODO: Clean all widgets, rename W2 to final name & refactor
class KD_Widget2 extends WP_Widget {

    /**
     * @var $controls Multidimensional array containing tabs and their fields.
     *
     * [
     *      First tab...
     *      ['name' => 'Tab name', 'fields' =>
     *          [
     *              'field_name' => ['type' => 'field_type', ...],
     *              'field_name' => ['type' => 'field_type', ...],
     *          ]
     *      ],
     *      Second tab...
     *      [...]
     * ]
     *
     */
    protected $controls;

    public function widget( $args, $i ) {
        // outputs the content of the widget
        echo '<div class="widget col-xs-12">';
        include $this->filename.'/'.$this->filename.'.template.php';
        echo '</div>';
    }

    public function form( $instance ) {
        // outputs the options form on admin

        /* If we find a custom form template we display it, if not we build form from controls  */
//        $formTemplate = __DIR__.'/'.$this->filename.'/'.$this->filename.'.form.php';

//        if(file_exists($formTemplate)){
//            include $formTemplate;
//        }else{
        echo $this->buildForm($instance);
//        }
    }

    public function update( $new_instance, $old_instance ) {
//        debug($new_instance);
        return $new_instance;
    }

    public function buildForm($instance){
        $tabControls = $tabContents = $form = '';
        /* Loop for each control group (tab) */
        foreach($this->controls as $index => $tab){
            $tabControls .= sprintf('<div class="tab %s">%s</div>', $index == 0 ? 'active' : '', $tab['name']);
            $tabFields = '';
            /* Build the fields inside a tab */
            $tabFields .= $this->buildFields($tab['fields'], $instance);
            /* Build the tab */
            $tabContents .= sprintf('<div class="tab %s">%s</div>', $index == 0 ? 'active' : '', $tabFields);
        }
        /* Build the resulting widget form html */
        $form = sprintf('
            <div class="widget-tabs">
                <div class="tab-controls">
                    %s
                </div>
                <div class="tab-contents">
                    %s
                </div>
            </div>', $tabControls, $tabContents);
        $form .= '<script>try{kd_admin.initWidgetTabs();}catch(e){}</script>';
        return $form;
    }

    public function buildFields($fields, $instance, $cloneBlock = false){
        $fieldsHTML = $prefix = $suffix = '';

        foreach($fields as $name => $field){

            /* Get field instance values: id, name, value */
            $fi = $this->getFieldsData($name, $instance, $cloneBlock);

            /* Prepare field label */
            if(isset($field['label'])){
                $fieldsHTML  .= sprintf('<label for="%s">%s</label>',$fi['id'] , $field['label']);
            }
            
            /* Prepare field description */
            if(isset($field['description'])){
                $fieldsHTML  .= sprintf('<p class="kd-widget-field-description">%s</p>',$field['description']);
            }

            /* If the input has attributes, generate corresponding HTML attributes string */
            $attributes = isset($field['attrs']) ? implode(' ', array_map(function($attr, $val){return sprintf('%s="%s"', $attr, $val);}, array_keys($field['attrs']), $field['attrs'])) : '';

            /* If it's a clone field block, create it */

            if($field['type'] == 'clone'){
                $fieldsHTML .= $this->buildFields($field['fields'], $instance, true);
            }else{
                /* Check if the field template exists, if not use text */
                $fieldTmpl = '/fields/'.$field['type'].'.php';

                if(file_exists(__DIR__.$fieldTmpl)){
                    $fieldsHTML  .= include 'fields/'.$field['type'].'.php';
                }else{
                    $fieldsHTML  .= include 'fields/text.php';
                }
            }
        }
        return $prefix.$fieldsHTML.$suffix;
    }

    public function getFieldsData($fieldName, $instance, $isClone = false){
        $aux = [];
        $aux['id'] = $this->get_field_id($fieldName);
        $aux['name'] = $this->get_field_name($fieldName);

        /* If this field is part of a clone block, we update its input name to store multiple */
        if($isClone){
            $aux['name'] = str_replace($fieldName,$fieldName.'][]',$this->get_field_name($fieldName));
        }

        $aux['value'] = isset($instance[$fieldName]) ? $instance[$fieldName] : false;

        return $aux;
    }

}
