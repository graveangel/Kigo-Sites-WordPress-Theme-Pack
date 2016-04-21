<?php

namespace Discovery;

class Activation {

    private $activeWidgets;
    private $BAPIHelper;

    public function __construct() {
        /* Get the array of current widgets in each sidebar */
        $this->activeWidgets = get_option('sidebars_widgets');
        $this->BAPIHelper = new BAPIHelper();
    }

    public function init() {
        $this->initWidgets();
        $this->setThemeMods();
        $this->initDefaultPosts();
        $this->initBapiSettings();
    }

    public function initDefaultPosts(){
        set_kd_items_posts();
    }

    public function initBapiSettings(){
        //Overwrite previous settings. If we shouldn't overwrite, use get_option('bapi_sitesettings') to check beforehand
        update_option('bapi_sitesettings', '{"searchmode-listview":"BAPI.config().searchmodes.listview=true;","searchmode-photoview":"BAPI.config().searchmodes.photoview=false;","searchmode-widephotoview":"BAPI.config().searchmodes.widephotoview=false;","searchmode-pagination":"BAPI.config().searchmodes.pagination=false;","searchmode-mapview":"BAPI.config().searchmodes.mapview=true;","mapviewType":"BAPI.config().mapviewType=\'ROADMAP\';","averagestarsreviews":"BAPI.config().hidestarsreviews=false;","defaultsearchresultview":"BAPI.config().defaultsearchresultview=\'tmpl-propertysearch-mapview\';","showunavailunits":"BAPI.config().restrictavail=false;","searchsort":"BAPI.config().sort=\'random\';","searchsortorder":"BAPI.config().sortdesc=false;","checkinoutmode":"BAPI.config().checkin.enabled=true; BAPI.config().checkout.enabled=true; BAPI.config().los.enabled=false;","deflos":"BAPI.config().los.defaultval=4; BAPI.config().los.minval=4;","categorysearch":"BAPI.config().category.enabled=false;","minsleepsearch":"BAPI.config().minsleeps={}; BAPI.config().minsleeps.enabled=false;","bedsearch":"BAPI.config().beds.enabled=true;","minbedsearch":"BAPI.config().minbeds={}; BAPI.config().minbeds.enabled=false;","maxbedsearch":"BAPI.config().beds.values=BAPI.config().beds.values.splice(0,1);BAPI.config().beds.minvalues=BAPI.config().beds.minvalues.splice(0,1);","amenitysearch":"BAPI.config().amenity.enabled=false;","devsearch":"BAPI.config().dev.enabled=false;","adultsearch":"BAPI.config().adults.enabled=true;","childsearch":"BAPI.config().children.enabled=false;","headlinesearch":"BAPI.config().headline.enabled=false;","maxratesearch":"BAPI.config().rate.enabled=false;","roomsearch":"BAPI.config().rooms.enabled=false;","locsearch":"BAPI.config().city.enabled=true; BAPI.config().location.enabled=false; BAPI.config().city.autocomplete=false;","propdetailrateavailtab":"BAPI.config().hideratesandavailabilitytab=false;","propdetail-availcal":"BAPI.config().displayavailcalendar=true;  BAPI.config().availcalendarmonths=6;","propdetailratestable":"BAPI.config().hideratestable=false;","propdetail-reviewtab":"BAPI.config().hasreviews=true;","poitypefilter":"BAPI.config().haspoitypefilter={}; BAPI.config().haspoitypefilter.enabled=true;"}');
    }

