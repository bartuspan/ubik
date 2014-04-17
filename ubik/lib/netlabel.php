<?php // ==== NETLABEL ==== //

// @TODO: licensing, catalog codes, download handling, release custom post type (overkill?), additional taxonomies, embedded media

// A simple collection of taxonomies and tools for netlabel use
function ubik_netlabel_init() {

  // Artists taxonomy
  register_taxonomy( 'artists', 'post', array(
    'hierarchical' => false,
    'labels' => array(
      'name'              => _x( 'Artists', 'taxonomy general name' ),
      'singular_name'     => _x( 'Artist', 'taxonomy singular name' ),
      'search_items'      =>  __( 'Search Artists' ),
      'all_items'         => __( 'All Artists' ),
      'parent_item'       => __( 'Parent Artist' ),
      'parent_item_colon' => __( 'Parent Artist:' ),
      'edit_item'         => __( 'Edit Artist' ),
      'update_item'       => __( 'Update Artist' ),
      'add_new_item'      => __( 'Add New Artist' ),
      'new_item_name'     => __( 'New Artist Name' ),
      'menu_name'         => __( 'Artists' ),
    ),
    'rewrite' => array(
      'slug' => 'artists'
    ),
  ));

  // Styles taxonomy
  register_taxonomy( 'styles', 'post', array(
    'hierarchical' => false,
    'labels' => array(
      'name'              => _x( 'Styles', 'taxonomy general name' ),
      'singular_name'     => _x( 'Style', 'taxonomy singular name' ),
      'search_items'      =>  __( 'Search Styles' ),
      'all_items'         => __( 'All Styles' ),
      'parent_item'       => __( 'Parent Style' ),
      'parent_item_colon' => __( 'Parent Style:' ),
      'edit_item'         => __( 'Edit Style' ),
      'update_item'       => __( 'Update Style' ),
      'add_new_item'      => __( 'Add New Style' ),
      'new_item_name'     => __( 'New Style Name' ),
      'menu_name'         => __( 'Styles' ),
    ),
    'rewrite' => array(
      'slug' => 'styles'
    ),
  ));

  // Discography shortcode
  add_shortcode( 'discog', 'ubik_discography_shortcode' );

}
add_action( 'init', 'ubik_netlabel_init' );


// == DISCOGRAPHY SHORTCODE == //

// For pulling discographies into profiles and such
function ubik_discography_shortcode( $atts, $content = null ) {

  global $post;

  // Get the current slug; used as a default if slug attribute is not explicitly defined
  $slug = $post->post_name;

  // Extract attributes
  extract( shortcode_atts( array(
    'slug'    => $slug,                     // Pass a slug if you need to
    'type'    => '',                        // A category slug
    'format'  => 'text',                    // How to display the discography: 'text' or 'thumbnails'
    'size'    => 'medium-third-cropped'     // Size of the thumbnail
  ), $atts ) );

  // If $type isn't empty let's limit results by category
  if ( !empty( $type ) ) {
    $tax_query = array(
      'relation' => 'AND',
      array(
        'taxonomy' => 'category',
        'field' => 'slug',
        'terms' => $type
      ),
      array(
        'taxonomy' => 'artists',
        'field' => 'slug',
        'terms' => $slug
      )
    );
  } else {
    $tax_query = array(
      array(
        'taxonomy' => 'artists',
        'field' => 'slug',
        'terms' => $slug
      )
    );
  }

  // Run the query
  $disco_query = new WP_Query( array(
    'nopaging' => true, // Show all posts
    'tax_query' => $tax_query
  ) );

  // If we have a match
  if ( $disco_query->have_posts() ) {

    // Outer wrapper
    $content = '<div class="discography">' . "\n";
    $class = 'discography-' . esc_attr( $format );

    // If a type was specified lets give it special treatment
    if ( !empty( $type ) ) {
      $content .= '<h2>' . get_term_by( 'slug', $type, 'category' )->name . '</h2>' . "\n";
      $class .= ' discography-' . esc_attr( $type );

    // Otherwise this is just a regular discography
    } else {
      $content .= '<h2>' . __( 'Discography', 'ubik' ) . '</h2>' . "\n";
      $class .= '';
    }

    $content .= '<ul class="' . $class . '">' . "\n";
    while ( $disco_query->have_posts() ) : $disco_query->the_post();
      if ( $format === 'text' ) {
        $content .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a> (' . get_the_date( "Y" ) . ')</li>' . "\n";
      } elseif ( $format === 'thumbnails' ) {
        $content .= '<li><a href="' . get_permalink() . '">' . get_the_post_thumbnail( '', $size ) . '</a></li>' . "\n";
      }
    endwhile;
    wp_reset_postdata();
    $content .= '</ul>' . "\n" . '</div>' . "\n";
  }

  return apply_filters( 'the_content', $content );
}



// Adds places to entry metadata right after other taxonomies
function ubik_netlabel_entry_meta( $meta ) {
  global $post;
  if ( has_term( '', 'artists' ) )
    $meta .= 'Artists: ' . get_the_term_list( $post->ID, 'artists', '', ', ', '. ' );
  if ( has_term( '', 'styles' ) )
    $meta .= 'Styles: ' . get_the_term_list( $post->ID, 'styles', '', ', ', '. ' );
  return $meta;
}
add_filter( 'ubik_content_meta_taxonomies', 'ubik_netlabel_entry_meta' );



// Hook into Pendrell to deliver excerpts of profiles on relevant terms (i.e. an artist with a profile page)
function ubik_netlabel_term_description( $description ) {

  $tax = get_query_var( 'taxonomy' );

  if ( $tax === 'artists' ) {

    //$term = get_term_by( 'slug', get_query_var( 'term' ), $tax );
    $profile = get_posts( array(
        'name'        => get_query_var( 'term' ),
        'post_type'   => array( 'post', 'page' ),
        'post_status' => 'publish',
        'numberposts' => 1
      )
    );
    if ( !empty( $profile ) ) {
      $description = ubik_excerpt( $profile[0]->post_content ) . "\n";
      $description .= ' <a href="'. esc_url( get_permalink( $profile[0]->ID ) ) . '" class="more-link">' . __( 'Continue reading&nbsp;&rarr;', 'ubik' ) . '</a>';
    }
  }
  return $description;
}
add_filter( 'pendrell_archive_term_description', 'ubik_netlabel_term_description' );
