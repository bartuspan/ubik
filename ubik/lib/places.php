<?php // === PLACES === //

// Quick and dirty places custom post type
function ubik_places_init() {
  $labels = array(
    'name' => _x( 'Places', 'ubik' ),
    'singular_name' => _x( 'Place', 'ubik' ),
    'add_new' => _x( 'Add New', 'ubik' ),
    'add_new_item' => _x( 'Add New Place', 'ubik' ),
    'edit_item' => _x( 'Edit Place', 'ubik' ),
    'new_item' => _x( 'New Place', 'ubik' ),
    'view_item' => _x( 'View Place', 'ubik' ),
    'search_items' => _x( 'Search Places', 'ubik' ),
    'not_found' => _x( 'No places found', 'ubik' ),
    'not_found_in_trash' => _x( 'No places found in trash', 'ubik' ),
    'parent_item_colon' => _x( 'Parent Place:', 'ubik' ),
    'menu_name' => _x( 'Places', 'ubik' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
    'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'wpcom-markdown' ),
    'taxonomies' => array( 'place_tag' ),
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_position' => 5,
    'menu_icon' => 'dashicons-location-alt',
    'show_in_nav_menus' => true,
    'publicly_queryable' => true,
    'exclude_from_search' => false,
    'has_archive' => true,
    'query_var' => true,
    'can_export' => true,
    'rewrite' => array( 'slug' => 'places' ),
    'capability_type' => 'page'
  );
  register_post_type( 'place', $args );

  // Place tag taxonomy
  register_taxonomy( 'place_tag', 'place', array(
    'hierarchical' => false,
    'labels' => array(
      'name' => _x( 'Place Tags', 'taxonomy general name' ),
      'singular_name' => _x( 'Place Tag', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Place Tags' ),
      'all_items' => __( 'All Place Tags' ),
      'parent_item' => __( 'Parent Place Tags' ),
      'parent_item_colon' => __( 'Parent Place Tags:' ),
      'edit_item' => __( 'Edit Place Tag' ),
      'update_item' => __( 'Update Place Tag' ),
      'add_new_item' => __( 'Add New Place Tag' ),
      'new_item_name' => __( 'New Place Tag Name' ),
      'menu_name' => __( 'Place Tags' ),
    ),
    'rewrite' => array(
      'slug' => 'place-tag',
      'with_front' => false
    )
  ));

  add_shortcode('place', 'ubik_places_shortcode');
}
add_action( 'init', 'ubik_places_init' );



// Places conditional
function ubik_is_place() {
  if (
    is_singular( 'place' )
    || is_post_type_archive( 'place' )
    || is_tax( 'place_tag' )
    || get_post_type( 'place' )
  ) {
    return true;
  }
  return false;
}



// Controls the flow of places in regular queries
function ubik_places_query( $query ) {
  if ( $query->is_main_query() ) {

    // Add places to the regular flow of items on the blog
    if ( UBIK_PLACES_IN_LOOP && ( is_front_page() || is_home() ) ) {

      // Check to see if the post type is already set (avoids conflicts)
      $post_type_vars = $query->get( 'post_type' );

      // Conditionally add places to the query
      if ( is_string( $post_type_vars ) && !empty( $post_type_vars ) ) {
        $query->set( 'post_type', array( $post_type_vars, 'place' ) );
      } elseif ( is_array( $post_type_vars ) ) {
        $post_type_vars[] = 'place';
        $query->set( 'post_type', $post_type_vars );
      } else {
        $query->set( 'post_type', array( 'post', 'place' ) );
      }
    }

    // Don't bother with anything tagged as a placeholder in the regular flow or on place type archives
    if ( UBIK_PLACES_PLACEHOLDER && ( ( UBIK_PLACES_IN_LOOP && ( is_front_page() || is_home() ) ) || is_post_type_archive( 'place' ) ) ) {
      $tax_query = array(
        'taxonomy'  => 'place_tag',
        'field'     => 'slug',
        'terms'     => UBIK_PLACES_PLACEHOLDER,
        'operator'  => 'NOT IN'
      );
      $query->set( 'tax_query', array( $tax_query ) );
    }

    // Display place tags in forward chronological order
    if ( is_tax ( 'place_tag' ) ) {
      $query->set( 'posts_per_page', 10 );
      $query->set( 'order', 'ASC' );
      $query->set( 'orderby', 'title' );
    }
  }
  return $query;
}
add_action( 'pre_get_posts', 'ubik_places_query' );



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
      'post_type'   => 'place',
      'post_status' => 'publish'
    );

    // Doesn't work for some reason
    if ( UBIK_PLACES_PLACEHOLDER ) {
      $tax_query = array(
        array(
          'taxonomy'  => 'place_tag',
          'field'     => 'slug',
          'terms'     => UBIK_PLACES_PLACEHOLDER,
          'operator'  => 'NOT IN'
        )
      );
      $args['tax_query'] = $tax_query;
    }

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
      // No posts found!
    }
    // Restore original post data
    wp_reset_postdata();
  }

  // If the preceding loop didn't find a match we still return the original content as-is; graceful fallback
  return $content;
}



