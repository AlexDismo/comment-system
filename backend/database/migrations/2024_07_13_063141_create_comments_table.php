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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');

            $table->string('author_name')->index();
            $table->string('author_email')->index();
            $table->string('author_url')->nullable();

            $table->foreignId('parent_id')->index()->nullable()->constrained('comments')->onDelete('cascade');

            $table->timestamp('created_at')->index();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
