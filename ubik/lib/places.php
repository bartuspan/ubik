<?php // ==== PLACES ==== //

// Quick and dirty places taxonomy
function ubik_places_init() {

  // Places taxonomy
  register_taxonomy( 'places', 'post', array(
    'labels' => array(
      'name' => _x( 'Places', 'taxonomy general name' ),
      'singular_name' => _x( 'Places', 'taxonomy singular name' ),
      'menu_name' => __( 'Places' ),
      'all_items' => __( 'All places' ),
      'edit_item' => __( 'Edit place' ),
      'view_item' => __( 'View place' ),
      'update_item' => __( 'Update places' ),
      'add_new_item' => __( 'Add new place' ),
      'new_item_name' => __( 'New place name' ),
      'parent_item' => __( 'Parent place' ),
      'parent_item_colon' => __( 'Parent place:' ),
      'search_items' =>  __( 'Search places' ),
    ),
    'show_admin_column' => true,
    'hierarchical' => true,
    'rewrite' => array(
      'slug' => 'places',
      'with_front' => true,
      'hierarchical' => true
    )
  ));

  // Places shortcode
  add_shortcode('place', 'ubik_places_shortcode');
}
add_action( 'init', 'ubik_places_init' );



// == SIDEBAR == //

// Don't display regular sidebar on portfolio items
function ubik_places_sidebar( $sidebar ) {
  if ( is_tax( 'places' ) ) {
    ubik_places_widget();
    $sidebar = false;
  }
  return $sidebar;
}
add_filter( 'pendrell_sidebar', 'ubik_places_sidebar' );



// Places widget; this isn't a true widget... but it's also not 200+ lines of code I don't need
function ubik_places_widget( $depth = 2, $term = null ) {

  $tax = get_query_var( 'taxonomy' );

  // Allows us to pass an explicit term and achieve the same functionality
  if ( empty( $term ) || $term == '' )
    $term = get_term_by( 'slug', get_query_var( 'term' ), $tax );

  // Try to find ancestors of the current place
  $ancestors = get_ancestors( $term->term_id, $tax );

  // Did we find any?
  if ( $ancestors ) {

    // What we're looking for is the last element of the array; pop it off and get its info
    $ancestor = get_term( array_pop( $ancestors ), $tax );
    $title = sprintf( __( 'Places in %s', 'ubik' ), '<a href="' . get_term_link( $ancestor->term_id, $tax ) . '">' . $ancestor->name . '</a>' );
    $child_of = $ancestor->term_id;
    $show_count = 0;

  } else {

    // Master list of top-level places
    $title = __( 'Top-level places', 'ubik' );
    $child_of = 0;
    $depth = 1;
    $show_count = 1;
  }

?><div id="secondary" class="widget-area" role="complementary">
    <aside id="places" class="widget widget_places">
      <h3 class="widget-title"><?php echo $title; ?></a></h3>
      <ul class="place-list"><?php
        wp_list_categories(
          array(
            'child_of'      => $ancestor->term_id,
            'depth'         => $depth,
            'show_count'    => $show_count,
            'taxonomy'      => $tax,
            'title_li'      => '',
          )
        );
      ?></ul>
    </aside>
  </div><!-- #secondary --><?php
}



// == SHORTCODE == //

// Places shortcode; simply wrap places in [place]Place name[/place]; alternately [place slug="place_slug"]Place name[/place]
function ubik_places_shortcode( $atts, $content = null ) {

  // Extract attributes
  extract( shortcode_atts( array(
    'slug' => ''
  ), $atts ) );

  // Guess the slug if we have content but no slug attribute
  if ( empty( $slug ) && !empty( $content ) ) {
    $slug_query = sanitize_title( $content );
  // Keep the slug if we have one
  } elseif ( !empty( $slug ) ) {
    $slug_query = $slug;
  }

  // Don't bother if there's no slug
  if ( $slug_query ) {

    // Now test to see if the term exists in the places taxonomy
    $term = get_term_by( 'slug', $slug_query, 'places' );

    if ( !empty( $term ) ) {

      if ( empty( $slug ) || empty( $content ) ) {
        $title = $term->name;
      } else {
        $title = $content;
      }

      $content = sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
        get_term_link( $term->term_id, 'places' ),
        esc_attr( sprintf( __( 'Permalink to %s', 'ubik' ), $term->name ) ),
        $title
      );
    }
  }

  // If the preceding loop didn't find a match we still return the original content as-is; graceful fallback
  return $content;
}



// == PLACES ARCHIVES == //

// All of these functions depend on Pendrell but they can easily be adapted for use with other WordPress themes

