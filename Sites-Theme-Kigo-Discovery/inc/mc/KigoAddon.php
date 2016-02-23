<?php
/**
 * @package KigoAddon
 * @version 0.0
 */
/*
  Plugin Name: KigoAddon
  Description: This plugin integrates mailchimp with wordpress OK.
  Author: Jairo E. Vengoechea R.
  Version: .0.0
 */


//Include Mailchimp API**********************************************
require_once( __DIR__ . '/inc/mailchimp-api-php/src/mailchimp.php' );

// create custom plugin settings menu
add_action('admin_menu', 'kigoaddon_setup_menu');

function kigoaddon_setup_menu() { {

        //create new top-level menu
        add_menu_page('Kigo Addon plugin options', 'Kigo +', 'administrator', __FILE__, 'kigoaddon_setup_menu_page', plugins_url('/img/kigo-addon-logo.png', __FILE__));

        //call register settings function
        add_action('admin_init', 'register_kigoaddon_setup_menu');
    }
}

function register_kigoaddon_setup_menu() {
    //register our settings
    //
	//Mailchimp API key
    register_setting('kigoaddon_setup-group', 'kigoaddon_mailchimp_key');
    //Mailchimp Mailing list name
    register_setting('kigoaddon_setup-group', 'kigoaddon_mailchimp_mailing_list');
    //Mailchimp subscribeForm Title
    register_setting('kigoaddon_setup-group', 'kigoaddon_mailchimp_form_title');
}

function kigoaddon_setup_menu_page() {
    ?>
    <div class="wrap">
        <h2><img src="<?php echo plugins_url('img/kigo-addon-logo.png', __FILE__); ?>" /> Kigo Addon Settings</h2>
        <?php require_once( __DIR__ . '/inc/mailchimp-form-part.php' ); ?>
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
require_once( __DIR__ . '/inc/mailchimpWidget/mailchimpWidget.php' );

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
?>