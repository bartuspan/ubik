<?php // ==== MEDIA ==== //

// == THUMBNAILS == //

// Default thumbnail taken from first attached image; source: http://wpengineer.com/1735/easier-better-solutions-to-get-pictures-on-your-posts/
function ubik_media_thumbnail_fallback( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
  if ( empty( $html ) ) {
    $attachments = get_children( array(
      'post_parent'    => get_the_ID(),
      'post_type'      => 'attachment',
      'numberposts'    => 1,
      'post_status'    => 'inherit',
      'post_mime_type' => 'image',
      'order'          => 'ASC',
      'orderby'        => 'menu_order ASC'
    ), ARRAY_A );

    if ( !empty( $attachments ) ) {
      return wp_get_attachment_image( current( array_keys( $attachments ) ), $size );
    }
    // @TODO: add a default thumbnail
  } else {
    return $html;
  }
}
add_filter( 'post_thumbnail_html', 'ubik_media_thumbnail_fallback', 11, 5 );



// Remove image width and height attributes from most images; via https://gist.github.com/miklb/2919525
function ubik_media_thumbnail_dimensions( $html ) {
  $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
  $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
  return $html;
}
if ( UBIK_MEDIA_THUMBNAIL_ATTS ) {
  add_filter( 'post_thumbnail_html', 'ubik_media_thumbnail_dimensions', 10 );
  add_filter( 'img_caption_shortcode', 'ubik_media_thumbnail_dimensions' );
  add_filter( 'wp_caption', 'ubik_media_thumbnail_dimensions', 10 );
  add_filter( 'caption', 'ubik_media_thumbnail_dimensions', 10 );
}



// == CAPTIONS == //

// Improves the WordPress core caption shortcode with HTML5 figure & figcaption; microdata & WAI-ARIA accessibility attributes
// Source: http://joostkiens.com/improving-wp-caption-shortcode/
// Or perhaps: http://writings.orangegnome.com/writes/improved-html5-wordpress-captions/
// Or was it: http://clicknathan.com/2011/10/06/convert-wordpress-default-captions-shortcode-to-html-5-figure-and-figcaption-tags/
function ubik_media_caption_shortcode( $val, $attr, $html = '' ) {
  extract( shortcode_atts( array(
    'id'      => '',
    'align'   => 'none',
    'width'   => '',
    'caption' => '',
    'class'   => ''
  ), $attr) );

  // Default back to WordPress core if we aren't provided with an ID, a caption, or if no img element is present; returning '' tells the core to handle things
  if ( empty( $id ) || empty( $caption ) || strpos( $html, '<img' ) === false )
    return '';

  // Pass whatever we have to the general image markup generator
  return ubik_image_markup( $html, $id, $caption, $title = '', $align );
}
add_filter( 'img_caption_shortcode', 'ubik_media_caption_shortcode', 10, 3 );



// == ATTACHMENTS == //

// Turn off comments for all attachments; source: http://rayofsolaris.net/blog/2011/comments-on-attachments-in-wordpress
function ubik_media_attachment_comments( $open, $post_id ) {
  $post = get_post( $post_id );
  if( $post->post_type == 'attachment' ) {
    return false;
  }
  return $open;
}
if ( UBIK_MEDIA_ATTACHMENT_COMMENTS === false )
  add_filter( 'comments_open', 'ubik_media_attachment_comments', 10 , 2 );



// == GALLERY == //

// Remove injected CSS from the default WordPress gallery shortcode if we aren't using a custom gallery shortcode
function ubik_media_gallery_style($css) {
  return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}
add_filter( 'gallery_style', 'ubik_media_gallery_style' );

// Ditch the default gallery styling no matter what, yuck
add_filter( 'use_default_gallery_style', '__return_false' );
