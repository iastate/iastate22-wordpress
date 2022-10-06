# Card Set

**This is a custom component (the class has been prefaced with `.iastate22-[classname]`).**

- This is a companion component to the `card` component which outputs multiple instances of the cards, this should typically be used for output globally
- This outputs the standard `card` component default, but can output other card variants as well - `card--with-modal` variant by setting `with_modal` to `true` in the config, `card--teaser-news` by setting `teaser_news` to `true` in the config, or `card--teaser-profile` by setting `teaser_profile` to `true` in the config
- The custom styles are imported into the `index.scss` file as `@import "../components/card-set/card-set";`
- There is a paragraph-widget wrapper for these components for use in page context `<div class="paragraph-widget paragraph-widget--card-set">`
