=== WP Astro ===
Contributors: hozt
Tags: headless, graphql, custom-post-types, astro, cms
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 8.1
License: MIT
License URI: https://opensource.org/licenses/MIT

A WordPress theme designed to serve as a headless CMS backend for Astro-based frontends.

== Description ==

WP Astro turns WordPress into a structured content backend. It exposes content via WPGraphQL, registers custom post types and taxonomies, and provides an admin UI tailored for headless workflows — including a one-click "Publish Site" button that triggers a webhook to rebuild your Astro frontend.

The theme is intentionally minimal on the front end (it redirects all traffic to the admin) and focused on the admin experience, content modeling, and GraphQL API surface.

Built to work alongside [hozt/astro-wordpress](https://github.com/hozt/astro-wordpress).

= Requirements =

* WordPress 6.0+
* PHP 8.1+
* WPGraphQL plugin
* Node.js 18+ (for building theme assets)

= Required Plugins =

* [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/) — required for custom meta fields
* [WPGraphQL](https://wordpress.org/plugins/wp-graphql/) — required for GraphQL API
* [WPGraphQL for ACF](https://wordpress.org/plugins/wpgraphql-acf/) — required to expose ACF fields in the GraphQL schema

= Optional Plugins =

* [Font Awesome](https://wordpress.org/plugins/font-awesome/) — icon support
* [User Switching](https://wordpress.org/plugins/user-switching/) — quickly switch between user accounts for testing
* [Headless Form Block](https://github.com/hozt/headless-form-block) — form block support for headless frontends
* [WP Mail SMTP](https://wordpress.org/plugins/wp-mail-smtp/) — transactional email

= Custom Post Types =

* **Gallery** (`gallery`) — Image galleries with sortable gallery images meta field
* **Testimonial** (`testimonial`) — Customer/client testimonials with tagging
* **FAQ** (`faq`) — Frequently asked questions, organized by topic sections
* **Event** (`event`) — Dated events with sticky/featured support
* **Video** (`video`) — Video content with URL meta field
* **Portfolio** (`portfolio`) — Portfolio items with categories and additional image
* **Private** (`private`) — Private/members-only content
* **Other** (`other`) — Generic flexible post type with tagging
* **Form** (`form`) — Form definitions
* **Template** (`template`) — Reusable content templates with footer content field

= Custom Taxonomies =

* `faqs` (FAQ) — Hierarchical FAQ topic sections
* `portfolio_category` (Portfolio) — Flat portfolio categories
* `testimonial_tag` (Testimonial) — Tags for testimonials
* `other_tag` (Other) — Tags for Other post type

Standard WordPress `category` taxonomy is extended with a Hide Listing Page field, queryable via GraphQL.

= GraphQL API =

WP Astro extends the WPGraphQL schema with additional fields and types, including:

* `customSiteSettings` query — returns site-wide configuration for the Astro frontend (site title, tagline, logos, favicon, default images, editor key, enabled features)
* Extended Category fields — `hideListingPage` boolean to suppress archive pages on the frontend
* Extended MediaItem fields — `isStylesheet` flag for CSS media items
* Menu Order support — custom post types expose `menuOrder` via WPGraphQL

= Admin Features =

* **Feature Toggles** — Dashboard widget to enable/disable sections of the admin UI. Available flags: `posts`, `galleries`, `testimonials`, `forms`, `faqs`, `events`, `videos`, `portfolios`, `podcasts`, `private`, `others`, `backups`, `import`, `redirects`, `tools`, `settings`, `users`, `scf`
* **Publish Site** — Button in the admin menu that POSTs to the configured webhook URL and generates a new `editor_key` for the frontend editor overlay
* **Custom Sort** — Drag-and-drop sort interface in admin list views for post types with menu order support

= CORS =

CORS is pre-configured to allow `http://localhost:4321` (Astro default) and `http://localhost:8000` for local development, plus the Website URL from the Customizer.

To add additional origins, use the `wp_astro_cors_origins` filter:

`add_filter('wp_astro_cors_origins', function(array $origins): array {
    $origins[] = 'https://preview.example.com';
    return $origins;
});`

== Installation ==

1. Upload the `wp-astro` folder to `/wp-content/themes/`
2. Activate the theme in **Appearance > Themes**
3. Install and activate WPGraphQL
4. Configure theme settings under **Appearance > Customize**

= Build Assets =

After uploading, build the theme assets:

`npm install
npm run dev   # development watch
npm run prod  # production build`

== Configuration ==

All settings are in **Appearance > Customize** under the Site Identity section:

* **Website URL** — Your Astro frontend URL (e.g. `https://example.com`). Used for CORS, the editor link, and loading the frontend stylesheet in the admin.
* **Publish Webhook** — Webhook URL to trigger when "Publish Site" is clicked. Use your Astro/CI deploy hook.
* **Editor Key** — Auto-generated on each publish. Used by the frontend to enable edit mode.
* **Mobile Logo** — Logo variant for smaller screens.
* **Default Header Image** — Fallback header image for posts/pages that don't have one set.
* **Default Featured Image** — Fallback featured image for posts/pages.

== Frequently Asked Questions ==

= Does this theme have a frontend? =

No. WP Astro redirects all frontend traffic to the WordPress admin. It is designed exclusively as a headless CMS backend.

= What frontend framework does this work with? =

It is designed to work with [Astro](https://astro.build) via the [hozt/astro-wordpress](https://github.com/hozt/astro-wordpress) integration package, but any frontend that can consume a GraphQL API will work.

= Is WPGraphQL required? =

Yes. WPGraphQL is required for the GraphQL API surface. Install it from the WordPress plugin directory.

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Initial release.
