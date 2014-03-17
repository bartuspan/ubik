<?php // === VARIOUS === //
// A collection of theme-agnostic snippets

// Redirect user to single search result: http://wpglee.com/2011/04/redirect-when-search-query-only-returns-one-match/
function pendrell_search_redirect() {
  if ( is_search() && !empty( $_GET['s'] ) ) {
    global $wp_query;
    if ( $wp_query->post_count == 1 ) {
      wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
    } else {
      wp_redirect( site_url( '/search/' ) . get_search_query() );
    }
  }
}
add_action( 'template_redirect', 'pendrell_search_redirect' );



// Removes the ".recentcomments" style added to the header for no good reason
// http://www.narga.net/how-to-remove-or-disable-comment-reply-js-and-recentcomments-from-wordpress-header
function pendrell_remove_recent_comments_style() {
  global $wp_widget_factory;
  remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'pendrell_remove_recent_comments_style' );



// Allow HTML in author descriptions on single user blogs
if ( !is_multi_author() ) {
  remove_filter( 'pre_user_description', 'wp_filter_kses' );
}



// Ditch the default gallery styling, yuck
add_filter( 'use_default_gallery_style', '__return_false' );
