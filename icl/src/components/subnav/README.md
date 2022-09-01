# Subnav

**This is a custom component (no class prefix in place as it does not conflict with bootstrap)**

- There are two variations of the subnav, the default `subnav` is collapsed on mobile and opens up on click, the `--horizontal` variant is used on top-level landing-pages and expands underneath the hero image
- On mobile (for both variants), the menu requires a click (or touch) to be opened, and for the default version on desktop it is open by default
- The subnav is setup to use `:target` when a user has javascript disabled, to allow the nav to open on mobile
- The custom styles are imported into the `index.scss` file as `@import "../components/subnav/subnav";`
- The `.ts` file for the javascript is imported into the `index.ts` file as `import subnavInit from "./components/subnav";` and called as `subnavInit();`