    /**
     * Creates the default widgets for each sidebar if it's empty.
     * Updates each widget with it's default content.
     */
    private function initWidgets() {

        /* Header default widgets */
        if (!count($this->activeWidgets['header_left'])) {
            /* KD Logo */
            $this->setWidget('header_left', 'kd_logo', ['mode' => 'bapi', 'mainMenu' => 'on']);
        }

        if (!count($this->activeWidgets['header_right'])) {

        }

        if (!count($this->activeWidgets['under_header_left'])) {
            /* Create default header menu */
            $headerMenuId = $this->createBapiMenu('Header Menu', [
                'bapi_home',
                'bapi_rentals' => ['bapi_search', 'bapi_search_buckets'],
                'bapi_specials',
                'bapi_attractions',
                'bapi_company' => [
                    'bapi_about_us',
                    'bapi_contact'
                ]
            ]);

            if($headerMenuId) {
                $this->setWidget('under_header_left', 'kd_menu', ['menu' => $headerMenuId, 'mainMenu' => 'on']);
            }

        }

        if (!count($this->activeWidgets['under_header_right'])) {

            /* Create logins menu */
            $loginMenuId = $this->createCustomMenu('Logins', ['Owner Login' => 'https://newapp.kigo.net/']);

            $kd_login_menu_content = [
                'menu' => $loginMenuId,
                'filled' => 'on',
                'align' => 'right',
                'bgColor' => '#33baaf',
            ];

            $this->setWidget('under_header_right', 'kd_menu', $kd_login_menu_content);

        }

        /* About us page default widgets */
        if (!count($this->activeWidgets['page_about_us'])) {

            /* KD items */
            $this->setWidget('page_about_us', 'kd_items', ['title' => 'OUR ITEMS']);

            /* KD Team */
            set_kd_team_posts();
            $this->setWidget('page_about_us', 'kd_team', ['title' => 'MEET OUR CREW', 'displayImages' => 'on', 'columns' => 4]);
        }

        /* Home page default widgets */
        if (!count($this->activeWidgets['page_home'])) {
            include_once 'BAPIHelper.class.php';
            $themeBAPI = new BAPIHelper();

            /* KD Hero */

            $themePath = get_template_directory_uri();
            $kd_hero_content = array(
                'color' => '#ffffff',
                'primary_text' => 'FIND A PLACE TO STAY',
                'secondary_text' => 'A NEW WAY TO DISCOVER AND ENJOY ADVENTURES',
                'button_value' => 'SEARCH RENTALS',
                'button_link' => '#',
                'slides' => [
                    $themePath.'/kd-common/img/hero/barcelona.jpg',
                    $themePath.'/kd-common/img/hero/florence.jpg',
                    $themePath.'/kd-common/img/hero/purple-sea.jpg'
                ],
            );
            $this->setWidget('page_home', 'kd_hero', $kd_hero_content);

            /* KD Search */
            $this->setWidget('page_home', 'kd_search');

            /* KD Featured */
            $this->setWidget('page_home', 'kd_featured', ['userandom' => true]);

            /* KD Buckets */
            $this->setWidget('page_home', 'kd_buckets', ['title' => __('Property finders', 'kd'), 'columns' => 3]);

            /* KD Specials */
            $this->setWidget('page_home', 'kd_specials', ['title' => __('Special offers', 'kd'), 'columns' => 3]);

            /* KD Page Block */

            //Get 'About Us' page id to assign to the 'Page Block' widget
            $resultArr = get_posts(['post_type' => 'page', 'meta_key' => 'bapi_page_id', 'meta_value' => 'bapi_about_us']);
            $aboutUsPage = array_pop($resultArr);

            $kd_pblock_content = [
                'page' => $aboutUsPage->ID,
                'image' => 'http://i.imgsafe.org/e32b879.png', //TODO: Replace with local image
                'align' => 'left',
            ];
            $this->setWidget('page_home', 'kd_page_block', $kd_pblock_content);

            /* KD Items */
            $this->setWidget('page_home', 'kd_items', ['title' => __('Activities', 'kd'), 'type' => 'activity']);

            /* KD Items */
            $this->setWidget('page_home', 'kd_items', ['title' => __('Locations', 'kd'), 'type' => 'location']);

            /* KD Team */
            $this->setWidget('page_home', 'kd_team', ['title' => __('Meet our team', 'kd'), 'displayImages' => 'on', 'columns' => 4]);

            /* KD Map */

            $apiName = $themeBAPI->getName();
            $apiAddress = $themeBAPI->getAddress();
            $apiPhone = $themeBAPI->getTelephone();

            $mapContent = '<h2>'.$apiName.'</h2>'
                .$apiAddress."<br>"
                .$apiPhone.'<br><br><a style="font-weight: bold; font-size: 32px; text-decoration: underline;" href="'.bapiPageUrl('bapi_contact').'">CONTACT US</a>';

            $this->setWidget('page_home', 'kd_map', ['map' => $apiAddress, 'displayContent' => 'on', 'use' => 'custom', 'customContent' => $mapContent]);
        }

        /* Footer default widgets */
        if (!count($this->activeWidgets['footer_left'])) {
            /* Create footer legal menu */
            $legalMenuId = $this->createBapiMenu('Legal Menu', ['bapi_tos', 'bapi_privacy_policy']);
            $kd_lmenu_content = [
                'menu' => $legalMenuId,
            ];

            if($legalMenuId) {
                $this->setWidget('footer_left', 'kd_menu', $kd_lmenu_content);
            }
        }

        if (!count($this->activeWidgets['footer_right'])) {
            /* Create footer menu */
            $footerMenuId = $this->createBapiMenu('Footer Menu', ['bapi_home', 'bapi_rentals', 'bapi_specials', 'bapi_about_us', 'bapi_contact']);

            if($footerMenuId) {
                $this->setWidget('footer_right', 'kd_menu', ['menu' => $footerMenuId, 'align' => 'right']);
            }
        }

        /* Sidebar widgets */
        if (!count($this->activeWidgets['sidebar_detail'])){
            $this->setWidget('sidebar_detail', 'old_bapi_inquiry_form', ['title' => 'Contact us']);
        }
        if(!count($this->activeWidgets['sidebar_page'])){
            $this->setWidget('sidebar_page', 'old_bapi_inquiry_form', ['title' => 'Contact us']);
        }

        /* Once we've initialized all widgets, we store the updated active widgets array */
        update_option('sidebars_widgets', $this->activeWidgets);

    }

