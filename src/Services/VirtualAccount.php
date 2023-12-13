<?php 

namespace LensaWicara\SnapBI\Services;

use LensaWicara\SnapBI\Auth\AccessableToken;
use LensaWicara\SnapBI\Auth\AccessToken;
use LensaWicara\SnapBI\Http\SnapClient;
use LensaWicara\SnapBI\Signature\SymmetricPayload;
use LensaWicara\SnapBI\Support\Header;
use LensaWicara\SnapBI\Support\Signature;
use LensaWicara\SnapBI\Support\Timestamp;

class VirtualAccount
{
    public string $endpoint = '/api/v1.0/transfer-va/inquiry';

    protected ?SnapClient $client = null;

    // signature
    protected ?Signature $signature = null;

    // body
    protected array $body = [];

    // timestamp
    protected Timestamp $timestamp;

    public function __construct()
    {
        $this->timestamp = new Timestamp();
        $this->client = new SnapClient();
        $this->signature = new Signature();
    }

    // withBody
    public function withBody(array $body)
    {
        $this->body = $body;
        
        return $this;
    }

    /**
     * create virtual account
     * 
     */
    public function inquiry()
    {
        $response = $this->client->withHeaders($this->headers())->post($this->endpoint, $this->body);

        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }

    /**
     * get headers
     */
    protected function headers()
    {
        return Header::make([
            'x-client-key' => config('snap-bi.providers.aspi.client_id'),
            'x-timestamp' => (string) $this->timestamp,
            'authorization' => 'Bearer '. static::authorization(),
            'authorization-customer' => 'Bearer '. static::authorizationCustomer(),
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
    protected static function authorization()
    {
        return (string) AccessableToken::get('test');
    }

    /**
     * authorization customer
     */
    protected static function authorizationCustomer()
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
    protected function signatureService()
    {
        return $this->signature->signatureService(
            'POST',
            $this->endpoint,
            $this->body,
            static::authorization(),
            [
                'X-TIMESTAMP' => (string) $this->timestamp,
            ]
        );
    }
}