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
