<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class NokashService
{
    protected string $baseUrl;
    protected ?string $iSpaceKey;
    protected ?string $appSpaceKey;

    public function __construct()
    {
        $this->baseUrl = config('services.nokash.base_url') ?? 'https://api.nokash.app/lapas-on-trans/trans';
        $this->iSpaceKey = config('services.nokash.i_space_key');
        $this->appSpaceKey = config('services.nokash.app_space_key');
    }

    /**
     * Vérifier si le service est configuré
     */
    public function isConfigured(): bool
    {
        return !empty($this->iSpaceKey) && !empty($this->appSpaceKey);
    }

    /**
     * Générer une clé d'authentification (auth-code)
     */
    public function generateAuthCode(): string
    {
        if (!$this->isConfigured()) {
            throw new Exception('Service NOKASH non configuré. Veuillez configurer NOKASH_I_SPACE_KEY et NOKASH_APP_SPACE_KEY dans le fichier .env');
        }

        $response = Http::asForm()->post($this->baseUrl . '/auth', [
            'i_space_key'   => $this->iSpaceKey,
            'app_space_key' => $this->appSpaceKey,
        ]);

        $data = $response->json();

        if (!isset($data['status']) || $data['status'] !== 'LOGIN_SUCCESS') {
            Log::error('Nokash auth failed', $data);
            throw new Exception('Impossible de générer la clé NOKASH: ' . ($data['message'] ?? 'Erreur inconnue'));
        }

        return $data['data'];
    }

    /**
     * Récupérer le solde total NOKASH
     */
    public function getBalance(): float
    {
        $authCode = $this->generateAuthCode();

        $response = Http::asForm()->withHeaders([
            'auth-code' => $authCode,
        ])->post($this->baseUrl . '/301/get-balance', [
            'i_space_key' => $this->iSpaceKey,
        ]);

        $data = $response->json();

        if ($data['status'] !== 'GET_SUCCESS') {
            throw new Exception('Erreur NoKash Balance: ' . json_encode($data));
        }

        return (float) $data['data']['totalBalance'];
    }

    /**
     * Vérifier le statut d'un paiement (PAYIN)
     */
    public function verifyPayment(string $reference): array
    {
        $authCode = $this->generateAuthCode();

        $response = Http::asForm()->withHeaders([
            'auth-code' => $authCode,
        ])->post($this->baseUrl . '/api-payin-check/412', [
            'i_space_key' => $this->iSpaceKey,
            'reference'   => $reference,
        ]);

        $data = $response->json();

        if (!isset($data['status'])) {
            throw new Exception('Réponse invalide de NoKash: ' . json_encode($data));
        }

        return $data;
    }

    /**
     * Vérifier le statut d'un retrait (PAYOUT)
     */
    public function verifyPayout(string $reference): array
    {
        $authCode = $this->generateAuthCode();

        $response = Http::asForm()->withHeaders([
            'auth-code' => $authCode,
        ])->post($this->baseUrl . '/api-payout-check/413', [
            'i_space_key' => $this->iSpaceKey,
            'reference'   => $reference,
        ]);

        $data = $response->json();

        if (!isset($data['status'])) {
            throw new Exception('Réponse invalide de NoKash: ' . json_encode($data));
        }

        return $data;
    }

    /**
     * Effectuer un payout (MTN / Orange) pour les retraits auteurs
     */
    public function payout(
        float $amount,
        string $phone,
        string $method, // MTN_MOMO | ORANGE_MONEY
        string $callbackUrl = null
    ): array {
        // Vérifier le solde
        $balance = $this->getBalance();

        if ($balance < $amount) {
            throw new Exception('Solde NOKASH insuffisant. Veuillez contacter l\'administrateur.');
        }

        $authCode = $this->generateAuthCode();

        $payload = [
            'i_space_key'   => $this->iSpaceKey,
            'app_space_key' => $this->appSpaceKey,
            'payment_type'  => 'CM_MOBILEMONEY',
            'country'       => 'CM',
            'payment_method'=> $method,
            'order_id'      => uniqid('ocali_withdraw_'),
            'amount'        => (string) $amount,
            'user_data'     => [
                'user_phone' => $phone
            ],
        ];

        if ($callbackUrl) {
            $payload['callback_url'] = $callbackUrl;
        }

        $response = Http::withHeaders([
            'auth-code' => $authCode,
            'Content-Type' => 'application/json'
        ])->post($this->baseUrl . '/api-payout-request/407', $payload);

        $data = $response->json();

        Log::info('Nokash payout response', ['payload' => $payload, 'response' => $data]);

        if ($data['status'] !== 'REQUEST_OK') {
            throw new Exception($data['message'] ?? 'Erreur NOKASH. Veuillez contacter l\'administrateur.');
        }

        return $data;
    }
}
