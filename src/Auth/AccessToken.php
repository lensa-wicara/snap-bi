<?php

namespace LensaWicara\SnapBI\Auth;

use LensaWicara\SnapBI\Http\SnapClient;
use LensaWicara\SnapBI\Support\Header;
use LensaWicara\SnapBI\Support\Signature;
use LensaWicara\SnapBI\Auth\AccessableToken as Token;

class AccessToken
{
    public $endpoint = 'api/v1.0/access-token/b2b';

    public $customerEndpoint = 'api/v1.0/access-token/b2b2c';

    protected ?SnapClient $client = null;

    protected ?Signature $signature = null;

    public function __construct()
    {
        $this->client = new SnapClient();
        $this->signature = new Signature();
    }

    /**
     * Get access token
     *
     * @return mixed|\Illuminate\Http\Client\RequestException
     * [
     *   "responseCode" => "2007300"
     *   "responseMessage" => "Successful"
     *   "accessToken" => "eyJhbGciOiJIUzI1NiIsInR5..."
     *   "tokenType" => "Bearer"
     *   "expiresIn" => "900"
     *   ]
     */
    public function getAccessToken()
    {
        $response = $this->client->withHeaders($this->headers())->post($this->endpoint, [
            'grantType' => 'client_credentials',
        ]);

        if ($response->successful()) {
            Token::put('test', $response->json());
            return $response->json();
        }

        return $response->throw();
    }

    /**
     * get customer access token B2B2C
     *
     * @param  array  $body ['authCode', 'refreshToken', 'additionalInfo']
     * @return mixed|\Illuminate\Http\Client\RequestException
     */
    public function getCustomerAccessToken(array $body)
    {
        // validate body must have authCode, refreshToken, additionalInfo
        if (! isset($body['authCode']) || ! isset($body['refreshToken']) || ! isset($body['additionalInfo'])) {
            throw new \Exception('Body must have authCode, refreshToken, additionalInfo');
        }

        $response = $this->client->withHeaders($this->headers())->post($this->customerEndpoint, [
            'grantType' => 'authorization_code',
            'authCode' => $body['authCode'],
            'refreshToken' => $body['refreshToken'],
            'additionalInfo' => $body['additionalInfo'],
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    // headers for access token
    public function headers()
    {
        return Header::make([
            'x-timestamp' => now()->toIso8601String(),
            'x-client-key' => config('snap-bi.providers.aspi.client_id'),
            'x-signature' => $this->signature->signatureAuth(),
        ])->onlyForAccessToken();
    }
}
