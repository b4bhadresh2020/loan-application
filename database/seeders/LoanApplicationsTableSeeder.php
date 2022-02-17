<?php

namespace Database\Seeders;

use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Support\Str;

class LoanApplicationsTableSeeder extends BaseSeeder
{
    public function run()
    {
        $user = User::all();
        $adminId = User::whereRole(User::ROLE['ADMIN'])->value('id');
        foreach ($user as $user) {
            LoanApplication::create(
                [
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'amount' => $this->faker->numerify('###.##'),
                    'tenure' => rand(5, 20),
                    'interest' => $this->faker->numerify('##.##'),
                    'approver_id' => $adminId
                ]
            );
        }
    }
}
