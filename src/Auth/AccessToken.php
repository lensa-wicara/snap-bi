<?php

namespace LensaWicara\SnapBI\Auth;

use LensaWicara\SnapBI\Http\SnapClient;
use LensaWicara\SnapBI\Support\Header;
use LensaWicara\SnapBI\Support\Signature;

class AccessToken
{
    public $endpoint = 'api/v1.0/access-token/b2b';

    protected ?SnapClient $client = null;

    // signature auth
    protected ?Signature $signature = null;

    // Header	Content-Type	Mandatory	String	String represents indicate the media type of the resource (e.g. application/json, application/pdf)
    // X-TIMESTAMP	Mandatory	String	Client's current local time in yyyy-MM- ddTHH:mm:ssTZD format
    // X-CLIENT- KEY	Mandatory	String	Client’s client_id (PJP Name) (given at completion registration process )
    // X-SIGNATURE	Mandatory	String	Non-Repudiation & Integrity checking
    // X-Signature : dengan algoritma asymmetric signature SHA256withRSA
    // (Private_Key, stringToSign). stringToSign = client_ID + “|” + X-TIMESTAMP
    // Body	grantType	Mandatory	String	“client_credentials” : The client can request an access token using only its client credentials (or other supported means of authentication) when the client is requesting access to the protected resources under its control (OAuth 2.0: RFC 6749 & 6750)
    // additionalInfo	Optional	Object	Additional Information

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
