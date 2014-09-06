# UBIK

Ubik is a library of useful theme-agnostic WordPress snippets, hacks, and functions bundled into a plugin. It is designed to be extremely lightweight and configurable, offering no back-end options screen or user interface of any kind. All settings and options are managed in a simple configuration file. Most features are disabled by default which means you'll need to get your hands dirty and break out a text editor to harness the power of Ubik. In essence, Ubik is a performance-optimized drop-in replacement for any number of single-use WordPress plugins.



## Installation

Copy or symlink `ubik/ubik` to your plugins folder and activate. Be sure to flush your permalinks after installation.

Ubik requires WordPress 3.9 and PHP 5.3.



## Configuration

Most of Ubik's features are disabled by default to reduce collision with other plugins and themes. There are two ways to activate Ubik's features or change settings:

1. Override default values in your `wp-config.php` file.
2. Rename `ubik-config-sample.php` to `ubik-config.php` and edit the default values.

So, for instance, to activate the meta module add this line to your `wp-config.php` or `ubik-config.php` file:

`define( 'UBIK_META', true );`



## Features

Here are some of Ubik's features broken down by module. Most of these features are disabled by default.



### Admin

* Reset admin-side HTML editor to a nicer font size and stack.
* Adds Facebook, Flickr, GitHub, Google+, Instagram, and Twitter to user contact methods; removes AIM, Jabber, and Yahoo.
* Switch for the magic "all settings" hack.
* System-wide shortcode viewer shows you what shortcodes are registered (useful in development).
* Rich term editor (experimental).



### Attachments

* Turns off comments on all attachments (optional).



### Categories

* Test whether the blog has more than one category (via _s).



### Chinese

* "Strict" slugs; removes non-Latin characters (designed for use with bilingual English/Chinese post titles).
* "Unpinyin" function; converts accented characters to their non-accented equivalents.



### Comments

* Modify what tags are allowable in comments. WordPress ships with a lot of stuff that nobody uses (e.g. `<abbr>`). This trims the list to just those elements that are most useful.
* Also alters the text that commonly appears below the comment entry form to match what tags are actually allowed.



### Content

* SEO-friendly title generator for use with `wp_title` and any other place neatly formatted titles are welcome.
* Human-readable dates (e.g. posted 21 hours ago) for recent entries (user-configurable; defaults to 8 weeks).
* Standards-compliant entry metadata generator with schema.org structured data tags (e.g. proper use of `updated` and `published` classes).
* Content filters to strip paragraph tags from images and orphaned `<!--more-->` tags.



### Excerpts

* Nicer, smarter excerpts with sensible defaults.
* Also used to generate meta description tag content for SEO.
* Several user-configurable options including excerpt length, `<!--more-->` tag string, whether shortcodes are processed, etc.
* Strip opening `<aside>` tags from post contents; this way you can open a post with an aside (e.g. "This post was originally written three years ago...") without it dominating SEO.



### Feed

* Cleaner feed content titles.
* Downsize large photos to medium on RSS feeds (for use with Ubik's image shortcode).
* Remove specified post formats from the feed (defaults to aside, link, quote, and status post formats).
* Disable all feeds or only comments feeds (both inactive by default).



### Formats

* Post format rewrite: change the base ("type" to whatever).
* Post format slugs: change the slug (e.g. "quote" to "quotation"). Not a great hack; you have to edit the array in `lib/formats.php`.



### General

* Head cleaner; removes a lot of junk from the page header, a common feature of most starter themes.
* Dequeues the default Open Sans stylesheet.
* Removes the `.recentcomments` style injected into the header.
* Allow HTML in author descriptions on single user blogs.
* Page list fallback adds home to the list (via _s).
* Strips "protected" out of protected post titles.



### Google Analytics

* Adds Google Analytics code (universal or asynchronous) to the footer, where it belongs.



### Image

* Nicer HTML5-friendly image markup for attachments, image format posts, and image captions. Includes WAI-ARIA and schema.org structured data markup.
* Filter `ubik_meta_image_size` to set image size for meta tags (e.g. for Facebook and Twitter). Defaults to 'large' and falls back to the original image. See [Pendrell](https://github.com/synapticism/pendrell) for examples of usage.
* Thumbnail fallback: if a featured image isn't set the appropriately-sized thumbnail of the first image attached to the post will be displayed.
* Removes image height and width attributes from many different places for greater responsivity (optional).



### Image shortcodes

* This module introduces an image shortcode, one of Ubik's most powerful and convenient features. An image shortcode allows for dynamic resizing of images to suit different contexts (e.g. regular versus full-width view). Using a shortcode also means you never have to change URLs again. Read the source to find out more about this feature or [check out this post on my blog](http://synapticism.com/experimenting-with-html5-image-markup-and-shortcodes-in-wordpress/).
* Image shortcode `[image/]` automatically entered in the post editor with minimal markup. Note: presently image shortcodes are not compatible with the visual editor!
* Image group shortcode `[group][/group]` with optional parameter `columns` for control of image layout.
* Filters caption shortcode, spitting out better markup using code in the image module (above).



### Meta

This component is designed to be a lightweight drop-in replacement for most mainstream SEO plugins, most of which I find to be way too bulky and bloated. The idea here is to define just what is needed and otherwise work silently in the background without adding any cruft to the admin panel.

* SEO-friendly title and description generator (based on code in the content and excerpts modules).
* Supports Facebook/Open Graph, Google+, Twitter, and Pinterest social media meta tags. You will need to activate/verify ownership of most of these; check the comments in the source.
* If a post has a featured image this will always be displayed *first* in the Open Graph image tags.
* Includes a workaround for the [Facebook and Pinterest article author meta tag conflict](http://synapticism.com/pinterest-and-facebook-open-graph-incompatibility-fix/).
* Favicon markup using [best practices for 2014](http://synapticism.com/favicon-best-practices-for-2014/). Generate favicons with [RealFaviconGenerator](http://realfavicongenerator.net/).
* **Be sure to fill out the relevant fields in the configuration file to activate and use this module!**



### Netlabel

* A bunch of functions and taxonomies to help categorize netlabel releases. Might be spun into its own plugin at some point. Will be documented when development matures.



### Places

* Places is a custom taxonomy designed to act as a lightweight geographic database. Each place is a term in a heirarchal taxonomy.
* Place archives act like regular term archives with the addition of breadcrumb navigation and a list of descendent or related places.
* Place shortcode: `[place]Place name[/place]` or `[place slug=placename]`. Will default back to plain text when the place doesn't exist. Makes it easy to reference places in your posts.
* Places sidebar when navigating within the places database (requires configuration).
* Hooks into entry metadata to display places next to tags and categories.
* No custom mapping function at present. Just use simple Google Maps embeds.



### Search

* Smarter HTML5-based search form replacement. Will include current search query in the search box.
* Singleton search results redirect to matching post in one step.
* Specify a different number of results on search pages (defaults to 20).



### Series

* A lightweight implementation of a post series taxonomy. Designed to easily link posts together in a series.
* Inserts an ordered list of posts in a given series at the bottom of posts when viewed individually.
* Series archives themselves are in chronological order (i.e. oldest first).



## Notes

* Fill out your full user profile, blog description, and all category, tag, taxonomy, and other descriptions where available! All the SEO-friendly goodness in this plugin reads from standard WordPress data.
* Flush your permalinks after fiddling around with anything.
* **Read the source code!**
