<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organization_teacher', function (Blueprint $table) {
            $table->enum('salary_type', ['hourly', 'fixed'])->default('hourly')->after('hourly_rate');
            $table->decimal('fixed_salary', 12, 3)->default(0)->after('salary_type');
        });
    }

    public function down(): void
    {
        Schema::table('organization_teacher', function (Blueprint $table) {
            $table->dropColumn(['salary_type', 'fixed_salary']);
        });
    }
};
