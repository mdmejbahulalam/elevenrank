# Platform progress log

## Phase 0: Reconcile (2026-11-25)

**Built (docs only, no code or migration changes)**
- `docs/PLACEHOLDERS.md`: placeholders and unknowns.
- `docs/ROUTES.md`: all 61 routes at the baseline.
- In the plugin repo, `docs/PLATFORM_PLAN.md` rewritten to match the Laravel build (sections 1, 4, 5, 7, 8, 9 and 10; decisions table updated). Also added there: `docs/PLATFORM_MASTER_PROMPT.md`, `docs/PLATFORM_UI_DESIGN_PLAN.md`.

**Evidence**
- `php artisan test` on branch `main`, clean tree: 26 passed, 189 assertions, 2.1 s.
- `php artisan route:list --except-vendor`: 61 routes (see `docs/ROUTES.md`).
- Not run: screenshots of the current pages (needs the app served with a seeded local database; to capture at the start of Phase 1 before the port so behaviour can be compared), axe, Lighthouse.

**Decisions recorded**: one Laravel app; provider-neutral payments; Pro keeps working after expiry; 14-day refund; licence API frozen; roles planned as super_admin, support, finance. See `PLATFORM_PLAN.md` section 0.

**Open questions for the owner**
1. Confirm plan names, site counts and prices.
2. Payment provider: Lemon Squeezy, Paddle or Stripe?
3. Is `elevenrank.com` owned; final product name?
4. Support channel: tickets, email, or both; response time to promise?
5. Telemetry: none or opt-in?
6. Dark theme at launch?
7. Seller legal name, address and tax details.

**Next**: Phase 1, design system. Risk: porting about 970 lines of Blade with inline CSS to shared tokens and components without changing behaviour; the 26 feature tests plus a before and after screenshot grid are the guard.

## Phase 1: Design system (2026-11-25)

**Built** (branch `phase-1-design-system`)
- `resources/css/{tokens,base,components,site,account,admin}.css`. Tokens match the design system canvas (colour, 12-60 type scale, 4px space, radius 8/12/20, light and dark by `prefers-color-scheme` plus a `data-theme` override). Old class names (`btn`, `card`, `tag`, `flash`, `grid`, `row`, `--bg`, `--card`, `--soft`) are kept so un-ported pages still work.
- `php artisan assets:build` (`app/Console/Commands/BuildAssets.php`) writes `public/assets/{site,account,admin}.css`. **Deviation:** no Vite/Node, because the repo has no `node_modules`, the host has no Node, and the CSS is plain. Output is committed.
- `public/assets/app.js` (copy buttons, modal open/close, theme toggle hook; pages work without it), `theme.js` (applies a saved theme before paint), `mark.svg`.
- Blade components in `resources/views/components`: logo, icon (18 icons, inline SVG), badge, alert, button, card, stat, field, empty, copy-field, breadcrumb, modal, code-block.
- Layouts `resources/views/layouts/{site,account,admin,auth}.blade.php` (+ `head`). Old `site/account/admin.layout` removed; every page now extends the new ones. No inline `<style>` blocks remain in any layout. Skip link, `<main id="main">`, landmarks added. Account has a sidebar that becomes bottom tabs on phones; admin has a grouped sidebar.
- Ported to components: all account pages (login, register, overview, licences, checkout, downloads, orders, order, profile, tickets, ticket) and all admin pages (login, overview, customers, customer, orders, licences, plans, releases, tickets, ticket, audit, system). New components: table (scrollable, with headings), status (badge tone from order, licence and ticket status), and textarea, maxlength, min, max, step support in field. Marketing pages (home, features, pro, pricing, docs, hooks, changelog, support, legal) keep their markup and use the compatible classes; they are rebuilt in Phase 2.
- Emails: `resources/views/emails/{layout,welcome,receipt,renewal30,renewal7,expired,refund,ticket_reply}.blade.php`, inline-styled, with `EmailTemplatesTest`. **Not wired to mailables or any sending code** (that would change behaviour).

**Evidence**
- `php artisan test`: 28 passed, 244 assertions (26 baseline + EmailTemplatesTest + PortedPagesTest, which renders every account and staff page empty and with data).
- Rendered-text diff against the Phase 0 HTML of 15 public pages: marketing pages differ only by the new "Skip to content" link; login and register differ because they no longer carry the site header and footer (they use the auth layout).
- CSS size: site 10.5 KB, account 10.0 KB, admin 10.0 KB (about 3 KB gzip each), under the 40 KB budget.
- Spot-checked pricing and account login in the in-app browser.
- **Not run:** axe, Lighthouse, screenshot grid of all pages in light and dark, dark-mode contrast check, signed-in pages in a browser.

**Not done (carry into Phase 2 to 4):** icon sprite file (icons are inline per use), sortable and paginated table component, tabs, toggle, select and checkbox components, markdown mailables and sending, apple-touch icon and OG image template, porting the marketing pages (Phase 2).
