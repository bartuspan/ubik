<?php // ==== GENERAL ==== //

// Head cleaner: removes useless fluff
function ubik_init() {
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
add_action( 'init', 'ubik_init' );



// Cleans out the RSS feed
function ubik_generator() {
  return '';
}
add_filter( 'the_generator', 'ubik_generator' );



// Removes the ".recentcomments" style added to the header for no good reason
// http://www.narga.net/how-to-remove-or-disable-comment-reply-js-and-recentcomments-from-wordpress-header
function ubik_remove_recent_comments_style_widget() {
  global $wp_widget_factory;
  if ( isset( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ) ) {
    remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
  }
}
add_action( 'wp_head', 'ubik_remove_recent_comments_style_widget', 1 );



// Remove injected CSS for recent comments widget; from Bones: https://github.com/eddiemachado/bones
function ubik_remove_recent_comments_style() {
  if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
    remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
  }
}
add_filter( 'wp_head', 'ubik_remove_recent_comments_style', 1 );



// Enqueue scripts like a boss
function ubik_enqueue_scripts() {
  // Hack: no need to load Open Sans more than once
  wp_deregister_style( 'open-sans' );
  wp_register_style( 'open-sans', false );
}
add_action( 'wp_enqueue_scripts', 'ubik_enqueue_scripts' );



// Remove "protected" from password-protected posts: http://www.paulund.co.uk/remove-protected-post-titles
function ubik_strip_protected( $title ) {
  return '%s';
}
add_filter( 'protected_title_format', 'ubik_strip_protected' );



// Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link; via _s
function ubik_page_menu_args( $args ) {
  $args['show_home'] = true;
  return $args;
}
add_filter( 'wp_page_menu_args', 'ubik_page_menu_args' );



// Enable WordPress links manager on new installs; a copy of http://wordpress.org/plugins/link-manager/
if ( UBIK_LINKS_MANAGER )
  add_filter( 'pre_option_link_manager_enabled', '__return_true' );



// Allow HTML in author descriptions on single user blogs
// Careful: might be stripped out anyway (e.g. when making meta descriptions) so don't put anything essential in there
if ( !is_multi_author() ) {
  remove_filter( 'pre_user_description', 'wp_filter_kses' );
}
