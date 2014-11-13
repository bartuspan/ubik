<?php // === UBIK CONFIGURATION FILE === //

// == UBIK CORE SWITCHES == //

// The following switches enable or disable various Ubik modules; true/false
defined( 'UBIK_CHINESE' )                   || define( 'UBIK_CHINESE', false );
defined( 'UBIK_EXCERPT' )                   || define( 'UBIK_EXCERPT', true );
defined( 'UBIK_FORMAT' )                    || define( 'UBIK_FORMAT', false );
defined( 'UBIK_MARKDOWN' )                  || define( 'UBIK_MARKDOWN', false );
defined( 'UBIK_META' )                      || define( 'UBIK_META', false );
defined( 'UBIK_NETLABEL' )                  || define( 'UBIK_NETLABEL', false );



// == ADMIN == //

// Switch for the magic "all settings" hack; true/false
defined( 'UBIK_ADMIN_ALL_SETTINGS' )        || define( 'UBIK_ADMIN_ALL_SETTINGS', false );

// Switch for the magic "view all shortcodes" hack; true/false
defined( 'UBIK_ADMIN_ALL_SHORTCODES' )      || define( 'UBIK_ADMIN_ALL_SHORTCODES', false );

// Additional contact methods hack; true/false
defined( 'UBIK_ADMIN_CONTACT_METHODS' )     || define( 'UBIK_ADMIN_CONTACT_METHODS', false );

// Admin HTML editor font size; string or false to disable
defined( 'UBIK_ADMIN_EDITOR_FONT_SIZE' )    || define( 'UBIK_ADMIN_EDITOR_FONT_SIZE', '18px' );

// Admin HTML editor font stack; string or false to disable
defined( 'UBIK_ADMIN_EDITOR_FONT_STACK' )   || define( 'UBIK_ADMIN_EDITOR_FONT_STACK', 'Georgia, "Palatino Linotype", Palatino, "URW Palladio L", "Book Antiqua", "Times New Roman", serif;' );

// Featured image/post thumbnail column in post list; true/false
defined( 'UBIK_ADMIN_POST_LIST_THUMB' )     || define( 'UBIK_ADMIN_POST_LIST_THUMB', false );

// Filter posts by tags; true/false
defined( 'UBIK_ADMIN_TAG_FILTER' )          || define( 'UBIK_ADMIN_TAG_FILTER', false );

// Inject some CSS to re-arrange the edit-tags.php template for term power users; true/false
defined( 'UBIK_ADMIN_TERM_EDIT_STYLE' )     || define( 'UBIK_ADMIN_TERM_EDIT_STYLE', false );

// Master switch for the visual editor; true to disable editor, false to let WordPress sort it out
defined( 'UBIK_ADMIN_VISUAL_EDITOR_OFF' )   || define( 'UBIK_ADMIN_VISUAL_EDITOR_OFF', false );



// == ATTACHMENTS == //

// Turn comments on/off for all attachments; true to disable comments or false to let WordPress sort it out
defined( 'UBIK_ATTACHMENT_COMMENTS_OFF' )   || define( 'UBIK_ATTACHMENT_COMMENTS_OFF', false );



// == COMMENTS == //

// Modify tags allowed in comments; specify allowable tags in lib/comments.php; false to disable
defined( 'UBIK_COMMENTS_ALLOWED_TAGS' )     || define( 'UBIK_COMMENTS_ALLOWED_TAGS', false );



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



// == FEED == //

// Disable all feeds
defined( 'UBIK_FEED_DISABLE' )              || define( 'UBIK_FEED_DISABLE', false );

// Disable comments feed
defined( 'UBIK_FEED_DISABLE_COMMENTS' )     || define( 'UBIK_FEED_DISABLE_COMMENTS', false );

// Remove certain post formats from the main feed
defined( 'UBIK_FEED_DISABLE_FORMATS' )      || define( 'UBIK_FEED_DISABLE_FORMATS', false );



// == FORMATS == //

// Post format rewrite; change "type/status" to "whatever/status"; string or false to disable
defined( 'UBIK_FORMAT_REWRITE' )            || define( 'UBIK_FORMAT_REWRITE', false );

// Post format slug; change post format slug "quote" to "quotation" as defined in lib/formats.php; string or false to disable
defined( 'UBIK_FORMAT_SLUG' )               || define( 'UBIK_FORMAT_SLUG', false );



// == GENERAL == //

// Remove useless fluff from the <head> element; true/false
defined( 'UBIK_GENERAL_HEAD_CLEANER' )      || define( 'UBIK_GENERAL_HEAD_CLEANER', false );

// Enable core WordPress links manager on new installs; true/false
defined( 'UBIK_GENERAL_LINKS_MANAGER' )     || define( 'UBIK_GENERAL_LINKS_MANAGER', false );

// Disable jQuery migrate; true/false
defined( 'UBIK_GENERAL_REMOVE_MIGRATE' )    || define( 'UBIK_GENERAL_REMOVE_MIGRATE', false );

// Disable automatic loading of Open Sans; true/false
defined( 'UBIK_GENERAL_REMOVE_OPEN_SANS' )  || define( 'UBIK_GENERAL_REMOVE_OPEN_SANS', false );

// Remove the word "protected" from password-protected posts; true/false
defined( 'UBIK_GENERAL_REMOVE_PROTECTED' )  || define( 'UBIK_GENERAL_REMOVE_PROTECTED', false );



// == GOOGLE ANALYTICS == //

// Google Analytics tracking code e.g. 'UA-XXXXXX-XX'; string or false to disable
defined( 'UBIK_GOOGLE_ANALYTICS' )          || define( 'UBIK_GOOGLE_ANALYTICS', false );

// Google Analytics display features; enable for compatibility with demographic reporting
defined( 'UBIK_GOOGLE_ANALYTICS_DISPLAYF' ) || define( 'UBIK_GOOGLE_ANALYTICS_DISPLAYF', false );

// Google Analytics tracking code version; true for legacy support of asynchronous analytics; false for universal analytics
defined( 'UBIK_GOOGLE_ANALYTICS_ASYNC' )    || define( 'UBIK_GOOGLE_ANALYTICS_ASYNC', false );



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



// == SEARCH == //

// Improved HTML5 search form; true/false
defined( 'UBIK_SEARCH_FORM' )               || define( 'UBIK_SEARCH_FORM', false );

// Posts per search page; integer or false to disable
defined( 'UBIK_SEARCH_POSTS_PER_PAGE' )     || define( 'UBIK_SEARCH_POSTS_PER_PAGE', false );

// Singleton search redirect; true/false
defined( 'UBIK_SEARCH_REDIRECT' )           || define( 'UBIK_SEARCH_REDIRECT', false );



// == TERMS == //

// Hard switch for the categorized blog test; set to true for no categories; false to let WordPress figure it out
defined( 'UBIK_TERMS_UNCATEGORIZED_BLOG' )  || define( 'UBIK_TERMS_UNCATEGORIZED_BLOG', false );
