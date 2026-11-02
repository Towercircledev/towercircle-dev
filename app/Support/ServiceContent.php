<?php

namespace App\Support;

/**
 * Starter content for every service page. Loaded by the seeder on a fresh install,
 * and by `php artisan tc:service-content` on an existing site (only fills pages still
 * holding the short placeholder text, unless --force is used). Everything stays editable in the admin.
 */
class ServiceContent
{
    public static function all(): array
    {
        return [
            'shopify-store-development' => [
                'title' => 'Shopify Store Development',
                'sort' => 0,
                'icon' => 's1',
                'short' => 'A complete Shopify store — design, products, payments, shipping and apps — set up to sell from day one.',
                'seo_title' => 'Shopify Store Development Company in India',
                'seo_description' => 'Get a professional Shopify store designed, set up and ready to sell — Razorpay/UPI payments, shipping, GST, WhatsApp and marketing apps. 100+ websites built.',
                'body' => <<<'HTML'
<p>Shopify is the fastest way for an Indian brand to start selling online — but a store that <em>actually sells</em> needs more than a theme and a few product photos. We set up everything, so customers trust your store, find what they want quickly and check out without dropping off.</p>
<h2>Who this is for</h2>
<ul>
<li>Clothing, jewellery, home décor, beauty, food and handmade brands going online for the first time</li>
<li>Instagram and WhatsApp sellers who want a proper store with automatic orders and payments</li>
<li>Businesses moving from WooCommerce, Wix or another platform to Shopify</li>
</ul>
<h2>Already on Shopify?</h2>
<p>We also redesign slow or outdated Shopify stores, speed them up, fix checkout problems and set up proper tracking so your ads stop wasting money.</p>
HTML,
                'features' => [
                    'Custom theme design in your brand style', 'Product, collection and menu setup', 'Razorpay / UPI / COD payments',
                    'Shipping rates and courier integration (Shiprocket etc.)', 'GST-ready invoices and store policies', 'WhatsApp chat and order notifications',
                    'Reviews, discounts and abandoned-cart recovery', 'Google Analytics, Google Ads and Meta Pixel set up', 'Mobile-first, fast-loading pages',
                    'Training video and 30 days of free support',
                ],
                'faqs' => [
                    ['q' => 'How long does a Shopify store take?', 'a' => 'Most stores go live in 2 to 3 weeks. It depends mainly on how quickly product photos, descriptions and approvals are ready.'],
                    ['q' => 'Is the Shopify subscription included?', 'a' => 'No — the Shopify plan, paid apps and paid themes are billed by Shopify directly to you. We help you choose the plan that fits your size so you don\'t overpay.'],
                    ['q' => 'Can you upload all my products?', 'a' => 'Yes. Share them in an Excel sheet or Google Drive folder and we\'ll upload them, including variants like size and colour.'],
                    ['q' => 'Can I manage the store myself after launch?', 'a' => 'Yes. Shopify is easy to use, and we give you a short training session plus a recorded walkthrough.'],
                    ['q' => 'Can you move my WooCommerce store to Shopify?', 'a' => 'Yes. We migrate products, customers and past orders, and set up redirects so you keep your Google rankings.'],
                ],
            ],

            'website-design-development' => [
                'title' => 'Custom Website Design',
                'sort' => 1,
                'icon' => 's2',
                'short' => 'Fast, modern business websites that look great on mobile, rank on Google and turn visitors into enquiries.',
                'seo_title' => 'Website Design & Development Company in India',
                'seo_description' => 'Custom business website design and development — fast, mobile-first, SEO-ready, with enquiry forms and WhatsApp built in. Fixed price, quick delivery.',
                'body' => <<<'HTML'
<p>Your website is often the first thing a customer checks before calling you. We design websites that load fast, look professional on every phone, and make it easy for visitors to contact you — so the site works as your best salesperson.</p>
<h2>What makes our websites different</h2>
<ul>
<li><strong>Built for enquiries</strong> — clear call-to-action buttons, WhatsApp and call buttons, and short forms that land straight in your inbox.</li>
<li><strong>Fast</strong> — we build lean pages that load in about two seconds on mobile data, which Google rewards.</li>
<li><strong>Easy to update</strong> — change text, photos, services and blog posts yourself from a simple admin panel.</li>
<li><strong>Ready for Google</strong> — page titles, descriptions, sitemap, schema and Search Console set up from day one.</li>
</ul>
<h2>Good fit for</h2>
<ul>
<li>Service businesses — clinics, consultants, builders, schools, salons, agencies</li>
<li>Manufacturers and traders who need a professional company profile and product catalogue</li>
<li>Startups that need a landing page fast, with room to grow</li>
</ul>
HTML,
                'features' => [
                    'Unique design — no copy-paste templates', 'Mobile-first and fast-loading', 'Enquiry forms, WhatsApp and call buttons',
                    'Easy admin to edit content yourself', 'On-page SEO, sitemap and Google Search Console', 'Google Analytics and ad tracking',
                    'Free SSL (https)', 'Blog / news section', 'Training and 30 days of free support',
                ],
                'faqs' => [
                    ['q' => 'How many pages do I need?', 'a' => 'Most businesses need 5 to 8 pages: Home, About, Services (or one page per service), Work/Gallery, Contact and a blog. We\'ll recommend the right structure on our first call.'],
                    ['q' => 'Do you write the content?', 'a' => 'We can. Share the basics about your business and we\'ll write clear, Google-friendly content, or polish what you already have.'],
                    ['q' => 'WordPress or custom?', 'a' => 'We recommend based on your needs. Custom-built sites are faster and more secure; WordPress suits sites with lots of plugins. Either way you get an easy admin.'],
                    ['q' => 'Do I own the website?', 'a' => 'Yes. Once paid, the website, content and domain are yours.'],
                ],
            ],

            'ecommerce-website-development' => [
                'title' => 'E-commerce Website',
                'sort' => 2,
                'icon' => 's3',
                'short' => 'Custom online stores for businesses that need more than an off-the-shelf store — your rules, your workflow, no monthly platform fees.',
                'seo_title' => 'Custom E-commerce Website Development in India',
                'seo_description' => 'Custom e-commerce websites with your own catalogue, pricing, checkout, payment gateway, GST invoices and admin panel — built around how your business sells.',
                'body' => <<<'HTML'
<p>Shopify is perfect for most stores. But some businesses sell in ways a standard store can't handle — B2B price lists, bookings with time slots, subscriptions, custom products, multiple sellers or complex delivery rules. For these, we build a custom e-commerce website that works exactly the way you do.</p>
<h2>When a custom store makes sense</h2>
<ul>
<li>Different prices for retail and wholesale customers, or minimum order quantities</li>
<li>Services with bookings, time slots or delivery areas (like home services or rentals)</li>
<li>Products made to order, with custom options and price calculation</li>
<li>Subscriptions, prepaid packs or memberships</li>
<li>You want to avoid monthly platform and app fees as you grow</li>
</ul>
<h2>What you get</h2>
<p>A fast storefront, a smooth checkout with Razorpay/UPI, automatic GST invoices, order and customer emails, coupons, and an admin panel to manage products, orders, stock, customers and reports — all on your own server.</p>
HTML,
                'features' => [
                    'Custom catalogue, cart and checkout', 'Razorpay / UPI / card payments', 'Automatic GST invoices (PDF)',
                    'Admin panel for orders, stock and customers', 'Coupons, offers and prepaid packs', 'Order emails and WhatsApp updates',
                    'Bookings and time slots (if needed)', 'Sales reports and exports',
                ],
                'faqs' => [
                    ['q' => 'Shopify or custom — which should I choose?', 'a' => 'If your products and checkout are standard, Shopify is faster and cheaper to start. If you have special pricing, bookings or workflows, custom is better long term. We\'ll tell you honestly which fits.'],
                    ['q' => 'Are there monthly fees?', 'a' => 'No platform fees. You only pay for hosting (which we can manage) and payment gateway charges.'],
                    ['q' => 'Can it grow with my business?', 'a' => 'Yes. Because it\'s built for you, we can add features — like a mobile app, loyalty points or a seller portal — whenever you need them.'],
                ],
            ],

            'digital-performance-marketing' => [
                'title' => 'Digital & Performance Marketing',
                'sort' => 3,
                'icon' => 's5',
                'short' => 'Google Ads and Meta (Facebook & Instagram) campaigns built for leads and sales — tracked to real enquiries, not just clicks.',
                'seo_title' => 'Performance Marketing Agency – Google Ads & Meta Ads',
                'seo_description' => 'Google Ads and Facebook/Instagram ads managed for leads and sales, with landing pages and conversion tracking set up properly. Clear monthly reports.',
                'body' => <<<'HTML'
<p>Most small businesses lose money on ads for the same reasons: they send traffic to the homepage, track the wrong things and optimise for clicks instead of customers. We fix all three — so every rupee you spend can be traced to an enquiry or a sale.</p>
<h2>What we manage</h2>
<ul>
<li><strong>Google Ads</strong> — Search campaigns that catch people already looking for what you sell, plus Performance Max and YouTube where it makes sense.</li>
<li><strong>Meta Ads</strong> — Facebook and Instagram campaigns for brand awareness, leads and online sales, with creatives that stop the scroll.</li>
<li><strong>Amazon Ads</strong> — Sponsored Products, Brands and Display for marketplace sellers (see <a href="/services/amazon-ppc-management">Amazon PPC management</a>).</li>
<li><strong>Landing pages</strong> — a dedicated page for each campaign, matched to the ad, with a short form and WhatsApp button.</li>
<li><strong>Tracking</strong> — Google Analytics 4, Google Ads conversions, Meta Pixel and Conversions API, so the platforms learn which clicks become customers.</li>
</ul>
<p>Your ad budget is paid directly to Google and Meta from your own account — you stay in full control.</p>
HTML,
                'features' => [
                    'Google Search, Performance Max and YouTube ads', 'Facebook and Instagram ads', 'Landing pages built for each campaign',
                    'GA4, Google Ads and Meta conversion tracking', 'Ad copy and creatives', 'Negative keywords and spam-lead filtering',
                    'Weekly optimisation', 'Monthly report: leads, cost per lead, sales',
                ],
                'faqs' => [
                    ['q' => 'How much should I spend on ads?', 'a' => 'It depends on your industry and city. We\'ll suggest a starting budget after a quick audit, start small, and scale only what brings leads at a cost that works for you.'],
                    ['q' => 'How soon will I see results?', 'a' => 'Google Search ads usually bring enquiries within the first week. Meta ads need 2 to 4 weeks of testing to find the best audience and creatives.'],
                    ['q' => 'Do I own the ad accounts?', 'a' => 'Yes. Ads run from accounts in your business name; we get manager access. If you ever leave, everything stays with you.'],
                    ['q' => 'Google Ads or Facebook ads — which is better?', 'a' => 'Google catches people already searching for your service (high intent). Facebook and Instagram create demand and work well for visual products. Most businesses do best with a mix.'],
                ],
            ],

            'amazon-ppc-management' => [
                'title' => 'Amazon PPC Management',
                'sort' => 4,
                'icon' => 's5',
                'short' => 'Amazon Sponsored Products, Brands and Display ads managed for profitable sales — lower ACoS, better ranking, clear reporting.',
                'seo_title' => 'Amazon PPC Management Agency in India – Amazon Ads',
                'seo_description' => 'Amazon PPC management for sellers on Amazon.in: Sponsored Products, Sponsored Brands and Display ads, keyword harvesting, ACoS/TACoS optimisation and listing improvements.',
                'body' => <<<'HTML'
<p>On Amazon, ads decide who gets seen. But without a clear structure, PPC quickly turns into high ACoS, wasted clicks on irrelevant searches and ads that bring sales today but no lasting ranking.</p>
<p>We manage Amazon advertising for sellers on Amazon.in (and other marketplaces) with one goal: <strong>profitable growth</strong>. That means the right campaign structure, constant keyword research, bids based on your margins, and listings that convert the traffic you pay for.</p>
<h2>Who this is for</h2>
<ul>
<li>Brands and sellers already on Amazon whose ad spend keeps rising but profit doesn't</li>
<li>New products that need a strong launch to start ranking organically</li>
<li>Brand-registered sellers ready to use Sponsored Brands, video and Display ads</li>
<li>Shopify / D2C brands expanding to Amazon</li>
</ul>
HTML,
                'features' => [
                    'Sponsored Products, Sponsored Brands and Sponsored Display', 'Campaign structure: auto, broad, phrase, exact and product targeting',
                    'Keyword harvesting and negative keywords every week', 'Bids set by your margin and target ACoS', 'Product launch campaigns for new ASINs',
                    'Brand defence and competitor targeting', 'Listing review: title, images, bullets, A+ content', 'Monthly report: sales, ACoS, TACoS, ranking',
                ],
                'faqs' => [
                    ['q' => 'What are ACoS and TACoS?', 'a' => 'ACoS is ad spend divided by ad sales. TACoS is ad spend divided by your total sales (ads + organic). We watch both: a healthy account sees TACoS fall over time as organic sales grow.'],
                    ['q' => 'How much should I spend on Amazon ads?', 'a' => 'It depends on your category, margins and goals (launch vs profit). We\'ll suggest a starting budget after auditing your account and products.'],
                    ['q' => 'Do you need access to my Seller Central?', 'a' => 'We need access to the Amazon Ads console and limited Seller Central reports. You add us as a user — you never share your password, and you can remove access any time.'],
                    ['q' => 'How soon will I see results?', 'a' => 'Wasted spend usually drops within the first 2–3 weeks. Ranking and organic growth build over 2–3 months of consistent optimisation.'],
                    ['q' => 'Do you also manage Flipkart ads?', 'a' => 'Yes, we can manage Flipkart and other marketplace ads with the same approach. Ask us on WhatsApp.'],
                ],
            ],

            'custom-software-development' => [
                'title' => 'Custom Software (CRM, HRM, ERP)',
                'sort' => 5,
                'icon' => 's6',
                'short' => 'CRM, HRM, ERP, billing and booking software built around how your business actually works — not the other way round.',
                'seo_title' => 'Custom CRM, HRM & ERP Software Development in India',
                'seo_description' => 'Custom business software — CRM, HRM & payroll, ERP & inventory, billing with GST, booking systems and client portals. Built for your workflow, owned by you.',
                'body' => <<<'HTML'
<p>Ready-made software forces you to change how you work — and charges you per user, every month, forever. We build web-based software around your exact process: your fields, your approvals, your reports. It runs in any browser, works on mobile, and you own it.</p>
<h2>What we build</h2>
<ul>
<li><strong>CRM</strong> — leads from your website and ads in one inbox, follow-up reminders, sales pipeline, quotes and WhatsApp in one click.</li>
<li><strong>HRM & payroll</strong> — employee records, attendance, leave, salary slips, documents and role-based access.</li>
<li><strong>ERP & inventory</strong> — purchases, stock across locations, production, sales orders and GST invoices in one system.</li>
<li><strong>Billing & invoicing</strong> — GST invoices, payments, outstanding reports and automatic reminders.</li>
<li><strong>Booking & scheduling</strong> — online bookings with time slots, staff assignment, payments and SMS/WhatsApp updates.</li>
<li><strong>Client & vendor portals</strong> — let customers or partners log in to see orders, invoices, projects and documents.</li>
</ul>
<h2>Why custom instead of off-the-shelf</h2>
<ul>
<li>Fits your process exactly — no paying for 100 features you don't use</li>
<li>No per-user monthly fees as your team grows</li>
<li>Your data on your own server, with backups</li>
<li>Connects with what you already use — website, Tally/Excel exports, payment gateway, WhatsApp, email</li>
</ul>
HTML,
                'features' => [
                    'CRM with lead pipeline and follow-ups', 'HRM: attendance, leave and payroll', 'ERP: inventory, purchase and sales',
                    'GST billing and invoice PDFs', 'Booking and scheduling systems', 'Client and vendor login portals',
                    'Role-based access for staff', 'Dashboards and Excel exports', 'WhatsApp, email and payment integrations', 'Hosting, backups and ongoing support',
                ],
                'faqs' => [
                    ['q' => 'How long does custom software take?', 'a' => 'A focused first version (one module, like a CRM or billing system) usually takes 3 to 6 weeks. Larger ERP systems are built in phases so you start using it early.'],
                    ['q' => 'Is it a mobile app?', 'a' => 'It\'s a web application that works in any browser, on desktop and mobile — no app store needed. We can add a mobile app later if you need one.'],
                    ['q' => 'Who owns the software and data?', 'a' => 'You do. It runs on your own server or cloud account, and the data is always yours.'],
                    ['q' => 'Can you connect it to Tally or my website?', 'a' => 'Yes. We commonly connect to websites, payment gateways, WhatsApp, email and export data in formats your accountant can import into Tally.'],
                    ['q' => 'What does it cost?', 'a' => 'It depends on the modules and users. After a short discovery call we give you a fixed price per phase, so there are no surprises.'],
                ],
            ],

            'website-speed-seo' => [
                'title' => 'Website Speed & SEO',
                'sort' => 6,
                'icon' => 's5',
                'short' => 'Slow or invisible on Google? We fix speed, technical SEO and your Google Business Profile so customers find you.',
                'seo_title' => 'Website Speed Optimisation & SEO Services',
                'seo_description' => 'Make your website load fast and rank better on Google — Core Web Vitals, technical SEO, on-page optimisation and Google Business Profile for local searches.',
                'body' => <<<'HTML'
<p>A slow website loses visitors before it even loads, and a website Google can't understand never shows up. We fix both — and set you up to win the local searches that bring real customers.</p>
<h2>What we do</h2>
<ul>
<li><strong>Speed</strong> — image compression, caching, removing heavy plugins and scripts, and fixing Core Web Vitals so pages load in about two seconds.</li>
<li><strong>Technical SEO</strong> — indexing problems, broken links, redirects, sitemaps, schema and mobile issues.</li>
<li><strong>On-page SEO</strong> — page titles, descriptions, headings and content matched to what your customers search.</li>
<li><strong>Local SEO</strong> — Google Business Profile optimisation, reviews strategy and location pages for the areas you serve.</li>
</ul>
<p>You get a before-and-after report with Google PageSpeed scores and Search Console data, so you can see exactly what changed.</p>
HTML,
                'features' => [
                    'Speed audit and Core Web Vitals fixes', 'Image compression and caching', 'Technical SEO fixes',
                    'Page titles, descriptions and headings', 'Google Search Console and Analytics', 'Google Business Profile optimisation',
                    'Schema (rich results) set up', 'Before/after report',
                ],
                'faqs' => [
                    ['q' => 'How long does SEO take to show results?', 'a' => 'Speed improvements show immediately. Rankings usually improve over 2 to 4 months, depending on competition.'],
                    ['q' => 'Do you guarantee first position on Google?', 'a' => 'No honest agency can. We guarantee the work: a fast, technically sound site with content targeting the right searches — which is what Google rewards.'],
                    ['q' => 'Can you speed up my WordPress or Shopify site?', 'a' => 'Yes. Most of our speed work is on WordPress and Shopify sites.'],
                ],
            ],

            'domain-hosting' => [
                'title' => 'Domains & Hosting',
                'sort' => 7,
                'icon' => 's4',
                'short' => 'Domain registration, fast cloud hosting, business email and SSL — fully managed, with renewals handled for you.',
                'seo_title' => 'Domain, Hosting & Business Email Services',
                'seo_description' => 'Domain registration, fast managed cloud hosting in India, Google Workspace / Zoho business email and SSL — set up, monitored and renewed for you.',
                'body' => <<<'HTML'
<p>Expired domains, a hacked server or email that stops working can take your business offline overnight. We set up and look after the technical side — so your website and email simply work.</p>
<h2>What's included</h2>
<ul>
<li><strong>Domains</strong> — registration and transfers (.in, .com and more), DNS set up correctly, and renewals tracked so they never expire.</li>
<li><strong>Hosting</strong> — fast cloud servers in Mumbai for quick loading in India, daily backups and security updates.</li>
<li><strong>Business email</strong> — Google Workspace or Zoho Mail on your domain (you@yourbusiness.com), with spam protection set up properly.</li>
<li><strong>SSL & monitoring</strong> — free https on every site, and 24/7 uptime monitoring so we know about problems before your customers do.</li>
</ul>
<p>Everything is registered in your name. We just make sure it keeps running.</p>
HTML,
                'features' => [
                    'Domain registration and transfer', 'Fast cloud hosting (Mumbai region)', 'Google Workspace / Zoho business email',
                    'Free SSL certificates', 'Daily backups', 'Uptime monitoring and alerts', 'Security updates', 'Renewal reminders — nothing expires by surprise',
                ],
                'faqs' => [
                    ['q' => 'Can you move my existing website and email?', 'a' => 'Yes. We migrate websites and mailboxes with no data loss and minimal downtime, usually overnight.'],
                    ['q' => 'Whose name is the domain in?', 'a' => 'Always yours. We manage it on your behalf, but you own it.'],
                    ['q' => 'What happens if my site goes down?', 'a' => 'Our monitoring checks your site every few minutes and alerts us immediately, so we can fix it — often before you notice.'],
                ],
            ],

            'website-maintenance' => [
                'title' => 'Website Maintenance',
                'sort' => 8,
                'icon' => 's6',
                'short' => 'Monthly care plans: updates, backups, security checks and small changes whenever you need them.',
                'seo_title' => 'Website Maintenance & Support Plans',
                'seo_description' => 'Monthly website maintenance: updates, backups, security, uptime monitoring and content changes on request, with WhatsApp support.',
                'body' => <<<'HTML'
<p>Websites need regular care — plugin updates, security fixes, backups and the small changes every business needs. Our maintenance plans take all of that off your plate for a fixed monthly fee.</p>
<h2>Every month we</h2>
<ul>
<li>Update WordPress, themes, plugins or Shopify apps safely, with a backup first</li>
<li>Check security and fix anything suspicious</li>
<li>Monitor uptime and SSL around the clock</li>
<li>Make your content changes — new offers, photos, prices, pages</li>
<li>Send a short report of what we did</li>
</ul>
<p>Need something urgent? Message us on WhatsApp and we'll take care of it.</p>
HTML,
                'features' => [
                    'Software and plugin updates', 'Daily or weekly backups', 'Security monitoring', 'Uptime and SSL monitoring',
                    'Content changes on request', 'Priority WhatsApp support', 'Monthly report',
                ],
                'faqs' => [
                    ['q' => 'Do you maintain websites you didn\'t build?', 'a' => 'Yes. We start with a quick check-up of the site, then take over maintenance.'],
                    ['q' => 'What counts as a "small change"?', 'a' => 'Text, photo, price and banner updates, adding a product or a page section. Bigger additions are quoted separately.'],
                    ['q' => 'Is there a lock-in?', 'a' => 'No. Plans are monthly or yearly and you can stop any time before renewal.'],
                ],
            ],
        ];
    }
}
