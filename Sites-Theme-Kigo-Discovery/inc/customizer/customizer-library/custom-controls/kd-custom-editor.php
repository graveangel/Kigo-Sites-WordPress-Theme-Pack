<?php
if ( ! class_exists( 'WP_Customize_Control' ) ) {
    return NULL;
}
class Kd_Wp_Edtor extends WP_Customize_Control {
    public $type = 'textarea';

    public function render_content() {
        ?><label>
        <?php if ( ! empty( $this->label ) ) : ?>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <?php endif;
        if ( ! empty( $this->description ) ) : ?>
            <span class="description customize-control-description"><?php echo $this->description; ?></span>
        <?php endif; ?>
        <!--              <div class="rel editor-container-html">-->
        <!--                <textarea id="--><?php //echo $this->id; ?><!--" class="textarea editor-ace-html-textarea" name="--><?php //echo $this->id; ?><!---editor-ace-html" rows="5"  style="width:100%;" --><?php //$this->link(); ?><!--><?php //echo $this->value(); ?><!--</textarea>-->
        <!--                <div id="--><?php //echo $this->id; ?><!---editor-ace-html" class="editor-ace-html"></div>-->
        <!--              </div>-->
        <!--              <a class="go-fullscreen"><i class="kd-icon-toggle-fscreen"></i> <strong>Go Fullscreen</strong></a>-->

        <?php
debug($this->link(), false);
        wp_editor($this->value(), $this->id, ['textarea_name' => $this->id, 'textarea_rows' => 7,
            'tinymce' => [
                'setup' => "function (ed) {

                  ed.on('change', function() {
                  tinymce.triggerSave();
                  });

                  ed.on('KeyUp', function (e) {
                  $(ed.targetElm).html(ed.save()).trigger('change');
                  tinyMCE.triggerSave();
                  return true;
                  });

            }"
            ]]);


        echo "<script>try{kd_admin.initTinyMCE()}catch(e){}</script>";

        ?>

        </label>
    <?php
    }
}
