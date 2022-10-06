# Site Header

**This is a custom component (no class prefix in place as it does not conflict with bootstrap)**

- The header is setup to use `:target` when a user has javascript disabled, to allow the hamburger menu to open on mobile and the navs to use hover states on desktop. When javascript is enabled - these actions are initiated by a click (or touch)
- Contains areas for utility nav as well as full navigation (with sections for subpages)
- The search form has been duplicated to get the positioning correct between mobile and desktop
- The site-header should be placed in the code directly after `<div class="max-bound">`
- The custom styles are imported into the `index.scss` file as `@import "../components/site-header/site-header";`
- The `.ts` file for the javascript is imported into the `index.ts` file as `import siteHeaderInit from "./components/site-header";` and called as `siteHeaderInit();`
- The `site-header--ecosystem` variant is set up differntly to the default `site-header`. A section for a parent unit name appears close to the logo (on mobile it's visible when the nav is open, on desktop it's always visible. The "Of Science And Technology" tagline is removed from the logo to allow for this to be in it's place. A button can be output in the utility navigation to the right of the search button (on mobile it appears above the search field). The primary navigation on deskton is moved down and has a background color - the website title appears in the left portion of this navigation. The primary navigation is set to wrap when the website title or number of links is long enough to warrant two lines.
- The `site-header--ecosystem` variant contains several optional elements: the Parent Unit Name, Utility Navigation, Search Option, Button in the Utility Navigation and the Main Navigation are all optional
