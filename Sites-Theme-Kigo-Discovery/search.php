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
        $wp_query = $this->get_query();
        $this->render(['wp_query' => $wp_query]);
    }

    private function get_query()
    {
        // Getting the search query
        $search_query = kd_get_search_query();

        // Array to store the post types to filter
        $post_types_to_filter = [];

        $search_query_types = $search_query[0]['types'] ? : $search_query[1]['types'];

        // The search query
        $s = urldecode(empty($search_query[0]['s']) ? '' : $search_query[0]['s']);

        // The post types that come in the search query
        if(!empty($search_query_types))
        {
            // The post types to filter
            $post_types_to_filter = explode(',',urldecode($search_query_types));
        }

        //Query 1 will get the s results
        $args1 =
        [
            'post_type'         => $post_types_to_filter ?: null,
            's' 			    => $s,
            'posts_per_page'    => -1,
        ];

        $q1 = get_posts($args1); // array



        $q2 = [];

        //Query 2 will get the meta fields results
        $meta_query = $this->get_meta_query_array($s);

        if((in_array('page',$post_types_to_filter) || empty($post_types_to_filter)) && !empty($s))
        {

            $args2 =
            [
                'post_type'         => get_post_types(),
                'posts_per_page'    => -1,
                'meta_query'        => $meta_query,
            ];

            $q2 = get_posts($args2);// array

        }

        $arraymerged = array_merge($q1, $q2);
        $ids = [];

        foreach($arraymerged as $merged)
        {
            $ids[] = $merged->ID;
        }

        $unique = array_unique($ids);

        $page_number = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $args =
                [
                    'post_type'                 => !empty($post_types_to_filter) ?$post_types_to_filter: get_post_types(),
                    'post__in'                  => empty($unique) ? [uniqid(time())] : $unique,
                    'ignore_sticky_posts'       => true,
                    'paged'                     => $page_number,
                ];



                $new_wp_query = new WP_Query($args);



                return $new_wp_query;

    }

    /**
     * returns an array of the meta fields to include in the search
     * @return array the meta fields array
     */
    function get_meta_query_array($s)
    {
        $mini_queries = $this->get_mini_queries($s);

        $meta_query = [
                            'relation' => 'AND',
                    ];
        $meta_query = array_merge($meta_query, $mini_queries);

        return $meta_query;
    }

    function get_mini_queries($s)
    {
        $string_parts = explode(' ',$s);
        $mini_queries = [];

        global $wpdb;
        //meta table
        $meta_table = $wpdb->prefix . 'postmeta';
        $custom_query_string = "SELECT meta_key FROM $meta_table ";
        $all_metas_results =  $wpdb->get_results( $custom_query_string, ARRAY_A );
        $all_metas = [];
        foreach($all_metas_results as $meta)
        {
            if(!in_array($meta['meta_key'],$all_metas))
                $all_metas[] = $meta['meta_key'];
        }



        foreach ($string_parts as $key => $value) {

            $all_metas_arrays = [];

            foreach($all_metas as $meta)
            {
                $all_metas_arrays[] =
                [
                        'key'       => $meta,
                        'value'     => "$value",
                        'compare'   => 'LIKE'
                ];
            }

            $mini_query =
            [
                'relation'  => 'OR',
            ];

            $mini_queries = array_merge($mini_query, $all_metas_arrays);
        }

        return $mini_queries;
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

$SearchPage = new SearchTemplateController('page-templates/blog-search-template.php');
