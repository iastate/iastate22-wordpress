# Iowa State University Front End Library

This is a frontent library designed and built for use in Iowa State University web projects.

- Staging site: [staging2.idfive.com](https://staging2.idfive.com/iowa-state-university/fractal/), currently displaying the master branch. Use `guest/guest` for username/password.
- Repo: [Bitbucket private](https://bitbucket.org/idfivellc/iowa-state-frontend/src/master/).
- Build pipelines: [Buddy](https://app.buddy.works/idfive/iowa-state-frontend/), idfive access only.

## Installation of this library into your projects

### Installing via composer

Whenever possible, we reccomend using composer to instaall this library, to ensure all future updates can be pulled in to your project.

`composer require iastate/frontend-component-library`

### Using the library

In general, to use this library in your own project, you would need to link the CSS/JS files from the build folder:

- Link to `/build/css/index.css`
- Link to `/build/css/print.css` (print syles only)
- Link to `/build/js/index.js`

You can then pull, and utilize the provided markup examples in your own project.

## Development

This library is designed to be used "as-is" for most developers. If you wish to modify this library, you will need to create your own fork. This section is strictly information for those working on, and committing to, this project.

The component library includes [Fractal](http://fractal.build) for component based development. Your own components can be added to the `src/components` folder. Static assets such as JavaScript, CSS and images will be served out of the `build` folder, but can also be configured to your specific needs by editing the [fractal.js file](fractal.js). For more information, read the [fractal guide](http://fractal.build/guide).

### General notes:

- Installed Boostrap 5.1, the site uses the default breakpoints
- Using Iowa State color variables
- Using Iowa State Font Awesome for social media icons and various elements
- All CSS/JS is scoped to not interfere with Boostrap, using a prefix of "iastate22-" on relevant classes
- All components live in the `src/components` directory
- The page structure can be found in `src/components/_page-preview.twig`. For individual components, the structure is located in `src/components/_preview.twig`

### Dependencies

Dependencies need to be installed with [node/npm](https://docs.npmjs.com/getting-started/installing-node), and is best pinned to stable versions via [nvm](https://github.com/nvm-sh/nvm). More on [node usage at idfive](https://developers.idfive.com/#/front-end/node).

- `cd idfive-component-library`
- `nvm use`
- `npm install`

### Building for development

To start the fractal development server:

- `cd idfive-component-library`
- `nvm use`
- `npm run fractal`

### Building for production

To build your code for production, prior to a release, run the following:

- `npm run build`
- `npm run fractal:build`

This will generate `build` and `fractal` folders at the root of your project. The `build` folder contains all of your compiled assets (CSS, JavaScript etc.), while the `fractal` folder contains a static generated version of your Fractal component library, which can be used for previews and an online reference to your component library. The `build` folder is committed, in order that projects may use it as a dependency directly.

#### Versioning

Releases for this project should follow [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

Steps when releasing:

- Commit (or merge) and push master to origin.
- Tag `1.0.1` (the new release version), and push to origin. The tag is what composer uses to define a new release.

### Markup

- Written using Twig templates
- Proper ARIA functionality is used to meet WCAG accessibility guidelines

#### Page wrapper and overall structure

This structure below shows the overall wrappers and ordering needed to properly display the layout:

```html
<body>
  <a class="skip-link" href="#main-content">Skip To Main Content</a>
  <div class="off-canvas">
    <div class="max-bound">
      {% include "@site-header" with header %}
      <main id="main-content">
        {{ yield }}
      </main>
      {% include "@site-footer" with footer %}
    </div>
  </div>
  <!-- Scripts Here -->
</body>
```

- The `.skip-link` link is provided for accessibility purposes
- The `.off-canvas` div is in use to set `overflow-x: hidden;` on the whole page to prevent unwanted horizontal scrolling
- Next is the `.max-bound` div, which provides a max-width, (and 100% width), centers the content and provides a background-color the the whole document
- Next our main site header is added `{% include "@site-header" with header %}`
- Then the `<main id="main-content">` is added which contains all of the page content `{{ yield }}`
- After the closing `<main>` tag, the footer is added `{% include "@site-footer" with footer %}`
- Kitchen sink pages have an additional tag `<div class="outer-pad">` directly after `<main>`, which provides left and right padding for the page

### Images

All images should be added to `src/images` which compiles to `build/images/*`.

### CSS/JS

- All CSS/JS/Images will be compiled from `src/*`.
- All CSS to be written as SCSS, and compiled via Webpack.
- All CSS compiled from `src/scss/index.scss`
- All theme JS is written as TypeScript, and compiled to stable, browser-compliant JS via Webpack.
- ALL JS to be compiled from Typescript in `src/js/index.ts`

#### Utility Classes

- In `_base.scss`, `.align-left`, `.align-right`, and `.align-center` handle images placed in WYSIWYG sections
- In `_base.scss`, the `.skip-link` class is for the "Skip To Main Content" button for accessibility
- In `_placeholder-selectors.scss`, `.visible-for-screen-readers` is used to hide content but allow it to be accessibly read/spoken
- In `_placeholder-selectors.scss`, `.outer-pad-x` handles horiztonal padding throughout various parts of the site
- In `_placeholder-selectors.scss`, `%responsive-img` is a placeholder selector which sets up object-fit for images and the padding-top percentage for the associated pseudo element which sets the height of the image
- In `_placeholder-selectors.scss`, `.caption` is a re-used type style for image and video captions
- In `_placeholder-selectors.scss`, `.arrow` is the arrow shape used throughout various button and nav styles
- In `_base.scss`, there are two helper classes for remove the space above and below paragraph widgets. `.paragraph-widget-no-margin-bottom` will reduce the `margin-bottom` of the widget to zero and `.paragraph-widget-no-margin-top` will reduce the `margin-top` top zero. These both have `!important` tags attached to ensure there are no conflicts.

## Acceptance Standards

- [W3C Validation](https://validator.w3.org/)
- Passes Accessibility check using WAVE and Google Lighthouse
- Provides fallback of full content for non-JS users.
