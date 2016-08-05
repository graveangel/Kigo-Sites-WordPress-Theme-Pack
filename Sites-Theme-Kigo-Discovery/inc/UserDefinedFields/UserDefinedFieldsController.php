<?php

namespace UserDefinedFields;

class UserDefinedFieldsController {

    private $template_vars = [], $template_path;

    function __construct() {
        
        if($_POST['udfs'])
        {
            $udfs = json_decode(stripslashes($_POST['udfs']));
            
            //$udfs = serialize($udfs);
            
  
            update_option('udfs',$udfs);
            $ud = get_option('udfs');
        }
        
        # Set the template path
        $this->template_path = 'UserDefinedFields.Template.php';

        # Index
        $this->index();
    }

    function prepare_vars() {
        $title = "User Defined Fields Admin page";
        $this->set('title', $title);

        $option_udfs = json_encode(get_option('udfs'));
        
        $this->set('udfs', $option_udfs);
    }


    function index() {
        # Prepare the vars
        $this->prepare_vars();

        # Render
        $this->render();
    }

    function ajax() {
        $obj = new \stdClass();
        # Set the ajax vars
        $return = json_encode($obj);
        # returns json always
        header('Content-type: application/json');
        echo $return;
        exit;
    }

    function render() {
        extract($this->template_vars);
        include $this->template_path;
    }

    function set($varname, $varval) {
        $this->template_vars[$varname] = $varval;
    }

}
