<?php // === CONTENT === //

// Dynamic page titles; hooks into wp_title to improve search engine ranking without making a mess
function ubik_content_title_filter( $title = '', $sep = '-', $seplocation = 'right' ) {

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
  $title = ubik_content_title( $sep );

  // Apply a filter before adding the name of the blog to the title
  $title = apply_filters( 'ubik_content_title_filter', $title );

  // Two scenarios for title generation: front/home page and everything else
  if ( !is_front_page() && !is_home() ) {
    $title .= $sep . get_bloginfo( 'name' );
  }

  // Sanitize and add page number as needed
  return esc_html( strip_tags( stripslashes( $title . $page_num ) ) );
}
// Lower priority than WP default (10) and Pendrell (11) so titles aren't doubled up
if ( UBIK_CONTENT_TITLE )
  add_filter( 'wp_title', 'ubik_content_title_filter', 12, 3 );



// Generate a context-dependent title; abstracted for use with wp_title filter as well as other functions
function ubik_content_title( $sep = ' - ' ) {
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



// Output a human readable date wrapped in an HTML5 time tag
function ubik_content_date( $date ) {

  // Force timestamp
  $date = date( 'U', $date );

  // Force date format
  if ( UBIK_CONTENT_DATE_FORMAT ) {
    $date_format = UBIK_CONTENT_DATE_FORMAT;
  } else {
    // Whatever the default is...
    $date_format = 'M j, Y, g:i a';
  }

  if ( UBIK_CONTENT_DATE_HUMAN && !is_archive() && ( current_time( 'timestamp' ) - $date ) < 86400 ) {
    $ubik_date = human_time_diff( $date, current_time( 'timestamp' ) ) . ' ago';
  } else {
    $ubik_date = date( $date_format, $date );
  }
  return '<time datetime="' . date('c', $date ) . '" pubdate>' . $ubik_date . '</time>';
}
// Switch for the date function; of course the function can still be called directly in templates
if ( UBIK_CONTENT_DATE )
  add_filter( 'get_the_date', 'ubik_content_date' );



// Output entry metadata: date, author, category, tags, etc.
function ubik_content_meta() {

  // Content type
  if ( is_attachment() ) {
    if ( wp_attachment_is_image() ) {
      $type = __( 'image', 'ubik' );
    } else {
      $type = __( 'attachment', 'ubik' );
    }
  } elseif ( is_page() ) {
    $type = __( 'page', 'ubik' );
  } else {
    $type = __( 'entry', 'ubik' );
  }

  // Post format voodoo
  $post_format = get_post_format();
  if ( $post_format ) {
    if ( $post_format === 'quote' || $post_format === 'quotation' ) {
      $post_format_name = 'Quotation';
    } else {
      $post_format_name = get_post_format_string( $post_format );
    }
    $type = sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
      esc_url( get_post_format_link( $post_format ) ),
      esc_attr( sprintf( __( '%s archive', 'ubik' ), $post_format_name ) ),
      esc_attr( strtolower( $post_format_name ) )
    );
  }

  // Post type voodoo; get all post types that aren't built in and cycle through to see if we have a match
  $custom_post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' );
  foreach ( $custom_post_types as $custom_post_type ) {
    if ( $custom_post_type->name === get_post_type() ) {
      if ( $custom_post_type->has_archive ) {
        $type = sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
          esc_url( get_post_type_archive_link( $custom_post_type->name ) ),
          esc_attr( sprintf( __( '%s archive', 'ubik' ), $custom_post_type->name ) ),
          esc_attr( strtolower( $custom_post_type->labels->singular_name ) )
        );
      } else {
        $type = strtolower( $custom_post_type->labels->singular_name );
      }
    }
  }

  $type = apply_filters( 'ubik_content_meta_type', $type );



  // Date
  $date_published_raw = get_the_time( 'U' );
  $date_updated_raw = get_the_modified_time( 'U' );

  // If the dates are the same both classes need to be added to the same span
  if ( $date_published_raw === $date_updated_raw ) {
    $date_published_class = 'published updated';
  } else {
    // Only generate updated date if the dates differ
    $date_updated = '<span class="updated">' . ubik_content_date( $date_updated_raw ) . '</span>';
    $date_published_class = 'published';
  }

  // Published date
  $date_published = sprintf( '<span class="%1$s"><a href="%2$s" title="%3$s" rel="bookmark">%4$s</a></span>',
    $date_published_class,
    esc_url( get_permalink() ),
    the_title_attribute( array( 'before' => __( 'Permalink to ', 'ubik' ), 'echo' => false ) ),
    ubik_content_date( $date_published_raw )
  );



  // Parent link for pages, images, attachments, and places
  $parent = '';
  if ( ( is_attachment() && wp_attachment_is_image() && $post->post_parent ) || ( ( is_page() || get_post_type() === 'place' ) && $post->post_parent ) ) {
    if ( is_attachment() && wp_attachment_is_image() && $post->post_parent ) {
      $parent_rel = 'gallery';
    } elseif ( is_page() && $post->post_parent ) {
      $parent_rel = 'parent';
    }
    $parent = sprintf( __( '<a href="%1$s" title="Return to %2$s" rel="%3$s">%4$s</a>', 'ubik' ),
      esc_url( get_permalink( $post->post_parent ) ),
      esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ),
      $parent_rel,
      get_the_title( $post->post_parent )
    );
  }



  // Category
  $categories = apply_filters( 'ubik_content_meta_categories', get_the_category_list( __( ', ', 'ubik' ) ) );



  // Tags
  $tags = apply_filters( 'ubik_content_meta_tags', get_the_tag_list( '', __( ', ', 'ubik' ) ) );



  // Author
  $author = sprintf( '<span class="author vcard"><a class="fn n url" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
    esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
    esc_attr( sprintf( __( 'View all posts by %s', 'ubik' ), get_the_author() ) ),
    get_the_author()
  );
  $author = apply_filters( 'ubik_content_meta_author', $author );



  // Build the entry meta line
  $entry_meta = 'This %1$s was published %2$s';
  if ( $parent ) {
    $entry_meta .= ' under %$3s';
  }
  if ( $categories && ( $post_format === false ) ) {
    $entry_meta .= ' in %$4s';
  }
  if ( $tags ) {
    $entry_meta .= ' and tagged %5$s';
  }
  $entry_meta .= '<span class="by-author"> by %6$s</span>.';
  if ( $date_updated ) {
    $entry_meta .= '<span class="last-updated"> Last updated %7$s.</a>';
  }

  // Build the entry meta line the old-fashioned way
  $entry_meta = 'This ' . $type . ' was published ' . $date_published;
  if ( $parent ) {
    $entry_meta .= ' under ' . $parent;
  }
  if ( $categories && ( $post_format === false ) ) {
    $entry_meta .= ' in ' . $categories;
  }
  if ( $tags ) {
    $entry_meta .= ' and tagged ' . $tags;
  }
  $entry_meta .= '<span class="by-author"> by ' . $author . '</span>.';
  if ( $date_updated ) {
    $entry_meta .= '<span class="last-updated"> Last updated ' . $date_updated . '.</span>';
  }

  // Translating this is a nightmare; Twenty Twelve had it down cold but it was also much more simple
  printf(
    $entry_meta,
    $type,
    $date_published,
    $parent,
    $categories,
    $tags,
    $author,
    $date_updated
  );

  /*printf(
      $utility_text,
      $categories,
      $tags,
      $date,
      $author,
      $type,
      $parent
    );*/
}