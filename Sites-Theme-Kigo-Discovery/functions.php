<?php

namespace Discovery;

//Helper functions - Contains dependencies for further functionalities (Should be included first)
include_once 'inc/helpers.php';

//Theme core
include_once 'inc/theme/Core.class.php';

//Theme actions
include_once 'inc/theme/Compatibility.class.php';

//Theme activation related functionalities
include_once 'inc/theme/Activation.class.php';

//Theme BAPI helper
include_once 'inc/theme/BAPIHelper.class.php';

//Market areas landings
include_once 'inc/market-areas-posttype/MarketAreasLandings.class.php';



/* Inititalize core theme functionalities */
$site = new Core();
$site->init();

/* Initialize theme activation */
$activation = new Activation();
add_action('after_switch_theme', array($activation, 'init'));

/* Initialize theme compatibility */
$compatibility = new Compatibility();
add_action('init', array($compatibility, 'overwriteIfSynced'));
