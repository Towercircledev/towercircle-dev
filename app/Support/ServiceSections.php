<?php

namespace App\Support;

/**
 * The image + text sections shown on each service page (image on one side, content on the other).
 * Images live in public/img/svc/ and can be replaced per section in the admin.
 */
class ServiceSections
{
    public static function for(string $slug): array
    {
        return self::all()[$slug] ?? [];
    }

    public static function all(): array
    {
        return [
            'shopify-store-development' => [
                [
                    'eyebrow' => 'Step 1 · Research',
                    'heading' => 'We Study Your Products, Customers and Competitors First',
                    'default_image' => 'img/svc/research.webp',
                    'body' => '<p>Before we touch a theme, we spend time understanding how your customers buy. Who are they? What do they search for? What makes them trust a store enough to pay online — or choose cash on delivery?</p><p>We look at 3–5 stores your customers already buy from, note what they do well and where they lose people, and turn that into a clear plan for your store: the pages you need, how products should be grouped, and what has to be visible before someone adds to cart.</p>',
                    'points' => ['Competitor store review', 'Customer buying journey mapped', 'Right Shopify plan, theme and apps chosen', 'Written plan you approve before we start'],
                ],
                [
                    'eyebrow' => 'Step 2 · Design',
                    'heading' => 'A Store Designed for Mobile Shoppers in India',
                    'default_image' => 'img/svc/design.webp',
                    'body' => '<p>Most of your customers will shop on a phone, often on mobile data. So we design mobile-first: big product photos, fast pages, a clear price and delivery promise, and a checkout that takes seconds.</p><p>We match your brand colours and style, then add the trust signals Indian shoppers look for — reviews, secure payment badges, easy returns, WhatsApp support and COD where it makes sense. You see and approve the design before we build.</p>',
                    'points' => ['Mobile-first product and collection pages', 'Trust badges, reviews and clear policies', 'Fast-loading images', 'Design approval before development'],
                ],
                [
                    'eyebrow' => 'Step 3 · Build',
                    'heading' => 'Everything Set Up So You Can Start Selling',
                    'default_image' => 'img/svc/dashboard.webp',
                    'body' => '<p>This is where most DIY stores fall short. We set up every detail: products with variants, collections and filters, Razorpay and UPI payments, COD rules, shipping rates and courier integration, GST settings, order emails and policies.</p><p>We also connect WhatsApp for order updates, set up discount codes and abandoned-cart reminders, and install Google Analytics, Google Ads and Meta Pixel tracking — so when you start ads, every sale is measured from day one.</p>',
                    'points' => ['Payments: Razorpay, UPI, cards, COD', 'Shipping and courier integration', 'GST, policies and order emails', 'Analytics and ad tracking installed'],
                ],
                [
                    'eyebrow' => 'Step 4 · Launch & grow',
                    'heading' => 'We Launch With You — and Stay After',
                    'default_image' => 'img/svc/analytics.webp',
                    'body' => '<p>Before going live we place real test orders, check every page on different phones, and connect your domain with SSL. Then we train you to add products, process orders and run offers yourself — and give you a recorded walkthrough to refer back to.</p><p>For the first 30 days we\'re on WhatsApp for any question. After that, many clients keep us on for monthly updates, speed checks and running Google and Meta ads to grow sales.</p>',
                    'points' => ['Real test orders before launch', 'Training session + recorded video', '30 days of free support', 'Optional ads and growth support'],
                ],
            ],

            'website-design-development' => [
                [
                    'eyebrow' => 'Step 1 · Understand',
                    'heading' => 'We Start With Your Business, Not a Template',
                    'default_image' => 'img/svc/research.webp',
                    'body' => '<p>A good website begins with questions: Who are your best customers? Why do they choose you over others? What do they need to see before they call? We get these answers in a short discovery call and a simple questionnaire.</p><p>We also review your competitors\' websites and the Google searches people use to find businesses like yours. This tells us which pages you need and what each page must say to win the enquiry.</p>',
                    'points' => ['Discovery call and questionnaire', 'Competitor website review', 'Keyword research for your services', 'Sitemap and page plan'],
                ],
                [
                    'eyebrow' => 'Step 2 · Design',
                    'heading' => 'A Professional Look That Builds Instant Trust',
                    'default_image' => 'img/svc/design.webp',
                    'body' => '<p>Visitors decide in a few seconds whether a business looks trustworthy. We design a clean, modern site in your brand colours, with real photos of your work and team wherever possible — not generic stock images.</p><p>Every page has one clear next step: call, WhatsApp or fill a short form. We design the homepage first, refine it with you, then design the inner pages in the same style.</p>',
                    'points' => ['Custom design in your brand style', 'Clear call-to-action on every page', 'Designed for mobile first', 'Unlimited feedback rounds on the homepage'],
                ],
                [
                    'eyebrow' => 'Step 3 · Build',
                    'heading' => 'Fast, Secure and Easy for You to Update',
                    'default_image' => 'img/svc/cloud.webp',
                    'body' => '<p>We build lean websites that load in about two seconds on mobile — speed matters for both visitors and Google. Forms send enquiries straight to your email (and optionally Telegram), and a WhatsApp button sits on every page.</p><p>You get an easy admin panel to change text, photos, services and blog posts yourself, without calling a developer for every small change.</p>',
                    'points' => ['Loads in ~2 seconds on mobile', 'Enquiry forms + WhatsApp button', 'Easy admin panel', 'Free SSL and secure hosting'],
                ],
                [
                    'eyebrow' => 'Step 4 · Launch',
                    'heading' => 'Ready for Google From Day One',
                    'default_image' => 'img/svc/analytics.webp',
                    'body' => '<p>Before launch we add page titles and descriptions, a sitemap, business schema and Open Graph images for WhatsApp sharing. Then we connect Google Search Console and Analytics so you can see visitors and enquiries.</p><p>After launch we show you how to update the site and stay available for 30 days. Many clients then add our SEO, ads or maintenance plans to keep the website working for them.</p>',
                    'points' => ['On-page SEO and sitemap', 'Google Search Console + Analytics', 'Training and 30 days support', 'Optional SEO / ads / maintenance'],
                ],
            ],

            'ecommerce-website-development' => [
                [
                    'eyebrow' => 'Step 1 · Map the workflow',
                    'heading' => 'We Map How You Sell, Step by Step',
                    'default_image' => 'img/svc/research.webp',
                    'body' => '<p>Custom e-commerce exists because your business has rules a standard store can\'t follow. So we start by mapping them: how pricing works, who can buy what, how orders are approved, packed, delivered and invoiced.</p><p>The result is a clear document and flow diagram that both of us agree on — no surprises later about what the system will and won\'t do.</p>',
                    'points' => ['Pricing and customer rules documented', 'Order-to-delivery flow mapped', 'Integrations listed (payments, courier, Tally)', 'Fixed scope and price'],
                ],
                [
                    'eyebrow' => 'Step 2 · Prototype',
                    'heading' => 'See the Store Before It Is Built',
                    'default_image' => 'img/svc/mobile.webp',
                    'body' => '<p>We design the key screens — product page, cart, checkout and your admin dashboard — as clickable mock-ups. You can tap through them on your phone and share them with your team.</p><p>Changes at this stage are quick and free. Once you\'re happy, development starts with a clear picture of the finished product.</p>',
                    'points' => ['Clickable mobile and desktop screens', 'Admin dashboard designed too', 'Feedback before any coding', 'Design system for future features'],
                ],
                [
                    'eyebrow' => 'Step 3 · Build in phases',
                    'heading' => 'Built Securely, Released in Phases',
                    'default_image' => 'img/svc/security.webp',
                    'body' => '<p>We build the core — catalogue, cart, checkout and payments — first, so you can start taking orders early. Extra modules like subscriptions, bookings or wholesale portals follow in planned phases.</p><p>Payments go through Razorpay or your preferred gateway, every order generates a GST invoice automatically, and your data stays on your own server with daily backups.</p>',
                    'points' => ['Core store live first', 'Secure payments and GST invoices', 'Daily backups on your server', 'Role-based admin access'],
                ],
                [
                    'eyebrow' => 'Step 4 · Grow',
                    'heading' => 'Reports That Help You Sell More',
                    'default_image' => 'img/svc/analytics.webp',
                    'body' => '<p>Your admin shows what matters: sales by day and product, repeat customers, pending orders and stock running low. Exports work with Excel and your accountant\'s tools.</p><p>Because the platform is yours, we can keep adding features as you grow — loyalty points, a mobile app, a seller portal — without changing platforms or paying per-feature fees.</p>',
                    'points' => ['Sales, stock and customer reports', 'Excel / Tally-friendly exports', 'No per-app monthly fees', 'Add features any time'],
                ],
            ],

            'digital-performance-marketing' => [
                [
                    'eyebrow' => 'Step 1 · Audit',
                    'heading' => 'We Find Where Your Money Is Leaking',
                    'default_image' => 'img/svc/research.webp',
                    'body' => '<p>If you\'ve run ads before, we start with an honest audit of your Google Ads and Meta accounts: which campaigns brought real customers, which only brought clicks, and where budget went to the wrong searches or audiences.</p><p>We also check your website and landing pages — because even great ads fail if the page is slow or confusing. You get a simple report with the three biggest fixes, whether or not you hire us.</p>',
                    'points' => ['Google Ads and Meta account audit', 'Wasted spend and wrong keywords found', 'Landing page and speed check', 'Plain-language report with top fixes'],
                ],
                [
                    'eyebrow' => 'Step 2 · Tracking first',
                    'heading' => 'Every Lead Tracked Back to the Ad That Brought It',
                    'default_image' => 'img/svc/analytics.webp',
                    'body' => '<p>Most accounts track clicks or page views — so the platforms optimise for clicks. We set up proper conversion tracking: form submissions, WhatsApp clicks and calls in Google Ads and GA4, and the Meta Pixel plus Conversions API for Facebook and Instagram.</p><p>Where possible we also send back which leads became paying customers. That teaches Google and Meta to find more people like your real customers — the single biggest improvement to lead quality.</p>',
                    'points' => ['GA4 + Google Ads conversions', 'Meta Pixel + Conversions API', 'WhatsApp and call tracking', 'Offline conversions for won customers'],
                ],
                [
                    'eyebrow' => 'Step 3 · Launch',
                    'heading' => 'Campaigns and Landing Pages Built to Convert',
                    'default_image' => 'img/svc/marketing.webp',
                    'body' => '<p>On Google, we target searches with clear buying intent and block the ones that waste money (jobs, free, courses, DIY). On Facebook and Instagram, we test several audiences and creatives to find what stops the scroll.</p><p>Each campaign sends people to a landing page that matches the ad — the same offer, a short form and a WhatsApp button — instead of your homepage. This alone often halves the cost per lead.</p>',
                    'points' => ['High-intent keywords + negative keywords', 'Several creatives and audiences tested', 'Dedicated landing page per campaign', 'Spam-lead filtering'],
                ],
                [
                    'eyebrow' => 'Step 4 · Optimise & report',
                    'heading' => 'Weekly Optimisation, Clear Monthly Reports',
                    'default_image' => 'img/svc/dashboard.webp',
                    'body' => '<p>Every week we pause what isn\'t working and move budget to what is. Every month you get a short report in plain language: how many leads, what each cost, how many became customers, and what we\'ll test next.</p><p>The ad accounts are in your business name and your ad budget is paid directly to Google and Meta — you always stay in control and can see everything.</p>',
                    'points' => ['Weekly bid and budget optimisation', 'Monthly report: leads, cost per lead, sales', 'Accounts owned by you', 'No long lock-in contracts'],
                ],
            ],

            'amazon-ppc-management' => [
                [
                    'eyebrow' => 'Step 1 · Account audit',
                    'heading' => 'We Find Where Your Ad Spend Is Leaking',
                    'default_image' => 'img/svc/research.webp',
                    'body' => '<p>We start with a detailed audit of your Amazon Ads account and search term reports: which keywords bring profitable sales, which ones burn budget with clicks but no orders, and where competitors are taking sales you should be winning.</p><p>We also check each product\'s margin, current ranking and listing quality, so we know the ACoS each product can actually afford — instead of chasing one number for the whole account.</p>',
                    'points' => ['Search term and wasted-spend analysis', 'Margin and break-even ACoS per product', 'Competitor and category research', 'Clear list of quick wins'],
                ],
                [
                    'eyebrow' => 'Step 2 · Listing first',
                    'heading' => 'Make Every Click More Likely to Buy',
                    'default_image' => 'img/svc/design.webp',
                    'body' => '<p>Ads only bring people to your product page — the listing has to do the selling. Before scaling spend, we review titles, main images, bullet points, A+ content, price and reviews against the top sellers in your category.</p><p>Small listing fixes often raise conversion rate quickly, which lowers ACoS on every campaign and helps organic ranking too.</p>',
                    'points' => ['Title and keyword placement', 'Main image and infographic suggestions', 'Bullets and A+ content review', 'Price and review comparison'],
                ],
                [
                    'eyebrow' => 'Step 3 · Campaign structure',
                    'heading' => 'A Clean Structure That Is Easy to Control',
                    'default_image' => 'img/svc/marketing.webp',
                    'body' => '<p>We rebuild campaigns into a clear structure: automatic campaigns to discover new searches, broad and phrase to grow, exact match for proven winners, product targeting against competitors, and brand defence for your own brand name.</p><p>Winning search terms are moved to exact match every week, and irrelevant ones are blocked with negative keywords — so budget keeps flowing to what sells.</p>',
                    'points' => ['Auto, broad, phrase and exact campaigns', 'Product and competitor targeting', 'Sponsored Brands and Display', 'Weekly keyword harvesting and negatives'],
                ],
                [
                    'eyebrow' => 'Step 4 · Optimise & report',
                    'heading' => 'Bids Based on Profit, Reports You Can Understand',
                    'default_image' => 'img/svc/analytics.webp',
                    'body' => '<p>Bids are adjusted regularly by product margin, time of day and placement — pushing harder on searches that rank you and pulling back on ones that don\'t pay off.</p><p>Every month you get a simple report: ad sales, total sales, ACoS, TACoS, top keywords and ranking movement, plus what we\'ll test next. You can see everything in your own Amazon Ads account at any time.</p>',
                    'points' => ['Bid and budget optimisation', 'ACoS and TACoS tracking', 'Ranking movement for key searches', 'Monthly plain-language report'],
                ],
            ],

            'custom-software-development' => [
                [
                    'eyebrow' => 'Step 1 · Understand the workflow',
                    'heading' => 'We Sit With Your Team and Map How Work Really Flows',
                    'default_image' => 'img/svc/team.webp',
                    'body' => '<p>Good software starts with understanding the people who will use it. We talk to the owner, the managers and the staff who do the daily work — sales, accounts, HR, store — and map how information moves today: the Excel sheets, WhatsApp groups, registers and approvals.</p><p>This shows us exactly where time is lost, where mistakes happen and what reports you wish you had. It becomes a clear requirement document with priorities agreed together.</p>',
                    'points' => ['Interviews with owners and staff', 'Current process and pain points mapped', 'Must-have vs nice-to-have list', 'Fixed scope and price per phase'],
                ],
                [
                    'eyebrow' => 'Step 2 · Prototype',
                    'heading' => 'See and Click Through It Before We Write Code',
                    'default_image' => 'img/svc/dashboard.webp',
                    'body' => '<p>We design the main screens — dashboards, forms, lists and reports — as a clickable prototype your team can try on a laptop or phone. Everyone sees how their daily work will look and gives feedback early.</p><p>This avoids the most expensive mistake in software projects: building the wrong thing. Changes here take hours, not weeks.</p>',
                    'points' => ['Clickable screens for each role', 'Team feedback before development', 'Simple, consistent design', 'Mobile-friendly from the start'],
                ],
                [
                    'eyebrow' => 'Step 3 · Build in phases',
                    'heading' => 'The Most Important Module First, Live in Weeks',
                    'default_image' => 'img/svc/security.webp',
                    'body' => '<p>Instead of a long project that delivers everything at the end, we build in phases. The module that saves you the most time — often the CRM, billing or attendance — goes live first, usually within 3 to 6 weeks.</p><p>Security is built in: separate logins and permissions for each role, encrypted sensitive data, activity logs, and daily backups on your own server. Your data never leaves your control.</p>',
                    'points' => ['First module live in 3–6 weeks', 'Role-based access and activity logs', 'Encrypted data and daily backups', 'Hosted on your own server / cloud'],
                ],
                [
                    'eyebrow' => 'Step 4 · Train & support',
                    'heading' => 'Training, Data Import and Ongoing Improvements',
                    'default_image' => 'img/svc/partner.webp',
                    'body' => '<p>We import your existing data from Excel, train each team on the screens they use, and provide short guides they can refer to. For the first weeks we\'re on call to fix anything and adjust what doesn\'t feel right.</p><p>After that, a yearly support plan covers hosting, backups, updates and small improvements. As your business grows, we add the next modules — HR, inventory, client portal — on the same system.</p>',
                    'points' => ['Data imported from your Excel sheets', 'Role-wise staff training', 'Hosting, backups and updates', 'New modules added as you grow'],
                ],
            ],

            'website-speed-seo' => [
                [
                    'eyebrow' => 'Step 1 · Audit',
                    'heading' => 'A Full Health Check of Your Website',
                    'default_image' => 'img/svc/research.webp',
                    'body' => '<p>We run your site through Google PageSpeed, Search Console and our own crawl to find what\'s slowing it down and what\'s stopping Google from ranking it: heavy images, slow plugins, broken links, missing titles, duplicate pages and mobile issues.</p><p>You get a clear list of problems ranked by impact, so the most important fixes happen first.</p>',
                    'points' => ['PageSpeed and Core Web Vitals test', 'Technical SEO crawl', 'Search Console review', 'Prioritised fix list'],
                ],
                [
                    'eyebrow' => 'Step 2 · Speed',
                    'heading' => 'Make Every Page Load in About Two Seconds',
                    'default_image' => 'img/svc/cloud.webp',
                    'body' => '<p>We compress and resize images, convert them to modern formats, remove unused scripts and plugins, add caching and, where needed, move you to faster hosting closer to your customers.</p><p>Faster pages mean fewer visitors leave before the page loads — and Google uses speed as a ranking signal, especially on mobile.</p>',
                    'points' => ['Image compression and WebP', 'Caching and script clean-up', 'Hosting upgrade if needed', 'Before/after PageSpeed scores'],
                ],
                [
                    'eyebrow' => 'Step 3 · On-page & technical SEO',
                    'heading' => 'Help Google Understand Every Page',
                    'default_image' => 'img/svc/analytics.webp',
                    'body' => '<p>We research what your customers actually type into Google, then match each important page to the right searches: titles, descriptions, headings, internal links and content improvements.</p><p>We fix technical issues like redirects, broken links and indexing, and add schema so your business, services and FAQs can appear as rich results.</p>',
                    'points' => ['Keyword research', 'Titles, descriptions and headings', 'Redirects and indexing fixes', 'Schema for rich results'],
                ],
                [
                    'eyebrow' => 'Step 4 · Local SEO',
                    'heading' => 'Show Up When Nearby Customers Search',
                    'default_image' => 'img/svc/marketing.webp',
                    'body' => '<p>For most local businesses, the Google Business Profile brings more calls than the website itself. We optimise your profile — categories, services, photos and posts — and set up a simple system to collect more genuine reviews.</p><p>We can also create location pages for the areas you serve, each with real local content, so you appear in searches like "website designer in [your area]".</p>',
                    'points' => ['Google Business Profile optimisation', 'Review collection system', 'Location pages for your service areas', 'Monthly ranking check (optional)'],
                ],
            ],

            'domain-hosting' => [
                [
                    'eyebrow' => 'Domains',
                    'heading' => 'Your Domain, Registered in Your Name',
                    'default_image' => 'img/svc/partner.webp',
                    'body' => '<p>Your domain is your business address online, so it should always belong to you. We register or transfer it in your name, set up DNS correctly for your website and email, and keep a record of every renewal date.</p><p>Our renewal reminders mean your domain never expires by surprise — one of the most common (and expensive) mistakes small businesses make.</p>',
                    'points' => ['.in, .com and other extensions', 'Registered in your name', 'DNS set up correctly', 'Renewal reminders'],
                ],
                [
                    'eyebrow' => 'Hosting',
                    'heading' => 'Fast Cloud Hosting Close to Your Customers',
                    'default_image' => 'img/svc/server.webp',
                    'body' => '<p>We host websites on cloud servers in Mumbai, so pages load quickly for visitors across India. Each site gets free SSL, daily backups and regular security updates.</p><p>We size the server to your needs — a small business site doesn\'t need an expensive plan, and a busy store shouldn\'t be on cheap shared hosting.</p>',
                    'points' => ['Cloud servers in Mumbai', 'Free SSL (https)', 'Daily backups', 'Security updates'],
                ],
                [
                    'eyebrow' => 'Business email',
                    'heading' => 'Professional Email That Reaches the Inbox',
                    'default_image' => 'img/svc/cloud.webp',
                    'body' => '<p>An email like you@yourbusiness.com builds trust instantly. We set up Google Workspace or Zoho Mail, create mailboxes for your team, and move your old emails across.</p><p>We also configure SPF, DKIM and DMARC records — the settings that stop your emails landing in spam and protect your domain from being misused by scammers.</p>',
                    'points' => ['Google Workspace or Zoho Mail', 'Mailbox migration', 'SPF, DKIM and DMARC set up', 'Mobile and Outlook setup help'],
                ],
                [
                    'eyebrow' => 'Monitoring',
                    'heading' => 'We Watch Your Website Around the Clock',
                    'default_image' => 'img/svc/security.webp',
                    'body' => '<p>Our monitoring checks every site we manage every five minutes. If it goes down, we\'re alerted immediately and start fixing it — often before you or your customers notice.</p><p>We also watch SSL certificate expiry dates and domain renewals, so the small technical things never take your business offline.</p>',
                    'points' => ['Uptime check every 5 minutes', 'Instant alerts to our team', 'SSL expiry monitoring', 'Domain and hosting renewal tracking'],
                ],
            ],

            'website-maintenance' => [
                [
                    'eyebrow' => 'Updates',
                    'heading' => 'Safe Updates Without Breaking Your Site',
                    'default_image' => 'img/svc/dashboard.webp',
                    'body' => '<p>Outdated plugins and themes are the number-one way websites get hacked. Every month we update your website software — but always after taking a backup, and we check the site afterwards so nothing breaks.</p><p>If an update causes a problem, we roll it back immediately and fix it properly.</p>',
                    'points' => ['Backup before every update', 'Plugins, themes and core updated', 'Site checked after updates', 'Instant rollback if needed'],
                ],
                [
                    'eyebrow' => 'Security & backups',
                    'heading' => 'Protected, Backed Up and Monitored',
                    'default_image' => 'img/svc/security.webp',
                    'body' => '<p>We scan for malware and suspicious changes, keep regular off-site backups, and monitor uptime and SSL around the clock.</p><p>If something goes wrong — a hack, a broken update, an accidental deletion — we can restore your site from a recent backup quickly.</p>',
                    'points' => ['Security scans', 'Regular off-site backups', 'Uptime and SSL monitoring', 'Fast restore when needed'],
                ],
                [
                    'eyebrow' => 'Changes on request',
                    'heading' => 'Your Small Changes, Done Quickly',
                    'default_image' => 'img/svc/partner.webp',
                    'body' => '<p>New offer this week? Updated prices? A new team member or product? Just WhatsApp us the details and we\'ll update the site — no need to log in and figure it out yourself.</p><p>Your plan includes a set number of small changes every month. Bigger additions are quoted separately before we start, so there are no surprises.</p>',
                    'points' => ['WhatsApp your changes', 'Quick turnaround', 'Monthly change allowance', 'Clear quotes for bigger work'],
                ],
                [
                    'eyebrow' => 'Reporting',
                    'heading' => 'A Short Monthly Report',
                    'default_image' => 'img/svc/analytics.webp',
                    'body' => '<p>Each month you receive a short summary: what we updated, backups taken, uptime, any issues fixed, and key numbers like visitors and enquiries.</p><p>It\'s also where we suggest improvements — like a faster page, a better form or a new section — that could bring you more customers.</p>',
                    'points' => ['Work done this month', 'Uptime and backup status', 'Visitors and enquiries', 'Suggested improvements'],
                ],
            ],
        ];
    }
}
