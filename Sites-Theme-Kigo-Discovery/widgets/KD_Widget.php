<?php

class KD_Widget extends WP_Widget {

    protected $fields, $widget, $form;

    /**
     * Build a widget admin form from it's fields.
     *
     * @param $instance
     * @return string
     */
    public function buildForm($instance, $onlyCommon = false){

        $fields = isset($this->fields) ? $this->fields : [];

        $commonFields = [
//            'sizeDesktop' => ['type' => 'size', 'label' => 'Desktop widget size'],
//            'sizeMobile' => ['type' => 'size', 'label' => 'Mobile widget size'],
//            'deskopAlignment' => ['type' => 'radio' ,'label'=>'Desktop Alignment', 'choices'=> ['left','center','right']],
//            'mobileAlignment' => ['type' => 'radio' ,'label'=>'Mobile Alignment', 'choices'=> ['left','center','right']],
        ];

        $fields = $onlyCommon ? $commonFields : $commonFields + $fields;

        $html = $this->fieldsToHTML($fields, $instance);

        return $html;
    }

    /**
     * Takes an array of widget fields and returns corresponding fields markup
     *
     * @param $fields Array of widget option fields
     * @return string Resulting form HTML
     */
    public function fieldsToHTML($fields, $instance){
        $html = $append = '';

        foreach($fields as $field => $attrs){
            $id = $this->get_field_id($field);
            $name = $this->get_field_name($field);
            $value = isset($instance[$field]) ? $instance[$field] : '';

            $aux = '';

            $aux .= '<div class="option-head">';
            if(isset($attrs['label'])){
                $aux .= '<label data-type="'.$attrs['type'].'" for="'.$id.'">'.$attrs['label'].'</label>';
            }
            if(isset($attrs['description'])){
                $aux .= '<span class="option-description">'.$attrs['description'].'</span>';
            }
            $aux .= '</div>';

            switch($attrs['type']){
                case 'textarea':
                    $aux .= '<textarea class="wp_tinymce" id="'.$id.'" name="'.$name.'" id="'.$name.'">'.$value.'</textarea>';
                    $append = "<script>
                                    try{
                                        tinymce.EditorManager.execCommand('mceRemoveEditor', false, '".$id."');
                                        tinymce.EditorManager.execCommand('mceAddEditor', false, '".$id."');
                                    }catch(e){};
                                </script>";
                    break;
                case 'media':
                    $value = empty($value) ? [] : (array)$value; //cast to array if single image
                    $multiple = isset($attrs['multiple']) ? $attrs['multiple'] : false;
                    $aux .= '<div class="widget-media">';
                    foreach($value as $pos => $url){
                        $aux .= '<div class="widget-media-item" style="background-image: url('.$url.')"><input type="hidden" name="'.$name.($multiple ? '['.$pos.']' : '').'" value="'.$url.'" /><div class="delete" onclick="this.parentElement.remove();">Delete</div></div>';
                    }
                    $aux .= '</div><button data-multiple="'.($multiple ? 1 : 0).'" data-id="'.$id.'" data-name="'.$name.'" class="upload_image_button button button-primary">'.($multiple ? 'Add Images' : 'Add Image').'</button>';
                    break;
                case 'page':
                    $pagesDropdown = wp_dropdown_pages(['name' => $name, 'selected' => $value, 'echo' => 0]);
                    $aux .= $pagesDropdown;
                    break;
                case 'size':
                    $value = empty($value) ? "[0.00, 12.00]" : $value;
                    $aux .= '<input name="'.$name.'" type="hidden" id="'.$id.'" value=\''.$value.'\'>';
                    $aux .= '<div data-id="'.$id.'" data-value=\''.$value.'\' class="nouislider"></div>';
                    $append = '<script>if(typeof kd_admin !== "undefined"){kd_admin.initNoUISliders()}</script>';
                    break;
                case 'menu':
                    $menus = wp_get_nav_menus();

                    $aux .= sprintf('<select id="%s" name="%s">', $id, $name);
                    $aux .= sprintf('<option value="0">%s</option>', _( '&mdash; Select &mdash;' ));
                    foreach ( $menus as $menu ) :
                        $aux .= sprintf('<option value="%s" %s >', esc_attr( $menu->term_id ), selected( $value, $menu->term_id, false ));
                        $aux .= sprintf('%s', esc_html( $menu->name ));
                        $aux .= '</option>';
                    endforeach;
                    $aux .= '</select>';
                    break;
                case 'color':
                    $append = '<script>if(typeof kd_admin !== "undefined"){kd_admin.initColorPickers()}</script>';
                    $aux .= '<input class="colorPicker" type="text" name="'.$name.'" id="'.$id.'" value="'.$value.'" />';
                    break;
                case 'radio':
                    $choices = isset($attrs['choices']) ? $attrs['choices'] : [];
                    foreach($choices as $choice){
                        $aux .= '<label><input type="radio" '. ($value==$choice ? 'checked ':'') .'name="'.$name.'" id="'.$id.'" value="'.$choice.'" />' . $choice . ' </label> ';
                    }
                    $aux .= '</select>';
                    break;
                case 'checkbox':
                    $aux .= sprintf('<input type="checkbox" name="%s" id="%s" %s />', $name, $id, checked($value, 'on', false));
                    break;
                default:
                    $aux .= '<input type="'.$attrs['type'].'" name="'.$name.'" id="'.$id.'" value="'.$value.'" />';
            }

            $html .= '<div class="control">'.$aux.'</div>';
        }

        if(empty($fields)){
            $html .= '<p></p>';
        }

        return $html.$append;
    }

    /**
     * Display a widget on the frontend using it's template.
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        $class = '';

        $desktopSizes = json_decode($instance['sizeDesktop']);
        $desktopAlignment = $instance['deskopAlignment'];
        $dCols = floor($desktopSizes[1]);
        $desktopCols = $dCols ?  : 12;

        $mobileSizes = json_decode($instance['sizeMobile']);
        $mobileAlignment = $instance['mobileAlignment'];
        $mCols = floor($mobileSizes[1]);
        $mobileCols = $mCols ?  : 12;

        $alignments = (empty($instance['deskopAlignment']) ? '' : ' kd-align-lg-'.$desktopAlignment) . (empty($instance['mobileAlignment']) ? '' :  ' kd-align-xs-'. $mobileAlignment);

        $class .= ' col-xs-'.$mobileCols.' col-lg-'.$desktopCols . $alignments;

        echo '<div class="'.$class.'">';
        include $this->filename.'/'.$this->filename.'.template.php';
        echo '</div>';
    }

    /**
     * Display a widget admin form.
     * If the form template exists it will use that, if not it will display the built form.
     *
     * @param array $instance
     * @return string|void
     */
    public function form( $instance ) {
        $formTemplate = __DIR__.'/'.$this->filename.'/'.$this->filename.'.form.php';

        if(file_exists($formTemplate)){ // If the widget has a template, load that, if not, generate form from specified fields.
            echo $this->buildForm($instance, true);
            $fields = $this->getFieldsData($instance); // Get $fields, to be used in included template
            include $formTemplate;
        }else{
            $form = $this->buildForm($instance);
            echo $form;
        }
    }

    public function update( $new_instance, $old_instance) {
        return $new_instance;
    }

    public function getFieldsData($instance){
        $aux = [];
        foreach($this->fields as $field){
            $aux[$field]['id'] = $this->get_field_id($field);
            $aux[$field]['name'] = $this->get_field_name($field);
            $aux[$field]['value'] = isset($instance[$field]) ? $instance[$field] : false;
        }
        return $aux;
    }

}
