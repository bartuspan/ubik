<?php // === CONTENT === //

// Remove all characters that are not the separator, a-z, 0-9, or whitespace; mainly for use with bilingual English/Chinese post titles
function ubik_strict_title( $title ) {
  // Lifted from http://wordpress.org/plugins/strings-sanitizer/
  $strict_title = preg_replace('![^'.preg_quote('-').'a-z0-_9\s]+!', '', strtolower( $title ) );

  // Only return the strict title if there is something left
  if ( !empty( $strict_title ) ) {
    return $strict_title;
  } else {
    return $title;
  }
}
if ( UBIK_STRICT_TITLE )
  add_filter( 'sanitize_title', 'ubik_strict_title', 1 );



// Output a human readable date wrapped in an HTML5 time tag
function ubik_date( $date ) {
  if ( is_archive() ) {
    return $date;
  } else {
    if ( ( current_time( 'timestamp' ) - get_the_time('U') ) < 86400 )
      $ubik_time = human_time_diff( get_the_time('U'), current_time( 'timestamp' ) ) . ' ago';
    else
      $ubik_time = get_the_time( 'M j, Y, g:i a', '', '' );
    return '<time datetime="' . get_the_time('c') . '" pubdate>' . $ubik_time . '</time>';
  }
}
add_filter( 'get_the_date', 'ubik_date' );



// Custom excerpt length; source: http://digwp.com/2010/03/wordpress-functions-php-template-custom-functions/
function ubik_excerpt_length( $length ) {
  if ( UBIK_EXCERPT_LENGTH )
    $length = UBIK_EXCERPT_LENGTH;

  return $length;
}
add_filter( 'excerpt_length', 'ubik_excerpt_length' );
