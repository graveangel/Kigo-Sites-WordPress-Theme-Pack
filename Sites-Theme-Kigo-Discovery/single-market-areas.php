<?php
/**
 * Market Areas Controller
 */
if(!defined(DS))
    define('DS', DIRECTORY_SEPARATOR);

class MarketAreasController
{
    private $template_path;

    public function __construct()
    {

        $this->id = get_the_ID(); //The post id

        $this->set_template(); //Select the landing template

        $this->index_action();
    }

    public function index_action()
    {
        $this->render();
    }

    /**
     * defines the template for this controller.
     */
    public function set_template()
    {
        $templates_dir          = 'page-templates';
        $template_name          = 'market-areas-tmpl';


        //Get the saved param:
        $landing_value          = json_decode(stripcslashes(get_post_meta( $this->id,'market_area_generate_landing', true)), true);

        $use_landing = $landing_value['landing'];
        $template_selected = $landing_value['template'];

        if($use_landing)
        {
            $template_name = $template_selected;
        }

        $template_path          =  $templates_dir . DS . $template_name . '.php';

        $this->template_path    = $template_path;
    }

    /**
     * Renders the template.
     * @param  array $template_vars The variables to include in the template
     * @return null                no return is defined.
     */
    public function render($template_vars=[])
    {
        extract($template_vars);

        $template_fullpath = dirname(__FILE__) . DS . $this->template_path;

        require $template_fullpath;
    }
}
$MAInit = new MarketAreasController();
