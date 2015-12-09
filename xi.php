<?php
/*
    Plugin Name: Xi Events
    Plugin URI:
    Description: An events and calendar plugin.
    Version: 0.0.2
    Author: Sam Oldenburg
    Author URI: http://samoldenburg.me
*/

define('XI__PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('XI__PLUGIN_DIR', plugin_dir_path( __FILE__ ));

foreach (glob(XI__PLUGIN_DIR . '/class/*.php') as $class)
    include $class;

$xi_error = new XiError();
XiEvents::init();

register_activation_hook(__FILE__, array('XiEvents', 'plugin_activation_hook'));
