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

* Dequeues the default Open Sans stylesheet
* Singleton search results redirect to matching post in one step
* Thumbnail fallback: if a featured image isn't set the appropriately-sized thumbnail of the first image will be displayed
* Reset admin-side HTML editor to a nicer font size and stack
