<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\LandingPage;
use App\Models\Service;
use App\Models\Setting;
use App\Support\Defaults;
use Illuminate\Database\Seeder;

/** Starter content. Safe to run once on a fresh database; everything is editable in the admin. */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate(['key' => 'site'], ['value' => Defaults::site() + ['lead_emails' => 'reach@towercircle.in']]);
        Setting::firstOrCreate(['key' => 'pages'], ['value' => ['team' => true, 'blog' => true, 'faq' => true]]);

        $services = [
            ['Shopify Store Development', 'shopify-store-development', 's1', 'A complete Shopify store — design, products, payments, shipping and apps — ready to sell from day one.', [
                'Custom theme design for your brand', 'Product, collection and menu setup', 'Razorpay / UPI payments and shipping', 'WhatsApp, reviews and marketing apps', 'Mobile-first, fast-loading store', 'Training to manage orders yourself',
            ]],
            ['Custom Website Design', 'website-design-development', 's2', 'Fast, modern business websites that look great on mobile and are built to rank on Google.', [
                'Unique design — no cookie-cutter templates', 'Enquiry forms and WhatsApp button', 'Basic on-page SEO setup', 'Easy admin to edit content', 'Loads in under 2 seconds',
            ]],
            ['E-commerce Website', 'ecommerce-website-development', 's3', 'Custom online stores and web apps for businesses that need more than an off-the-shelf store.', [
                'Custom catalogue, cart and checkout', 'Payment gateway integration', 'Admin panel for orders and stock', 'Invoices and email notifications',
            ]],
            ['Domains & Hosting', 'domain-hosting', 's4', 'Domain registration, fast cloud hosting, business email and SSL — fully managed by us.', [
                'Domain registration and renewals', 'Fast, secure cloud hosting', 'Business email setup', 'Free SSL and daily backups',
            ]],
            ['Website Speed & SEO', 'website-speed-seo', 's5', 'Slow or invisible on Google? We fix speed, technical SEO and Google Business Profile.', [
                'Speed optimisation (Core Web Vitals)', 'Technical SEO fixes', 'Google Search Console & Analytics setup', 'Google Business Profile optimisation',
            ]],
            ['Website Maintenance', 'website-maintenance', 's6', 'Monthly care plans: updates, backups, security checks and small changes whenever you need them.', [
                'Regular updates and backups', 'Security monitoring', 'Content changes on request', 'Priority WhatsApp support',
            ]],
        ];
        foreach ($services as $i => [$title, $slug, $icon, $short, $features]) {
            Service::firstOrCreate(['slug' => $slug], [
                'title' => $title,
                'short_description' => $short,
                'body' => '<p>'.$short.'</p><p>Tell us about your business and goals — we\'ll recommend the right setup and give you a fixed price before any work starts.</p>',
                'features' => $features,
                'is_featured' => true,
                'sort' => $i,
            ]);
        }

        $faqs = [
            ['How much does a website cost?', 'It depends on the number of pages and features. Business websites and Shopify stores have clear fixed prices — share your requirement and we\'ll send an exact quote the same day.'],
            ['How long does it take?', 'Most business websites and Shopify stores go live in 1 to 3 weeks, depending on how quickly content and approvals come in.'],
            ['Will I own my website and domain?', 'Yes. The domain is registered in your name and you get full access to your website and admin.'],
            ['Do you provide hosting and support after launch?', 'Yes. We offer managed hosting, business email and monthly maintenance plans, with support on WhatsApp.'],
            ['Can I update the website myself?', 'Yes. Every website comes with an easy admin and a short training session.'],
        ];
        foreach ($faqs as $i => [$q, $a]) {
            Faq::firstOrCreate(['question' => $q], ['answer' => $a, 'sort' => $i]);
        }

        LandingPage::firstOrCreate(['slug' => 'shopify-store'], [
            'title' => 'Shopify – Google Ads',
            'eyebrow' => 'Shopify experts in India',
            'heading' => 'Get a Shopify Store That Starts Selling in 14 Days',
            'subheading' => 'Professional Shopify store design and setup — payments, shipping, products and apps done for you. Fixed price, no surprises.',
            'offer' => 'Free store audit this month',
            'benefits' => [
                ['title' => 'Built to sell', 'text' => 'Conversion-focused design, fast checkout and trust badges.'],
                ['title' => 'Everything set up', 'text' => 'Payments, shipping, taxes, products and apps — all done.'],
                ['title' => 'Support after launch', 'text' => 'Training plus WhatsApp support when you need help.'],
            ],
            'steps' => [
                ['title' => 'Free call', 'text' => 'We understand your products and goals.'],
                ['title' => 'Design', 'text' => 'You approve the store design.'],
                ['title' => 'Build & launch', 'text' => 'We set up everything and go live.'],
            ],
            'faqs' => [
                ['q' => 'Do I need to buy Shopify separately?', 'a' => 'Yes, the Shopify plan is billed by Shopify. We help you pick the right plan and set it up.'],
                ['q' => 'Can you move my existing store to Shopify?', 'a' => 'Yes. We migrate products, customers and orders from WooCommerce and other platforms.'],
            ],
            'form_heading' => 'Get a free Shopify quote',
            'cta_label' => 'Get My Free Quote',
            'service_id' => Service::where('slug', 'shopify-store-development')->value('id'),
        ]);
    }
}
