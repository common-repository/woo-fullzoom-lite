<?php
/*
Plugin Name: WOO FULLZOOM LITE
Version: 1.0.4
Description: Full Screen Zoom Plugin For Woocommerce
Plugin URI: www.barfaraz.com/woofullzoom
Author: Barfaraz
Author URI: http://www.barfaraz.com
*/

if ( ! function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit();
}

define( 'WOOFULLZOOM_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'WOOFULLZOOM_URL_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' );

require_once( WOOFULLZOOM_DIR_PATH . 'classes/woofullzoom.php' );

$woo_fullzoom = new WOOFULLZOOM();

register_activation_hook( __FILE__, array( 'WOOFULLZOOM', 'plugin_activated' ) );

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( 'WOOFULLZOOM', 'plugin_settings_link' ) );

?>