<?php // ==== GENERAL ==== //

// Head cleaner: removes useless fluff
function ubik_init() {
  if ( !is_admin() ) {
    // Windows Live Writer support, version info
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_generator' );

    // Pointless relational links
    remove_action( 'wp_head', 'index_rel_link' );
    remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
  }
}
add_action( 'init', 'ubik_init' );



// Cleans out the RSS feed
function ubik_generator() {
  return '';
}
add_filter( 'the_generator', 'ubik_generator' );



// Removes the ".recentcomments" style added to the header for no good reason
// http://www.narga.net/how-to-remove-or-disable-comment-reply-js-and-recentcomments-from-wordpress-header
function ubik_remove_recent_comments_style_widget() {
  global $wp_widget_factory;
  if ( isset( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ) ) {
    remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
  }
}
add_action( 'wp_head', 'ubik_remove_recent_comments_style_widget', 1 );



// Remove injected CSS for recent comments widget; from Bones: https://github.com/eddiemachado/bones
function ubik_remove_recent_comments_style() {
  if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
    remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
  }
}
add_filter( 'wp_head', 'ubik_remove_recent_comments_style', 1 );



// Enqueue scripts like a boss
function ubik_enqueue_scripts() {
  // Hack: no need to load Open Sans more than once
  wp_deregister_style( 'open-sans' );
  wp_register_style( 'open-sans', false );

  // Adds JavaScript to pages with the comment form to support sites with threaded comments
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
    wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'ubik_enqueue_scripts' );



// Google Analytics code
function ubik_analytics() {
  if ( UBIK_GOOGLE_ANALYTICS ) { ?>
        <script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo UBIK_GOOGLE_ANALYTICS; ?>']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script><?php
  }
}
add_action( 'wp_footer', 'ubik_analytics' );
