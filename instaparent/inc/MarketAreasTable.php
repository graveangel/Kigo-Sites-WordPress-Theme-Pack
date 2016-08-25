<?php

namespace MarketAreasTable;

/**
 * The class needs to be based on the info we want to show
 */
class MarketAreasTable extends \WP_Posts_List_Table {

    /**
     * Initializing the table
     */
    function __construct() {

        $this->_args = [
            'singular' => __('Market Area', TEXTDOMAIN),
            'plural' => __('Market Areas', TEXTDOMAIN),
            'ajax' => false,
            'screen' => 'page'
        ];

        \WP_List_Table::__construct($this->_args); # May seem redudant but it is not.
        # The items will appear nested only if no sorting statement is defined.
        $nested = empty($_REQUEST['orderby']);
        $this->set_hierarchical_display($nested);
    }

    /**
     * Get the info from the database
     * @return mixed the result can be false or array or an object
     */
    static function get_market_areas($per_page = 5, $page_number = 1) {
        global $wpdb;

        # Sort hierarchical

        $sql = "SELECT p.* FROM {$wpdb->prefix}posts p ";


        # Condition
        $sql .= " INNER JOIN {$wpdb->prefix}postmeta pm on p.ID = pm.post_id WHERE pm.meta_key = '_wp_page_template' AND pm.meta_value LIKE '%page-templates/market-area.php%' AND p.post_status <> 'trash'";

        if (!empty($_REQUEST['m'])) {
            $year = substr(esc_sql($_REQUEST['m']), 0, 4);
            $month = substr(esc_sql($_REQUEST['m']), 4, 2);
            $sql .= ' AND  p.post_date_gmt LIKE "%' . $year . '-' . $month . '%" ';
        }

        if (!empty($_REQUEST['orderby'])) {


            $sql .= ' ORDER BY p.' . esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';

        }

        $sql .= " LIMIT $per_page";
        $sql .= " OFFSET " . ( $page_number - 1 ) * $per_page;

        #   var_dump($sql);die;
        $result = $wpdb->get_results($sql, 'ARRAY_A');



        return $result;
    }

    static function flatten_array($array, $children_key)
    {
        $flat_branch = [];
        foreach($array as $branch)
        {
            # Take out the children
            $children = [];
            if(array_key_exists($children_key, $branch))
            {
                $children = $branch[$children_key];
                 unset($branch[$children_key]);
            }

            # Add this to the flat branch
            $flat_branch[] = $branch;
            $flat_children = [];
            $flat_children = self::flatten_array($children,$children_key);

            # Put flat children inside
            $flat_branch = array_merge($flat_branch,$flat_children);
        }
        return $flat_branch;
    }

    /**
     *
     * @param int $parentid the id of the parent to build the hierchy from
     * @param array $arr the flat array of all parent children
     * @return array the nested array
     */
    static function build_hierarchy($parentid, $arr) {
        $children = [];
        foreach ($arr as $idx => $ar) {
            if ($ar->post_parent === $parentid) {
                $children[$idx] = $ar;
                $children[$idx]->children = self::build_hierarchy($ar->ID, $arr);
            }
        }

        return $children;
    }

    /**
     * Trash an item
     * @param  int $id The id of the entity to delte
     * @return void     no return
     */
    static function trash_market_area($id) {
        global $wpdb;
        wp_trash_post($id);
        // $wpdb->delete("{$wpdb->prefix}posts", [ 'ID' => $id], ['%d']);
    }

    /**
     * Trash an item
     * @param  int $id The id of the entity to delte
     * @return void     no return
     */
    static function whipe_market_area($id) {
        global $wpdb;
        $wpdb->delete("{$wpdb->prefix}posts", [ 'ID' => $id], ['%d']);
    }

