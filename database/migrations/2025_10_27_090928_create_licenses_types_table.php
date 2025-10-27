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
        Schema::create('licenses_types', function (Blueprint $table) {
            $table->id();
            $table->string('_name')->unique();
            $table->text('features');
            $table->text(column: 'akaeay_')->nullable(); // Consider renaming to something meaningful
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses_types');
    }
};
