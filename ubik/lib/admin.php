<?php // === ADMIN === //

// HTML editor fontstack and fontsize hack; source: http://justintadlock.com/archives/2011/07/06/fixing-wordpress-3-2s-html-editor-font
function ubik_html_editor_fontstack() {
  ?><style type="text/css">
    #wp-content-editor-container textarea#content,
    textarea#wp_mce_fullscreen {
      font-size: <?php echo UBIK_ADMIN_EDITOR_FONT_SIZE; ?>;
      font-family: <?php echo UBIK_ADMIN_EDITOR_FONT_STACK; ?>;
    }
  </style><?php
}
if ( UBIK_ADMIN_EDITOR_FONT_SIZE || UBIK_ADMIN_EDITOR_FONT_STACK ) {
  add_action( 'admin_head-post.php', 'ubik_html_editor_fontstack' );
  add_action( 'admin_head-post-new.php', 'ubik_html_editor_fontstack' );
}



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
if ( UBIK_CONTENT_SLUG_STRICT )
  add_filter( 'sanitize_title', 'ubik_slug_strict', 1 );



// Change user contact methods
function ubik_contact_methods( $contact ) {

  // Add useful contact methods
  if ( !isset( $contact['facebook'] ) )
    $contact['facebook'] = 'Facebook';

  if ( !isset( $contact['flickr'] ) )
    $contact['flickr'] = 'Flickr';

  if ( !isset( $contact['github'] ) )
    $contact['github'] = 'GitHub';

  if ( !isset( $contact['google'] ) )
    $contact['google'] = 'Google+';

  if ( !isset( $contact['instagram'] ) )
    $contact['instagram'] = 'Instagram';

  if ( !isset( $contact['twitter'] ) )
    $contact['twitter'] = 'Twitter';

  // Remove cruft
  if ( isset( $contact['aim'] ) )
    unset($contact['aim']);

  if ( isset( $contact['jabber'] ) )
    unset($contact['jabber']);

  if ( isset( $contact['yim'] ) )
    unset($contact['yim']);

  return $contact;
}
if ( UBIK_ADMIN_CONTACT_METHODS )
  add_filter('user_contactmethods', 'ubik_contact_methods');



// Show all settings link
function all_settings_link() {
  add_options_page( __('All Settings'), __('All Settings'), 'administrator', 'options.php' );
}
if ( UBIK_ADMIN_ALL_SETTINGS )
  add_action('admin_menu', 'all_settings_link');
