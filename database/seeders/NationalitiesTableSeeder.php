<?php

namespace Database\Seeders;

use App\Models\Nationality;
use Illuminate\Database\Seeder;

class NationalitiesTableSeeder extends Seeder
{
    public function run(): void
    {
        $nationalities = [
            ['ar' => ['name' => 'سعودي'], 'en' => ['name' => 'Saudi']],
            ['ar' => ['name' => 'مصري'], 'en' => ['name' => 'Egyptian']],
            ['ar' => ['name' => 'أردني'], 'en' => ['name' => 'Jordanian']],
            ['ar' => ['name' => 'سوري'], 'en' => ['name' => 'Syrian']],
            ['ar' => ['name' => 'لبناني'], 'en' => ['name' => 'Lebanese']],
            ['ar' => ['name' => 'عراقي'], 'en' => ['name' => 'Iraqi']],
            ['ar' => ['name' => 'يمني'], 'en' => ['name' => 'Yemeni']],
            ['ar' => ['name' => 'فلسطيني'], 'en' => ['name' => 'Palestinian']],
            ['ar' => ['name' => 'كويتي'], 'en' => ['name' => 'Kuwaiti']],
            ['ar' => ['name' => 'إماراتي'], 'en' => ['name' => 'Emirati']],
            ['ar' => ['name' => 'قطري'], 'en' => ['name' => 'Qatari']],
            ['ar' => ['name' => 'بحريني'], 'en' => ['name' => 'Bahraini']],
            ['ar' => ['name' => 'عماني'], 'en' => ['name' => 'Omani']],
            ['ar' => ['name' => 'مغربي'], 'en' => ['name' => 'Moroccan']],
            ['ar' => ['name' => 'تونسي'], 'en' => ['name' => 'Tunisian']],
            ['ar' => ['name' => 'جزائري'], 'en' => ['name' => 'Algerian']],
            ['ar' => ['name' => 'ليبي'], 'en' => ['name' => 'Libyan']],
            ['ar' => ['name' => 'سوداني'], 'en' => ['name' => 'Sudanese']],
            ['ar' => ['name' => 'أمريكي'], 'en' => ['name' => 'American']],
            ['ar' => ['name' => 'بريطاني'], 'en' => ['name' => 'British']],
            ['ar' => ['name' => 'فرنسي'], 'en' => ['name' => 'French']],
            ['ar' => ['name' => 'ألماني'], 'en' => ['name' => 'German']],
            ['ar' => ['name' => 'إيطالي'], 'en' => ['name' => 'Italian']],
            ['ar' => ['name' => 'إسباني'], 'en' => ['name' => 'Spanish']],
            ['ar' => ['name' => 'تركي'], 'en' => ['name' => 'Turkish']],
            ['ar' => ['name' => 'هندي'], 'en' => ['name' => 'Indian']],
            ['ar' => ['name' => 'باكستاني'], 'en' => ['name' => 'Pakistani']],
            ['ar' => ['name' => 'بنغلاديشي'], 'en' => ['name' => 'Bangladeshi']],
            ['ar' => ['name' => 'فلبيني'], 'en' => ['name' => 'Filipino']],
            ['ar' => ['name' => 'إندونيسي'], 'en' => ['name' => 'Indonesian']],
        ];

        foreach ($nationalities as $nationality) {

            Nationality::create($nationality);

        }//end of for each

    }//end of run

}//end of seeder

