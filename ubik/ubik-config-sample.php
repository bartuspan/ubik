<?php // Ubik configuration file
define( 'UBIK_VERSION', '0.1.0' );

// Google Analytics code e.g. 'UA-XXXXXX-XX'; false when not in use
define( 'UBIK_GOOGLE_ANALYTICS', false );

// Post series functionality
define( 'UBIK_SERIES', true );

if ( is_admin() ) {
  // Admin HTML editor font stack.
  define( 'UBIK_FONTSTACK_EDITOR', 'Georgia, "Palatino Linotype", Palatino, "URW Palladio L", "Book Antiqua", "Times New Roman", serif;' );

  // Admin HTML editor font size.
  define( 'UBIK_FONTSIZE_EDITOR', '16px' );
}
