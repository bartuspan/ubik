<?php // Ubik configuration file
define( 'UBIK_VERSION', '0.1.0' );

// Custom post excerpt length
define( 'UBIK_EXCERPT_LENGTH', 50 );

// Google Analytics code e.g. 'UA-XXXXXX-XX'; false when not in use
define( 'UBIK_GOOGLE_ANALYTICS', false );

// Places functionality
define( 'UBIK_PLACES', true );

// Portfolio functionality
define( 'UBIK_PORTFOLIO', true );

// Post format rewrite
define( 'UBIK_POST_FORMAT_REWRITE', true );

// Posts per page
define( 'UBIK_POSTS_PER_PAGE_SEARCH', 20 );

// Search redirect
define( 'UBIK_SEARCH_REDIRECT', true );

// Post series functionality
define( 'UBIK_SERIES', true );

// Post series order; chronological by default
define( 'UBIK_SERIES_ORDER', 'ASC' );

// Strict titles; removes non-standard characters
define( 'UBIK_STRICT_TITLE', true );

if ( is_admin() ) {
  // Admin HTML editor font size.
  define( 'UBIK_FONTSIZE_EDITOR', '18px' );

  // Admin HTML editor font stack.
  define( 'UBIK_FONTSTACK_EDITOR', 'Georgia, "Palatino Linotype", Palatino, "URW Palladio L", "Book Antiqua", "Times New Roman", serif;' );
}
