<?php // ==== MARKDOWN ==== //

// == CONFIGURATION == //

// Allow Markdown in <aside> elements; true/false
defined( 'UBIK_MARKDOWN_ASIDES' )           || define( 'UBIK_MARKDOWN_ASIDES', true );

// Convert Markdown in term descriptions when displayed on the front-end; true/false
defined( 'UBIK_MARKDOWN_TERM_DESCRIPTION' ) || define( 'UBIK_MARKDOWN_TERM_DESCRIPTION', false );


// == FUNCTIONS == //

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
if ( UBIK_MARKDOWN_ASIDES )
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



// A simple hack for using Markdown in term descriptions
// Requires: Jetpack or JP Markdown
// Warning 1: Markdown is left as raw text in the database; conversion only happens when `term_description` filter runs so you're effectively locked in once you start using this!
// Warning 2: this is also a major security risk on multi-user blogs where other people can edit term descriptions!
function ubik_markdown_term_description( $description ) {
  if ( class_exists( 'WPCom_Markdown' ) ) {
    $markdown = WPCom_Markdown::get_instance();
    $description = $markdown->transform( $description, array( 'unslash' => false ) );
  }
  return $description;
}
if ( UBIK_MARKDOWN_TERM_DESCRIPTION )
  add_filter( 'term_description', 'ubik_markdown_term_description' );
