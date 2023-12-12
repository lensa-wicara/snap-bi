<?php 

namespace LensaWicara\SnapBI\Support;

class Timestamp
{
    /**
     * timestamp
     *
     * @var string
     */
    protected string $timestamp;

    /**
     * Get timestamp
     *
     * @return string
     */
    public function __construct($timestamp = null) {
        $this->timestamp = $timestamp ?? now()->toIso8601String();
    }
    
    // to string
    public function __toString()
    {
        return $this->timestamp;
    }
}