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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('server_id')->unique();
            $table->longText('auasae_'); // Consider renaming to something meaningful
            $table->longText(column: 'akaeay_')->nullable(); // Consider renaming to something meaningful
            $table->timestamps();
            $table->softDeletes(); // Adds the deleted_at column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
