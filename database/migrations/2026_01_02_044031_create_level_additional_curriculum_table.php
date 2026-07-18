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
        Schema::create('level_additional_curriculum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->constrained()->cascadeOnDelete();
            $table->foreignId('curriculum_id')->constrained()->cascadeOnDelete();
            $table->integer('from_page');
            $table->integer('to_page');
            $table->timestamps();

            // Add unique constraint to prevent duplicate relationships
            $table->unique(['level_id', 'curriculum_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_additional_curriculum');
    }
};

