<?php // ==== IMAGE SHORTCODES ==== //

// Create a really simple image shortcode based on HTML5 image markup standards
function ubik_image_shortcode( $atts, $caption = '' ) {
  $args = shortcode_atts( array(
    'id'            => '',
    'title'         => '',
    'align'         => 'none',
    'url'           => '',
    'size'          => 'medium',
    'alt'           => ''
  ), $atts );

  $id = $args['id'];
  $title = $args['title'];
  $align = $args['align'];
  $url = $args['url'];
  $size = $args['size'];
  $alt = $args['alt'];

  return apply_filters( 'ubik_image_shortcode', ubik_image_markup( $html = '', $id, $caption, $title, $align, $url, $size, $alt ) );
}
if ( UBIK_IMAGE_SHORTCODE )
  add_shortcode( 'image', 'ubik_image_shortcode' );



// A simple shortcode designed to group images; see Pendrell for an example of usage: https://github.com/synapticism/pendrell
function ubik_image_group_shortcode( $atts, $content ) {

  // Default values
  $args = shortcode_atts( array(
    'columns'   => UBIK_IMAGE_SHORTCODE_COLUMNS,
    'size'      => ''
  ), $atts );

  $columns = $args['columns'];
  $size = $args['size'];

  // Force an image size if one has not been set
  if ( !strpos( 'size="', $content ) )
    $content = str_replace( '[image ', '[image size="' . $size . '" ', $content );

  // Removes paragraph and break elements inserted by wpautop function; easier and more reliable than messing around with the order of filters
  $content = str_replace( array('<p>', '</p>', '<br>', '<br/>', '<br />'), '', do_shortcode( $content ) );

  // Cast the columns attribute; keep the number under 6; prepare the columns class
  $columns = (int) $columns;
  if ( $columns >= 6 )
    $columns = 5;
  if ( $columns > 1 ) {
    $column_class = ' img-group-' . $columns;
  } else {
    $column_class = '';
  }

  if ( !empty( $content ) )
    $content = '<div class="img-group' . $column_class . '">' . $content . '</div>';
  return $content;
}
if ( UBIK_IMAGE_SHORTCODE )
  add_shortcode( 'group', 'ubik_image_group_shortcode' );



// Improves the WordPress core caption shortcode with HTML5 figure & figcaption; microdata & WAI-ARIA accessibility attributes
// Source: http://joostkiens.com/improving-wp-caption-shortcode/
// Or perhaps: http://writings.orangegnome.com/writes/improved-html5-wordpress-captions/
// Or was it: http://clicknathan.com/2011/10/06/convert-wordpress-default-captions-shortcode-to-html-5-figure-and-figcaption-tags/
function ubik_caption_shortcode( $val, $atts, $html = '' ) {
  $args = shortcode_atts( array(
    'id'      => '',
    'align'   => 'none',
    'width'   => '',
    'caption' => '',
    'class'   => ''
  ), $atts );

  $id = $args['id'];
  $align = $args['align'];
  $width = $args['width'];
  $caption = $args['caption'];
  $class = $args['class'];

  // Default back to WordPress core if we aren't provided with an ID, a caption, or if no img element is present; returning '' tells the core to handle things
  if ( empty( $id ) || empty( $caption ) || strpos( $html, '<img' ) === false )
    return '';

  // Pass whatever we have to the general image markup generator
  return ubik_image_markup( $html, $id, $caption, $title = '', $align );
}
add_filter( 'img_caption_shortcode', 'ubik_caption_shortcode', 10, 3 );



// == ADMIN == //

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

  // Default size: medium
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
