<?php

namespace LensaWicara\SnapBI\Services\Payload\VirtualAccount;

use LensaWicara\SnapBI\Services\Payload\WithPayload;

class ReportPayload extends WithPayload
{
    // [
    //     'partnerServiceId' => '088899',
    //     'startDate' => '2020-12-31',
    //     'startTime' => '14:56:11+07:00',
    //     'endDate' => '2021-12-31',
    //     'endTime' => '14:56:11+07:00',
    //     'additionalInfo' => [],
    // ];
    public function __construct(
        public string $partnerServiceId,
        public string $startDate,
        public string $startTime,
        public string $endDate,
        public string $endTime,
        public array $additionalInfo = [],
    ) {
        $this->boot();
    }
}
