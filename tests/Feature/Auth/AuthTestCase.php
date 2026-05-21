<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

abstract class AuthTestCase extends TestCase
{
    use RefreshDatabase;

    protected function makeUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'password' => Hash::make('password'),
        ], $attributes));
    }

    protected function loginAs(?User $user = null): User
    {
        $user ??= $this->makeUser();

        $this->actingAs($user);

        return $user;
    }
}
