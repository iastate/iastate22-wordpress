# Multi-Column Content

**This is a custom component (no class prefix in place as it does not conflict with bootstrap)**

- There are two variations of the component. The `default` is unstructured in terms of the content input and `multi-column-content--alternative` is more structured and has fields for an icon/image (optional), title, description and tertiary link.
- This component is set up to handle 2 and 3 columns with an additional class of `.multi-column-text-two-col` automatically added if 2 column to allow for custom gap spacing between columns
- The `default` columns are set up to allow for raw html, so any markup (including images) could potentially be placed inside
- The custom styles are imported into the `index.scss` file as `@import "../components/multi-column-text/multi-column-text";`
- There is a paragraph-widget wrapper for use in page context `<div class="paragraph-widget paragraph-widget--multicolumn-text">`.
