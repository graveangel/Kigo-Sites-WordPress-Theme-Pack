<?php
add_action("admin_menu", "kd_options");
function kd_options() {
    add_menu_page('Options', 'Options', 'manage_options','kigo-discovery', 'kd_options_panel', get_template_directory_uri() .'/kd-common/img/KD.png');
    add_submenu_page('kigo-discovery','KD Custom CSS','Custom Styles','manage_options','kd-custom-css', 'kd_settings_page_custom_css');
    add_submenu_page('kigo-discovery','KD Custom JS','Custom Scripts','manage_options','kd-custom-js','kd_settings_page_custom_js');
//    add_submenu_page('kigo-discovery','KD Header Widgets','Header Widgets','manage_options','kd-header-widgets','kd_header_widgets');
//    add_submenu_page('kigo-discovery','Mailchimp integration','Mailchimp','manage_options','kd-mailchimp-connect','kd_settings_page_mailchimp_connect');
}

function kd_header_widgets(){
    include 'headerWidgets.php';
}

function kd_options_panel(){

    if(isset($_POST['kd-option-properties'])){
         $selected = '';
         if(isset($_POST['kd-option-properties']['usemap'])){
             $selected = json_encode($_POST['kd-option-properties']['usemap']);
             set_theme_mod('kd_usemap_properties_layout',$_POST['kd-option-properties']['layout']);
         }
        set_theme_mod('kd_usemap_properties',$selected);
    }
    
    if(isset($_POST['property-detail-settings'])){
        set_theme_mod('kd_properties_settings',$_POST['property-detail-settings']);
    }
    
    include "kd-options-panel.php";
}

function kd_settings_page_custom_css() {
    if(!empty($_POST)){
        $css = $_POST['kd-custom-css'];
        $use = isset($_POST['use_css']);

        /* Minimize custom CSS & store both versions */
        $mincss = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        // Remove space after colons
        $mincss = str_replace(': ', ':', $mincss);
        // Remove whitespace
        $mincss = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $mincss);

        set_theme_mod('kd-use-css', $use ? 1 : 0 );
        set_theme_mod('kd-custom-css', $css );
        set_theme_mod('kd-custom-css-min', $mincss );
    }

    $use_css = get_theme_mod('kd-use-css');
    $custom_css = get_theme_mod('kd-custom-css');
    /* Get total css (min) size */
    $custom_css_min = get_theme_mod('kd-custom-css-min');
    $custom_css_size = mb_strlen($custom_css_min, '8bit');
    ?>
    <div class="kd-options custom_styles">
        <form method="post" action="">
            <h1>Custom CSS</h1><h5>Current size (minified): <span class="size"><?php echo $custom_css_size ?> bytes</span></h5>
            <p>
                <label for="enable_css">Enable using custom css:  <input type="checkbox" <?php checked(1, $use_css) ?> name="use_css" id="enable_css" /></label>
            </p>
            <p class="disclaimer">
                Custom CSS changes have the <strong>potential to cause harm to the style of your website</strong>. Any changes made are at your own risk.
                If you need to revert your changes for any reason, simply turn off custom CSS.<br>
                The following styles are minified and included into the header of the site.
            </p>
            <div id="custom_css" class="aceEditor" data-mode="css" data-input="custom_css_input"><?php echo $custom_css; ?></div>
            <input type="hidden" id="custom_css_input" name="kd-custom-css" value="<?php echo $custom_css; ?>"/>
            <button type="submit" class="btn btn-primary" name="button">SAVE CSS</button>
        </form>
    </div>
<?php
}

function kd_settings_page_custom_js() {
    if(!empty($_POST)){

        $hjs = stripslashes($_POST['kd-custom-h-js']); //Header scripts
        $fjs = stripslashes($_POST['kd-custom-f-js']); //Footer scripts

        $use_header = isset($_POST['use_h_js']);
        $use_footer = isset($_POST['use_f_js']);

        /* Minimize custom JS & store both versions */
        $hminjs = str_replace(': ', ':', $hjs);
        // Remove whitespace
        $hminjs = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $hminjs);

        /* Minimize custom JS & store both versions */
        $fminjs = str_replace(': ', ':', $fjs);
        // Remove whitespace
        $fminjs = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $fminjs);

        set_theme_mod('kd-use-h-js', $use_header ? 1 : 0 );
        set_theme_mod('kd-use-f-js', $use_footer ? 1 : 0 );

        set_theme_mod('kd-custom-h-js', $hjs );
        set_theme_mod('kd-custom-h-js-min', $hminjs );

        set_theme_mod('kd-custom-f-js', $fjs );
        set_theme_mod('kd-custom-f-js-min', $fminjs );
    }

    $use_h_js = get_theme_mod('kd-use-h-js');
    $use_f_js = get_theme_mod('kd-use-f-js');

    $custom_h_js = get_theme_mod('kd-custom-h-js');
    $custom_h_js_min = get_theme_mod('kd-custom-h-js-min');
    $custom_h_js_size = mb_strlen($custom_h_js_min, '8bit');

    $custom_f_js = get_theme_mod('kd-custom-f-js');
    $custom_f_js_min = get_theme_mod('kd-custom-f-js-min');
    $custom_f_js_size = mb_strlen($custom_f_js_min, '8bit');
    ?>
    <div class="kd-options custom_scripts">
        <form method="post" action="">
            <input type="hidden" id="custom_js_h_input" name="kd-custom-h-js" value="<?php echo $hjs ?>" />
            <input type="hidden" id="custom_js_f_input" name="kd-custom-f-js" value="<?php echo $fjs ?>" />

            <h1>Custom JS</h1>
            <p class="disclaimer">
                Using custom scripts has the <strong>potential to cause harm to the functionality of your website</strong>. Any changes made are at your own risk.
                If you need to revert your changes for any reason, simply turn off custom JS.<br>
            </p>

            <h2>Header scripts</h2>
            <h5>Current size (minified): <span class="size"><?php echo $custom_h_js_size ?> bytes</span></h5>
            <p>
                <label for="enable_h_js">Enable using custom header script:  <input type="checkbox" <?php checked(1, $use_h_js) ?> name="use_h_js" id="enable_h_js" /></label>
            </p>
            <p>The following script is minified and included into the <strong>header</strong> of the site.</p>
            <div class="aceEditor" data-mode="javascript" data-input="custom_js_h_input"><?php echo $custom_h_js; ?></div>

            <h2>Footer scripts</h2>
            <h5>Current size (minified): <span class="size"><?php echo $custom_f_js_size ?> bytes</span></h5>
            <p>
                <label for="enable_f_js">Enable using custom footer script:  <input type="checkbox" <?php checked(1, $use_f_js) ?> name="use_f_js" id="enable_f_js" /></label>
            </p>
            <p>The following script is minified and included before the closing <strong>body</strong> tag.</p>
            <div class="aceEditor" data-mode="javascript" data-input="custom_js_f_input"><?php echo $custom_f_js; ?></div>

            <button type="submit" class="btn btn-primary" name="button">SAVE JS</button>
        </form>
    </div>
