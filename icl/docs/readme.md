Silc is a **S**imple, **I**ntuitive, **L**ibrary of **C**omponents for web developers. Unlike other "frameworks", silc is intentionally bare bones, focusing on functionality over uneccessary styles that you end up overriding later. Silc features purposeful and semantic HTML, minimal "vanilla" JavaScript, and SASS variables for easy customization.

## Modules
Silc contains a number of leightweight modules. Since silc modules are truly modular, any single silc module can be included in your own project using [npm](https://www.npmjs.com/).

### Grid
The grid module is a flexbox-based grid system, that allows you to create classes based on your own custom breakpoints.

[Grid demo](https://cdn.rawgit.com/nickrigby/silc-grid/47742d50a0a039281f1f611d1f5d916ba70a74c3/index.html) | [Grid docs](https://github.com/nickrigby/silc-grid) | [Grid npm package](https://www.npmjs.com/package/silc-grid)

### Accordion
The accordion module has options for pure accordions, accordions that become tabs, and pure tabs.

[Accordion demo](https://cdn.rawgit.com/nickrigby/silc-accordion/9db8b6bcc3fc32660e026ac842ead0ae3644df97/index.html) | [Accordion docs](https://github.com/nickrigby/silc-accordion) | [Accordion npm package](https://www.npmjs.com/package/silc-accordion)

### Offcanvas
The offcanvas module allows you to place content outside of the viewport, with the ability to trigger its visibility.

[Offcanvas demo](https://cdn.rawgit.com/nickrigby/silc-offcanvas/b5341a90e5e89fbcc6c78c5a2f72f01b6d578231/index.html) | [Offcanvas docs](https://github.com/nickrigby/silc-offcanvas) | [Offcanvas npm package](https://www.npmjs.com/package/silc-offcanvas)

### Nav
The nav module allows you to create nested, multi-level navigations, which is collapsed by default — to work on smaller screens — with the ability to expand to a horizontal navigation on larger screens.

[Nav demo](https://cdn.rawgit.com/nickrigby/silc-nav/f8b2deb6b92df1055c694dc4bbdd727e9633f52d/index.html) | [Nav docs](https://github.com/nickrigby/silc-nav) | [Nav npm package](https://www.npmjs.com/package/silc-nav)

### Utilities
The utilities module contains a number of utilities for hiding/showing content and more.

[Utilities demo](https://cdn.rawgit.com/nickrigby/silc-utilities/f2896360fef03f9cacdf879dec8295a3e3a10505/index.html) | [Utilities docs](https://github.com/nickrigby/silc-utilities) | [Utilities npm package](https://www.npmjs.com/package/silc-utilities)

### Core
The core module contains a number of SASS variables/mixins/functions, and a JavaScript classes that are dependencies for other silc modules.

[Core docs](https://github.com/nickrigby/silc-core) | [Core npm package](https://www.npmjs.com/package/silc-core)

## Architecture
Silc uses [webpack 2](https://webpack.js.org/) to bundle and compile assets, [BEM](http://getbem.com/) for modular class definitions, [TypeScript](https://www.typescriptlang.org/) for modern "vanilla" JavaScript, and [SASS](http://sass-lang.com/) for extensible CSS.

## Installation and configuration
View the [Github project](https://github.com/nickrigby/silc) for detailed instructions on installation and configuration. 
