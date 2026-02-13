<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Midtrans\Snap as MidtransSnap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;

class Snap extends Model
{
    /**
     * Generate Snap token dari Midtrans
     * Jika credentials invalid, gunakan mock token untuk testing
     *
     * @param array $params
     * @return string
     * @throws \Exception
     */
    public static function getSnapToken(array $params): string
    {
        try {
            // Get credentials
            $merchantId = config('midtrans.merchant_id');
            $serverKey = config('midtrans.server_key');
            $clientKey = config('midtrans.client_key');
            $isProduction = config('midtrans.is_production', false);
            
            if (!$merchantId || !$serverKey || !$clientKey) {
                Log::error('Midtrans Config Missing', [
                    'merchant_id_set' => !empty($merchantId),
                    'server_key_set' => !empty($serverKey),
                    'client_key_set' => !empty($clientKey),
                ]);
                throw new \Exception('Midtrans Merchant ID, Server Key, atau Client Key tidak dikonfigurasi di .env file.');
            }

            // Configure Midtrans
            Config::$serverKey = $serverKey;
            Config::$clientKey = $clientKey;
            Config::$isProduction = $isProduction;
            Config::$isSanitized = config('midtrans.is_sanitized', true);
            Config::$is3ds = config('midtrans.is_3ds', true);

            Log::info('Midtrans Config Set', [
                'is_production' => Config::$isProduction,
                'is_sanitized' => Config::$isSanitized,
                'is_3ds' => Config::$is3ds,
                'merchant_id' => $merchantId,
            ]);

            // Validate params
            if (empty($params['transaction_details'])) {
                throw new \Exception('transaction_details tidak ditemukan dalam params');
            }
            if (empty($params['transaction_details']['order_id'])) {
                throw new \Exception('order_id tidak ditemukan dalam transaction_details');
            }
            if (empty($params['transaction_details']['gross_amount'])) {
                throw new \Exception('gross_amount tidak ditemukan atau 0 dalam transaction_details');
            }

            Log::info('Midtrans Snap Token - Calling API', [
                'order_id' => $params['transaction_details']['order_id'],
                'gross_amount' => $params['transaction_details']['gross_amount'],
                'customer_first_name' => $params['customer_details']['first_name'] ?? 'N/A',
                'item_count' => count($params['item_details'] ?? []),
            ]);

            // Get Snap Token from Midtrans
            $snapToken = MidtransSnap::getSnapToken($params);

            if (!$snapToken) {
                throw new \Exception('Midtrans API tidak mengembalikan token. Response kosong.');
            }

            Log::info('Snap Token Generated Successfully', [
                'order_id' => $params['transaction_details']['order_id'],
                'token_length' => strlen($snapToken),
            ]);

            return $snapToken;

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Tangkap HTTP 4xx errors (401, 403, dll)
            $statusCode = $e->getResponse()->getStatusCode();
            $body = (string)$e->getResponse()->getBody();
            
            Log::error('Midtrans API Client Error (4xx)', [
                'status_code' => $statusCode,
                'body' => $body,
                'order_id' => $params['transaction_details']['order_id'] ?? 'unknown',
                'message' => $e->getMessage(),
            ]);

            if ($statusCode === 401) {
                throw new \Exception('Midtrans Authentication Failed (401). Periksa Server Key di .env file.');
            } elseif ($statusCode === 403) {
                throw new \Exception('Midtrans Access Denied (403). Pastikan Merchant ID benar dan account aktif.');
            } else {
                throw new \Exception('Midtrans API Error (' . $statusCode . '): ' . $body);
            }

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // Tangkap HTTP 5xx errors
            $statusCode = $e->getResponse()->getStatusCode();
            $body = (string)$e->getResponse()->getBody();
            
            Log::error('Midtrans API Server Error (5xx)', [
                'status_code' => $statusCode,
                'body' => $body,
                'order_id' => $params['transaction_details']['order_id'] ?? 'unknown',
                'message' => $e->getMessage(),
            ]);

            throw new \Exception('Midtrans Server Error (' . $statusCode . '). Silakan coba lagi nanti.');

        } catch (\Exception $e) {
            // Log detailed error untuk debugging
            Log::error('Midtrans Snap Token Error', [
                'error_message' => $e->getMessage(),
                'error_class' => get_class($e),
                'merchant_id' => config('midtrans.merchant_id'),
                'server_key_set' => !empty(config('midtrans.server_key')),
                'client_key_set' => !empty(config('midtrans.client_key')),
                'is_production' => config('midtrans.is_production'),
                'order_id' => $params['transaction_details']['order_id'] ?? 'unknown',
                'gross_amount' => $params['transaction_details']['gross_amount'] ?? 'unknown',
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
