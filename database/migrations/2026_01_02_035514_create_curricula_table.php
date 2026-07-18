<?php

use App\Enums\CurriculumTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('curricula', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('book_name')->nullable();
            $table->string('book_file')->nullable();
            $table->integer('book_number_of_pages')->nullable();
            $table->enum('curriculum_type', [
                CurriculumTypeEnum::MAIN,
                CurriculumTypeEnum::ADDITIONAL,
            ])->index()->default(CurriculumTypeEnum::MAIN);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curricula');
    }
};