    /**
     * Adds a widget and it's optional default content to a sidebar.
     *
     * @param $sidebar_id String Sidebar id
     * @param $widget_id String Widget id
     * @param array $data Array Widget default content
     */
    private function setWidget($sidebar_id, $widget_id, $data = []) {
        $currentWidgetID = 2; //Live widgets begin with an ID of 2

        /* We count how many widgets of the same type are currently assigned to calculate new widget ID */
        foreach ($this->activeWidgets as $sidebar => $widgets) {
            if (!is_array($widgets))
                continue;
            foreach ($widgets as $key => $id) {
                $currentWidgetID += strpos($id, $widget_id) !== false ? 1 : 0;
            }
        }

        /* Assign new widget to sidebar */
        $this->activeWidgets[$sidebar_id][] = $widget_id . '-' . $currentWidgetID;

        /* Get & update widget content */
        $aux_content = get_option('widget_' . $widget_id) ? : [];
        $aux_content[$currentWidgetID] = $data;
        update_option('widget_' . $widget_id, $aux_content);
    }

    /**
     * @param $name
     * @param $pageBapiIds
     * @param bool $parentId
     * @return int|void|WP_Error
     */
    public function createBapiMenu($name, $pageBapiIds, $parentId = false) {

        // Check if the menu exists
        $menu_exists = wp_get_nav_menu_object($name);

        $menu_id = $menu_exists ? $menu_exists->term_id : wp_create_nav_menu($name); //Grab resulting menu id

        if($menu_exists && !$parentId){
            return $menu_id;
        }

        /* We go through all the bapi page ID's and find the actual page post for each */
        foreach ($pageBapiIds as $parentBapiId => $pageBapiId) {

            $multilevel = is_array($pageBapiId);
            $bapiId = $multilevel ? $parentBapiId : $pageBapiId;

            $resultArr = get_posts(['post_type' => 'page', 'meta_key' => 'bapi_page_id', 'meta_value' => $bapiId]);

            if(empty($resultArr)){ //If a page doesn't exists, we delete menu & exit
                wp_delete_nav_menu($menu_id);
                return false;
            }

            $page = array_pop($resultArr);

            /* We add each page object into the new menu */
            $itemId = wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => $page->post_title,
                    'menu-item-url' => get_the_permalink($page->ID),
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $page->ID,
                    'menu-item-status' => 'publish',
                    'menu-item-parent-id' => $parentId
                )
            );

            if($multilevel){
                $this->createBapiMenu($name, $pageBapiId, $itemId);
            }
        }

        return $menu_id; //We return the menu id
    }

    private function createCustomMenu($name, $items){
        // Check if the menu exists
        $menu_exists = wp_get_nav_menu_object($name);

        // If it doesn't exist, let's create it.
        if (!$menu_exists) {
            $menu_id = wp_create_nav_menu($name); //Grab resulting menu id

            /* We go through all the bapi page ID's and find the actual page post for each */
            foreach ($items as $name => $link) {

                /* We add each page object into the new menu */
                wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title' => $name,
                        'menu-item-url' => $link,
                        'menu-item-type' => 'custom',
                        'menu-item-status' => 'publish',
                    )
                );
            }
        }
        return $menu_id ? : $menu_exists->term_id; //We return the menu id
    }

    /**
     * Set default customizer setting options (theme mods).
     */
    private function setThemeMods() {

        //Fetch BAPI data to set as default values
        $site_phone = $this->BAPIHelper->getTelephone();
        $site_logo = $this->BAPIHelper->getSiteLogo();

        /* Define default values for customizer settings */
        $mods = array(

            //Styles
            'primary-color' => '#33baaf',
            'secondary-color' => '#f6af33',
            'tertiary-color' => '#555',
            'tertiary-color-hover' => '#33baaf',

            //Social settings
            'url-facebook'      => '#',
            'url-twitter'       => '#',
            'url-google'        => '#',
            'url-blog'        => '#',

            //Global settings
            'site-phone'        => $site_phone,
            'site-phone-header' => 1,
            'fb-like-share' => 1,
            'site-weather' => 0,
            'site-weather-scale' => 'f',
            'terms-privacy'     => 1,
            'site-favicon'      => $site_logo,

            //About us
            'about-hero' => 'http://i.imgsafe.org/e4d977f.png',
            'about-side' => 'http://i.imgsafe.org/e32b879.png',
            'about-title' => 'WHAT MAKES US UNIQUE',
            'about-subtitle' => 'Discover what sets us apart from the rest.',
            'about-title-subtitle-color' => '#ffffff',

            //Contact us
            'contact-subtitle' => 'Drop us a line!',
            'contact-left' => 'Left side content',
            'contact-under' => 'Find us on the map',

        );

        foreach ($mods as $name => $val) {
            if(!get_theme_mod($name)){
                set_theme_mod($name, $val);
            }
        }
    }

}


