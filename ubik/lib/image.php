<?php // ==== IMAGES ==== //

// @TODO: clean this file up and apply switches to functions enabled by default

// == IMAGE MARKUP == //

// Generalized image markup generator; used by captioned images and image shortcodes; alternate markup presented on feeds is intended to validate
// Note: the $title variable is not used at all; it's WordPress legacy code; images don't need titles, just alt attributes
function ubik_image_markup( $html = '', $id = '', $caption = '', $title = '', $align = 'none', $url = '', $size = 'medium', $alt = '', $rel = '', $class = '' ) {

  // Sanitize $id and ensure it points to an existing image attachment; if not, spit out $html
  $id = (int) $id;
  if ( !empty( $id ) ) {
    $post = get_post( $id );
    if ( empty( $post ) || !wp_attachment_is_image( $id ) )
      return $html;
  }

  // Default alignment
  if ( empty( $align ) )
    $align = 'none';

  // Default size
  if ( empty( $size ) )
    $size = 'medium';

  // If the $html variable is empty let's generate our own markup from scratch
  if ( empty( $html ) ) {

    // Clean up the alt attribute; it may contain HTML and other things
    $alt = esc_attr( strip_tags( $alt ) );

    // Default back to post title if alt attribute is empty
    if ( empty( $alt ) && !empty( $post ) )
      $alt = $post->post_title;

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

      // If we have an ID to work with we can roll our own image element...
      if ( !empty( $id ) ) {

        // Custom replacement for get_image_tag(); roll your own instead of using $html = get_image_tag( $id, $alt, $title, $align, $size );
        list( $src, $width, $height, $is_intermediate ) = image_downsize( $id, $size );

        // If the image isn't resized then it is obviously the original; set $size to 'full' unless $width matches medium or large
        if ( !$is_intermediate ) {

          // Check if the size requested is a hard-cropped square
          $size_metadata = ubik_get_image_sizes( $size );
          if ( $size_metadata['width'] == $size_metadata['height'] && $size_metadata['crop'] == true ) {
            // Now check if the original image is square; if not, return a thumbnail, which is definitely square (but low quality)
            if ( $width != $height ) {
              $size = 'thumbnail';
              list( $src, $width, $height ) = image_downsize( $id, $size );
            }

          // Test to see whether the presumably "full" sized image matches medium or large for consistent styling
          } else {
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
        }

        // With all the pieces in place let's generate the img element
        $html = '<img itemprop="contentUrl" src="' . esc_attr( $src ) . '" ' . image_hwstring( $width, $height ) . 'class="wp-image-' . $id . ' size-' . esc_attr( $size ) . '" alt="' . $alt . '" />';

      // No ID was passed; let's make a placeholder...
      } else {
        $html = '<div class="no-image size-' . esc_attr( $size ) . '"></div>';
      }
    }

    // If no URL is set let's default back to an attachment link (unless we're dealing with an attachment already); for no URL use url="none"
    if ( empty( $url ) && !wp_attachment_is_image() )
      $url = 'attachment';

    // Generate the link from the contents of the $url variable; optionally generates URL and rel attribute for images explicitly identified as attachments
    if ( !empty( $url ) ) {
      if ( $url === 'attachment' ) {
        $url = get_attachment_link( $id );
        $rel = ' rel="attachment wp-att-' . $id . '"';
      } elseif ( $url === 'none' ) {
        $url = '';
      }
    }

    // Now wrap everything in a link (whether it's an actual image element or just a div placeholder)
    if ( !empty( $url ) ) {
      $html = '<a href="' . $url . '"' . $rel . '>' . $html . '</a>';
    }

  // But if the $html variable has been passed (e.g. from caption shortcode, post thumbnail functions, or legacy code) we don't do much...
  } else {

    // Add itemprop="contentURL" to image element when $html variable is passed to this function; ugly hack but it works
    if ( !is_feed() )
      $html = str_replace( '<img', '<img itemprop="contentUrl"', $html );
  }

  // Don't generate double the markup; deals with edge cases in which content is fed through this function twice
  if ( !strpos( $html, '<figure class="wp-caption' ) ) {

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

      // Conditionally generate ARIA attributes for the figure element
      if ( !empty( $id ) )
        $aria = 'aria-describedby="figcaption-' . $id . '" ';
    }

    // Initialize class array
    if ( empty( $class ) || !is_array( $class ) )
      $class = (array) $class;

    // Prefix $align with "align"; saves us the trouble of writing it out all the time
    if ( $align === 'none' || $align === 'left' || $align === 'right' || $align === 'center' ) {
      $class[] = 'align' . $align;
    } else {
      $class[] = $align;
    }

    // There's a chance $size will have been wiped clean by the `ubik_image_markup_size` filter
    if ( !empty( $size ) )
      $class[] = 'size-' . $size;

    // Create class string
    $class = ' ' . esc_attr( trim( implode( ' ', $class ) ) );

    // Return stripped down markup for feeds
    if ( is_feed() ) {
      $content = $html;
      if ( !empty( $caption ) )
        $content .= '<br/><small>' . $caption . '</small> '; // Note the trailing space

    // Generate image wrapper markup used everywhere else
    } else {

      // Edge case where ID is not set
      if ( empty( $id ) ) {
        $content = '<figure class="wp-caption' . $class . '" itemscope itemtype="http://schema.org/ImageObject">' . $html;
        if ( !empty( $caption ) )
          $content .= '<figcaption class="wp-caption-text" itemprop="caption">' . $caption . '</figcaption>';

      // Regular output
      } else {
        $content = '<figure id="attachment-' . $id . '" ' . $aria . 'class="wp-caption wp-caption-' . $id . $class . '" itemscope itemtype="http://schema.org/ImageObject">' . $html;
        if ( !empty( $caption ) )
          $content .= '<figcaption id="figcaption-' . $id . '" class="wp-caption-text" itemprop="caption">' . $caption . '</figcaption>';
      }
      $content .= '</figure>' . "\n";
    }
  }
  return $content;
}



