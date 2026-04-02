# WP Astro

A WordPress theme designed to serve as a headless CMS backend for [Astro](https://astro.build)-based frontends. Built to work alongside [hozt/astro-wordpress](https://github.com/hozt/astro-wordpress).

**Author:** Jeffrey Haug — [hozt.com](https://hozt.com)
**License:** MIT

---

## Overview

WP Astro turns WordPress into a structured content backend. It exposes content via WPGraphQL, registers custom post types and taxonomies, and provides an admin UI tailored for headless workflows — including a one-click "Publish Site" button that triggers a webhook to rebuild your Astro frontend.

The theme is intentionally minimal on the front end (it redirects all traffic to the admin) and focused on the admin experience, content modeling, and GraphQL API surface.

---

## Requirements

- WordPress 6.0+
- PHP 8.1+
- [WPGraphQL](https://www.wpgraphql.com/) plugin
- Node.js 18+ (for building theme assets)

### Recommended Plugins

- [WPGraphQL](https://wordpress.org/plugins/wp-graphql/) — required for GraphQL API
- [JWT Authentication for WP-API](https://wordpress.org/plugins/jwt-authentication-for-wp-rest-api/) — required for secure headless auth
- [WP Mail SMTP](https://wordpress.org/plugins/wp-mail-smtp/) — recommended for transactional email

---

## Installation

1. Upload the `wp-astro` folder to `/wp-content/themes/`
2. Activate the theme in **Appearance > Themes**
3. Install and activate WPGraphQL
4. Configure theme settings under **Appearance > Customize**

### Build Assets

```bash
npm install
npm run dev   # development watch
npm run prod  # production build
```

---

## Configuration

All settings are in **Appearance > Customize** under the Site Identity section:

| Setting | Description |
|---|---|
| **Website URL** | Your Astro frontend URL (e.g. `https://example.com`). Used for CORS, the editor link, and loading the frontend stylesheet in the admin. |
| **Publish Webhook** | Webhook URL to trigger when "Publish Site" is clicked. Use your Astro/CI deploy hook. |
| **Editor Key** | Auto-generated on each publish. Used by the frontend to enable edit mode. |
| **Mobile Logo** | Logo variant for smaller screens. |
| **Default Header Image** | Fallback header image for posts/pages that don't have one set. |
| **Default Featured Image** | Fallback featured image for posts/pages. |

### CORS

CORS is pre-configured to allow `http://localhost:4321` (Astro default) and `http://localhost:8000` for local development, plus the **Website URL** from the Customizer.

To add additional origins, use the `wp_astro_cors_origins` filter in a mu-plugin or child theme:

```php
add_filter('wp_astro_cors_origins', function(array $origins): array {
    $origins[] = 'https://preview.example.com';
    return $origins;
});
```

---

## Custom Post Types

| Post Type | Slug | Description |
|---|---|---|
| Gallery | `gallery` | Image galleries with sortable gallery images meta field |
| Testimonial | `testimonial` | Customer/client testimonials with tagging |
| FAQ | `faq` | Frequently asked questions, organized by topic sections |
| Event | `event` | Dated events with sticky/featured support |
| Video | `video` | Video content with URL meta field |
| Portfolio | `portfolio` | Portfolio items with categories and additional image |
| Private | `private` | Private/members-only content |
| Other | `other` | Generic flexible post type with tagging |
| Form | `form` | Form definitions |
| Template | `template` | Reusable content templates with footer content field |

---

## Custom Taxonomies

| Taxonomy | Post Type | Description |
|---|---|---|
| `faqs` | FAQ | Hierarchical FAQ topic sections |
| `portfolio_category` | Portfolio | Flat portfolio categories |
| `testimonial_tag` | Testimonial | Tags for testimonials |
| `other_tag` | Other | Tags for Other post type |

Standard WordPress `category` taxonomy is extended with a **Hide Listing Page** field, queryable via GraphQL.

---

## Custom Meta Fields

| Field | Post Types | Description |
|---|---|---|
| Video URL | Video, Post | External video URL |
| Link URL | Portfolio | External link for portfolio items |
| Gallery Images | Gallery, Other, Portfolio | Sortable gallery image list (stores attachment IDs) |
| Featured/Sticky | Testimonial, Event, Portfolio, Video, Gallery | Pins item to top of listings |
| Additional Image | Portfolio | Secondary image for portfolio items |
| Banner Image | Page, Post | Override header image per post/page |
| Banner Subtitle | Page, Post | Subtitle displayed under page title |
| Content Footer | Template | Footer content block for templates |
| Is Stylesheet | Media | Marks a media item as a CSS stylesheet |

---

## GraphQL API

WP Astro extends the WPGraphQL schema with additional fields and types.

### `customSiteSettings` Query

Returns site-wide configuration for the Astro frontend:

```graphql
query {
  customSiteSettings {
    siteTitle
    tagLine
    logo { sourceUrl altText }
    mobileLogo { sourceUrl altText }
    faviconUrl
    faviconLogo { sourceUrl }
    defaultHeaderImage { sourceUrl altText }
    defaultFeaturedImage { sourceUrl altText }
    authorDisplayName
    editorKey
    enabledFeatures
  }
}
```

### Extended Category Fields

```graphql
query {
  categories {
    nodes {
      name
      hideListingPage   # Boolean — suppress this category's archive page on the frontend
    }
  }
}
```

### Extended MediaItem Fields

```graphql
query {
  mediaItems(where: { isStylesheet: true }) {
    nodes {
      sourceUrl
      title
    }
  }
}
```

### Menu Order Support

Custom post types support WordPress menu order (drag-to-sort) and expose this through WPGraphQL via `menuOrder`.

---

## Admin Features

### Feature Toggles

A dashboard widget lets admins enable/disable sections of the admin UI. Active features control which post type menus appear and which capabilities are accessible to non-super-admin users.

Available feature flags: `posts`, `galleries`, `testimonials`, `forms`, `faqs`, `events`, `videos`, `portfolios`, `podcasts`, `private`, `others`, `backups`, `import`, `redirects`, `tools`, `settings`, `users`, `scf`

### Publish Site

A **Publish Site** button in the admin menu triggers a POST request to the configured webhook URL. On each publish, a new `editor_key` is generated and stored — the frontend uses this token to enable the editor overlay.

### Custom Sort

Post types with menu order support include a drag-and-drop sort interface in the admin list view.

---

## Customizer Theme Mods Reference

| Mod Key | Type | Description |
|---|---|---|
| `website_url` | URL | Astro frontend URL |
| `publish_webhook_url` | URL | Deploy/rebuild webhook |
| `editor_key` | String | Auto-generated editor session key |
| `mobile_logo` | Image URL | Mobile logo attachment URL |
| `header_image` | Image URL | Default header image attachment URL |
| `default_featured_image` | Image URL | Default featured image attachment URL |

---

## Related

- [hozt/astro-wordpress](https://github.com/hozt/astro-wordpress) — Astro integration that consumes this theme's GraphQL API

---

## License

MIT — see [LICENSE](LICENSE)
