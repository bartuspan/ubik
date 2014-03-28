<?php // ==== POST FORMAT ==== //

// Source for this hack: http://justintadlock.com/archives/2012/09/11/custom-post-format-urls

// Rewrite post format base; be sure to update permalinks afterward
function ubik_format_rewrite_base( $slug ) {
  return UBIK_FORMAT_REWRITE;
}
if ( UBIK_FORMAT_REWRITE )
  add_filter( 'post_format_rewrite_base', 'ubik_format_rewrite_base' );



// Array containing desired post format slugs
function ubik_get_post_format_slugs() {
  $slugs = array(
    'aside'   => 'aside',
    'audio'   => 'audio',
    'chat'    => 'chat',
    'gallery' => 'gallery',
    'image'   => 'image',
    'link'    => 'link',
    'quote'   => 'quotation',
    'status'  => 'status',
    'video'   => 'video'
  );
  return $slugs;
}



// Filters post format links to use a custom slug.
function ubik_post_format_link( $link, $term, $taxonomy ) {
  global $wp_rewrite;

  if ( 'post_format' != $taxonomy )
    return $link;

  $slugs = ubik_get_post_format_slugs();

  $slug = str_replace( 'post-format-', '', $term->slug );
  $slug = isset( $slugs[ $slug ] ) ? $slugs[ $slug ] : $slug;

  if ( $wp_rewrite->get_extra_permastruct( $taxonomy ) )
    $link = str_replace( "/{$term->slug}", '/' . $slug, $link );
  else
    $link = add_query_arg( 'post_format', $slug, remove_query_arg( 'post_format', $link ) );

  return $link;
}



// Changes the queried post format slug to the slug saved in the database
function ubik_post_format_request( $qvs ) {

  if ( !isset( $qvs['post_format'] ) )
    return $qvs;

  $slugs = array_flip( ubik_get_post_format_slugs() );

  if ( isset( $slugs[ $qvs['post_format'] ] ) )
    $qvs['post_format'] = 'post-format-' . $slugs[ $qvs['post_format'] ];

  $tax = get_taxonomy( 'post_format' );

  if ( !is_admin() )
    $qvs['post_type'] = $tax->object_type;

  return $qvs;
}



// Filter the entry meta type; swaps "Quote" with "Quotation"
function ubik_post_format_entry_meta( $post_format ) {
  if ( $post_format ) {
    if ( $post_format === 'Quote' || $post_format === 'quotation' ) {
      $post_format = __( 'Quotation', 'ubik' );
    }
  }
  return $post_format;
}



// Main switch for slug functionality
if ( UBIK_FORMAT_SLUG ) {
  // Remove core WordPress filter and add custom filter
  remove_filter( 'term_link', '_post_format_link', 10 );
  add_filter( 'term_link', 'ubik_post_format_link', 10, 3 );
  remove_filter( 'request', '_post_format_request' );
  add_filter( 'request', 'ubik_post_format_request' );

  // Hook our custom entry meta function to change the display name
  add_filter( 'ubik_content_meta_format', 'ubik_post_format_entry_meta' );
}
