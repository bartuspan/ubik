<?php // ==== ADMIN ==== //

// This file is only loaded if is_admin() returns true!

// == EDITOR == //

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



// Disable the visual editor
if ( UBIK_ADMIN_VISUAL_EDITOR === false )
  add_filter( 'user_can_richedit' , '__return_false', 50 );



// == POST LIST COLUMNS == //

// Featured image/post thumbnail column in post list; adapted from http://www.rektproductions.com/display-featured-images-in-admin-post-list/
function ubik_admin_posts_columns( $defaults ) {
  $defaults['featured_image'] = __( 'Thumbnails', 'ubik' );
  return $defaults;
}

function ubik_admin_posts_custom_columns( $column_name, $id ) {
  if ( $column_name === 'featured_image' )
    echo the_post_thumbnail( 'thumbnail' );
}
if ( UBIK_ADMIN_POST_LIST_THUMBS ) {
  add_filter( 'manage_posts_columns', 'ubik_admin_posts_columns', 5);
  add_action( 'manage_posts_custom_column', 'ubik_admin_posts_custom_columns', 5, 2);
}



// == POST LIST FILTERS == //

// Add a tags filter to post list; adapted from https://wordpress.stackexchange.com/questions/578/adding-a-taxonomy-filter-to-admin-list-for-a-custom-post-type
function ubik_admin_tag_filter() {
  global $typenow, $wp_query;

  if ( $typenow == 'post' ) {
    $taxonomy = 'post_tag';
    if ( isset( $wp_query->query['term'] ) ) {
      $term = $wp_query->query['term'];
    } else {
      $term = '';
    }
    $dropdown_options = array(
      'show_option_all'   => __( 'View all tags', 'ubik' ),
      'hide_empty'        => 1,
      'hierarchical'      => 0,
      'show_count'        => 0,
      'orderby'           => 'name',
      'name'              => 'tag',
      'taxonomy'          => $taxonomy,
      'selected'          => $term
    );
    wp_dropdown_categories( $dropdown_options );
  }

}
function ubik_admin_tag_convert_id_to_term( $query ) {
  global $pagenow;
  $qv = &$query->query_vars;
  if ( $pagenow == 'edit.php'
    && isset( $qv['tag'] )
    && is_numeric( $qv['tag'] )
  ) {
    $term = get_term_by( 'id', $qv['tag'], 'post_tag' );
    $qv['tag'] = $term->slug;
  }
}
if ( UBIK_ADMIN_TAG_FILTER ) {
  add_action( 'restrict_manage_posts', 'ubik_admin_tag_filter' );
  add_filter( 'parse_query', 'ubik_admin_tag_convert_id_to_term');
}



// Hide categories filter on uncategorized blogs
function ubik_admin_category_filter_hide() {
  ?><style type="text/css">
      select#cat { display: none; }
    }
  </style><?php
}
if ( !ubik_categorized_blog() )
  add_action( 'admin_head-edit.php', 'ubik_admin_category_filter_hide' );



// == USERS == //

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



// == SETTINGS == //

// Show all settings link
function all_settings_link() {
  add_options_page( __('All Settings'), __('All Settings'), 'administrator', 'options.php' );
}
if ( UBIK_ADMIN_ALL_SETTINGS )
  add_action('admin_menu', 'all_settings_link');

// Show all shortcodes link
if ( UBIK_ADMIN_ALL_SHORTCODES )
  include_once( 'admin-shortcodes.php' );
