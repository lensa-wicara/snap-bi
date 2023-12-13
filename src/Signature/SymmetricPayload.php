<?php

namespace LensaWicara\SnapBI\Signature;

class SymmetricPayload
{
    /**
     * Create symmetric payload
     *
     * @param  string  $payload
     */
    public function __construct(
        protected string $method,
        protected string $endpointUrl,
        protected string $accessToken,
        protected string $timestamp,
        protected string|array $payload = '',
    ) {
        //
    }

    /**
     * Get string representation of payload
     *
     * @return string
     */
    public function __toString()
    {
        $payload = is_array($this->payload) ? json_encode($this->payload) : $this->payload;
        $minified = hash('sha256', $payload);

        $stringToSign = implode(':', [
            $this->method,
            $this->endpointUrl,
            $this->accessToken,
            $minified,
            (string) $this->timestamp ?? now()->toIso8601String(),
        ]);

        return $stringToSign;
    }
}