// List places in the entry meta area
function ubik_places_list() {
  global $post;

  if ( is_singular( 'place' ) ) {

    $children = get_pages('post_type=place&child_of=' . $post->ID);
    $siblings = get_pages('post_type=place&child_of=' . $post->post_parent);

    if ( $children ) {
      ?><div class="entry-meta-places-list">
        <h2><?php printf( __( 'Places in %s:', 'ubik' ), $post->post_title ); ?></h2>
        <ul class="place-list"><?php wp_list_pages(
        array(
          'child_of'      => $post->ID,
          'depth'         => 2,
          'post_type'     => 'place',
          'title_li'      => '',
          )
        ); ?></ul>
      </div><?php

    // If there aren't any children perhaps siblings will be useful
    } elseif ( count( $siblings ) >= 3 ) {
      ?><div class="entry-meta-places-list">
        <h2><?php printf( 'Places near %s:', $post->post_title ); ?></h2>
        <ul class="place-list"><?php wp_list_pages(
        array(
          'child_of'      => $post->post_parent,
          'depth'         => 1,
          'post_type'     => 'place',
          'title_li'      => '',
          'exclude'       => $post->ID,
          )
        ); ?></ul>
      </div><?php
    } else {
      // Show parent?
    }
  }
}
// @TODO: move this to the_content hook
add_action( 'pendrell_entry_meta_before', 'ubik_places_list', 7 );



// List posts tagged with the current place
function ubik_places_posts() {
  global $post;

  if ( is_singular( 'place' ) ) {
    $place_name = $post->post_name;
    $place_title = $post->post_title;
    $place_tag = term_exists( $place_name, 'post_tag' );

    // Only do the extra work if there is a matching post tag
    if ($place_tag !== 0 && $place_tag !== null) {
      $place_tag_link = get_tag_link( $place_tag['term_id'] );

      // Fetch posts tagged with the current place; only the slugs need to match
      $the_query = new WP_Query( 'tag=' . $place_name );

      if ( $the_query->have_posts() ) {
        ?>
          <div class="entry-meta-places-posts">
            <h2><?php printf( __( 'Posts tagged <a href="%1$s">%2$s</a>:', 'ubik' ),
              $place_tag_link,
              $place_title
            ); ?></h2>
            <ul><?php while ( $the_query->have_posts() ) {
              $the_query->the_post();
              ?><li><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'ubik' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></li><?php
            } ?></ul>
          </div>
      <?php } else {
        // No posts found!
      }

      // Restore original post data
      wp_reset_postdata();
    }
  }
}
// @TODO: move this to the_content hook
add_action( 'pendrell_entry_meta_before', 'ubik_places_posts', 5 );



// Places widget; this isn't a true widget... but it's also not 200+ lines of code I don't need
function ubik_places_widget( $depth = 3 ) {
?><div id="secondary" class="widget-area" role="complementary">
    <aside id="places" class="widget widget_places">
      <h3 class="widget-title">Places</h3>
      <ul class="place-list"><?php wp_list_pages(
      array(
        'depth'         => $depth,
        'post_type'     => 'place',
        'title_li'      => '',
        )
      ); ?></ul>
    </aside>
  </div><!-- #secondary --><?php
}



// Filter the entry meta and add place-specific tags
function ubik_places_meta_tags( $tags ) {
  if ( ubik_is_place() )
    $tags = get_the_term_list( $post->ID, 'place_tag', '', ', ', '' );
  return $tags;
}
add_filter( 'ubik_content_meta_tags', 'ubik_places_meta_tags' );
