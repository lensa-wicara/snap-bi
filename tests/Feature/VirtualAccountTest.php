<?php

namespace LensaWicara\SnapBI\Tests\Feature;

use LensaWicara\SnapBI\Providers\Snap;
use LensaWicara\SnapBI\Services\Payload\Amount;
use LensaWicara\SnapBI\Services\Payload\VirtualAccount\CreateVAPayload;
use LensaWicara\SnapBI\Services\Payload\VirtualAccount\InquiryPayload;
use LensaWicara\SnapBI\Services\Payload\VirtualAccount\InquiryVAPayload;
use LensaWicara\SnapBI\Services\Payload\VirtualAccount\PaymentPayload;
use LensaWicara\SnapBI\Services\Payload\VirtualAccount\ReportPayload;
use LensaWicara\SnapBI\Services\Payload\VirtualAccount\StatusPayload;
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
        $body = new InquiryPayload(
            partnerServiceId: '88899',
            customerNo: '12345678901234567890',
            virtualAccountNo: '08889912345678901234567890',
            txnDateInit: '20201231T235959Z',
            channelCode: 6011,
            language: 'ID',
            amount: new Amount(
                amount: '12345678.00',
                currency: 'IDR',
            ),
            hashedSourceAccountNo: 'abcdefghijklmnopqrstuvwxyz123456',
            sourceBankCode: '008',
            passApp: 'abcdefghijklmnopqrstuvwxyz',
            inquiryRequestId: 'abcdef-123456-abcdef',
            additionalInfo: []
        );

        $response = Snap::virtualAccount()->using('inquiry')->withBody($body)->send();

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
    }

    #[Test]
    // can create VA
    public function test_can_create_va()
    {
        // use payload
        $body = new CreateVAPayload(
            partnerServiceId: '088899',
            customerNo: '12345678901234567890',
            virtualAccountNo: '08889912345678901234567890',
            virtualAccountName: 'Jokul Doe',
            virtualAccountEmail: 'jokul@email.com',
            virtualAccountPhone: '6281828384858',
            trxId: 'abcdefgh1234',
            totalAmount: new Amount(
                amount: '12345678.00',
                currency: 'IDR',
            ),
            billDetails: [
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
                    'billAmount' => (new Amount(
                        amount: '12345678.00',
                        currency: 'IDR',
                    ))->toArray(),
                    // 'billAmount' => [
                    //     'value' => '12345678.00',
                    //     'currency' => 'IDR',
                    // ],
                    'additionalInfo' => [],
                ],
            ],
            freeTexts: [
                [
                    'english' => 'Free text',
                    'indonesia' => 'Tulisan bebas',
                ],
            ],
            virtualAccountTrxType: '1',
            feeAmount: new Amount(
                amount: '12345678.00',
                currency: 'IDR',
            ),
            expiredDate: '2020-12-31T23:59:59-07:00',
            additionalInfo: [

            ],
        );

        $response = Snap::virtualAccount()->using('create-va')->withBody($body)->send();

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
        $body = new InquiryVAPayload(
            partnerServiceId: '088899',
            customerNo: '12345678901234567890',
            virtualAccountNo: '08889912345678901234567890',
            trxId: 'abcdefgh1234',
        );

        $response = Snap::virtualAccount()->using('inquiry-va')->withBody($body)->send();

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
        $body = new PaymentPayload(
            partnerServiceId: '088899',
            customerNo: '12345678901234567890',
            virtualAccountNo: '08889912345678901234567890',
            virtualAccountName: 'Jokul Doe',
            virtualAccountEmail: 'jokul@email.com',
            virtualAccountPhone: '6281828384858',
            trxId: 'abcdefgh1234',
            paymentRequestId: 'abcdef-123456-abcdef',
            channelCode: 6011,
            hashedSourceAccountNo: 'abcdefghijklmnopqrstuvwxyz123456',
            sourceBankCode: '008',
            paidAmount: new Amount(
                amount: '12345678.00',
                currency: 'IDR',
            ),
            cumulativePaymentAmount: new Amount(
                amount: '12345678.00',
                currency: 'IDR',
            ),
            paidBills: '95000',
            totalAmount: new Amount(
                amount: '12345678.00',
                currency: 'IDR',
            ),
            trxDateTime: '20201231T235959Z',
            referenceNo: '123456789012345',
            journalNum: '123456',
            paymentType: 1,
            flagAdvise: 'Y',
            subCompany: '12345',
            billDetails: [
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
                    'billAmount' => (new Amount(
                        amount: '12345678.00',
                        currency: 'IDR',
                    ))->toArray(),
                    // 'billAmount' => [
                    //     'value' => '12345678.00',
                    //     'currency' => 'IDR',
                    // ],
                    'additionalInfo' => [],
                    'billReferenceNo' => '123456789012345',
                ],
            ],
            freeTexts: [
                [
                    'english' => 'Free text',
                    'indonesia' => 'Tulisan bebas',
                ],
            ],
            additionalInfo: [],
        );

        $response = Snap::virtualAccount()->using('payment')->withBody($body)->send();

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

    #[Test]
    // can get virtual account report
    public function test_can_get_virtual_account_report()
    {
        $body = new ReportPayload(
            partnerServiceId: '088899',
            startDate: '2020-12-31',
            startTime: '14:56:11+07:00',
            endDate: '2021-12-31',
            endTime: '14:56:11+07:00',
            additionalInfo: [],
        );

        $response = Snap::virtualAccount()->using('report')->withBody($body)->send();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('responseCode', $response);
        $this->assertArrayHasKey('responseMessage', $response);
        $this->assertArrayHasKey('virtualAccountdata', $response);
        $this->assertArrayHasKey('paymentFlagReason', $response['virtualAccountdata'][0]);
        $this->assertArrayHasKey('partnerServiceId', $response['virtualAccountdata'][0]);
        $this->assertArrayHasKey('customerNo', $response['virtualAccountdata'][0]);
        $this->assertArrayHasKey('virtualAccountNo', $response['virtualAccountdata'][0]);
        $this->assertArrayHasKey('virtualAccountName', $response['virtualAccountdata'][0]);
        $this->assertArrayHasKey('virtualAccountEmail', $response['virtualAccountdata'][0]);
        $this->assertArrayHasKey('virtualAccountPhone', $response['virtualAccountdata'][0]);
        $this->assertArrayHasKey('billDetails', $response['virtualAccountdata'][0]);
        $this->assertArrayHasKey('billCode', $response['virtualAccountdata'][0]['billDetails'][0]);
        $this->assertArrayHasKey('billNo', $response['virtualAccountdata'][0]['billDetails'][0]);
        $this->assertArrayHasKey('billName', $response['virtualAccountdata'][0]['billDetails'][0]);
        $this->assertArrayHasKey('billShortName', $response['virtualAccountdata'][0]['billDetails'][0]);
        $this->assertArrayHasKey('billDescription', $response['virtualAccountdata'][0]['billDetails'][0]);
        $this->assertArrayHasKey('billSubCompany', $response['virtualAccountdata'][0]['billDetails'][0]);
        $this->assertArrayHasKey('billAmount', $response['virtualAccountdata'][0]['billDetails'][0]);
        $this->assertArrayHasKey('additionalInfo', $response['virtualAccountdata'][0]['billDetails'][0]);
        $this->assertArrayHasKey('status', $response['virtualAccountdata'][0]['billDetails'][0]);
    }

    #[Test]
    // inquiry status
    public function test_can_inquiry_status()
    {
        $body = new StatusPayload(
            partnerServiceId: '088899',
            customerNo: '12345678901234567890',
            virtualAccountNo: '08889912345678901234567890',
            inquiryRequestId: 'abcdef-123456-abcdef',
            paymentRequestId: 'abcdef-123456-abcdef',
            additionalInfo: [],
        );

        $response = Snap::virtualAccount()->using('status')->withBody($body)->send();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('responseCode', $response);
        $this->assertArrayHasKey('responseMessage', $response);
        $this->assertArrayHasKey('virtualAccountData', $response);
        $this->assertArrayHasKey('paymentFlagReason', $response['virtualAccountData']);
        $this->assertArrayHasKey('partnerServiceId', $response['virtualAccountData']);
        $this->assertArrayHasKey('customerNo', $response['virtualAccountData']);
        $this->assertArrayHasKey('virtualAccountNo', $response['virtualAccountData']);
        $this->assertArrayHasKey('inquiryRequestId', $response['virtualAccountData']);
        $this->assertArrayHasKey('paymentRequestId', $response['virtualAccountData']);
        $this->assertArrayHasKey('paidAmount', $response['virtualAccountData']);
        $this->assertArrayHasKey('paidBills', $response['virtualAccountData']);
        $this->assertArrayHasKey('totalAmount', $response['virtualAccountData']);
        $this->assertArrayHasKey('trxDateTime', $response['virtualAccountData']);
        $this->assertArrayHasKey('transactionDate', $response['virtualAccountData']);
        $this->assertArrayHasKey('referenceNo', $response['virtualAccountData']);
        $this->assertArrayHasKey('paymentType', $response['virtualAccountData']);
        $this->assertArrayHasKey('flagAdvise', $response['virtualAccountData']);
        $this->assertArrayHasKey('paymentFlagStatus', $response['virtualAccountData']);
        $this->assertArrayHasKey('billDetails', $response['virtualAccountData']);
        $this->assertArrayHasKey('freeTexts', $response['virtualAccountData']);
        $this->assertArrayHasKey('additionalInfo', $response);
    }
}
