<?php

namespace LensaWicara\SnapBI\Services\Payload\VirtualAccount;

use LensaWicara\SnapBI\Services\Payload\Amount;
use LensaWicara\SnapBI\Services\Payload\WithPayload;

class CreateVAPayload extends WithPayload
{
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
}
