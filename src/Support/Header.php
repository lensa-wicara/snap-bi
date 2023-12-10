<?php 

namespace LensaWicara\SnapBI\Support;

use Illuminate\Contracts\Support\Arrayable;

class Header implements Arrayable
{
    /**
     * Header constructor.
     *
     * @param string|null $contentType
     * @param string|null $clientKey
     * @param string|null $authorization
     * @param string|null $authorizationCustomer
     * @param string|null $timestamp
     * @param string|null $signature
     * @param string|null $origin
     * @param string|null $partnerId
     * @param string|null $externalId
     * @param string|null $ipAddress
     * @param string|null $deviceId
     * @param string|null $latitude
     * @param string|null $longitude
     * @param string|null $channelId
     */
    public function __construct(
        protected ?string $contentType = 'application/json',
        protected ?string $clientKey = null,
        protected ?string $authorization = null,
        protected ?string $authorizationCustomer = null,
        protected ?string $timestamp = null,
        protected ?string $signature = null,
        protected ?string $origin = null,
        protected ?string $partnerId = null,
        protected ?string $externalId = null,
        protected ?string $ipAddress = null,
        protected ?string $deviceId = null,
        protected ?string $latitude = null,
        protected ?string $longitude = null,
        protected ?string $channelId = null
    ) {
        // 
    }

    /**
     * Convert to array
     *
     * @return array
     */
    public function toArray()
    {
        $data = [
            'Content-Type' => $this->contentType,
            'Authorization' => $this->authorization,
            'Authorization-Customer' => $this->authorizationCustomer,
            'X-CLIENT-KEY' => $this->clientKey,
            'X-TIMESTAMP' => $this->timestamp,
            'X-SIGNATURE' => $this->signature,
            'X-ORIGIN' => $this->origin,
            'X-PARTNER-ID' => $this->partnerId,
            'X-EXTERNAL-ID' => $this->externalId,
            'X-IP-ADDRESS' => $this->ipAddress,
            'X-DEVICE-ID' => $this->deviceId,
            'X-LATITUDE' => $this->latitude,
            'X-LONGITUDE' => $this->longitude,
            'CHANNEL-ID' => $this->channelId,
        ];

        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * Get only several header item
     *
     * @param array $keys
     *
     * @return array
     */
    public function only(array $keys)
    {
        $keys = array_map(function ($value) {
            return strtolower($value);
        }, $keys);

        $headers = $this->toArray();
        $attributes = [];

        foreach ($headers as $key => $value) {
            if (in_array(strtolower($key), $keys)) {
                $attributes[$key] = $value;
            }
        }

        return $attributes;
    }
    
    /**
     * Get necessary header items for requesting access token
     *
     * @return array
     */
    public function onlyForAccessToken()
    {
        return $this->only(['content-type', 'x-client-key', 'x-timestamp', 'x-signature']);
    }
}