function set_kd_items_posts() {
    if(!get_terms('type')) {

        //We add default item types

        $defaultTypes = [
            'location' => array(
                'name' => 'Location',
                'description' => 'Best spots in the city',
                'slug' => 'location',
            ),
            'activity' => array(
                'name' => 'Activity',
                'description' => 'Fun activities for all ages',
                'slug' => 'activity',
            ),
        ];

        //Insert item type taxonomies to item cpt

        foreach ($defaultTypes as $type => $args) {
            $id = wp_insert_term(
                $args['name'], // the term
                'type', // the taxonomy
                $args
            );
        }
    }

    //Defining default item posts by type
    $defaultContent = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis inventore ipsa mollitia porro provident rerum voluptatum!<!--more-->Culpa cum dolores eius hic molestiae numquam quidem reiciendis repellat sit sunt. Tenetur, veniam.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis inventore ipsa mollitia porro provident rerum voluptatum! Culpa cum dolores eius hic molestiae numquam quidem reiciendis repellat sit sunt. Tenetur, veniam.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis inventore ipsa mollitia porro provident rerum voluptatum! Culpa cum dolores eius hic molestiae numquam quidem reiciendis repellat sit sunt. Tenetur, veniam.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis inventore ipsa mollitia porro provident rerum voluptatum! Culpa cum dolores eius hic molestiae numquam quidem reiciendis repellat sit sunt. Tenetur, veniam.';

    $defaultItems = [
        'location' => [
            ['name' => 'Beach', 'slug' => 'beach', 'icon' => 'fa-sun-o'],
            ['name' => 'Mountains', 'slug' => 'mountains', 'icon' => 'fa-tree'],
            ['name' => 'Marina', 'slug' => 'marina', 'icon' => 'fa-anchor'],
        ],
        'activity' => [
            ['name' => 'Hiking', 'slug' => 'hiking', 'icon' => 'fa-compass'],
            ['name' => 'Wine tasting', 'slug' => 'wine-tasting', 'icon' => 'fa-glass'],
            ['name' => 'Go-Karting', 'slug' => 'go-karting', 'icon' => 'fa-flag-checkered'],
        ]
    ];

    $posts = get_posts(array('post_type' => 'item'));

    if (!count($posts)) {
        foreach($defaultItems as $type => $items) {
            foreach($items as $item){
                $item_post = array(
                    'post_type' => 'item',
                    'post_title' => $item['name'],
                    'post_name' => $item['slug'],
                    'post_status' => 'publish',
                    'post_content' => $defaultContent,
                );
                $pid = wp_insert_post($item_post);
                add_post_meta($pid, 'item_icon', $item['icon']);
                wp_set_object_terms($pid, $type, 'type');
            }
        }
    }


}

function set_kd_team_posts() {
    $type = "team";
    $args = array('post_type' => $type);
    $posts = get_posts($args);
    $defaultContent = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis inventore ipsa mollitia porro provident rerum voluptatum!<!--more-->Culpa cum dolores eius hic molestiae numquam quidem reiciendis repellat sit sunt. Tenetur, veniam.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis inventore ipsa mollitia porro provident rerum voluptatum! Culpa cum dolores eius hic molestiae numquam quidem reiciendis repellat sit sunt. Tenetur, veniam.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis inventore ipsa mollitia porro provident rerum voluptatum! Culpa cum dolores eius hic molestiae numquam quidem reiciendis repellat sit sunt. Tenetur, veniam.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis inventore ipsa mollitia porro provident rerum voluptatum! Culpa cum dolores eius hic molestiae numquam quidem reiciendis repellat sit sunt. Tenetur, veniam.';

    if (!count($posts)) {
        for ($i = 0; $i < 4; $i++) {
            $team_post = array(
                'post_type' => $type,
                'post_title' => "TEAM MEMBER " . $i,
                'post_name' => 'team-member-' . $i,
                'post_status' => 'publish',
                'post_content' => $defaultContent,
            );
            $pid = wp_insert_post($team_post);
            add_post_meta($pid, '_position', 'position ' . $i);
            add_post_meta($pid, '_email', 'email ' . $i . '@dummytext.com');
        }
    }
}
