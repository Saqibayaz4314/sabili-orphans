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
        Schema::create('brothers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orphan_id')->constrained()->cascadeOnDelete();
            $table->string('brother_name');
            $table->string('brother_id_number' ,9)->unique();
            $table->enum('brother_gender' , ['ذكر','أنثى']);
            $table->date('brother_birth_date');
            $table->enum('brother_health_status' , ['سليم','مريض']);
            $table->string('brother_medical_report')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brothers');
    }
};
