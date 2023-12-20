<?php

namespace LensaWicara\SnapBI\Services\Payload\VirtualAccount;

use LensaWicara\SnapBI\Services\Payload\Amount;
use LensaWicara\SnapBI\Services\Payload\WithPayload;

class InquiryPayload extends WithPayload
{
    public function __construct(
        public string $partnerServiceId,
        public string $customerNo,
        public string $virtualAccountNo,
        public string $txnDateInit,
        public int $channelCode,
        public string $language,
        public array|Amount $amount,
        public string $hashedSourceAccountNo,
        public string $sourceBankCode,
        public string $passApp,
        public string $inquiryRequestId,
        public array $additionalInfo = [],
    ) {
        $this->boot();
    }
}
