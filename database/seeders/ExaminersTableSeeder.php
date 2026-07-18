<?php

namespace Database\Seeders;

use App\Enums\UserTypeEnum;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExaminersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branch = Branch::find(1);

        $examiners = [
            [
                'name' => 'ممتحن 1',
                'email' => 'examiner1@org1.com',
            ],
            [
                'name' => 'ممتحن 2',
                'email' => 'examiner2@org1.com',
            ],
            [
                'name' => 'ممتحن 3',
                'email' => 'examiner3@org1.com',
            ],
            [
                'name' => 'ممتحن 4',
                'email' => 'examiner4@org1.com',
            ],
            [
                'name' => 'ممتحن 5',
                'email' => 'examiner5@org1.com',
            ],
        ];

        foreach ($examiners as $examinerData) {

            $examiner = User::create([
                'name' => $examinerData['name'],
                'email' => $examinerData['email'],
                'password' => bcrypt('password'),
                'type' => UserTypeEnum::EXAMINER,
            ]);

            $examiner->examinerBranches()->attach($branch->id);

            if ($branch->team_id) {

                $examiner->syncRoles([UserTypeEnum::EXAMINER], $branch->team_id);

            } else {

                $examiner->addRole(UserTypeEnum::EXAMINER);

            }

        }//end of foreach

    }//end of run

}//end of seeder
