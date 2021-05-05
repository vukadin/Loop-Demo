<?php /*
Plugin Name: Loop Demo
Description: Demo plugin developed for Loop
Author: Njegos Vukadin
Version: 1.0.0
*/

namespace Loop;

define( 'LD_VERSION', '1.0.0' );
define( 'LD_FILE', __FILE__ );
define( 'LD_DIR', dirname( __FILE__ ) );

include 'inc/class.Helpers.php';
include 'inc/class.Plugin.php';

new Plugin();