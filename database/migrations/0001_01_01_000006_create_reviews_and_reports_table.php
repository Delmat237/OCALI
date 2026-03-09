<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Book Reviews
        Schema::create('book_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');

            $table->unsignedTinyInteger('rating'); // 1-5
            $table->string('title')->nullable();
            $table->text('content');

            $table->unsignedInteger('helpful_count')->default(0);
            $table->boolean('is_verified_purchase')->default(false);

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');

            $table->timestamps();

            $table->unique(['user_id', 'book_id']);
            $table->index(['book_id', 'status']);
        });

        // Book Reports (Signalements)
        Schema::create('book_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');

            $table->enum('reason', [
                'copyright',
                'inappropriate_content',
                'spam',
                'misleading',
                'quality_issues',
                'other'
            ]);
            $table->text('description');

            $table->enum('status', ['pending', 'investigating', 'resolved', 'dismissed'])->default('pending');
            $table->text('admin_response')->nullable();
            $table->enum('action_taken', ['none', 'warning', 'book_removed', 'author_banned'])->nullable();

            $table->foreignId('handled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('handled_at')->nullable();

            $table->timestamps();

            $table->index(['status', 'created_at']);
        });

        // Chronicles / Literary Reviews
        Schema::create('chronicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->nullable()->constrained()->onDelete('set null');

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->string('cover_image')->nullable();

            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();

            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('comments_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'published_at']);
        });

        // Chronicle Comments
        Schema::create('chronicle_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chronicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('chronicle_comments')->onDelete('cascade');

            $table->text('content');
            $table->unsignedInteger('likes_count')->default(0);

            $table->timestamps();

            $table->index('chronicle_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chronicle_comments');
        Schema::dropIfExists('chronicles');
        Schema::dropIfExists('book_reports');
        Schema::dropIfExists('book_reviews');
    }
};
