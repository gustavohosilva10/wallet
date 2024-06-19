<?php

namespace App\Domain\Transfers\Service;

use Illuminate\Support\Facades\Http;
use App\Jobs\SendNotificationJob;

class TransferService
{
    protected $urlAuthorize;
    protected $urlNotify;

    public function __construct()
    {
        $this->urlAuthorize = env('URL_AUTHORIZE');
        $this->urlNotify = env('URL_NOTIFY');
    }

    public function autorizationTransaction(): bool
    {
        try {
            $authorization = Http::get($this->urlAuthorize);

            if ($authorization->failed()) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function sendNotification($paye, $payee, $transferId, $value): bool
    {
        try {
            SendNotificationJob::dispatch($paye, $payee, $transferId, $value);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