<?php
}

/*
//Include Mailchimp API**********************************************
require_once( __DIR__ . '/mc/inc/mailchimp-api-php/src/mailchimp.php' );


//call register settings function
add_action('admin_init', 'register_kdmailchimp_setup_menu');
function register_kdmailchimp_setup_menu() {
    //register our settings
    //
	//Mailchimp API key
    register_setting('kigoaddon_setup-group', 'kigoaddon_mailchimp_key');
    //Mailchimp Mailing list name
    register_setting('kigoaddon_setup-group', 'kigoaddon_mailchimp_mailing_list');
    //Mailchimp subscribeForm Title
    register_setting('kigoaddon_setup-group', 'kigoaddon_mailchimp_form_title');
}
function kd_settings_page_mailchimp_connect(){
    ?>
       <div class="wrap">
           <h2>KD MAILCHIMP CONNECT</h2>
           <?php require_once( __DIR__ . '/mc/inc/mailchimp-form-part.php' ); ?>

       </div>
       <?php
}

function validate_kigoaddon_mailchimp_form($req) {
    //echo "<pre>";var_dump($req);die;
    if (!empty($req->query_vars)) {
        if (preg_match("/mailchimp\/subscribe/", $req->query_vars['pagename'])) {

            if (empty($_POST) || !wp_verify_nonce($_POST['kigoaddon_mailchimp_subscribe_hid'], 'kigoaddon_mailchimp_subscribe')) {
                print "Don't be a smartass!";
                exit;
            } else {
                $post = $_POST;

                //get email from form:
                $email = $post['kigoaddon_mailchimp']['email'];

                //Add email to mailing list **********
                //Mailchimp object
                $mailchimp_key = esc_attr(get_option('kigoaddon_mailchimp_key'));
                $mailchimp = new Mailchimp($mailchimp_key);

                $list_id = esc_attr(get_option('kigoaddon_mailchimp_mailing_list'));

                try {


                    //subscribe
                    $mailchimp->lists->subscribe($list_id, array('email' => $email));
                } catch (Exception $err) {
                    // Error...
                    //var_dump($err->getMessage()); die;
                }

                wp_redirect($post['_wp_http_referer']);
                exit;
            }
        }
    }
}

add_action('parse_request', 'validate_kigoaddon_mailchimp_form');
add_shortcode('mailchimp_form', 'get_kigoaddon_mailchimp_form');
add_filter('widget_text', 'do_shortcode');

function get_kigoaddon_mailchimp_form($title) {
    ?>
    <form action="/mailchimp/subscribe" method="post" id="kigoaddon_mailchimp_subscribe_form" class="wide form category-block text-center">
        <?php if (empty($title)): ?>
            <h3><?php echo esc_attr(get_option('kigoaddon_mailchimp_form_title')); ?></h3>
        <?php endif; ?>

        <input type="email" name="kigoaddon_mailchimp[email]" class="property-search-input" required>

        <?php wp_nonce_field('kigoaddon_mailchimp_subscribe', 'kigoaddon_mailchimp_subscribe_hid'); ?>
        <input type="submit" value="subscribe" class="button mailchimp-submit c-pointer">
    </form>
    <?php
}

//Include Mailchimp WIDGET ******************************************
require_once( __DIR__ . '/mc/inc/mailchimpWidget/mailchimpWidget.php' );

//Include Pages Tag support ******************************************
// add tag support to pages
function tags_support_all() {
    register_taxonomy_for_object_type('post_tag', 'page');
}

// ensure all tags are included in queries
function tags_support_query($wp_query) {
    if ($wp_query->get('tag'))
        $wp_query->set('post_type', 'any');
}

// tag hooks
add_action('init', 'tags_support_all');
add_action('pre_get_posts', 'tags_support_query');
*/
?>
