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
            'getshuddh-car-and-home-cleaning-booking-platform' => [
                'title' => 'GetShuddh – Car & Home Cleaning Booking Platform',
                'client' => 'GetShuddh (our product)',
                'category' => 'Web app',
                'live_url' => 'https://getshuddh.com',
                'location' => 'Hyderabad',
                'completed_on' => '2026-11-03',
                'featured' => true,
                'sort' => 4,
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
