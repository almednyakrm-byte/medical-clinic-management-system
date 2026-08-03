<?php

namespace Tests\Unit\Auth;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class TestAuth extends TestCase
{
    /**
     * @test
     */
    public function test_login_valid_credentials()
    {
        // Mock database connection
        $user = Mockery::mock(User::class);
        $user->shouldReceive('where')->andReturn($user);
        $user->shouldReceive('where')->andReturn($user);
        $user->shouldReceive('first')->andReturn(new User());

        // Mock session
        Session::shouldReceive('put')->andReturn(true);

        // Login with valid credentials
        $response = $this->post('/login', ['email' => 'test@example.com', 'password' => 'password']);

        // Assert session login
        $this->assertTrue(Session::has('user_id'));
        $this->assertEquals('test@example.com', Session::get('email'));
    }

    /**
     * @test
     */
    public function test_login_invalid_credentials()
    {
        // Mock database connection
        $user = Mockery::mock(User::class);
        $user->shouldReceive('where')->andReturn($user);
        $user->shouldReceive('where')->andReturn($user);
        $user->shouldReceive('first')->andReturn(null);

        // Mock session
        Session::shouldReceive('put')->andReturn(false);

        // Login with invalid credentials
        $response = $this->post('/login', ['email' => 'test@example.com', 'password' => 'wrong_password']);

        // Assert session login
        $this->assertFalse(Session::has('user_id'));
    }

    /**
     * @test
     */
    public function test_register_valid_credentials()
    {
        // Mock database connection
        DB::shouldReceive('insert')->andReturn(true);

        // Mock session
        Session::shouldReceive('put')->andReturn(true);

        // Register with valid credentials
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'confirm_password' => 'password',
        ]);

        // Assert session login
        $this->assertTrue(Session::has('user_id'));
        $this->assertEquals('test@example.com', Session::get('email'));
    }

    /**
     * @test
     */
    public function test_register_invalid_credentials()
    {
        // Mock database connection
        DB::shouldReceive('insert')->andReturn(false);

        // Mock session
        Session::shouldReceive('put')->andReturn(false);

        // Register with invalid credentials
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'confirm_password' => 'wrong_password',
        ]);

        // Assert session login
        $this->assertFalse(Session::has('user_id'));
    }
}


This test file includes four test methods:

1. `test_login_valid_credentials`: Tests logging in with valid credentials.
2. `test_login_invalid_credentials`: Tests logging in with invalid credentials.
3. `test_register_valid_credentials`: Tests registering with valid credentials.
4. `test_register_invalid_credentials`: Tests registering with invalid credentials.

Each test method uses Mockery to mock the database connection and session. The test methods then assert the expected behavior after logging in or registering.