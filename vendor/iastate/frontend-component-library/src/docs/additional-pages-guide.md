---
title: Additional Pages Guide
---

**Table of Contents**

1. Admission & Aid
2. Majors, Minors and Certificates
3. College Template
4. A-Z Index
5. Search

<hr>
<br>

# Admission & Aid

## Hero Section

- This is a custom full-width hero section
- Uses breadcrumb variant `breadcrumb--top-level-pages`
- Contains a hero image (two versions required: one sized for mobile and another for tablet/desktop). Images within the `admission-aid-hero__media` element will be inline `img` elements and are sized using the css `object-fit` property (a polyfill is included for older browsers).
- The svg background is custom for this particular page, and is not intended to animate
- Contains an `h1` page title
- The sub-navigation (appears below the hero image) is an instance of the `subnav--horizontal` component

## Explore Section

- Contains an `h2` title and intro section (which is set to output raw HTML)
- Custom section for this page
- The video section is set to display a YouTube video, using an instance of the `video-embed` component (includes wallpaper image and caption)

## Why Iowa State University Section

- Custom section for this page
- Contains an `h2` title
- The callout sections below the title are custom to this page and contain an image, title and tertiary link (`.link-tertiary`)
- The statistic section with room for multiple statistics is custom to this page (the Kitchen Sink `statistic` component only contains room for one statistic)

## Afford Section

- Custom section for this page
- Contains an `h2` title and intro section (which is set to output raw HTML)
- The link below the title and intro is an instance of the `link-set` component, this is showing the "external link" icon since the page is pointing away from `https://www.iastate.edu/`
- The column section is using a variant of the `multi-column-content` component, called `multi-column-content--alternative`. This is a structured version of the component which includes output for the icon, title, copy and link, as well as including the dividing lines between the columns.

## Apply Section

- Modified version of the `callout-inset` component, which features a button in the first "inset" part, and secondary links in the bottom cta part
- The markup has been pulled from the `callout-inset` component, but since there are differences, the actual component is not in use here

## Links Section

- Custom section for this page
- Contains an `h2` subtitle and `h3` title
- Contains multiple links in columns, with each containing an icon and link text

## Connect Section

- Custom section for this page
- Contains an `h2` title
- Contains social media link section
- Contains a custom column section, each column containing `h3` titles, description copy (outputtin raw HTML), and an instance of the `link-set` component

<br>
<hr>
<br>

# Majors, Minors and Certificates

## Hero Section

- This is an instance of the `hero--top-level-lp` component
- Does not contain the horizonatl sub-navigation (optional within the `hero--top-level-lp` component)

## Text With Statistic Section

- The statistic section (with surrounding text) is an instance of the `statistic-with-text--right` component

## Explore Programs Section

- Custom section for this page
- Contains an `h2` title
- Contains link section, an instance of the `button-set` component

## Find An Undergraduate Program Section

- Custom section for this page
- The form elements have been mocked up for styling purposes, contains an `h2` title, label, input and submit button
- In the listings section, each listing contains a secondary link (`.iastate22-link-secondary`) with the linked program name, and a `p` which outputs the program type

## Call To Action Section

- This call to action section is an instance of the `call-to-action--small` component

<br>
<hr>
<br>

# College Template

## Hero Section

- This is a custom full-width hero section for the college pages
- Uses breadcrumb variant `breadcrumb--top-level-pages`
- Contains a hero image (two versions required: one sized for mobile and another for tablet/desktop). Images within the `college-hero__media` element will be inline `img` elements and are sized using the css `object-fit` property (a polyfill is included for older browsers).
- The svg background is custom for this particular page, and is not intended to animate
- Contains an `h1` page title
- The sub-navigation (appears below the hero image) is an instance of the `subnav--horizontal` component

## Overview Section

- Custom section for this page
- Contains an `h2` title, intro text (which outputs raw HTML), and an image

## In-Page Nav

- Custom section for this page
- The In-Page navigation section contains anchor links to quickly go to individual sections of the college page
- The links are tertiary links (`.link-tertiary`)

## Majors, Minors and Certificates (What Can I Study) Section

- Custom section for this page
- Contains an `h2` title, intro text (which outputs raw HTML), an `h3` subhead and an image, as well as the listing section
- In the listings section, each listing contains a secondary link (`.iastate22-link-secondary`) with the linked program name, and a `p` which outputs the program type

## Research Section

- Custom section for this page
- Contains an `h2` title, intro text (which outputs raw HTML), an instance of the `button-set` component, and an instance of the `external-news-story` component

## Innovation and Entrepreneurship Section

- Custom section for this page
- Contains an `h2` title, intro text (which outputs raw HTML), an instance of the `button-set` component, and an instance of the `external-news-story` component

## Opportunities Section

- This section is an instance of the `callout-inset` component

## Success Section

- Custom section for this page
- Contains an `h2` title, intro text (which outputs raw HTML), an instance of the `link-set` component, a list of career paths (output in a `ul`), and a featured news story - which contains an `h3` title an instance of the `card--profile-with-modal` component

## Call To Action Section

- Custom section for this page
- Contains two calls to action and a large image
- Each call to action contains an `h2` title, intro text (which outputs raw HTML), and an instance of the `button-set` component

## Keep Exploring Section

- This section is an instance of the `call-to-action--small` component

<br>
<hr>
<br>

# A-Z Index

## Hero Section

- A custom hero section `.red-bkgrnd-hero` is in use for this page, as well as the search page
- Uses breadcrumb variant `breadcrumb--top-level-pages`
- Contains an `h1` title and message text (which outputs raw HTML)

## Letter Section

- This section links to each letter of the alphabet for each part of the index
- If the letter has an entry it will appear as a link, if the letter does not have any entries it will appear greyed out, using a `span` instead of a link
- A `current_page` attribute is set in the config file, if set to `true` the current page's letter will appear with an active state (similar to the hover state of the other links)

## Entry List Section

- This section lists out all of the index entries per letter (depending on which page the user is currently viewing)
- The current letter being displayed is output above the listings in an `h2` tag
- Each entry appears as a secondary-link (`.iastate22-link-secondary`)

<br>
<hr>
<br>

# Search

## Hero Section

- A custom hero section `.red-bkgrnd-hero` is in use for this page (also in use on the A-Z Index page)

## Search Section

- The search in use on this page is an instance of [Google Programmable Search](https://programmablesearchengine.google.com/)
- The form and results are wrapped in div with a class of `search-wrapper`, an `h3` search label has been added above the input field
- The account used to instantiate the search is a personal account (for styling purposes), the script source should be swapped out for an official Iowa State University instance when used for production
- Some of the settings in the [control panel](https://programmablesearchengine.google.com/) will need replicated in order to have this appear properly:
  - Under the Look & Feel tab: Layout: this needs to be set to Full width instead of the default Overlay
  - Under the Look & Feel tab: Thumbnails: Automatic thumbnails in search results should be turned off
  - Under the Look & Feel tab: Customize: Google Branding: Google Branding should be set to Disable
- Ads will be visible in the search results, however Iowa State University should be able to use non-profit status to hide the ads from the results
  [Google Support Article: Information for non-profits](https://support.google.com/programmable-search/answer/4542102?hl=en&ctx=topic)
