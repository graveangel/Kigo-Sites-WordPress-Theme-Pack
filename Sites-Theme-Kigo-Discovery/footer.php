<!-- FOOTER-PUSH: for sticky footer -->
<div class="footer-push"></div>
</div>

<!-- END GLOBAL WRAPPER -->
<div class="footer-background">
<div class="page-width">
    <footer class="footer">
        <div class="row row-nopadding">
            <div class="col-sm-5 col-xs-12 nopadding"><?php  if (is_active_sidebar('footer_left')) { dynamic_sidebar('footer_left'); }  ?></div>
            <div class="col-sm-7 col-xs-12 nopadding align-r"><?php  if (is_active_sidebar('footer_right')) { dynamic_sidebar('footer_right'); }  ?></div>
        </div>

        <div class="row row-nopadding">
            <?php if(get_theme_mod("terms-privacy", true)):?>
            <div class="kd-widget kd-copy row">
                <div class="col-sm-6 col-xs-12 xs-center sm-left">
                    <span>Copyright &copy;2015 Discovery Rentals. All Rights Reserved.</span>
                </div>
                <div class="col-sm-6 col-xs-12 xs-center sm-right">
                    <span>Vacation Rental Software by Kigo.</span>
                </div>
            </div>
        <?php endif; ?>
        </div>

    </footer>
</div>
</div>
<?php if(get_theme_mod('site-weather')):?>
<script type="text/javascript">
$(document).ready(function() {
  $.simpleWeather({
    woeid: '<?php echo get_theme_mod('site-weather-woid'); ?>',
    unit: '<?php echo get_theme_mod('site-weather-scale'); ?>',
    success: function(weather) {
      html = '<i class="wi '+getClassnameForCode(weather.code)+'"></i> '+weather.temp+'&deg;'+weather.units.temp;
      $("#weather-place").html(html);
    },
    error: function(error) {
      $("#weather-place").html('<p>'+error+'</p>');
    }
  });
});
</script>
<?php endif; ?>
<?php wp_footer() ?>
</body>
</html>