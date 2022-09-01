# Link Set

**This is a custom component (no class prefix in place as it does not conflict with bootstrap, although the "link-seondary" subcomponent is prefixed "iastate22-link-secondary")**

- This is a companion component to `link-secondary`, allowing for multiple instances of the button to be output
- By adding either `"iastate22-link-secondary"` or `"link-tertiary"` in the config file as an option of `"variant"`: - the secondary or tertiary link styles will be pulled, if link is pointing to any site pointing outside of `https://www.iastate.edu/`, the external link icon will be added with no option needed for the `"variant"`
- For the arrow on the right side, the markup `<span class="arrow"></span>` is required after the button text
- The custom styles are imported into the `index.scss` file as `@import "../components/link-set/link-set";`
- There is a paragraph-widget wrapper for use in page context `<div class="paragraph-widget paragraph-widget--link-set">`.
