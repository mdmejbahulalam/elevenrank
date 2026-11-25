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

## Phase 2: Marketing site (2026-11-25, partial slice)

**Built**
- Site layout: unique title and description, canonical, Open Graph and X tags, `Organization` and `WebSite` JSON-LD on every page, `SoftwareApplication` on Home, "Get free plugin" header button (`config/site.php`, `FREE_PLUGIN_URL`).
- Home rebuilt to the section list: hero with two buttons, proof strip, six feature cards, importer chips (text only), how it works in 3 steps, free vs Pro table, privacy block, FAQ of 6, final CTA.
- Pricing: trust row and free vs Pro comparison table. About page (owner text slot), branded 404.
- `robots.txt`, `sitemap.xml` and `llms.txt` served by `SeoFilesController` (the static `public/robots.txt` was removed; admin, account and checkout are disallowed).
- Tests: `MarketingSeoTest`. Total 31 passed, 268 assertions.

**Not done (still Phase 2)**
- Product screenshots (need real captures from the dev site), Features sticky nav, Pro four-group layout and "what Pro is not" box driven by Content, Compare pages (need verified facts and dates), Blog, docs sidebar and search, changelog filter, Content store (`resources/content/*.md`), generated OG image, `Product`+`Offer` and `FAQPage` JSON-LD (prices are placeholders), 500 and maintenance pages.
- Not run: Lighthouse, axe, schema validation, link check, copy honesty review.
- Placeholders: `FREE_PLUGIN_URL` slug is unconfirmed; About text is a slot.

### Phase 2 continued (2026-11-25)
- Features: sticky in-page nav with anchors. Pro: the 15 features in four groups (On-page, Crawl control, Analysis, Launch safety) and a "What Pro is not (yet)" box read from `config/site.php` (`pro_version`, `pro_status`). Docs: sidebar and client-side search with a no-result message, "last updated" line. Changelog: filter by product (`?product=free|pro`) and an anchor per version.
- Tests: 32 passed, 281 assertions. Site CSS 12.3 KB (3.5 KB gzip).
- Still open in Phase 2: product screenshots, Compare pages, Blog, Content store, generated OG image, docs split into `/docs/{slug}` pages with prev/next, Lighthouse, axe, schema validation, link check.

## Phase 3: Customer dashboard (2026-11-25, partial slice)

**Built**
- Forgot and reset password (`/account/forgot-password`, `/account/reset-password/{token}`): same answer whether or not the address exists, throttled, link in the sign-in page. Mail goes through the configured driver (`log` until a real one is set).
- Downloads: size, SHA-256, requirements, earlier versions list for eligible customers.
- Orders: print-ready invoice view (`/account/orders/{number}/invoice`, seller and tax lines are marked placeholders), "Request a refund" for paid orders inside 14 days (opens a ticket and writes an audit row; staff still refund in the Staff area).
- Licences: label (migration `add_label_to_licences`). Overview: "Install Pro in 3 steps" checklist that ticks when a first site activates.
- Support: optional site address on new tickets. Profile: "Export my data" (JSON of account, orders, licences with keys, tickets), throttled.
- Tests: `CustomerDashboardTest`. Total 35 passed, 307 assertions.

**Not done (still Phase 3)**
- Email verification, two-factor, active sessions, delete account, billing name/address/tax ID, renewal and cancel-renewal, ticket attachments and unread marker, ticket tied to a licence (site address is free text), seat meter and reveal/mask on keys, loading and error states, sending the Phase 1 email templates (receipts, key delivery), Playwright journey.
- Not run: browser check of new pages, axe.

### Phase 3 continued: service emails (2026-11-25)
- `App\Mail\TemplateMail` plus `App\Services\Notifier` send the Phase 1 templates: welcome (registration), receipt with key (order marked paid, once), refund (once), ticket reply (staff reply). The old plain-text key email in `Fulfilment` is replaced. Sending is synchronous and wrapped so a mail failure is reported but never undoes an order (tested).
- `php artisan emails:term-notices` sends the 30-day, 7-day and term-ended emails once per licence (migration `add_notice_columns_to_licences`), scheduled daily at 08:00. **Hosting needs one cron entry:** `* * * * * php /path/to/artisan schedule:run`.
- Tests: `ServiceEmailsTest`. Total 39 passed, 325 assertions.
- Still needed: set `MAIL_*` for a real sender (default is `log`), real seller name and support address in the email footer (placeholders), password-reset mail still uses Laravel's default text, no email verification yet.

