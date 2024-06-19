<?php

namespace App\Domain\Transfers\Contracts;

interface TransferInterface
{
    public function createTransfer(array $request, $payer, $payeer);
}
