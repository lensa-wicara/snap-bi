<?php

namespace LensaWicara\SnapBI\Services;

use LensaWicara\SnapBI\Auth\AccessableToken;
use LensaWicara\SnapBI\Auth\AccessToken;
use LensaWicara\SnapBI\Http\SnapClient;
use LensaWicara\SnapBI\Support\Header;
use LensaWicara\SnapBI\Support\Signature;
use LensaWicara\SnapBI\Support\Timestamp;

class VirtualAccount
{
    // using
    public ?string $using = null;

    // endpoint
    public ?string $endpoint = null;

    // endpoints
    public array $endpoints = [
        'inquiry' => '/api/v1.0/transfer-va/inquiry',
        'inquiry-va' => '/api/v1.0/transfer-va/inquiry-va',
        'create-va' => '/api/v1.0/transfer-va/create-va',
        'update-va' => '/api/v1.0/transfer-va/update-va',
        'delete-va' => '/api/v1.0/transfer-va/delete-va',
        'payment' => '/api/v1.0/transfer-va/payment',
        'status' => '/api/v1.0/transfer-va/status',
        'report' => '/api/v1.0/transfer-va/report',
        'update-status' => '/api/v1.0/transfer-va/update-status',
    ];

    // endpoints method
    public array $endpointsMethod = [
        'inquiry' => 'POST',
        'inquiry-va' => 'POST',
        'create-va' => 'POST',
        'update-va' => 'PUT',
        'delete-va' => 'DELETE',
        'payment' => 'POST',
        'status' => 'POST',
        'report' => 'POST',
        'update-status' => 'PUT',
    ];

    protected ?SnapClient $client = null;

    // signature
    protected ?Signature $signature = null;

    // body
    protected array $body = [];

    // timestamp
    protected Timestamp $timestamp;

    /**
     * Create new instance
     */
    public function __construct()
    {
        $this->timestamp = new Timestamp();
        $this->client = new SnapClient();
        $this->signature = new Signature();
    }

    /**
     * withBody
     */
    public function withBody(array $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * using
     */
    public function using(string $endpoint): self
    {
        $this->using = $endpoint;
        $this->endpoint = $this->endpoints[$endpoint];

        return $this;
    }

    /**
     * send request
     *
     * @return mixed|self
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function send()
    {
        // endpoint must be set
        if (is_null($this->endpoint)) {
            throw new \Exception('Endpoint has not been set. Please use `using` method to set endpoint');
        }

        // method
        $method = strtolower($this->endpointsMethod[$this->using]);

        $response = $this->client->withHeaders($this->headers())
            ->$method($this->endpoint, $this->body);

        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    /**
     * get headers
     */
    protected function headers(): array
    {
        return Header::make([
            'x-client-key' => config('snap-bi.providers.aspi.client_id'),
            // timestamp on the header must be the same as the timestamp on the request signature
            'x-timestamp' => (string) $this->timestamp,
            'authorization' => 'Bearer '.static::authorization(),
            'authorization-customer' => 'Bearer '.static::authorizationCustomer(),
            'x-signature' => $this->signatureService(),
            'x-origin' => request()->getHost(),
            'x-partner-id' => config('snap-bi.providers.aspi.client_id'),
            'x-external-id' => '41807553358950093184162180797837',
            'x-ip-address' => request()->ip(),
            'x-device-id' => '09864ADCASA',
            // 'x-latitude' => '-6.1617169',
            // 'x-longitude' => '106.6643946',
            'channel-id' => '95221',
        ])->toArray();
    }

    /**
     * authorization
     */
    protected static function authorization(): string
    {
        return (string) AccessableToken::get('test');
    }

    /**
     * authorization customer
     */
    protected static function authorizationCustomer(): string
    {
        $auth = (new AccessToken)->getCustomerAccessToken([
            'authCode' => 'a6975f82-d00a-4ddc-9633-087fefb6275e',
            'refreshToken' => '83a58570-6795-11ec-90d6-0242ac120003',
            'additionalInfo' => [],
        ]);

        return $auth['accessToken'];
    }

    /**
     * get signature service
     */
    protected function signatureService(): string
    {
        return $this->signature->signatureService(
            'POST',
            $this->endpoint,
            $this->body,
            static::authorization(),
            [
                // timestamp on the header must be the same as the timestamp on the request signature
                'X-TIMESTAMP' => (string) $this->timestamp,
            ]
        );
    }
}
