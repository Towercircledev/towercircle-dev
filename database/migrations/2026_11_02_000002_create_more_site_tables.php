<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logos', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('image');
            $t->string('group')->default('platform'); // platform | client
            $t->string('url')->nullable();
            $t->boolean('is_visible')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('redirects', function (Blueprint $t) {
            $t->id();
            $t->string('from')->unique();
            $t->string('to');
            $t->unsignedSmallInteger('code')->default(301);
            $t->unsignedInteger('hits')->default(0);
            $t->timestamp('last_hit_at')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('locations', function (Blueprint $t) {
            $t->id();
            $t->string('city');
            $t->string('slug')->unique();
            $t->string('heading');
            $t->text('intro')->nullable();
            $t->longText('body')->nullable();
            $t->json('faqs')->nullable();
            $t->boolean('is_visible')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->string('seo_title')->nullable();
            $t->string('seo_description', 300)->nullable();
            $t->timestamps();
        });

        Schema::table('leads', function (Blueprint $t) {
            $t->string('fbclid')->nullable()->after('gclid');
            $t->string('fbp')->nullable()->after('fbclid');
            $t->string('user_agent', 300)->nullable()->after('ip');
        });
    }

    public function down(): void
    {
        Schema::table('leads', fn (Blueprint $t) => $t->dropColumn(['fbclid', 'fbp', 'user_agent']));
        Schema::dropIfExists('locations');
        Schema::dropIfExists('redirects');
        Schema::dropIfExists('logos');
    }
};
