<?php

namespace LensaWicara\Tests\Feature;

use Illuminate\Support\Facades\Http;
use LensaWicara\SnapBI\Auth\AccessToken;
use LensaWicara\Tests\TestCase;

class AccessTokenTest extends TestCase
{
    /**
     * set up
     */
    protected function setUp(): void
    {
        parent::setUp();

        $baseUrl = config('snap-bi.providers.aspi.base_url');

        // set config to use test keys
        // fake http client

        Http::fake([
            $baseUrl.'/api/v1.0/utilities/signature-auth' => Http::response([
                'signature' => 'fnjKJJfejlfuhfsnamef',
            ], 200),

            $baseUrl.'/api/v1.0/access-token/b2b' => Http::response([
                'responseCode' => '2007300',
                'responseMessage' => 'Successful',
                'accessToken' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIzMmM5OWZjZi05NTczLTQ1MjQtYWY0OS1kZWI4Y',
                'tokenType' => 'Bearer',
                'expiresIn' => '900',
            ], 200),

            $baseUrl.'/api/v1.0/access-token/b2b2c' => Http::response([
                'responseCode' => '2007400',
                'responseMessage' => 'Successful',
                'accessToken' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIzNGVhMmYxMy05M2ZjLTQyZGUtODV',
                'tokenType' => 'Bearer',
                'accessTokenExpiryTime' => '2023-12-10T20:05:05+07:00',
                'refreshToken' => 'fb902845-727b-4ba1-8bde-245f8dd1c2c9',
                'refreshTokenExpiryTime' => '2023-12-25T19:50:05+07:00',
                'additionalInfo' => [],
            ], 200),
        ]);
    }

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
}
