<?php // === GENERAL === //

// Head cleaner: removes useless fluff, Windows Live Writer support, version info, pointless relational links
function ubik_init() {
  if ( !is_admin() ) {
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'start_post_rel_link' );
    remove_action( 'wp_head', 'index_rel_link' );
    remove_action( 'wp_head', 'adjacent_posts_rel_link' );
  }
}
add_action( 'init', 'ubik_init' );



function ubik_enqueue_scripts() {
  // Hack: no need to load Open Sans more than once!
  wp_deregister_style( 'open-sans' );
  wp_register_style( 'open-sans', false );

  // Adds JavaScript to pages with the comment form to support sites with threaded comments (when in use).
  // Commented out due to unnecessary bloat!
  //if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
    //wp_enqueue_script( 'comment-reply' );
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
