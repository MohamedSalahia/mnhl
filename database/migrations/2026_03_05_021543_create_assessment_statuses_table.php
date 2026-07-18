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
        Schema::create('assessment_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');
            $table->enum('status', [
                AssessmentStatusEnum::PENDING,
                AssessmentStatusEnum::IN_PROGRESS,
                AssessmentStatusEnum::PARTIALLY_IN_PROGRESS,
                AssessmentStatusEnum::COMPLETED,
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_statuses');
    }
};
