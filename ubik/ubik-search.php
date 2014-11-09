<?php // ==== SEARCH ==== //

// Custom search form; easily overridden by the use of searchform.php in your theme
if ( !function_exists( 'ubik_search_form' ) ) : function ubik_search_form( $html ) {

  // Smarter search form default
  if ( is_search() ) {
    $value = get_search_query();
  } else {
    $value = '';
  }

  // Prepare the form
  $html =  '<form role="search" method="get" class="search-form" action="' .  trailingslashit( home_url() ) . '">' . "\n";
  $html .= '  <label>' . "\n";
  $html .= '    <span class="screen-reader-text">' . __( 'Search for&hellip;', 'ubik' ) . '</span>' . "\n";
  $html .= '    <input type="search" class="search-field" placeholder="' . __( 'Search for&hellip;', 'ubik' ) . '" value="' . $value . '" name="s" title="' . __( 'Search for&hellip;', 'ubik' ) . '" /> ' . "\n";
  $html .= '  </label>' . "\n";
  $html .= '  <input type="submit" class="search-submit button" value="' . __( 'Search', 'ubik' ) . '" />' . "\n";
  $html .= '</form>' . "\n";

  return $html;
} endif;
if ( UBIK_SEARCH_FORM )
  add_action( 'get_search_form', 'ubik_search_form' );



// Modify how many posts per page are displayed in different contexts
// Source: http://wordpress.stackexchange.com/questions/21/show-a-different-number-of-posts-per-page-depending-on-context-e-g-homepage
function ubik_search_pre_get_posts( $query ) {
  if (
    is_search()
    && $query->is_main_query()
    && is_int( UBIK_SEARCH_POSTS_PER_PAGE )
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
      ubik_search_rewrite();
    }
  }
}
if ( UBIK_SEARCH_REDIRECT )
  add_action( 'template_redirect', 'ubik_search_redirect' );



// "Nice search" rewrite; full credit to Mark Jaquith for this function: https://wordpress.org/plugins/nice-search/
function ubik_search_rewrite() {
  global $wp_rewrite;
  if ( !isset( $wp_rewrite ) || !is_object( $wp_rewrite ) || !$wp_rewrite->using_permalinks() )
    return;

  $search_base = $wp_rewrite->search_base;
  if ( is_search() && !is_admin() && strpos( $_SERVER['REQUEST_URI'], "/{$search_base}/" ) === false ) {
    wp_redirect( home_url( "/{$search_base}/" . urlencode( get_query_var( 's' ) ) ) );
    exit();
  }
}
