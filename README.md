# Ubik

*Ubik is currently in the process of being broken up into smaller modules. Do not install this plugin at this time!*

Ubik is a collection of useful theme-agnostic WordPress snippets, hacks, and utility functions. It is designed to be extremely lightweight and configurable, offering no back-end options screen or user interface of any kind. All settings and options are managed in a simple configuration file and everything is disabled by default. This means you'll need to get your hands dirty and break out a text editor to harness the power of Ubik. In essence, Ubik is a performance-optimized drop-in replacement for dozens of single-function WordPress plugins *or* a library of potentially useful code that can be mined for your own purposes.

These days I am breaking Ubik up into a suit of micro-plugins that can easily be integrated into WordPress theme build systems to require various components. See [Pendrell](https://github.com/synapticism/pendrell) for examples of usage.



## Components

These components were formerly a part of the core Ubik plugin:

* [Admin](https://github.com/synapticism/ubik-admin): a library of hacks and snippets for the admin panel.
* [Analytics](https://github.com/synapticism/ubik-analytics): simple Google Analytics functions.
* [Cleaner](https://github.com/synapticism/ubik-cleaner): clean up default installations.
* [Comments](https://github.com/synapticism/ubik-comments): a simple collection of comment-related functions.
* [Excluder](https://github.com/synapticism/ubik-excluder): arbitrarily exclude posts from the homepage.
* [Feed](https://github.com/synapticism/ubik-feed): a collection of feed-related functions.
* [Imagery](https://github.com/synapticism/ubik-imagery): minimalist image management.
* [Lingual](https://github.com/synapticism/ubik-lingual): simple language-related functions.
* [Markdown](https://github.com/synapticism/ubik-markdown): Markdown helper functions.
* [Meta](https://github.com/synapticism/ubik-meta): meta tag-related functions.
* [Places](https://github.com/synapticism/ubik-places): a simple places taxonomy.
* [Post Formats](https://github.com/synapticism/ubik-post-formats): post format hacks and snippets.
* [Quick Terms](https://github.com/synapticism/ubik-quick-terms): add term descriptions to the quick edit box.
* [RecordPress](https://github.com/synapticism/ubik-recordpress): useful things for netlabels.
* [Search](https://github.com/synapticism/ubik-search): a small library of useful search-related functions.
* [Series](https://github.com/synapticism/ubik-series): a lightweight post series taxonomy.
* [Terms](https://github.com/synapticism/ubik-terms): functions for working with categories, tags, and taxonomies.



## Installation

Copy or symlink `ubik/ubik` to your plugins folder, configure, and activate. Be sure to flush your permalinks after installation.

Ubik requires WordPress 3.9 and PHP 5.3.



## Configuration

Virtually all of Ubik's features are disabled by default to reduce collision with other plugins and themes. There are two ways to activate Ubik's features or change settings:

1. Override default values in your `wp-config.php` file.
2. Rename `ubik-config-sample.php` to `ubik-config.php` and edit the default values.

So, for instance, to activate the meta module add this line to your `wp-config.php` or `ubik-config.php` file:

`define( 'UBIK_META', true );`



## Features

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



## Notes

* Fill out your full user profile, blog description, and all category, tag, taxonomy, and other descriptions where available! All the SEO-friendly goodness in this plugin reads from standard WordPress data.
* Flush your permalinks after fiddling around with anything.
* **Read the source code!**



## License

GPLv3.
