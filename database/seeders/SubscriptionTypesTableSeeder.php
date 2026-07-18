<?php

namespace Database\Seeders;

use App\Models\SubscriptionType;
use Illuminate\Database\Seeder;

class SubscriptionTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        $year = (int) now()->year;

        $subscriptionTypes = [
            [
                'organization_id' => 1,
                'year' => $year,
                'name' => 'اشتراك سنوي',
                'fees' => 500,
                'has_specific_date' => false,
                'start_date' => null,
                'end_date' => null,
            ],
            [
                'organization_id' => 1,
                'year' => $year,
                'name' => 'اشتراك فصلي',
                'fees' => 250,
                'has_specific_date' => true,
                'start_date' => sprintf('%d-01-01', $year),
                'end_date' => sprintf('%d-06-30', $year),
            ],
            [
                'organization_id' => 1,
                'year' => $year,
                'name' => 'اشتراك شهري',
                'fees' => 50,
                'has_specific_date' => false,
                'start_date' => null,
                'end_date' => null,
            ],
        ];

        foreach ($subscriptionTypes as $subscriptionType) {

            SubscriptionType::create($subscriptionType);

        }//end of foreach

    }//end of run

}//end of seeder
