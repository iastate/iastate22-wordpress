# Accordion

**This is using the standard Bootstrap accordions (with the `.accordion-flush` modifier class to remove certain styles and render the accordions full-width): [Bootstrap Accordion](https://getbootstrap.com/docs/5.1/components/accordion/).**

- There are some additional custom styles being used here, imported into the `.index.scss` file as `@import "_core/accordion";`
- There is a paragraph-widget wrapper for use in page context `<div class="paragraph-widget paragraph-widget--accordion">`.
- The `.ts` file for the javascript is imported into the `index.ts` file as `import accordionsInit from "./components/accordion";` and called as `accordionsInit();`
