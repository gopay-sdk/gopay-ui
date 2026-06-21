<?php

namespace Gopay\GopayUi\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Gopay\GopayUi\Models\GopayForm;
use Gopay\GopayUi\Models\Gopay as GopayModel;
use Gopay\GopayUi\Services\PaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Mecxer713\GoPay\Facades\GoPay;

class GopayController extends Controller
{
    public function init()
    {
        $validator = Validator::make(
            request()->all(),
            [
                'reference' => 'required|string',
                'signature' => 'required|string',
                'phone' => 'required|digits:9',
                'test_status' => 'nullable|in:success,failed',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(' ', $validator->errors()->all()),
            ], 422);
        }

        $data = (object) $validator->validated();

        $form = GopayForm::where('reference', $data->reference)->first();
        if (!$form) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payment reference',
            ], 422);
        }

        $expectedSignature = hash_hmac(
            'sha256',
            "{$form->reference}|{$form->amount}|{$form->currency}",
            config('gopay.secret_key')
        );

        if (!hash_equals($expectedSignature, $data->signature)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid signature',
            ], 422);
        }

        $phone = '+243' . $data->phone;
        $now = now('Africa/Lubumbashi');

        $myref = sprintf(
            'T%s.%s.%s',
            $now->format('ymd'),
            $now->format('His'),
            rand(1000, 9999)
        );

        if (config('gopay.environment') === 'sandbox') {
            $myref = "TEST_$myref";
        }

        $amount = $form->amount;
        $currency = $form->currency;

        $payload = GopayController::crypto($form->payload, false);
        $payload = json_decode($payload);
        abort_if(!is_object($payload), 422, "Invalid payload data");

        try {
            $transaction = GopayModel::create([
                'issaved' => 0,
                'isfailed' => 0,
                'myref' => $myref,
                'paydata' => json_encode($payload),
                'date' => $now,
                'environment' => config('gopay.environment'),
            ]);

            if (config('gopay.environment') === 'sandbox') {
                $ref = 'TEST_' . uniqid();
                $transaction->update([
                    'ref' => $ref,
                ]);

                cache()->put(
                    "gopay_test_status_{$myref}",
                    request('test_status', 'success'),
                    now()->addHour()
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Transaction initialisée avec succès. Veuillez saisir votre Pin mobile Money pour confirmer la transaction.',
                    'data' => [
                        'ref' => $ref,
                        'myref' => $myref,
                        'sandbox' => true,
                    ]
                ]);
            }

            // Initier un paiement
            $response = (object) GoPay::initPayment($amount, $currency, $phone, $myref);
            $raw = (object) @$response->raw;

            $success = @$raw->success === true;
            $rep = (object) [];
            $rep->success = $success;

            if (!$success) {
                $m = (array) @$raw->data['errors_msg'];
                $m = implode(' ', $m);
                $t = [];
                $t[] = @$raw->message . " : ";
                $t[] = $m;
                $t = array_filter($t);
                $t = implode(' ', $t);
                $rep->message = $t;
                return $rep;
            }

            $ref = @$raw->data['ref'];
            $transaction->update(compact('ref'));

            return $raw;
        } catch (\Throwable $th) {
            Log::info("[GoPayUI SDK] init payment : " . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Erreur, veuillez réessayer SVP.",
            ], 422);
        }
    }


    public function check(PaymentService $paymentService)
    {
        $myref = request()->myref;
        $trans = GopayModel::where('myref', $myref)->first();

        if (!$trans) {
            return response([
                'success' => false,
                'message' => 'Invalid ref'
            ], 404);
        }

        $t = $paymentService->transactionStatus($myref);
        $status = $t->status ?? null;

        if ($status === 'success') {
            DB::transaction(function () use ($myref, $paymentService) {
                $trans = GopayModel::where('myref', $myref)
                    ->lockForUpdate()
                    ->first();
                if (!$trans || $trans->issaved == 1) {
                    return;
                }
                $paymentService->saveData($trans);
            });
        }

        if ($status === 'failed') {
            DB::transaction(function () use ($myref) {
                $trans = GopayModel::where('myref', $myref)
                    ->lockForUpdate()
                    ->first();
                if (!$trans || $trans->isfailed == 1) {
                    return;
                }
                $trans->update([
                    'isfailed' => 1
                ]);
            });
        }

        $trans->refresh();

        if ($trans->issaved == 1) {
            $d = @json_decode($trans->paydata);
            $action = ['onSuccess' => @$d->onSuccess, 'redirectUrl' => @$d->redirectUrl];
            if (!empty($action['redirectUrl'])) {
                $action['redirectUrl'] = $paymentService->replaceUrl(
                    $action['redirectUrl'],
                    $trans
                );
            }

            return response([
                'success' => true,
                'message' => 'Votre paiement est effectué avec succès.',
                'transaction' => $t,
                'action' => $action,
            ]);
        }

        return response([
            'success' => false,
            'message' => 'Aucun paiement trouvé.',
            'transaction' => $t
        ]);
    }

    public static function crypto($str, $encrypt = true)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = config('gopay.secret_key');
        if ($secret_key === null) {
            throw new Exception("GoPay Secret Key is missed in your enviroment variable");
        }
        $secret_iv = '2026';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($encrypt == true) {
            $output = openssl_encrypt($str, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else {
            $output = openssl_decrypt(base64_decode($str), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
}
