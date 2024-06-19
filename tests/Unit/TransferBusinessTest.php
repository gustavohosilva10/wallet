<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Domain\Transfers\Business\TransferBusiness;
use App\Domain\Transfers\Contracts\TransferInterface;
use App\Domain\Users\Contracts\UserInterface;
use App\Domain\Transfers\Service\TransferService;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Exception;

class TransferBusinessTest extends TestCase
{
    protected $transferBusiness;
    protected $transferRepository;
    protected $userRepository;
    protected $transferService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->transferRepository = $this->app->make(TransferInterface::class);
        $this->userRepository = $this->app->make(UserInterface::class);
        $this->transferService = $this->app->make(TransferService::class);

        $this->transferBusiness = new TransferBusiness(
            $this->transferRepository,
            $this->userRepository,
            $this->transferService
        );
    }

    public function testSuccessfulTransfer()
    {
        $payer = User::factory()->create(['type' => 'user']);
        $payer->wallet()->save(Wallet::factory()->make(['balance' => 100]));

        $payeer = User::factory()->create(['type' => 'shopkeeper']);
        $payeer->wallet()->save(Wallet::factory()->make(['balance' => 0]));

        $request = [
            'payer' => $payer->id,
            'payeer' => $payeer->id,
            'value' => 50,
        ];

        $transfer = $this->transferBusiness->transfer($request);

        $this->assertTrue($transfer['success']);
        $this->assertEquals('Transferência realizada com sucesso.', $transfer['message']);
        $this->assertInstanceOf(Transaction::class, $transfer['transfer']);
    }

    public function testInsufficientBalance()
    {
        $payer = User::factory()->create(['type' => 'user']);
        $payer->wallet()->save(Wallet::factory()->make(['balance' => 10]));

        $payeer = User::factory()->create(['type' => 'shopkeeper']);
        $payeer->wallet()->save(Wallet::factory()->make(['balance' => 0]));

        $request = [
            'payer' => $payer->id,
            'payeer' => $payeer->id,
            'value' => 50,
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Saldo insuficiente.');
        $this->transferBusiness->transfer($request);
    }

    public function testUnauthorizedTransaction()
    {
        $payer = User::factory()->create(['type' => 'user']);
        $payer->wallet()->save(Wallet::factory()->make(['balance' => 100]));

        $payeer = User::factory()->create(['type' => 'shopkeeper']);
        $payeer->wallet()->save(Wallet::factory()->make(['balance' => 0]));

        $request = [
            'payer' => $payer->id,
            'payeer' => $payeer->id,
            'value' => 50,
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Transação não autorizada tente novamente.');
        $this->transferBusiness->transfer($request);
    }
}
