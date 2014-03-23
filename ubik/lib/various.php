<?php // === VARIOUS === //
// A collection of theme-agnostic snippets

// Modify how many posts per page are displayed in different contexts
// Source: http://wordpress.stackexchange.com/questions/21/show-a-different-number-of-posts-per-page-depending-on-context-e-g-homepage
function ubik_pre_get_posts( $query ) {
  if (
    is_search()
    && $query->is_main_query()
    && UBIK_POSTS_PER_PAGE_SEARCH
  ) {
    $query->set( 'posts_per_page', UBIK_POSTS_PER_PAGE_SEARCH );
  }
}
add_action( 'pre_get_posts', 'ubik_pre_get_posts' );



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



// Removes the ".recentcomments" style added to the header for no good reason
// http://www.narga.net/how-to-remove-or-disable-comment-reply-js-and-recentcomments-from-wordpress-header
function ubik_remove_recent_comments_style() {
  global $wp_widget_factory;
  remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'ubik_remove_recent_comments_style' );



// Allow HTML in author descriptions on single user blogs
if ( !is_multi_author() ) {
  remove_filter( 'pre_user_description', 'wp_filter_kses' );
}



// Ditch the default gallery styling, yuck
add_filter( 'use_default_gallery_style', '__return_false' );



function all_settings_link() {
  add_options_page( __('All Settings'), __('All Settings'), 'administrator', 'options.php' );
}
add_action('admin_menu', 'all_settings_link');



// Sub-optimal hack to deal with Jetpack Markdown failing to decode single quote HTML entities; should be removed when the issue is fixed
function ubik_markdown_codeblock_fix( $content ) {
  return preg_replace_callback( "/^(`{3})([^`\n]+)?\n([^`~]+)(`{3})/m", 'ubik_markdown_codeblock_preserve', $content );
}
function ubik_markdown_codeblock_replace( $content ) {
  return str_replace( '&amp;#039;', '\'', $content);
}
function ubik_markdown_codeblock_preserve( $matches ) {
  $block = html_entity_decode( $matches[3], ENT_QUOTES );
  $open = $matches[1] . $matches[2] . "\n";
  return $open . $block . $matches[4];
}
add_filter( 'edit_post_content', 'ubik_markdown_codeblock_fix', 11, 2 );
add_filter( 'wpcom_markdown_transform_post', 'ubik_markdown_codeblock_replace');
