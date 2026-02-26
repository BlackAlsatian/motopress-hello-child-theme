# Hello Theme Child (Book Inn)

Custom WordPress child theme for site-specific UI and MotoPress Hotel Booking overrides.

## Scope
This repository contains only the child theme in:
- `wp-content/themes/hello-theme-child`

It does **not** include WordPress core, parent themes, plugins, uploads, or environment secrets.

## Parent / Dependencies
- Parent theme: **Hello Elementor**
- Common plugin dependency: **MotoPress Hotel Booking**

## License
This child theme is distributed under **GPL-2.0-or-later**.
See [LICENSE](LICENSE).

## Attribution
- Built on top of the Hello Elementor ecosystem.
- Contains project-specific customizations and template overrides.
- See [NOTICE](NOTICE) for explicit attribution details.

## MPHB Search Form Overrides
- Adults and Children fields are intentionally removed from all site search forms.
- Search requests still submit valid `mphb_adults` and `mphb_children` values via hidden inputs (plugin minimums).
- Override templates:
	- [hotel-booking/shortcodes/search/search-form.php](hotel-booking/shortcodes/search/search-form.php)
	- [hotel-booking/widgets/search-availability/search-form.php](hotel-booking/widgets/search-availability/search-form.php)
	- [hotel-booking/create-booking/search/search-form.php](hotel-booking/create-booking/search/search-form.php)
	- [assets/css/mphb-booking.css](assets/css/mphb-booking.css) (global fallback hide rules)

## Versioning
Use semantic version tags for releases, and keep the WordPress theme version in `style.css` in sync where practical.

Suggested tag format:
- `v2.0.32`

## Local Git Setup
From this folder:

```bash
git init
git add .
git commit -m "Initial child theme baseline"
```

Then connect your remote:

```bash
git remote add origin <your-repo-url>
git branch -M main
git push -u origin main
```
