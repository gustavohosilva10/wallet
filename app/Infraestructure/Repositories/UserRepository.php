<?php

namespace App\Infraestructure\Repositories;

use App\Models\User;
use App\Models\Wallet;
use App\Domain\Users\Contracts\UserInterface;

class UserRepository implements UserInterface
{
    public function register(array $data, string $type)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'document' => $data['document'],
            'type' => $type
        ]);

        if (!$user) {
            throw new Exception('Falha ao registrar usuário.');
        }

        $this->createWallet($user->id);

        return $user;
    }

    private function createWallet($idUser):void
    {
        Wallet::create([
            'user_id' => $idUser,
            'balance' => 0,
        ]);
    }

    public function user($idUser)
    {
        return User::with('wallet')->findOrFail($idUser);
    }
}
