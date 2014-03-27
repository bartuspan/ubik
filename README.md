# Ubik 0.2.0

Ubik is my personal library of theme-agnostic hacks and snippets for WordPress. It is designed to be lightweight with no options screen or user interface of any kind. All options are handled by a single configuration file. The goal is to build on core WordPress functionality rather than writing a bloated, sprawling plugin with far too many moving parts. Even so, Ubik does a lot; it's like a Swiss army knife.



## Installation

Copy `ubik/ubik` to your plugins folder and activate.

Alternately, you can edit the `wpdev` variable specified in `gulpfile.js` and build your own copy.

Be sure to update your permalinks.



## Configuration

There is no options screen. Edit `ubik-config-sample.php` and rename to `ubik-config.php` to change default values.



## Features

Merely a sampling, most of which can be switched on or off in the configuration file:

### Admin

* Reset admin-side HTML editor to a nicer font size and stack.
* "Strict" slugs; removes non-Latin characters (designed for use with bilingual English/Chinese post titles).
* Adds Facebook, Flickr, GitHub, Google+, Instagram, and Twitter to user contact methods; removes AIM, Jabber, and Yahoo.
* Switch for the magic "all settings" hack.

### Content

* SEO-friendly title generator.
* Human-readable dates on recent entries (e.g. posted 21 hours ago).
* Standards-compliant structured entry metadata.

### Excerpts

* Nicer, smarter excerpts with sensible defaults and several configuration options.

### Feed

* Still under development.

### Formats

* Post format rewrite: change the base ("type" to whatever).
* Post format slugs: change the slug ("quote" to "quotation"). Not a great hack; you have to edit the array in `lib/formats.php`.

### General

* Head cleaner; removes a lot of junk from the page header.
* Dequeues the default Open Sans stylesheet.
* Adds Google Analytics to the footer.

### Media

* Thumbnail fallback: if a featured image isn't set the appropriately-sized thumbnail of the first image will be displayed.

### Meta

* Facebook/Open Graph, Google+, and Twitter social media meta tags. You will need to activate/verify ownership of all three.
* SEO-friendly description generator (based on excerpts).
* Be sure to fill out the relevant fields in the configuration file to activate and use this component.
* Filter `ubik_meta_image_size` for setting image meta tags. Defaults to 'large' and falls back to the original image.

### Places

* Custom Post Type for building a geographic database. Each place is a post-like object in a page-like heirarchy.
* Includes a "place tag" taxonomy for tagging places (e.g. "food", "transportation", "attraction", etc.).
* `ubik_is_place()` conditional function.
* Places post type archive. You can also slip places into the regular flow of posts.
* Special "placeholder" place tag to keep some places out of sight. This should really be a custom post status but the current implementation in WordPress is lacking. Define and use a placeholder if you want to scaffold out a place heirarchy without bothering to polish intermediary places (e.g. you want "Empire State Building" under "Manhattan" but you don't want to fill out Manhattan's info and don't want it to appear in the post type archive or in the regular flow of posts). A bit complex, I know, but the utility of this little hack should be obvious if you start playing around with this feature.
* Places shortcode: `[place]Place name[/place]` or `[place slug=placename]`. Will default back to plain text when the place doesn't exist. Makes it easy to reference places in your posts.
* Lists posts tagged with the place name. This way you can have an index of places that relates back to your travel writing or whatever. Only the slug needs to match.
* Lists related places in a sensible manner. No extra work required.
* Places sidebar when navigating within the places database.
* No custom mapping function at present. I just use simple Google Maps embeds.

### Portfolio

* Not yet properly implemented; what exists is just a bunch of legacy code from an old theme of mine.

### Search

* Singleton search results redirect to matching post in one step.
* Specify a different number of results on search pages (defaults to 20).

### Series

* Lightweight series taxonomy for regular posts. Usage is pretty much self-explanatory.
* Inserts an ordered list of posts in the series on single post view.
* Viewing posts in a series in chronological order.

### Various

* Removes the ".recentcomments" style added to the header for no good reason.
* Allow HTML in author descriptions on single user blogs.
* Ditch default gallery styling.
* Jetpack Markdown fenced code block fix. Should be removed for Jetpack 3.0+ after testing.



## Notes

* Fill out your full user profile, blog description, and all category, tag, taxonomy, and other descriptions where available! All the SEO-friendly goodness in this plugin reads from standard WordPress data.
* Flush your permalinks after fiddling around with anything.



## Issues

* Placeholder limitation doesn't work on places shortcode queries. You can still link to placeholders.
