<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Auth\Auth;
use App\Auth\User;
use App\Auth\Repository\UserRepository;
use App\Auth\Repository\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;

class TestAuth extends TestCase
{
    private $auth;
    private $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->auth = new Auth($this->userRepository);
    }

    public function testLoginSuccess()
    {
        $username = 'test_user';
        $password = 'test_password';

        $this->userRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(new User($username, $password));

        $this->auth->login($username, $password);

        $this->assertTrue($this->auth->isLoggedIn());
    }

    public function testLoginFailure()
    {
        $username = 'test_user';
        $password = 'test_password';

        $this->userRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $this->auth->login($username, $password);

        $this->assertFalse($this->auth->isLoggedIn());
    }

    public function testRegisterSuccess()
    {
        $username = 'test_user';
        $password = 'test_password';

        $this->userRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $this->userRepository->expects($this->once())
            ->method('createUser')
            ->with(new User($username, $password));

        $this->auth->register($username, $password);

        $this->assertTrue($this->auth->isLoggedIn());
    }

    public function testRegisterFailure()
    {
        $username = 'test_user';
        $password = 'test_password';

        $this->userRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(new User($username, $password));

        $this->auth->register($username, $password);

        $this->assertFalse($this->auth->isLoggedIn());
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Verifies that a user can successfully log in with the correct credentials.
- `testLoginFailure`: Verifies that a user cannot log in with incorrect credentials.
- `testRegisterSuccess`: Verifies that a user can successfully register with the correct credentials.
- `testRegisterFailure`: Verifies that a user cannot register with existing credentials.

Each test method uses the `createMock` method to create a mock object for the `UserRepositoryInterface` and sets up expectations for the `getUserByUsername` and `createUser` methods. The `login` and `register` methods are then called with the test data, and the `isLoggedIn` method is asserted to verify the expected outcome.