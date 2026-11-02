<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('role')->default('admin')->after('email');
            $t->string('phone', 20)->nullable()->after('role');
            $t->text('app_authentication_secret')->nullable();
            $t->text('app_authentication_recovery_codes')->nullable();
        });

        Schema::create('clients', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('contact_name')->nullable();
            $t->string('phone', 20)->nullable();
            $t->string('alt_phone', 20)->nullable();
            $t->string('email')->nullable();
            $t->string('gstin', 15)->nullable();
            $t->text('address')->nullable();
            $t->string('city')->nullable();
            $t->string('status')->default('active')->index();
            $t->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $t->text('notes')->nullable();
            $t->timestamps();
        });

        Schema::create('client_sites', function (Blueprint $t) {
            $t->id();
            $t->foreignId('client_id')->constrained()->cascadeOnDelete();
            $t->string('domain');
            $t->string('platform')->nullable();
            $t->string('live_url')->nullable();
            $t->string('admin_url')->nullable();
            $t->string('registrar')->nullable();
            $t->string('hosting_provider')->nullable();
            $t->string('hosting_plan')->nullable();
            $t->string('server_ip', 64)->nullable();
            $t->string('email_provider')->nullable();
            $t->unsignedSmallInteger('mailboxes')->nullable();
            $t->text('notes')->nullable();
            $t->boolean('monitor')->default(true);
            $t->string('uptime_status')->default('unknown'); // up | down | unknown
            $t->unsignedSmallInteger('last_status_code')->nullable();
            $t->unsignedInteger('last_response_ms')->nullable();
            $t->timestamp('last_checked_at')->nullable();
            $t->timestamp('down_since')->nullable();
            $t->unsignedTinyInteger('fail_count')->default(0);
            $t->date('ssl_expires_on')->nullable();
            $t->timestamps();
        });

        Schema::create('credentials', function (Blueprint $t) {
            $t->id();
            $t->foreignId('client_id')->constrained()->cascadeOnDelete();
            $t->foreignId('client_site_id')->nullable()->constrained()->nullOnDelete();
            $t->string('type');
            $t->string('label')->nullable();
            $t->string('url')->nullable();
            $t->text('username')->nullable();
            $t->text('password')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
        });

        Schema::create('credential_views', function (Blueprint $t) {
            $t->id();
            $t->foreignId('credential_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('action', 20);
            $t->string('ip', 45)->nullable();
            $t->timestamp('created_at')->useCurrent();
        });

        Schema::create('renewals', function (Blueprint $t) {
            $t->id();
            $t->foreignId('client_id')->constrained()->cascadeOnDelete();
            $t->foreignId('client_site_id')->nullable()->constrained()->nullOnDelete();
            $t->string('type');
            $t->string('description')->nullable();
            $t->string('provider')->nullable();
            $t->unsignedInteger('amount')->nullable();
            $t->unsignedInteger('cost')->nullable();
            $t->string('cycle')->default('yearly');
            $t->date('due_on')->index();
            $t->boolean('is_active')->default(true);
            $t->date('reminded_30_on')->nullable();
            $t->date('reminded_7_on')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
        });

        Schema::create('payments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('client_id')->constrained()->cascadeOnDelete();
            $t->foreignId('renewal_id')->nullable()->constrained()->nullOnDelete();
            $t->string('number')->nullable()->unique();
            $t->string('purpose');
            $t->string('description')->nullable();
            $t->unsignedInteger('amount');
            $t->decimal('gst_rate', 5, 2)->default(0);
            $t->unsignedInteger('gst_amount')->default(0);
            $t->unsignedInteger('total');
            $t->string('status')->default('pending')->index(); // pending | paid | cancelled
            $t->date('invoice_date');
            $t->date('due_on')->nullable();
            $t->date('paid_on')->nullable();
            $t->string('method')->nullable();
            $t->string('reference')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
        });

        Schema::create('tasks', function (Blueprint $t) {
            $t->id();
            $t->foreignId('client_id')->nullable()->constrained()->cascadeOnDelete();
            $t->foreignId('lead_id')->nullable()->constrained()->cascadeOnDelete();
            $t->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $t->string('title');
            $t->text('details')->nullable();
            $t->date('due_on')->nullable()->index();
            $t->string('priority')->default('normal');
            $t->string('status')->default('todo')->index();
            $t->timestamp('completed_at')->nullable();
            $t->timestamps();
        });

        Schema::create('client_files', function (Blueprint $t) {
            $t->id();
            $t->foreignId('client_id')->constrained()->cascadeOnDelete();
            $t->string('title');
            $t->string('path');
            $t->string('original_name')->nullable();
            $t->timestamps();
        });

        Schema::create('activities', function (Blueprint $t) {
            $t->id();
            $t->morphs('subject');
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('type', 30);
            $t->text('body')->nullable();
            $t->timestamp('happened_at')->useCurrent();
            $t->timestamps();
        });

        Schema::table('leads', function (Blueprint $t) {
            $t->timestamp('auto_reply_sent_at')->nullable();
        });
    }

    public function down(): void
    {
        foreach (['activities', 'client_files', 'tasks', 'payments', 'renewals', 'credential_views', 'credentials', 'client_sites', 'clients'] as $t) {
            Schema::dropIfExists($t);
        }
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn(['role', 'phone', 'app_authentication_secret', 'app_authentication_recovery_codes']));
        Schema::table('leads', fn (Blueprint $t) => $t->dropColumn('auto_reply_sent_at'));
    }
};
