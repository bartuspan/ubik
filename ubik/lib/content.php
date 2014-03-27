<?php // === CONTENT === //

// Dynamic page titles; hooks into wp_title to improve search engine ranking without making a mess
function ubik_wp_title( $title = '', $sep = '-', $seplocation = 'right' ) {

  // Remove default seperator and add spacing
  if ( ( trim( $sep ) === '' ) || $sep === '&raquo;' )
    $sep = '-';
  $sep = ' ' . $sep . ' ';

  // Determine page number, if any
  $page_num = '';
  if ( is_paged() ) {
    global $page, $paged;
    if ( $paged >= 2 || $page >= 2 )
      $page_num = $sep . sprintf( __( 'Page %d', 'ubik' ), max( $paged, $page ) );
  }

  // Generate the title
  $title = ubik_title( $sep );

  // Apply a filter before adding the name of the blog to the title
  $title = apply_filters( 'ubik_wp_title', $title );

  // Two scenarios for title generation: front/home page and everything else
  if ( !is_front_page() && !is_home() ) {
    $title .= $sep . get_bloginfo( 'name' );
  }

  // Sanitize and add page number as needed
  return esc_html( strip_tags( stripslashes( $title . $page_num ) ) );
}
// Lower priority than WP default (10) and Pendrell (11) so titles aren't doubled up
if ( UBIK_CONTENT_TITLE )
  add_filter( 'wp_title', 'ubik_wp_title', 12, 3 );



// Generate a context-dependent title; abstracted for use with wp_title as well as other functions
function ubik_title( $sep = ' - ' ) {
  global $post;

  // Page not found
  if ( is_404() )
    $title = __( 'Page not found', 'ubik' );

  // Archives; some guidance from Hybrid on times and dates
  if ( is_archive() ) {
    if ( is_author() )
      $title = sprintf( __( 'Posts by %s', 'ubik' ), get_the_author_meta( 'display_name', get_query_var( 'author' ) ) );
    elseif ( is_category() )
      $title = sprintf( __( '%s category archive', 'ubik' ), single_term_title( '', false ) );
    elseif ( is_tag() )
      $title = sprintf( __( '%s tag archive', 'ubik' ), single_term_title( '', false ) );
    elseif ( is_post_type_archive() )
      $title = sprintf( __( '%s archive', 'ubik' ), post_type_archive_title( '', false ) );
    elseif ( is_tax() )
      $title = sprintf( __( '%s archive', 'ubik' ), single_term_title( '', false ) );
    elseif ( is_date() ) {
      if ( get_query_var( 'second' ) || get_query_var( 'minute' ) || get_query_var( 'hour' ) )
        $title = sprintf( __( 'Archive for %s', 'ubik' ), get_the_time( __( 'g:i a', 'ubik' ) ) );
      elseif ( is_day() )
        $title = sprintf( __( '%s daily archive', 'ubik' ), get_the_date() );
      elseif ( get_query_var( 'w' ) )
        $title = sprintf( __( 'Archive for week %1$s of %2$s', 'ubik' ), get_the_time( __( 'W', 'ubik' ) ), get_the_time( __( 'Y', 'ubik' ) ) );
      elseif ( is_month() )
        $title = sprintf( __( '%s monthly archive', 'ubik' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'ubik' ) ) );
      elseif ( is_year() )
        $title = sprintf( __( '%s yearly archive', 'ubik' ), get_the_date( _x( 'Y', 'yearly archives date format', 'ubik' ) ) );
      else
        $title = get_the_date();
    }
  }

  // Is this a feed? Let's make it simple
  if ( is_feed() )
    $title = single_post_title( '', false );

  // Front or home page
  if ( is_front_page() || is_home() ) {
    $title = get_bloginfo( 'name' );
    if ( get_bloginfo( 'description' ) && !is_paged() )
      $title .= $sep . get_bloginfo( 'description' );
  }

  // Search; put the query in the title
  if ( is_search() ) {
    if ( trim( get_search_query() ) == '' )
      $title = __( 'No search query entered', 'ubik' );
    else
      $title = sprintf( __( 'Search results for &#8216;%s&#8217;', 'ubik' ), trim( get_search_query() ) );
  }

  // Single posts, pages, and attachments
  if ( is_singular() ) {
    $title = single_post_title( '', false );
  }

  // Clean things up a bit
  $title = preg_replace('/\s+/', ' ', trim( $title) );

  // Send in...send back
  return $title;
}



// Remove all characters that are not the separator, a-z, 0-9, or whitespace; mainly for use with bilingual English/Chinese post titles
function ubik_slug_strict( $title ) {
  // Lifted from http://wordpress.org/plugins/strings-sanitizer/
  $strict_title = preg_replace('![^'.preg_quote('-').'a-z0-_9\s]+!', '', strtolower( $title ) );

  // Only return the strict title if there is something left
  if ( !empty( $strict_title ) ) {
    return $strict_title;
  } else {
    return $title;
  }
}
if ( UBIK_CONTENT_SLUG_STRICT )
  add_filter( 'sanitize_title', 'ubik_slug_strict', 1 );



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
// Switch for the date function; of course the function can still be called directly in templates
if ( UBIK_CONTENT_DATE )
  add_filter( 'get_the_date', 'ubik_date' );


