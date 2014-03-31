<?php // ==== PORTFOLIO ==== //

// @TODO: this needs to be converted into a custom post type

function ubik_portfolio_init() {
  // Thumbnails on the portfolio screen
  add_image_size( 'portfolio-navigation', 300, 200, true );
}
add_action( 'init', 'ubik_portfolio_init' );

// Portfolio categories; add or remove any slug to this array to enable matching categories with portfolio functionality
$ubik_portfolio_cats = array( 'creative', 'design', 'photography', 'portfolio' );

// Body class filter
function ubik_portfolio_body_class( $classes ) {
  if ( ubik_is_portfolio() ) {
    $classes[] = 'portfolio';
  }
  return $classes;
}
add_filter( 'body_class', 'ubik_portfolio_body_class' );



// This lets Pendrell know to make portfolio items full-width
function ubik_portfolio_width() {
  if ( ubik_is_portfolio() ) {
    return true;
  } else {
    return false;
  }
}
add_filter( 'pendrell_full_width', 'ubik_portfolio_width' );



// Test to see whether we are viewing a portfolio post or category archive
function ubik_is_portfolio() {
  global $ubik_portfolio_cats;
  if (
    is_category( $ubik_portfolio_cats )
    || ( is_singular() && in_category( $ubik_portfolio_cats ) )
  ) {
    return true;
  } else {
    return false;
  }
}



// Modify how many posts per page are displayed in different contexts (e.g. more portfolio items on category archives)
function ubik_portfolio_pre_get_posts( $query ) {
  // Source: http://wordpress.stackexchange.com/questions/21/show-a-different-number-of-posts-per-page-depending-on-context-e-g-homepage
  if ( ubik_is_portfolio() && $query->is_main_query() ) {
    $query->set( 'posts_per_page', 24 );
  }
}
add_action( 'pre_get_posts', 'ubik_portfolio_pre_get_posts' );



// Don't display regular sidebar on portfolio items
function ubik_portfolio_sidebar( $sidebar ) {
  if ( ubik_is_portfolio() ) {
    // Do something here
    $sidebar = false;
  }
  return $sidebar;
}
add_filter( 'pendrell_sidebar', 'ubik_portfolio_sidebar' );
