# Hero

**This is a custom component (no class prefix in place as it does not conflict with bootstrap)**

- There are two variants, the `default` for the main site (which places the `subnav` in the top right on desktop), and the `--top-level-lp` variant for for top-level landing pages (contains a modified version of the `subnav` below the main image)
- The image within the `hero__media` element will be inline `img` and is sized using the css `object-fit` property (a polyfill is included for older browsers)
- The `image` field is optional
- Contains two subcomponents: `breadcrumb` and `subnav`
- The custom styles are imported into the `index.scss` file as `@import "../components/hero/hero";`
- The hero component should be placed in the page in the following order relative to the `<main>` element:

```
    <div class="off-canvas">
        <div class="max-bound">
            {% include "@site-header" with header %}
                <main id="main-content">
```
