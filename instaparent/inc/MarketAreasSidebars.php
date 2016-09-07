<?php
#Sidebar for market areas:
function market_area_sidebars()
{
    $sidebars = 
        [
            'market-area-search' => [
                                'name'          => 'Detail Market Areas Search Sidebar',
                                'id'            => 'market-area-search',
                                'description'   => 'Widgets in this area will be shown on all market area detail pages.',
                                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                                'after_widget'  => '</div>',
                                'before_title'  => '<h2 class="widgettitle">',
                                'after_title'   => '</h2>',
                            ],
            'market-area-inquire' => [
                                'name'          => 'Detail Market Areas Inquire Sidebar',
                                'id'            => 'market-area-inquire',
                                'description'   => 'Widgets in this area will be shown on all market area detail pages.',
                                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                                'after_widget'  => '</div>',
                                'before_title'  => '<h2 class="widgettitle">',
                                'after_title'   => '</h2>',
                            ]
        ];
    
    foreach($sidebars as $sidebar)
    {
        //register_sidebar($sidebar);
    }
}
add_action('widgets_init', 'market_area_sidebars');

