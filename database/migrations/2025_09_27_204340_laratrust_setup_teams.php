<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LaratrustSetupTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for storing teams
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::table('role_user', function (Blueprint $table) {
            // Drop existing constraints first
            $table->dropPrimary();

            // Add team_id column
            $table->foreignId('team_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            // Add user foreign key (role_id constraint already exists)
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();

            // Create a unique key instead of primary key
            $table->unique(['user_id', 'role_id', 'user_type', 'team_id']);
        });

        Schema::table('permission_user', function (Blueprint $table) {
            // Drop existing constraints first
            $table->dropForeign('permission_user_permission_id_foreign');
            $table->dropPrimary();

            // Add team_id column
            $table->foreignId('team_id')->nullable()->constrained('teams')->cascadeOnDelete()->cascadeOnUpdate();

            // Recreate foreign keys
            $table->foreign('permission_id')->references('id')->on('permissions')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unique(['user_id', 'permission_id', 'user_type', 'team_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_user', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
        });

        Schema::table('permission_user', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
        });

        Schema::dropIfExists('teams');
    }
}
