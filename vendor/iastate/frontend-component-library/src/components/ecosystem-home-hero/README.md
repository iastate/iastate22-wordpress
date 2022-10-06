# Ecosystem Home Hero

**This is a custom component (no class prefix in place as it does not conflict with bootstrap)**

- This component contains five variants: `featured-image`, `background-video`, `supporting-image`, `atmospheric-image` and `no-image`
- The left & right margins on these components are set on the root of the element (no extra spacing widgets or elements needed to place into a hero section of a page)
- The `featured-image` variant contains a full-width image with a subtle color overlay, headline and an optional instance of the `button-set` component
- The `background-video` variant contains a full-width image (fallback & visible while the visible is loading), a background YouTube video, headline and an optional instance of the `button-set` component. The config for this variant accepts a YouTube video id and autoplays in the background with no sound or controls. A play/pause button appears in the lower right side of the hero. The video is set to loop by setting the playlist to the same value as the video id. If a video is set to "unlisted" on YouTube it will prevent the related videos from being displayed on pause.
- The `supporting-image` variant contains an image, title and an optional instance of the `button-set` component
- The `atmospheric-image` variant contains an image, title and an optional instance of the `button-set` component
- The `no-image` variant contains a title and an optional instance of the `button-set` component
- The custom styles are imported into the `index.scss` file as `@import "../components/ecosystem-home-hero/ecosystem-home-hero";`
- The `.ts` file for the javascript is imported into the `index.ts` file as `import ecosystemHeroBackgroundVideo from "./components/ecosystem-home-hero";`
