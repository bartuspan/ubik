# Ubik 0.4.0

Ubik is my personal library of theme-agnostic hacks and snippets for WordPress. It is designed to be lightweight with no options screen or user interface of any kind. All options are handled by a single configuration file. The goal is to build on core WordPress functionality rather than writing a bloated, sprawling plugin with far too many moving parts. Even so, Ubik does a lot; it's meant to be the Swiss army knife of WordPress plugins.



## Installation

Copy `ubik/ubik` to your plugins folder and activate.

Alternately, you can edit the `wpdev` variable specified in `gulpfile.js` and build your own copy.

Be sure to update your permalinks.



## Configuration

There is no options screen. Most of Ubik's features are disabled by default to reduce collision with other plugins and themes. There are two ways to activate Ubik's features or change settings:

1. Override default values in your `wp-config.php` file.
2. Edit `ubik-config-sample.php` and rename to `ubik-config.php` to change default values.

So, for instance, to activate the meta module add this line to your `wp-config.php` file:

`define( 'UBIK_META', true );`



## Development

Ubik is built with `gulp`.



## Features

Merely a sampling, most of which can be switched on or off in the configuration file:

### Admin

* Reset admin-side HTML editor to a nicer font size and stack.
* "Strict" slugs; removes non-Latin characters (designed for use with bilingual English/Chinese post titles).
* Adds Facebook, Flickr, GitHub, Google+, Instagram, and Twitter to user contact methods; removes AIM, Jabber, and Yahoo.
* Switch for the magic "all settings" hack.
* System-wide shortcode viewer shows you what shortcodes are registered.
* Rich term editor (experimental).

### Category

* Test whether the blog has more than one category (via _s).

### Content

* SEO-friendly title generator.
* Human-readable dates on recent entries (e.g. posted 21 hours ago).
* Standards-compliant structured entry metadata.

### Excerpts

* Nicer, smarter excerpts with sensible defaults.
* Most options are configurable.

### Feed

* Downsize large photos to medium on RSS feeds (experimental).
* RSS feed publication delay (experimental).

### Formats

* Post format rewrite: change the base ("type" to whatever).
* Post format slugs: change the slug ("quote" to "quotation"). Not a great hack; you have to edit the array in `lib/formats.php`.

### General

* Head cleaner; removes a lot of junk from the page header.
* Dequeues the default Open Sans stylesheet.
* Removes the ".recentcomments" style added to the header for no good reason.

### Google Analytics

* Adds Google Analytics code (universal or asynchronous) to the footer.

### Image

* Nicer HTML5-friendly image markup for attachments, image format posts, and image captions. Includes wai-aria and schema.org structured data markup.
* Image shortcode `[image/]` automatically entered in the post editor. This allows for dynamic resizing of the image to suit different contexts. (See the source for more on this.)
* Filter `ubik_meta_image_size` for setting image meta tags. Defaults to 'large' and falls back to the original image. See [Pendrell](https://github.com/synapticism/pendrell) for examples of usage.

### Media

* Thumbnail fallback: if a featured image isn't set the appropriately-sized thumbnail of the first image will be displayed.
* Removes image height and width attributes from many different places for greater responsivity (optional).
* Filters caption shortcode, spitting out better markup using code in the image module (above).
* Turns off comments on all attachments (optional).
* Removes default gallery styling and style injection (possibly redundant if you use WP 3.9's new HTML5 support option).

### Meta

This component is designed to be a lightweight drop-in replacement for most mainstream SEO plugins, most of which I find to be way too top-heavy. The idea here is to define just what is needed and otherwise work silently in the background without adding any cruft to the admin panel.

* SEO-friendly title and description generator (based on code in the content and excerpts modules respectively).
* Supports Facebook/Open Graph, Google+, and Twitter social media meta tags. You will need to activate/verify ownership of all three.
* **Be sure to fill out the relevant fields in the configuration file to activate and use this component!**

### Microdata

* Functions for working with microdata (experimental, inactive).

### Navigation

* Numeric page navigation via bones (experimental, inactive).

### Netlabel

A bunch of functions helpful for netlabels. Might be spun into its own plugin at some point. Will be documented when development matures.

### Places

Places is a custom taxonomy designed to act as a lightweight geographic database. Each place is a term in a heirarchal taxonomy.

* Place archives act like regular term archives but add breadcrumb navigation and a list of descendent or related places.
* Place shortcode: `[place]Place name[/place]` or `[place slug=placename]`. Will default back to plain text when the place doesn't exist. Makes it easy to reference places in your posts.
* Places sidebar when navigating within the places database (requires configuration).
* No custom mapping function at present. Just use simple Google Maps embeds.

### Search

* Singleton search results redirect to matching post in one step.
* Specify a different number of results on search pages (defaults to 20).

### Series

Series is a custom taxonomy designed to link posts in a simple series. Usage is pretty close to self-explanatory but you will need to modify your theme templates to display series properly.

* Inserts an ordered list of posts in the series on single post view.
* Series archives are in chronological order (i.e. oldest first).

### Various

* Allow HTML in author descriptions on single user blogs.
* Page list fallback adds home to the list (via _s).
* Strips "protected" out of protected post titles.
* Jetpack Markdown fenced code block fix. Should be removed for Jetpack 3.0+ after testing.



## Notes

* Fill out your full user profile, blog description, and all category, tag, taxonomy, and other descriptions where available! All the SEO-friendly goodness in this plugin reads from standard WordPress data.
* Flush your permalinks after fiddling around with anything.
