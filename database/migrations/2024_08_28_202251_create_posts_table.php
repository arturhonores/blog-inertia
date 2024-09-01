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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->string('meta_title');
            $table->string('meta_description');
            $table->text('image_post_url');
            $table->text('image_card_url');
            $table->longText('post_html');
            $table->text('summary')->nullable(false);
            $table->date('publish_date')->nullable(false);
            // Llave foránea para la relación con Author
            $table->foreignId('author_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // Llave foránea para la relación con Category
            $table->foreignId('category_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // Llave foránea para la relación con User
            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
