<?php

namespace Tests;

use App\Models\LoanApplication;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

abstract class BaseApiTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    public User $user;

    /**
     * Runs each time test starts.
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->cleanAllData();
        $user = User::query()->where('email', 'test@user.com')->first();
        if (!$user) {
            $user = User::factory()
                ->create([
                    'email' => 'test@user.com',
                    'role' => User::ROLE['USER']
                ]);
        }
        $this->user = $user;

        $this->be($this->user, 'api');
    }

    protected function logout()
    {
        // log out from current default auth
        $protectedProperty = new \ReflectionProperty($this->app['auth'], 'guards');
        $protectedProperty->setAccessible(true);
        $protectedProperty->setValue($this->app['auth'], []);
    }

    protected function cleanAllData()
    {
        LoanApplication::query()->forceDelete();
        User::query()->forceDelete();
        return true;
    }
}
