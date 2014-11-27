<?php // ==== POPULAR TERMS ==== //

// Return terms ordered by count without singletons by default; @TODO: require PHP 5.3
if ( !function_exists( 'ubik_popular_terms' ) ) : function ubik_popular_terms( $id, $taxonomies = 'post_tag', $threshold = 1 ) {

  if ( empty( $id ) )
    return false;

  $terms = get_the_terms( $id, $taxonomies );

  if ( is_wp_error( $terms ) )
    return $terms;

  if ( empty( $terms ) )
    return false;

  // PHP 5.3 version commented out below...
  // Remove any terms with a count at or below $threshold
  //$terms = array_filter( $terms, function( $a ) use ( $threshold ) { return $a->count > $threshold; } );

  // Sort terms by count
  //usort( $terms, function( $a, $b ) { return $b->count - $a->count; } );

  // Remove any terms with a count below 2
  $terms_filtered = array_filter( $terms, "ubik_popular_terms_threshold" );

  // If we stripped away all terms abort and restore the list
  if ( empty( $terms_filtered ) )
    $terms_filtered = $terms;

  // Sort terms by count
  usort( $terms_filtered, "ubik_popular_terms_sort" );

  return $terms_filtered;
} endif;



// Only needed for backward compatibility with PHP 5.2; @TODO: upgrade to 5.3
if ( !function_exists( 'ubik_popular_terms_threshold' ) ) : function ubik_popular_terms_threshold( $a ) {
  $threshold = 1;
  return $a->count > $threshold; // @TODO: $threshold isn't user-configurable in PHP 5.2 and it's too much trouble to fix it :/
} endif;



// Sorting function to put more popular terms in front
if ( !function_exists( 'ubik_popular_terms_sort' ) ) : function ubik_popular_terms_sort( $a, $b ) {
  return $b->count - $a->count;
} endif;



// Adapted from the WordPress core; display a list of popular terms
// @filter: term_links-$taxonomy
if ( !function_exists( 'ubik_popular_terms_list' ) ) : function ubik_popular_terms_list( $id, $taxonomy, $before = '', $sep = '', $after = '', $threshold = 1 ) {

  // Get the popular terms
  $terms = ubik_popular_terms( $id, $taxonomy, $threshold );

  if ( is_wp_error( $terms ) )
    return $terms;

  if ( empty( $terms ) )
    return false;

  // Cycle through the terms and create HTML links
  foreach ( $terms as $term ) {
    $link = get_term_link( $term, $taxonomy );
    if ( is_wp_error( $link ) )
      return $link;
    $term_links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';
  }

  $term_links = apply_filters( "term_links-$taxonomy", $term_links );

  return $before . join( $sep, $term_links ) . $after;
} endif;
