# Image Grid

**This is a custom component (no class prefix in place as it does not conflict with bootstrap)**

- Images within the `image-grid__image` element will be inline `img` elements and are sized using the css `object-fit` property (a polyfill is included for older browsers)
- Contains room for 3 images and an optional `caption`
- There is a companion component associated called `image-grid-with-text` which outputs the image grid with room for a headline, text, and button - this should typically be used for output globally
- The custom styles are imported into the `index.scss` file as `@import "../components/image-grid/image-grid";`
