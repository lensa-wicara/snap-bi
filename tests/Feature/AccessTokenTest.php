<?php

namespace LensaWicara\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use LensaWicara\SnapBI\Auth\AccessableToken;
use LensaWicara\SnapBI\Auth\AccessToken;
use LensaWicara\SnapBI\Support\Signature;
use LensaWicara\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AccessTokenTest extends TestCase
{
    /**
     * set up
     */
    protected function setUp(): void
    {
        parent::setUp();

        // set config to use test keys
        // fake http client

        // Http::fake([
        //     $baseUrl.'/api/v1.0/utilities/signature-auth' => Http::response([
        //         'signature' => 'fnjKJJfejlfuhfsnamef',
        //     ], 200),

        //     $baseUrl.'/api/v1.0/access-token/b2b' => Http::response([
        //         'responseCode' => '2007300',
        //         'responseMessage' => 'Successful',
        //         'accessToken' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIzMmM5OWZjZi05NTczLTQ1MjQtYWY0OS1kZWI4Y',
        //         'tokenType' => 'Bearer',
        //         'expiresIn' => '900',
        //     ], 200),

        //     $baseUrl.'/api/v1.0/access-token/b2b2c' => Http::response([
        //         'responseCode' => '2007400',
        //         'responseMessage' => 'Successful',
        //         'accessToken' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIzNGVhMmYxMy05M2ZjLTQyZGUtODV',
        //         'tokenType' => 'Bearer',
        //         'accessTokenExpiryTime' => '2023-12-10T20:05:05+07:00',
        //         'refreshToken' => 'fb902845-727b-4ba1-8bde-245f8dd1c2c9',
        //         'refreshTokenExpiryTime' => '2023-12-25T19:50:05+07:00',
        //         'additionalInfo' => [],
        //     ], 200),
        // ]);
    }

    #[Test]
    // can get access token
    public function test_can_get_access_token()
    {
        $auth = (new AccessToken)->getAccessToken();

        $this->assertArrayHasKey('responseCode', $auth);
        $this->assertArrayHasKey('responseMessage', $auth);
        $this->assertArrayHasKey('accessToken', $auth);
        $this->assertArrayHasKey('tokenType', $auth);
        $this->assertArrayHasKey('expiresIn', $auth);
    }

    #[Test]
    // can get customer access token
    public function test_can_get_customer_access_token()
    {
        $auth = (new AccessToken)->getCustomerAccessToken([
            'authCode' => 'a6975f82-d00a-4ddc-9633-087fefb6275e', // str()->uuid(),
            'refreshToken' => '83a58570-6795-11ec-90d6-0242ac120003', // str()->uuid(),
            'additionalInfo' => [],
        ]);

        $this->assertArrayHasKey('responseCode', $auth);
        $this->assertArrayHasKey('responseMessage', $auth);
        $this->assertArrayHasKey('accessToken', $auth);
        $this->assertArrayHasKey('tokenType', $auth);
        $this->assertArrayHasKey('accessTokenExpiryTime', $auth);
        $this->assertArrayHasKey('refreshToken', $auth);
        $this->assertArrayHasKey('refreshTokenExpiryTime', $auth);
        $this->assertArrayHasKey('additionalInfo', $auth);
    }

    #[Test]
    // can get access token from cache
    public function test_can_get_access_token_from_cache()
    {
        $accessToken = (string) AccessableToken::get('test');

        $this->assertIsString($accessToken);
    }

    #[Test]
    // can generate signature auth
    public function can_generate_signature_auth()
    {
        $signature = new Signature();

        $response = $signature->signatureAuth();

        $this->assertIsString($response);
    }

    #[Test]
    // can generate signature service
    public function can_generate_signature_service()
    {
        $signature = new Signature();

        $response = $signature->signatureService(
            'POST',
            'https://api.snapbi.com/v1/endpoint',
            [
                'accountNo' => '1234567890',
                'cliendId' => '1234567890',
                'clientName' => 'John Doe',
                'reqMsgId' => '1234567890',
            ],
            (string) AccessableToken::get('test')
        );

        $this->assertIsString($response);
    }
}
