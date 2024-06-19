<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransferControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testTransferEndpoint()
    {
        $payer = User::factory()->create(['type' => 'user']);
        $payer->wallet()->save(Wallet::factory()->make(['balance' => 100]));

        $payeer = User::factory()->create(['type' => 'shopkeeper']);
        $payeer->wallet()->save(Wallet::factory()->make(['balance' => 0]));

        $data = [
            'payer' => $payer->id,
            'payeer' => $payeer->id,
            'value' => 50,
        ];

        $response = $this->postJson('/api/transfer', $data);
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Transferência realizada com sucesso.',
            ]);
    }
}
