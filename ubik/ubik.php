<?php
/**
 * Plugin Name: Ubik
 * Plugin URI: http://github.com/synapticism/ubik
 * Description: A handy set of custom functions for WordPress
 * Author: Alexander Synaptic
 * Author URI: http://alexandersynaptic.com
 * Version: 0.1.0
 */

// Do not call this plugin directly
if ( !defined( 'WPINC' ) ) {
  die;
}

// Pendrell ultra configuration files
if ( is_readable( plugin_dir_path( __FILE__ ) . '/ubik-config.php' ) ) {
  require_once( plugin_dir_path( __FILE__ ) . '/ubik-config.php' );
} else {
  require_once( plugin_dir_path( __FILE__ ) . '/ubik-config-sample.php' );
}

// Load ubik core library
include( plugin_dir_path( __FILE__ ) . '/lib/content.php' );
include( plugin_dir_path( __FILE__ ) . '/lib/general.php' );
include( plugin_dir_path( __FILE__ ) . '/lib/media.php' );
include( plugin_dir_path( __FILE__ ) . '/lib/various.php' );

if ( is_admin() ) {
  include( plugin_dir_path( __FILE__ ) . '/lib/admin.php' );
}

// Load optional ubik modules
if ( UBIK_PLACES )
  include( plugin_dir_path( __FILE__ ) . '/lib/places.php' );

if ( UBIK_PORTFOLIO )
  include( plugin_dir_path( __FILE__ ) . '/lib/portfolio.php' );

if ( UBIK_POST_FORMAT_REWRITE )
  include( plugin_dir_path( __FILE__ ) . '/lib/post-format-rewrite.php' );

if ( UBIK_SERIES )
  include( plugin_dir_path( __FILE__ ) . '/lib/series.php' );
