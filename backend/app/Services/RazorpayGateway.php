<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class RazorpayGateway
{
    public function assertTestMode(): void
    {
        $config = config('services.razorpay');
        if ($config['mode'] !== 'test'
            || !str_starts_with((string) $config['key_id'], 'rzp_test_')
            || empty($config['key_secret'])) {
            throw new RuntimeException('Razorpay Test Mode credentials are required. Live charging is disabled.');
        }
    }

    public function publicKey(): string
    {
        $this->assertTestMode();
        return config('services.razorpay.key_id');
    }

    public function createOrder(int $amountPaise, string $receipt, int $schoolId): array
    {
        return $this->request('post', '/orders', [
            'amount' => $amountPaise,
            'currency' => 'INR',
            'receipt' => $receipt,
            'notes' => ['school_id' => (string) $schoolId],
        ]);
    }

    public function fetchOrder(string $orderId): array
    {
        return $this->request('get', '/orders/' . rawurlencode($orderId));
    }

    public function fetchPayment(string $paymentId): array
    {
        return $this->request('get', '/payments/' . rawurlencode($paymentId));
    }

    public function validCheckoutSignature(string $orderId, string $paymentId, string $signature): bool
    {
        $this->assertTestMode();
        $expected = hash_hmac('sha256', $orderId . '|' . $paymentId, config('services.razorpay.key_secret'));
        return hash_equals($expected, $signature);
    }

    public function validWebhookSignature(string $body, string $signature): bool
    {
        $secret = (string) config('services.razorpay.webhook_secret');
        if ($secret === '' || config('services.razorpay.mode') !== 'test') {
            return false;
        }
        return hash_equals(hash_hmac('sha256', $body, $secret), $signature);
    }

    private function request(string $method, string $path, array $body = []): array
    {
        $this->assertTestMode();
        $client = Http::withBasicAuth(
            config('services.razorpay.key_id'),
            config('services.razorpay.key_secret')
        )->acceptJson()->timeout(15);

        $response = $method === 'post'
            ? $client->post('https://api.razorpay.com/v1' . $path, $body)
            : $client->get('https://api.razorpay.com/v1' . $path);

        if (!$response->successful() || !is_array($response->json())) {
            throw new RuntimeException('Razorpay Test Mode request failed. Please retry later.');
        }

        return $response->json();
    }
}
