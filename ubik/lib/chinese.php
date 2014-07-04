<?php // ==== CHINESE ==== //

// Several simple functions for use with Chinese characters and various Romanizations

// Remove all characters that are not the separator, a-z, 0-9, or whitespace; mainly for use with bilingual English/Chinese post titles
function ubik_slug_strict( $title ) {
  // Lifted from http://wordpress.org/plugins/strings-sanitizer/
  $strict_title = preg_replace('![^'.preg_quote('-').'a-z0-_9\s]+!', '', strtolower( $title ) );

  // Only return the strict title if there is something left
  if ( !empty( $strict_title ) ) {
    return $strict_title;
  } else {
    return $title;
  }
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
add_filter( 'pendrell_entry_title', 'ubik_unpinyin' );
add_filter( 'ubik_content_title', 'ubik_unpinyin' );
add_filter( 'ubik_places_title', 'ubik_unpinyin' );