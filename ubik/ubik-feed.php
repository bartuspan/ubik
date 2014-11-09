<?php // ==== FEED ==== //

// Cleaner feed title
function ubik_feed_title( $title, $sep ) {
  if ( !is_archive() )
    return '';
  return $title;
}
add_filter( 'get_wp_title_rss', 'ubik_feed_title', 10, 2 );



// Cleans out the RSS feed
function ubik_generator() {
  return '';
}
add_filter( 'the_generator', 'ubik_generator' );



// Standardize image sizes on feeds; just send medium regardless of what's in the original content
// @TODO: flesh out this function to handle custom image sizes
function ubik_feed_images( $size ) {
  if ( is_feed() && $size === 'large' )
    return 'medium';
  return $size;
}
add_filter( 'post_thumbnail_size', 'ubik_feed_images' );
//add_filter( 'ubik_image_markup_size', 'ubik_feed_images' ); // @TODO: reinstate



// Remove certain post formats from the feed; via https://wordpress.stackexchange.com/questions/18412/how-to-exclude-posts-of-a-certain-format-from-the-feed
function ubik_feed_disable_formats( &$wp_query ) {
  if ( $wp_query->is_feed() ) {
    $post_format_tax_query = array(
      'taxonomy' => 'post_format',
      'field' => 'slug',
      'terms' => array( 'post-format-aside', 'post-format-link', 'post-format-quote', 'post-format-status' ),
      'operator' => 'NOT IN'
    );
    $tax_query = $wp_query->get( 'tax_query' );
    if ( is_array( $tax_query ) ) {
      $tax_query = $tax_query + $post_format_tax_query;
    } else {
      $tax_query = array( $post_format_tax_query );
    }
    $wp_query->set( 'tax_query', $tax_query );
  }
}
if ( UBIK_FEED_DISABLE_FORMATS )
  add_action( 'pre_get_posts', 'ubik_feed_disable_formats' );



// Disable all feeds
function ubik_feed_disable() {
  remove_theme_support( 'automatic-feed-links' );
}
if ( UBIK_FEED_DISABLE )
  add_action( 'after_theme_support', 'ubik_feed_disable' );



// Disable comments feed
function ubik_feed_disable_comments( $for_comments ){
  if( $for_comments ){
    remove_action( 'do_feed_atom', 'do_feed_atom', 10, 1 );
    remove_action( 'do_feed_rdf', 'do_feed_rdf', 10, 1 );
    remove_action( 'do_feed_rss', 'do_feed_rss', 10, 1 );
    remove_action( 'do_feed_rss2', 'do_feed_rss2', 10, 1 );
  }
}
if ( UBIK_FEED_DISABLE_COMMENTS ) {
  add_action( 'do_feed_atom', 'ubik_feed_disable_comments', 9, 1 );
  add_action( 'do_feed_rdf', 'ubik_feed_disable_comments', 9, 1 );
  add_action( 'do_feed_rss', 'ubik_feed_disable_comments', 9, 1 );
  add_action( 'do_feed_rss2', 'ubik_feed_disable_comments', 9, 1 );
}



// == DEVELOPMENT ZONE == //

// @TODO: add taxonomies to feeds
// @TODO: add footer text to feeds
// @TODO: feed publication delay
