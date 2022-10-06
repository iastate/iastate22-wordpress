---
title: Homepage Guide
---

#Homepage Sections:

1. Header (homepage variation)
2. Hero
3. Announcement Alert
4. Community
5. Blockquote
6. Academics
7. Solutions
8. Location
9. Events & News
10. Social
11. Resources
12. Calls To Action
13. Campus Photo

<hr>
<br>

# Header (homepage variation)

- The header on the homepage has slightly different styles compared to the interior pages
- The markup is identical to interior pages, a class of `.home` has been applied to the `<body>` element, to scope the modified styles in the `_site-header.scss` file
- The white background is replaced by a shadow gradient at the top for this version of the header, on scroll the white background and bottom border is visible

<hr>
<br>

# Hero

**Parent class**: `.home-hero`

- Contains a hero image (two versions required: one sized for mobile and another for tablet/desktop). Images within the `home-hero__media` element will be inline `img` elements and are sized using the css `object-fit` property (a polyfill is included for older browsers).
- Contains a headline section with a class of `.home-hero__title`, the element is set to output raw html. In this example, the `<span>` elements are used to change the text color from white to gold to highlight certain parts of the text
- Contains a single button element, the `button--reverse` component
- A background SVG animation behind the title is using a SMIL `<animate>` tag to morph the shapes between points. A play/pause button has been added to the bottom right to allow users to stop the animation (this is also for accesibility purposes). When a user has a system setting enabled on their machine to `prefer reduced motion` the animation will not play. The play/pause button javascript is the class of `PlayPauseAnimation` in the `home.ts` file

<hr>
<br>

# Announcement Alert

**Parent class**: `.home_announcement_alert`

- (please see refer to the home-announcement component notes section for detailed notes)
- This is an optional section

<hr>
<br>

# Community

**Parent class**: `.home-community`

- Section contains room for a subhead, headline, statistic, photography, and a callout with it's own headline, intro copy and button (an instance of the `button` component)
- The inline `img` elements are sized using the css `object-fit` property (see the home hero section for more info if needed), there is room for three total images, two of which are hidden on mobile and become visible again on tablet sizes

<hr>
<br>

# Blockquote

**Parent class**: `.home-iastate22-blockquote`

- The homepage blockquote section differs from the standard kitchen sink blockquote component as it contains a video modal (the markup is different), some of the spacing is also different to accomodate the layout
- A play button is visible on top of the portrait, on click ths pops open a modal window and auto-plays a youtube video, added with the video's corresponding id. When the modal is closed, the video is automatically paused
- The modal window uses a third-party library (which is lightweight and very accessible) called [Micromodal.js](https://micromodal.vercel.app/) (see the notes in the `modal` component for more info)
- If a user does not have javascript enabled, the play button is hidden as the modal will not be able to be opened

<hr>
<br>

# Academics

**Parent class**: `.home-academics`

- The academics section contains room for a headline, intro copy, subhead, set of links and an image grid
- As many links as needed can be added to the link set
- The image grid appears similar to the `image-grid` component, however the markup differs as each image is a linked card with image and title instead of the standard non-linked image in the component. A new variant of the `card` component called `card--linked-image` has been created and used here. Each image card is fully clickable

<hr>
<br>

# Solutions

**Parent class**: `.home-solutions`

- The solutions section contains room for a headline, intro copy and three cards with modal pop-up windows associated with each respective card
- Each card is an instance of the variant to the `cards` component called `card--profile-with-modal`, which contains the associated modal
- On mobile the cards are displayed in a carousel, using the third-party [Flickity](https://flickity.metafizzy.co/) carousel plugin. This is destroyed on desktop and becomes a grid of cards at this size, see the class `FeaturedStoryCarousel` in the `home.ts` file
- The modal window uses a third-party library (which is lightweight and very accessible) called [Micromodal.js](https://micromodal.vercel.app/) (see the notes in the `modal` component for more info)

<hr>
<br>

# Location

**Parent class**: `.home-location`

- This section contains room for a large image, caption and link
- The inline `img` element and are sized using the css `object-fit` property (see the home hero section for more info if needed)

<hr>
<br>

# Events & News

**Parent class**: `.home-events-news`

- This section contains a headline, and individual sections for news and events items
- On mobile the events block and news block are separated and stacked, with a link below each section to view more of each type of item
- On desktop the news and events are combined into a new list alternating between news and events beside each other, and the mobile (individual lists) are hidden. The lists are combined using javascript, see the class `NewsAndEvents` in the `home.ts` file
- On desktop the masonry layout is accomplished using the third-party js library [Masonry](https://masonry.desandro.com/). This is used to absolutely position the elements of differing heights up next to one another, also in the class `NewsAndEvents` in the `home.ts` file
- The events blocks contain room for a date and a linked title
- The news blocks contain a linked title and date, as well as an image. The image is added with javascript using the data attribute `data-image`, these are hidden on mobile and visible on desktop
- On the desktop version of the combined lists, each event "card" is fully clickable
- When a user has javascript disabled, the fallback will display the individual `<ul>` for events and one for news in a two-colum layout

<hr>
<br>

# Social

**Parent class**: `.home-social`

- This section contains room for a headline, linked social media icons, and three hand-picked images
- The images are not pulled directly from a feed but must be manually entered, they are not linked
- On mobile only one image is visible and on tablet and desktop - all three are displayed

<hr>
<br>

# Resources

**Parent class**: `.home-resources`

- This section contains room for a headline, subhead, link set (using the `link-set` component), an additional heading, unique homepage version of the button and one image
- The inline `img` element is sized using the css `object-fit` property (see the home hero section for more info if needed)

<hr>
<br>

# Calls To Action

**Parent class**: `.home-ctas`

- This section contains room for two images, a headline and multiple buttons (using the `button-set` component)
- The inline `img` elements are sized using the css `object-fit` property (see the home hero section for more info if needed)

<hr>
<br>

# Campus Photo

**Parent class**: `.home-full-width-campus-photo`

- This section contains a full width image (two versions required: one sized for mobile and another for tablet/desktop)
- The inline `img` elements are sized using the css `object-fit` property (see the home hero section for more info if needed)
