# Deploy checklist

Target: shared hosting, PHP 8.2+, no Node. Tick each box on the server.

## 1. Before you upload
- [ ] `php artisan test` passes on the branch you are deploying.
- [ ] `public/assets/{site,account,admin}.css` are committed (run `php artisan assets:build` if CSS changed).
- [ ] No `storage/canvas-tmp`, `.env` or local sqlite file in the upload.
- [ ] Run `composer install --no-dev --optimize-autoloader` locally or on the host.

## 2. Hosting
- [ ] PHP 8.2+ with `pdo_sqlite` (or your DB driver), `mbstring`, `openssl`, `gd`, `fileinfo`, `ctype`, `tokenizer`, `xml`.
- [ ] Domain points at the `public/` folder (not the project root). If the host cannot do this, keep everything above `public_html` and move only `public/` contents in.
- [ ] HTTPS is on and HTTP redirects to it.
- [ ] `storage/` and `bootstrap/cache/` are writable.
- [ ] The sqlite file (if used) lives outside the web root and is writable.

## 3. `.env`
- [ ] `APP_ENV=production`, `APP_DEBUG=false`
- [ ] `APP_URL=https://your-domain` (used in emails and links)
- [ ] `APP_KEY` generated on the server: `php artisan key:generate --force`
- [ ] `DB_*` set for the production database
- [ ] `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`
- [ ] `PADDLE_WEBHOOK_SECRET` and `PADDLE_PRICES` (price id => plan slug), once the Paddle account exists
- [ ] Licence API settings unchanged from what the plugin expects (the API is frozen)

## 4. First run
- [ ] `php artisan migrate --force`
- [ ] Seed or create plans (Plans page in HQ, or the seeder)
- [ ] Create the first staff user and enrol 2FA
- [ ] `php artisan config:cache && php artisan route:cache && php artisan view:cache`
- [ ] `php artisan storage:link` if release files are served from storage

## 5. HQ Settings
- [ ] Seller legal name and address
- [ ] Support email
- [ ] Upload the Pro and free release zips under Releases

## 6. Scheduler (renewal and expiry emails)
- [ ] Cron every minute: `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1`

## 7. Payments (Paddle)
- [ ] Paddle account approved, products and prices created
- [ ] Checkout passes `custom_data {order, email}`
- [ ] Webhook destination set to `https://your-domain/webhooks/paddle`, events: `transaction.completed`, `adjustment.created`, `adjustment.updated`
- [ ] Send a test event; confirm it shows as processed in HQ, Webhooks
- [ ] Do one sandbox purchase and one refund end to end

## 8. Smoke test on the live site
- [ ] Home, Pricing, Docs pages load; no mixed-content warnings
- [ ] Sign up, sign in, password reset email arrives
- [ ] Buy Pro (sandbox): receipt and licence emails arrive, key shows in /account
- [ ] Download Pro from /account/downloads
- [ ] Activate the key from the plugin against the live API
- [ ] Open a ticket; reply from HQ; the customer email arrives
- [ ] HQ sign in with 2FA; audit log records it
- [ ] `https://your-domain/storage/` and `.env` are not reachable from the browser
- [ ] Share a page and check the social card appears

## 9. After launch
- [ ] Back up the database daily
- [ ] Watch `storage/logs/laravel.log` for mail and webhook failures
- [ ] Check HQ, Webhooks for failed events and retry them

## Known gaps (not blockers for a soft launch)
- Email confirmation for sign-ups is built but not wired.
- Customer "Disable account" and "Invite staff" are built (invite emails a password-reset link).
- Some HQ pages were checked by tests only, not compared to the designs.
