# Video Embed

**This is a custom component (no class prefix in place as it does not conflict with bootstrap)**

- The component allows for use of either a YouTube or Vimeo video called by using the appropriate video ID and selecting a service in the config. If no wallpaper image is supplied, the default wallpaper image from either YouTube or Vimeo will be displayed. When the play button is clicked, the wallpaper image disappears and the video begins playing
- The `wallpaper` and `caption` fields are optional
- The custom styles are imported into the `index.scss` file as `@import "../components/video-embed/video-embed";`
- There is a paragraph-widget wrapper for use in page context `<div class="paragraph-widget paragraph-widget--video-embed">`.
- The `.ts` file for the javascript is imported into the `index.ts` file as `import videoEmbedInit from "./components/video-embed";` and called as `videoEmbedInit();`
