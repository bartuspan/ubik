<?php // === UBIK CONFIGURATION FILE === //

// Switch for the magic "all settings" hack; true/false
define( 'UBIK_ADMIN_ALL_SETTINGS', true );

// Additional contact methods hack; true/false
define( 'UBIK_ADMIN_CONTACT_METHODS', true );

// Admin HTML editor font size; string or false to disable
define( 'UBIK_ADMIN_EDITOR_FONT_SIZE', '18px' );

// Admin HTML editor font stack; string or false to disable
define( 'UBIK_ADMIN_EDITOR_FONT_STACK', 'Georgia, "Palatino Linotype", Palatino, "URW Palladio L", "Book Antiqua", "Times New Roman", serif;' );



// === CONTENT === //

// Filter get_the_date with Ubik's custom date function; true/false
define( 'UBIK_CONTENT_DATE', false );

// Override WordPress date format; string or false to disable
define( 'UBIK_CONTENT_DATE_FORMAT', 'M j, Y, g:i a' );

// Human-readable dates; true/false
define( 'UBIK_CONTENT_DATE_HUMAN', true );

// Strict titles; removes non-standard characters; true/false
define( 'UBIK_CONTENT_SLUG_STRICT', true );

// Switch for wp_title filter; disable if you use some sort of SEO plugin; true/false
define( 'UBIK_CONTENT_TITLE', true );



// === EXCERPTS === //

// Custom excerpt handling; true/false
define( 'UBIK_EXCERPT', true );

// Custom post excerpt length; integer or false to disable
define( 'UBIK_EXCERPT_LENGTH', 70 );

// Custom post excerpt ending; string or false to disable
define( 'UBIK_EXCERPT_MORE', '...' );

// Custom "more" link; true/false
define( 'UBIK_EXCERPT_MORE_LINK', true );

// Make excerpts shortcode-friendly; true/false
define( 'UBIK_EXCERPT_SHORTCODES', true );



// === FORMATS === //

// Activate post format module; true/false
define( 'UBIK_FORMAT', true );

// Post format rewrite; change "type/status" to "whatever/status"; string or false to disable
define( 'UBIK_FORMAT_REWRITE', false );

// Post format slug; change post format slug "quote" to "quotation" as defined in lib/formats.php; string or false to disable
define( 'UBIK_FORMAT_SLUG', false );



// === META TAGS === //

// Main switch for additional meta functionality; disable if you use another SEO plugin; true/false
define( 'UBIK_META', true );

// Facebook admin value for page insights; can be a single ID or comma-separated series of IDs; string or false to disable
define( 'UBIK_META_FACEBOOK_ADMINS', false );

// Facebook publisher; only for media outlets; string or false to disable
define( 'UBIK_META_FACEBOOK_PUBLISHER', false );

// Google Plus page for the entire site
define( 'UBIK_META_GOOGLE_PUBLISHER', false );

// Set the desired image size for images in the meta tags; defaults to 'large'; string or false to disable
define( 'UBIK_META_IMAGE_SIZE', false );

// Name of the Twitter account associated with the whole web site; should be "Account" without the @ sign; string or false to disable
define( 'UBIK_META_TWITTER_PUBLISHER', false );



// === PLACES === //

// Places functionality; true/false
define( 'UBIK_PLACES', true );

// Mix places in with posts; true/false
define( 'UBIK_PLACES_IN_LOOP', false );

// Placeholder place tag; use for places that are needed to flesh out the taxonomy but that shouldn't appear in lists; string or false to disable
define( 'UBIK_PLACES_PLACEHOLDER', 'placeholder' );



// === PORTFOLIO === //

// Portfolio functionality; true/false
define( 'UBIK_PORTFOLIO', true );



// === SEARCH === //

// Posts per search page; integer or false to disable
define( 'UBIK_SEARCH_POSTS_PER_PAGE', 20 );

// Singleton search redirect; true/false
define( 'UBIK_SEARCH_REDIRECT', true );



// === SERIES === //

// Post series functionality; true/false
define( 'UBIK_SERIES', true );

// Post series order; chronological by default; string or false to disable
define( 'UBIK_SERIES_ORDER', 'ASC' );



// === VARIOUS === //

// Google Analytics code e.g. 'UA-XXXXXX-XX'; string or false to disable
define( 'UBIK_GOOGLE_ANALYTICS', false );

