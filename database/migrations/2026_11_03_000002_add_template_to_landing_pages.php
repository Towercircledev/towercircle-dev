<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landing_pages', function (Blueprint $t) {
            $t->string('template')->default('standard')->after('slug');
            $t->json('content')->nullable()->after('faqs');
        });
    }

    public function down(): void
    {
        Schema::table('landing_pages', fn (Blueprint $t) => $t->dropColumn(['template', 'content']));
    }
};
