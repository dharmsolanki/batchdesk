<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->timestamp('trial_ends_at')->nullable()->after('state');
            $table->boolean('subscribed')->default(false)->after('trial_ends_at');
            $table->timestamp('subscribed_at')->nullable()->after('subscribed');
            $table->timestamp('subscription_ends_at')->nullable()->after('subscribed_at');
            $table->string('subscription_plan', 30)->default('trial')->after('subscription_ends_at'); // trial | monthly | yearly
            $table->text('admin_notes')->nullable()->after('subscription_plan');
        });

        // Admin flag on users
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['trial_ends_at', 'subscribed', 'subscribed_at', 'subscription_ends_at', 'subscription_plan', 'admin_notes']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
