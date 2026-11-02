<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $t) {
            $t->string('key')->primary();
            $t->json('value')->nullable();
            $t->timestamps();
        });

        Schema::create('leads', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('phone', 20);
            $t->string('email')->nullable();
            $t->string('company')->nullable();
            $t->string('website')->nullable();
            $t->string('service')->nullable();
            $t->string('budget')->nullable();
            $t->string('timeline')->nullable();
            $t->text('message')->nullable();
            $t->string('form')->default('contact'); // contact, audit, landing, quote
            $t->string('page_url')->nullable();
            $t->string('landing_url')->nullable();
            $t->string('referrer')->nullable();
            $t->string('utm_source')->nullable();
            $t->string('utm_medium')->nullable();
            $t->string('utm_campaign')->nullable();
            $t->string('utm_term')->nullable();
            $t->string('utm_content')->nullable();
            $t->string('gclid')->nullable();
            $t->string('status')->default('new')->index();
            $t->unsignedInteger('deal_value')->nullable();
            $t->date('follow_up_on')->nullable();
            $t->text('notes')->nullable();
            $t->timestamp('converted_at')->nullable();
            $t->string('ip', 45)->nullable();
            $t->timestamps();
        });

        Schema::create('services', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('icon')->nullable();
            $t->string('short_description', 300)->nullable();
            $t->longText('body')->nullable();
            $t->unsignedInteger('price_from')->nullable();
            $t->string('price_note')->nullable();
            $t->json('features')->nullable();
            $t->json('faqs')->nullable();
            $t->boolean('is_featured')->default(true);
            $t->boolean('is_visible')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->string('seo_title')->nullable();
            $t->string('seo_description', 300)->nullable();
            $t->timestamps();
        });

        Schema::create('projects', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('client')->nullable();
            $t->string('category')->nullable();
            $t->string('cover')->nullable();
            $t->json('gallery')->nullable();
            $t->string('summary', 300)->nullable();
            $t->longText('body')->nullable();
            $t->json('highlights')->nullable();
            $t->string('live_url')->nullable();
            $t->string('location')->nullable();
            $t->date('completed_on')->nullable();
            $t->boolean('is_featured')->default(false);
            $t->boolean('is_visible')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->string('seo_title')->nullable();
            $t->string('seo_description', 300)->nullable();
            $t->timestamps();
        });

        Schema::create('posts', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('cover')->nullable();
            $t->string('excerpt', 300)->nullable();
            $t->longText('body')->nullable();
            $t->json('tags')->nullable();
            $t->string('author')->nullable();
            $t->timestamp('published_at')->nullable();
            $t->boolean('is_published')->default(false);
            $t->string('seo_title')->nullable();
            $t->string('seo_description', 300)->nullable();
            $t->timestamps();
        });

        Schema::create('faqs', function (Blueprint $t) {
            $t->id();
            $t->string('question');
            $t->text('answer');
            $t->boolean('is_visible')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('team_members', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('role')->nullable();
            $t->string('photo')->nullable();
            $t->string('linkedin')->nullable();
            $t->string('instagram')->nullable();
            $t->boolean('is_visible')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('company')->nullable();
            $t->text('quote');
            $t->unsignedTinyInteger('rating')->default(5);
            $t->string('photo')->nullable();
            $t->boolean('is_visible')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('landing_pages', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('title');
            $t->string('eyebrow')->nullable();
            $t->string('heading');
            $t->text('subheading')->nullable();
            $t->string('hero_image')->nullable();
            $t->unsignedInteger('price_from')->nullable();
            $t->string('offer')->nullable();
            $t->json('benefits')->nullable();
            $t->json('steps')->nullable();
            $t->json('faqs')->nullable();
            $t->string('form_heading')->nullable();
            $t->string('cta_label')->nullable();
            $t->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $t->boolean('show_projects')->default(true);
            $t->boolean('show_testimonials')->default(true);
            $t->boolean('is_visible')->default(true);
            $t->boolean('noindex')->default(true);
            $t->string('seo_title')->nullable();
            $t->string('seo_description', 300)->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['landing_pages', 'testimonials', 'team_members', 'faqs', 'posts', 'projects', 'services', 'leads', 'settings'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
