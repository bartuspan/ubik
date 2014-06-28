<?php // ==== ATTACHMENTS ==== //

// Turn off comments for all attachments; source: http://rayofsolaris.net/blog/2011/comments-on-attachments-in-wordpress
function ubik_attachment_comments( $open, $post_id ) {
  $post = get_post( $post_id );
  if ( empty( $post ) )
    return;
  if ( $post->post_type == 'attachment' )
    return false;
  return $open;
}
if ( UBIK_ATTACHMENT_COMMENTS === false )
  add_filter( 'comments_open', 'ubik_attachment_comments', 10 , 2 );



// Utility function to determine if a post is an image attachment
function ubik_is_image_attachment( $id = null ) {
  if ( empty( $id ) )
    get_the_ID();

  if ( !empty( $id ) ) {
    $post = get_post( $id );
    if ( empty( $post ) )
      return false;
    if ( $post->post_type == 'attachment' && wp_match_mime_types( 'image', $post->post_mime_type ) )
      return true;
  }
  return false;
}
