<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            LaratrustSeeder::class,
            UsersTableSeeder::class,
            LanguagesTableSeeder::class,
            CountriesTableSeeder::class,
            GovernoratesTableSeeder::class,
            AreasTableSeeder::class,
            NationalitiesTableSeeder::class,
            OrganizationsTableSeeder::class,
            AssessmentSchemeTableSeeder::class,
            EvaluationModelsTableSeeder::class,
            EvaluationItemsTableSeeder::class,
            CurriculaTableSeeder::class,
            ProjectsTableSeeder::class,
            LevelsTableSeeder::class,
            TeachersTableSeeder::class,
            ExaminersTableSeeder::class,
            ClassroomsTableSeeder::class,
            CurrenciesTableSeeder::class,
            PaymentMethodsTableSeeder::class,
            SubscriptionTypesTableSeeder::class,
            StudentsTableSeeder::class,
        ]);
    }
}
