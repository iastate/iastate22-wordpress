# Iowa State Wordpress Starter Theme

The starter theme for all ISU WP sites is based on the [Timber Library](https://wordpress.org/plugins/timber-library/) and incorporates the [Iowa State Frontend Library](https://github.com/iastate/iastate22-frontend) for styles, components and functionality. Please refer to that repository for specific information regarding the front-end library, and markup.

This theme utilizes twig for templating, and relies on the Advanced Custom Fields module.

## Installing the Theme

Install this theme as you would any other, and be sure the Timber plugin, and ACF Plugin are activated.

1. Make sure you have installed the plugin for ACF Pro.
2. Download the zip for this theme (or install via composer) and move it to `wp-content/themes` in your WordPress installation.
3. Activate the theme in Appearance > Themes.

### Installing via composer

`composer require iastate/wordpress-theme`

### Updating the frontend library

`composer update iastate/frontend-component-library`

## What's here?

- `inc/` is a folder for theme functions. It serves the purpose of the functions.php file in a more organized way.
- `vendor/iastate/frontend-component-library/` is the [Iowa State Frontend Library](https://github.com/iastate/iastate22-frontend), designed for the Iowa State family of websites. This is controlled by compoiser, and should not be modified here, except for testing.
- `wp_components/` is a Wordpress specific version of the component library. It contains styles specific to the Wordpress interface and editor, that are not a part of the ICL.
- `templates/` contains all of your Twig templates. These pretty much correspond 1 to 1 with the PHP files that respond to the WordPress template hierarchy. At the end of each PHP template, you'll notice a `Timber::render()` function whose first parameter is the Twig file where that data (or `$context`) will be used. Just an FYI.
- `acf_json/` is a json copy of the custom ACF data fields, they're used for syncronization across development environments.
- `bin/` and `tests/` ... basically don't worry about (or remove) these unless you know what they are and want to.

## Other Resources

- The [main Timber Wiki](https://github.com/jarednova/timber/wiki) is super great, so reference those often. Also, check out these articles and projects for more info:
- [This branch](https://github.com/laras126/timber-starter-theme/tree/tackle-box) of the starter theme has some more example code with ACF and a slightly different set up.
- [Twig for Timber Cheatsheet](http://notlaura.com/the-twig-for-timber-cheatsheet/)
- [Timber and Twig Reignited My Love for WordPress](https://css-tricks.com/timber-and-twig-reignited-my-love-for-wordpress/) on CSS-Tricks
- [A real live Timber theme](https://github.com/laras126/yuling-theme).
- [Timber Video Tutorials](http://timber.github.io/timber/#video-tutorials) and [an incomplete set of screencasts](https://www.youtube.com/playlist?list=PLuIlodXmVQ6pkqWyR6mtQ5gQZ6BrnuFx-) for building a Timber theme from scratch.
