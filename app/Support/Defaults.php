<?php

namespace App\Support;

/** Starting content, used until something is saved in the admin. */
class Defaults
{
    public static function home(): array
    {
        return [
            'hero' => [
                'eyebrow' => 'Technology · Digital · Growth',
                'heading' => 'Websites That Bring You Customers',
                'highlight' => 'Customers',
                'text' => 'Websites, Shopify stores and custom business software — plus Google & Meta ads that bring real enquiries. One team for everything your business needs online.',
                'cta' => 'Get a Free Quote',
                'cta2' => 'WhatsApp Us',
                'image' => null,
            ],
            'highlights' => [
                ['title' => 'Shopify Stores', 'text' => 'Online stores set up to sell from day one.'],
                ['title' => 'Custom Websites', 'text' => 'Fast business websites built to rank on Google.'],
                ['title' => 'Digital & Performance Marketing', 'text' => 'Google & Meta ads that bring leads, not just clicks.'],
                ['title' => 'Speed & SEO', 'text' => 'Slow site? We make it load in under 2 seconds.'],
            ],
            'about' => [
                'eyebrow' => 'About Towercircle',
                'heading' => 'Your Website Team, Without the Agency Headaches',
                'text' => 'We have built 100+ websites and online stores for shops, startups and service businesses. You get one team for design, development, hosting and support — with clear prices and direct WhatsApp access.',
                'points' => ['Clear fixed pricing, no hidden costs', 'You own your website and domain', 'Quick support on WhatsApp'],
            ],
            'stats' => [
                ['number' => 100, 'suffix' => '+', 'label' => 'Websites built'],
                ['number' => 50, 'suffix' => '+', 'label' => 'Happy clients'],
                ['number' => 5, 'suffix' => '+', 'label' => 'Years of experience'],
                ['number' => 24, 'suffix' => 'h', 'label' => 'Support response'],
            ],
            'software' => [
                'eyebrow' => 'Custom software',
                'heading' => 'Software Built Around Your Business',
                'text' => 'Stop juggling Excel sheets and WhatsApp groups. We build CRM, HRM, ERP and other business software that fits exactly how you work — on the web, on mobile, owned by you.',
                'items' => [
                    ['title' => 'CRM', 'text' => 'Every lead in one place, follow-up reminders, quotes and sales pipeline.'],
                    ['title' => 'HRM & Payroll', 'text' => 'Attendance, leave, salary slips and employee records.'],
                    ['title' => 'ERP & Inventory', 'text' => 'Purchase, stock, production and sales in one system.'],
                    ['title' => 'Billing & GST Invoicing', 'text' => 'Invoices, payments, outstanding and reminders.'],
                    ['title' => 'Booking & Scheduling', 'text' => 'Online bookings, time slots, staff and payments.'],
                    ['title' => 'Client & Vendor Portals', 'text' => 'Secure logins for customers and partners to see orders and documents.'],
                ],
            ],
            'process' => [
                'heading' => 'From Idea to Launch in Four Simple Steps',
                'text' => 'A clear process so you always know what is happening and when your website goes live.',
                'steps' => [
                    ['title' => 'Plan', 'text' => 'A short call to understand your business, goals and budget.'],
                    ['title' => 'Design', 'text' => 'You see and approve the design before we build.'],
                    ['title' => 'Build', 'text' => 'We develop, add content and test on mobile and desktop.'],
                    ['title' => 'Launch & support', 'text' => 'We go live, train you, and stay on call.'],
                ],
            ],
            'logos' => [
                'client_heading' => 'Trusted by 100+ Businesses',
                'platform_heading' => 'Platforms We Work With',
            ],
            'audit' => [
                'heading' => 'Get a Free Website Audit',
                'text' => 'Send us your website. Within 24 hours we\'ll WhatsApp you a short report on speed, Google ranking and what is costing you customers.',
            ],
            'show' => ['software' => true, 'logos' => true, 'about' => true, 'stats' => true, 'process' => true, 'services' => true, 'projects' => true, 'team' => true, 'reviews' => true, 'blog' => true, 'audit' => true],
            'seo' => [
                'title' => 'Website Design, Shopify & Hosting Company in India | Towercircle',
                'description' => 'Towercircle builds fast websites and Shopify stores that bring customers, with managed domains and hosting. 100+ websites built. Get a free quote.',
            ],
        ];
    }

    public static function site(): array
    {
        return [
            'name' => 'Towercircle',
            'email' => 'reach@towercircle.in',
            'phone' => '6302639638',
            'whatsapp' => '6302639638',
            'footer_about' => 'Websites, Shopify stores and performance marketing for growing businesses. 100+ websites built.',
            'hours' => 'Mon – Sat, 10am – 7pm',
            'tagline' => 'Technology · Digital · Growth',
        ];
    }
}
