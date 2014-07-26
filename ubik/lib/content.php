<?php // ==== CONTENT ==== //

// - Title
// - Date
// - Entry meta
// - Content filters

// == TITLE == //

// Dynamic page titles; hooks into wp_title to improve search engine ranking without making a mess
function ubik_content_title_filter( $title = '', $sep = '-', $seplocation = 'right' ) {

  // Remove default seperator and add spacing
  if ( ( trim( $sep ) === '' ) || $sep === '&raquo;' || $sep === '&#187;' )
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
  $title = apply_filters( 'ubik_content_title', $title );

  // Three scenarios for title generation: feeds, front/home page, and everything else
  if ( is_feed() && is_archive() ) {
    $title = $sep . $title;
  } elseif ( !is_front_page() && !is_home() ) {
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
      $title = sprintf( __( '%s category archives', 'ubik' ), single_term_title( '', false ) );
    elseif ( is_tag() )
      $title = sprintf( __( '%s tag archives', 'ubik' ), single_term_title( '', false ) );
    elseif ( is_post_type_archive() )
      $title = sprintf( __( '%s archives', 'ubik' ), post_type_archive_title( '', false ) );
    elseif ( is_tax() )
      $title = sprintf( __( '%s archives', 'ubik' ), single_term_title( '', false ) );
    elseif ( is_date() ) {
      if ( get_query_var( 'second' ) || get_query_var( 'minute' ) || get_query_var( 'hour' ) )
        $title = sprintf( __( '%s archives', 'ubik' ), get_the_time( __( 'g:i a', 'ubik' ) ) );
      elseif ( is_day() )
        $title = sprintf( __( '%s daily archives', 'ubik' ), get_the_date() );
      elseif ( get_query_var( 'w' ) )
        $title = sprintf( __( 'Archives for week %1$s of %2$s', 'ubik' ), get_the_time( __( 'W', 'ubik' ) ), get_the_time( __( 'Y', 'ubik' ) ) );
      elseif ( is_month() )
        $title = sprintf( __( '%s monthly archives', 'ubik' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'ubik' ) ) );
      elseif ( is_year() )
        $title = sprintf( __( '%s yearly archives', 'ubik' ), get_the_date( _x( 'Y', 'yearly archives date format', 'ubik' ) ) );
      else
        $title = get_the_date();
    }
  }

  // Is this a feed? Let's handle this in feed.php except if it's an archive
  if ( is_feed() && !is_archive() )
    $title = '';

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
  if ( is_singular() )
    $title = single_post_title( '', false );

  // Clean things up a bit
  $title = preg_replace('/\s+/', ' ', trim( $title) );

  // Send in...send back
  return $title;
}



// == DATE == //

// Output a human readable date wrapped in an HTML5 time tag
function ubik_content_date( $date ) {

  // Force timestamp
  $date = date( 'U', $date );

  // Force date format
  if ( UBIK_CONTENT_DATE_FORMAT ) {
    $date_format = UBIK_CONTENT_DATE_FORMAT;
  } else {
    // Whatever the default is...
    $date_format = get_option('date_format') . ', ' . get_option('time_format');
  }

  // Human date span
  if ( UBIK_CONTENT_DATE_HUMAN_SPAN ) {
    $date_span = UBIK_CONTENT_DATE_HUMAN_SPAN;
  } else {
    $date_span = 4838000; // Eight weeks
  }

  if ( UBIK_CONTENT_DATE_HUMAN && !is_archive() && ( current_time( 'timestamp' ) - $date ) < $date_span ) {
    $ubik_date = human_time_diff( $date, current_time( 'timestamp' ) ) . ' ago';
  } else {
    $ubik_date = date( $date_format, $date );
  }
  // The HTML5 spec for the time tag used to include a pubdate attribute but as of March 2014 it no longer does
  return '<time datetime="' . date('c', $date ) . '">' . $ubik_date . '</time>';
}
// Switch for the date function; of course the function can still be called directly in templates
if ( UBIK_CONTENT_DATE )
  add_filter( 'get_the_date', 'ubik_content_date' );



// == ENTRY META == //

