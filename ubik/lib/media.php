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

  if ( !empty( $align ) )
    $content .= ' align="align' . esc_attr( $align ) . '"';

  if ( !empty( $url ) )
    $content .= ' url="' . esc_attr( $url ) . '"';

  if ( !empty( $size ) )
    $content .= ' size="' . esc_attr( $size ) . '"';

  if ( !empty( $alt ) )
    $content .= ' alt="' . esc_attr( $alt ) . '"';

  if ( !empty( $caption ) ) {
    $content = '[image' . $content . ']' . $caption . '[/image]';
  } else {
    $content = '[image' . $content . '/]';
  }

  return $content;
}
add_filter( 'image_send_to_editor', 'ubik_image_send_to_editor', 10, 9 );



// == IMAGE MARKUP == //

// Generalized image markup generator; used by caption and image shortcodes
function ubik_image_markup( $html = '', $id, $caption, $title = '', $align = 'alignnone', $url = '', $size = 'medium', $alt = '' ) {

  // If the $html variable is empty let's generate our own markup
  if ( empty( $html ) ) {

    // Responsive image size hook; see Pendrell for an example of usage
    $size = apply_filters( 'ubik_image_markup_size', $size );

    // WordPress is very good at alt attribute fallbacks; just in case, let's copy the caption over here if it isn't filled
    if ( empty( $alt ) )
      $alt = esc_attr( $caption );

    // Custom get_image_tag() function; used instead of $html = get_image_tag( $id, $alt, $title, $align, $size );
    list( $src, $width, $height ) = image_downsize( $id, $size );

    // For reference: ' . image_hwstring( $width, $height ) . '
    $html = '<img src="' . esc_attr( $src ) . '" alt="' . esc_attr( $alt ) . '" class="wp-image-' . esc_attr( $id ) . '" itemprop="contentUrl" />';

    // @TODO: determing whether $url points to an attachment or not; add the rel attribute if so; low-priority
    //$rel = ' rel="attachment wp-att-' . esc_attr( $id ) . '"';

    if ( !empty( $url ) )
      $html = '<a href="' . esc_attr( $url ) . '">' . $html . '</a>';

  // If the $html variable has been passed (e.g. from caption shortcode, post thumbnail functions, or legacy code)
  } else {
    // Add itemprop="contentURL" to image; ugly hack
    $html = str_replace( '<img', '<img itemprop="contentUrl"', $html );
  }

  // Sanitize $id, not that this should really be a problem
  $id = esc_attr( $id );

  // If the caption isn't empty generate wai-aria attribute for the figure element
  if ( !empty( $caption ) ) {
    $aria = 'aria-describedby="figcaption-' . $id . '" ';
  } else {
    $aria = '';
  }

  // Strip tags from captions but preserve some text formatting elements; this is mainly used to get rid of stray paragraph and break tags
  $caption = strip_tags( $caption, '<a><abbr><acronym><b><bdi><bdo><cite><code><del><em><i><ins><mark><q><rp><rt><ruby><s><small><strong><sub><sup><time><u>' );

  // Get rid of excess white space and line breaks to make things neat; adds a space instead
  $caption = trim( str_replace( array("\r\n", "\r", "\n"), ' ', $caption ) );

  // Do shortcodes and texturize (since shortcode contents aren't texturized by default)
  $caption = wptexturize( do_shortcode( $caption ) );

  // In case the align property arrived without being properly formed; might be unnecessary
  if ( $align === 'none' || $align === 'left' || $align === 'right' || $align === 'center' )
    $align = 'align' . $align;

  // There's a chance no $size will be passed to this function
  if ( !empty( $size ) )
    $size = ' size-' . esc_attr( $size );

  // Image wrapper element
  $content = '<figure id="attachment-' . $id . '" ' . $aria . 'class="wp-caption wp-caption-' . $id . ' ' . esc_attr( $align ) . $size . '" itemscope itemtype="http://schema.org/ImageObject">' . "\n";

  // The HTML for the link (optional) and image generated above or fed into this function from somewhere else
  $content .= $html . "\n";

  // Wraps the caption in a figcaption element with appropriate markup
  if ( !empty( $caption ) )
    $content .= '<figcaption id="figcaption-' . $id . '" class="wp-caption-text" itemprop="caption">' . $caption . '</figcaption>' . "\n";

  // Good to the last drop
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
