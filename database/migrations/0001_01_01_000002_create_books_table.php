<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');

            // Book details
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('cover_image');
            $table->string('file_path');
            $table->enum('file_type', ['pdf', 'epub'])->default('pdf');
            $table->unsignedInteger('pages_count')->default(0);
            $table->string('isbn')->nullable();
            $table->string('language', 5)->default('fr');

            // Type: book, scientific_review, chronicle
            $table->enum('type', ['book', 'scientific_review', 'chronicle'])->default('book');

            // For scientific reviews
            $table->string('doi')->nullable();
            $table->json('keywords')->nullable();

            // Status
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('published_at')->nullable();

            // Flags
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_free_welcome_book')->default(false);

            // Statistics
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('reads_count')->default(0);
            $table->unsignedInteger('downloads_count')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->unsignedInteger('reviews_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'published_at']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
