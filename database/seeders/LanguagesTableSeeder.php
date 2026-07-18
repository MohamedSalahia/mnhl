<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['name' => 'Arabic', 'code' => 'ar', 'country_flag_code' => 'sa', 'active' => 1],
            ['name' => 'English', 'code' => 'en', 'country_flag_code' => 'us', 'active' => 1],
            ['name' => 'French', 'code' => 'fr', 'country_flag_code' => 'fr', 'active' => 0],
            ['name' => 'Italian', 'code' => 'it', 'country_flag_code' => 'it', 'active' => 0],
            ['name' => 'German', 'code' => 'de', 'country_flag_code' => 'de', 'active' => 0],
            ['name' => 'Urdu', 'code' => 'ur', 'country_flag_code' => 'in', 'active' => 0],
        ];

        foreach ($languages as $language) {

            Language::create($language);

        }//end of for each

    }//end of run

}//end of seeder
