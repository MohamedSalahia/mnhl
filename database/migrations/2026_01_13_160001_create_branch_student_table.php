<?php

use App\Enums\BranchStudentStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('branch_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', [
                BranchStudentStatusEnum::ACTIVE,
                BranchStudentStatusEnum::INACTIVE,
            ])->default(BranchStudentStatusEnum::ACTIVE)->index();
            $table->foreignId('curriculum_id')->nullable()->constrained('curricula')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
            $table->integer('page_number')->nullable();
            $table->foreignId('level_id')->nullable()->constrained('levels')->nullOnDelete();
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->nullOnDelete();
            $table->boolean('exempted_from_fees')->default(false);
            $table->decimal('fees', 20, 3)->default(0.00);
            $table->decimal('paid_fees', 20, 3)->default(0.00);
            $table->decimal('remaining_fees', 20, 3)->default(0.00);
            $table->timestamps();

            $table->unique(['student_id', 'branch_id', 'classroom_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_student');
    }
};
