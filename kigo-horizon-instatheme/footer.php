<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
/*
File: footer.php
This is the footer code.
*/
?>
</div>
<!-- End main section -->
</div>
<!-- End page section -->
</section>
<!-- End pushdown section -->
</article>
<!-- End Main Content Wrapper -->
<!-- Start Insta Footer Widget Area -->
<footer id="insta-footer">
  <div class="footermenu">
      <div class="container-fluid maintainer">
        <?php 
          //an array with properties that overwrites the default          
          $defaults = array(
		      'menu_id'         => 'footer',
          'theme_location'  => 'footer',					
          'walker'          => new instaparent_walker_nav_menu //here we are calling our new method we created in functions.php
          );                
          //the menu function of wp with our array of properties we created above
          wp_nav_menu( $defaults );            
          ?>
      </div>
  </div>    
  <div class="container-fluid">
    <div class="row-fluid maintainer site-info">
      <div class="span12">
        <?php if ( is_active_sidebar( 'insta-footer' ) ) : ?>
        <?php dynamic_sidebar( 'insta-footer' ); ?>
        <?php endif; ?>
        <?php
					if ( !is_active_widget( false, false, 'bapi_footer', true ) ) {
						echo '<div class="widget widget_bapi_footer brandinglink"><div class="footer"><div class="footer-links"><span class="poweredby"><a href="http://www.kigo.net" target="_blank" rel="nofollow">Powered by Kigo</a></span></div><div class="clear"></div></div></div>';
					}
				?>
      </div>
    </div>
  </div>
</footer>

<!-- End Insta Footer Widget Area --> 

<!-- Start Insta Bottom Fixed Widget Area -->
<?php if ( is_active_sidebar( 'insta-bottom-fixed' ) ) : ?>
<div id="insta-bottom-fixed" class="navbar-fixed-bottom">
  <div class="container-fluid">
    <div class="row-fluid maintainer">
      <div class="span12">
        <?php dynamic_sidebar( 'insta-bottom-fixed' ); ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
<!-- End Insta Bottom Fixed Widget Area -->

<?php wp_footer(); ?>
<script type="text/javascript" src="<?php echo wp_make_link_relative(get_template_directory_uri()); ?>/insta-common/bootstrap/js/insta-common.js"></script> 
<script type="text/javascript" src="/wp-content/themes/kigo-horizon-instatheme/js/script.js"></script>
</body></html>