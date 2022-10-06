# Image Grid With Text

**This is a custom component (no class prefix in place as it does not conflict with bootstrap)**

- This is a companion to the component called `image-grid` which outputs the image grid with room for a headline, text, and button - this should typically be used for output globally
- Contains subcomponents `button-set` and `image-grid`
- This is setup to use raw html for the body section under the healine (tables may be added to this section and will display properly)
- The custom styles are imported into the `index.scss` file as `@import "../components/image-grid-with-text/image-grid-with-text";`
- There is a paragraph-widget wrapper for use in page context `<div class="paragraph-widget paragraph-widget--image-grid-with-text">`.
