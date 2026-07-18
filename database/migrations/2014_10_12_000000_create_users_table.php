<?php

use App\Enums\GenderEnum;
use App\Enums\MaritalStatusEnum;
use App\Enums\UserTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('hash_id')->nullable()->index();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->enum('type', [
                UserTypeEnum::SUPER_ADMIN,
                UserTypeEnum::ADMIN,
                UserTypeEnum::ORGANIZATION_SUPER_ADMIN,
                UserTypeEnum::ORGANIZATION_ADMIN,
                UserTypeEnum::TEACHER,
                UserTypeEnum::STUDENT,
                UserTypeEnum::EXAMINER,
            ])->index();
            $table->enum('gender', [
                GenderEnum::MALE,
                GenderEnum::FEMALE,
            ])->index();
            $table->string('mobile_country_code')->nullable();
            $table->string('mobile')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('father_mobile_country_code')->nullable();
            $table->string('father_mobile')->nullable();
            $table->string('mother_mobile_country_code')->nullable();
            $table->string('mother_mobile')->nullable();
            $table->string('identity_document_file')->nullable();
            $table->string('image')->nullable();
            $table->text('current_address')->nullable();
            $table->enum('marital_status', [
                MaritalStatusEnum::SINGLE,
                MaritalStatusEnum::MARRIED,
                MaritalStatusEnum::DIVORCED,
                MaritalStatusEnum::WIDOWED,
                MaritalStatusEnum::SEPARATED,
            ])->index();
            $table->text('last_educational_certificate')->nullable();
            $table->text('teaching_experience')->nullable();
            $table->boolean('has_previous_education')->default(false);
            $table->text('previous_education_details')->nullable();
            $table->string('locale')->default('ar');
            $table->boolean('dark_mode')->default(false);
            $table->boolean('menu_collapsed')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
