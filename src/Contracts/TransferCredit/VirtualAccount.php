<?php

namespace LensaWicara\SnapBI\Contracts\TransferCredit;

interface VirtualAccount
{
    /**
     * Set body request
     */
    public function withBody(array $body): self;

    /**
     * Set using to endpoint of request
     */
    public function using(string $using): self;

    /**
     * send request
     *
     * @return mixed|self
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function send();
}
