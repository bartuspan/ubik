<?php // ==== PLACES ==== //

// Quick and dirty places taxonomy
function ubik_places_init() {

  // Places taxonomy
  register_taxonomy( 'places', 'post', array(
    'labels' => array(
      'name' => _x( 'Places', 'taxonomy general name' ),
      'singular_name'     => _x( 'Places', 'taxonomy singular name' ),
      'menu_name'         => __( 'Places' ),
      'all_items'         => __( 'All places' ),
      'edit_item'         => __( 'Edit place' ),
      'view_item'         => __( 'View place' ),
      'update_item'       => __( 'Update places' ),
      'add_new_item'      => __( 'Add new place' ),
      'new_item_name'     => __( 'New place name' ),
      'parent_item'       => __( 'Parent place' ),
      'parent_item_colon' => __( 'Parent place:' ),
      'search_items'      => __( 'Search places' ),
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
  add_shortcode( 'place', 'ubik_places_shortcode' );
}
add_action( 'init', 'ubik_places_init' );



// == SIDEBAR == //

// Don't display regular sidebar on portfolio items; assumes Pendrell is in use, otherwise adapt this function to suit your theme
function ubik_places_sidebar( $sidebar ) {
  if ( is_tax( 'places' ) && !pendrell_is_full_width() ) {
    ubik_places_widget();
    $sidebar = false;
  }
  return $sidebar;
}
add_filter( 'pendrell_sidebar', 'ubik_places_sidebar' );



// Places widget; this isn't a true widget... but it's also not 200+ lines of code I don't need
function ubik_places_widget( $term = null ) {

  $tax = 'places';

  // Allows us to pass an explicit term and achieve the same functionality
  if ( empty( $term ) || $term == '' )
    $term = get_term_by( 'slug', get_query_var( 'term' ), $tax );

  // Check again
  if ( !empty( $term ) ) {

    $places = array();

    // Get direct descendents of the current term
    $children = get_term_children( $term->term_id, $tax );

    // Get direct descendents of the parent of the current term; get_term_children is not appropriate here
    $siblings = get_terms( $tax, array( 'parent' => $term->parent ) );

    // Get ancestors of the current term
    $ancestors = get_ancestors( $term->term_id, $tax );

    // Get the highest ancestor of the current term
    if ( !empty( $ancestors ) ) {
      $patriarch = get_term( end( $ancestors ), $tax );
    } else {
      $patriarch = $term;
    }

    // Unite the whole family (the current term plus all ancestors)
    $family = $ancestors;
    $family[] = $term->term_id;

    // Setup children query
    if ( !empty( $children ) ) {

      // Attempt to limit terms with an abundance of children; this is pure guess work
      if ( count( $children ) >= 25 && !empty( $ancestors) ) {
        $depth = 1;
      } else {
        $depth = 2;
      }

      $places[] = array(
        'title' => sprintf( __( 'Places in %s', 'ubik' ), apply_filters( 'ubik_places_title', $term->name ) ),
        'args' => array(
          'child_of'            => $term->term_id,
          'depth'               => $depth,
          'show_count'          => 1,
          'hide_empty'          => 0,
          'taxonomy'            => $tax,
          'title_li'            => '',
          'show_option_none'    => __( 'No places found', 'ubik' ),
          'echo'                => 0
        )
      );

    // If there are no childen at least show the parent tree; no different than breadcrumbs, really
    } elseif ( !empty( $ancestors ) ) {

      $places[] = array(
        'title' => sprintf( __( '%s in context', 'ubik' ), apply_filters( 'ubik_places_title', $term->name ) ),
        'args' => array(
          'depth'               => 0,
          'taxonomy'            => $tax,
          'include'             => $family,
          'title_li'            => '',
          'show_option_none'    => __( 'No places found', 'ubik' ),
          'echo'                => 0
        )
      );

    }

    // Setup sibling query; conditions: more than 2 siblings and not a top-level place
    if ( !empty( $siblings ) && count( $siblings ) >= 2 && !empty( $ancestors ) ) {

      $places[] = array(
        'title' => __( 'Related places', 'ubik' ),
        'args' => array(
          'child_of'            => $term->parent,
          'depth'               => 1,
          'taxonomy'            => $tax,
          'exclude'             => $term->term_id,
          'title_li'            => '',
          'show_option_none'    => __( 'No places found', 'ubik' ),
          'echo'                => 0
        )
      );

    }

    // Places index
    $places[] = array(
      'title' => __( 'Places index', 'ubik' ),
      'args' => array(
        'child_of'            => 0,
        'depth'               => 1,
        'show_count'          => 1,
        'taxonomy'            => $tax,
        'title_li'            => '',
        'show_option_none'    => __( 'No places found', 'ubik' ),
        'echo'                => 0
      )
    );

  }

  // Only output places widget markup if we have results
  if ( !empty( $places ) ) {

    ?><div id="secondary" class="widget-area" role="complementary">
      <aside id="places" class="widget widget_places">
        <?php if ( !empty( $places ) ) {
          foreach ( $places as $place ) {
            ?><h3 class="widget-title"><?php echo $place['title']; ?></a></h3>
            <ul class="place-list"><?php echo wp_list_categories( $place['args'] ); ?></ul><?php
          }
        } ?>
      </aside>
    </div><!-- #secondary --><?php

  }
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

      $content = sprintf( '<a href="%1$s">%2$s</a>',
        get_term_link( $term->term_id, 'places' ),
        $title
      );
    }
  }

  // If the preceding loop didn't find a match we still return the original content as-is; graceful fallback
  return $content;
}



// == PLACES ARCHIVES == //

// These functions depend on Pendrell but they can easily be adapted for use with other WordPress themes

// Adds places to the Pendrell archive title system
function ubik_places_archive_title( $title ) {
  if ( is_tax( 'places' ) ) {
    $term = get_term_by( 'slug', get_query_var( 'term' ), 'places' );
    $title = sprintf( __( '%s archives', 'pendrell' ), '<span>' . apply_filters( 'ubik_places_title', $term->name ) . '</span>' );
  }
  return $title;
}
add_filter( 'pendrell_archive_title', 'ubik_places_archive_title' );



// Breadcrumb navigation for places based on http://www.billerickson.net/wordpress-taxonomy-breadcrumbs/
function ubik_places_breadcrumb( $content, $term = '' ) {

  $tax = get_query_var( 'taxonomy' );

  if ( $tax !== 'places' )
    return $content;

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
      $content .= "\n" . '<nav class="breadcrumbs">' . "\n" . '<ul>' . "\n";

      // For each parent, create a breadcrumb item
      foreach ( $parents as $parent ) {
        $item = get_term_by( 'id', $parent, $tax );
        $link = get_term_link( $parent, $tax );
        $content .= '<li><a href="' . $link . '">' . $item->name . '</a></li>' . "\n";
      }

      // Wrap it up
      $content .= '</ul>' . "\n" . '</nav>' . "\n";
    }
  }
  return $content;
}
add_filter( 'pendrell_archive_term_before', 'ubik_places_breadcrumb' );



// == PLACES ENTRY META == //

// Adds places to entry metadata right after other taxonomies
function ubik_places_entry_meta( $meta ) {
  global $post;
  if ( has_term( '', 'places' ) )
    $meta .= 'Places: ' . get_the_term_list( $post->ID, 'places', '', ', ', '. ' );
  return $meta;
}
add_filter( 'ubik_entry_meta_taxonomies', 'ubik_places_entry_meta' );



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
