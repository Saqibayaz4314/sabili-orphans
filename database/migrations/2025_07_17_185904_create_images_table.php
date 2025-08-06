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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orphan_id')->constrained()->cascadeOnDelete();
            $table->string('medical_report')->nullable();
            $table->string('father_death_certificate');
            $table->string('wife_ID');
            $table->string('sponsor_ID');
            $table->string('birth_certificate');
            $table->string('personl_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
