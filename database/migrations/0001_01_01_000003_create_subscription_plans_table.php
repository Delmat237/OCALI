<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Subscription Plans
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Type: reader or author
            $table->enum('type', ['reader', 'author'])->default('reader');

            // Duration: monthly, quarterly, yearly
            $table->enum('duration', ['monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->unsignedInteger('duration_days');

            // Pricing (in XAF)
            $table->decimal('price', 10, 2);
            $table->decimal('price_eur', 10, 2)->nullable();

            // Limits
            $table->unsignedInteger('books_limit');
            $table->unsignedInteger('publications_limit')->default(0);

            // Commission
            $table->decimal('platform_commission_rate', 5, 4)->default(0.20);

            // Features
            $table->json('features')->nullable();

            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);

            $table->timestamps();
        });

        // User Subscriptions
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('subscription_plans')->onDelete('cascade');

            $table->decimal('amount_paid', 10, 2);
            $table->decimal('platform_commission', 10, 2)->default(0);

            $table->timestamp('starts_at');
            $table->timestamp('ends_at');

            // Books selected during subscription
            $table->json('selected_book_ids')->nullable();
            $table->unsignedInteger('books_remaining');

            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('ends_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_plans');
    }
};
