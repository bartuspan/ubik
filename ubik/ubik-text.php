<?php // ==== TEXT ==== //

// A small common library of functions for manipulating text
// Currently used by Ubik Excerpt and Ubik SEO

// Truncate text; a replacement for the native `wp_trim_words` function
// @filter: ubik_text_truncate
// @filter: ubik_text_truncate_length
// @filter: ubik_text_truncate_ending
if ( !function_exists( 'ubik_text_truncate' ) ) : function ubik_text_truncate( $text = '', $words = 55, $ending = '...', $strip = '' ) {

  // Exit early
  if ( empty( $text ) )
    return;

  // Filter the number of words returned
  $words = (int) apply_filters( 'ubik_text_truncate_length', $words );

  // Filter the ending
  $ending = (string) apply_filters( 'ubik_text_truncate_ending', $ending );

  // Check the $strip array
  if ( empty( $strip ) || !is_array( $strip ) )
    $strip = array( 'asides', 'code', 'tags' ); // Note: 'shortcodes' *not* included by default

  // Shortcode handler; this one goes first as shortcodes may introduce HTML and other stuff that we may want to strip later
  if ( in_array( 'shortcodes', $strip ) ) {
    $text = strip_shortcodes( $text );
  } else {
    $text = do_shortcode( $text );
  }

  // Strip opening asides
  if ( in_array( 'asides', $strip ) )
    $text = ubik_text_strip_asides( $text );

  // Strip code wrapped in `pre` and `code` elements
  if ( in_array( 'code', $strip ) )
    $text = ubik_text_strip_code( $text );

  // Strip all tags
  if ( in_array( 'tags', $strip ) )
    $text = wp_strip_all_tags( $text );

  // Strip any remaining tags
  $text = str_replace( ']]>', ']]&gt;', $text );

  // Test whether the string is UTF-8 encoded
  if ( seems_utf8( $text ) )
    $text = $text; // @TODO: additional multibyte functionality?

  // A modification of the core `wp_trim_words` function
  if ( 'characters' == _x( 'words', 'word count: words or characters?' ) && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {

    // This first chunk handles character-based languages (e.g. Chinese)
    $text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
    preg_match_all( '/./u', $text, $words_array );
    $words_array = array_slice( $words_array[0], 0, $words + 1 );
    $sep = '';

  } else {

    // This handles non-character based input
    $words_array = preg_split( "/[\n\r\t ]+/", $text, $words + 1, PREG_SPLIT_NO_EMPTY );
    $sep = ' ';
  }

  // Trim the array to the desired word count
  if ( count( $words_array ) > $words )
    array_pop( $words_array );

  // Make a string from the array of words
  $text = implode( $sep, $words_array );

  //$text = 'target word count: ' . $words . '. actual: ' . str_word_count( $text ) . '. :::' . $text;

  // Strip out trailing punctuation and add the excerpt ending
  if ( str_word_count( $text ) > $words - 1 ) { // Fudge factor since str_word_count() may return something other than what we have
    if ( !preg_match( '/[.!?]$/u', $text ) ) { // Could also try \p{P} for punctuation; @TODO: i18n
      $text = preg_replace('/^[\p{P}|\p{S}|\s]+|[\p{P}|\p{S}|\s]+$/', '', $text ) . $ending;
    }
  } else {
    if ( !preg_match( '/[.!?]$/u', $text ) ) {
      $text = preg_replace('/^[\p{P}|\p{S}|\s]+|[\p{P}|\p{S}|\s]+$/', '', $text ) . _x( '.', 'sentence ending for truncated text below the word count', 'ubik' ); // A simple period
    }
  }

  return apply_filters( 'ubik_text_truncate', $text );

} endif;



// Strip opening `aside` elements from a string
// Use case: allows the use of `<aside>This post is a continuation of...</aside>` without this throw-away text dominating meta descriptions
if ( !function_exists( 'ubik_text_strip_asides' ) ) : function ubik_text_strip_asides( $text ) {
  if ( strpos( $text, '<aside' ) < 10 ) // Anywhere in the first 10 characters
    $text = preg_replace( '/<aside>(.*?)<\/aside>/si', '', $text, 1 );
  return $text;
} endif;



// Strip code blocks wrapped in `pre` and `code` elements
if ( !function_exists( 'ubik_text_strip_code' ) ) : function ubik_text_strip_code( $text ) {
  $text = preg_replace( '/<pre><code(.*?)<\/code><\/pre>/si', '', $text ); // Handles `<pre><code class="language-` style of markup via Markdown
  return $text;
} endif;
