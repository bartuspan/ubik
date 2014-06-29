<?php // ==== THUMBNAILS ==== //

// Default thumbnail taken from first attached image; adapted from: http://wpengineer.com/1735/easier-better-solutions-to-get-pictures-on-your-posts/
function ubik_thumbnail( $html, $post_id, $post_thumbnail_id, $size, $attr ) {

  // If this post doesn't already have a thumbnail
  if ( empty( $html ) && !empty( $post_id ) )
    $post_thumbnail_id = ubik_thumbnail_id( $post_id );

  // Attempt to beautify thumbnail markup; note: this means that you shouldn't wrap post thumbnails in additional image markup
  if ( function_exists( 'ubik_image_markup' ) && !empty( $post_thumbnail_id ) ) {
    $html = ubik_image_markup( '', $post_thumbnail_id, '', '', 'none', get_permalink( $post_id ), $size );
  } else {
    $html = wp_get_attachment_image( $post_thumbnail_id, $size, false, $attr );
  }

  return $html;
}
add_filter( 'post_thumbnail_html', 'ubik_thumbnail', 11, 5 );



// Return the ID of a post's thumbnail, the first attached image, or a fallback image specified in ubik-config.php
function ubik_thumbnail_id( $post_id = null, $fallback_id = null ) {

  // Try to get the current post ID if one was not passed
  if ( $post_id === null )
    $post_id = get_the_ID();

  if ( empty( $post_id ) )
    return false;

  // Check for attachments and return the first of the lot
  $attachments = get_children( array(
    'numberposts'    => 1,
    'order'          => 'ASC',
    'orderby'        => 'menu_order ASC',
    'post_parent'    => $post_id,
    'post_mime_type' => 'image',
    'post_status'    => 'inherit',
    'post_type'      => 'attachment'
    )
  );

  // Fetch the first attachment if it exists
  if ( !empty( $attachments ) )
    return current( array_keys( $attachments ) );

  // Default image fallback; double check it is an existing image attachment first
  if ( $fallback_id === null && is_int( UBIK_THUMBNAIL_DEFAULT ) )
    $fallback_id = UBIK_THUMBNAIL_DEFAULT;

  if ( !empty( $fallback_id ) ) {
    $fallback_id = (int) $fallback_id;
    $post = get_post( $fallback_id );
    if ( !empty( $post ) ) {
      if ( ubik_is_image_attachment( $fallback_id ) )
        return $fallback_id;
    }
  }

  // No thumbnail, attachment, or fallback image was found
  return false;
}



// Remove image width and height attributes from most images; via https://gist.github.com/miklb/2919525
function ubik_thumbnail_dimensions( $html ) {
  $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
  $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
  return $html;
}
if ( UBIK_THUMBNAIL_DIMENSIONS === false ) {
  add_filter( 'post_thumbnail_html', 'ubik_thumbnail_dimensions', 10 );
  add_filter( 'img_caption_shortcode', 'ubik_thumbnail_dimensions' );
  add_filter( 'wp_caption', 'ubik_thumbnail_dimensions', 10 );
  add_filter( 'caption', 'ubik_thumbnail_dimensions', 10 );
  add_filter( 'ubik_image_shortcode', 'ubik_thumbnail_dimensions', 10);
}
