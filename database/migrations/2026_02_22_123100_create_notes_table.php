<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('note_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();

            $table->unique(['user_id', 'name']);
        });

        Schema::create('note_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();

            $table->unique(['user_id', 'name']);
        });

        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('note_categories')->nullOnDelete();
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->boolean('is_pinned')->default(false)->index();
            $table->boolean('is_archived')->default(false)->index();
            $table->boolean('is_favorite')->default(false)->index();
            $table->boolean('is_locked')->default(false);
            $table->string('color', 20)->nullable();
            $table->unsignedInteger('word_count')->default(0);
            $table->unsignedInteger('character_count')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'is_archived']);
            $table->index(['user_id', 'is_pinned']);
        });

        Schema::create('note_note_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->constrained('notes')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('note_tags')->cascadeOnDelete();

            $table->unique(['note_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note_note_tag');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('note_tags');
        Schema::dropIfExists('note_categories');
    }
};
