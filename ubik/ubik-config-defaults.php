<?php // === UBIK CONFIGURATION FILE === //

// == UBIK CORE SWITCHES == //

// The following switches enable or disable various Ubik modules; true/false
defined( 'UBIK_EXCERPT' )                   || define( 'UBIK_EXCERPT', true );
defined( 'UBIK_META' )                      || define( 'UBIK_META', false );
defined( 'UBIK_NETLABEL' )                  || define( 'UBIK_NETLABEL', false );



// == CONTENT == //

// Filter get_the_date with Ubik's custom date function; true/false
defined( 'UBIK_CONTENT_DATE' )              || define( 'UBIK_CONTENT_DATE', false );

// Override WordPress date format; string or false to disable
defined( 'UBIK_CONTENT_DATE_FORMAT' )       || define( 'UBIK_CONTENT_DATE_FORMAT', false );

// Human-readable dates; true/false
defined( 'UBIK_CONTENT_DATE_HUMAN' )        || define( 'UBIK_CONTENT_DATE_HUMAN', false );

// Human-readable time span; integer or false to disable and use default (4838000; one week = 604800)
defined( 'UBIK_CONTENT_DATE_HUMAN_SPAN' )   || define( 'UBIK_CONTENT_DATE_HUMAN_SPAN', false );

// Strip paragraph tags from <iframe> elements; true/false to disable
defined( 'UBIK_CONTENT_STRIP_MEDIA_P' )     || define( 'UBIK_CONTENT_STRIP_MEDIA_P', false );

// Strip orphaned paragraph tags formed by <!--more--> tag edge cases; true/false to disable
defined( 'UBIK_CONTENT_STRIP_MORE_ORPHAN' ) || define( 'UBIK_CONTENT_STRIP_MORE_ORPHAN', false );

// Switch for wp_title filter; disable if you use some sort of SEO plugin; true/false to disable
defined( 'UBIK_CONTENT_TITLE' )             || define( 'UBIK_CONTENT_TITLE', false );



// == EXCERPTS == //

// Custom excerpt handling; this module needs to be enabled

// Custom post excerpt length; integer or false to disable
defined( 'UBIK_EXCERPT_LENGTH' )            || define( 'UBIK_EXCERPT_LENGTH', 70 );

// Custom post excerpt ending; had some problems with '&hellip;'' in places so '...' it is; string or false to disable
defined( 'UBIK_EXCERPT_MORE' )              || define( 'UBIK_EXCERPT_MORE', '...' );

// Custom "more" link; true/false
defined( 'UBIK_EXCERPT_MORE_LINK' )         || define( 'UBIK_EXCERPT_MORE_LINK', false );

// Add excerpts to pages; true/false
defined( 'UBIK_EXCERPT_PAGES' )             || define( 'UBIK_EXCERPT_PAGES', false );

// Process shortcodes in excerpts; true/false
defined( 'UBIK_EXCERPT_SHORTCODES' )        || define( 'UBIK_EXCERPT_SHORTCODES', false );

// Make excerpts shortcode-friendly; true/false
defined( 'UBIK_EXCERPT_STRIP_ASIDES' )      || define( 'UBIK_EXCERPT_STRIP_ASIDES', false );



// == META TAGS == //

// Facebook admin value for page insights; can be a single ID or comma-separated series of IDs; string or false to disable
defined( 'UBIK_META_FACEBOOK_ADMINS' )      || define( 'UBIK_META_FACEBOOK_ADMINS', false );

// Facebook publisher; only for media outlets; string or false to disable
defined( 'UBIK_META_FACEBOOK_PUBLISHER' )   || define( 'UBIK_META_FACEBOOK_PUBLISHER', false );

// Favicon markup; dependent on generated Favicons in the root of the domain; true/false
defined( 'UBIK_META_FAVICONS' )             || define( 'UBIK_META_FAVICONS', false );

// Favicon tile color; should be a hex value or false to disable
defined( 'UBIK_META_FAVICONS_TILE_COLOR' )  || define( 'UBIK_META_FAVICONS_TILE_COLOR', false );

// Google Plus page for the entire site
defined( 'UBIK_META_GOOGLE_PUBLISHER' )     || define( 'UBIK_META_GOOGLE_PUBLISHER', false );

// Set the desired image size for images in the meta tags; defaults to 'large'; string or false to disable
defined( 'UBIK_META_IMAGE_SIZE' )           || define( 'UBIK_META_IMAGE_SIZE', false );

// Name of the Twitter account associated with the whole web site; should be "Account" without the @ sign; string or false to disable
defined( 'UBIK_META_TWITTER_PUBLISHER' )    || define( 'UBIK_META_TWITTER_PUBLISHER', false );



// == TERMS == //

// Hard switch for the categorized blog test; set to true for no categories; false to let WordPress figure it out
defined( 'UBIK_TERMS_UNCATEGORIZED_BLOG' )  || define( 'UBIK_TERMS_UNCATEGORIZED_BLOG', false );
