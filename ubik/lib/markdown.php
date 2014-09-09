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



// Make footnote links absolute; a hack to avoid problems with the use of Markdown Extra footnotes and the <!--more--> tag
function ubik_markdown_more_footnotes( $content ) {
  if ( !is_singular() ) {
    if ( strpos( $content, 'class="more-link' ) && strpos( $content, 'href="#fn' ) ) {
      global $post;
      $content = str_replace( 'href="#fn', 'href="' . get_permalink() . '#fn', $content );
    }
  }
  return $content;
}
add_filter( 'the_content', 'ubik_markdown_more_footnotes' );
