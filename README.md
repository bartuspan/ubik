# Ubik

Ubik is a collection of useful theme-agnostic WordPress snippets, hacks, and utility functions broken up into many modular components. All these components ship as WordPress micro-plugins and are meant to be integrated into themes using a build system such as Gulp/Grunt in combination with a package manager like Bower (working) or Component (untested). Each component is designed to be extremely lightweight and highly configurable, offering no back-end options screen or user interface of any kind. All settings and options are managed with simple configuration files populated primarily by PHP constants (and occasionally arrays where such functionality is necessary). This means you'll need to get your hands dirty and break out a text editor to harness the power of Ubik. In essence, Ubik is a performance-optimized drop-in replacement for dozens of single-function WordPress plugins *or* a library of potentially useful code that can be mined for your own purposes.

This master plugin contains all core functions that Ubik components rely on. It must be incorporated into your theme (or activated as a plugin) for all components to function properly.



## Components

Mix and match whichever components you require:

* [Admin](https://github.com/synapticism/ubik-admin): a library of hacks and snippets for the admin panel.
* [Analytics](https://github.com/synapticism/ubik-analytics): simple Google Analytics functions.
* [Cleaner](https://github.com/synapticism/ubik-cleaner): clean up default installations.
* [Comments](https://github.com/synapticism/ubik-comments): a simple collection of comment-related functions.
* [Excerpt](https://github.com/synapticism/ubik-excerpt): excerpt handling functions.
* [Excluder](https://github.com/synapticism/ubik-excluder): arbitrarily exclude posts from the homepage.
* [Favicons](https://github.com/synapticism/ubik-favicons): favicon support.
* [Feed](https://github.com/synapticism/ubik-feed): a collection of feed-related functions.
* [Imagery](https://github.com/synapticism/ubik-imagery): minimalist image management.
* [Lingual](https://github.com/synapticism/ubik-lingual): simple language-related functions.
* [Markdown](https://github.com/synapticism/ubik-markdown): Markdown helper functions.
* [Meta](https://github.com/synapticism/ubik-meta): post metadata management.
* [Places](https://github.com/synapticism/ubik-places): a simple places taxonomy.
* [Post Formats](https://github.com/synapticism/ubik-post-formats): post format hacks and snippets.
* [Quick Terms](https://github.com/synapticism/ubik-quick-terms): add term descriptions to the quick edit box.
* [RecordPress](https://github.com/synapticism/ubik-recordpress): useful things for netlabels.
* [Related](https://github.com/synapticism/ubik-related): lightweight related posts.
* [Search](https://github.com/synapticism/ubik-search): a small library of useful search-related functions.
* [SEO](https://github.com/synapticism/ubik-seo): various SEO functions.
* [Series](https://github.com/synapticism/ubik-series): a lightweight post series taxonomy.
* [Terms](https://github.com/synapticism/ubik-terms): functions for working with categories, tags, and taxonomies.
* [Time](https://github.com/synapticism/ubik-time): time and date functions.
* [Title](https://github.com/synapticism/ubik-title): generate document, archive, and entry titles.



## Installation

Ubik components feature raw PHP code that can be loaded as plugins or incorporated directly into your theme using `require_once`. See [Pendrell](https://github.com/synapticism/pendrell) for examples of usage.



## Configuration

Ubik components follow a simple configuration pattern. Constants and variables can be set in `functions.php` (or some equivalent in your theme) prior to loading components. All functions are wrapped in `function_exists` calls which means you can make small customizations to Ubik components without needing to fork a repo. It is also possible to drop configuration files into component directories themselves but this is much more of a roundabout process. Part of the goal of this project is to provide a simple, centralized way to configure a wide variety of commonly deployed components; distributing your configuration into many different files defeats this purpose.

Set constants like so:

```define( 'UBIK_CONSTANT', true );```

Remember to flush your permalinks after messing around with Ubik; this is not handled automatically.



## Features

The core Ubik plugin is home to a common library of functions that the various components depend on:

* `is_categorized()` conditional: an adaptation of the "uncategorized blog" function from `_s`. This version allows you to explicitly declare that your blog uses no categories.
* `ubik_popular_terms()` and `ubik_popular_terms_list()` for retrieving only the most popular terms in a taxonomy.
* `ubik_truncate_text()` for trimming excerpts and meta descriptions.



## License

GPLv3.
