<?php

namespace LensaWicara\SnapBI\Signature;

class AsymmetricPayload
{
    public function __construct(
        protected string $clientkey,
    ) {
        //
    }

    // to string
    public function __toString(): string
    {
        return $this->clientkey.'|'.now()->toIso8601String();
    }
}
