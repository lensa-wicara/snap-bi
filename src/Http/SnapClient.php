<?php 

namespace LensaWicara\SnapBI\Http;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SnapClient
{
    /**
     * The http client
     *
     * @var PendingRequest|null
     */
    private ?PendingRequest $client;

    /**
     * Throw error
     *
     * @var bool
     */
    protected $throwError = true;

    public function __construct()
    {
        $this->client = new PendingRequest();
    }

    public function withHeaders(array $headers = [])
    {
        $this->client->withHeaders($headers)->acceptJson()->baseUrl(config('snap-bi.providers.aspi.base_url'));

        return $this;
    }

    public function get(string $endpoint)
    {
        return $this->client->get($endpoint);
    }

    public function post(string $endpoint, array $data = [])
    {
        return $this->client->post($endpoint, $data);
    }
    
    /**
     * Magic method to handle dynamic method calls.
     *
     * @param string $method The name of the method being called.
     * @param array $arguments The arguments passed to the method.
     * @return mixed The result of the method call.
     */
    public function __call($method, $arguments)
    {
        if (!$this->client) {
            $this->client = Http::acceptJson();
        }

        if ('withHeaders' === $method) {
            $this->client->withHeaders(...$arguments);
            return $this;
        }

        if (in_array($method, ['get', 'post', 'put', 'delete'])) {
            $response = $this->client->$method(...$arguments);

            $this->client = null;

            if ($this->throwError) {
                $response = $response->throw();
            }

            return $response;
        }

        $this->client->$method(...$arguments);
        
        return $this;
    }
}