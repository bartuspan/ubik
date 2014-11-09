<?php // ==== CHINESE ==== //

// Several simple functions for use with Chinese characters, pinyin, etc.

// Remove all characters that are not a separator, a-z, 0-9, or whitespace
function ubik_slug_strict( $title ) {

  // Only use this in the admin panel
  if ( is_admin() ) {

    // Lifted from http://wordpress.org/plugins/strings-sanitizer/
    $strict_title = preg_replace('![^'.preg_quote('-').'a-z0-_9\s]+!', '', strtolower( $title ) );

    // Only return the strict title if there is something left; passes Chinese characters when there's no Latin characters to fall back on
    if ( !empty( $strict_title ) )
      $title = $strict_title;
  }
  return $title;
}
add_filter( 'sanitize_title', 'ubik_slug_strict', 1 );



// A simple function for removing pinyin tone marks from a string
function ubik_unpinyin( $string ) {
  $string = str_replace( array( 'ā', 'á', 'ǎ', 'à' ), 'a', $string );
  $string = str_replace( array( 'ō', 'ó', 'ǒ', 'ò' ), 'o', $string );
  $string = str_replace( array( 'ē', 'é', 'ě', 'è' ), 'e', $string );
  $string = str_replace( array( 'ī', 'í', 'ǐ', 'ì' ), 'i', $string );
  $string = str_replace( array( 'ū', 'ú', 'ǔ', 'ù', 'ǖ', 'ǘ', 'ǚ', 'ǜ', 'ü' ), 'u', $string );
  $string = str_replace( array( 'Ā', 'Á', 'Ǎ', 'À' ), 'A', $string );
  $string = str_replace( array( 'Ō', 'Ó', 'Ǒ', 'Ò' ), 'O', $string );
  $string = str_replace( array( 'Ē', 'É', 'Ě', 'È' ), 'E', $string );
  return $string;
}
// Add filters as needed
add_filter( 'pendrell_entry_title', 'ubik_unpinyin' );
add_filter( 'ubik_content_title', 'ubik_unpinyin' );
add_filter( 'ubik_places_title', 'ubik_unpinyin' );
