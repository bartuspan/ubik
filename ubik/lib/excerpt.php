<?php // ==== EXCERPT ==== //

// Excerpt handling; shortcode activation, custom excerpt length and ending
function ubik_excerpt( $text = '' ) {
  $raw_excerpt = $text;

  // Generate an excerpt if nothing has been set
  if ( empty( $text ) || $text == '' ) {

    global $post;

    if ( post_password_required( $post->ID ) )
      return;

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
  $text = ubik_excerpt_shortcode_handler( $text );

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
  return UBIK_EXCERPT_LENGTH;
}
if ( UBIK_EXCERPT_LENGTH )
  add_filter( 'excerpt_length', 'ubik_excerpt_length' );



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



// Shortcode handler
function ubik_excerpt_shortcode_handler( $text ) {
  if ( UBIK_EXCERPT_SHORTCODES ) {
    return do_shortcode( $text );
  } else {
    return strip_shortcodes( $text );
  }
}



// Fix the description for image format posts to the metadata associated with that image
function ubik_excerpt_image_format( $content ) {
  global $post;

  // If we're dealing with an image format post with a thumbnail fetch caption and description of the image attachment
  if ( is_singular() && has_post_format( 'image' ) && has_post_thumbnail() ) {
    $caption = get_post( get_post_thumbnail_id() )->post_excerpt;
    $description = get_post( get_post_thumbnail_id() )->post_content;
    if ( !empty( $caption ) ) {
      $content = $caption;
    } elseif ( !empty( $description ) ) {
      $content = $description;
    }
  }
  return $content;
}
add_filter( 'ubik_excerpt_content', 'ubik_excerpt_image_format' );