    /**
     * Get the number of elements
     * @return integer The number of records
     */
    static function record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}posts p";
        $sql .= " INNER JOIN {$wpdb->prefix}postmeta pm on p.ID = pm.post_id WHERE pm.meta_key = '_wp_page_template' AND pm.meta_value LIKE '%page-templates/market-area.php%' AND p.post_status <> 'trash'";

        return $wpdb->get_var($sql);
    }

    /**
     * Text displayed when no market area data is available
     * @return void no return
     */
    public function no_items() {
        _e('No Market Areas avaliable.', TEXTDOMAIN);
    }

    /**
     * Render a column when no column specific method exists.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default($item, $column_name) {
        $count = count(get_post_ancestors($item->ID));
        $permalink = "/wp-admin/post.php?post={$item->ID}&action=edit";
        $depth = '';
        if ($column_name === 'post_title' && empty($_REQUEST['orderby']) && $this->hierarchical_display) {
            $depth = str_repeat('&mdash;', $count);
        }
        switch ($column_name) {
            case 'post_title':
            case 'post_status':
            case 'ID':
            case 'post_parent':
                return "<a href='$permalink'>$depth{$item->{ $column_name }}</a>";
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb($item) {
        return sprintf(
                '<input type="checkbox" name="bulk-trash[]" value="%s" />', $item->ID
        );
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns() {
        $columns = [
            'cb' => '<input type="checkbox" />',
            // 'ID' => __('ID', TEXTDOMAIN),
            'post_title' => __('Title', TEXTDOMAIN),
            'post_status' => __('Status', TEXTDOMAIN),
                // 'post_parent'    => __( 'Parent ID', TEXTDOMAIN ),
        ];

        return $columns;
    }

    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'ID' => array('ID', true),
            'post_title' => array('post_title', true),
            'post_status' => array('post_status', true),
            'post_parent' => array('post_parent', true),
        );

        return $sortable_columns;
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions() {
        $actions = [
            'bulk-trash' => 'Send to Trash',
            'bulk-whipe' => 'Delete permanently',
        ];

        return $actions;
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items() {

        $this->_column_headers = $this->get_column_info();

        /** Process bulk action */
        $this->process_bulk_action();

        $per_page = $this->get_items_per_page('market_areas_per_page', 5);

        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        $this->set_pagination_args([
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page //WE have to determine how many items to show on a page
        ]);
        $this->items = self::get_market_areas($per_page, $current_page);
    }

    /**
     * This method takes care of the deleting market_areas record either
     * when the trash link is clicked or when a group of records is
     * checked and the trash option is selected from the bulk action.
     *
     * @return void no return
     */
    public function process_bulk_action() {

        //Detect when a bulk action is being triggered...
        if ('bulk-trash' === $this->current_action()) {

            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['_wpnonce']);

            if (!wp_verify_nonce($nonce, 'bulk-' . $this->_args['plural'])) {
                die('No script kiddies');
            } else {
                $trash_ids = esc_sql($_POST['bulk-trash']);
                // loop over the array of record IDs and trash them
                foreach ($trash_ids as $id) {
                    self::trash_market_area($id);
                }
            }
        } elseif ('bulk-whipe' === $this->current_action()) {
            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['_wpnonce']);

            if (!wp_verify_nonce($nonce, 'bulk-' . $this->_args['plural'])) {
                die('No script kiddies');
            } else {
                $whipe_ids = esc_sql($_POST['bulk-trash']);
                // loop over the array of record IDs and trash them
                foreach ($whipe_ids as $id) {
                    self::whipe_market_area($id);
                }
            }
        }
    }

    /**
     * Display the table
     *
     * @since 3.1.0
     * @access public
     */
    public function display() {

        $singular = $this->_args['singular'];

        $this->display_tablenav('top');

        $this->screen->render_screen_reader_content('heading_list');
        ?>
        <table class="wp-list-table <?php echo implode(' ', $this->get_table_classes()); ?>">
            <thead>
                <tr>
                    <?php $this->print_column_headers(); ?>
                </tr>
            </thead>

            <tbody id="the-list"<?php
            if ($singular) {
                echo " data-wp-lists='list:$singular'";
            }
            ?>>
                       <?php $this->display_rows_or_placeholder(); ?>
            </tbody>

            <tfoot>
                <tr>
                    <?php $this->print_column_headers(false); ?>
                </tr>
            </tfoot>

        </table>
        <?php
        $this->display_tablenav('bottom');
    }

    /**
     * Generate the table navigation above or below the table
     *
     * @since 3.1.0
     * @access protected
     * @param string $which
     */
    protected function display_tablenav($which) {
        if ('top' === $which) {
            wp_nonce_field('bulk-' . $this->_args['plural']);
        }
        ?>
        <div class="tablenav <?php echo esc_attr($which); ?>">

            <?php if (count($this->items)): ?>
                <div class="alignleft actions bulkactions">
                    <?php $this->bulk_actions($which); ?>
                </div>
                <?php
            endif;
            $this->extra_tablenav($which);
            $this->pagination($which);
            ?>

            <br class="clear" />
        </div>
        <?php
    }

    /**
     * Generate the tbody element for the list table.
     *
     * @since 3.1.0
     * @access public
     */
    public function display_rows_or_placeholder() {
        if (count($this->items)) {
            $this->display_rows($this->items);
        } else {
            echo '<tr class="no-items"><td class="colspanchange" colspan="' . $this->get_column_count() . '">';
            $this->no_items();
            echo '</td></tr>';
        }
    }

    /* @global WP_Query $wp_query
     * @global int $per_page
     * @param array $posts
     * @param int $level
     */

    public function display_rows($posts = array(), $level = 0) {
        global $wp_query;

        $per_page = $this->get_items_per_page('market_areas_per_page', 5);

        if (empty($posts))
            $posts = $wp_query->posts;

        add_filter('the_title', 'esc_html');

//        if ($this->hierarchical_display) {
//            $this->_display_rows_hierarchical($posts, $this->get_pagenum(), $per_page);
//        } else {
            foreach ($this->items as $item) {
                $this->single_row($item);
            }
//        }
    }

    /**
     * @global wpdb    $wpdb
     * @global WP_Post $post
     * @param array $pages
     * @param int $pagenum
     * @param int $per_page
     */
    private function _display_rows_hierarchical($pages, $pagenum = 1, $per_page = 20) {
        global $wpdb;
        $level = 0;

        if (!$pages) {
            $pages = get_pages(array('sort_column' => 'menu_order'));

            if (!$pages)
                return;
        }

        /*
         * Arrange pages into two parts: top level pages and children_pages
         * children_pages is two dimensional array, eg.
         * children_pages[10][] contains all sub-pages whose parent is 10.
         * It only takes O( N ) to arrange this and it takes O( 1 ) for subsequent lookup operations
         * If searching, ignore hierarchy and treat everything as top level
         */
        if (empty($_REQUEST['s'])) {

            $top_level_pages = array();
            $children_pages = array();

            foreach ($pages as $page) {

                $page = get_post($page['ID']);

                // Catch and repair bad pages.
                if ($page->post_parent == $page->ID) {
                    $page->post_parent = 0;
                    $wpdb->update($wpdb->posts, array('post_parent' => 0), array('ID' => $page->ID));
                    clean_post_cache($page);
                }

                if (0 === intval($page->post_parent)) {
                    $top_level_pages[] = $page;
                } else
                    $children_pages[$page->post_parent][] = $page;
            }

            $pages = &$top_level_pages;
        }


        $count = 0;
        //	$start = 0;//( $pagenum - 1 ) * $per_page;
        $end = /* $start + */ $per_page;
        $to_display = array();
        foreach ($pages as $page) {
            if ($count >= $end)
                break;

            $count++;

            if ($count >= 0) {

                $to_display[$page->ID] = $level;
            }



            if (isset($children_pages)) {
                $this->_page_rows($children_pages, $count, $page->ID, $level + 1, $pagenum, $per_page, $to_display);
            }
        }



        // If it is the last pagenum and there are orphaned pages, display them with paging as well.
        if (isset($children_pages) && $count < $end) {
            foreach ($children_pages as $orphans) {
                try {
                    foreach ($orphans as $op) {
                        if ($count >= $end)
                            break;

                        if ($count >= $start) {
                            $to_display[$op->ID] = 0;
                        }

                        $count++;
                    }
                } catch (\Exception $e) {

                    # ...
                }
            }
        }



        $ids = array_keys($to_display);
        _prime_post_caches($ids);

        if (!isset($GLOBALS['post'])) {
            $GLOBALS['post'] = reset($ids);
        }

        foreach ($to_display as $page_id => $level) {
            echo "\t";
            $post = json_decode(json_encode(get_post($page_id)), true);
            $this->single_row($post, $level);
        }
    }

    /**
     * @global WP_Post $post
     *
     * @param int|WP_Post $post
     * @param int         $level
     */
    public function single_row($post, $level = 0) {
        $global_post = get_post();

        $post = get_post($post['ID']);
        $this->current_level = $level;

        $GLOBALS['post'] = $post;
        setup_postdata($post);

        $classes = 'iedit author-' . ( get_current_user_id() == $post->post_author ? 'self' : 'other' );

        $lock_holder = wp_check_post_lock($post->ID);
        if ($lock_holder) {
            $classes .= ' wp-locked';
        }

        if ($post->post_parent) {
            $count = count(get_post_ancestors($post->ID));
            $classes .= ' level-' . $count;
        } else {
            $classes .= ' level-0';
        }
        ?>
        <tr id="post-<?php echo $post->ID; ?>" class="<?php echo implode(' ', get_post_class($classes, $post->ID)); ?>">
            <?php $this->single_row_columns($post); ?>
        </tr>
        <?php
        $GLOBALS['post'] = $global_post;
    }

    /*
     * @since 3.1.0
     * @access protected
     *
     * @param object $item The current item
     */

    function single_row_columns($item) {

        list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();


        foreach ($columns as $column_name => $column_display_name) {
            $classes = "$column_name column-$column_name";
            if ($primary === $column_name) {
                $classes .= ' has-row-actions column-primary';
            }

            if (in_array($column_name, $hidden)) {
                $classes .= ' hidden';
            }

            // Comments column uses HTML in the display name with screen reader text.
            // Instead of using esc_attr(), we strip tags to get closer to a user-friendly string.
            $data = 'data-colname="' . wp_strip_all_tags($column_display_name) . '"';

            $attributes = "class='$classes' $data";

            if ('cb' === $column_name) {
                echo '<th scope="row" class="check-column">';
                echo $this->column_cb($item);
                echo '</th>';
            } elseif (method_exists($this, '_column_' . $column_name)) {
                echo call_user_func(
                        array($this, '_column_' . $column_name), $item, $classes, $data, $primary
                );
            } elseif (method_exists($this, 'column_' . $column_name)) {
                echo "<td $attributes>";
                echo call_user_func(array($this, 'column_' . $column_name), $item);
                echo $this->handle_row_actions($item, $column_name, $primary);
                echo "</td>";
            } else {
                echo "<td $attributes>";
                echo $this->column_default($item, $column_name);
                echo $this->handle_row_actions($item, $column_name, $primary);
                echo "</td>";
            }
        }
    }

    /**
     * Generates and displays row action links.
     *
     * @since 4.3.0
     * @access protected
     *
     * @param object $post        Post being acted upon.
     * @param string $column_name Current column name.
     * @param string $primary     Primary column name.
     * @return string Row actions output for posts.
     */
    protected function handle_row_actions($post, $column_name, $primary) {
        if ($primary !== $column_name) {
            return '';
        }

        $post_type_object = get_post_type_object($post->post_type);
        $can_edit_post = current_user_can('edit_post', $post->ID);
        $actions = array();
        $title = _draft_or_post_title();

        if ($can_edit_post && 'trash' != $post->post_status) {
            $actions['edit'] = sprintf(
                    '<a href="%s" aria-label="%s">%s</a>', get_edit_post_link($post->ID),
                    /* translators: %s: post title */ esc_attr(sprintf(__('Edit &#8220;%s&#8221;'), $title)), __('Edit')
            );
             $actions['inline hide-if-no-js'] = sprintf(
             	'<a href="#" class="editinline" aria-label="%s">%s</a>',
             	/* translators: %s: post title */
             	esc_attr( sprintf( __( 'Quick edit &#8220;%s&#8221; inline' ), $title ) ),
             	__( 'Quick&nbsp;Edit' )
             );
        }

        if (current_user_can('delete_post', $post->ID)) {
            if ('trash' === $post->post_status) {
                $actions['untrash'] = sprintf(
                        '<a href="%s" aria-label="%s">%s</a>', wp_nonce_url(admin_url(sprintf($post_type_object->_edit_link . '&amp;action=untrash', $post->ID)), 'untrash-post_' . $post->ID),
                        /* translators: %s: post title */ esc_attr(sprintf(__('Restore &#8220;%s&#8221; from the Trash'), $title)), __('Restore')
                );
            } elseif (EMPTY_TRASH_DAYS) {
                $actions['trash'] = sprintf(
                        '<a href="%s" class="submitdelete" aria-label="%s">%s</a>', get_delete_post_link($post->ID),
                        /* translators: %s: post title */ esc_attr(sprintf(__('Move &#8220;%s&#8221; to the Trash'), $title)), _x('Trash', 'verb')
                );
            }
            if ('trash' === $post->post_status || !EMPTY_TRASH_DAYS) {
                $actions['delete'] = sprintf(
                        '<a href="%s" class="submitdelete" aria-label="%s">%s</a>', get_delete_post_link($post->ID, '', true),
                        /* translators: %s: post title */ esc_attr(sprintf(__('Delete &#8220;%s&#8221; permanently'), $title)), __('Delete Permanently')
                );
            }
        }

        if (is_post_type_viewable($post_type_object)) {
            if (in_array($post->post_status, array('pending', 'draft', 'future'))) {
                if ($can_edit_post) {
                    $preview_link = get_preview_post_link($post);
                    $actions['view'] = sprintf(
                            '<a href="%s" rel="permalink" aria-label="%s">%s</a>', esc_url($preview_link),
                            /* translators: %s: post title */ esc_attr(sprintf(__('Preview &#8220;%s&#8221;'), $title)), __('Preview')
                    );
                }
            } elseif ('trash' != $post->post_status) {
                $actions['view'] = sprintf(
                        '<a href="%s" rel="permalink" aria-label="%s">%s</a>', get_permalink($post->ID),
                        /* translators: %s: post title */ esc_attr(sprintf(__('View &#8220;%s&#8221;'), $title)), __('View')
                );
            }
        }

        if (is_post_type_hierarchical($post->post_type)) {
            /**
             * Filter the array of row action links on the Pages list table.
             *
             * The filter is evaluated only for hierarchical post types.
             *
             * @since 2.8.0
             *
             * @param array $actions An array of row action links. Defaults are
             *                         'Edit', 'Quick Edit', 'Restore, 'Trash',
             *                         'Delete Permanently', 'Preview', and 'View'.
             * @param WP_Post $post The post object.
             */
            $actions = apply_filters('page_row_actions', $actions, $post);
        } else {

            /**
             * Filter the array of row action links on the Posts list table.
             *
             * The filter is evaluated only for non-hierarchical post types.
             *
             * @since 2.8.0
             *
             * @param array $actions An array of row action links. Defaults are
             *                         'Edit', 'Quick Edit', 'Restore, 'Trash',
             *                         'Delete Permanently', 'Preview', and 'View'.
             * @param WP_Post $post The post object.
             */
            $actions = apply_filters('post_row_actions', $actions, $post);
        }

        return $this->row_actions($actions);
    }

}
