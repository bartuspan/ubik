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
if ( UBIK_ATTACHMENT_COMMENTS_OFF )
  add_filter( 'comments_open', 'ubik_attachment_comments', 10, 2 );
