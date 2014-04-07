<?php // === UBIK CONFIGURATION FILE === //

// == UBIK CORE SWITCHES == //

// The following switches enable or disable various Ubik modules; true/false
defined( 'UBIK_EXCERPT' )                   || define( 'UBIK_EXCERPT', true );
defined( 'UBIK_FORMAT' )                    || define( 'UBIK_FORMAT', false );
defined( 'UBIK_META' )                      || define( 'UBIK_META', false );
defined( 'UBIK_PLACES' )                    || define( 'UBIK_PLACES', false );
defined( 'UBIK_SERIES' )                    || define( 'UBIK_SERIES', false );
defined( 'UBIK_WPCONFIG' )                  || define( 'UBIK_WPCONFIG', false );



// == ADMIN == //

// Switch for the magic "all settings" hack; true/false
defined( 'UBIK_ADMIN_ALL_SETTINGS' )        || define( 'UBIK_ADMIN_ALL_SETTINGS', true );

// Additional contact methods hack; true/false
defined( 'UBIK_ADMIN_CONTACT_METHODS' )     || define( 'UBIK_ADMIN_CONTACT_METHODS', true );

// Admin HTML editor font size; string or false to disable
defined( 'UBIK_ADMIN_EDITOR_FONT_SIZE' )    || define( 'UBIK_ADMIN_EDITOR_FONT_SIZE', '18px' );

// Admin HTML editor font stack; string or false to disable
defined( 'UBIK_ADMIN_EDITOR_FONT_STACK' )   || define( 'UBIK_ADMIN_EDITOR_FONT_STACK', 'Georgia, "Palatino Linotype", Palatino, "URW Palladio L", "Book Antiqua", "Times New Roman", serif;' );

// Rich term editor; true/false
defined( 'UBIK_ADMIN_TERM_EDITOR' )         || define( 'UBIK_ADMIN_TERM_EDITOR', false );



// == CONTENT == //

// Filter get_the_date with Ubik's custom date function; true/false
defined( 'UBIK_CONTENT_DATE' )              || define( 'UBIK_CONTENT_DATE', false );

// Override WordPress date format; string or false to disable
defined( 'UBIK_CONTENT_DATE_FORMAT' )       || define( 'UBIK_CONTENT_DATE_FORMAT', false );

// Human-readable dates; true/false
defined( 'UBIK_CONTENT_DATE_HUMAN' )        || define( 'UBIK_CONTENT_DATE_HUMAN', true );

// Human-readable time span; integer or false to disable and use default
defined( 'UBIK_CONTENT_DATE_HUMAN_SPAN' )   || define( 'UBIK_CONTENT_DATE_HUMAN_SPAN', false);

// Strict titles; removes non-standard characters; true/false
defined( 'UBIK_CONTENT_SLUG_STRICT' )       || define( 'UBIK_CONTENT_SLUG_STRICT', false );

// Switch for wp_title filter; disable if you use some sort of SEO plugin; true/false
defined( 'UBIK_CONTENT_TITLE' )             || define( 'UBIK_CONTENT_TITLE', true );



// == EXCERPTS == //

// Custom excerpt handling; true/false

// Custom post excerpt length; integer or false to disable
defined( 'UBIK_EXCERPT_LENGTH' )            || define( 'UBIK_EXCERPT_LENGTH', 70 );

// Custom post excerpt ending; string or false to disable
defined( 'UBIK_EXCERPT_MORE' )              || define( 'UBIK_EXCERPT_MORE', '...' );

// Custom "more" link; true/false
defined( 'UBIK_EXCERPT_MORE_LINK' )         || define( 'UBIK_EXCERPT_MORE_LINK', true );

// Make excerpts shortcode-friendly; true/false
defined( 'UBIK_EXCERPT_SHORTCODES' )        || define( 'UBIK_EXCERPT_SHORTCODES', true );



// == FORMATS == //

// Post format rewrite; change "type/status" to "whatever/status"; string or false to disable
defined( 'UBIK_FORMAT_REWRITE' )            || define( 'UBIK_FORMAT_REWRITE', false );

// Post format slug; change post format slug "quote" to "quotation" as defined in lib/formats.php; string or false to disable
defined( 'UBIK_FORMAT_SLUG' )               || define( 'UBIK_FORMAT_SLUG', false );



// == MEDIA == //

// Custom gallery function
defined( 'UBIK_MEDIA_GALLERY' )             || define( 'UBIK_MEDIA_GALLERY', false );



// == META TAGS == //

// Facebook admin value for page insights; can be a single ID or comma-separated series of IDs; string or false to disable
defined( 'UBIK_META_FACEBOOK_ADMINS' )      || define( 'UBIK_META_FACEBOOK_ADMINS', false );

// Facebook publisher; only for media outlets; string or false to disable
defined( 'UBIK_META_FACEBOOK_PUBLISHER' )   || define( 'UBIK_META_FACEBOOK_PUBLISHER', false );

// Google Plus page for the entire site
defined( 'UBIK_META_GOOGLE_PUBLISHER' )     || define( 'UBIK_META_GOOGLE_PUBLISHER', false );

// Set the desired image size for images in the meta tags; defaults to 'large'; string or false to disable
defined( 'UBIK_META_IMAGE_SIZE' )           || define( 'UBIK_META_IMAGE_SIZE', false );

// Name of the Twitter account associated with the whole web site; should be "Account" without the @ sign; string or false to disable
defined( 'UBIK_META_TWITTER_PUBLISHER' )    || define( 'UBIK_META_TWITTER_PUBLISHER', false );



// == SEARCH == //

// Posts per search page; integer or false to disable
defined( 'UBIK_SEARCH_POSTS_PER_PAGE' )     || define( 'UBIK_SEARCH_POSTS_PER_PAGE', 20 );

// Singleton search redirect; true/false
defined( 'UBIK_SEARCH_REDIRECT' )           || define( 'UBIK_SEARCH_REDIRECT', true );



// == SERIES == //

// Post series order; chronological by default; string or false to disable
defined( 'UBIK_SERIES_ORDER' )              || define( 'UBIK_SERIES_ORDER', 'ASC' );



// == WPCONFIG == //

// Compression; flips several core settings to turn on gzip etc.; don't use with an already minified theme; true/false
defined( 'UBIK_WPCONFIG_COMPRESS' )         || define( 'UBIK_WPCONFIG_COMPRESS', false );

// Development mode; flips various settings in wp-config for debugging; true/false
defined( 'UBIK_WPCONFIG_DEV' )              || define( 'UBIK_WPCONFIG_DEV', false );

// Force SSL; true/false
defined( 'UBIK_WPCONFIG_FORCE_SSL' )        || define( 'UBIK_WPCONFIG_FORCE_SSL', false );



// == VARIOUS == //

// Google Analytics code e.g. 'UA-XXXXXX-XX'; string or false to disable
defined( 'UBIK_GOOGLE_ANALYTICS' )          || define( 'UBIK_GOOGLE_ANALYTICS', false );

