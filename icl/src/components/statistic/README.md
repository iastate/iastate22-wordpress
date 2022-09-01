# Statistic

**This is a custom component (no class prefix in place as it does not conflict with bootstrap)**

- This is a companion component to `stat`, allowing for room for text (and floats the statistic box left or right) - this should typically be used for output globally
- There are two variations, the `default` floats the statistic box to the left, and the `--right` variant floats the statistic box to the right
- The text portion of the component surrounding the statistic allows for raw html (tables may be added to this section and will display properly)
- The custom styles are imported into the `index.scss` file as `@import "../components/statistic/statistic";
- There is a paragraph-widget wrapper for use in page context `<div class="paragraph-widget paragraph-widget--statistic-with-text">`.
