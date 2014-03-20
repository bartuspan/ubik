<?php // === POST FORMAT REWRITE === //

// Source for this hack: http://justintadlock.com/archives/2012/09/11/custom-post-format-urls

// Rewrite post format base; be sure to update permalinks afterward
function ubik_post_format_rewrite_base( $slug ) {
  return UBIK_POST_FORMAT_REWRITE_BASE;
}
if ( UBIK_POST_FORMAT_REWRITE_BASE )
  add_filter( 'post_format_rewrite_base', 'ubik_post_format_rewrite_base' );
