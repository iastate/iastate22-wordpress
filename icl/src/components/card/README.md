# Card

**This is a custom component (the class has been prefaced with `.iastate22-[classname]`).**

- Images within the `card__media` element will be inline `img` elements and are sized using the css `object-fit` property (a polyfill is included for older browsers)
- The `image` and `link` fields are optional
- There is a companion component associated called `card-set` which outputs multiple instances of the cards, this should typically be used for output globally
- By deafult, the entire card is clickable. This global setting can be overridden on a per-component basis by applying the `data-clickable` attribute with either `true` or `false` as its value
- The `card--profile-with-modal` variant is a structured version for profile content which has an associated modal window that will appear on click
- The `card--with-modal` variant is a more unstructured version of the card that has an associated modal window that appears on click. For this version the following elements are optional: image, title, modal image, modal title, and modal link set.
  — The `card--teaser-news` and `card--teaser-profile` variants are intended to be used to display previews of posts (news and profiles)
- The custom styles are imported into the `index.scss` file as `@import "../components/card/card";`
- The `.ts` file for the javascript is imported into the `index.ts` file as `import cardsInit from "./components/card";` and called as `cardsInit();`
