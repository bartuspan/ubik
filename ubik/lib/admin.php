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

// Disable the visual editor altogether
if ( UBIK_ADMIN_VISUAL_EDITOR === false )
  add_filter( 'user_can_richedit' , '__return_false', 50 );



// == POST LIST COLUMNS == //

// Featured image/post thumbnail column in post list; adapted from http://www.rektproductions.com/display-featured-images-in-admin-post-list/
function ubik_admin_posts_columns( $defaults ) {
  $defaults['featured_image'] = __( 'Thumb', 'ubik' );
  return $defaults;
}
function ubik_admin_posts_custom_columns( $column_name, $id ) {
  if ( $column_name === 'featured_image' )
    echo the_post_thumbnail( array( 60, 60 ) );
}
function ubik_admin_posts_columns_style() { // This is a bit of a cheap hack but we're not too concerned about back-end optimization
  ?><style type="text/css">
    .column-featured_image {
      width: 60px;
    }
    td.column-featured_image {
      text-align: center;
    }
  </style><?php
}
if ( UBIK_ADMIN_POST_LIST_THUMB ) {
  add_filter( 'manage_posts_columns', 'ubik_admin_posts_columns', 5);
  add_action( 'manage_posts_custom_column', 'ubik_admin_posts_custom_columns', 5, 2);
  add_action( 'admin_head-edit.php', 'ubik_admin_posts_columns_style' );
}



// == POST LIST FILTERS == //

// Add a tags filter to post list; adapted from https://wordpress.stackexchange.com/questions/578/adding-a-taxonomy-filter-to-admin-list-for-a-custom-post-type
function ubik_admin_tag_filter() {
  global $typenow;

  if ( $typenow == 'post' ) {
    $taxonomy = 'post_tag';
    $term = get_query_var( 'tag_id' );
    wp_dropdown_categories( array(
      'show_option_all'   => __( 'View all tags', 'ubik' ),
      'hide_empty'        => 1,
      'hierarchical'      => 0,
      'show_count'        => 0,
      'orderby'           => 'name',
      'name'              => 'tag_id',
      'taxonomy'          => $taxonomy,
      'selected'          => $term
    ) );
  }
}

// This is a workaround for a known WordPress issue: https://core.trac.wordpress.org/ticket/13258
function ubik_admin_tag_filter_query_vars( $vars ) {
  if ( is_admin() )
    $vars[] = "tag_id";
  return $vars;
}
if ( UBIK_ADMIN_TAG_FILTER ) {
  add_filter( 'query_vars', 'ubik_admin_tag_filter_query_vars' );
  add_action( 'restrict_manage_posts', 'ubik_admin_tag_filter' );
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



// == TERMS == //

// Only show sanitized term descriptions in the admin; removes all HTML formatting, leaving just text
function ubik_term_description_admin_clean( $description ) {
  if ( is_admin() )
    $description = strip_tags( $description );
  return $description;
}
add_filter( 'term_description', 'ubik_term_description_admin_clean', 99 );



// Hack: re-arrange the edit-tags.php file; makes working with terms a little easier but there are better ways of doing this
function ubik_term_edit_style() {
  ?><style type="text/css">
    #col-left {
      float: right;
      padding-left: 2%;
      width: 30%;
    }
    #col-right {
      float: left;
      width: 68%;
    }
    .col-wrap {
      padding: 0;
    }
    /* Hide popular tags and add term help; this stuff is superfluous if you work with terms a lot */
    .tagcloud,
    #addtag .form-field p {
      display: none;
    }
  </style><?php
}
if ( UBIK_ADMIN_TERM_EDIT_STYLE )
  add_action( 'admin_head-edit-tags.php', 'ubik_term_edit_style' );



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



// == MODULES == //

// Add term descriptions to the quick edit box
if ( UBIK_ADMIN_TERM_DESC_QUICK )
  require_once( 'admin-quick-term-descriptions.php' );

// Show all shortcodes link
if ( UBIK_ADMIN_ALL_SHORTCODES )
  require_once( 'admin-shortcodes.php' );
