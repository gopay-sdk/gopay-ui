<?php

namespace Gopay\GopayUi\Services;

use Gopay\GopayUi\Models\Gopay as GopayModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function transactionStatus(string $myref)
    {
        if (config('gopay.environment') === 'sandbox') {
            return (object) [
                'status' => 'success',
                'status' => cache()->get(
                    "gopay_test_status_{$myref}",
                    'success'
                ),
                'sandbox' => true,
            ];
        }

        try {
            $url = "https://gopay.gooomart.com/api/v3/payment/check/$myref";

            $apiKey = config('gopay.api_key');
            $secretKey = config('gopay.secret_key');

            $nonce = bin2hex(random_bytes(16));
            $timestamp = time();

            $method = 'GET';
            $path   = parse_url($url, PHP_URL_PATH);
            $paramsQuery = "";

            $message   = $path . $method . $paramsQuery . $nonce . $timestamp;
            $signature = hash_hmac('sha256', $message, $secretKey);

            $headers = [
                "x-api-key: $apiKey",
                "x-signature: $signature",
                "x-timestamp: $timestamp",
                "x-nonce: $nonce",
                "Content-Type: application/json",
                "Accept: application/json",
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
            $response = curl_exec($ch);
            $status = null;
            if (!curl_errno($ch)) {
                curl_close($ch);
                $status = @json_decode($response)->transaction;
            }

            return $status;
        } catch (\Throwable $th) {
        }
    }

    public function saveData(GopayModel $trans): void
    {
        try {
            DB::transaction(function () use ($trans) {

                $dto = json_decode($trans->paydata, true);

                foreach ($dto['insertActions'] ?? [] as $action) {

                    $model = $action['model'];

                    $data = $this->replacePlaceholders(
                        $action['data'],
                        $trans
                    );

                    $model::create($data);
                }

                foreach ($dto['updateActions'] ?? [] as $action) {

                    $model = $action['model'];

                    $where = $this->replacePlaceholders(
                        $action['where'],
                        $trans
                    );

                    $data = $this->replacePlaceholders(
                        $action['data'],
                        $trans
                    );

                    $model::where($where)->update($data);
                }

                $trans->update([
                    'issaved' => 1,
                    'isfailed' => 0,
                    'save_error' => null,
                ]);
            });
        } catch (\Throwable $th) {
            $mess = $th->getMessage();
            Log::error('[GoPayUI SDK] saveData failed', [
                'reference' => $trans->myref,
                'message' => $mess,
            ]);
            $trans->update(['save_error' => $mess]);
        }
    }

    public function replacePlaceholders(array $data, GopayModel $trans): array
    {
        $pd = (object) @json_decode($trans->paydata);

        $placeholders = [
            '{reference}' => @$trans->myref,
            '{amount}' => @$pd->amount,
            '{currency}' => @$pd->currency,
        ];

        array_walk_recursive($data, function (&$value) use ($placeholders) {
            if (!is_string($value)) {
                return;
            }
            if (array_key_exists($value, $placeholders)) {
                $value = $placeholders[$value];
            }
        });

        return $data;
    }

    public function replaceUrl(?string $url, GopayModel $trans): ?string
    {
        if (!$url) {
            return $url;
        }

        // split base + query string
        $parts = parse_url($url);

        $path = $parts['path'] ?? '';

        $queryArray = [];

        if (!empty($parts['query'])) {
            parse_str($parts['query'], $queryArray);
        }

        $data = [
            'path' => $path,
            'query' => $queryArray,
        ];

        $data = $this->replacePlaceholders($data, $trans);

        // rebuild URL
        $query = http_build_query($data['query']);

        return $data['path'] . ($query ? '?' . $query : '');
    }
}
