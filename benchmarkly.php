<?php
/*
Plugin Name: Benchmarkly 
Description: A plugin to help you track your site's performance over time
Version: 1.0.0
Author: mike van winkle
Author URI: http://mikevanwinkle.com/
Plugin URI: http://mikevanwinkle.com/wordpress/plugins/benchmarkly
Text Domain: benchmarkly
Domain Path: /lang
*/
include_once __DIR__.'/loader.php';
global $benchmarly;
$bmloader = new Benchmarkly\Loader();
$benchmarkly = $bmloader->loadController("benchmarkly");
register_activation_hook( __FILE__ , array( $benchmarkly, "checkActive" ) );
register_deactivation_hook( __FILE__, array( $benchmarkly, "checkInactive" ) );
unset($bmloader);
// The rest is history
