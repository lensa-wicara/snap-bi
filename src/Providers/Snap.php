<?php

namespace LensaWicara\SnapBI\Providers;

use LensaWicara\SnapBI\Contracts\TransferCredit;
use LensaWicara\SnapBI\Services\VirtualAccount;

class Snap implements TransferCredit
{
    /**
     * Virtual account
     */
    public static function virtualAccount(): VirtualAccount
    {
        return new VirtualAccount();
    }
}
