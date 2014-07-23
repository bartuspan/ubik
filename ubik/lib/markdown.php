<?php // ==== MARKDOWN ==== //

// Check whether Markdown is enabled in comments (set by Jetpack and Jetpack Markdown plugins among others)
function ubik_comments_markdown_enabled() {
  return (bool) get_option( 'wpcom_publish_comments_with_markdown' );
}



// Add 'markdown="1"' to asides, automatically enabling Markdown Extra within aside tags
function ubik_markdown_asides( $content ) {
  //$content = preg_replace( '/<(aside|div)>/', '<$1 markdown="1">', $content ); // To extend this hack use preg_replace
  $content = str_replace( '<aside>', '<aside markdown="1">', $content );
  return $content;
}
add_filter( 'content_save_pre', 'ubik_markdown_asides' );
