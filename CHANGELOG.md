# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Announcement editor block (Is a block the right implementation for this?)

---

## [1.3.2] - 2025-08-22

### Added

- Last updated time visibility check for Posts
- Archive banner visibility toggle for Posts
- Theme version in urls for script and style files to help with clearing browser cache on updates

### Changed

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