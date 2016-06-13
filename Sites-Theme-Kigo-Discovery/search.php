<?php
/**
 * Search template controller.
 */
define('DS', DIRECTORY_SEPARATOR );

class SearchTemplateController
{
    private $template_path;

    public function __construct($template_path)
    {
        $this->set_template($template_path);
        $this->index_action();
    }

    public function index_action()
    {
        //$this->get_query();
        $this->render();
    }

    /**
     * defines the template for this controller.
     * @param string $template_path the template path
     */
    public function set_template($template_path)
    {
        $this->template_path = $template_path;
    }

    /**
     * Renders the template.
     * @param  array $template_vars The variables to include in the template
     * @return null                no return is defined.
     */
    public function render(array $template_vars=null)
    {
        extract($template_vars);
        require dirname(__FILE__) . DS . $this->template_path;
    }
}

$SearchPage = new SearchTemplateController('page-templates' . DS . 'blog-search-template.php');
