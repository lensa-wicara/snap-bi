<?php

namespace LensaWicara\SnapBI\Services\Payload;

use Illuminate\Contracts\Support\Arrayable;
use ReflectionClass;

class WithPayload implements Arrayable
{
    protected array $payload = [];

    public function boot(): void
    {
        // make all of the properties value to be array (some of them are multi level array)
        // if there an object in the properties value then convert it to array
        $properties = (new ReflectionClass($this))->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);

            $value = $property->getValue($this);

            if (is_object($value)) {
                $property->setValue($this, $value->toArray());
            }
        }

        // set payload
        $this->payload = get_object_vars($this);
    }

    // get payload
    public function getPayload(): array
    {
        return $this->payload;
    }

    public function toArray()
    {
        return $this->getPayload();
    }
}