// Adds places to the Pendrell archive title system
function ubik_places_archive_title( $title ) {
  if ( is_tax( 'places' ) ) {
    $term = get_term_by( 'slug', get_query_var( 'term' ), 'places' );
    $title = sprintf( __( '%s archives', 'pendrell' ), '<span>' . $term->name . '</span>' );
  }
  return $title;
}
add_filter( 'pendrell_archive_title', 'ubik_places_archive_title' );



// A list of places; tries to list children, falls back to siblings if there are enough of them
function ubik_places_list( $term, $depth = 2 ) {

  $tax = get_query_var( 'taxonomy' );

  // Don't display on paged archives
  if ( is_archive() && is_paged() || ( $tax !== 'places' ) )
    return;

  // Allows us to pass an explicit term and achieve the same functionality
  if ( empty( $term ) || $term == '' )
    $term = get_term_by( 'slug', get_query_var( 'term' ), $tax );

  if ( $term ) {

    $parent = get_term( $term->parent, $tax );

    $children = get_term_children( $term->term_id, $tax );

    // We can't use get_term_children for siblings as we are only interested in direct descendents of the parent term
    $siblings = get_terms( $tax, array( 'parent' => $term->parent ) );

    // Show children
    if ( $children ) {

      // Attempt to put a damper on top-level places with many children
      if ( count( $children ) >= 25 )
        $depth = 1;

      ?><div class="archive-places-list">
        <h2><?php printf( __( 'Places in %s', 'ubik' ), $term->name ); ?></h2>
        <ul class="place-list"><?php wp_list_categories(
          array(
            'child_of'      => $term->term_id,
            'depth'         => $depth,
            'taxonomy'      => $tax,
            'title_li'      => '',
          )
        ); ?></ul>
      </div><?php

    // If there aren't any children perhaps siblings will be useful
    } elseif ( count( $siblings ) >= 2 ) {
      ?><div class="archive-places-list">
        <h2>Related places</h2>
        <ul class="place-list"><?php wp_list_categories(
          array(
            'child_of'      => $term->parent,
            'depth'         => 1,
            'taxonomy'      => $tax,
            'title_li'      => '',
            'exclude'       => $term->term_id
          )
        ); ?></ul>
      </div><?php
    }
  }
}
add_action( 'pendrell_archive_term_after', 'ubik_places_list', 10 );



// Breadcrumb navigation for places based on http://www.billerickson.net/wordpress-taxonomy-breadcrumbs/
function ubik_places_breadcrumb( $term ) {

  $tax = get_query_var( 'taxonomy' );

  if ( $tax !== 'places' )
    return;

  // Allows us to pass an explicit term and achieve the same functionality
  if ( empty( $term ) || $term == '' )
    $term = get_term_by( 'slug', get_query_var( 'term' ), $tax );

  // Create a list of all the term's parents
  $parent = $term->parent;

  if ( $parent ) {

    // Back things up a bit so that the current term is included
    $parent = $term->term_id;

    while ( $parent ) {
      $parents[] = $parent;
      $parent_parent = get_term_by( 'id', $parent, $tax );
      $parent = $parent_parent->parent; // Heh
    }

    if( !empty( $parents ) ) {
      $parents = array_reverse( $parents );

      // Wrap it up
      echo '<nav class="breadcrumbs">' . "\n" . '<ul>' . "\n";

      // For each parent, create a breadcrumb item
      foreach ( $parents as $parent ) {
        $item = get_term_by( 'id', $parent, $tax );
        $link = get_term_link( $parent, $tax );
        echo '<li><a href="' . $link . '">' . $item->name . '</a></li>' . "\n";
      }

      // Wrap it up
      echo '</ul>' . "\n" . '</nav>' . "\n";
    }
  }
}
add_action( 'pendrell_archive_term_before', 'ubik_places_breadcrumb', 10 );



// == PLACES ENTRY META == //

// Adds places to entry metadata right after other taxonomies
function ubik_places_entry_meta( $places ) {
  global $post;
  if ( has_term( '', 'places' ) )
    $places = 'Places: ' . get_the_term_list( $post->ID, 'places', '', ', ', '' );
  return $places;
}
add_filter( 'ubik_content_meta_taxonomies', 'ubik_places_entry_meta' );



// == PLACES ADMIN == //

// Removes description from places admin
function ubik_places_admin_columns( $theme_columns ) {
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'name' => __('Name'),
        'slug' => __('Slug'),
        'posts' => __('Posts')
        );
    return $new_columns;
}
if ( UBIK_ADMIN_TERM_EDITOR )
  add_filter( 'manage_edit-places_columns', 'ubik_places_admin_columns' );
