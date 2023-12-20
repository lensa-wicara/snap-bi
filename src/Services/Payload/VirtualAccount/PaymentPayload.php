<?php

namespace LensaWicara\SnapBI\Services\Payload\VirtualAccount;

use LensaWicara\SnapBI\Services\Payload\Amount;
use LensaWicara\SnapBI\Services\Payload\WithPayload;

class PaymentPayload extends WithPayload
{
    public function __construct(
        public string $partnerServiceId,
        public string $customerNo,
        public string $virtualAccountNo,
        public string $virtualAccountName,
        public string $virtualAccountEmail,
        public string $virtualAccountPhone,
        public string $trxId,
        public string $paymentRequestId,
        public int $channelCode,
        public string $hashedSourceAccountNo,
        public string $sourceBankCode,
        public array|Amount $paidAmount,
        public array|Amount $cumulativePaymentAmount,
        public string $paidBills,
        public array|Amount $totalAmount,
        public string $trxDateTime,
        public string $referenceNo,
        public string $journalNum,
        public int $paymentType,
        public string $flagAdvise,
        public string $subCompany,
        public array $billDetails,
        public array $freeTexts,
        public array $additionalInfo = [],
    ) {
        $this->boot();
    }
}
