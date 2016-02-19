<?php //Template Name: Virtual Sidebars ?>
<?php get_header(); ?>

<?php $post_sidebar = '';
$pid = get_the_ID();
$posts = $GLOBALS['posts'];
if(count($posts) > 1){
  $pid = get_option( 'page_for_posts' );
}
$post_sidebar = json_decode(get_post_meta($pid,'post-sidebar' ,true ));

$sbcount =    0;
?>

<!--content widget area -->
<?php if ( !empty( $post_sidebar) ) : ?>
  <div class="col-xs-12">
    <div class="page-width virtual-sidebars-box">
      <?php foreach($post_sidebar->name as $ind => $sidebar):?>

        <?php if (is_active_sidebar('sidebar_'.hiphenize($sidebar))): ?>

          <style type="text/css">
            #content-<?php echo   'sidebar_'.hiphenize($sidebar); ?>-sidebar-box {
              width:<?php echo ($post_sidebar->widthmob[$ind] ? $post_sidebar->widthmob[$ind]: 0); ?>%; display:inline-block;
            }
            @media screen and (min-width: 768px){
              #content-<?php echo   'sidebar_'.hiphenize($sidebar); ?>-sidebar-box {
                width:<?php echo ($post_sidebar->width[$ind] ? $post_sidebar->width[$ind]: 0); ?>%; display:inline-block;
              }
            }
          </style>
          <div id="content-<?php echo   'sidebar_'.hiphenize($sidebar); ?>-sidebar-box" class="virtual-sidebar">
            <?php dynamic_sidebar('sidebar_'.hiphenize($sidebar)); ?>
          </div>


        <?php else:
          $sbcount++;
        endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>

<?php if(empty($post_sidebar) || count($post_sidebar->name) === $sbcount): ?>

  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <div class="col-xs-12">
      <div class="col-xs-12 col-md-8 col-md-offset-2">
        <h2><?php the_title(); ?></h2>
        <?php the_content(); ?>
      </div>
    </div>
  <?php endwhile; else : ?>
    <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
  <?php endif; ?>

<?php endif; ?>
<!--end content widget area -->

<?php get_footer(); ?>
