<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Domain\Users\Business\UserBusiness;
use App\Domain\Users\Contracts\UserInterface;
use Mockery;
use Exception;

class UserBusinessTest extends TestCase
{
    protected $userBusiness;
    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = Mockery::mock(UserInterface::class);
        $this->userBusiness = new UserBusiness($this->userRepository);
    }

    public function testRegisterUser()
    {
        $request = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'document' => '12345678901',
        ];

        $this->userRepository
            ->shouldReceive('register')
            ->once()
            ->with($request, 'user')
            ->andReturn((object) $request);

        $result = $this->userBusiness->register($request);

        $this->assertTrue($result['success']);
        $this->assertEquals($request['name'], $result['user']->name);
        $this->assertEquals($request['email'], $result['user']->email);
        $this->assertEquals($request['document'], $result['user']->document);
    }

    public function testRegisterUserFails()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Falha ao registrar-se: Test Exception');

        $request = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'document' => '12345678901',
        ];

        $this->userRepository
            ->shouldReceive('register')
            ->once()
            ->with($request, 'user')
            ->andThrow(new Exception('Test Exception'));

        $this->userBusiness->register($request);
    }
}
