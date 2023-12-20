<?php

namespace LensaWicara\Tests\Feature;

use LensaWicara\SnapBI\Providers\Snap;

class HttpTest extends \LensaWicara\Tests\TestCase
{
    // http test
    public function test_http()
    {
        $this->assertTrue(true);
    }

    // can create snap client
    public function test_can_create_snap_client()
    {
        $body = [
            'partnerServiceId' => '088899',
            'customerNo' => '12345678901234567890',
            'virtualAccountNo' => '08889912345678901234567890',
            'virtualAccountName' => 'Jokul Doe',
            'virtualAccountEmail' => 'jokul@email.com',
            'virtualAccountPhone' => '6281828384858',
            'trxId' => 'abcdefgh1234',
            'totalAmount' => [
                'value' => '12345678.00',
                'currency' => 'IDR',
            ],
            'billDetails' => [
                [
                    'billCode' => '01',
                    'billNo' => '123456789012345678',
                    'billName' => 'Bill A for Jan',
                    'billShortName' => 'Bill A',
                    'billDescription' => [
                        'english' => 'Maintenance',
                        'indonesia' => 'Pemeliharaan',
                    ],
                    'billSubCompany' => '00001',
                    'billAmount' => [
                        'value' => '12345678.00',
                        'currency' => 'IDR',
                    ],
                    'additionalInfo' => [],
                ],
            ],
            'freeTexts' => [
                [
                    'english' => 'Free text',
                    'indonesia' => 'Tulisan bebas',
                ],
            ],
            'virtualAccountTrxType' => '1',
            'feeAmount' => [
                'value' => '12345678.00',
                'currency' => 'IDR',
            ],
            'expiredDate' => '2020-12-31T23:59:59-07:00',
            'additionalInfo' => [
                'deviceId' => '12345679237',
                'channel' => 'mobilephone',
            ],
        ];

        $snap = Snap::virtualAccount()
            ->using('create-va')
            ->withBody($body)
            ->send();

        // responseCode must be 2002700
        $this->assertEquals($snap['responseCode'], '2002700');
        // responseMessage must be Successful
        $this->assertEquals($snap['responseMessage'], 'Successful');
        // must have virtualAccountData
        $this->assertArrayHasKey('virtualAccountData', $snap);
    }
}
