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
 <!-- removed all this block here to the booking theme --> 
 

 <div class="container-fluid">
    <div class="row-fluid maintainer site-info">
    <div class="span12">
      <?php if ( is_active_sidebar( 'insta-footer' ) ) : ?>
        <?php dynamic_sidebar( 'insta-footer' ); ?>
        <?php endif; ?>
      <?php
					if ( !is_active_widget( false, false, 'bapi_footer', true ) ) {
						echo '<div class="widget widget_bapi_footer brandinglink"><div class="footer"><div class="footer-links"><span class="poweredby"><a rel="nofollow" target="_blank" href="http://www.kigo.net">Vacation Rental Software by Kigo</a></span></div><div class="clear"></div></div></div>';
					}
				?>
      </div>
    </div>
  </div>
  
<!-- End removed all this block here to the booking theme --> 
</footer>

<!-- End Insta Footer Widget Area --> 

<!-- Start Insta Bottom Fixed Widget Area -->

<!-- removed all this block here to the booking theme --> 
<!--

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

-->
<!-- End removed all this block here to the booking theme --> 


<!-- End Insta Bottom Fixed Widget Area -->
</article>
<!-- End Main Content Wrapper -->
<?php wp_footer(); ?>
<script type="text/javascript" src="<?php echo wp_make_link_relative(get_template_directory_uri()); ?>/insta-common/bootstrap/js/insta-common.js"></script>
<script type="text/javascript">
$(document).ready(function() {	
   $.each($('.bottom-full-home h3.widget-title'), function(index, value) {
    var title = $(this).text();
	$(this).html('<span>'+ title +'</span>');
});		
	$('.datepickercheckin').siblings('.pickadate__holder').attr('id', 'checkinCalendar');
	$('.datepickercheckout').siblings('.pickadate__holder').attr('id', 'checkoutCalendar');
	
	$('.datepickercheckin').next('.cal-icon-trigger').click(function(){	
	$('.home-qsearch .widget_bapi_hp_search .search-button-block .quicksearch-dosearch').focus();	
			$('#checkinCalendar').addClass('pickadate__holder--opened');
		});
	$('.datepickercheckout').next('.cal-icon-trigger').click(function(){
		$('.home-qsearch .widget_bapi_hp_search .search-button-block .quicksearch-dosearch').focus();
		$('#checkoutCalendar').addClass('pickadate__holder--opened');
		});
$(document)[0].oncontextmenu = function() { return false;}
        $(document).mousedown(function(e){
          if( e.button == 2 ) {
             return false;
           } else {
             return true;
            }
        });
});
$(document).ready(function(){
  if($('body.home').length > 0){
     $('.welcome-page button.code').click(function(){
         $('.welcome-page #embed').slideToggle();
     });

     $('#embed input[type=radio]').change(function() {       
        var  svalue = this.value;
        if(svalue == 'custom'){
          $('#embed #code').html('<iframe width="300" height="10000" src="http://XYZsite.imbookingsecure.com" frameborder="0"></iframe>');
          $('.welcome-page #embed .cform').toggle();
        }else{
           $('#embed #code').html('<iframe width="100%" height="40" src="http://XYZsite.imbookingsecure.com" frameborder="0"></iframe>');
           $('.welcome-page #embed .cform').css('display','none');
        }
    });
    $('#embed .cform input[type=text]').change(function() {
      var  cvalue = $(this).attr("name");
        if(cvalue == 'cheight'){
          var cwidth = $('#embed .cform input[type=text].cwidth').val();
          $('#embed #code').html('<iframe width="'+cwidth+'" height="'+this.value+'" src="http://XYZsite.imbookingsecure.com" frameborder="0"></iframe>');
        }else{
           var cheight = $('#embed .cform input[type=text].cheight').val(); 
           $('#embed #code').html('<iframe width="'+this.value+'" height="'+cheight+'" src="http://XYZsite.imbookingsecure.com" frameborder="0"></iframe>');
        }

    });
  }
});
</script>
</body></html>