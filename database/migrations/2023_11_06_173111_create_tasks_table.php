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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('tasks');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['todo', 'done']);
            $table->unsignedTinyInteger('priority');
            $table->string('title',255);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->timestamp('completed_at')->nullable();
            $table->index('status');
            $table->index('priority');
            $table->index('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
