<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organization_teacher', function (Blueprint $table) {
            $table->decimal('hourly_rate', 12, 3)->default(0)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('organization_teacher', function (Blueprint $table) {
            $table->dropColumn('hourly_rate');
        });
    }
};
