<?php

namespace App\Support;

/** Starter blog articles. Body HTML lives in resources/content/blog/{slug}.html. Added with `php artisan tc:blog-content`. */
class BlogContent
{
    public static function all(): array
    {
        return [
            'shopify-vs-woocommerce-india' => [
                'title' => 'Shopify vs WooCommerce: Which Is Better for Indian Businesses in 2026?',
                'excerpt' => 'An honest comparison of Shopify and WooCommerce for Indian sellers — setup, cost, UPI and COD, speed, SEO, security and which one to choose.',
                'seo_title' => 'Shopify vs WooCommerce India (2026): Which Should You Choose?',
                'tags' => ['Shopify', 'WooCommerce', 'E-commerce'],
                'cover' => 'img/svc/design.webp',
            ],
            'shopify-store-cost-india' => [
                'title' => 'How Much Does a Shopify Store Cost in India? (Complete Breakdown)',
                'excerpt' => 'Every cost of running a Shopify store in India explained — plans, themes, apps, setup, payment fees and the hidden costs most sellers forget.',
                'seo_title' => 'Shopify Store Cost in India: Complete Breakdown',
                'tags' => ['Shopify', 'Pricing', 'E-commerce'],
                'cover' => 'img/svc/dashboard.webp',
            ],
            'shopify-apps-india' => [
                'title' => '10 Must-Have Shopify Apps for Indian Stores (Payments, Shipping, WhatsApp)',
                'excerpt' => 'The 10 types of Shopify apps every Indian store needs — UPI payments, shipping, WhatsApp, COD verification, reviews, GST invoices and more.',
                'seo_title' => 'Best Shopify Apps for Indian Stores – 10 Must-Haves',
                'tags' => ['Shopify', 'Apps'],
                'cover' => 'img/svc/mobile.webp',
            ],
            'website-cost-india' => [
                'title' => 'How Much Does a Website Cost in India? Business Website Price Guide',
                'excerpt' => 'What really decides the cost of a business website in India, what should be included, ongoing costs, and red flags to watch for in a quote.',
                'seo_title' => 'Website Design Cost in India: Business Website Price Guide',
                'tags' => ['Website design', 'Pricing'],
                'cover' => 'img/svc/research.webp',
            ],
            'website-slow-fixes' => [
                'title' => 'Why Is My Website Slow? 8 Fixes That Improve Speed and Google Ranking',
                'excerpt' => 'The 8 fixes that make the biggest difference to website speed — images, hosting, plugins, caching, fonts and more — and how to measure results.',
                'seo_title' => 'Why Is My Website Slow? 8 Speed Fixes That Work',
                'tags' => ['Speed', 'SEO'],
                'cover' => 'img/svc/cloud.webp',
            ],
            'google-business-profile-local-seo' => [
                'title' => 'Google Business Profile: How to Rank in Local Searches (Step-by-Step)',
                'excerpt' => 'A step-by-step guide to setting up and optimising your Google Business Profile to appear in the local map results and get more calls.',
                'seo_title' => 'Google Business Profile Optimisation: Step-by-Step Local SEO Guide',
                'tags' => ['Local SEO', 'Google'],
                'cover' => 'img/svc/marketing.webp',
            ],
            'google-ads-vs-facebook-ads' => [
                'title' => 'Google Ads vs Facebook Ads: Which Should Small Businesses Choose?',
                'excerpt' => 'Intent vs interest: when Google Ads works better, when Meta (Facebook & Instagram) ads win, lead quality, budgets and tracking.',
                'seo_title' => 'Google Ads vs Facebook Ads for Small Businesses in India',
                'tags' => ['Google Ads', 'Meta Ads', 'Marketing'],
                'cover' => 'img/svc/analytics.webp',
            ],
            'amazon-ppc-beginners-guide' => [
                'title' => 'Amazon PPC for Beginners: ACoS, TACoS and How to Stop Wasting Ad Spend',
                'excerpt' => 'Amazon ad types, ACoS and TACoS explained, break-even ACoS, a simple campaign structure and the 7 mistakes that waste the most money.',
                'seo_title' => 'Amazon PPC for Beginners: ACoS, TACoS & Campaign Structure',
                'tags' => ['Amazon PPC', 'Marketplace'],
                'cover' => 'img/svc/partner.webp',
            ],
            'excel-vs-crm-signs' => [
                'title' => 'Excel vs CRM: 7 Signs Your Business Needs CRM Software',
                'excerpt' => 'Leads slipping through, forgotten follow-ups, broken spreadsheets? 7 signs it\'s time to move from Excel to a CRM — and how to switch smoothly.',
                'seo_title' => 'Excel vs CRM: 7 Signs Your Business Needs a CRM',
                'tags' => ['CRM', 'Custom software'],
                'cover' => 'img/svc/team.webp',
            ],
            'custom-software-vs-ready-made' => [
                'title' => 'Custom Software vs Ready-Made (Zoho, Tally etc.): Which Is Right for You?',
                'excerpt' => 'When off-the-shelf software is enough, when custom software pays off, total cost, data ownership and the hybrid approach most SMEs use.',
                'seo_title' => 'Custom Software vs Ready-Made Software: How to Choose',
                'tags' => ['Custom software', 'ERP', 'CRM'],
                'cover' => 'img/svc/security.webp',
            ],
        ];
    }

    public static function body(string $slug): ?string
    {
        $file = resource_path("content/blog/{$slug}.html");

        return is_file($file) ? file_get_contents($file) : null;
    }
}
