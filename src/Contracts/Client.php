<?php

namespace LensaWicara\SnapBI\Contracts;

interface Client
{
    public function withBody(array $body): self;
}