## Phase 4: Staff area (2026-11-25, partial slice)

**Built**
- Roles `super_admin`, `support`, `finance` (migration adds `users.role`, existing staff become `super_admin`; a staff row with no role counts as `super_admin`, an unknown role gets no access). Abilities are Gates in `AppServiceProvider::ABILITIES`, enforced with `can:` on every staff route; order actions are checked per action (support cannot mark paid or refund; finance cannot see licences, customers or releases; plans, releases, system and audit are super_admin only). Sidebar hides what the role cannot open. `php artisan admin:create <email> --role=support|finance|super_admin`.
- Refund needs a reason (min 3 characters). The audit log now stores IP and reason and shows both.
- Global search (`/admin/search`, box in the sidebar) over customers, licence keys, sites, orders and tickets, limited to what the role may see.
- Orders CSV export (`/admin/orders.csv`, audited, formula-safe cells).
- Tests: `StaffRolesTest` (7). Total 46 passed, 375 assertions.

**Not done (still Phase 4)**
- Two-factor for staff (spec says required), revoke and reinstate with a reason and confirm modal, reissue key, customer notes and timeline, impersonation, internal notes on tickets, canned replies, assignee, plan feature bullets and preview, release validation report and notes, download counts, Content editor, telemetry, settings and staff management screens, audit filter and export, health checks beyond the current system page, sortable and paginated tables, Ctrl+K shortcut, Playwright journey per role.
- Not run: browser check, axe.

### Phase 4 continued: staff two-factor (2026-11-25)
- TOTP (RFC 6238, SHA-1, 6 digits) in `App\Support\Totp`, checked against the RFC test vectors. No outside library.
- Sign-in: a correct staff password only leads to `/admin/two-factor`; nobody is signed in until a valid code (each time step usable once, plus or minus one step for clock drift) or an unused recovery code is entered. The pending step expires after 5 minutes and is throttled.
- Enrolment at `/admin/two-factor/setup`: secret shown for copying (and as an `otpauth://` address; **no QR image yet**), confirmed with a code, then 8 one-time recovery codes shown once and stored as SHA-256 hashes. Secret is encrypted at rest. Audit rows: `staff.2fa_enabled`, `staff.signed_in`, `staff.recovery_code_used`.
- Enforcement: `STAFF_2FA_REQUIRED` (default true, set false in `phpunit.xml` for the older tests). Staff without 2FA can reach only the setup page and sign out.
- Lost device: `php artisan admin:reset-2fa <email>` (they re-enrol at next sign-in). Migration `add_two_factor_to_users`.
- Tests: `StaffTwoFactorTest` (7). Total 53 passed, 440 assertions.
- Not done: QR code, a screen to reset another staff member's 2FA, 2FA for customers (spec lists it as opt-in), a "turn off" action for yourself, remember-this-device.

## Homepage matched to the Site canvas
- New marketing header and footer (brand, nav, mobile menu, Company column) and a rebuilt homepage following the Site canvas home board: split hero with a dashboard mockup, feature cards with icons, steps, Free/Pro split, FAQ, closing call to action. Styles live in resources/css/home.css (`.s-*`) and are bundled into site.css.
- Checked in the browser in light, dark and mobile widths; 53 tests pass.
- Not done: the hero mockup is a labelled placeholder wireframe (a real screenshot is still needed); Blog and "Switching from Yoast" links omitted because those pages do not exist; Features, Pro, Pricing and other pages keep their older layout inside the new header and are not yet compared with their canvas boards.

## Marketing pages matched to the Site canvas
- Features, Pro, Pricing, Docs, Changelog, Support, About, legal pages and the 404 page now follow the canvas boards (styles in resources/css/home.css). 53 tests pass.
- Not done: Hooks page keeps its older layout; Blog, Blog post and Compare boards have no pages yet; screenshots are placeholders; account, admin and sign-in pages are not matched (no canvas read for them); checked visually only in a small browser pane.

## Blog and Compare pages
- /blog, /blog/{slug} (posts read from config/blog.php until the content store exists; empty state and noindex while there are none) and /compare/yoast-seo (Yoast column marked Not yet checked, noindex until each cell is verified). Footer links added. 56 tests pass.
- Not done: real posts, cover images, the verified Yoast comparison, sitemap entries for these pages, Hooks page restyle.
