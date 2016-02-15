<?php
$title = apply_filters('widget_title',$instance['title']);
$woid = esc_textarea($instance['text']);
$unit = $instance['unit'];
if(empty($woid)) return;
if(empty($unit)){
  $unit = 'f';
};
?>
    <div id="weather-widget"></div>
<script>
  $(document).ready(function () {
    // weather widget uses code found here: http://www.zazar.net/developers/jquery/zweatherfeed/
    // lookup woid here: http://woeid.rosselliot.co.nz/
    var woid = '<?= $woid ?>';
    var sTemperatureUnit = '<?= $unit ?>';
    if (woid!='') {
      if (sTemperatureUnit == null || sTemperatureUnit == '' && BAPI.defaultOptions.language=="en-US") { sTemperatureUnit = 'f'; }
      BAPI.UI.createWeatherWidget('#weather-widget', ['<?= $woid ?>'], { "link": false, "woeid": true, "unit": sTemperatureUnit });
    }
  });
    </script>
