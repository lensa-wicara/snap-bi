<?php 

namespace LensaWicara\Tests\Feature;

use LensaWicara\SnapBI\Auth\AccessToken;
use LensaWicara\Tests\TestCase;

class AccessTokenTest extends TestCase
{
    // can get access token
    public function test_can_get_access_token()
    {
        $auth = (new AccessToken)->getAccessToken();

        // dd($auth);

        // [
        //   "responseCode" => "2007300"
        //   "responseMessage" => "Successful"
        //   "accessToken" => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIzMmM5OWZjZi05NTczLTQ1MjQtYWY0OS1kZWI4YTM1N2NiNTciLCJjbGllbnRJZCI6IjQ0YjE3MmU4NWI4NjRiNTk4YmQ3YjhhNGQwNWJkYTI4IiwibmJmIjoxNzAyMjA0MzE4LCJleHAiOjE3MDIyMDUyMTgsImlhdCI6MTcwMjIwNDMxOH0.ORotobTfSmRQZeeCrcFmpqosBDojS1WZ2f7gm_RP3N8"
        //   "tokenType" => "Bearer"
        //   "expiresIn" => "900"
        // ]
        $this->assertArrayHasKey('responseCode', $auth);
        $this->assertArrayHasKey('responseMessage', $auth);
        $this->assertArrayHasKey('accessToken', $auth);
        $this->assertArrayHasKey('tokenType', $auth);
        $this->assertArrayHasKey('expiresIn', $auth);
    }
}