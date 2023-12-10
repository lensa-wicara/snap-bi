<?php 

namespace LensaWicara\Tests\Feature;

use LensaWicara\SnapBI\Http\SnapClient;
use LensaWicara\SnapBI\Support\Signature;
use PHPUnit\Framework\Attributes\Test;

class SignatureAuthTest extends \LensaWicara\Tests\TestCase
{
    #[Test]
    // can get signature auth
    public function can_get_signature_auth()
    {
        $http = new SnapClient();

        $response = $http->withHeaders([
            'X-TIMESTAMP' => now()->toIso8601String(),
            'X-CLIENT-KEY' => config('snap-bi.providers.aspi.client_id'),
            'Private_Key' => config('snap-bi.providers.aspi.private_key'),
        ])->post('api/v1.0/utilities/signature-auth');

        // assert verify header
        $this->assertEquals(200, $response->status());
        // response body has signature
        $this->assertArrayHasKey('signature', $response->json(), $response->json()['signature']);
    }

    #[Test]
    // can get signature auth with signature class
    public function can_get_signature_auth_with_signature_class()
    {
        $http = new Signature();

        $response = $http->signatureAuth();

        // assert string
        $this->assertIsString($response);
    }
}