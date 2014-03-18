<?php // === POST FORMAT REWRITE === //
// Source for this hack: http://justintadlock.com/archives/2012/09/11/custom-post-format-urls

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



/**
 * Filters post format links to use a custom slug.
 *
 * @param string $link The permalink to the post format archive.
 * @param object $term The term object.
 * @param string $taxnomy The taxonomy name.
 * @return string
 */
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

// Remove core WordPress filter and add custom filter
remove_filter( 'term_link', '_post_format_link', 10 );
add_filter( 'term_link', 'ubik_post_format_link', 10, 3 );



/**
 * Changes the queried post format slug to the slug saved in the database.
 *
 * @param array $qvs The queried variables.
 * @return array
 */
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

// Remove core WordPress filter and add custom filter
remove_filter( 'request', '_post_format_request' );
add_filter( 'request', 'ubik_post_format_request' );



// Rewrite post format base; be sure to update permalinks afterward
function ubik_post_format_rewrite_base( $slug ) {
  return UBIK_POST_FORMAT_REWRITE_BASE;
}
if ( UBIK_POST_FORMAT_REWRITE_BASE )
  add_filter( 'post_format_rewrite_base', 'ubik_post_format_rewrite_base' );
