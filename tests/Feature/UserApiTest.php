<?php

namespace Tests\Feature;

use App\Http\Responses\ApiResponse;
use App\Models\User;
use Tests\BaseApiTest;

class UserApiTest extends BaseApiTest
{
    public function test_profile_expectSuccess()
    {
        $this->actingAs($this->user, 'api')
            ->getJson(route('user.profile'))
            ->assertOk()
            ->assertJson([
                'data' => $this->user->toArray()
            ]);
    }

    public function test_profile_expectNotFound()
    {
        //if admin try to access
        $user = User::factory()->create([
            'role' => User::ROLE['ADMIN']
        ]);
        $this->actingAs($user, 'api')
            ->getJson(route('user.profile'))
            ->assertStatus(ApiResponse::NOT_FOUND);
    }
}
