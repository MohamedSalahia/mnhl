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
        Schema::table('teacher_salary_transactions', function (Blueprint $table) {
            $table->smallInteger('period_year')->unsigned()->nullable()->after('date');
            $table->tinyInteger('period_month')->unsigned()->nullable()->after('period_year');
            $table->boolean('carry_forward')->default(false)->after('period_month');
        });

        // Back-fill existing records: derive period from date or created_at
        DB::statement("
            UPDATE teacher_salary_transactions
            SET period_year  = YEAR(COALESCE(date, created_at)),
                period_month = MONTH(COALESCE(date, created_at))
            WHERE period_year IS NULL
        ");
    }

    public function down(): void
    {
        Schema::table('teacher_salary_transactions', function (Blueprint $table) {
            $table->dropColumn(['period_year', 'period_month', 'carry_forward']);
        });
    }
};
