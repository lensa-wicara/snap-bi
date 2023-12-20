<?php

namespace LensaWicara\SnapBI\Services\Payload\VirtualAccount;

use LensaWicara\SnapBI\Services\Payload\WithPayload;

class InquiryVAPayload extends WithPayload
{
    public function __construct(
        public string $partnerServiceId,
        public string $customerNo,
        public string $virtualAccountNo,
        public string $trxId,
    ) {
        $this->boot();
    }
}
