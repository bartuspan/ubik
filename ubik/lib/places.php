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
function ubik_places_widget( $depth = 3 ) {
?><div id="secondary" class="widget-area" role="complementary">
    <aside id="places" class="widget widget_places">
      <h3 class="widget-title">Places</h3>
      <ul class="place-list"><?php
        wp_list_categories(
          array(
            'depth'         => $depth,
            //'hide_empty'    => 0,
            'taxonomy'      => 'places',
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

    // Fetch posts tagged with the current place; only the slugs need to match
    $args = array(
      'name'        => $slug_query,
      'post_type'   => 'post',
      'post_status' => 'publish'
    );

    // Roll with what we've got
    $the_query = new WP_Query( $args );

    if ( $the_query->have_posts() ) {
      while ( $the_query->have_posts() ) : $the_query->the_post();

        // Use the proper title if no slug was passed (but content was) or vice versa
        // This is just a confusing way to allow for non-Latin characters in the actual posted title
        // That way we can go from [place]Taiwan[/place] to Taiwan (with Chinese characters from the original post)
        // This also allows for [place=taiwan] to work without any content passed
        if ( empty( $slug ) || empty( $content ) ) {
          $title = get_the_title();
        } else {
          $title = $content;
        }

        // Put it all together
        $content = sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
          get_permalink(),
          esc_attr( sprintf( __( 'Permalink to %s', 'ubik' ), the_title_attribute( 'echo=0' ) ) ),
          $title
        );

      endwhile;
    } else {
      // No posts found; try places taxonomy
      $term = get_term_by( 'slug', $slug_query, 'places' );
      if ( $term ) {

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
    // Restore original post data
    wp_reset_postdata();
  }

  // If the preceding loop didn't find a match we still return the original content as-is; graceful fallback
  return $content;
}



// == PLACES ARCHIVES == //

// Adds places to the archive title system
function ubik_places_archive_title( $title ) {
  if ( is_tax( 'places' ) ) {
    $term = get_term_by( 'slug', get_query_var( 'term' ), 'places' );
    //$title = sprintf( __( 'Place archives: %s', 'pendrell' ), '<span><a href="' . get_term_link( $term->term_id, 'places' ) . '" title="' . $term->name . '">' . $term->name . '</a></span>' );
    $title = sprintf( __( 'Posts placed in <mark>%s</mark>', 'pendrell' ), '<span>' . $term->name . '</span>' );
  }
  return $title;
}
add_filter( 'pendrell_archive_title', 'ubik_places_archive_title' );



// A list of places; tries to list children, falls back to siblings if there are enough of them
function ubik_places_list( $term, $depth = 2 ) {

  // Don't display on paged archives
  if ( is_archive() && is_paged() )
    return;

  // Allows us to pass an explicit term and achieve the same functionality
  if ( empty( $term ) || $term == '' )
    $term = get_term_by( 'slug', get_query_var( 'term' ), 'places' );

  if ( $term ) {

    $children = get_term_children( $term->term_id, 'places' );
    // We can't use get_term_children for siblings as we are only interested in direct descendents of the parent term
    $siblings = get_terms( 'places', array( 'parent' => $term->parent ) );

    // Show children
    if ( $children ) {
      ?><div class="archive-places-list">
        <h2><?php printf( __( 'Places in %s:', 'ubik' ), $term->name ); ?></h2>
        <ul class="place-list"><?php wp_list_categories(
          array(
            'child_of'      => $term->term_id,
            'depth'         => $depth,
            'taxonomy'      => 'places',
            'title_li'      => '',
          )
        ); ?></ul>
      </div><?php

    // If there aren't any children perhaps siblings will be useful
    } elseif ( count( $siblings ) >= 2 ) {
      ?><div class="archive-places-list">
        <h2><?php printf( 'Places near %s:', $term->name ); ?></h2>
        <ul class="place-list"><?php wp_list_categories(
          array(
            'child_of'      => $term->parent,
            'depth'         => 1,
            'taxonomy'      => 'places',
            'title_li'      => '',
            'exclude'       => $term->term_id
          )
        ); ?></ul>
      </div><?php
    }
  }
}
add_action( 'pendrell_archive_description_after', 'ubik_places_list', 7 );



// == PLACES ENTRY META == //

// @TODO: figure out a better solution than jamming this into the "parent" part of the entry meta
function ubik_places_entry_meta( $places ) {
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
