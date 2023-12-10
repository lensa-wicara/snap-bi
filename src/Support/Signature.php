<?php 

namespace LensaWicara\SnapBI\Support;

use Illuminate\Http\Client\PendingRequest;
use LensaWicara\SnapBI\Http\SnapClient;

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
}

