<?php 

namespace LensaWicara\SnapBI\Tests\Feature;

use Illuminate\Support\Facades\Cache;
use LensaWicara\SnapBI\Services\VirtualAccount;
use LensaWicara\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class VirtualAccountTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    #[Test]
    // can get virtual account inquiry
    public function test_can_get_virtual_account_inquiry()
    {
        $va = new VirtualAccount;

        $body = [
            "partnerServiceId" => "88899",
            "customerNo" => "12345678901234567890",
            "virtualAccountNo" => "08889912345678901234567890",
            "txnDateInit" => "20201231T235959Z",
            "channelCode" => 6011,
            "language" => "ID",
            "amount" => [
                "value" => "12345678.00",
                "currency" => "IDR"
            ],
            "hashedSourceAccountNo" => "abcdefghijklmnopqrstuvwxyz123456",
            "sourceBankCode" => "008",
            "passApp" => "abcdefghijklmnopqrstuvwxyz",
            "inquiryRequestId" => "abcdef-123456-abcdef",
            "additionalInfo" => [
                "deviceId" => "12345679237",
                "channel" => "mobilephone"
            ]
        ];
        
        $va->withBody($body)->inquiry();

        $this->assertTrue(true);
    }
}