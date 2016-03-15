<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
/*
File: footer.php
This is the footer code.
*/

$num_columns = 0;
$num_columns = is_active_sidebar( 'insta-footer' ) ? $num_columns + 1 : $num_columns;
$num_columns = is_active_sidebar( 'insta-footer-2' ) ? $num_columns + 1 : $num_columns;
$num_columns = is_active_sidebar( 'insta-footer-info' ) ? $num_columns + 1 : $num_columns;
$num_columns = is_active_sidebar( 'insta-footer-nav' ) ? $num_columns + 1 : $num_columns;

$no_widgets = ($num_columns == 0) ? TRUE : FALSE;

$column_width = 12;
if(!$no_widgets){ $column_width = 12/$num_columns;}
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
                
                                    
                    <?php if ( is_active_sidebar( 'insta-footer-info' ) ) : ?>
                        <div class="span<?php echo $column_width;?>">
                        <?php dynamic_sidebar( 'insta-footer-info' ); ?>
                        </div>      
                    <?php endif; ?>
                
                    <?php if ( is_active_sidebar( 'insta-footer-nav' ) ) : ?>
                        <div class="span<?php echo $column_width;?>">
                        <?php dynamic_sidebar( 'insta-footer-nav' ); ?>
                        </div>      
                    <?php endif; ?>

                    <?php if ( is_active_sidebar( 'insta-footer' ) ) : ?>
                    <div class="span<?php echo $column_width;?>">
                        <?php dynamic_sidebar( 'insta-footer' ); ?>
                    </div>
                    <?php endif; ?>
                
                    <?php if ( is_active_sidebar( 'insta-footer-2' ) ) : ?>
                    <div class="span<?php echo $column_width;?>">
                        <?php dynamic_sidebar( 'insta-footer-2' ); ?>
                    </div>
                    <?php endif; ?>
                    
                   <?php
					if ( !is_active_widget( false, false, 'bapi_footer', true ) ) {
						echo '<div class="span12"><div class="widget widget_bapi_footer brandinglink"><div class="footer"><div class="footer-links"><span class="poweredby"><a rel="nofollow" target="_blank" href="http://www.InstaManager.com">Vacation Rental Software by InstaManager</a></span></div><div class="clear"></div></div></div></div>';
					}
				?>
                
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
</article>
<!-- End Main Content Wrapper -->
<?php wp_footer(); ?>
<script type="text/javascript" src="<?php echo wp_make_link_relative(get_template_directory_uri()); ?>/insta-common/bootstrap/js/insta-common.js"></script>
</body>
</html>