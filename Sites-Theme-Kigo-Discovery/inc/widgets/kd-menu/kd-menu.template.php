<div class="kd-widget kd-menu <?php echo $i['mainMenu'] == 'on' ? ' main-menu' : ''; ?>">
    <div class="toggle-wrapper"><div class="toggle"><i class="kd-icon-menu"></i></div></div>
    <?php
    $nav_menu = ! empty( $i['menu'] ) ? wp_get_nav_menu_object( $i['menu'] ) : false;
    if ( !$nav_menu )
        return;

    $menuClass = $i['align'] ? 'menu align_'.$i['align'] : 'menu';
    $menuClass .= $i['filled'] == 'on' ? ' filled' : '';

    $nav_menu_args = array(
        'fallback_cb' => '',
        'menu'        => $nav_menu,
        'menu_class'  => $menuClass,
    );

    if($i['filled'] && $i['bgColor']){
        $nav_menu_args += array(
            'before' => '<div class="primary-fill-color" style="background-color: '.$i['bgColor'].' !important;">',
            'after' => '</div>',
        );
    }

    wp_nav_menu( apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args ) + ['container' => false] );
    ?>
</div>