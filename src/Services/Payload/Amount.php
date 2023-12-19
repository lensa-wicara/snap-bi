<?php

namespace LensaWicara\SnapBI\Services\Payload;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Number;

class Amount implements Arrayable
{
    /**
     * @var float
     */
    protected $amount;

    /**
     * @var string
     */
    protected $currency;

    public function __construct(float $amount, string $currency = 'IDR')
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public static function make(float $amount, string $currency = 'IDR'): static
    {
        return new static($amount, $currency);
    }

    /**
     * Get amount
     */
    public function getAmount(): string
    {
        return Number::format($this->amount, 2, null, $this->currency);
    }

    /**
     * Get currency
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Get amount and currency
     */
    public function getAmountAndCurrency(): string
    {
        return Number::currency($this->amount, $this->currency);
    }

    /**
     * to array
     */
    public function toArray(): array
    {
        return [
            // 'amount' => $this->getAmount(),
            'value' => $this->getAmount(),
            'currency' => $this->getCurrency(),
        ];
    }

    // when class is called as array
    public function __invoke(): array
    {
        return $this->toArray();
    }
}
