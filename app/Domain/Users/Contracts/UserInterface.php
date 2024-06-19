<?php

namespace App\Domain\Users\Contracts;

interface UserInterface
{
    public function register(array $request, string $type);
    public function user($idUser);
}
