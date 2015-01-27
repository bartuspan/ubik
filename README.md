# Ubik

Ubik is a modular library of WordPress components: common snippets, hacks, and functions bundled as plugins but designed for deeper integrated with WordPress themes using a build system such as Gulp/Grunt in combination with a package manager like Bower (working) or Component (untested). Each component is designed to be extremely lightweight and highly configurable, offering no back-end options screen or user interface of any kind. All settings and options are managed with simple configuration files populated primarily by PHP constants (and occasionally global arrays where such functionality is necessary). This means you'll need to get your hands dirty and break out a text editor to harness the power of Ubik.

In essence, Ubik is a performance-optimized drop-in replacement for dozens of single-function WordPress plugins *or* a library of potentially useful code that can be mined for your own purposes. Integrate these components into your theme or rip code out and modify or use it as you see fit.



## Components

Mix and match whichever components you require:

* [Admin](https://github.com/synapticism/ubik-admin): a library of hacks and snippets for the admin panel (requires core).
* [Analytics](https://github.com/synapticism/ubik-analytics): simple Google Analytics functions.
* [Cleaner](https://github.com/synapticism/ubik-cleaner): clean up default installations.
* [Comments](https://github.com/synapticism/ubik-comments): a simple collection of comment-related functions.
* [Excerpt](https://github.com/synapticism/ubik-excerpt): excerpt handling functions (requires core).
* [Excluder](https://github.com/synapticism/ubik-excluder): arbitrarily exclude posts from the homepage.
* [Favicons](https://github.com/synapticism/ubik-favicons): favicon support.
* [Feed](https://github.com/synapticism/ubik-feed): a collection of feed-related functions.
* [Imagery](https://github.com/synapticism/ubik-imagery): minimalist image management.
* [Lingual](https://github.com/synapticism/ubik-lingual): simple language-related functions.
* [Links](https://github.com/synapticism/ubik-links): expanded links management functionality.
* [Markdown](https://github.com/synapticism/ubik-markdown): Markdown helper functions.
* [Meta](https://github.com/synapticism/ubik-meta): post metadata management (requires core).
* [Places](https://github.com/synapticism/ubik-places): a simple places taxonomy (requires core).
* [Post Formats](https://github.com/synapticism/ubik-post-formats): post format hacks and snippets.
* [Quick Terms](https://github.com/synapticism/ubik-quick-terms): add term descriptions to the quick edit box.
* [RecordPress](https://github.com/synapticism/ubik-recordpress): useful things for netlabels (requires core).
* [Related](https://github.com/synapticism/ubik-related): lightweight related posts.
* [Search](https://github.com/synapticism/ubik-search): a small library of useful search-related functions.
* [SEO](https://github.com/synapticism/ubik-seo): various SEO functions (requires core).
* [Series](https://github.com/synapticism/ubik-series): a lightweight post series taxonomy.
* [Terms](https://github.com/synapticism/ubik-terms): functions for working with categories, tags, and taxonomies.
* [Time](https://github.com/synapticism/ubik-time): time and date functions.
* [Title](https://github.com/synapticism/ubik-title): generate document, archive, and entry titles.

Components that "require core" must include the functionality with this master plugin. Ubik must be incorporated into your theme (or activated as a plugin) for those components to function properly.



## Installation

Ubik components feature raw PHP code that can be loaded as plugins or incorporated directly into your theme using `require_once`. Where you put the files or how you integrate them is really up to you. Personally I recommend using Gulp and Bower to install and manage Ubik components (but explaining the why and the how is somewhat beyond the scope of this document). See [Pendrell](https://github.com/synapticism/pendrell) for examples of usage.



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



## Philosophy

All Ubik components aspire to some simple guidelines:

* Only procedural code; nothing object-oriented. Procedural code is often easier to follow and more readily copied and pasted into other contexts. Ubik is, first and foremost, a library of snippets and hacks, after all.
* All functions, constants, variables, and translation-ready strings are in the `ubik` namespace *e.g.* `ubik_truncate_text` and `__( 'String', 'ubik' )`.
* All functions are conditionally wrapped in `function_exists` for greater extensibility and customization *except* when those functions are called exclusively by `add_filter` or `do_action` (at which point the `remove_filter` and `remove_actions` functions will allow the same without any additional overhead).
* Follow a modular, not monolithic, design pattern; components are broken out into individual files describing functionality wherever it is sensible to do so.
* Minimize dependencies. Most components are standalone but there are a few that require functions defined in Ubik core (the repo you're looking at right now). If something is close to the base metal and used by more than one component it may qualify for inclusion in Ubik core but I'd really like to keep the core as empty as possible (see previous standard).
* All settings and options are either PHP constants or easily hooked using the WordPress filter/action system. No options pages or other admin panel bloat. Global variables only where strictly necessary.
* Only include PHP code. Let themes handle front-end dependency management (e.g. CSS/JS). This requires more work by the developer but avoids that horrible problem in the WordPress world where sites are loading 20 different script and stylesheet fragments.
* Shortcodes should include simple fallback functionality (e.g. if the component is deactivated).
* Comment liberally and in proper English; Ubik's code should be easy to follow.
* Credit original sources even when adapting code beyond recognition.
* There are no presumptions that everything you might want to do with WordPress can or should be done this way. (And, in fact, Ubik deviates strongly from what you might call "the WordPress way".)

If you'd like to contribute by identifying issues or submitting pull requests, please do!



## License

GPLv3.
