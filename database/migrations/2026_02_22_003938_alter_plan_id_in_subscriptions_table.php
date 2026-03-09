<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Check if constraint exists before dropping
            if (Schema::hasColumn('subscriptions', 'plan_id')) {
                // To avoid errors, we should silence dropForeign if it doesn't exist,
                // but Laravel requires the exact name. The default is table_column_foreign.
                try {
                    $table->dropForeign(['plan_id']);
                } catch (\Exception $e) {
                    // Ignore if foreign key is already gone
                }
            }
            $table->string('plan_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('plan_id')->change();
            $table->foreign('plan_id')->references('id')->on('subscription_plans')->onDelete('cascade');
        });
    }
};
