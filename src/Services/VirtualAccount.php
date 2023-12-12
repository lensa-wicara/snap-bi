<?php 

namespace LensaWicara\SnapBI\Services;

use LensaWicara\SnapBI\Http\SnapClient;
use LensaWicara\SnapBI\Support\Header;
use LensaWicara\SnapBI\Support\Signature;

class VirtualAccount
{
    public string $endpoint = '/v1.0/transfer-va/inquiry';

    protected ?SnapClient $client = null;

    // signature
    protected ?Signature $signature = null;

    public function __construct()
    {
        $this->client = new SnapClient();
        $this->signature = new Signature();
    }

    /**
     * create virtual account
     * 
     */
    public function inquiry(array $body)
    {
        // 
    }

    /**
     * get headers
     */
    protected function headers()
    {
        return Header::make([
            'x-client-key' => config('snap-bi.providers.aspi.client_id'),
            'x-timestamp' => now()->toIso8601String(),
            'signature' => $this->signature->signatureAuth(),
        ]);
    }

}