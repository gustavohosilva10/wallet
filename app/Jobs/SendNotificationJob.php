<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $paye;
    protected $payee;
    protected $transferId;
    protected $value;

    public function __construct($paye, $payee, $transferId, $value)
    {
        $this->paye = $paye;
        $this->payee = $payee;
        $this->transferId = $transferId;
        $this->value = $value;
    }

    public function handle()
    {
        try {
            $notification = Http::post(env('URL_NOTIFY'), [
                'transaction_id' => $this->transferId,
                'amount' => $this->value,
                'payer' => $this->paye->name,
                'payee' => $this->payee->name,
            ]);

            if ($notification->failed()) {
                Log::error('Notification failed', ['response' => $notification->body()]);
            }
        } catch (\Exception $e) {
            Log::error('Notification exception', ['exception' => $e->getMessage()]);
        }
    }

    public function failed(\Exception $exception)
    {
        Log::error('Job failed', ['exception' => $exception->getMessage()]);
    }
}
