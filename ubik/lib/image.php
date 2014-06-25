<?php // ==== IMAGES ==== //

// == SHORTCODES == //

// Create a really simple image shortcode based on HTML5 image markup standards
function ubik_image_shortcode( $atts, $caption = '' ) {
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
if ( UBIK_IMAGE_SHORTCODE )
  add_shortcode( 'image', 'ubik_image_shortcode' );



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



// A simple shortcode designed to group images; see Pendrell for an example of usage: https://github.com/synapticism/pendrell
// Removes paragraph and break elements inserted by wpautop function; easier and more reliable than messing around with the order of filters
// Core function shortcode_unautop doesn't work here because the wpautop filter has already run
function ubik_group_shortcode( $atts, $content ) {
  return '<div class="image-group">' . str_replace( array('<p>', '</p>', '<br>', '<br/>'), '', do_shortcode( $content ) ) . '</div>';
}
if ( UBIK_IMAGE_SHORTCODE )
  add_shortcode( 'group', 'ubik_group_shortcode' );



// Generate an image shortcode when inserting images into a post
function ubik_image_send_to_editor( $html, $id, $caption = '', $title = '', $align = '', $url = '', $size = 'medium', $alt = '' ) {

  if ( !empty( $id ) )
    $content = ' id="' . esc_attr( $id ) . '"';

  if ( !empty( $align ) && $align !== 'none' )
    $content .= ' align="' . esc_attr( $align ) . '"';

  // URL is left blank for attachments; only specified in the event of a custom URL, media object link, or when "none" is selected
  if ( !empty( $url ) ) {
    if ( !( strpos( $url, 'attachment_id' ) || get_attachment_link( $id ) == $url ) ) {
      $content .= ' url="' . esc_attr( $url ) . '"';
    }
  } else {
    $content .= ' url="none"';
  }

  if ( !empty( $size ) && $size !== 'medium' )
    $content .= ' size="' . esc_attr( $size ) . '"';

  // Alt attribute defaults to caption contents which may contain shortcodes and markup; process shortcodes here and let the image shortcode do the rest
  $alt = do_shortcode( $alt );

  // Only set the alt attribute if it isn't identical to the caption
  if ( !empty( $alt ) && $alt !== $caption )
    $content .= ' alt="' . $alt . '"';

  if ( !empty( $caption ) ) {
    $content = '[image' . $content . ']' . $caption . '[/image]';
  } else {
    $content = '[image' . $content . '/]';
  }

  return $content;
}
if ( UBIK_IMAGE_SHORTCODE )
  add_filter( 'image_send_to_editor', 'ubik_image_send_to_editor', 10, 9 );



// == IMAGE MARKUP == //

// Generalized image markup generator; used by captioned images and image shortcodes; alternate markup presented on feeds is intended to validate
// Note: the $title variable is not used at all; it's WordPress legacy code; images don't need titles, just alt attributes
function ubik_image_markup( $html = '', $id = '', $caption = '', $title = '', $align = 'none', $url = '', $size = 'medium', $alt = '', $rel = '' ) {

  // Sanitize $id and ensure it points to an existing attachment
  $id = (int) esc_attr( $id );

  if ( !empty( $id ) ) {
    $post = get_post( $id );
    if ( empty( $post ) )
      return;
    if ( $post->post_type !== 'attachment' )
      return;
  }

  // If the $html variable is empty let's generate our own markup from scratch
  if ( empty( $html ) && !empty( $id ) ) {

    // Default back to post title if alt attribute is empty
    if ( empty( $alt ) && !empty( $post ) )
      $alt = $post->post_title;

    // Clean up the alt attribute; it may contain HTML and other things
    $alt = esc_attr( strip_tags( $alt ) );

    // No fancy business in the feed
    if ( is_feed() ) {

      // The get_image_tag function requires a simple alignment e.g. "none", "left", etc.
      $align = str_replace( 'align', '', $align );

      // Default img element generator from WordPress core
      $html = get_image_tag( $id, $alt, $title, $align, $size );

    } else {

      // Dynamic image size hook; see Pendrell for an example of usage
      // Use case: you have full-width content on a blog with a sidebar but you don't want to waste bandwidth by loading those images in feeds or in the regular flow of posts
      // Just filter this and return 'medium' when $size === 'large'
      $size = apply_filters( 'ubik_image_markup_size', $size );

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
      $html = '<img itemprop="contentUrl" src="' . esc_attr( $src ) . '" ' . image_hwstring( $width, $height ) . 'class="wp-image-' . esc_attr( $id ) . ' size-' . esc_attr( $size ) . '" alt="' . $alt . '" />';
    }

    // If no URL is set let's default back to an attachment link; for no URL use url="none"
    if ( empty( $url ) ) {
      $url = 'attachment';
    }

    // Generate the link from the contents of the $url variable; optionally generates URL and rel attribute for images explicitly identified as attachments
    if ( !empty( $url ) ) {
      if ( $url === 'attachment' ) {
        $url = get_attachment_link( $id );
        $rel = ' rel="attachment wp-att-' . esc_attr( $id ) . '"';
      } elseif ( $url === 'none' ) {
        $url = '';
      }
    }

    // Now wrap everything in a link
    if ( !empty( $url ) ) {
      $html = '<a href="' . esc_attr( $url ) . '"' . $rel . '>' . $html . '</a>';
    }

  // If the $html variable has been passed (e.g. from caption shortcode, post thumbnail functions, or legacy code) we don't do much here
  } else {

    // Add itemprop="contentURL" to image element when $html variable is passed to this function; ugly hack but it works
    if ( !is_feed() )
      $html = str_replace( '<img', '<img itemprop="contentUrl"', $html );
  }

  // Initialize ARIA attributes
  $aria = '';

  // Caption processing
  if ( !empty( $caption ) ) {
    // Strip tags from captions but preserve some text formatting elements; this is mainly used to get rid of stray paragraph and break tags
    $caption = strip_tags( $caption, '<a><abbr><acronym><b><bdi><bdo><cite><code><del><em><i><ins><mark><q><rp><rt><ruby><s><small><strong><sub><sup><time><u>' );

    // Replace excess white space and line breaks with a single space to neaten things up
    $caption = trim( str_replace( array("\r\n", "\r", "\n"), ' ', $caption ) );

    // Do shortcodes and texturize (since shortcode contents aren't texturized by default)
    $caption = wptexturize( do_shortcode( $caption ) );

    // Generate ARIA attributes for the figure element if the ID isn't empty and this isn't a feed
    if ( !is_feed() && !empty( $id ) )
      $aria = 'aria-describedby="figcaption-' . $id . '" ';
  }

  // Prefix $align with "align"; saves us the trouble of writing it out all the time
  if ( $align === 'none' || $align === 'left' || $align === 'right' || $align === 'center' )
    $align = ' align' . esc_attr( $align );

  // There's a chance $size will have been wiped clean by the `ubik_image_markup_size` filter
  if ( !empty( $size ) )
    $size = ' size-' . esc_attr( $size );

  // Return stripped down markup for feeds
  if ( is_feed() ) {
    $content = $html;
    if ( !empty( $caption ) )
      $content .= '<br/><small>' . $caption . '</small> '; // Note the space

  // Generate image wrapper markup used everywhere else
  } else {

    // Edge case where $id is not set
    if ( empty( $id ) ) {
      $content = '<figure class="wp-caption' . $align . $size . '" itemscope itemtype="http://schema.org/ImageObject">' . $html;
      if ( !empty( $caption ) )
        $content .= '<figcaption class="wp-caption-text" itemprop="caption">' . $caption . '</figcaption>';

    // Regular output
    } else {
      $content = '<figure id="attachment-' . $id . '" ' . $aria . 'class="wp-caption wp-caption-' . $id . $align . $size . '" itemscope itemtype="http://schema.org/ImageObject">' . $html;
      if ( !empty( $caption ) )
        $content .= '<figcaption id="figcaption-' . $id . '" class="wp-caption-text" itemprop="caption">' . $caption . '</figcaption>';
    }
    $content .= '</figure>' . "\n";
  }

  return $content;
}