// Get info about various images sizes, both standard and custom; adapted from http://codex.wordpress.org/Function_Reference/get_intermediate_image_sizes
function ubik_get_image_sizes( $size ) {

  global $_wp_additional_image_sizes;
  $sizes = array();
  $get_intermediate_image_sizes = get_intermediate_image_sizes();

  // Create the full array with sizes and crop info
  foreach( $get_intermediate_image_sizes as $_size ) {
    if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
      $sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
      $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
      $sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
    } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
      $sizes[ $_size ] = array(
        'width' => $_wp_additional_image_sizes[ $_size ]['width'],
        'height' => $_wp_additional_image_sizes[ $_size ]['height'],
        'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
      );
    }
  }

  // Get only one size if found
  if ( $size ) {
    if( isset( $sizes[ $size ] ) ) {
      return $sizes[ $size ];
    } else {
      return false;
    }
  }
  return $sizes;
}



// == THUMBNAILS == //

// Default thumbnail taken from first attached image; adapted from: http://wpengineer.com/1735/easier-better-solutions-to-get-pictures-on-your-posts/
function ubik_thumbnail( $html, $post_id, $post_thumbnail_id, $size, $attr ) {

  // If this post doesn't already have a thumbnail
  if ( empty( $html ) && !empty( $post_id ) )
    $post_thumbnail_id = ubik_thumbnail_id( $post_id );

  // Attempt to beautify thumbnail markup; note: this means that you shouldn't wrap post thumbnails in additional image markup
  if ( function_exists( 'ubik_image_markup' ) && !empty( $post_thumbnail_id ) && UBIK_THUMBNAIL_MARKUP ) {
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

  // Return false if we have nothing to work with
  if ( empty( $post_id ) )
    return false;

  // Check for an existing featured image; this should take precedence over the other methods
  if ( has_post_thumbnail( $post_id ) )
    return get_post_thumbnail_id( $post_id );

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
      if ( wp_attachment_is_image( $fallback_id ) )
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
if ( UBIK_THUMBNAIL_NO_DIMENSIONS ) {
  add_filter( 'post_thumbnail_html', 'ubik_thumbnail_dimensions', 10 );
  add_filter( 'img_caption_shortcode', 'ubik_thumbnail_dimensions' );
  add_filter( 'wp_caption', 'ubik_thumbnail_dimensions', 10 );
  add_filter( 'caption', 'ubik_thumbnail_dimensions', 10 );
  add_filter( 'ubik_image_shortcode', 'ubik_thumbnail_dimensions', 10);
}
