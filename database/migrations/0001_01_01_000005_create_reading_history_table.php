<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Reading History / User Library
        Schema::create('user_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');

            // Reading progress
            $table->unsignedInteger('current_page')->default(1);
            $table->unsignedInteger('total_pages')->default(0);
            $table->decimal('progress_percent', 5, 2)->default(0);

            // Reading time tracking
            $table->unsignedInteger('reading_time_minutes')->default(0);
            $table->timestamp('last_read_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Status
            $table->enum('status', ['reading', 'completed', 'paused'])->default('reading');

            // Bookmarks
            $table->json('bookmarks')->nullable();

            // Reader settings for this book
            $table->json('reader_settings')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'book_id']);
            $table->index(['user_id', 'status']);
        });

        // Reading Sessions (for analytics)
        Schema::create('reading_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_book_id')->constrained('user_books')->onDelete('cascade');

            $table->unsignedInteger('start_page');
            $table->unsignedInteger('end_page');
            $table->unsignedInteger('pages_read');
            $table->unsignedInteger('duration_minutes');

            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();

            $table->string('device_type')->nullable();
            $table->string('ip_address', 45)->nullable();

            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['book_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reading_sessions');
        Schema::dropIfExists('user_books');
    }
};
