<?php

namespace App\Domain\Transfers\Business;

use App\Domain\Transfers\Contracts\TransferInterface;
use App\Domain\Transfers\Service\TransferService;
use App\Domain\Users\Contracts\UserInterface;
use Exception;

class TransferBusiness
{
    protected $transferRepository;
    protected $userRepository;
    protected $transferService;

    public function __construct(TransferInterface $transferRepository, UserInterface $userRepository, TransferService $transferService)
    {
        $this->transferRepository = $transferRepository;
        $this->userRepository = $userRepository;
        $this->transferService = $transferService;
    }

    public function transfer(array $request): array
    {
        try {
            if (!$this->validateTypeUser($request['payer'])) {
                throw new Exception('Lojista não pode realizar transferências.');
            }

            if (!$this->validateBalance($request['payer'], $request['value'])) {
                throw new Exception('Saldo insuficiente.');
            }

            if(!$this->autorizationTransaction()){
                throw new Exception('Transação não autorizada tente novamente.');
            }

            $transfer = $this->transferRepository->createTransfer(
                $request,
                $this->userRepository->user($request['payer']),
                $this->userRepository->user($request['payeer'])
            );

            if(!$this->transferService->sendNotification(
                $this->userRepository->user($request['payer']),
                $this->userRepository->user($request['payeer']),
                $transfer->id,
                $request['value']
            )){
                throw new Exception('Falha ao enviar a notificação.');
            }

            return [
                'success' => true,
                'message' => 'Transferência realizada com sucesso.',
                'transfer' => $transfer,
            ];
        } catch (Exception $e) {
            throw new Exception('Falha na transferência: ' . $e->getMessage());
        }
    }

    private function validateTypeUser(string $payer): bool
    {
        $payer = $this->userRepository->user($payer);

        return $payer->type !== 'shopkeeper';
    }

    private function validateBalance(string $payer, float $value): bool
    {
        $payer = $this->userRepository->user($payer);

        if (!$payer->wallet) {
            throw new Exception('Carteira não encontrada para o usuário.');
        }

        return $payer->wallet->balance >= $value;
    }

    private function autorizationTransaction(): bool
    {
        return $this->transferService->autorizationTransaction();
    }
}
