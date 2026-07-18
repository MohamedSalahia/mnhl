<?php

use App\Enums\AssessmentStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assessment_scheme_id')->constrained()->cascadeOnDelete();
            $table->foreignId('examiner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('curriculum_id')->nullable()->constrained('curricula')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('level_id')->nullable()->constrained('levels')->nullOnDelete();
            $table->enum('status', [
                AssessmentStatusEnum::PENDING,
                AssessmentStatusEnum::IN_PROGRESS,
                AssessmentStatusEnum::PARTIALLY_IN_PROGRESS,
                AssessmentStatusEnum::COMPLETED,
            ])->default(AssessmentStatusEnum::PENDING);
            $table->decimal('score', 8, 2)->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
