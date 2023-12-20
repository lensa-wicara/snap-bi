<?php

namespace LensaWicara\SnapBI\Services\Payload\VirtualAccount;

use Illuminate\Contracts\Support\Arrayable;
use LensaWicara\SnapBI\Services\Payload\Amount;
use ReflectionClass;

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
    /**
     * CreateVAPayload constructor.
     *
     * @param  string  $partnerServiceId The partner service ID.
     * @param  string  $customerNo The customer number.
     * @param  string  $virtualAccountNo The virtual account number.
     * @param  string  $virtualAccountName The virtual account name.
     * @param  string  $virtualAccountEmail The virtual account email.
     * @param  string  $virtualAccountPhone The virtual account phone.
     * @param  string  $trxId The transaction ID.
     * @param  array{value: string, currency: string}|object|\LensaWicara\SnapBI\Services\Payload\Amount  $totalAmount The total amount.
     * @param  array{billCode: string, billNo: string, billName: string, billShortName: string, billDescription: array{english: string, indonesia: string}, billSubCompany: string, billAmount: array{value: string, currency: string}, additionalInfo: array}[]  $billDetails The bill details.
     * @param  array{english: string, indonesia: string}[]  $freeTexts The free texts.
     * @param  string  $virtualAccountTrxType The virtual account transaction type.
     * @param  array{value: string, currency: string}|object|\LensaWicara\SnapBI\Services\Payload\Amount  $feeAmount The fee amount.
     * @param  string  $expiredDate The expiration date.
     * @param  array{deviceId: string, channel: string}  $additionalInfo The additional information.
     */
    public function __construct(
        public string $partnerServiceId,
        public string $customerNo,
        public string $virtualAccountNo,
        public string $virtualAccountName,
        public string $virtualAccountEmail,
        public string $virtualAccountPhone,
        public string $trxId,
        public array|Amount $totalAmount,
        public array $billDetails,
        public array $freeTexts,
        public string $virtualAccountTrxType,
        public array|Amount $feeAmount,
        public string $expiredDate,
        public array $additionalInfo
    ) {
        $this->boot();
    }

    public function boot(): void
    {
        // make all of the properties value to be array (some of them are multi level array)
        // if there an object in the properties value then convert it to array
        $properties = (new ReflectionClass($this))->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);

            $value = $property->getValue($this);

            if (is_object($value)) {
                $property->setValue($this, $value->toArray());
            }
        }

        // set payload
        $this->payload = get_object_vars($this);
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
