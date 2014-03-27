<?php // === SEARCH === //

// Modify how many posts per page are displayed in different contexts
// Source: http://wordpress.stackexchange.com/questions/21/show-a-different-number-of-posts-per-page-depending-on-context-e-g-homepage
function ubik_search_pre_get_posts( $query ) {
  if (
    is_search()
    && $query->is_main_query()
    && UBIK_SEARCH_POSTS_PER_PAGE
  ) {
    $query->set( 'posts_per_page', UBIK_SEARCH_POSTS_PER_PAGE );
  }
}
if ( UBIK_SEARCH_POSTS_PER_PAGE )
  add_action( 'pre_get_posts', 'ubik_search_pre_get_posts' );



// Redirect user to single search result: http://wpglee.com/2011/04/redirect-when-search-query-only-returns-one-match/
function ubik_search_redirect() {
  if ( is_search() && !empty( $_GET['s'] ) ) {
    global $wp_query;
    if ( $wp_query->post_count == 1 ) {
      wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
    } else {
      wp_redirect( site_url( '/search/' ) . get_search_query() );
    }
  }
}
if ( UBIK_SEARCH_REDIRECT )
  add_action( 'template_redirect', 'ubik_search_redirect' );