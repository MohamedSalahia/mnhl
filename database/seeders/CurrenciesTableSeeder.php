<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            [
                'organization_id' => 1,
                'ar' => ['name' => 'ريال سعودي', 'code' => 'ر س',],
                'en' => ['name' => 'saudi reyial', 'code' => 'sar',],
            ],
            [
                'organization_id' => 1,
                'ar' => ['name' => 'دينار كويتي', 'code' => 'د ك',],
                'en' => ['name' => 'Kuwait dinar', 'code' => 'kwd',],
            ],
            [
                'organization_id' => 1,
                'ar' => ['name' => 'دولار أمريكي', 'code' => 'دولار',],
                'en' => ['name' => 'American dollar', 'code' => 'usd',],
            ],
            [
                'organization_id' => 1,
                'ar' => ['name' => 'جنيه مصري', 'code' => 'ج م',],
                'en' => ['name' => 'egyptian pound', 'code' => 'egp',],
            ],
            [
                'organization_id' => 1,
                'ar' => ['name' => 'درهم مغربي', 'code' => 'د م',],
                'en' => ['name' => 'moroccan dir-ham', 'code' => 'mad',],
            ],
        ];

        foreach ($currencies as $currency) {

            Currency::create($currency);

        }//end of for each

    }//end of run

}//end of seeder
