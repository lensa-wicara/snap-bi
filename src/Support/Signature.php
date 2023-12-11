<?php

namespace LensaWicara\SnapBI\Support;

use Illuminate\Http\Client\PendingRequest;
use LensaWicara\SnapBI\Http\SnapClient;
use LensaWicara\SnapBI\Signature\AsymmetricPayload;

class Signature
{
    public $endpoint = 'api/v1.0/utilities/signature-auth';

    /**
     * The http client
     *
     * @var PendingRequest|null
     */
    protected $client = null;

    /**
     * Throw error
     *
     * @var bool
     */
    protected $throwError = true;

    public function __construct()
    {
        $this->client = new SnapClient();
    }

    /**
     * generate asymmetric signature SHA256withRSA
     * Private_Key, stringToSign). stringToSign = client_ID + “|” + X-TIMESTAMP
     *
     * @param  string  $privateKey
     * @param  string  $clientKey
     * @return string
     */
    public function asymmetric(AsymmetricPayload $payload)
    {
        $signature = null;

        $privateKey = openssl_get_privatekey($this->getPrivateKey());

        openssl_sign((string) $payload, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        if (! $signature) {
            throw new \Exception('Failed to generate signature.');
        }

        return base64_encode($signature);
    }

    /**
     * verify asymmetric signature SHA256withRSA
     *
     * @return int|false
     *   1 if the signature is correct, 0 if it is incorrect, and -1 on error.
     */
    public function asymmetricVerify(AsymmetricPayload $payload, $signature)
    {
        $asymmetricKey = openssl_pkey_get_public($this->getPublicKey());

        if (! $asymmetricKey) {
            throw new \InvalidArgumentException('Invalid public key.');
        }

        $verify = openssl_verify((string) $payload, base64_decode($signature), $asymmetricKey, OPENSSL_ALGO_SHA256);

        if ($verify === -1) {
            throw new \Exception('Failed to verify signature.');
        }

        return $verify;
    }

    /**
     * Generate signature for accessing ASPI API Services
     *
     * @return string
     */
    public function signatureAuth(array $headers = [])
    {
        $withHeaders = [
            'X-TIMESTAMP' => now()->toIso8601String(),
            'X-CLIENT-KEY' => config('snap-bi.providers.aspi.client_id'),
            'Private_Key' => config('snap-bi.providers.aspi.private_key'),
        ];

        $response = $this->client->withHeaders(array_merge([
            ...$withHeaders,
            ...$headers,
        ]))->post($this->endpoint);

        if ($response->successful()) {
            return $response->json()['signature'];
        }

        return $response->throw();
    }

    /**
     * get private key from storage
     */
    public function getPrivateKey()
    {
        $key = config('snap-bi.providers.aspi.private_key');

        if (! file_exists($key)) {
            throw new \Exception('Private key not found.');
        }

        return file_get_contents($key);
    }

    /**
     * get public key from storage
     */
    public function getPublicKey()
    {
        $key = config('snap-bi.providers.aspi.public_key');

        if (! file_exists($key)) {
            throw new \Exception('Public key not found.');
        }

        return file_get_contents($key);
    }

    /**
     * get aspi private key
     */
    public function getAspiPrivateKey()
    {
        $key = config('snap-bi.providers.aspi.private_key');

        return $key;
    }

    /**
     * get aspi public key
     */
    public function getAspiPublicKey()
    {
        $key = config('snap-bi.providers.aspi.public_key');

        return $key;
    }
}
