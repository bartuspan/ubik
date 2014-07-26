<?php // ==== TERMS ==== //

// Terms, tags, categories, etc.

// == CATEGORIES == //

// Check whether a blog has more than one category; via _s: https://github.com/Automattic/_s/blob/master/inc/template-tags.php
function ubik_categorized_blog() {
  if ( false === ( $all_the_cool_cats = get_transient( '_ubik_categories' ) ) ) {
    // Create an array of all the categories that are attached to posts.
    $all_the_cool_cats = get_categories( array(
      'fields'     => 'ids',
      'hide_empty' => 1,
      'number'     => 2, // We only need to know if there is more than one category.
    ) );

    // Count the number of categories that are attached to the posts.
    $all_the_cool_cats = count( $all_the_cool_cats );

    set_transient( '_ubik_categories', $all_the_cool_cats );
  }

  if ( $all_the_cool_cats > 1 ) {
    return true;
  } else {
    return false;
  }
}

// Flush out the transients used in ubik_categorized_blog
function ubik_category_transient_flusher() {
  delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'ubik_category_transient_flusher' );
add_action( 'save_post',     'ubik_category_transient_flusher' );



// == TAGS == //

// Nothing yet!



// == TERMS == //

// Return terms ordered by count without singletons by default; requires PHP 5.3
function ubik_get_the_popular_terms( $id, $taxonomies = 'post_tag', $threshold = 1 ) {

  if ( empty( $id ) )
    return false;

  $terms = get_the_terms( $id, $taxonomies );

  if ( is_wp_error( $terms ) )
    return $terms;

  if ( empty( $terms ) )
    return false;

  //if( version_compare( PHP_VERSION, '5.3.0' ) >= 0 ) {

    // Remove any terms with a count at or below $threshold
    //$terms = array_filter( $terms, function( $a ) use ( $threshold ) { return $a->count > $threshold; } );

    // Sort terms by count
    //usort( $terms, function( $a, $b ) { return $b->count - $a->count; } );

  //} else {

    // Remove any terms with a count below 2
    $terms = array_filter( $terms, "ubik_get_the_popular_terms_threshold" );

    // Sort terms by count
    usort( $terms, "ubik_get_the_popular_terms_sort" );

  //}

  return $terms;
}

// Only needed for backward compatibility with PHP 5.2
function ubik_get_the_popular_terms_threshold( $a ) {
  return $a->count > 1; // $threshold isn't user-configurable in PHP 5.2 and it's too much trouble to fix it :/
}
function ubik_get_the_popular_terms_sort( $a, $b ) {
  return $b->count - $a->count;
}



// Adapted from the WordPress core; display a list of popular terms
function ubik_get_the_popular_term_list( $id, $taxonomy, $before = '', $sep = '', $after = '', $threshold = 1 ) {
  $terms = ubik_get_the_popular_terms( $id, $taxonomy, $threshold );

  if ( is_wp_error( $terms ) )
    return $terms;

  if ( empty( $terms ) )
    return false;

  foreach ( $terms as $term ) {
    $link = get_term_link( $term, $taxonomy );
    if ( is_wp_error( $link ) )
      return $link;
    $term_links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';
  }

  $term_links = apply_filters( "term_links-$taxonomy", $term_links );

  return $before . join( $sep, $term_links ) . $after;
}
