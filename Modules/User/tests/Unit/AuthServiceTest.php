<?php

namespace Modules\User\Tests\Unit;

use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Modules\User\Services\AuthService;
use Modules\User\Transformers\OutputLogin;
use Modules\User\Transformers\OutputLogout;
use Modules\User\Transformers\OutputUser;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authService = new AuthService();
    }

    public function testLoginSuccess(): void
    {
        $credentials = ['email' => 'test@example.com', 'password' => 'password'];

        Auth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn('fake_token');

        $result = $this->authService->login($credentials);

        $this->assertInstanceOf(OutputLogin::class, $result);
        $this->assertEquals('fake_token', $result->resource);
    }

    public function testLoginFailure(): void
    {
        $this->expectException(UnauthorizedException::class);

        $credentials = ['email' => 'wrong@example.com', 'password' => 'wrongpassword'];

        Auth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn(false);

        $this->authService->login($credentials);
    }

    public function testLogout(): void
    {
        Auth::shouldReceive('logout')
            ->once();

        $result = $this->authService->logout();

        $this->assertInstanceOf(OutputLogout::class, $result);
        $this->assertTrue($result->resource); 
    }

    public function testGetAuthenticatedUserSuccess(): void
    {
        $user = Mockery::mock('alias:App\Models\User');

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        $result = $this->authService->getAuthenticatedUser();

        $this->assertInstanceOf(OutputUser::class, $result);
        $this->assertEquals($user, $result->resource);
    }

    public function testGetAuthenticatedUserFailure(): void
    {
        $this->expectException(NotFoundException::class);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn(null);

        $this->authService->getAuthenticatedUser();
    }
}
