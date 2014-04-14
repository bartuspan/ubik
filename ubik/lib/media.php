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

    if ( !empty( $attachments ) ) {
      echo wp_get_attachment_image( current( array_keys( $attachments ) ), $size );
    }
    // @TODO: add a default thumbnail!
  } else {
    return $html;
  }
}
add_filter( 'post_thumbnail_html', 'ubik_media_post_thumbnail', 11, 5 );



// == CAPTIONS == //

// Improves the WordPress core caption shortcode with HTML5 figure & figcaption; microdata & wai-aria attributes
// Source: http://joostkiens.com/improving-wp-caption-shortcode/
// Or perhaps: http://writings.orangegnome.com/writes/improved-html5-wordpress-captions/
// Or was it: http://clicknathan.com/2011/10/06/convert-wordpress-default-captions-shortcode-to-html-5-figure-and-figcaption-tags/
function ubik_media_caption_shortcode( $val, $attr, $html = '' ) {
  extract( shortcode_atts( array(
    'id'      => '',
    'align'   => 'none',
    'width'   => '',
    'caption' => ''
  ), $attr));

  // Fail gracefully if we aren't provided with an ID or a caption
  if ( empty( $id ) || empty( $caption ) )
    return $val;

  // Possible unnecessary given further processing
  if ( $id )
    $id = esc_attr( $id );

  // Add itemprop="contentURL" to image; ugly hack
  $html = str_replace( '<img', '<img itemprop="contentUrl"', $html );

  // Pass whatever we have to the general image markup generator
  return ubik_image_markup( $html, $id, $caption, $title = '', $align, $url = '', $size = '', $alt = '' );
}
add_filter( 'img_caption_shortcode', 'ubik_media_caption_shortcode', 10, 3 );



// == IMAGE SHORTCODE == //

// Create a really simple image shortcode based on HTML5 image markup requirements
function ubik_image_shortcode( $atts, $caption = null ) {
  extract( shortcode_atts( array(
    'id'            => '',
    'title'         => '',
    'align'         => 'none',
    'url'           => '',
    'size'          => 'medium',
    'alt'           => ''
  ), $atts ) );

  return apply_filters( 'ubik_image_shortcode', ubik_image_markup( $html = '', $id, $caption, $title, $align, $url, $size, $alt ) );
}
add_shortcode( 'image', 'ubik_image_shortcode' );



// Build an image shortcode when inserting images into a post
function ubik_image_send_to_editor( $html, $id, $caption, $title = '', $align, $url = '', $size = 'medium', $alt = '' ) {

  if ( !empty( $id ) )
    $content = ' id="' . esc_attr( $id ) . '"';

  // We don't bother with the title attribute; it's not useful for much of anything

  if ( !empty( $align ) )
    $content .= ' align="align' . esc_attr( $align ) . '"';

  if ( !empty( $url ) )
    $content .= ' url="' . esc_attr( $url ) . '"';

  if ( !empty( $size ) )
    $content .= ' size="' . esc_attr( $size ) . '"';

  if ( !empty( $alt ) )
    $content .= ' alt="' . esc_attr( $alt ) . '"';

  $content = '[image' . $content . ']';

  if ( !empty( $caption ) )
    $content .= $caption . '[/image]';

  return $content;
}
add_filter( 'image_send_to_editor', 'ubik_image_send_to_editor', 10, 9 );



// == IMAGE MARKUP == //

// Generalized image markup generator; used by caption and image shortcodes
function ubik_image_markup( $html = '', $id, $caption, $title, $align = 'alignnone', $url = '', $size = 'medium', $alt = '' ) {

  // If the $html variable is empty let's generate our own markup
  if ( empty( $html ) ) {

    // Responsive image size hook; see Pendrell for an example of usage
    $size = apply_filters( 'ubik_image_markup_size', $size );

    // Custom get_image_tag() function; used instead of $html = get_image_tag( $id, $alt, $title, $align, $size );
    list( $img_src, $width, $height ) = image_downsize( $id, $size );
    $html = '<img src="' . esc_attr( $img_src ) . '" alt="' . esc_attr( $alt ) . '" ' . image_hwstring( $width, $height ) . 'class="wp-image' . esc_attr( $id ) . '" itemprop="contentUrl" />';

    // @TODO: determine whether the link is an attachment or not; we shouldn't add rel in all circumstances
    //$rel = ' rel="attachment wp-att-' . esc_attr( $id ) . '"';

    if ( !empty( $url ) )
      $html = '<a href="' . esc_attr( $url ) . '">' . $html . '</a>';
  }

  // Caption cleaning; from core
  //$caption = str_replace( array("\r\n", "\r"), "\n", $caption);
  //$caption = preg_replace_callback( '/<[a-zA-Z0-9]+(?: [^<>]+>)*/', '_cleanup_image_add_caption', $caption );
  //$caption = preg_replace( '/[ \n\t]*\n[ \t]*/', '<br />', $caption );
  if ( $align === 'none' || $align === 'left' || $align === 'right' || $align === 'center' )
    $align = 'align' . $align;

  if ( !empty( $caption ) )
    $aria = 'aria-describedby="figcaption-' . $id . '" ';

  $content = '<figure id="attachment-' . $id . '" ' . $aria . 'class="wp-caption wp-caption-' . esc_attr( $id ) . ' ' . esc_attr( $align ) . ' size-' . esc_attr( $size ) . '" itemscope itemtype="http://schema.org/ImageObject">' . "\n";
  $content .= $html . "\n";

  if ( !empty( $caption ) )
    $content .= '<figcaption id="figcaption-' . $id . '" class="wp-caption-text" itemprop="caption">' . do_shortcode( trim( $caption ) ) . '</figcaption>' . "\n";

  $content .= '</figure>' . "\n";

  return $content;
}



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
