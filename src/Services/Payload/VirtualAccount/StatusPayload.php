<?php

namespace LensaWicara\SnapBI\Services\Payload\VirtualAccount;

use LensaWicara\SnapBI\Services\Payload\WithPayload;

class StatusPayload extends WithPayload
{
    // [
    //     'partnerServiceId' => '088899',
    //     'customerNo' => '12345678901234567890',
    //     'virtualAccountNo' => '08889912345678901234567890',
    //     'inquiryRequestId' => 'abcdef-123456-abcdef',
    //     'paymentRequestId' => 'abcdef-123456-abcdef',
    //     'additionalInfo' => [],
    // ];
    public function __construct(
        public string $partnerServiceId,
        public string $customerNo,
        public string $virtualAccountNo,
        public string $inquiryRequestId,
        public string $paymentRequestId,
        public array $additionalInfo = [],
    ) {
        $this->boot();
    }
}
