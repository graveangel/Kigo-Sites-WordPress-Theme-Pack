<div>
    <script type="text/javascript">
        var fields =    [
            <?php
            $post_sidebars = json_decode(get_post_meta( $object->ID, 'post-sidebar', true ));

            if(is_object($post_sidebars))
            foreach($post_sidebars->name as $ind => $post_sidebar_name){
              ?>
            {
                type:  "text",
                style: "width: 97%;",
                value: "<?php echo $post_sidebar_name; ?>",
                width: "<?php echo $post_sidebars->width[$ind]; ?>",
                widthmob: "<?php echo $post_sidebars->widthmob[$ind]; ?>"
            },
            <?php
          }
          ?>
        ]
    </script>
    <ul class="sortable" id="sortable">
        <li class="not">
            <a class="btn btn-primary sidebar-add" href="#">+ Sidebar</a>
            <input type="hidden" name="post_meta_box_nonce" value="<?php echo wp_create_nonce( $baseNamefile ); ?>" />
        </li>
    </ul>

</div>
