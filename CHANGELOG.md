# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Media wrapper for twig templates

### Changed

- Hard-coded image HTML to use media wrapper

---

## [1.5.0] - 2026-04-30

### Added

- New Block: `Image Collage`
  - [Fractal Reference: Image Collage](https://www.theme.iastate.edu/iastate22/fractal/components/detail/image-collage--default.html)
- New Block: `Image Gallery`
  - [Fractal Reference: Image Gallery](https://www.theme.iastate.edu/iastate22/fractal/components/detail/image-gallery.html)
- New Block: `Home Social`
  - [Fractal Reference: Home Social](https://www.theme.iastate.edu/iastate22/fractal/components/detail/home-social--default.html)
- Many blocks have additional custom editor styles for v3, since "preview" is the only view option now
- Some blocks have a slightly different "Preview" for block editor vs. live view
- ACF inline editor wrappers for Timber

### Changed

- theme.json file migrated to v3
- All ACF Blocks migrated to v3
  - See [issue #72](https://github.com/iastate/iastate22-wordpress/issues/72) for background
- `Profile` taxonomy slugs changed (/_tax-slug_/)
    - `Department` changed from "~~locations~~" -> "**department**"
    - `Affiliation` changed from "~~division~~" -> "**affiliation**"
- `Profile` taxonomy pages now use the same layout as directory list
- Side nav query optimization for `Page Hero` block

### Removed

- Email anchor wrapper for profile email icon, flagged by accessibility

---

## [1.4.1] - 2026-04-02

### Changed

- Moved template logic into Theme class

### Fixed

- Profile taxonomy wasn't showing on the search page

---

## [1.4.0] - 2026-02-23

### Changed

- Package updates (iastate22-frontend: `v1.0.80`)
- HTML template changes synced from "iastate22-frontend"
- Timber context optimization
- Blocks loading functions now in a Class
- Author archive template

### Fixed

- "News - Item" block would not render on public side
- "Video" block did not show values in edit view
- News search filter not filtering on tags
- Profile icons accessibility

### Removed

- Local editor validation script since ACF Pro supports it since 6.4
- Unused template `sidebar.php`

---

## [1.3.6] - 2025-09-10

### Fixed

- Edit/Preview mode not showing in some cases
  - Revert Block API version (`3`-->`2`)
- Author credit toggle
  - `has_field()` did not work as expected for ACF "true/false" fields

### Changed

- Removed minimum for optional button rows in Blocks (Call to Action: Small, Feature Set, Feature with Small Image)

---

## [1.3.5] - 2025-09-02

### Added

- Event post formatted in loop
- Search form context (blog vs. full site)
- Post type for loops
  - Added post label in tag list for full site search
  - Added class for specific post_type

### Changed

- Search form doesn't limit post_type if doing a full search

---

## [1.3.4] - 2025-09-02

### Added

- Post Preview Options
  - Added ACF fields in General Settings (Show Preview, Preview Length, Read More)
  - Added Timber Functions (`get_theme_post_thumbnail()`, `get_theme_post_preview()`)
  - Default visibility is off

### Fixed

- Post loops now format thumbnails and previews the same as Blog Home page
- Hide hero block from excerpt/preview content

---

## [1.3.3] - 2025-09-02

### Fixed

- Show author check did not work as expected for posts with no ACF metadata
- Table block not displaying on public view

---

## [1.3.2] - 2025-08-22

### Added

- Last updated time visibility check for Posts
- Archive banner visibility toggle for Posts
- Theme version in urls for script and style files to help with clearing browser cache on updates

### Changed

- Archive banner check references published date instead of modified date
- HTML alt attribute for images changed to use media library text in blocks (Image grid with text, Callout with image, Callout set)
- Post Option ACF fields
  - Updated text
  - Added fields (archive banner visibility toggle, last updated visibility toggle)
  - Credit override fields are conditional on credit visibility

---

## [1.3.1] - 2025-08-13

### Removed

- Debug calls
- Unused announcement template

### Fixed

- Post options logic (show author, hero image)

---

## [1.3.0] - 2025-08-13

### Added

- `CHANGELOG.md`
- Social menu icons (Discord, Bluesky, ORCiD, Slack, WordPress)
- 3rd party package requirements test before loading
- Twig/Timber macros and conditional templates for easier reusability
- Twig function `is_post_expired` to check if post needs announcement
- Post archive banner for post older than 2 years
- Post/News credit manual override
- Post loop timestamp
- Post announcement style

### Changed

- Timber option menu switched to native WP registration using built in capability permissions check
- Migrated ACF blocks to register natively with WP blocks
- Theme Option menu updated to vertical tab layout instead of horizontal across the top to better use space
- Renamed some block labels to match Theme blocks
- Updated vendor packages
- BREAKING: Video Embed block JSON format changed. Manual update required. [Link to wiki](https://github.com/iastate/iastate22-wordpress/wiki/ACF-Field-Updates#manually-updating-acf-block-data)

### Fixed

- Search custom post-type meta optimization

### Removed

- Unused template `page-faculty.php`
- Unused template `templates/sidebar.twig`

-------

[unreleased]: https://github.com/iastate/iastate22-wordpress/compare/master...develop
[1.3.0]: https://github.com/iastate/iastate22-wordpress/compare/1.2.2...1.3.0
[1.3.1]: https://github.com/iastate/iastate22-wordpress/compare/1.3.0...1.3.1
[1.3.2]: https://github.com/iastate/iastate22-wordpress/compare/1.3.1...1.3.2
[1.3.3]: https://github.com/iastate/iastate22-wordpress/compare/1.3.2...1.3.3
[1.3.4]: https://github.com/iastate/iastate22-wordpress/compare/1.3.3...1.3.4
[1.3.5]: https://github.com/iastate/iastate22-wordpress/compare/1.3.4...1.3.5
[1.3.6]: https://github.com/iastate/iastate22-wordpress/compare/1.3.5...1.3.6
[1.3.7]: https://github.com/iastate/iastate22-wordpress/compare/1.3.6...1.3.7
[1.4.0]: https://github.com/iastate/iastate22-wordpress/compare/1.3.7...1.4.0
[1.4.1]: https://github.com/iastate/iastate22-wordpress/compare/1.4.0...1.4.1
[1.5.0]: https://github.com/iastate/iastate22-wordpress/compare/1.4.1...1.5.0