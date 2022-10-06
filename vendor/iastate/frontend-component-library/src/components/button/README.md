# Button

**This is a custom component (the class has been prefaced with `.iastate22-[classname]`).**

- There are two styles for the buttons, the default style with maroon border, white background (and maroon background on hover) and the `--reverse` variant with no border (and white background on hover)
- For the arrow on the right side, the markup `<span class="arrow"></span>` is required after the button text
- The custom styles are imported into the `index.scss` file as `@import "../components/button/button";`
- Typically the button would be placed onto a page using the companion component `button-set`, which allows for multiple instances of the button to be output
