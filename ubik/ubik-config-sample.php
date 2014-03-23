<?php // Ubik configuration file
define( 'UBIK_VERSION', '0.1.0' );

// Custom post excerpt length
define( 'UBIK_EXCERPT_LENGTH', 50 );

// Google Analytics code e.g. 'UA-XXXXXX-XX'; false when not in use
define( 'UBIK_GOOGLE_ANALYTICS', false );

// Meta tags; disable if you use some sort of SEO plugin
define( 'UBIK_META', true );

// Places functionality
define( 'UBIK_PLACES', true );

// Mix places in with posts?
define( 'UBIK_PLACES_IN_LOOP', false );

// Placeholder place tag; use for places that are needed to flesh out the taxonomy but that shouldn't appear in lists
define( 'UBIK_PLACES_PLACEHOLDER', 'placeholder' );

// Portfolio functionality
define( 'UBIK_PORTFOLIO', true );

// Post format rewrite; change "type/status" to "whatever/status"
define( 'UBIK_POST_FORMAT_REWRITE', false );
define( 'UBIK_POST_FORMAT_REWRITE_BASE', 'format' );

// Post format slug; change post format slug "quote" to "quotation" as defined in lib/post-format-slug.php
define( 'UBIK_POST_FORMAT_SLUG', false );

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
