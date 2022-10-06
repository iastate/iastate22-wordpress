# Home Announcement

**This is a custom component (no class prefix in place as it does not conflict with bootstrap)**

- Intended to display underneath the hero section of the homepage to alert users to various items/initiatives
- Outputs an icon, along with intro copy and a link
- The component will only display `if` an announcement is present
- There are two variants, `--alert` and `--info`, the only difference being the icon used in the alert, `--alert` shows an exclamation point and `--info` shows a letter "i"
- The custom styles are imported into the `index.scss` file as `@import "../components/home-announcement/home-announcement";`
- There is no paragraph-widget wrapper needed for this component
