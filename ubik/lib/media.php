<?php // ==== MEDIA ==== //

// == THUMBNAILS == //

// Default thumbnail taken from first attached image
function ubik_post_thumbnail( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
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
add_filter( 'post_thumbnail_html', 'ubik_post_thumbnail', 11, 5 );



// == CAPTIONS == //

// Improves the caption shortcode with HTML5 figure & figcaption; microdata & wai-aria attributes
// Source: http://joostkiens.com/improving-wp-caption-shortcode/
function ubik_caption_shortcode_filter( $val, $attr, $content = null ) {
  extract( shortcode_atts( array(
    'id'      => '',
    'align'   => 'aligncenter',
    'width'   => '',
    'caption' => ''
  ), $attr));

  // No caption, no dice... But why width?
  if ( 1 > (int) $width || empty( $caption ) )
    return $val;

  if ( $id )
    $id = esc_attr( $id );

  // Add itemprop="contentURL" to image - Ugly hack
  $content = str_replace('<img', '<img itemprop="contentURL"', $content);

  return '<figure id="' . $id . '" aria-describedby="figcaption-' . $id . '" class="wp-caption ' . esc_attr($align) . '" itemscope itemtype="http://schema.org/ImageObject" style="width: ' . (0 + (int) $width) . 'px">' . do_shortcode( $content ) . '<figcaption id="figcaption-'. $id . '" class="wp-caption-text" itemprop="description">' . $caption . '</figcaption></figure>';
}
add_filter( 'img_caption_shortcode', 'ubik_caption_shortcode_filter', 10, 3 );



// == GALLERY == //

// Ditch the default gallery styling no matter what, yuck
add_filter( 'use_default_gallery_style', '__return_false' );



// Remove injected CSS from the default WordPress gallery shortcode if we aren't using a custom gallery shortcode
function ubik_gallery_style($css) {
  return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}
if ( !UBIK_MEDIA_GALLERY )
  add_filter( 'gallery_style', 'ubik_gallery_style' );



// Custom gallery shortcode function; some guidance from https://wordpress.stackexchange.com/questions/43558/how-to-manually-fix-the-wordpress-gallery-code-using-php-in-functions-php
function ubik_gallery( $output, $attr ) {
  global $post;

  static $instance = 0;
  $instance++;

  // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
  if ( isset( $attr['orderby'] ) ) {
    $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
    if ( !$attr['orderby'] )
      unset( $attr['orderby'] );
  }

  extract(shortcode_atts(array(
    'order'      => 'ASC',
    'orderby'    => 'menu_order ID',
    'id'         => $post->ID,
    'itemtag'    => 'li',
    'icontag'    => 'figure',
    'captiontag' => 'figcaption',
    'columns'    => 3,
    'size'       => 'medium',
    'include'    => '',
    'exclude'    => ''
  ), $attr));

  $id = intval($id);
  if ( 'RAND' == $order )
    $orderby = 'none';

  if ( !empty($include) ) {
    $include = preg_replace( '/[^0-9,]+/', '', $include );
    $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

    $attachments = array();
    foreach ( $_attachments as $key => $val ) {
      $attachments[$val->ID] = $_attachments[$key];
    }
  } elseif ( !empty($exclude) ) {
    $exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
    $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
  } else {
    $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
  }

  if ( empty($attachments) )
    return '';

  if ( is_feed() ) {
    $output = "\n";
    foreach ( $attachments as $att_id => $attachment )
      $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
    return $output;
  }

  $itemtag = tag_escape($itemtag);
  $captiontag = tag_escape($captiontag);
  $columns = intval($columns);
  $itemwidth = $columns > 0 ? floor(100/$columns) : 100;

  // What about using $content_width to determine $size?
  $size_class = sanitize_html_class( $size );
  // Don't mess around with this too much; the gallery_style filter usually needs a div element to play with
  $gallery_div = '<div id="gallery-' . $instance . '" class="gallery gallery-id-' . $id . ' gallery-columns-' .  $columns . ' gallery-size-' . $size_class . '"><ul>';
  $output = apply_filters( 'gallery_style', $gallery_div );

  foreach ( $attachments as $id => $attachment ) {
    $link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);
    if ( $captiontag && trim($attachment->post_excerpt) ) {
      $output .= '<' . $itemtag . ' class="gallery-item gallery-item-id-' . $id . '"><' . $icontag . ' aria-describedby="figcaption-' . $id . '" class="gallery-icon">' . $link . '</' . $icontag . '>';
      $output .= '<' . $captiontag . ' id="figcaption-' . $id . '" class="wp-caption-text gallery-caption">' . wptexturize( $attachment->post_name ) . '</' . $captiontag . '>';
    } else {
      $output .= '<' . $itemtag . ' class="gallery-item gallery-item-id-' . $id . '"><' . $icontag . ' class="gallery-icon">' . $link . '</' . $icontag . '>';
    }
    $output .= '</' . $itemtag . '>';
  }

  $output .= '</ul></div>';

  return $output;
}
// This will absolutely break the display on any theme but Pendrell; @TODO: consider removing this component from Ubik
if ( UBIK_MEDIA_GALLERY ) {
  add_filter( 'post_gallery', 'ubik_gallery', 10, 2);
  // This enables compatibility with Jetpack Carousel
  add_filter( 'jp_carousel_force_enable', '__return_true' );
}