<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
           DB::statement("
            ALTER TABLE orders
            MODIFY status ENUM(
                'pending',
                'accepted',
                'cooking',
                'cooked',
                'completed',
                'cancelled'
            ) NOT NULL DEFAULT 'pending'
        ");

        // 2. Add status timestamps
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('accepted_at')->nullable()->after('status');
            $table->timestamp('cooking_started_at')->nullable()->after('accepted_at');
            $table->timestamp('cooked_at')->nullable()->after('cooking_started_at');
            $table->timestamp('completed_at')->nullable()->after('cooked_at');
            $table->timestamp('cancelled_at')->nullable()->after('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'accepted_at',
                'cooking_started_at',
                'cooked_at',
                'completed_at',
                'cancelled_at',
            ]);
        });

        // Revert ENUM
        DB::statement("
            ALTER TABLE orders
            MODIFY status ENUM(
                'pending',
                'processing',
                'completed',
                'cancelled'
            ) NOT NULL DEFAULT 'pending'
        ");
    }
};
