<?php // ==== VARIOUS ==== //
// A collection of theme-agnostic snippets

// Allow HTML in author descriptions on single user blogs
// Careful: might be stripped out anyway (e.g. when making meta descriptions) so don't put anything essential in there
if ( !is_multi_author() ) {
  remove_filter( 'pre_user_description', 'wp_filter_kses' );
}



// Quick and dirty test to see if post is paginated; source: http://tommcfarlin.com/post-is-paginated/
// Possibly not used for anything
function ubik_is_post_paginated() {
  global $multipage;
  return 0 !== $multipage;
}



// Sub-optimal hack to deal with Jetpack Markdown failing to decode single quote HTML entities; should be removed when the issue is fixed
function ubik_markdown_codeblock_fix( $content ) {
  return preg_replace_callback( "/^(`{3})([^`\n]+)?\n([^`~]+)(`{3})/m", 'ubik_markdown_codeblock_preserve', $content );
}
function ubik_markdown_codeblock_replace( $content ) {
  return str_replace( '&amp;#039;', '\'', $content);
}
function ubik_markdown_codeblock_preserve( $matches ) {
  $block = html_entity_decode( $matches[3], ENT_QUOTES );
  $open = $matches[1] . $matches[2] . "\n";
  return $open . $block . $matches[4];
}
add_filter( 'edit_post_content', 'ubik_markdown_codeblock_fix', 11, 2 );
add_filter( 'wpcom_markdown_transform_post', 'ubik_markdown_codeblock_replace');
