# Carousel

**This is a custom component (the class has been prefaced with `.iastate22-[classname]`).**

- The carousel uses the [flickity](https://flickity.metafizzy.co/) library
- The image is added as a background image
- Optional elements are the caption, title and body sections and the wallpaper image (if using a video in a slide)
- The slide allows for use of either a YouTube or Vimeo video called by using the appropriate video ID and selecting a service in the config. If no wallpaper image is supplied, the default wallpaper image from either YouTube or Vimeo will be displayed.
- The custom styles are imported into the `index.scss` file as `@import "../components/carousel/carousel";`
- The `.ts` file for the javascript is imported into the `index.ts` file as `import carouselsInit from "./components/carousel";` and called as `carouselsInit();`
