<?php // ==== COMMENTS ==== //

// Modify what tags and attributes are allowed in comments; also changed the text output of allowed_tags(); doesn't affect administrators
function ubik_comments_allowed_tags() {
  global $allowedtags;
  $allowedtags = array(
    'a' => array(
      'href' => true,
    ),
    'b' => array(),
    'blockquote' => array(
      'cite' => true,
    ),
    'cite' => array(),
    'code' => array(),
    'em' => array(),
    'i' => array(),
    'q' => array(
      'cite' => true,
    ),
    'strong' => array(),
  );
}
if ( UBIK_COMMENTS_ALLOWED_TAGS )
  add_action( 'init', 'ubik_comments_allowed_tags', 11 );