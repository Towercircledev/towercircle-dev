<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_sites', function (Blueprint $table) {
            $table->string('domain_login')->nullable()->after('registrar');
            $table->text('domain_password')->nullable()->after('domain_login');
            $table->string('hosting_login')->nullable()->after('hosting_provider');
            $table->text('hosting_password')->nullable()->after('hosting_login');
        });
    }

    public function down(): void
    {
        Schema::table('client_sites', fn (Blueprint $t) => $t->dropColumn(['domain_login', 'domain_password', 'hosting_login', 'hosting_password']));
    }
};
