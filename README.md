# Ubik

Ubik is a modular suite of WordPress components for developers. Ubik components are lightweight plugins designed for direct integration into WordPress themes using an automated build process such as [Gulp](http://gulpjs.com/) or [Grunt](http://gruntjs.com/) and a package manager like [Composer](https://getcomposer.org) (the "correct" way) or [Bower](http://bower.io/) (a bit more DIY but still doable). The ultimate aim of this project is to improve theme development workflow by making it easy to add standard template tags, code snippets, and hacks that are regularly included in `functions.php` without any of the bloat (option screens, database tables, and so on) that typically ship with regular plugins.

Ubik components do not feature any sort of back-end interface; all settings are managed with simple configuration files populated primarily by PHP constants (and occasionally global arrays where necessary). Again, this project is for *WordPress hackers**, not general end-users, and you'll need to know your way around WordPress, PHP, and the command line to harness the power of Ubik.

Ubik also acts as a kind of curated code base of common WordPress snippets and hacks. Most functions are readily cut and pasted into new contexts. Integrate Ubik components into your theme or rip code out and modify or use it as you see fit (within the terms of the license, of course). Contributions are also welcome, within reason. Feel welcome to open issues if you find any bugs or have any questions!



## Components

Mix and match whichever components you require! Most components are dependency-free. Check `composer.json` for a full list where applicable. Most of these components are in good shape and ready to use but a few remain under development. Read the documentation for each component to get a sense of how mature each one is.

* [Admin](https://github.com/synapticism/ubik-admin): a library of hacks and snippets for the admin panel (in flux).
* [Analytics](https://github.com/synapticism/ubik-analytics): simple Google Analytics functions (stable; no dependencies).
* [Cleaner](https://github.com/synapticism/ubik-cleaner): clean up default installations (stable; no dependencies).
* [Colophon](https://github.com/synapticism/ubik-colophon): a flexible system of generating footer data (stable; no dependencies).
* [Comments](https://github.com/synapticism/ubik-comments): a simple collection of comment-related functions (stable; no dependencies).
* [Excerpt](https://github.com/synapticism/ubik-excerpt): excerpt handling functions (stable; has dependencies).
* [Excluder](https://github.com/synapticism/ubik-excluder): arbitrarily exclude posts from the homepage (stable; no dependencies).
* [Favicons](https://github.com/synapticism/ubik-favicons): favicon support (stable; no dependencies).
* [Feed](https://github.com/synapticism/ubik-feed): a collection of feed-related functions (under development; no dependencies).
* [Fonts](https://github.com/synapticism/ubik-fonts): simple font loading functions (stable; no dependencies).
* [Imagery](https://github.com/synapticism/ubik-imagery): minimalist image management (under development; will be spun out into its own plugin; no dependencies).
* [Lingual](https://github.com/synapticism/ubik-lingual): simple language-related functions (stable; no dependencies).
* [Links](https://github.com/synapticism/ubik-links): expanded links management functionality (experimental; no dependencies).
* [Markdown](https://github.com/synapticism/ubik-markdown): Markdown helper functions (stable; no dependencies).
* [Meta](https://github.com/synapticism/ubik-meta): post metadata management (stable; has dependencies).
* [Photo Meta](https://github.com/synapticism/ubik-photo-meta): display photo metadata and licensing info (stable; no dependencies).
* [Places](https://github.com/synapticism/ubik-places): a simple places taxonomy (stable; has dependencies).
* [Post Formats](https://github.com/synapticism/ubik-post-formats): post format hacks and snippets (stable; no dependencies).
* [Quick Terms](https://github.com/synapticism/ubik-quick-terms): add term descriptions to the quick edit box (stable; no dependencies).
* [RecordPress](https://github.com/synapticism/ubik-recordpress): useful things for netlabels (experimental; has dependencies).
* [Related](https://github.com/synapticism/ubik-related): lightweight related posts (stable; no dependencies).
* [Search](https://github.com/synapticism/ubik-search): a small library of useful search-related functions (stable; no dependencies).
* [SEO](https://github.com/synapticism/ubik-seo): various SEO functions (stable; has dependencies).
* [Series](https://github.com/synapticism/ubik-series): a lightweight post series taxonomy (stable; no dependencies).
* [SVG Icons](https://github.com/synapticism/ubik-svg-icons): SVG icon helper functions (stable; no dependencies).
* [Terms](https://github.com/synapticism/ubik-terms): functions for working with categories, tags, and taxonomies (stable; no dependencies).
* [Text](https://github.com/synapticism/ubik-text): simple text processing functions (stable; no dependencies).
* [Time](https://github.com/synapticism/ubik-time): time and date functions (stable; no dependencies).
* [Title](https://github.com/synapticism/ubik-title): generate document, archive, and entry titles (stable; no dependencies).
* [Views](https://github.com/synapticism/ubik-views): a content template selector (stable; no dependencies).



## Installation

Ubik components can be loaded like any other plugin but are designed to be directly incorporated into WordPress themes using `require_once`. See [Pendrell](https://github.com/synapticism/pendrell) for an example of integration and usage.

Install via Composer:

```composer require synapticism/ubik-[component]```

Install via Bower (warning: no dependency management):

```bower install https://github.com/synapticism/ubik-[component].git -D```



## Configuration

Most Ubik components follow a simple configuration pattern: constants and variables should be set in `functions.php` (or some equivalent in your theme) prior to loading each components. Some functions are wrapped in `function_exists` calls which means you can make small customizations to Ubik components without needing to fork a repo. Functions without `function_exists` are usually pluggable via the [action/filter hook system](http://codex.wordpress.org/Plugin_API/Hooks); remove existing hooks and add your own as needed.

It is also possible to drop configuration files into component directories themselves but this is much more of a roundabout process. Part of the goal of this project is to provide a simple, centralized way to configure a wide variety of commonly deployed components; distributing your configuration into many different files defeats this purpose.

Set constants like so:

```define( 'UBIK_CONSTANT', true );```

Remember to flush your permalinks after messing around with certain components; this is not handled automatically.



## Philosophy

All Ubik components aspire to some simple guidelines (in case anyone would like to contribute or simply understand a little more about what this project is about):

* Keep things simple--Ubik components are meant to be understood at a glance. Anything that requires more complicated architecture may be better suited for a full-fledged plugin.
* Stick to simple procedural functions where possible. This makes it easy to cut and paste functions into other contexts.
* All settings and options are either PHP constants or easily hooked using the WordPress filter/action system. No options pages or other admin panel bloat. Global variables only where strictly necessary.
* Principle of least surprise: features should be switched off by default where possible (unless including a component suggests that it is safe to do otherwise).
* Minimize dependencies. Most components are standalone but there are a few that require other components references in `composer.json`.
* Break feature sets out into individual files for easy identification.
* Only include PHP code. Let themes handle front-end dependency management (e.g. CSS/JS). This requires more work by the developer but avoids that horrible problem in the WordPress world where sites are loading 20 different script and stylesheet fragments.
* Avoid generating markup; give theme authors the raw data they need to implement a particular function wherever possible.
* All functions, constants, variables, and translation-ready strings should be in the `ubik` namespace *e.g.* `ubik_truncate_text` and `__( 'String', 'ubik' )`.
* Shortcodes should include simple fallback functionality (e.g. if the component is deactivated) that can easily be copied and pasted into a "fallback file".
* Comment liberally and in proper English; Ubik's code should be easy to follow.
* Credit original sources where sensible to do so.
* Follow [semantic versioning](http://semver.org/).

Final note: I don't presume that everything you might want to do with WordPress can or should be done this way. Ubik deviates from what you might call "the WordPress way" but it isn't meant to be a political statement of any kind.

If you'd like to contribute by identifying issues or submitting pull requests, please do!



## License

GPLv3.
