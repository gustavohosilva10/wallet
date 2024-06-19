<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Infraestructure\Repositories\UserRepository;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepository();
    }

    public function testRegisterUser()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'document' => '12345678901',
        ];

        $type = 'user';

        $user = $this->userRepository->register($data, $type);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $this->assertDatabaseHas('wallets', ['user_id' => $user->id, 'balance' => 0]);
    }
}
