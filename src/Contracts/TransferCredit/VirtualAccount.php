<?php

namespace LensaWicara\SnapBI\Contracts\TransferCredit;

interface VirtualAccount
{
    public function withBody(array $body): self;

    public function using(string $using): self;

    public function send(): mixed;
}
