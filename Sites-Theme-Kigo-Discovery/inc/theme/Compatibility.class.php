<?php

namespace Discovery;

class Compatibility{

    public function __construct(){

    }

    public function init(){
        $this->overwriteIfSynced();
    }

    public function overwriteIfSynced(){
        $pageTemplateOverwrites = array(
            array(
                'bapi_id'     => 'bapi_contact',
                'template' => 'page-templates/contact-page.php'
            ),
            array(
                'bapi_id'     => 'bapi_about_us',
                'template' => 'page-templates/page-aboutus.php'
            ),
        );

        if($this->hasBeenSynced())
        {
            $this->setPageTemplates($pageTemplateOverwrites);
            $this->overwriteBAPIPageContent('bapi_contact', '/default-content/contactus.tmpl', [
                'subtitle' => get_theme_mod('contact-subtitle'),
                'left' => get_theme_mod('contact-left'),
                'under' => get_theme_mod('contact-under'),
            ]);
        }
    }

    private function hasBeenSynced(){
        $hbs = false;
        $lastSyncSaved = get_theme_mod('kd_wp_settings_update_time');
        $lastSync      = $this->checkLastSync();

        if((int)$lastSyncSaved !== (int)$lastSync)
        {
            set_theme_mod('kd_wp_settings_update_time',$lastSync);
            $hbs = true;
        }

        return $hbs;
    }

    private function checkLastSync(){
        return get_option('bapi_keywords_lastmod');
    }

    private function overwriteBAPIPageContent($bapiPageId, $templatePath, $custom_data = []) {
        $data = getbapisolutiondata() + $custom_data;

        $m = new \Mustache_Engine();
        $args = array(
            'meta_key' => 'bapi_page_id',
            'meta_value' => $bapiPageId,
            'post_type' => 'page'
        );

        $query = new \WP_Query($args);
        $t     = file_get_contents(get_template_directory().$templatePath);

        $string = $m->render($t, $data);

        wp_update_post(array('ID' => $query->posts[0]->ID,'post_content'=> $string));
    }

    private function setPageTemplates($pages){
        foreach($pages as $page){

            $args = array(
                'meta_key'   => 'bapi_page_id',
                'meta_value' => $page['bapi_id'],
                'post_type'  => 'page'
            );

            $post = array_pop(get_posts($args));

            if($post)
                update_post_meta($post->ID, '_wp_page_template', $page['template']);
        }
    }
}