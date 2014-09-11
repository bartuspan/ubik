<?php // ==== QUICK TERM DESCRIPTIONS ==== //

// Add a term description field to the quick edit box!
// This is not as easy as it looks since there's no hook for default term fields
// To get around this fact we create a hidden column and populate that with the raw description (to allow for HTML, Markdown, shortcodes, etc.)
// We then use jQuery to pull content into the quick edit description textarea
// After saving the description is immediately updated
// By default this applies to categories and tags; use the 'ubik_term_description_taxonomies' to add your own like so:

// function ubik_your_taxonomy_quick_edit( $taxonomies ) {
//   $taxonomies[] = 'your-taxonomy';
//   return $taxonomies;
// }
// add_filter( 'ubik_term_description_taxonomies', 'ubik_your_taxonomy_quick_edit' );

// This code is adapted from: https://wordpress.stackexchange.com/questions/139663/add-description-to-taxonomy-quick-edit
// Full credit goes to G.M. @ http://gm.zoomlab.it/
// I also worked from this (now obsolete) tutorial: http://code.tutsplus.com/articles/extending-the-quick-edit-tool-for-taxonomy-terms--wp-20495

// Edit term descriptions in quick edit mode
function ubik_quick_term_description_edit( $column, $screen, $taxonomy ) {
  if ( $screen !== 'edit-tags' ) return;
  $tax = get_taxonomy( $taxonomy );
  if ( ! current_user_can( $tax->cap->edit_terms ) ) return;
  if ( $column !== '_description' ) return;
?>
  <fieldset>
    <div class="inline-edit-col">
    <label>
      <span class="title"><?php _e( 'Description', 'ubik' ); ?></span>
      <span class="input-text-wrap">
      <textarea id="inline-desc" name="description" rows="5" class="ptitle"></textarea>
      </span>
    </label>
    </div>
  </fieldset>
  <script>
  jQuery('#the-list').on('click', 'a.editinline', function(){
    var now = jQuery(this).closest('tr').find('td.column-_description').text();
    jQuery('#inline-desc').text( now );
  });
  </script>
  <?php
}
add_action( 'quick_edit_custom_box', 'ubik_quick_term_description_edit', 10, 3 );

// Save the inline term description
function ubik_quick_term_description_save( $term_id ) {
  $tax = get_taxonomy( $_REQUEST['taxonomy'] );
  if (
    current_filter() === 'edited_' . $tax->name
    && current_user_can( $tax->cap->edit_terms )
  ) {
    $description = filter_input( INPUT_POST, 'description', FILTER_SANITIZE_STRING );
    remove_action( current_filter(), __FUNCTION__ ); // Removing action to avoid recursion
    wp_update_term( $term_id, $tax->name, array( 'description' => $description ) );
  }
}

// Hidden column hack to enable `quick_box_custom_column` action
function ubik_quick_term_hidden_column( $columns ) {
  $columns['_description'] = '';
  return $columns;
}

// Fill our hidden column with the raw term description; this will be pulled into the quick edit box with jQuery
function ubik_quick_term_hidden_column_contents( $_, $column_name, $term_id ) {
  if ( $column_name === '_description' ) {

    // Get current screen, if available
    $screen = get_current_screen();
    if ( !empty( $screen ) ) {
      // Set the taxonomy from the current screen
      $taxonomy = $screen->taxonomy;
    } else {
      // Set the taxonomy from the request (after saving the quick edit box `get_current_screen` is null)
      $taxonomy = sanitize_text_field( $_REQUEST['taxonomy'] );
    }

    // If we have something, let's roll
    if ( !empty( $term_id ) && !empty( $taxonomy ) )
      echo get_term( $term_id, $taxonomy )->description;
  }
}

// Hide the column it from view completely
function ubik_quick_term_hidden_column_visibility( $columns ) {
  $columns[] = '_description';
  return $columns;
}

// Pull it all together
function ubik_quick_term_init() {

  // Filter this to add or subtract taxonomies
  $ubik_term_description_taxonomies = apply_filters( 'ubik_term_description_taxonomies', array( 'category', 'post_tag' ) );

  // Setup all the necessary actions and filters
  foreach ( $ubik_term_description_taxonomies as $tax ) {
    add_action( "edited_{$tax}", 'ubik_quick_term_description_save' );
    add_filter( "manage_edit-{$tax}_columns", 'ubik_quick_term_hidden_column' );
    add_filter( "manage_{$tax}_custom_column", 'ubik_quick_term_hidden_column_contents', 10, 3 );
    add_filter( "get_user_option_manageedit-{$tax}columnshidden", 'ubik_quick_term_hidden_column_visibility' );
  }
}
add_action( 'init', 'ubik_quick_term_init' );
