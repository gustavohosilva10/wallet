<?php

namespace App\Infraestructure\Repositories;

use App\Models\Transaction;
use App\Domain\Transfers\Contracts\TransferInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class TransferRepository implements TransferInterface
{
    public function createTransfer(array $request, $payer, $payeer)
    {
        DB::beginTransaction();

        try {
            if(!$this->updateWalletUser($payer, $request['value'])){
                throw new Exception('Falha ao realizar atualização do saldo usuario.');
            }

            if(!$this->updateWalletShopkeeper($payeer, $request['value'])){
                throw new Exception('Falha ao realizar atualização do saldo lojista.');
            }

            $transaction = Transaction::create([
                'payer_id' => $payer->id,
                'payee_id' => $payeer->id,
                'amount' => $request['value']
            ]);

            if(!$transaction){
                throw new Exception('Falha ao salvar a transferencia.');
            }

            DB::commit();

            return $transaction;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception('Falha ao realizar a transferencia.'.$th);
        }

    }

    private function updateWalletUser($payer, float $value): bool
    {
        try {
            $wallet = $payer->wallet;
            $wallet->balance -= $value;
            return $wallet->save();
        } catch (\Throwable $th) {
            throw new Exception('Falha ao atualizar a carteira do usuário: ' . $th->getMessage());
        }
    }

    private function updateWalletShopkeeper($payeer, float $value): bool
    {
        try {
            $wallet = $payeer->wallet;
            $wallet->balance += $value;
            return $wallet->save();
        } catch (\Throwable $th) {
            throw new Exception('Falha ao atualizar a carteira do lojista: ' . $th->getMessage());
        }
    }

}
