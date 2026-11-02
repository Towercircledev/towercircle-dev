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

        foreach (\App\Support\ServiceContent::all() as $slug => $c) {
            Service::firstOrCreate(['slug' => $slug], [
                'title' => $c['title'], 'short_description' => $c['short'], 'body' => $c['body'], 'sections' => \App\Support\ServiceSections::for($slug),
                'features' => $c['features'], 'faqs' => $c['faqs'], 'is_featured' => true, 'sort' => $c['sort'],
                'seo_title' => $c['seo_title'], 'seo_description' => $c['seo_description'],
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
