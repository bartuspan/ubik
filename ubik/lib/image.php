<?php // ==== IMAGES ==== //

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



// Generate an image shortcode when inserting images into a post
function ubik_image_send_to_editor( $html, $id, $caption, $title = '', $align, $url = '', $size = 'medium', $alt = '' ) {

  if ( !empty( $id ) )
    $content = ' id="' . esc_attr( $id ) . '"';

  if ( !empty( $align ) && $align !== 'none' )
    $content .= ' align="' . esc_attr( $align ) . '"';

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

// Generalized image markup generator; used by caption and image shortcodes; alternate markup presented on feeds is meant to validate
// Note: the $title variable is not used at all; it's WordPress legacy code; images don't need titles, just alt attributes
function ubik_image_markup( $html = '', $id, $caption, $title = '', $align = 'none', $url = '', $size = 'medium', $alt = '', $rel = '' ) {

  // If the $html variable is empty let's generate our own markup from scratch
  if ( empty( $html ) ) {

    // No fancy business in the feed
    if ( is_feed() ) {

      // The get_image_tag function requires a simple alignment e.g. "none", "left", etc.
      $align = str_replace( 'align', '', $align );

      // Default img element generator from WordPress core
      $html = get_image_tag( $id, $alt, $title, $align, $size );

    } else {

      // Responsive image size hook; see Pendrell for an example of usage
      // Use case: you have full-width content on a blog with a sidebar but you don't want to waste bandwidth by loading those images in feeds or in the regular flow of posts
      // Just filter this and return 'medium' when $size === 'large'
      $size = apply_filters( 'ubik_image_markup_size', $size );

      // WordPress is likely to supply an alt attribute; if not, let's copy the caption, assuming there is one
      if ( empty( $alt ) )
        $alt = esc_attr( $caption );

      // Custom replacement for get_image_tag(); roll your own instead of using $html = get_image_tag( $id, $alt, $title, $align, $size );
      list( $src, $width, $height, $is_intermediate ) = image_downsize( $id, $size );

      // If the image isn't resized then it is obviously the original; set $size to 'full' unless $width matches medium or large
      if ( $is_intermediate === false ) {

        // Test to see whether the presumably "full" sized image matches medium or large for consistent styling
        $medium = get_option( 'medium_size_w' );
        $large = get_option( 'large_size_w' );

        if ( $width === $medium ) {
          $size = 'medium';
        } elseif ( $width === $large ) {
          $size = 'large';
        } else {
          $size = 'full';
        }
      }

      // With all the pieces in place let's generate the img element
      $html = '<img itemprop="contentUrl" src="' . esc_attr( $src ) . '" ' . image_hwstring( $width, $height ) . 'class="wp-image-' . esc_attr( $id ) . ' size-' . esc_attr( $size ) . '" alt="' . esc_attr( $alt ) . '" />';

    }

    // Generate rel attribute from $rel variable; we only want this on images explicitly identified as attachments
    if ( !empty( $rel ) ) {
      if ( $rel === 'attachment' ) {
        $rel = ' rel="attachment wp-att-' . esc_attr( $id ) . '"';
      } else {
        // Reset the attribute so we don't pass garbage
        $rel = '';
      }
    }

    // Now wrap everything in a link
    if ( !empty( $url ) )
      $html = '<a href="' . esc_attr( $url ) . '"' . $rel . '>' . $html . '</a>';

  // If the $html variable has been passed (e.g. from caption shortcode, post thumbnail functions, or legacy code); we don't do much here
  } else {
    // Add itemprop="contentURL" to image element when $html variable is passed to this function; ugly hack but it works
    if ( !is_feed() )
      $html = str_replace( '<img', '<img itemprop="contentUrl"', $html );
  }

  // Sanitize $id, not that this should really be a problem
  $id = esc_attr( $id );

  // Caption processing
  if ( !empty( $caption ) ) {
    // Strip tags from captions but preserve some text formatting elements; this is mainly used to get rid of stray paragraph and break tags
    $caption = strip_tags( $caption, '<a><abbr><acronym><b><bdi><bdo><cite><code><del><em><i><ins><mark><q><rp><rt><ruby><s><small><strong><sub><sup><time><u>' );

    // Get rid of excess white space and line breaks to make things neat; adds a space instead
    $caption = trim( str_replace( array("\r\n", "\r", "\n"), ' ', $caption ) );

    // Do shortcodes and texturize (since shortcode contents aren't texturized by default)
    $caption = wptexturize( do_shortcode( $caption ) );

    // If the caption isn't empty generate wai-aria attribute for the figure element
    if ( !is_feed() )
      $aria = 'aria-describedby="figcaption-' . $id . '" ';
  } else {
    $aria = '';
  }

  // In case the align property arrived without being properly formed; might be unnecessary
  if ( $align === 'none' || $align === 'left' || $align === 'right' || $align === 'center' )
    $align = 'align' . $align;

  // There's a chance no $size will be passed to this function
  if ( !empty( $size ) )
    $size = ' size-' . esc_attr( $size );

  // Return stripped down markup for feeds
  if ( is_feed() ) {
    $content = $html;
    if ( !empty( $caption ) )
      $content .= '<br/><small>' . $caption . '</small>';

  // Generate image wrapper markup used everywhere else
  } else {
    $content = '<figure id="attachment-' . $id . '" ' . $aria . 'class="wp-caption wp-caption-' . $id . ' ' . esc_attr( $align ) . $size . '" itemscope itemtype="http://schema.org/ImageObject">' . $html;
    if ( !empty( $caption ) )
      $content .= '<figcaption id="figcaption-' . $id . '" class="wp-caption-text" itemprop="caption">' . $caption . '</figcaption>';
    $content .= '</figure>' . "\n";
  }

  return $content;
}
