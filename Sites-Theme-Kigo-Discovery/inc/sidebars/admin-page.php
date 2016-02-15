<div class="wrap">
    <h2>Custom Sidebars</h2>

    <div class="sidebar-generator">
        <label for="generator_input">Sidebar name:</label>
        <input type="text" id="generator_input" />
        <button class="button button-secondary" id="generator_button">Add</button>
    </div>
    <h3>Current sidebars</h3>
    <form id="sidebars" method="post">

        <div class="sidebars-container">

            <?php
            wp_nonce_field('kd_dynamic_sidebars', 'kd_sidebars_nonce');
            $kdSidebars = get_option('kd_dynamic_sidebars');
            if(is_array($kdSidebars)){
                foreach($kdSidebars as $sidebar){
                    $pagesUsing = get_posts(['post_type' => 'page', 'meta_key' => '_custom_sidebar', 'meta_value' => $sidebar, 'posts_per_page' => -1]);

                    echo '<div>';
                    echo '<input type="text" name="custom_sidebars[]" value="'.$sidebar.'" /><button type="button" class="button button-secondary">Delete</button>';

                    if(!empty($pagesUsing)){
                        echo '<span>Currently assigned to: ';

                        foreach($pagesUsing as $pageUsing){
                            echo '<a class="asignee" href="'.get_edit_post_link($pageUsing->ID).'">'.$pageUsing->post_title.'</a>';
                        }
                    }

                    echo '</div>';
                }
            }
            ?>

        </div>

        <p class="submit">
            <input name="submit" class="button button-primary" value="Save" type="submit">
        </p>
    </form>

</div>