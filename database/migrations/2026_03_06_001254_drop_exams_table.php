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
        Schema::dropIfExists('exams');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('level_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('min_passing_score', 5, 2)->default(0);
            $table->integer('order')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
