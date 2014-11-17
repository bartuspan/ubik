<?php
/**
 * Plugin Name: Ubik
 * Plugin URI: http://github.com/synapticism/ubik
 * GitHub Plugin URI: https://github.com/synapticism/ubik
 * Description: A library of useful theme-agnostic WordPress snippets, hacks, and functions
 * Author: Alexander Synaptic
 * Author URI: http://alexandersynaptic.com
 * Version: 0.7.0
 */
define( 'UBIK_VERSION', '0.7.0' );

// Do not call this plugin directly
if ( !defined( 'WPINC' ) ) {
  die;
}

// Ubik configuration file loading: first we try to grab user-defined settings
if ( is_readable( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-config.php' ) )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-config.php' );

// Ubik configuration file loading: now load the defaults
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-config-defaults.php' );

// Load ubik core library
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-content.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-excerpt.php' );



function ubik_activate() {
  flush_rewrite_rules(); // Refresh permalinks on plugin activation; @TODO: make sure this works
}
register_activation_hook( __FILE__, 'ubik_activate' );
