<?php // ==== VARIOUS ==== //
// A collection of theme-agnostic snippets

// Removes the ".recentcomments" style added to the header for no good reason
// http://www.narga.net/how-to-remove-or-disable-comment-reply-js-and-recentcomments-from-wordpress-header
function ubik_remove_recent_comments_style() {
  global $wp_widget_factory;
  remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'ubik_remove_recent_comments_style' );



// Allow HTML in author descriptions on single user blogs
if ( !is_multi_author() ) {
  remove_filter( 'pre_user_description', 'wp_filter_kses' );
}



// Ditch the default gallery styling, yuck
add_filter( 'use_default_gallery_style', '__return_false' );



// Quick and dirty test to see if post is paginated; source: http://tommcfarlin.com/post-is-paginated/
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
