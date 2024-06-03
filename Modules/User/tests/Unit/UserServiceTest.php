<?php

namespace Modules\User\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Modules\User\Models\User;
use Modules\User\Repositories\UserRepository;
use Modules\User\Services\UserService;
use Modules\User\Transformers\OutputUserCollection;
use Modules\User\Transformers\OutputUserRegister;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;
    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = Mockery::mock(UserRepository::class);
        $this->userService = new UserService($this->userRepository);
    }

    public function testGetAllUsers(): void
    {
        $users = User::factory()->count(3)->make();
        $paginator = new LengthAwarePaginator($users, $users->count(), 10);

        $this->userRepository->shouldReceive('all')
            ->once()
            ->andReturn($paginator);

        $result = $this->userService->getAllUsers();

        $this->assertInstanceOf(OutputUserCollection::class, $result);
        $this->assertCount(3, $result->resource);
    }

    public function testStore(): void
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password'
        ];

        $hashedPassword = Hash::make($userData['password']);

        $user = new User([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => $hashedPassword,
        ]);

        $this->userRepository->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) use ($userData) {
                return $arg['name'] === $userData['name'] &&
                       $arg['email'] === $userData['email'] &&
                       Hash::check($userData['password'], $arg['password']);
            }))
            ->andReturn($user);

        JWTAuth::shouldReceive('fromUser')
            ->once()
            ->with($user)
            ->andReturn('fake_token');

        $result = $this->userService->store($userData['name'], $userData['email'], $userData['password']);

        $this->assertInstanceOf(OutputUserRegister::class, $result);
        $this->assertEquals($user, $result->resource['user']);
        $this->assertEquals('fake_token', $result->resource['token']);
    }
}
