<?php

namespace LensaWicara\SnapBI\Contracts\TransferCredit;

interface VirtualAccount
{
    /**
     * Set body request
     *
     * @param array $body
     * @return self
     */
    public function withBody(array $body): self;

    /**
     * Set using to endpoint of request
     * 
     * @param string $using
     * @return self
     */
    public function using(string $using): self;

    /**
     * send request
     * 
     * @return mixed|self
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function send();
}
