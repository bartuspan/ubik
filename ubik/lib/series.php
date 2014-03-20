<?php // === SERIES === //

// Everything you need to implement a quick and dirty post series custom taxonomy
function ubik_series_init() {
  register_taxonomy( 'series', 'post', array(
    'hierarchical' => false,
    'labels' => array(
      'name' => _x( 'Series', 'taxonomy general name' ),
      'singular_name' => _x( 'Series', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Series' ),
      'all_items' => __( 'All Series' ),
      'parent_item' => __( 'Parent Series' ),
      'parent_item_colon' => __( 'Parent Series:' ),
      'edit_item' => __( 'Edit Series' ),
      'update_item' => __( 'Update Series' ),
      'add_new_item' => __( 'Add New Series' ),
      'new_item_name' => __( 'New Series Name' ),
      'menu_name' => __( 'Series' ),
    ),
    'rewrite' => array(
      'slug' => 'series',
      'with_front' => false
    ),
  ));
}
add_action( 'init', 'ubik_series_init' );



// Quick and dirty series list on single entries
function ubik_series_list() {
  global $post;

  // Only display the post series list on the single post view
  if ( is_singular() ) {

    // Fetch a list of post series the current post is a part of
    $series_terms = wp_get_post_terms( $post->ID, 'series', array(
      'orderby' => 'name', // Defaults to alphabetical order; also: count, slug, or term_id
      'order' => 'ASC'
    ) );

    if ( $series_terms ) {
      foreach ( $series_terms as $series_term ) {

        // Fetch a list of posts in a given series in chronological order
        $series_query = new WP_Query( array(
          'order' => 'ASC',
          'nopaging' => true,
          'tax_query' => array(
            array(
              'taxonomy' => 'series',
              'field' => 'slug',
              'terms' => $series_term->slug
            )
          )
        ) );

        // Display the list of posts in the series only if there is more than one post in that series
        if ( $series_query->have_posts() && ( $series_query->found_posts > 1 ) ): ?>
        <div class="entry-meta-series">
          <h2><?php printf( __( 'This post is a part of the &#8216;<a href="%1$s">%2$s</a>&#8217; series:', 'ubik' ),
            get_term_link( $series_term->slug, 'series' ),
            $series_term->name );
          ?></h2>
          <ol>
          <?php while ( $series_query->have_posts() ) : $series_query->the_post(); ?>
            <li><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></li>
          <?php endwhile; wp_reset_postdata(); ?>
          </ol>
        </div>
        <?php endif;
      }
    }
  }
}
add_action( 'pendrell_entry_meta_before', 'ubik_series_list' );



// Display post series in order
// Caution: may require changing "newer" and "older" navigation cues to "next" and "previous"
function ubik_series_get_posts( $query ) {
  if (
    is_tax ( 'series' )
    && $query->is_main_query()
    && UBIK_SERIES_ORDER
  ) {
    $query->set( 'order', UBIK_SERIES_ORDER );
  }
  return $query;
}
add_filter( 'pre_get_posts', 'ubik_series_get_posts' );



// Test to see whether the post is part of a series
// TODO: tighten this up
function ubik_in_series() {
  if (
    taxonomy_exists( 'series' )
    && has_term( '', 'series' )
  ) {
    return true;
  } else {
    return false;
  }
}