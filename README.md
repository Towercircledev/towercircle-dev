# Towercircle website

towercircle.in — Laravel 13 + Filament 5. Public site built from the Turacos "index-8" design with one hand-written stylesheet (~7 KB gzipped), Plus Jakarta Sans + Inter (self-hosted) and ~1 KB of JavaScript. No jQuery, no Bootstrap.

## What's where

| Area | Where |
|---|---|
| Public pages | `resources/views/site/*`, layout in `resources/views/layouts/site.blade.php` |
| Styles / JS | `resources/css/site.css`, `resources/js/site.js` (built with Vite into `public/build`, committed) |
| Admin | `/admin` — Leads, Ad landing pages, Services, Projects, Reviews, Blog, FAQs, Team, Homepage & About, Site settings |
| Lead capture | `app/Http/Controllers/LeadController.php`; source tracking in `app/Http/Middleware/CaptureAttribution.php` |

### Pages
`/` · `/about` · `/services` · `/services/{slug}` · `/work` · `/work/{slug}` · `/blog` · `/blog/{slug}` · `/faq` · `/team` · `/contact` · `/lp/{slug}` (ad landing pages) · `/thank-you` · `/sitemap.xml` · `/robots.txt`

Team, Blog and FAQ can be switched off in **Site settings → Pages on/off**. Each homepage section has its own switch in **Homepage & About**.

### Other admin tools
* **Logos** (Website → Logos): client logos show under "Trusted by 100+ Businesses", platform logos under "Platforms We Work With". A strip appears only when it has logos.
* **Location pages** (`/in/{city}`): write real, city-specific content — copy-paste pages that only swap the city name can be ignored by Google.
* **Redirects** (Settings → Redirects): map old WordPress addresses to new pages (301). Any unknown address is checked here before showing the 404 page; hits are counted.
* **Privacy & terms** (`/privacy-policy`, `/terms-and-conditions`): starter text included — have it checked by your CA or lawyer.
* **Telegram alerts** (Site settings → Instant lead alerts): free instant notification for every lead.

### Clients CRM (Clients group in the admin)
* **Clients** with websites, passwords, renewals, payments, tasks, files and a dated timeline. Search any client by name, phone, email or domain (Ctrl/⌘ + K).
* **Passwords** are encrypted with `APP_KEY`, visible to Admins only, and every "Show / copy" is logged. **Keep a copy of `APP_KEY` somewhere safe (outside the server). Without it, saved passwords cannot be decrypted, even from backups.**
* **Renewals** (domain, hosting, email, SSL, maintenance…): "Create invoice", "Mark renewed" (moves the date forward one cycle) and a ready-written WhatsApp reminder.
* **Payments & invoices**: invoice numbers like `TC/2026-27/0001` (restart each April), GST split as CGST+SGST or IGST, PDF download, "Mark as paid", WhatsApp payment reminder. Invoice details are in Site settings → Invoices.
* **Websites** are checked every 5 minutes (alert after 2 failed checks, and again when back online); SSL expiry is read daily.
* **Daily summary at 9 am** (Telegram + email): renewals due in 30 and 7 days, overdue payments, tasks due and sites down.
* **Roles**: Admin sees everything. Staff sees leads, clients, websites, renewals (no amounts), tasks and website content — not passwords, payments, settings or users. Manage users in Settings → Admin users.
* **2FA** is required for every admin user (set up on first login with Google Authenticator; recovery codes shown once). Reset someone's 2FA from Settings → Admin users.

### Leads and Google Ads
* Every form saves to **Leads** with the Google click ID (gclid), UTM tags, first page visited and the page the form was sent from. An email goes to the addresses in Site settings.
* `/thank-you` fires the Google Ads conversion (set *Google Ads ID* and *Lead conversion label* in Site settings → Tracking) and a GA4 `generate_lead` event. WhatsApp and call clicks are sent as GA4 events.
* **Meta (Facebook/Instagram):** the Pixel tracks visits and leads in the browser, and the Conversions API sends the same lead from the server (same event ID, so it is counted once). Add the Pixel ID and a Conversions API token in Site settings → Tracking. Leads from Meta ads are saved with the Facebook click ID and show as "Meta Ads"; marking one **Won** sends a Purchase event with the deal value to Meta.
* Mark leads **Won** (with the deal value). **Leads → Google Ads conversions** downloads a CSV for Google Ads → Goals → Conversions → Uploads, so Ads learns which clicks became paying clients.

## Local setup

```bash
composer install
cp .env.example .env && php artisan key:generate
touch database/database.sqlite && php artisan migrate --seed
php artisan make:filament-user   # then set ADMIN_2FA_REQUIRED=false in .env if you want to skip 2FA locally
npm install && npm run build      # after changing CSS/JS; commit public/build
php artisan serve
```

## Server (new Lightsail instance, Ubuntu 24.04, Mumbai)

Same setup as GetShuddh: nginx + PHP 8.3-FPM + MySQL, app in `/var/www/towercircle`.

1. Install nginx, php8.3-fpm (+ mbstring, xml, curl, mysql, intl, zip, gd, bcmath), mysql-server, composer, certbot.
2. `git clone` this repo to `/var/www/towercircle`, create `.env` (APP_ENV=production, APP_DEBUG=false, DB_*, MAIL_* with a real SMTP such as Zoho/Brevo, `MAIL_FROM_ADDRESS=reach@towercircle.in`).
3. `php artisan key:generate`, `php artisan migrate --seed --force`, `php artisan make:filament-user`.
4. Copy `deploy/nginx-towercircle.conf` to `/etc/nginx/sites-available/towercircle`, enable it, then `certbot --nginx`.
5. Add the scheduler to cron (`crontab -e` as the web user): `* * * * * cd /var/www/towercircle && php artisan schedule:run >> /dev/null 2>&1` — this runs uptime checks, SSL checks and the daily summary.
6. In `.env` set `ADMIN_PATH=` to a private admin address (e.g. `tc-panel`), keep `ADMIN_2FA_REQUIRED=true`, and configure real SMTP (`MAIL_*`) so lead alerts and customer auto-replies are delivered.
7. Every update after that: `git pull && bash deploy/deploy.sh`.
8. Backups: `deploy/backup.sh` in root's crontab, plus Lightsail automatic snapshots.

Test on `new.towercircle.in` first. When switching the main domain, change only the A record — leave the MX (email) records alone. Add 301 redirects for old WordPress URLs before switching.
