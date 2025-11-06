# SurajX GST Invoice SaaS

Complete WordPress 6+ theme and plugin bundle for a GST-invoicing SaaS experience.

## Contents

- `surajx-gii-theme/` – public marketing + customer dashboard theme
- `gst-invoice-inventory-saas/` – REST, CPT, and OAuth glue plugin (namespace `GII_SaaS`)
- `theme.zip` and `plugin.zip` – distributable archives generated from the above directories

## Requirements

- PHP 8.0+
- WordPress 6.0+
- OpenSSL + curl extensions (required for OAuth/token exchanges)

## Installation

1. Copy `surajx-gii-theme/` and `gst-invoice-inventory-saas/` into your WordPress `wp-content/themes/` and `wp-content/plugins/` folders respectively, or upload `theme.zip` / `plugin.zip` via the WordPress admin.
2. Activate **GST Invoice Inventory SaaS** plugin first, then the **SurajX GII Theme**.
3. Create the following pages (slugs must match):
   - `front-page` (set as Static Front Page) – use default template.
   - `pricing`, `account`, `login`, `register`, `forgot-password` – set each to the matching page template if WordPress does not auto-assign.
4. Navigate to **Settings → Permalinks** and click **Save** once to flush rewrite rules.

## Plugin Configuration

1. (Optional) Visit **Settings → General** to add a Google OAuth Client ID in the `gii_google_client_id` option (via `wp option update gii_google_client_id YOUR_ID`). Without it the Google button falls back to the native login form.
2. Manage products and invoices via the new **Products** and **Invoices** post types in the admin sidebar.
3. REST endpoints exposed at `/wp-json/gii-saas/v1/*`:
   - `GET /products`
   - `GET|POST /invoices`
   - `GET /account`
   - `GET /oauth/google`
   - `GET /oauth/callback`

## Theme Features

- Responsive marketing landing page and pricing layout.
- Auth pages for login, register, and password reset.
- Dashboard tabs powered by the plugin REST endpoints.
- Two shortcodes: `[gii_customer_dashboard]` and `[gii_invoice_builder]`.
- English + Hindi translations (`.po` files included).

## Development

Run `php -l` against theme/plugin PHP files before packaging. If you add build tooling (e.g., Tailwind) ensure compiled assets live in `assets/`.

## Packaging

```bash
zip -r theme.zip surajx-gii-theme
zip -r plugin.zip gst-invoice-inventory-saas
```

Upload the generated zips through the WordPress Appearance → Themes or Plugins screen to install on another site.
