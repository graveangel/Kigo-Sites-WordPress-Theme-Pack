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
<!-- Start Insta Footer Widget Area -->
<footer id="insta-footer">
		<div class="container-fluid">
            <div class="row-fluid maintainer site-info">
                <div class="span12">
                    <?php if ( is_active_sidebar( 'insta-footer' ) ) : ?>
                    <?php dynamic_sidebar( 'insta-footer' ); ?>
                    <?php endif; ?>
                    <?php
					if ( !is_active_widget( false, false, 'bapi_footer', true ) ) {
						echo '<div class="widget widget_bapi_footer brandinglink"><div class="footer"><div class="footer-links"><span class="poweredby"><a rel="nofollow" target="_blank" href="http://www.InstaManager.com">Vacation Rental Software by InstaManager</a></span></div><div class="clear"></div></div></div>';
					}
				?>
                </div>
            </div>
        </div>
</footer>
<!-- End Insta Footer Widget Area -->

<!-- Start Insta Bottom Fixed Widget Area -->
<?php if ( is_active_sidebar( 'insta-bottom-fixed' ) ) : ?>
<div id="insta-bottom-fixed" class="container-fluid navbar-fixed-bottom">
		<div class="row-fluid maintainer">
        	<div class="span12">
				<?php dynamic_sidebar( 'insta-bottom-fixed' ); ?>
			</div>
		</div>
</div>
<?php endif; ?>
<!-- End Insta Bottom Fixed Widget Area -->
</article>
<!-- End Main Content Wrapper -->
<?php wp_footer(); ?>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/insta-common/bootstrap/js/insta-common.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.cycle.all.min.js" type="text/javascript"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.maximage.min.js" type="text/javascript"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/javascript.js" type="text/javascript"></script>
</body>
</html>