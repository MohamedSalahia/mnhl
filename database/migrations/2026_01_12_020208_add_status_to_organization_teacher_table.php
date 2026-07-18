<?php

use App\Enums\OrganizationTeacherStatusEnum;
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
        Schema::table('organization_teacher', function (Blueprint $table) {
            $table->enum('status', [
                OrganizationTeacherStatusEnum::PENDING,
                OrganizationTeacherStatusEnum::ACTIVE,
                OrganizationTeacherStatusEnum::INACTIVE,
            ])->default(OrganizationTeacherStatusEnum::PENDING)->index()->after('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organization_teacher', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
