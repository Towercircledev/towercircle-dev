# Towercircle website

towercircle.in — Laravel 13 + Filament 5. Public site built from the Turacos "index-8" design with one hand-written stylesheet (~6 KB gzipped) and ~1 KB of JavaScript. No jQuery, no Bootstrap.

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

### Leads and Google Ads
* Every form saves to **Leads** with the Google click ID (gclid), UTM tags, first page visited and the page the form was sent from. An email goes to the addresses in Site settings.
* `/thank-you` fires the Google Ads conversion (set *Google Ads ID* and *Lead conversion label* in Site settings → Tracking) and a GA4 `generate_lead` event. WhatsApp and call clicks are sent as GA4 events.
* Mark leads **Won** (with the deal value). **Leads → Google Ads conversions** downloads a CSV for Google Ads → Goals → Conversions → Uploads, so Ads learns which clicks became paying clients.

## Local setup

```bash
composer install
cp .env.example .env && php artisan key:generate
touch database/database.sqlite && php artisan migrate --seed
php artisan make:filament-user
npm install && npm run build      # after changing CSS/JS; commit public/build
php artisan serve
```

## Server (new Lightsail instance, Ubuntu 24.04, Mumbai)

Same setup as GetShuddh: nginx + PHP 8.3-FPM + MySQL, app in `/var/www/towercircle`.

1. Install nginx, php8.3-fpm (+ mbstring, xml, curl, mysql, intl, zip, gd, bcmath), mysql-server, composer, certbot.
2. `git clone` this repo to `/var/www/towercircle`, create `.env` (APP_ENV=production, APP_DEBUG=false, DB_*, MAIL_* with a real SMTP such as Zoho/Brevo, `MAIL_FROM_ADDRESS=reach@towercircle.in`).
3. `php artisan key:generate`, `php artisan migrate --seed --force`, `php artisan make:filament-user`.
4. Copy `deploy/nginx-towercircle.conf` to `/etc/nginx/sites-available/towercircle`, enable it, then `certbot --nginx`.
5. Every update after that: `git pull && bash deploy/deploy.sh`.
6. Backups: `deploy/backup.sh` in root's crontab, plus Lightsail automatic snapshots.

Test on `new.towercircle.in` first. When switching the main domain, change only the A record — leave the MX (email) records alone. Add 301 redirects for old WordPress URLs before switching.
