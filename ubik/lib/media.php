<?php // ==== MEDIA ==== //

// == THUMBNAILS == //

// Default thumbnail taken from first attached image
function ubik_media_post_thumbnail( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
  // Source: http://wpengineer.com/1735/easier-better-solutions-to-get-pictures-on-your-posts/
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

    if ( $attachments ) {
      echo wp_get_attachment_image( current( array_keys( $attachments ) ), $size );
    }
    // @TODO: add a default thumbnail!
  } else {
    return $html;
  }
}
add_filter( 'post_thumbnail_html', 'ubik_media_post_thumbnail', 11, 5 );



// == CAPTIONS == //

// Improves the caption shortcode with HTML5 figure & figcaption; microdata & wai-aria attributes
// Source: http://joostkiens.com/improving-wp-caption-shortcode/
// Or perhaps: http://writings.orangegnome.com/writes/improved-html5-wordpress-captions/
// Or was it: http://clicknathan.com/2011/10/06/convert-wordpress-default-captions-shortcode-to-html-5-figure-and-figcaption-tags/
function ubik_media_caption_shortcode( $val, $attr, $content = null ) {
  extract( shortcode_atts( array(
    'id'      => '',
    'align'   => 'alignnone',
    'width'   => '',
    'caption' => ''
  ), $attr));

  // No caption, no dice...
  if ( empty( $caption ) )
    return $val;

  if ( $id )
    $id = esc_attr( $id );

  // Add itemprop="contentURL" to image; ugly hack
  $content = str_replace('<img', '<img itemprop="contentURL"', $content);

  $figure = '<figure id="' . $id . '" aria-describedby="figcaption-' . $id . '" class="wp-caption ' . esc_attr($align) . '" itemscope itemtype="http://schema.org/ImageObject">' . "\n";
  $figure .= do_shortcode( $content ) . "\n";
  $figure .= '<figcaption id="figcaption-'. $id . '" class="wp-caption-text" itemprop="description">' . $caption . '</figcaption>' . "\n";
  $figure .= '</figure>' . "\n";

  return $figure;
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

// Ditch the default gallery styling no matter what, yuck
add_filter( 'use_default_gallery_style', '__return_false' );



// Remove injected CSS from the default WordPress gallery shortcode if we aren't using a custom gallery shortcode
function ubik_media_gallery_style($css) {
  return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}
add_filter( 'gallery_style', 'ubik_media_gallery_style' );
