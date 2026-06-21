<?php

namespace Gopay\GopayUi;

use Exception;
use Gopay\GopayUi\DTO\PaymentFormData;
use Gopay\GopayUi\Http\Controllers\GopayController;
use Gopay\GopayUi\Models\GopayForm;
use Illuminate\Support\Str;

class GopayUI
{
    public static function renderForm(PaymentFormData $dto)
    {
        do {
            $reference = Str::uuid()->toString();
        } while (GopayForm::where('reference', $reference)->exists());

        // Signature sécurisée
        $signature = hash_hmac(
            'sha256',
            "$reference|$dto->amount|$dto->currency",
            config('gopay.secret_key')
        );

        // Sauvegarde DB
        GopayForm::create([
            'reference' => $reference,
            'amount' => $dto->amount,
            'currency' => $dto->currency,
            'phone' => $dto->phone,
            'payload' => GopayController::crypto(json_encode($dto->toArray(),  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)),
            'signature' => $signature,
        ]);

        return view('gopay::payment', compact('dto', 'reference', 'signature'));
    }
}
