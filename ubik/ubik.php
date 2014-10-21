<?php
/**
 * Plugin Name: Ubik
 * Plugin URI: http://github.com/synapticism/ubik
 * GitHub Plugin URI: https://github.com/synapticism/ubik
 * Description: A library of useful theme-agnostic WordPress snippets, hacks, and functions.
 * Author: Alexander Synaptic
 * Author URI: http://alexandersynaptic.com
 * Version: 0.5.0
 */
define( 'UBIK_VERSION', '0.5.0' );

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
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/attachments.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/comments.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/content.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/excerpt.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/feed.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/general.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/image.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/image-shortcodes.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/search.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/terms.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/terms-popular.php' );

if ( is_admin() )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/admin.php' );

// Load optional Ubik modules; set these in your `ubik-config.php`
if ( UBIK_CHINESE )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/chinese.php' );

if ( UBIK_EXCLUDER )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/excluder.php' );

if ( UBIK_FORMAT )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/formats.php' );

if ( UBIK_GOOGLE_ANALYTICS )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/google-analytics.php' );

if ( UBIK_MARKDOWN )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/markdown.php' );

if ( UBIK_META )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/meta.php' );

if ( UBIK_NETLABEL )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/taxonomy-netlabel.php' );

if ( UBIK_PLACES )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/taxonomy-places.php' );

if ( UBIK_SERIES )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'lib/taxonomy-series.php' );



function ubik_activate() {
  flush_rewrite_rules(); // Refresh permalinks on plugin activation; @TODO: make sure this works
}
register_activation_hook( __FILE__, 'ubik_activate' );
