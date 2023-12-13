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

        $va = new VirtualAccount;
        
        $response = $va->using('inquiry')->withBody($body)->send();

        // $responseData = [
        //     "responseCode" => "2002400",
        //     "responseMessage" => "Successful",
        //     "virtualAccountData" => [
        //         "inquiryStatus" => "00",
        //         "inquiryReason" => [
        //             "english" => "Success",
        //             "indonesia" => "Sukses"
        //         ],
        //         "partnerServiceId" => "88899",
        //         "customerNo" => "12345678901234567890",
        //         "virtualAccountNo" => "08889912345678901234567890",
        //         "virtualAccountName" => "Jokul Doe",
        //         "virtualAccountEmail" => "john@email.com",
        //         "virtualAccountPhone" => "6281828384858",
        //         "inquiryRequestId" => "abcdef-123456-abcdef",
        //         "totalAmount" => [
        //             "value" => "12345678.00",
        //             "currency" => "IDR"
        //         ],
        //         "subCompany" => "abcdefgh1234",
        //         "billDetails" => [
        //             [
        //                 "billCode" => "01",
        //                 "billNo" => "123456789012345678",
        //                 "billName" => "Bill A for Jan",
        //                 "billShortName" => "Bill A",
        //                 "billDescription" => [
        //                     "english" => "Maintenance",
        //                     "indonesia" => "Pemeliharaan"
        //                 ],
        //                 "billSubCompany" => "00001",
        //                 "billAmount" => [
        //                     "value" => "50000",
        //                     "currency" => "IDR"
        //                 ],
        //                 "billAmountLabel" => "Total Tagihan",
        //                 "billAmountValue" => "Rp. 50.000,-",
        //                 "additionalInfo" => []
        //             ]
        //         ],
        //         "freeTexts" => [
        //             [
        //                 "english" => "Free text",
        //                 "indonesia" => "Tulisan bebas"
        //             ]
        //         ],
        //         "virtualAccountTrxType" => "1",
        //         "feeAmount" => [
        //             "value" => "5000",
        //             "currency" => "IDR"
        //         ],
        //         "additionalInfo" => [
        //             "deviceId" => "12345679237",
        //             "channel" => "mobilephone"
        //         ]
        //     ]
        // ];

        $this->assertIsArray($response);
        $this->assertArrayHasKey('responseCode', $response);
        $this->assertArrayHasKey('responseMessage', $response);
        $this->assertArrayHasKey('virtualAccountData', $response);
        $this->assertArrayHasKey('inquiryStatus', $response['virtualAccountData']);
        $this->assertArrayHasKey('inquiryReason', $response['virtualAccountData']);
        $this->assertArrayHasKey('partnerServiceId', $response['virtualAccountData']);
        $this->assertArrayHasKey('customerNo', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountNo', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountName', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountEmail', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountPhone', $response['virtualAccountData']);
        $this->assertArrayHasKey('inquiryRequestId', $response['virtualAccountData']);
        $this->assertArrayHasKey('totalAmount', $response['virtualAccountData']);
        $this->assertArrayHasKey('subCompany', $response['virtualAccountData']);
        $this->assertArrayHasKey('billDetails', $response['virtualAccountData']);
        $this->assertArrayHasKey('freeTexts', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountTrxType', $response['virtualAccountData']);
        $this->assertArrayHasKey('feeAmount', $response['virtualAccountData']);
        $this->assertArrayHasKey('additionalInfo', $response['virtualAccountData']);

        $this->assertTrue(true);
    }

    #[Test]
    // can create VA
    public function test_can_create_va()
    {
        $body = [
            "partnerServiceId" => "088899",
            "customerNo" => "12345678901234567890",
            "virtualAccountNo" => "08889912345678901234567890",
            "virtualAccountName" => "Jokul Doe",
            "virtualAccountEmail" => "jokul@email.com",
            "virtualAccountPhone" => "6281828384858",
            "trxId" => "abcdefgh1234",
            "totalAmount" => [
                "value" => "12345678.00",
                "currency" => "IDR"
            ],
            "billDetails" => [
                [
                    "billCode" => "01",
                    "billNo" => "123456789012345678",
                    "billName" => "Bill A for Jan",
                    "billShortName" => "Bill A",
                    "billDescription" => [
                        "english" => "Maintenance",
                        "indonesia" => "Pemeliharaan"
                    ],
                    "billSubCompany" => "00001",
                    "billAmount" => [
                        "value" => "12345678.00",
                        "currency" => "IDR"
                    ],
                    "additionalInfo" => []
                ]
            ],
            "freeTexts" => [
                [
                    "english" => "Free text",
                    "indonesia" => "Tulisan bebas"
                ]
            ],
            "virtualAccountTrxType" => "1",
            "feeAmount" => [
                "value" => "12345678.00",
                "currency" => "IDR"
            ],
            "expiredDate" => "2020-12-31T23:59:59-07:00",
            "additionalInfo" => [
                "deviceId" => "12345679237",
                "channel" => "mobilephone"
            ]
        ];


        $va = new VirtualAccount;

        $response = $va->using('create-va')->withBody($body)->send();

        // $responseData = [
        //     "responseCode" => "2002700",
        //     "responseMessage" => "Successful",
        //     "virtualAccountData" => [
        //         "partnerServiceId" => "088899",
        //         "customerNo" => "12345678901234567890",
        //         "virtualAccountNo" => "08889912345678901234567890",
        //         "virtualAccountName" => "Jokul Doe",
        //         "virtualAccountEmail" => "jokul@email.com",
        //         "virtualAccountPhone" => "6281828384858",
        //         "trxId" => "abcdefgh1234",
        //         "totalAmount" => [
        //             "value" => "12345678.00",
        //             "currency" => "IDR"
        //         ],
        //         "billDetails" => [
        //             [
        //                 "billCode" => "01",
        //                 "billNo" => "123456789012345678",
        //                 "billName" => "Bill A for Jan",
        //                 "billShortName" => "Bill A",
        //                 "billDescription" => [
        //                     "english" => "Maintenance",
        //                     "indonesia" => "Pemeliharaan"
        //                 ],
        //                 "billSubCompany" => "00001",
        //                 "billAmount" => [
        //                     "value" => "12345678.00",
        //                     "currency" => "IDR"
        //                 ],
        //                 "additionalInfo" => []
        //             ]
        //         ],
        //         "freeTexts" => [
        //             [
        //                 "english" => "Free text",
        //                 "indonesia" => "Tulisan bebas"
        //             ]
        //         ],
        //         "virtualAccountTrxType" => "1",
        //         "feeAmount" => [
        //             "value" => "12345678.00",
        //             "currency" => "IDR"
        //         ],
        //         "expiredDate" => "2024-01-13T12:04:20+07:00",
        //         "additionalInfo" => [
        //             "deviceId" => "12345679237",
        //             "channel" => "mobilephone"
        //         ]
        //     ]
        // ];

        $this->assertIsArray($response);
        $this->assertArrayHasKey('responseCode', $response);
        $this->assertArrayHasKey('responseMessage', $response);
        $this->assertArrayHasKey('virtualAccountData', $response);
        $this->assertArrayHasKey('partnerServiceId', $response['virtualAccountData']);
        $this->assertArrayHasKey('customerNo', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountNo', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountName', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountEmail', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountPhone', $response['virtualAccountData']);
        $this->assertArrayHasKey('trxId', $response['virtualAccountData']);
        $this->assertArrayHasKey('totalAmount', $response['virtualAccountData']);
        $this->assertArrayHasKey('billDetails', $response['virtualAccountData']);
        $this->assertArrayHasKey('freeTexts', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountTrxType', $response['virtualAccountData']);
        $this->assertArrayHasKey('feeAmount', $response['virtualAccountData']);
        $this->assertArrayHasKey('expiredDate', $response['virtualAccountData']);
        $this->assertArrayHasKey('additionalInfo', $response['virtualAccountData']);
    }

    #[Test]
    // can get virtual account inquiry va
    public function test_can_get_virtual_account_inquiry_va()
    {
        $body = [
            "partnerServiceId" => "088899",
            "customerNo" => "12345678901234567890",
            "virtualAccountNo" => "08889912345678901234567890",
            "trxId" => "abcdefgh1234"
        ];

        $va = new VirtualAccount;

        $response = $va->using('inquiry-va')->withBody($body)->send();

        // $responseData = [
        //     "responseCode" => "2003000",
        //     "responseMessage" => "Successful",
        //     "virtualAccountData" => [
        //         "partnerServiceId" => "088899",
        //         "customerNo" => "12345678901234567890",
        //         "virtualAccountNo" => "08889912345678901234567890",
        //         "virtualAccountName" => "Jokul Doe",
        //         "virtualAccountEmail" => "jokul@email.com",
        //         "virtualAccountPhone" => "6281828384858",
        //         "trxId" => "abcdefgh1234",
        //         "totalAmount" => [
        //             "value" => "88000",
        //             "currency" => "IDR"
        //         ],
        //         "billDetails" => [
        //             [
        //                 "billCode" => "01",
        //                 "billNo" => "123456789012345678",
        //                 "billName" => "Bill A for Jan",
        //                 "billShortName" => "Bill A",
        //                 "billDescription" => [
        //                     "english" => "Maintenance",
        //                     "indonesia" => "Pemeliharaan"
        //                 ],
        //                 "billSubCompany" => "00001",
        //                 "billAmount" => [
        //                     "value" => "50000",
        //                     "currency" => "IDR"
        //                 ],
        //                 "additionalInfo" => []
        //             ]
        //         ],
        //         "freeTexts" => [
        //             [
        //                 "english" => "Free text",
        //                 "indonesia" => "Tulisan bebas"
        //             ]
        //         ],
        //         "virtualAccountTrxType" => "1",
        //         "feeAmount" => [
        //             "value" => "5000",
        //             "currency" => "IDR"
        //         ],
        //         "expiredDate" => "2024-01-13T12:11:28+07:00",
        //         "lastUpdateDate" => "2023-11-13T12:11:28+07:00",
        //         "paymentDate" => "2023-12-13T12:11:28+07:00",
        //         "additionalInfo" => []
        //     ]
        // ];

        $this->assertIsArray($response);
        $this->assertArrayHasKey('responseCode', $response);
        $this->assertArrayHasKey('responseMessage', $response);
        $this->assertArrayHasKey('virtualAccountData', $response);
        $this->assertArrayHasKey('partnerServiceId', $response['virtualAccountData']);
        $this->assertArrayHasKey('customerNo', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountNo', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountName', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountEmail', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountPhone', $response['virtualAccountData']);
        $this->assertArrayHasKey('trxId', $response['virtualAccountData']);
        $this->assertArrayHasKey('totalAmount', $response['virtualAccountData']);
        $this->assertArrayHasKey('billDetails', $response['virtualAccountData']);
        $this->assertArrayHasKey('freeTexts', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountTrxType', $response['virtualAccountData']);
        $this->assertArrayHasKey('feeAmount', $response['virtualAccountData']);
        $this->assertArrayHasKey('expiredDate', $response['virtualAccountData']);
        $this->assertArrayHasKey('lastUpdateDate', $response['virtualAccountData']);
        $this->assertArrayHasKey('paymentDate', $response['virtualAccountData']);
        $this->assertArrayHasKey('additionalInfo', $response['virtualAccountData']);
    }

    #[Test]
    // can make payment va
    public function test_can_make_payment_va()
    {
        $body = [
            "partnerServiceId" => "088899",
            "customerNo" => "12345678901234567890",
            "virtualAccountNo" => "08889912345678901234567890",
            "virtualAccountName" => "Jokul Doe",
            "virtualAccountEmail" => "jokul@email.com",
            "virtualAccountPhone" => "6281828384858",
            "trxId" => "abcdefgh1234",
            "paymentRequestId" => "abcdef-123456-abcdef",
            "channelCode" => 6011,
            "hashedSourceAccountNo" => "abcdefghijklmnopqrstuvwxyz123456",
            "sourceBankCode" => "008",
            "paidAmount" => [
                "value" => "12345678.00",
                "currency" => "IDR"
            ],
            "cumulativePaymentAmount" => [
                "value" => "12345678.00",
                "currency" => "IDR"
            ],
            "paidBills" => "95000",
            "totalAmount" => [
                "value" => "12345678.00",
                "currency" => "IDR"
            ],
            "trxDateTime" => "20201231T235959Z",
            "referenceNo" => "123456789012345",
            "journalNum" => "123456",
            "paymentType" => 1,
            "flagAdvise" => "Y",
            "subCompany" => "12345",
            "billDetails" => [
                [
                    "billCode" => "01",
                    "billNo" => "123456789012345678",
                    "billName" => "Bill A for Jan",
                    "billShortName" => "Bill A",
                    "billDescription" => [
                        "english" => "Maintenance",
                        "indonesia" => "Pemeliharaan"
                    ],
                    "billSubCompany" => "00001",
                    "billAmount" => [
                        "value" => "12345678.00",
                        "currency" => "IDR"
                    ],
                    "additionalInfo" => [],
                    "billReferenceNo" => "123456789012345"
                ]
            ],
            "freeTexts" => [
                [
                    "english" => "Free text",
                    "indonesia" => "Tulisan bebas"
                ]
            ],
            "additionalInfo" => []
        ];

        $va = new VirtualAccount;

        $response = $va->using('payment')->withBody($body)->send();

        // $responseData = [
        //     "responseCode" => "2002500",
        //     "responseMessage" => "Successful",
        //     "virtualAccountData" => [
        //         "paymentFlagReason" => [
        //             "english" => "Success",
        //             "indonesia" => "Sukses"
        //         ],
        //         "partnerServiceId" => "088899",
        //         "customerNo" => "12345678901234567890",
        //         "virtualAccountNo" => "08889912345678901234567890",
        //         "virtualAccountName" => "Jokul Doe",
        //         "virtualAccountEmail" => "jokul@email.com",
        //         "virtualAccountPhone" => "6281828384858",
        //         "trxId" => "abcdefgh1234",
        //         "paymentRequestId" => "abcdef-123456-abcdef",
        //         "paidAmount" => [
        //             "value" => "12345678.00",
        //             "currency" => "IDR"
        //         ],
        //         "paidBills" => "95000",
        //         "totalAmount" => [
        //             "value" => "12345678.00",
        //             "currency" => "IDR"
        //         ],
        //         "trxDateTime" => "20201231T235959Z",
        //         "referenceNo" => "123456789012345",
        //         "journalNum" => "123456",
        //         "paymentType" => "1",
        //         "flagAdvise" => "Y",
        //         "paymentFlagStatus" => "00",
        //         "billDetails" => [
        //             [
        //                 "billerReferenceId" => "4314313572",
        //                 "billCode" => "01",
        //                 "billNo" => "123456789012345678",
        //                 "billName" => "Bill A for Jan",
        //                 "billShortName" => "Bill A",
        //                 "billDescription" => [
        //                     "english" => "Maintenance",
        //                     "indonesia" => "Pemeliharaan"
        //                 ],
        //                 "billSubCompany" => "00001",
        //                 "billAmount" => [
        //                     "value" => "12345678.00",
        //                     "currency" => "IDR"
        //                 ],
        //                 "additionalInfo" => [],
        //                 "status" => "00",
        //                 "reason" => [
        //                     "english" => "Success",
        //                     "indonesia" => "Sukses"
        //                 ]
        //             ]
        //         ],
        //         "freeTexts" => [
        //             [
        //                 "english" => "Free text",
        //                 "indonesia" => "Tulisan bebas"
        //             ]
        //         ]
        //     ],
        //     "additionalInfo" => []
        // ];

        $this->assertIsArray($response);
        $this->assertArrayHasKey('responseCode', $response);
        $this->assertArrayHasKey('responseMessage', $response);
        $this->assertArrayHasKey('virtualAccountData', $response);
        $this->assertArrayHasKey('paymentFlagReason', $response['virtualAccountData']);
        $this->assertArrayHasKey('partnerServiceId', $response['virtualAccountData']);
        $this->assertArrayHasKey('customerNo', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountNo', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountName', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountEmail', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountPhone', $response['virtualAccountData']);
        $this->assertArrayHasKey('trxId', $response['virtualAccountData']);
        $this->assertArrayHasKey('paymentRequestId', $response['virtualAccountData']);
        $this->assertArrayHasKey('paidAmount', $response['virtualAccountData']);
        $this->assertArrayHasKey('paidBills', $response['virtualAccountData']);
        $this->assertArrayHasKey('totalAmount', $response['virtualAccountData']);
        $this->assertArrayHasKey('trxDateTime', $response['virtualAccountData']);
        $this->assertArrayHasKey('referenceNo', $response['virtualAccountData']);
        $this->assertArrayHasKey('journalNum', $response['virtualAccountData']);
        $this->assertArrayHasKey('paymentType', $response['virtualAccountData']);
        $this->assertArrayHasKey('flagAdvise', $response['virtualAccountData']);
        $this->assertArrayHasKey('paymentFlagStatus', $response['virtualAccountData']);
        $this->assertArrayHasKey('billDetails', $response['virtualAccountData']);
        $this->assertArrayHasKey('freeTexts', $response['virtualAccountData']);
        $this->assertArrayHasKey('additionalInfo', $response);
    }
}