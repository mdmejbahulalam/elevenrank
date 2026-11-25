# Placeholders and unknowns

Everything here is a stand-in. Nothing below may reach customers as if it were real. Update this file in every phase: add new placeholders, strike through ones that are resolved.

Last reviewed: 2026-11-25 (Phase 0).

## Business facts (owner to supply)

| Item | Where it shows | Status |
|---|---|---|
| Plan prices (Personal $49, Agency $99, Studio $199) | Pricing, checkout, orders, emails. Seeded by migration `2026_11_26_000002_seed_default_plans`; editable in Staff, Plans | Placeholder |
| Plan names and site counts (1 / 3 / 10) | Same | Provisional |
| Seller legal name and address | Invoices, legal pages, email footers | Missing |
| Support email address and response time | Support page, email footers | Missing. Do not promise a response time until set |
| Tax or VAT line and tax IDs | Invoices | Depends on payment provider |
| Final domain | Emails, canonical URLs, sitemap, `APP_URL` | Presumed `elevenrank.com`, ownership unconfirmed |
| Payment provider | Checkout, webhooks | Paddle chosen (Lemon Squeezy is shutting down). Webhook adapter exists, off without PADDLE_WEBHOOK_SECRET; map price ids in PADDLE_PRICES |

## Content (clearly marked slots, never invented)

| Item | Rule |
|---|---|
| Customer reviews, user counts, logos, ratings, "as seen in" | None until real. Use an empty, labelled content slot in staff Content (Phase 2) |
| Comparison table against other SEO plugins | Cells need verified facts and a date. Leave blank until checked |
| Screenshots of the plugin | Real captures from the dev site only. Labelled wireframes until then |
| About page bio | Owner to write |
| Blog posts, docs guides beyond the existing pages | Owner to review before publishing |
| Legal text (terms, privacy, refunds, GPL) | Current text is a working draft. Needs legal review before launch |

## Technical stand-ins

| Item | Status |
|---|---|
| Pro release zip on the private disk | Local test build only. Upload the real zip through Staff, Releases |
| SHA-256 checksum on the Downloads page | To show once a release is uploaded |
| Staff roles | Single `is_admin` flag until Phase 4. Target roles: super_admin, support, finance |
| Two-factor for staff | Not built (Phase 6) |
| Telemetry | None. Decide: none or opt-in version stats |
| Email delivery | Local log mailer. Real SMTP or provider needed before launch |
| Analytics | None. If added, cookieless and self-hosted, disclosed in the privacy page |

## Design canvases (sample data only)

The five Claude Design canvases use invented names, keys, amounts and dates (for example "Alex Rahman", `ER-PRO-7F3K-…`, `ER-1042`). None of it is real data. The icon in the email and HQ canvases is a drawn stand-in for the real mark.

| Canvas | Link |
|---|---|
| Design system | https://claude.ai/artifact/B35gKaRjZCapGpy2892oLv |
| Site | https://claude.ai/artifact/AkZ4QRsUjiQ6skCSHLtQwh |
| Account | https://claude.ai/artifact/Rc1RWbVuZB5vao87hymqWm |
| HQ (staff) | https://claude.ai/artifact/5UxXNotLMjUb4gDgEm9c4y |
| Email and assets | https://claude.ai/artifact/XUox6rg65VVPkZHGGSB5F4 |
