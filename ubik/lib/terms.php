<?php // ==== TERMS ==== //

// Terms, tags, categories, etc.

// == CATEGORIES == //

// Check whether a blog has more than one category; via _s: https://github.com/Automattic/_s/blob/master/inc/template-tags.php
function ubik_categorized_blog() {

  // Hard switch for the category test; only acts when false
  if ( UBIK_TERMS_UNCATEGORIZED_BLOG )
    return false;

  if ( false === ( $all_the_cool_cats = get_transient( '_ubik_categories' ) ) ) {
    // Create an array of all the categories that are attached to posts.
    $all_the_cool_cats = get_categories( array(
      'fields'     => 'ids',
      'hide_empty' => 1,
      'number'     => 2, // We only need to know if there is more than one category.
    ) );

    // Count the number of categories that are attached to the posts.
    $all_the_cool_cats = count( $all_the_cool_cats );

    set_transient( '_ubik_categories', $all_the_cool_cats );
  }

  if ( $all_the_cool_cats > 1 ) {
    return true;
  } else {
    return false;
  }
}

// Flush out the transients used in ubik_categorized_blog
function ubik_category_transient_flusher() {
  delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'ubik_category_transient_flusher' );
add_action( 'save_post',     'ubik_category_transient_flusher' );
