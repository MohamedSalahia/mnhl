<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{
    public function run(): void
    {
        $paymentMethods = [
            [
                'organization_id' => 1,
                'ar' => ['name' => 'نقدي'],
                'en' => ['name' => 'Cash'],
            ],
            [
                'organization_id' => 1,
                'ar' => ['name' => 'تحويل بنكي'],
                'en' => ['name' => 'Bank transfer'],
            ],
            [
                'organization_id' => 1,
                'ar' => ['name' => 'بطاقة ائتمان'],
                'en' => ['name' => 'Credit card'],
            ],
        ];

        foreach ($paymentMethods as $paymentMethod) {

            PaymentMethod::create($paymentMethod);

        }//end of foreach

    }//end of run

}//end of seeder
