<?php

namespace App\Support;

/** Starting text for the legal pages. Edit in the admin under Settings → Legal pages, and have it checked by your CA or lawyer. */
class LegalDefaults
{
    public static function privacy(): string
    {
        $name = site('name');
        $email = site('email');

        return <<<HTML
<p>This policy explains what personal information {$name} ("we", "us") collects through this website, why we collect it and how we look after it. It is written to follow India's Digital Personal Data Protection Act, 2023.</p>
<h2>What we collect</h2>
<ul>
<li><strong>Details you give us</strong> when you fill in a form, call or message us: your name, phone number, email address, business name, website and the details of your enquiry.</li>
<li><strong>Visit information</strong>: pages you view, the website or advert that brought you here (for example a Google or Facebook ad), your browser type and IP address.</li>
</ul>
<h2>Why we use it</h2>
<ul>
<li>To reply to your enquiry and send you a quote, by phone, WhatsApp or email.</li>
<li>To deliver and support the services you buy from us.</li>
<li>To understand which of our adverts and pages work, and to show our adverts to people likely to need our services.</li>
</ul>
<p>We do not sell your personal information.</p>
<h2>Cookies and advertising tools</h2>
<p>We use Google Analytics, Google Ads and the Meta (Facebook) Pixel and Conversions API. These use cookies and similar technology to measure visits and ad results. When you send an enquiry, we may share a scrambled (hashed) version of your phone number or email with Google or Meta only to measure whether our ads worked. You can block cookies in your browser settings, and manage ad preferences in your Google and Facebook accounts.</p>
<h2>Who we share it with</h2>
<p>Only with service providers who help us run our business — for example our hosting, email and messaging providers, and Google and Meta as described above — or when the law requires it.</p>
<h2>How long we keep it</h2>
<p>Enquiry details are kept for up to 3 years so we can follow up and keep a record of our work, unless you ask us to delete them sooner. Client records are kept as long as needed for accounting and tax law.</p>
<h2>Your rights</h2>
<p>You can ask us to see, correct or delete your personal information, or to stop contacting you, by emailing <a href="mailto:{$email}">{$email}</a>. We will reply within 30 days.</p>
<h2>Security</h2>
<p>We protect your information with secure (HTTPS) connections, password-protected systems and limited access for our team.</p>
<h2>Changes</h2>
<p>We may update this policy. The latest version is always on this page.</p>
<h2>Contact</h2>
<p>For any privacy question or complaint, email <a href="mailto:{$email}">{$email}</a>.</p>
HTML;
    }

    public static function terms(): string
    {
        $name = site('name');
        $email = site('email');

        return <<<HTML
<p>These terms apply to your use of this website and to services provided by {$name}. Each project also has its own written quote or agreement; if the two differ, the project agreement applies.</p>
<h2>Quotes and payments</h2>
<ul>
<li>Quotes are valid for 30 days unless stated otherwise.</li>
<li>Work starts after the agreed advance payment. The balance is due before the website goes live or as set out in your quote.</li>
<li>Prices on this website marked "starting from" are a guide. Your final price depends on your requirements.</li>
<li>Third-party costs such as domain names, hosting, Shopify plans, paid apps, themes and advertising spend are paid by the client unless included in the quote.</li>
</ul>
<h2>Timelines</h2>
<p>Timelines depend on receiving content, feedback and approvals from you on time. Delays on either side may move the launch date.</p>
<h2>Changes and revisions</h2>
<p>Your quote includes the revisions listed in it. Extra pages, features or changes after approval may be charged separately; we will tell you the cost before doing the work.</p>
<h2>Ownership</h2>
<p>Once paid in full, you own your website content, design and domain. We may keep a copy and show the finished work in our portfolio unless you ask us not to.</p>
<h2>Hosting, maintenance and advertising</h2>
<ul>
<li>Hosting and maintenance plans renew for the period stated and can be cancelled before the renewal date.</li>
<li>For advertising and marketing services we work to improve results, but we cannot guarantee a specific number of leads, sales or search rankings, which depend on many factors outside our control.</li>
</ul>
<h2>Liability</h2>
<p>We are not responsible for losses caused by third-party services (such as hosting providers, payment gateways, Shopify, Google or Meta) or by changes made to the website by others. Our total liability for any project is limited to the amount paid for that project.</p>
<h2>Website use</h2>
<p>The content on this website belongs to {$name} and may not be copied without permission.</p>
<h2>Governing law</h2>
<p>These terms are governed by the laws of India.</p>
<h2>Contact</h2>
<p>Questions about these terms: <a href="mailto:{$email}">{$email}</a>.</p>
HTML;
    }
}
