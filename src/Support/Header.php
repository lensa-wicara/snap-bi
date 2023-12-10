<?php

namespace LensaWicara\SnapBI\Support;

use Illuminate\Contracts\Support\Arrayable;

class Header implements Arrayable
{
    /**
     * Header constructor.
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
     * method 'make' is used to create new instance of Header
     *
     * @param array $attributes
     * @return static
     */
    public static function make(array $attributes)
    {
        return new static(
            data_get($attributes, 'content-type', 'application/json'),
            data_get($attributes, 'x-client-key', null),
            data_get($attributes, 'authorization', null),
            data_get($attributes, 'authorization-customer', null),
            data_get($attributes, 'x-timestamp', null),
            data_get($attributes, 'x-signature', null),
            data_get($attributes, 'x-origin', null),
            data_get($attributes, 'x-partner-id', null),
            data_get($attributes, 'x-external-id', null),
            data_get($attributes, 'x-ip-address', null),
            data_get($attributes, 'x-device-id', null),
            data_get($attributes, 'x-latitude', null),
            data_get($attributes, 'x-longitude', null),
            data_get($attributes, 'channel-id', null),
        );
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
