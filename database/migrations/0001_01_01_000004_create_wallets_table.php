<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Author Wallets
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('balance', 12, 2)->default(0);
            $table->decimal('total_earned', 12, 2)->default(0);
            $table->decimal('total_withdrawn', 12, 2)->default(0);
            $table->decimal('pending_amount', 12, 2)->default(0);
            $table->string('currency', 3)->default('XAF');
            $table->timestamps();

            $table->unique('user_id');
        });

        // Wallet Transactions
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Type: earning, withdrawal, bonus, adjustment
            $table->enum('type', ['earning', 'withdrawal', 'bonus', 'adjustment']);

            $table->decimal('amount', 12, 2);
            $table->decimal('balance_before', 12, 2);
            $table->decimal('balance_after', 12, 2);

            $table->string('description');
            $table->string('reference')->nullable();

            // Related entities
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('book_id')->nullable()->constrained()->onDelete('set null');

            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('completed');

            $table->timestamps();

            $table->index(['wallet_id', 'type']);
            $table->index('created_at');
        });

        // Withdrawal Requests
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');

            $table->decimal('amount', 12, 2);
            $table->decimal('fees', 10, 2)->default(0);
            $table->decimal('net_amount', 12, 2);

            // Payment details
            $table->enum('payment_method', ['orange_money', 'mtn_money', 'bank_transfer']);
            $table->string('phone_number')->nullable();
            $table->json('bank_details')->nullable();

            $table->enum('status', ['pending', 'processing', 'completed', 'rejected', 'failed'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->string('transaction_reference')->nullable();

            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('wallets');
    }
};