// Output entry metadata: date, author, category, tags, etc.
function ubik_entry_meta() {

  // FILTERS
  // ubik_entry_meta_format
  // ubik_entry_meta_type
  // ubik_entry_meta_date_published
  // ubik_entry_meta_date_updated
  // ubik_entry_meta_date_parent
  // ubik_entry_meta_categories
  // ubik_entry_meta_tags
  // ubik_entry_meta_taxonomies
  // ubik_entry_meta_author
  // ubik_entry_meta

  $type = '';
  $post_format = '';
  $custom_post_types = '';
  $parent = '';
  $date_published_u = '';
  $date_published = '';
  $date_updated_u = '';
  $date_updated = '';
  $categories = '';
  $tags = '';
  $author = '';



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
    // This sets a default type that can be overridden later
    $type = __( 'entry', 'ubik' );
  }



  // Post format voodoo
  $post_format = get_post_format();
  if ( $post_format ) {
    $post_format_name = apply_filters( 'ubik_entry_meta_format', get_post_format_string( $post_format ) );
    $type = sprintf( '<a href="%1$s">%2$s</a>',
      esc_url( get_post_format_link( $post_format ) ),
      esc_attr( strtolower( $post_format_name ) )
    );
  }



  // Post type voodoo; get all post types that aren't built-in and cycle through to see if we have a match
  $custom_post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' );
  foreach ( $custom_post_types as $custom_post_type ) {
    if ( $custom_post_type->name === get_post_type() ) {
      if ( $custom_post_type->has_archive ) {
        $type = sprintf( '<a href="%1$s">%2$s</a>',
          esc_url( get_post_type_archive_link( $custom_post_type->name ) ),
          esc_attr( strtolower( $custom_post_type->labels->singular_name ) )
        );
      } else {
        $type = strtolower( $custom_post_type->labels->singular_name );
      }
    }
  }
  $type = apply_filters( 'ubik_entry_meta_type', $type );



  // Date
  $date_published_u = get_the_time( 'U' );
  $date_updated_u = get_the_modified_time( 'U' );
  $date_diff = $date_updated_u - $date_published_u;

  // If the dates differ by less than a day just go with the updated date
  // This accounts for two scenarios: posts that haven't been updated and posts that were updated not long after initial publication
  if ( $date_diff < 86400 ) {
    $date_published_class = 'entry-date post-date published updated';
    $date_published = ubik_content_date( $date_updated_u );
    $date_updated = '';
  } else {
    // Only generate updated date if the dates differ
    $date_published_class = 'entry-date post-date published';
    $date_published = ubik_content_date( $date_published_u );
    $date_updated = '<span class="updated">' . ubik_content_date( $date_updated_u ) . '</span>';
  }

  // Published date
  $date_published = sprintf( '<span class="%1$s"><a href="%2$s" rel="bookmark">%3$s</a></span>',
    $date_published_class,
    esc_url( get_permalink() ),
    $date_published
  );

  apply_filters( 'ubik_entry_meta_date_published', $date_published );
  apply_filters( 'ubik_entry_meta_date_updated', $date_updated );



  // Parent link for pages, images, attachments, and places
  global $post;
  if ( $post->post_parent ) {
    if ( wp_attachment_is_image() ) {
      $parent_rel = 'gallery';
    } else {
      $parent_rel = 'parent';
    }
    $parent = sprintf( __( '<a href="%1$s" rel="%2$s">%3$s</a>', 'ubik' ),
      esc_url( get_permalink( $post->post_parent ) ),
      $parent_rel,
      get_the_title( $post->post_parent )
    );
  }
  $parent = apply_filters( 'ubik_entry_meta_parent', $parent );



  // Category
  if ( ubik_categorized_blog() )
    $categories = ubik_get_the_popular_term_list( $post->ID, 'category', '', ', ', '', 0 ); // Show all categories by popularity
  $categories = apply_filters( 'ubik_entry_meta_categories', $categories );



  // Tags
  $tags = ubik_get_the_popular_term_list( $post->ID, 'post_tag', '', ', ' );
  $tags = apply_filters( 'ubik_entry_meta_tags', $tags );



  // Taxonomies; allows plugins and other code to hook into this function to add entry metadata
  $taxonomies = apply_filters( 'ubik_entry_meta_taxonomies', $taxonomies = '' );



  // Author
  $author = sprintf( '<span class="author vcard"><a class="fn n url" href="%1$s" rel="author">%2$s</a></span>',
    esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
    get_the_author()
  );
  $author = apply_filters( 'ubik_entry_meta_author', $author );



  // Has this post been updated?
  $date_updated_text = '';
  if ( !empty( $date_updated ) )
    $date_updated_text = '<span class="last-updated"> and updated ' . $date_updated . '</span>';



  // Setup entry meta data; the only information we have for sure is type, date, and author; @TODO: make this translation-friendly
  $entry_meta = 'This ' . $type . ' was published ' . $date_published . $date_updated_text . '<span class="by-author"> by ' . $author . '</span>. ' . "\n";

  if ( !empty( $parent ) )
    $entry_meta_extras[] = 'Posted under: ' . $parent . '. ';

  if ( !empty( $categories ) )
    $entry_meta_extras[] = 'Category: ' . $categories . '. ';

  if ( !empty( $tags ) )
    $entry_meta_extras[] = 'Tags: ' . $tags . '. ';

  if ( !empty( $taxonomies ) )
    $entry_meta_extras[] = $taxonomies;

  if ( !empty( $entry_meta_extras ) )
    $entry_meta .= implode( $entry_meta_extras ) . "\n";

  return apply_filters( 'ubik_entry_meta', $entry_meta );
}



// == CONTENT FILTERS == //

// Strip paragraph tags from orphaned more tags; mainly a hack to address more tags placed next to image shortcodes
function ubik_strip_more_tag_orphan( $content ) {
  $content = preg_replace( '/<p><span id="more-[0-9]*?"><\/span><\/p>/', '', $content );
  $content = preg_replace( '/<p><span id="more-[0-9]*?"><\/span>(<(div|img|figure)[\s\S]*?)<\/p>/', '$1', $content );
  $content = preg_replace( '/<p>(<(div|img|figure)[\s\S]*?)<span id="more-[0-9]*?"><\/span><\/p>/', '$1', $content );
  return $content;
}
add_filter( 'the_content', 'ubik_strip_more_tag_orphan', 99 );

// Playing around with a function to strip paragraph tags off of images and such
function ubik_strip_media_p( $content ) {
  //$content = preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
  $content = preg_replace( '/<p>\s*(<iframe .*>*.<\/iframe>)\s*<\/p>/iU', '\1', $content );
  return $content;
}
add_filter( 'the_content', 'ubik_strip_media_p' );
