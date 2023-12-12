<?php

namespace LensaWicara\SnapBI\Support;

use Illuminate\Http\Client\PendingRequest;
use LensaWicara\SnapBI\Http\SnapClient;
use LensaWicara\SnapBI\Signature\AsymmetricPayload;
use LensaWicara\SnapBI\Signature\SymmetricPayload;

class Signature
{
    /**
     * The endpoint url for signature auth
     *
     * @var string
     */
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

    /**
     * Create new instance
     */
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
     * generate symmetric signature 
     * HMAC_SHA512 (clientSecret, stringToSign) dengan formula stringToSign = HTTPMethod +”:“+ EndpointUrl +":"+ AccessToken +":“+ Lowercase(HexEncode(SHA-256(minify(RequestBody))))+ ":“ + TimeStamp
     * 
     * @param  SymmetricPayload  $payload
     * @param  string  $clientSecret
     * @return string
     */
    public function symmetric(SymmetricPayload $payload, $clientSecret)
    {
        $stringToSign = (string) $payload;

        $signature = hash_hmac('sha512', $stringToSign, $clientSecret, true);

        if (! $signature) {
            throw new \Exception('Failed to generate signature.');
        }

        return base64_encode($signature);
    }

    /**
     * verify symmetric signature
     *
     * @param  SymmetricPayload  $payload
     * @param  string  $clientSecret
     * @param  string  $signature
     * @return bool
     */
    public function symmetricVerify(SymmetricPayload $payload, $clientSecret, $signature)
    {
        $verify = hash_equals($signature, static::symmetric($payload, $clientSecret));

        if (! $verify) {
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
     * make asymmetric and symmetric signature to be called statically
     */
    public static function __callStatic($method, $args)
    {
        // only allow asymmetric and symmetric method
        // and the verify method
        if (! in_array($method, ['asymmetric', 'symmetric', 'asymmetricVerify', 'symmetricVerify'])) {
            throw new \Exception('Method not found.');
        }

        return (new static)->{$method}(...$args);
    }
}
