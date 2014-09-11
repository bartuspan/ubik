<?php // ==== EXCERPT ==== //

// Excerpt handling; shortcode activation, custom excerpt length and ending
function ubik_excerpt( $text = '' ) {
  $raw_excerpt = $text;

  // Generate an excerpt if nothing has been set
  if ( empty( $text ) || $text == '' ) {

    global $post;

    if ( post_password_required( $post->ID ) )
      return;

    // Ubik-specific excerpt content filter
    $content = apply_filters( 'ubik_excerpt_content', $post->post_content );

    // Regular content filter
    $text = apply_filters( 'the_content', $content );
  }

  $text = ubik_excerpt_sanitize( $text );

  return apply_filters( 'wp_trim_excerpt', $text, $raw_excerpt );
}
if ( UBIK_EXCERPT ) {
  remove_filter( 'get_the_excerpt', 'wp_trim_excerpt' );
  add_filter( 'get_the_excerpt', 'ubik_excerpt' );
}



// Process excerpts
function ubik_excerpt_sanitize( $text ) {

  if ( empty( $text ) || $text == '' )
    return;

  // Shortcode handler
  if ( UBIK_EXCERPT_SHORTCODES ) {
    $text = do_shortcode( $text );
  } else {
    $text = strip_shortcodes( $text );
  }

  // Strip any remaining tags
  $text = str_replace( ']]>', ']]&gt;', $text );

  // Excerpt length and ending; these can be set in ubik-config.php
  $excerpt_length = apply_filters( 'excerpt_length', 55 );
  $excerpt_more = apply_filters( 'excerpt_more', '&hellip;' );

  // Trim content to excerpt length and strip tags; don't add an ending just yet
  $text = wp_trim_words( $text, $excerpt_length, '' );

  // Beautify excerpts
  $text = wptexturize( $text );

  // Strip out trailing punctuation and add the excerpt ending
  if ( str_word_count( $text ) >= $excerpt_length )
    $text = preg_replace('/^[\p{P}|\p{S}|\s]+|[\p{P}|\p{S}|\s]+$/', '', $text ) . $excerpt_more;

  return $text;
}



// Custom excerpt length
function ubik_excerpt_length( $length ) {

  // Override default value with custom setting if it exists
  if ( ( ( empty( $length ) ) || ( $length == 55 ) ) && UBIK_EXCERPT_LENGTH )
    $length = UBIK_EXCERPT_LENGTH;

  return intval( $length );
}
if ( UBIK_EXCERPT_LENGTH )
  add_filter( 'excerpt_length', 'ubik_excerpt_length', 13 );



// One-off excerpts; set this in your code and the excerpt will bounce back to the default after one use; via https://gist.github.com/sanchothefat/3181655
function ubik_excerpt_length_transient( $length = 50 ) {
  add_filter( 'excerpt_length', create_function( '$l', 'return ' . intval( $length ) . ';' ), 13 ); // @TODO: upgrade this code to PHP 5.3
  add_filter( 'the_excerpt', create_function( '$e', 'remove_all_filters( "excerpt_length", 13 ); return $e;' ), 13 ); // @TODO: upgrade this code to PHP 5.3
}



// Custom excerpt ending
function ubik_excerpt_more( $more ) {
  return UBIK_EXCERPT_MORE;
}
if ( UBIK_EXCERPT_MORE )
  add_filter( 'excerpt_more', 'ubik_excerpt_more' );



// Custom excerpt more link; overwrites theme definition in the_content(); links direct to post instead of more link by design
function ubik_excerpt_more_link() {
  return ' <a href="'. esc_url( get_permalink() ) . '" class="more-link">' . __( 'Continue reading&nbsp;&rarr;', 'ubik' ) . '</a>';
}
if ( UBIK_EXCERPT_MORE_LINK )
  add_filter( 'the_content_more_link', 'ubik_excerpt_more_link' );



// Strip opening asides from excerpts; this way you can introduce posts with <aside>This post is a continuation of...</aside> without having this dominate search engine results and social media blurbs
function ubik_excerpt_strip_asides( $content ) {
  if ( strpos( $content, '<aside' ) < 10 )
    $content = preg_replace( '/<aside>(.*?)<\/aside>/si', '', $content, 1 );
  return $content;
}
add_filter( 'ubik_excerpt_content', 'ubik_excerpt_strip_asides' );



// Add excerpts to pages
function ubik_excerpt_pages() {
  add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'ubik_excerpt_pages' );
