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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->string('title', 200);
            $table->text('description');
            $table->string('category', 50);
            $table->unsignedBigInteger('budget_min');
            $table->unsignedBigInteger('budget_max');
            $table->date('deadline');
            $table->enum('status', ['terbuka', 'ditutup'])->default('terbuka');
            $table->timestamps();

            $table->index('client_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
