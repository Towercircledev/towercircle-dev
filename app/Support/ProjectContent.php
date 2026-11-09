<?php

namespace App\Support;

/** Portfolio case studies, imported with `php artisan tc:projects`. Images live in resources/content/projects. */
class ProjectContent
{
    public static function all(): array
    {
        return [
            'mihira-adorn-shopify-jewellery-store' => [
                'title' => 'Mihira Adorn – Handmade Jewellery Shopify Store',
                'client' => 'Mihira Adorn',
                'category' => 'Shopify',
                'live_url' => 'https://mihiraadorn.com',
                'location' => null,
                'completed_on' => null,
                'featured' => true,
                'sort' => 1,
                'summary' => 'A warm, founder-led Shopify store for handmade jewellery, with category shopping, combo deals, UPI and COD payments and WhatsApp support.',
                'images' => ['mihira-0', 'mihira-1', 'mihira-2', 'mihira-mobile'],
                'body' => <<<'HTML'
<h2>The client</h2>
<p>Mihira Adorn makes lightweight handmade jewellery: pearl, bead and jadau pieces, jhumkas, ear cuffs and necklace sets. Every design is worn and styled by the founder before it goes on sale, so the brand is personal by nature.</p>
<h2>The challenge</h2>
<p>The brand needed a proper online store that felt as personal as the founder's own styling, worked smoothly on phones, and let customers in every part of India buy with the payment method they trust, including cash on delivery.</p>
<h2>What we built</h2>
<p>We designed and built a Shopify store around the founder's story, with a hero banner of the founder wearing her own pieces and a "from the founder" section. Shoppers can browse by category (necklace sets, jhumkas, ear cuffs, earrings), see best sellers and new arrivals, and pick up combo deals on necklace-and-earring sets.</p>
<p>Checkout supports UPI, cards and COD, with pan-India shipping, and a WhatsApp chat button gives quick support. The store was connected to the brand's own domain, mihiraadorn.com, and set up so the client can add new collections herself.</p>
HTML,
                'highlights' => [
                    'Founder-led design that puts the brand story first',
                    'Shop by category, best sellers, new arrivals and combo deals',
                    'UPI, cards and cash on delivery with pan-India shipping',
                    'WhatsApp support and a mobile-first layout',
                ],
            ],
            'tongue-to-tail-dog-wellness-shopify-store' => [
                'title' => 'Tongue To Tail – Dog Wellness Shopify Store',
                'client' => 'Tongue To Tail',
                'category' => 'Shopify',
                'live_url' => 'https://tonguetotail.in',
                'location' => 'India',
                'completed_on' => null,
                'featured' => true,
                'sort' => 2,
                'summary' => 'A bold, playful Shopify store for premium dog supplements and real-chicken treats, with multi-pack bundles, offers and WhatsApp chat.',
                'images' => ['tonguetotail-0', 'tonguetotail-1', 'tonguetotail-2', 'tonguetotail-mobile'],
                'body' => <<<'HTML'
<h2>The client</h2>
<p>Tongue To Tail is a new Indian pet brand making nutritional supplements (probiotic and gut health, hip and joint care, skin and coat health) and air-dried real-chicken jerky for dogs.</p>
<h2>The challenge</h2>
<p>As a launch brand, it needed a store that builds trust with pet parents straight away: clear ingredient messaging, products grouped by what the dog needs, and an easy way to buy more than one pack.</p>
<h2>What we built</h2>
<p>We designed and built the Shopify store and launched it on the brand's own domain, tonguetotail.in. The design uses the brand's bold blue and orange, a "whole-dog wellness" message and trust points such as human-grade, all-natural and vet approved.</p>
<p>Products are grouped by need, and each product page offers 1, 2 or 3-pack bundles with the saving shown, so customers are nudged towards a bigger order. We added a running offer bar (free shipping and a gift on larger orders), product image galleries and a WhatsApp chat button for questions before buying.</p>
HTML,
                'highlights' => [
                    'Brand-led design with clear trust points for pet parents',
                    'Shop by need: digestion, joints, skin and coat, treats',
                    '1, 2 and 3-pack bundles with savings to lift order value',
                    'Launched on the brand\'s own domain, with product galleries and offers',
                ],
            ],
            'chahhat-designers-indian-fashion-store-australia' => [
                'title' => 'Chahhat Designers – Indian Fashion Store for Australia',
                'client' => 'Chahhat Designers',
                'category' => 'Shopify',
                'live_url' => null,
                'location' => 'Gold Coast, Australia',
                'completed_on' => null,
                'featured' => true,
                'sort' => 3,
                'summary' => 'An elegant Shopify store for handpicked Indian clothing and jewellery, shipping across Australia: lehengas, sarees, suits, men and kids.',
                'images' => ['chahhat-0', 'chahhat-1', 'chahhat-mobile'],
                'body' => <<<'HTML'
<h2>The client</h2>
<p>Chahhat Designers brings handpicked Indian clothing and jewellery to the Gold Coast and the rest of Australia, from Pimpama. The range covers lehengas, sarees, suits and shararas, Indo-western outfits, jewellery, menswear and kidswear for weddings, sangeets and festivals.</p>
<h2>The challenge</h2>
<p>With a large and varied catalogue, the store had to stay easy to browse while still feeling premium, the way a boutique does. It also had to work for customers in Australia rather than India.</p>
<h2>What we built</h2>
<p>We designed an elegant Shopify store with a dark navy and gold look, large editorial banners for each collection and a "shop by category" section covering every product type. Navigation is organised by women, men, kids, jewellery and occasions, so shoppers can find an outfit for the event they are dressing for.</p>
<p>The store tells the founder's story, highlights Australia-wide shipping and handpicked designs, and has a WhatsApp chat button for styling questions. It was built in Towercircle's Shopify Partner account, ready for handover to the client.</p>
HTML,
                'highlights' => [
                    'Premium boutique design in navy and gold',
                    'Large catalogue organised by category and occasion',
                    'Set up for Australia-wide shipping',
                    'WhatsApp chat for styling questions',
                ],
            ],
            'avanya-dental-clinic-software' => [
                'title' => 'Avanya – Dental Clinic Management Software',
                'client' => 'Avanya by Towercircle (our product)',
                'category' => 'Web app',
                'live_url' => 'https://avanya.site',
                'location' => 'Hyderabad',
                'completed_on' => null,
                'featured' => true,
                'sort' => 4,
                'summary' => 'Our own cloud software for Indian dental clinics: appointments by chair, dental charts, GST billing, WhatsApp recalls, patient portal and daily reports, for one or many branches.',
                'images' => ['avanya-dental-0', 'avanya-dental-1', 'avanya-dental-2', 'avanya-dental-3', 'avanya-dental-mobile'],
                'body' => <<<'HTML'
<h2>About the product</h2>
<p>Avanya is Towercircle's own software for dental clinics. Reception, dentists, billing staff and the owner work on the same system in the browser, on a computer, tablet or phone, with nothing to install. It is built for Indian clinics: GST invoices, UPI and PhonePe payment links, WhatsApp reminders and prescriptions in English, Telugu or Hindi.</p>
<h2>The problem it solves</h2>
<p>Most clinics run on a register, WhatsApp and memory. Appointments clash, patients forget their check-ups, bills are handwritten with GST mistakes, X-rays sit in phone galleries, and nobody knows how the day went until the cash is counted at night.</p>
<h2>What we built</h2>
<p>A calendar by chair and doctor that can't double-book, a live queue (expected, waiting, in chair, done) and quick registration with duplicate checks. Dentists get an interactive dental chart with full history, treatment plans with printed estimates, X-rays per tooth, consent forms signed on screen and prescriptions with the doctor's signature.</p>
<p>Billing produces GST-correct invoices with part payments, advances and online payment links, and big discounts or refunds need the owner's approval. Recalls and reminders go out automatically by WhatsApp, SMS and email, patients can book and download bills in their own portal, and the owner sees a daily summary of OPs and money by doctor and branch. Lab cases, inventory, expenses, staff roles and day-end cash closing are all included, with each clinic's data kept separate and stored in India.</p>
HTML,
                'highlights' => [
                    'Calendar by chair and doctor, live queue and walk-ins',
                    'Interactive dental chart, treatment plans, X-rays and e-consent',
                    'GST billing, UPI payment links and owner approvals for discounts',
                    'Automatic WhatsApp/SMS recalls, patient portal and daily reports',
                ],
            ],
            'avanya-multi-specialty-clinic-software' => [
                'title' => 'Avanya – Multi-Specialty Clinic Software',
                'client' => 'Avanya by Towercircle (our product)',
                'category' => 'Web app',
                'live_url' => 'https://avanya.site',
                'location' => 'Hyderabad',
                'completed_on' => null,
                'featured' => true,
                'sort' => 5,
                'summary' => 'One system for multi-doctor, multi-specialty clinics: specialty records, appointments by doctor and room, packages, GST billing and daily reports.',
                'images' => ['avanya-multi-2', 'avanya-multi-0', 'avanya-multi-1', 'avanya-multi-3'],
                'body' => <<<'HTML'
<h2>About the product</h2>
<p>The multi-specialty edition of Avanya runs a whole clinic from one screen: general physician, dermatology and aesthetics, obstetrics and gynaecology, orthopaedics, IV drips and infusions, weight loss, day-care surgery and dental. Each patient keeps one file, one bill and one set of reminders across every department.</p>
<h2>The problem it solves</h2>
<p>A clinic with many doctors and services ends up with one register for everyone, notes for each specialty on different papers, packages tracked from memory and GST applied wrongly, because medical services are exempt but aesthetic ones are taxed.</p>
<h2>What we built</h2>
<p>Each department gets ready-made records: vitals and prescriptions for physicians, before/after photos and session packages for dermatology, pregnancy tracking and antenatal reminders for gynaecology (with private notes only the treating doctor can see), X-ray uploads and physio sessions for orthopaedics, weight and BMI charts for weight-loss programmes, infusion records and bed tracking for IV drips, and OT schedules and discharge summaries for day-care surgery.</p>
<p>Reception books by specialty, then doctor, with a calendar by doctor or room that prevents double booking. Billing sets GST per service, sells packages and tracks sessions left, and accepts cash, UPI, card and payment links. The owner sees patients per doctor and money in and out at the end of every day.</p>
HTML,
                'highlights' => [
                    '8 specialties in one system, with one record per patient',
                    'Booking by specialty, doctor and room, with no double booking',
                    'Packages and programmes with sessions used and left',
                    'Correct GST per service, daily reports per doctor and branch',
                ],
            ],
            'getshuddh-car-and-home-cleaning-booking-platform' => [
                'title' => 'GetShuddh – Car & Home Cleaning Booking Platform',
                'client' => 'GetShuddh (our product)',
                'category' => 'Web app',
                'live_url' => 'https://getshuddh.com',
                'location' => 'Hyderabad',
                'completed_on' => '2026-11-03',
                'featured' => true,
                'sort' => 6,
                'summary' => 'Our own doorstep car and home cleaning service in Hyderabad, with online booking, live slots, car-type pricing and a full admin, built in Laravel.',
                'images' => ['getshuddh-0', 'getshuddh-1', 'getshuddh-mobile'],
                'body' => <<<'HTML'
<h2>About the product</h2>
<p>GetShuddh is Towercircle's own doorstep cleaning service in Hyderabad. Customers book car cleaning (bucket wash, pressure wash or a full 360 interior clean) and home cleaning for sofas, beds, mattresses, carpets, windows, curtains and kitchens, and the team comes to their parking or home.</p>
<h2>The challenge</h2>
<p>A service business needs more than a website. It needs a booking system that shows real prices, real time slots and the service area, and a back office the team can run every day without spreadsheets.</p>
<h2>What we built</h2>
<p>We built GetShuddh from scratch on Laravel with a Filament admin. Customers choose a service, pick their car type or the size of the item, add extras, then choose a date and time slot and share their address. Prices change with car type (hatchback, sedan or SUV), the earliest available slot is shown on every service, and there is a check for whether the address is in the service area.</p>
<p>Behind it, the admin manages services, prices, offers, slots, bookings and customers. The same work is what we build for clients who need custom software: booking systems, CRMs and internal tools that fit how the business really runs.</p>
HTML,
                'highlights' => [
                    'Custom Laravel booking platform with a Filament admin',
                    'Live time slots, service-area check and pay after the service',
                    'Prices by car type and size, with add-ons and offers',
                    '35+ home cleaning and 3 car cleaning services online',
                ],
            ],
        ];
    }
}
