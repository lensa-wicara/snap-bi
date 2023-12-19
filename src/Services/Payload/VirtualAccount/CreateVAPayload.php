<?php

namespace LensaWicara\SnapBI\Services\Payload\VirtualAccount;

use Illuminate\Contracts\Support\Arrayable;
use LensaWicara\SnapBI\Services\Payload\Amount;

// make a payload class for virtual account
// using below array
// [
//     'partnerServiceId' => '088899',
//     'customerNo' => '12345678901234567890',
//     'virtualAccountNo' => '08889912345678901234567890',
//     'virtualAccountName' => 'Jokul Doe',
//     'virtualAccountEmail' => 'jokul@email.com',
//     'virtualAccountPhone' => '6281828384858',
//     'trxId' => 'abcdefgh1234',
//     'totalAmount' => [
//         'value' => '12345678.00',
//         'currency' => 'IDR',
//     ],
//     'billDetails' => [
//         [
//             'billCode' => '01',
//             'billNo' => '123456789012345678',
//             'billName' => 'Bill A for Jan',
//             'billShortName' => 'Bill A',
//             'billDescription' => [
//                 'english' => 'Maintenance',
//                 'indonesia' => 'Pemeliharaan',
//             ],
//             'billSubCompany' => '00001',
//             'billAmount' => [
//                 'value' => '12345678.00',
//                 'currency' => 'IDR',
//             ],
//             'additionalInfo' => [],
//         ],
//     ],
//     'freeTexts' => [
//         [
//             'english' => 'Free text',
//             'indonesia' => 'Tulisan bebas',
//         ],
//     ],
//     'virtualAccountTrxType' => '1',
//     'feeAmount' => [
//         'value' => '12345678.00',
//         'currency' => 'IDR',
//     ],
//     'expiredDate' => '2020-12-31T23:59:59-07:00',
//     'additionalInfo' => [
//         'deviceId' => '12345679237',
//         'channel' => 'mobilephone',
//     ],
// ];

class CreateVAPayload implements Arrayable
{
    // payload
    public array $payload = [];

    // create new instance
    public function __construct(
        public string $partnerServiceId,
        public string $customerNo,
        public string $virtualAccountNo,
        public string $virtualAccountName,
        public string $virtualAccountEmail,
        public string $virtualAccountPhone,
        public string $trxId,
        public Amount $totalAmount,
        public array $billDetails,
        public array $freeTexts,
        public string $virtualAccountTrxType,
        public Amount $feeAmount,
        public string $expiredDate,
        public array $additionalInfo
    ) {
        $this->payload = [
            'partnerServiceId' => $partnerServiceId,
            'customerNo' => $customerNo,
            'virtualAccountNo' => $virtualAccountNo,
            'virtualAccountName' => $virtualAccountName,
            'virtualAccountEmail' => $virtualAccountEmail,
            'virtualAccountPhone' => $virtualAccountPhone,
            'trxId' => $trxId,
            'totalAmount' => $totalAmount->toArray(),
            'billDetails' => $billDetails,
            'freeTexts' => $freeTexts,
            'virtualAccountTrxType' => $virtualAccountTrxType,
            'feeAmount' => $feeAmount->toArray(),
            'expiredDate' => $expiredDate,
            'additionalInfo' => $additionalInfo,
        ];
    }

    // get payload
    public function getPayload(): array
    {
        return $this->payload;
    }

    public function toArray()
    {
        return $this->getPayload();
    }
}
