<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assessment_scheme_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assessment_scheme_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('value');
            $table->integer('max_clicks');
            $table->string('background_color')->default('#ffffff');
            $table->string('text_color')->default('#000000');
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_scheme_deductions');
    }
};
