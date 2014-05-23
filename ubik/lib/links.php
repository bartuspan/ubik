<?php // ==== LINKS ==== //

// Enable WordPress links manager on new installs; a copy of http://wordpress.org/plugins/link-manager/
if ( UBIK_VARIOUS_LINKS_MANAGER )
  add_filter( 'pre_option_link_manager_enabled', '__return_true' );
