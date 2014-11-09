<?php // ==== GENERAL ==== //

// == HEAD CLEANER == //

// Head cleaner: removes useless fluff
function ubik_head_cleaner() {
  if ( !is_admin() ) {
    // Windows Live Writer support, version info
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_generator' );

    // Pointless relational links
    remove_action( 'wp_head', 'index_rel_link' );
    remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
  }
}

// Removes the ".recentcomments" style added to the header for no good reason
// http://www.narga.net/how-to-remove-or-disable-comment-reply-js-and-recentcomments-from-wordpress-header
function ubik_remove_recent_comments_style_widget() {
  global $wp_widget_factory;
  if ( isset( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ) ) {
    remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
  }
}

// Remove injected CSS for recent comments widget; from Bones: https://github.com/eddiemachado/bones
function ubik_remove_recent_comments_style() {
  if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
    remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
  }
}

// Activate the last two functions
if ( UBIK_GENERAL_HEAD_CLEANER ) {
  add_action( 'init', 'ubik_head_cleaner' );
  add_action( 'wp_head', 'ubik_remove_recent_comments_style_widget', 1 );
  add_filter( 'wp_head', 'ubik_remove_recent_comments_style', 1 );
}



// Remove jQuery migrate from default scripts; adapted from: http://www.paulund.co.uk/remove-jquery-migrate-file-wordpress
function ubik_remove_jquery_migrate( &$scripts ) {
  if( !is_admin() ) {
    $scripts->remove('jquery');
    $scripts->add( 'jquery', false, array( 'jquery-core' ) );
  }
}
if ( UBIK_GENERAL_REMOVE_MIGRATE )
  add_filter( 'wp_default_scripts', 'ubik_remove_jquery_migrate' );



// Remove built-in Open Sans stylesheet
function ubik_remove_open_sans() {
  wp_deregister_style( 'open-sans' );
  wp_register_style( 'open-sans', false );
}
if ( UBIK_GENERAL_REMOVE_OPEN_SANS )
  add_action( 'wp_enqueue_scripts', 'ubik_remove_open_sans' );



// Remove the word "protected" from password-protected posts: http://www.paulund.co.uk/remove-protected-post-titles
function ubik_remove_protected( $title ) {
  return '%s';
}
if ( UBIK_GENERAL_REMOVE_PROTECTED )
  add_filter( 'protected_title_format', 'ubik_remove_protected' );



// Enable WordPress links manager on new installs; a copy of http://wordpress.org/plugins/link-manager/
if ( UBIK_GENERAL_LINKS_MANAGER )
  add_filter( 'pre_option_link_manager_enabled', '__return_true' );
