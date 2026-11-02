<?php

namespace App\Support;

/** Default content for the e-commerce landing page template. Everything here is editable in the admin. */
class EcomLanding
{
    public const ICONS = [
        'palette' => 'Palette', 'phone2' => 'Mobile', 'card' => 'Card / payment', 'truck' => 'Truck / shipping', 'file' => 'Document',
        'wa' => 'WhatsApp', 'star' => 'Star', 'store' => 'Store', 'search' => 'Search', 'chart' => 'Chart', 'shield' => 'Shield',
        'key' => 'Key', 'check' => 'Tick', 'megaphone' => 'Megaphone', 'code' => 'Code', 'users' => 'People', 'cal' => 'Calendar', 'bolt' => 'Lightning',
    ];

    public static function defaults(): array
    {
        $p = fn (string $t) => ['p' => $t];

        return [
            'hero' => [
                'badge' => '🚀 Brand + Store + Launch — done for you',
                'heading' => 'Launch Your Online Store in 7–10 Working Days',
                'highlight' => '7–10 Working Days',
                'text' => 'From idea to your first online order — we create your brand, build your store on Shopify, WooCommerce or custom, and set up payments, shipping and marketing. You focus on your products.',
                'platforms' => [$p('Shopify'), $p('WooCommerce'), $p('Custom e-commerce')],
                'points' => [$p('Brand identity & logo'), $p('UPI, cards & COD ready'), $p('Shipping & GST invoices'), $p('Mobile-first & fast')],
                'trust' => [$p('100+ websites built'), $p('Starting at ₹14,999'), $p('You own everything')],
            ],
            'form' => [
                'heading' => 'Get your free store plan & quote',
                'text' => 'Tell us a little about your business. We\'ll WhatsApp you a plan within a few hours.',
                'button' => 'Get My Free Store Plan',
            ],
            'pain' => [
                'show' => true,
                'eyebrow' => 'Sound familiar?',
                'heading' => 'Selling Online Shouldn\'t Feel This Hard',
                'items' => [
                    ['emoji' => '📱', 'title' => 'Orders lost in DMs', 'text' => 'Taking orders on Instagram and WhatsApp, chasing payments and losing track of who paid.'],
                    ['emoji' => '🤔', 'title' => 'Don\'t know where to start', 'text' => 'Shopify or WooCommerce? Which payment gateway? Which courier? Too many decisions.'],
                    ['emoji' => '😕', 'title' => 'Store looks amateur', 'text' => 'A template store with no real brand doesn\'t earn the trust needed for people to pay online.'],
                    ['emoji' => '💸', 'title' => 'Visitors but no sales', 'text' => 'Slow pages, confusing checkout, no COD or tracking — people leave before they buy.'],
                ],
                'answer' => 'We handle everything from idea to first order — so you launch with a brand customers trust and a store that\'s ready to sell.',
            ],
            'journey' => [
                'show' => true,
                'eyebrow' => 'Idea → implementation',
                'heading' => 'Your Complete Journey to Selling Online',
                'text' => 'Six clear steps. You always know what\'s happening and what comes next.',
                'steps' => [
                    ['eyebrow' => 'Step 1 · Idea & research', 'heading' => 'We Shape Your Idea Into a Clear Plan', 'default_image' => 'img/svc/research.webp',
                        'body' => '<p>We start by understanding your products, your ideal customer and your competitors. What are similar brands charging? What do their customers love — and complain about? Where\'s the gap you can own?</p><p>You get a short, practical plan: positioning, pricing guidance, the right platform for you, and the features your store needs on day one.</p>',
                        'points' => [$p('Competitor & market review'), $p('Target customer defined'), $p('Platform recommendation'), $p('Launch plan you approve')]],
                    ['eyebrow' => 'Step 2 · Branding', 'heading' => 'A Brand People Remember — and Trust', 'default_image' => 'img/svc/design.webp',
                        'body' => '<p>Online, your brand is your shop window. We create a complete identity that looks professional everywhere — on your store, Instagram, packaging and invoices.</p><p>Name ideas if you need them, logo, colours and fonts, brand guidelines, packaging and label designs, and social media templates — so everything looks consistent from day one.</p>',
                        'points' => [$p('Brand name ideas & tagline'), $p('Logo, colours & typography'), $p('Packaging & label design'), $p('Social media templates')]],
                    ['eyebrow' => 'Step 3 · Store design', 'heading' => 'A Store Designed for Mobile Shoppers', 'default_image' => 'img/svc/mobile.webp',
                        'body' => '<p>Most of your customers will shop on a phone. We design mobile-first: big product photos, clear prices and delivery promises, trust badges, reviews and a checkout that takes seconds.</p><p>You see the homepage, product page and checkout designs and approve them before we build anything.</p>',
                        'points' => [$p('Mobile-first layouts'), $p('Product & collection pages'), $p('Trust badges & reviews'), $p('Design approval first')]],
                    ['eyebrow' => 'Step 4 · Build & setup', 'heading' => 'Everything Set Up So You Can Start Selling', 'default_image' => 'img/svc/dashboard.webp',
                        'body' => '<p>We build your store on Shopify, WooCommerce or a custom platform — whichever fits your business — and set up every detail: products and variants, payments with UPI, cards and COD, shipping rates and courier integration, GST and invoices, policies and order emails.</p><p>WhatsApp order updates, discount codes, abandoned-cart reminders and Google/Meta tracking are included, so your marketing is measurable from the first rupee.</p>',
                        'points' => [$p('Razorpay / UPI / COD'), $p('Shiprocket & couriers'), $p('GST invoices & policies'), $p('Analytics & ad tracking')]],
                    ['eyebrow' => 'Step 5 · Launch', 'heading' => 'Tested, Launched and Handed Over to You', 'default_image' => 'img/svc/analytics.webp',
                        'body' => '<p>Before launch we place real test orders, check every page on different phones, connect your domain with SSL and make sure emails and WhatsApp messages work.</p><p>Then we train you to add products, process orders and run offers — with a recorded walkthrough to refer back to.</p>',
                        'points' => [$p('Real test orders'), $p('Domain & SSL connected'), $p('Training + recorded video'), $p('30 days free support')]],
                    ['eyebrow' => 'Step 6 · Grow', 'heading' => 'Get Your First Customers — and Keep Them Coming', 'default_image' => 'img/svc/marketing.webp',
                        'body' => '<p>A store needs visitors. We can run Google, Instagram/Facebook and Amazon ads, set up SEO for your products and create email and WhatsApp campaigns that bring customers back.</p><p>Every month you see clear numbers: visitors, orders, cost per order and what we\'ll improve next.</p>',
                        'points' => [$p('Google & Meta ads'), $p('Product SEO'), $p('WhatsApp & email campaigns'), $p('Monthly growth report')]],
                ],
            ],
            'compare' => [
                'show' => true,
                'eyebrow' => 'Which platform?',
                'heading' => 'Shopify, WooCommerce or Custom — We\'ll Recommend the Right One',
                'text' => 'No one-size-fits-all. We suggest the platform that fits your products, budget and plans — honestly.',
                'col1' => 'Shopify', 'col2' => 'WooCommerce', 'col3' => 'Custom e-commerce',
                'rows' => [
                    ['label' => 'Best for', 'a' => 'New D2C brands that want to launch fast', 'b' => 'Businesses with a WordPress site and content/SEO focus', 'c' => 'Special pricing, B2B, bookings, subscriptions or unique workflows'],
                    ['label' => 'Launch time', 'a' => 'Fastest', 'b' => 'Fast', 'c' => 'Phased — core store first'],
                    ['label' => 'Maintenance', 'a' => 'Handled by Shopify', 'b' => 'Needs regular updates', 'c' => 'Managed by us'],
                    ['label' => 'Monthly costs', 'a' => 'Shopify plan + apps', 'b' => 'Hosting + plugins', 'c' => 'Hosting only, no platform fees'],
                    ['label' => 'Customisation', 'a' => 'Good', 'b' => 'Very high', 'c' => 'Unlimited'],
                    ['label' => 'UPI, COD, shipping, GST', 'a' => '✓', 'b' => '✓', 'c' => '✓'],
                ],
                'button' => 'Not sure? Get a free recommendation',
            ],
            'packages' => [
                'show' => true,
                'eyebrow' => 'Packages',
                'heading' => 'Choose How Much We Do for You',
                'text' => 'Every project gets a fixed quote after a free call — no hidden costs.',
                'items' => [
                    ['name' => 'Store Launch', 'for' => 'You have a brand. You need a store that sells.', 'price' => 'Starting at ₹14,999', 'price_note' => 'Fixed price after a free call', 'popular' => false, 'button' => 'Get a quote',
                        'features' => [$p('Shopify / WooCommerce store'), $p('Theme customised to your brand'), $p('Products & collections setup'), $p('UPI, cards & COD payments'), $p('Shipping & GST setup'), $p('Training + 30 days support')]],
                    ['name' => 'Brand + Store', 'for' => 'Starting from an idea? We build the brand and the store.', 'price' => 'Get quote', 'price_note' => 'Fixed price after a free call', 'popular' => true, 'button' => 'Get a quote',
                        'features' => [$p('Everything in Store Launch'), $p('Name ideas, logo & brand guidelines'), $p('Packaging & label design'), $p('Custom store design'), $p('WhatsApp, reviews & abandoned cart'), $p('Social media templates'), $p('Google & Meta tracking')]],
                    ['name' => 'Launch & Grow', 'for' => 'Brand, store and marketing to get your first customers.', 'price' => 'Get quote', 'price_note' => 'Fixed price after a free call', 'popular' => false, 'button' => 'Get a quote',
                        'features' => [$p('Everything in Brand + Store'), $p('Google & Meta ads setup'), $p('3 months ads management'), $p('Product SEO'), $p('Email & WhatsApp campaigns'), $p('Monthly growth report')]],
                ],
                'note' => 'Store projects start at ₹14,999. Branding and marketing are quoted after a free call. Need a custom e-commerce platform? Tell us your requirements.',
            ],
            'included' => [
                'show' => true,
                'eyebrow' => 'What\'s included',
                'heading' => 'Everything Your Store Needs to Sell',
                'items' => [
                    ['icon' => 'palette', 'title' => 'Complete branding', 'text' => 'Logo, colours, fonts, guidelines.'],
                    ['icon' => 'phone2', 'title' => 'Mobile-first design', 'text' => 'Built for shoppers on phones.'],
                    ['icon' => 'card', 'title' => 'UPI, cards & COD', 'text' => 'Razorpay, PayU or Cashfree.'],
                    ['icon' => 'truck', 'title' => 'Shipping integration', 'text' => 'Shiprocket & courier partners.'],
                    ['icon' => 'file', 'title' => 'GST invoices', 'text' => 'Automatic, compliant invoices.'],
                    ['icon' => 'wa', 'title' => 'WhatsApp updates', 'text' => 'Order confirmations & chat.'],
                    ['icon' => 'star', 'title' => 'Reviews', 'text' => 'Photo reviews that build trust.'],
                    ['icon' => 'store', 'title' => 'Abandoned cart', 'text' => 'Win back unfinished orders.'],
                    ['icon' => 'search', 'title' => 'SEO-ready', 'text' => 'Titles, sitemap, schema.'],
                    ['icon' => 'chart', 'title' => 'Ad tracking', 'text' => 'GA4, Google Ads, Meta Pixel.'],
                    ['icon' => 'shield', 'title' => 'Secure & fast', 'text' => 'SSL, backups, quick loading.'],
                    ['icon' => 'key', 'title' => 'You own it all', 'text' => 'Domain, store, brand files.'],
                ],
            ],
            'timeline' => [
                'show' => true,
                'eyebrow' => 'Timeline',
                'heading' => 'Live in 7–10 Working Days',
                'text' => 'A typical Brand + Store project, once your product details and photos are ready.',
                'items' => [
                    ['day' => 'Day 1–2', 'title' => 'Plan & brand', 'text' => 'Research, platform choice, logo & colours.'],
                    ['day' => 'Day 3–4', 'title' => 'Design', 'text' => 'Homepage, product page & checkout designs for approval.'],
                    ['day' => 'Day 5–8', 'title' => 'Build', 'text' => 'Products, payments, shipping, GST, WhatsApp & tracking.'],
                    ['day' => 'Day 9–10', 'title' => 'Test & launch', 'text' => 'Test orders, domain, training — you\'re live.'],
                ],
            ],
            'proof' => [
                'eyebrow' => 'Our work',
                'heading' => 'Stores We\'ve Launched',
                'reviews_heading' => 'What Our Clients Say',
                'stats' => [
                    ['number' => '100+', 'label' => 'Websites built'],
                    ['number' => '7–10', 'label' => 'Working days to launch'],
                    ['number' => '3', 'label' => 'Platforms: Shopify, Woo, custom'],
                    ['number' => '30', 'label' => 'Days free support'],
                ],
            ],
            'guarantees' => [
                'show' => true,
                'eyebrow' => 'Our promise',
                'heading' => 'Zero Risk, Zero Surprises',
                'items' => [
                    ['icon' => 'file', 'title' => 'Fixed price', 'text' => 'Agreed before we start. No hidden costs.'],
                    ['icon' => 'key', 'title' => 'You own everything', 'text' => 'Domain, store, brand files — all yours.'],
                    ['icon' => 'check', 'title' => 'Approve before we build', 'text' => 'See designs first. Changes are easy.'],
                    ['icon' => 'wa', 'title' => '30 days free support', 'text' => 'On WhatsApp after you launch.'],
                ],
            ],
            'faq' => [
                'eyebrow' => 'FAQ',
                'heading' => 'Questions Before You Start',
                'items' => [
                    ['q' => 'How much does an online store cost?', 'a' => 'Store projects start at ₹14,999. The final price depends on the platform, number of products and how much branding you need. After a free 15-minute call we send a fixed, itemised quote — no surprises later.'],
                    ['q' => 'Can you really launch in 7–10 working days?', 'a' => 'Yes, for most stores — once your product details and photos are ready. Larger catalogues or custom platforms are delivered in phases, with the core store live first.'],
                    ['q' => 'Is branding included in the 7–10 days?', 'a' => 'Yes, the timeline includes logo, colours and basic brand guidelines, as long as name and logo approvals come quickly. A full brand identity with name research, packaging and label designs can add a few extra working days — we\'ll share an exact plan before we start.'],
                    ['q' => 'Shopify, WooCommerce or custom — which should I choose?', 'a' => 'We\'ll recommend honestly based on your products, budget and plans. Shopify suits most new brands; WooCommerce suits businesses with an existing WordPress site; custom suits special pricing, B2B or unique workflows.'],
                    ['q' => 'I only have an idea. Can you help with the brand name and logo?', 'a' => 'Yes. Our Brand + Store package covers name ideas, logo, colours, fonts, brand guidelines, packaging and social media templates.'],
                    ['q' => 'Do you write product descriptions and take photos?', 'a' => 'We can write product descriptions and guide you on photos. Product photography can be arranged separately if you need it.'],
                    ['q' => 'Are Shopify fees and apps included?', 'a' => 'Platform subscriptions, paid apps and payment gateway fees are paid directly by you to those providers. We help you choose plans that fit your size so you don\'t overpay.'],
                    ['q' => 'Can I manage the store myself after launch?', 'a' => 'Yes. We train you and give you a recorded walkthrough. We\'re also available for monthly support if you prefer.'],
                    ['q' => 'Can you move my existing store?', 'a' => 'Yes. We migrate products, customers and orders, and set up redirects so you keep your Google rankings.'],
                ],
            ],
            'final' => [
                'heading' => 'Ready to Start Selling Online?',
                'text' => 'Share your idea — we\'ll send a free store plan and fixed quote within a few hours. No obligation.',
                'points' => [$p('Free 15-minute consultation'), $p('Platform recommendation'), $p('Fixed, itemised quote')],
                'whatsapp_text' => 'Hi Towercircle, I want to launch an online store.',
            ],
        ];
    }
}
