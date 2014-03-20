# Ubik 0.1.0

Ubik is my personal library of theme-agnostic snippets for WordPress.

## Installation

Copy `ubik/ubik` to your plugins folder and activate.

Alternately, you can edit the `wpdev` variable specified in `gulpfile.js` and build your own copy.

Be sure to update your permalinks.

## Configuration

There is no options screen. Edit `ubik-config-sample.php` and rename to `ubik-config.php` to change default values.

## Features

Merely a sampling:

### HACKS

* Human-readable dates on recent entries (e.g. posted 21 hours ago).
* Optional "strict" slugs; removes non-Latin characters (designed for use with bilingual English/Chinese post titles).
* Post format rewrite: change the base ("type" to whatever) and slugs ("quote" to "quotation").
* Dequeues the default Open Sans stylesheet.
* Singleton search results redirect to matching post in one step.
* Thumbnail fallback: if a featured image isn't set the appropriately-sized thumbnail of the first image will be displayed.
* Reset admin-side HTML editor to a nicer font size and stack.
* Adds Google Analytics to the footer.

### PLACES

Places are a Custom Post Type for building a geographic database. This is still in development and very new. I am building it mainly for my own use and may transform it into a proper WordPress plugin at some point.

Places also includes a simple shortcode: `[place]Place name[/place]` or `[place slug=placename]`.

### SERIES

There is a lightweight series taxonomy for linking posts together. Usage is pretty much self-explanatory.

## ISSUES

* Shortcodes don't work in the excerpt or on the feed.
* Placeholder limitation doesn't work on places shortcode queries.
