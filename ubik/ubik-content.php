<?php // ==== CONTENT ==== //

// - Title
// - Date
// - Entry meta
// - Content filters





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
  if ( is_categorized_blog() )
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
if ( UBIK_CONTENT_STRIP_MORE_ORPHAN )
  add_filter( 'the_content', 'ubik_strip_more_tag_orphan', 99 );

// Playing around with a function to strip paragraph tags off of images and such
function ubik_strip_media_p( $content ) {
  //$content = preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
  $content = preg_replace( '/<p>\s*(<iframe .*>*.<\/iframe>)\s*<\/p>/iU', '\1', $content );
  return $content;
}
if ( UBIK_CONTENT_STRIP_MEDIA_P )
  add_filter( 'the_content', 'ubik_strip_media_p' );
