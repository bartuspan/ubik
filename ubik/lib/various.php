<?php // ==== VARIOUS ==== //

// Allow HTML in author descriptions on single user blogs
// Careful: might be stripped out anyway (e.g. when making meta descriptions) so don't put anything essential in there
if ( !is_multi_author() ) {
  remove_filter( 'pre_user_description', 'wp_filter_kses' );
}



// Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link; via _s
function ubik_page_menu_args( $args ) {
  $args['show_home'] = true;
  return $args;
}
add_filter( 'wp_page_menu_args', 'ubik_page_menu_args' );



// Remove "protected" from password-protected posts: http://www.paulund.co.uk/remove-protected-post-titles
function ubik_strip_protected( $title ) {
  return '%s';
}
add_filter( 'protected_title_format', 'ubik_strip_protected' );
