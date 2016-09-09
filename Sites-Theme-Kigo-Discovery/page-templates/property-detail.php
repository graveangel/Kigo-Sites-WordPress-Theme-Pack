<?php 
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;
/*
  Template Name: Property Detail Page
 */
$selected_usemap = json_decode(get_theme_mod('kd_usemap_properties')) ? : [];
$selected_usemap_array_str = '';
foreach ($selected_usemap as $usemap) {
    $selected_usemap_array_str.=$usemap . ',';
}
$selected_usemap_array_str = $selected_usemap_array_str . 0;

$props_settings = json_decode(stripslashes(get_theme_mod('kd_properties_settings')));
$sd = json_decode(get_option('bapi_keywords_array'));
$property = new stdClass();


foreach ($sd as $ent) {
    if ($ent->entity === "property") {
        if (strstr(get_permalink($post->ID), $ent->DetailURL)) {
            $property = $props_settings->{$ent->pkid};
            break;
        }
    }
}

//debug($property, true);
?>
<?php get_header(); 

$fblikeshare = get_theme_mod('fb-like-share');
if($fblikeshare === 1 || $fblikeshare === FALSE ){
    echo '<div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, \'script\', \'facebook-jssdk\'));</script>';
}

?>
<!-- PROPERTY CONTENT -->
<div class="template-property" data-markercolor="<?php echo get_theme_mod('primary-color') ?  : '#33baaf' ?>">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php the_content(); ?>
        <?php
        endwhile;
    else:
        ?>
        <p><?php _e('Sorry, this page does not exist.'); ?></p>
<?php endif; ?>

</div>
<!-- END PROPERTY CONTENT -->
<script type="text/javascript">
    var selected_usemap = new Array(<?php echo $selected_usemap_array_str; ?>);
    var selected_usemap_layout = <?php echo get_theme_mod('kd_usemap_properties_layout', 0) ?>;
    var marker_color = "<?php echo get_theme_mod('primary-color') ?>";

<?php if (!empty($property->forced_featured) && $property->forced_featured === true): ?>
        var forced_featured = true;
<?php else: ?>
        var forced_featured = false;
<?php endif; ?>

<?php if (!empty($property->force_usemap) && $property->force_usemap === true): ?>
        var force_usemap = true;
<?php else: ?>
        var force_usemap = false;
<?php endif; ?>

<?php if (isset($property->usemap_layout) && !empty($property->force_usemap) && $property->force_usemap): ?>
        usemap_layout = <?php echo $property->usemap_layout; ?>
<?php else: ?>
        var usemap_layout = -1;
<?php endif; ?>


</script>

<?php if (!empty($property->script_enabled) && $property->script_enabled === true): ?>
    <script type="text/javascript">
    <?php
    echo $property->script_value;
    ?>
    </script>
<?php endif; ?>
<?php get_footer(); ?>
