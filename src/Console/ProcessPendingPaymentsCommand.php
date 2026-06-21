<?php

namespace Gopay\GopayUi\Console;

use Gopay\GopayUi\Http\Controllers\GopayController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Gopay\GopayUi\Models\Gopay as GopayModel;
use Gopay\GopayUi\Services\PaymentService;

class ProcessPendingPaymentsCommand extends Command
{
    protected $signature = 'gopay:process-pending';
    protected $description = 'Process pending GoPay payments';

    public function handle(PaymentService $paymentService): int
    {
        GopayModel::query()
            ->where('date', '<=', now('Africa/Lubumbashi')->subMinutes(1))
            ->where('environment', 'production')
            ->where('issaved', 0)
            ->where('isfailed', 0)
            ->chunkById(100, function ($transactions) use ($paymentService) {
                foreach ($transactions as $trans) {
                    try {
                        $status = $paymentService->transactionStatus($trans->myref);
                        $state = $status->status ?? null;
                        if ($state === 'success') {
                            $paymentService->saveData($trans);
                        }

                        if ($state === 'failed') {
                            $trans->update([
                                'isfailed' => 1,
                            ]);
                        }
                    } catch (\Throwable $e) {
                        Log::error(
                            '[GoPayUI SDK] process-pending',
                            [
                                'myref' => $trans->myref,
                                'message' => $e->getMessage(),
                            ]
                        );
                    }
                }
            });

        $this->info('Pending payments processed.');

        return self::SUCCESS;
    }
}
