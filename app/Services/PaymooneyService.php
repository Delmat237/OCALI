<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymooneyService
{
    private Client $client;
    private string $apiUrl;
    private ?string $publicKey;
    private ?string $secretKey;
    private string $environment;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = config('services.paymooney.url');
        $this->publicKey = config('services.paymooney.public_key');
        $this->secretKey = config('services.paymooney.secret_key');
        $this->environment = config('services.paymooney.environment', 'live');

        if (!$this->publicKey) {
            throw new \InvalidArgumentException('PAYMOONEY_PUBLIC_KEY n\'est pas configurée');
        }
        if (!$this->secretKey) {
            throw new \InvalidArgumentException('PAYMOONEY_SECRET_KEY n\'est pas configurée');
        }
    }

    /**
     * Créer un lien de paiement pour PayPal et Carte bancaire
     *
     * @param array $orderData Données à stocker pour callback
     * @param float $amountXAF Montant en XAF
     * @param string $email Email du client
     * @param string $firstName Prénom
     * @param string $lastName Nom
     * @param string $itemName Nom de l'article
     * @return array{success: bool, payment_url?: string, session_id?: string, error?: string}
     */
    public function createPaymentLink(
        array  $orderData,
        float  $amountXAF,
        string $email,
        string $firstName,
        string $lastName,
        string $itemName = 'Abonnement OCaLi'
    ): array
    {
        // Convertir XAF en USD
        $amountUSD = $this->convertToUsd($amountXAF);

        // Calculer les frais selon le barème
        $fees = $this->calculateFees($amountUSD);
        $totalAmount = $amountUSD + $fees;

        Log::info("OCaLi Payment: {$amountXAF} XAF = {$totalAmount} USD (fees: {$fees} USD)");

        try {
            $sessionId = $this->storeOrderSession($orderData);

            $payload = [
                'amount' => $totalAmount,
                'currency_code' => 'USD',
                'code' => 'CM',
                'lang' => app()->getLocale() === 'en' ? 'EN' : 'FR',
                'item_ref' => $sessionId,
                'item_name' => $itemName,
                'description' => 'Paiement sur OCaLi - Bibliothèque en ligne',
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'public_key' => $this->publicKey,
                'logo' => asset('images/logo.png'),
                'environement' => $this->environment,
            ];

            $response = $this->client->post($this->apiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $this->secretKey,
                ],
                'body' => json_encode($payload),
            ]);

            $result = json_decode($response->getBody()->getContents());

            if (isset($result->response) && $result->response === 'success') {
                return [
                    'success' => true,
                    'payment_url' => $result->payment_url,
                    'session_id' => $sessionId,
                    'fees' => $fees,
                    'total_usd' => $totalAmount,
                ];
            }

            return [
                'success' => false,
                'error' => $result->message ?? 'Échec de génération du lien de paiement',
            ];
        } catch (GuzzleException $e) {
            Log::error('Erreur API Paymooney OCaLi', [
                'message' => $e->getMessage(),
                'amount' => $amountXAF,
                'email' => $email,
            ]);

            return [
                'success' => false,
                'error' => 'Service de paiement indisponible. Veuillez réessayer.',
            ];
        }
    }

    /**
     * Calculer les frais selon le barème PayMooney
     */
    private function calculateFees(float $amountUSD): float
    {
        if ($amountUSD <= 50) {
            return 0.30 + ($amountUSD * 0.07);
        } elseif ($amountUSD <= 1000) {
            return $amountUSD * 0.07;
        } elseif ($amountUSD <= 10000) {
            return $amountUSD * 0.06;
        } else {
            return round($amountUSD * 0.05, 6);
        }
    }

    /**
     * Stocker les données de session pour le callback
     */
    private function storeOrderSession(array $orderData): string
    {
        $sessionId = Str::uuid()->toString();

        DB::table('order_session_logs')->insert([
            'id' => $sessionId,
            'data' => json_encode($orderData),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $sessionId;
    }

    /**
     * Récupérer les données de session par ID
     */
    public function getOrderSession(string $sessionId): ?array
    {
        $session = DB::table('order_session_logs')
            ->where('id', $sessionId)
            ->first();

        return $session ? json_decode($session->data, true) : null;
    }

    /**
     * Convertir XAF en USD
     */
    public function convertToUsd(float $amountXAF): float
    {
        // Taux approximatif: 1 USD = 600 XAF
        return round($amountXAF / 600, 2);
    }

    /**
     * Convertir USD en XAF
     */
    public function convertToXaf(float $amountUSD): float
    {
        return round($amountUSD * 600, 0);
    }
}
